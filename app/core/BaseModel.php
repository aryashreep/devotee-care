<?php
// app/core/BaseModel.php

namespace App\Core;

abstract class BaseModel {
    protected $db;
    protected $table;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
}
