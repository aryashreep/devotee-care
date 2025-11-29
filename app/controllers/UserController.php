<?php
// app/controllers/UserController.php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\User;

class UserController extends BaseController {

    private $userModel;

    public function __construct() {
        // Redirect to login if user is not authenticated
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . url('login'));
            exit;
        }
        $this->userModel = new User();
    }

    /**
     * Display the user management page (Admin only).
     */
    public function index() {
        // RBAC Check: Allow only 'Admin'
        if ($_SESSION['user_role'] !== 'Admin') {
            showError('Forbidden: You do not have permission to access this page.', 403);
        }

        $users = $this->userModel->getAll();
        $data['users'] = $users;
        echo $this->view('dashboard/users', $data);
    }

    /**
     * Display and handle updates for the user's own profile.
     */
    public function profile() {
        $userId = $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Basic input validation
            $errors = [];
            if (empty($_POST['full_name'])) {
                $errors[] = 'Full name is required.';
            }
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'A valid email is required.';
            }
            if (empty($_POST['mobile_number'])) {
                $errors[] = 'Mobile number is required.';
            }
            if (!empty($_POST['password']) && strlen($_POST['password']) < 8) {
                $errors[] = 'Password must be at least 8 characters long.';
            }

            if (!empty($errors)) {
                $data['error'] = implode('<br>', $errors);
            } else {
                // Handle profile update
                $updateData = [
                    'full_name' => trim($_POST['full_name']),
                    'mobile_number' => trim($_POST['mobile_number']),
                    'email' => trim($_POST['email'])
                ];

                // Only update password if a new one is provided
                if (!empty($_POST['password'])) {
                    $updateData['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
                }

                if ($this->userModel->update($userId, $updateData)) {
                    $data['success'] = true;
                } else {
                    $data['error'] = 'Failed to update profile. The mobile number or email may already be in use.';
                }
            }
        }

        $user = $this->userModel->findById($userId);
        $data['user'] = $user;
        echo $this->view('dashboard/profile', $data);
    }
}
