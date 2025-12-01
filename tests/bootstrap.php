<?php
// tests/bootstrap.php

// Define constants for the test environment
if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
}
if (!defined('DB_USER')) {
    define('DB_USER', 'test_user');
}
if (!defined('DB_PASS')) {
    define('DB_PASS', 'test_pass');
}
if (!defined('DB_NAME')) {
    define('DB_NAME', 'test_db');
}

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/../vendor/autoload.php';
