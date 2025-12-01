<?php
// app/models/User.php

namespace App\Models;

use App\Core\BaseModel;
use PDO;

class User extends BaseModel {
    protected $table = 'users';

    // --- Add a fillable property to whitelist columns ---
    private $fillable = [
        'full_name', 'gender', 'photo', 'date_of_birth', 'marital_status',
        'marriage_anniversary_date', 'password', 'email', 'mobile_number',
        'address', 'city', 'state', 'pincode', 'country', 'education_id',
        'profession_id', 'blood_group_id', 'is_initiated', 'spiritual_master_name',
        'chanting_rounds', 'second_initiation', 'bhakti_sadan_id',
        'has_life_membership', 'life_member_no', 'life_member_temple', 'role_id'
    ];

    public function create($data) {
        // --- Filter the incoming data to only include fillable columns ---
        $filteredData = array_filter(
            $data,
            fn($key) => in_array($key, $this->fillable),
            ARRAY_FILTER_USE_KEY
        );

        // --- Prepare data for execution ---
        $filteredData['password'] = password_hash($filteredData['password'], PASSWORD_BCRYPT);
        if (!isset($filteredData['role_id'])) {
            $filteredData['role_id'] = 5; // Default to 'End User'
        }

        // --- Dynamically build the SQL query from the filtered data ---
        $columns = array_keys($filteredData);
        $placeholders = array_map(fn($col) => ":{$col}", $columns);

        $sql = sprintf(
            "INSERT INTO {$this->table} (%s) VALUES (%s)",
            implode(', ', $columns),
            implode(', ', $placeholders)
        );

        $stmt = $this->db->prepare($sql);

        try {
            if ($stmt->execute($filteredData)) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) { // Integrity constraint violation (e.g., duplicate mobile)
                return false;
            }
            throw $e;
        }
    }

    public function assignLanguages($userId, $languageIds) {
        $sql = "INSERT INTO user_languages (user_id, language_id) VALUES (:user_id, :language_id)";
        $stmt = $this->db->prepare($sql);
        foreach ($languageIds as $languageId) {
            $stmt->execute(['user_id' => $userId, 'language_id' => $languageId]);
        }
    }

    public function assignSevas($userId, $sevaIds) {
        $sql = "INSERT INTO user_sevas (user_id, seva_id) VALUES (:user_id, :seva_id)";
        $stmt = $this->db->prepare($sql);
        foreach ($sevaIds as $sevaId) {
            $stmt->execute(['user_id' => $userId, 'seva_id' => $sevaId]);
        }
    }

    public function assignShikshaLevels($userId, $levelIds) {
        $sql = "INSERT INTO user_shiksha_levels (user_id, shiksha_level_id) VALUES (:user_id, :shiksha_level_id)";
        $stmt = $this->db->prepare($sql);
        foreach ($levelIds as $levelId) {
            $stmt->execute(['user_id' => $userId, 'shiksha_level_id' => $levelId]);
        }
    }

    public function addDependants($userId, $dependants) {
        $sql = "INSERT INTO dependants (user_id, name, age, gender, date_of_birth) VALUES (:user_id, :name, :age, :gender, :date_of_birth)";
        $stmt = $this->db->prepare($sql);
        foreach ($dependants as $dependant) {
             if (empty($dependant['name'])) continue; // Skip empty dependant entries
            $stmt->execute([
                'user_id' => $userId,
                'name' => $dependant['name'],
                'age' => !empty($dependant['age']) ? $dependant['age'] : null,
                'gender' => $dependant['gender'],
                'date_of_birth' => !empty($dependant['date_of_birth']) ? $dependant['date_of_birth'] : null,
            ]);
        }
    }

    public function findByMobile($mobileNumber) {
        $sql = "SELECT u.*, r.role_name FROM {$this->table} u JOIN roles r ON u.role_id = r.id WHERE u.mobile_number = :mobile_number";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['mobile_number' => $mobileNumber]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $sql = "SELECT u.*, r.role_name FROM {$this->table} u JOIN roles r ON u.role_id = r.id WHERE u.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $sql = "SELECT u.*, r.role_name, bs.name as bhakti_sadan_name
                FROM {$this->table} u
                JOIN roles r ON u.role_id = r.id
                LEFT JOIN bhakti_sadans bs ON u.bhakti_sadan_id = bs.id";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $allowedColumns = ['full_name', 'mobile_number', 'email', 'password', 'bhakti_sadan_id', 'role_id'];
        $fields = [];
        $updateData = [];

        foreach ($allowedColumns as $column) {
            if (array_key_exists($column, $data)) {
                $fields[] = "{$column} = :{$column}";
                $updateData[$column] = $data[$column];
            }
        }

        if (empty($fields)) return false;

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $updateData['id'] = $id;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($updateData);
    }

    public function isBhaktiSadanLeader($userId) {
        $sql = "SELECT COUNT(*) FROM bhakti_sadan_leaders WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchColumn() > 0;
    }

    public function getUsersByBhaktiSadan($bhaktiSadanId) {
        $sql = "SELECT u.*, r.role_name, bs.name as bhakti_sadan_name
                FROM {$this->table} u
                JOIN roles r ON u.role_id = r.id
                LEFT JOIN bhakti_sadans bs ON u.bhakti_sadan_id = bs.id
                WHERE u.bhakti_sadan_id = :bhakti_sadan_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['bhakti_sadan_id' => $bhaktiSadanId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        // Clean up related data first
        $this->db->prepare("DELETE FROM user_languages WHERE user_id = :id")->execute(['id' => $id]);
        $this->db->prepare("DELETE FROM user_sevas WHERE user_id = :id")->execute(['id' => $id]);
        $this->db->prepare("DELETE FROM user_shiksha_levels WHERE user_id = :id")->execute(['id' => $id]);
        $this->db->prepare("DELETE FROM dependants WHERE user_id = :id")->execute(['id' => $id]);
        $this->db->prepare("DELETE FROM bhakti_sadan_leaders WHERE user_id = :id")->execute(['id' => $id]);

        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
