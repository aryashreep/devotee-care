<?php
// app/models/Role.php

namespace App\Models;

use App\Core\BaseModel;
use PDO;

class Role extends BaseModel {
    protected $table = 'roles';

    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
