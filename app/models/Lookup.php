<?php
// app/models/Lookup.php

namespace App\Models;

use App\Core\BaseModel;
use PDO;

class Lookup extends BaseModel {

    public function __construct($table) {
        parent::__construct();
        $this->table = $table;
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['name' => $data['name']]);
    }

    public function findById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
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
