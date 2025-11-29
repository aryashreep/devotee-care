<?php
// app/models/BhaktiSadan.php

namespace App\Models;

use App\Core\BaseModel;
use PDO;

class BhaktiSadan extends BaseModel {
    protected $table = 'bhakti_sadans';

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (name, address) VALUES (:name, :address)";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute(['name' => $data['name'], 'address' => $data['address']])) {
            return $this->db->lastInsertId();
        }
        return false;
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
        $sql = "UPDATE {$this->table} SET name = :name, address = :address WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id, 'name' => $data['name'], 'address' => $data['address']]);
    }

    public function getLeaders($bhaktiSadanId) {
        $sql = "SELECT u.* FROM users u JOIN bhakti_sadan_leaders bsl ON u.id = bsl.user_id WHERE bsl.bhakti_sadan_id = :bhakti_sadan_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['bhakti_sadan_id' => $bhaktiSadanId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function assignLeaders($bhaktiSadanId, $leaderIds) {
        // First, remove existing leaders to prevent duplicates
        $this->removeLeaders($bhaktiSadanId);

        $sql = "INSERT INTO bhakti_sadan_leaders (user_id, bhakti_sadan_id) VALUES (:user_id, :bhakti_sadan_id)";
        $stmt = $this->db->prepare($sql);

        foreach ($leaderIds as $userId) {
            $stmt->execute(['user_id' => $userId, 'bhakti_sadan_id' => $bhaktiSadanId]);
        }
    }

    public function removeLeaders($bhaktiSadanId) {
        $sql = "DELETE FROM bhakti_sadan_leaders WHERE bhakti_sadan_id = :bhakti_sadan_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['bhakti_sadan_id' => $bhaktiSadanId]);
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
