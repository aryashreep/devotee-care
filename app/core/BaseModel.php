<?php
// app/core/BaseModel.php

namespace App\Core;

abstract class BaseModel {
    protected $db;
    protected $table;

    public function __construct($db = null) {
        if ($db) {
            $this->db = $db;
        } else {
            $this->db = Database::getInstance()->getConnection();
        }
    }
}
