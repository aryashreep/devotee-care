<?php
// app/models/User.php

namespace App\Models;

use App\Core\BaseModel;
use PDO;

class User extends BaseModel {
    protected $table = 'users';

    /**
     * Creates a new user in the database.
     * @param array $data User data (full_name, mobile_number, email, password, role_id, bhakti_sadan_id)
     * @return bool True on success, false on failure.
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (full_name, mobile_number, email, password, role_id, bhakti_sadan_id) VALUES (:full_name, :mobile_number, :email, :password, :role_id, :bhakti_sadan_id)";
        $stmt = $this->db->prepare($sql);

        // Hash the password before storing it
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        return $stmt->execute($data);
    }

    /**
     * Finds a user by their mobile number.
     * @param string $mobileNumber
     * @return mixed User data as an associative array or false if not found.
     */
    public function findByMobile($mobileNumber) {
        $sql = "SELECT u.*, r.role_name FROM {$this->table} u JOIN roles r ON u.role_id = r.id WHERE u.mobile_number = :mobile_number";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['mobile_number' => $mobileNumber]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Finds a user by their ID.
     * @param int $id
     * @return mixed User data as an associative array or false if not found.
     */
    public function findById($id) {
        $sql = "SELECT u.*, r.role_name FROM {$this->table} u JOIN roles r ON u.role_id = r.id WHERE u.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Fetches all users from the database.
     * @return array An array of all users.
     */
    public function getAll() {
        $sql = "SELECT u.*, r.role_name, bs.name as bhakti_sadan_name
                FROM {$this->table} u
                JOIN roles r ON u.role_id = r.id
                LEFT JOIN bhakti_sadans bs ON u.bhakti_sadan_id = bs.id";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Updates a user's profile information.
     * @param int $id The user's ID.
     * @param array $data The data to update.
     * @return bool True on success, false on failure.
     */
    public function update($id, $data) {
        // Whitelist of columns that can be updated
        $allowedColumns = ['full_name', 'mobile_number', 'email', 'password', 'bhakti_sadan_id', 'role_id'];

        $fields = [];
        $updateData = [];

        foreach ($allowedColumns as $column) {
            if (array_key_exists($column, $data)) {
                $fields[] = "{$column} = :{$column}";
                $updateData[$column] = $data[$column];
            }
        }

        if (empty($fields)) {
            // Nothing to update
            return false;
        }

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
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
