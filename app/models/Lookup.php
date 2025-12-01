<?php
// app/models/Lookup.php

namespace App\Models;

use App\Core\BaseModel;
use PDO;

class Lookup extends BaseModel {
    // Table name can now be passed to methods instead of constructor
    protected $table;

    public function __construct($db = null) {
        parent::__construct($db);
    }

    public function create($table, $data) {
        $sql = "INSERT INTO {$table} (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['name' => $data['name']]);
    }

    public function findById($table, $id) {
        $sql = "SELECT * FROM {$table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll($table) {
        // Basic validation to allow only specific lookup tables
        $allowedTables = ['blood_groups', 'educations', 'professions', 'languages', 'shiksha_levels', 'sevas', 'bhakti_sadans', 'roles'];
        if (!in_array($table, $allowedTables)) {
            // Or throw an exception, depending on error handling strategy
            return [];
        }
        $sql = "SELECT * FROM {$table}";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET name = :name WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id, 'name' => $data['name']]);
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
