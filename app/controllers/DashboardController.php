<?php
// app/controllers/DashboardController.php

namespace App\Controllers;

use App\Core\BaseController;

class DashboardController extends BaseController {

    public function __construct() {
        // Redirect to login if user is not authenticated
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . url('login'));
            exit;
        }
    }

    public function index() {
        echo $this->view('dashboard/index');
    }
}
