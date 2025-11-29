<?php
// app/controllers/AuthController.php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\User;
use App\Models\BhaktiSadan;

class AuthController extends BaseController {

    // No constructor needed now

    /**
     * Handles user login.
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = new User();
            $user = $userModel->findByMobile($_POST['mobile_number']);

            if ($user && password_verify($_POST['password'], $user['password'])) {
                // Store user data in session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role_name'];

                // Redirect to dashboard
                header("Location: " . url('dashboard'));
                exit;
            } else {
                // Show login form with an error message
                $data['error'] = 'Invalid mobile number or password.';
                echo $this->view('auth/login', $data);
            }
        } else {
            // Show the login form
            echo $this->view('auth/login');
        }
    }

    /**
     * Handles user registration.
     */
    public function register() {
        $bhaktiSadanModel = new BhaktiSadan();
        $data['bhaktiSadans'] = $bhaktiSadanModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!validate_csrf_token($_POST['csrf_token'])) {
                showError('Invalid CSRF token.', 403);
            }

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
            if (strlen($_POST['password']) < 8) {
                $errors[] = 'Password must be at least 8 characters long.';
            }

            if (!empty($errors)) {
                $data['error'] = implode('<br>', $errors);
                echo $this->view('auth/register', $data);
                return;
            }

            $userModel = new User();

            $userData = [
                'full_name' => trim($_POST['full_name']),
                'mobile_number' => trim($_POST['mobile_number']),
                'email' => trim($_POST['email']),
                'password' => $_POST['password'],
                'role_id' => 5, // Default to 'End User' role on registration
                'bhakti_sadan_id' => $_POST['bhakti_sadan_id']
            ];

            if ($userModel->create($userData)) {
                // Redirect to login page with a success message
                header("Location: ". url('login', ['success' => 1]));
                exit;
            } else {
                // Show registration form with an error
                $data['error'] = 'Registration failed. The mobile number or email may already be in use.';
                echo $this->view('auth/register', $data);
            }
        } else {
            // Show the registration form
            $data['csrf_token'] = csrf_token();
            echo $this->view('auth/register', $data);
        }
    }

    /**
     * Handles user logout.
     */
    public function logout() {
        session_destroy();
        header("Location: " . url('login'));
        exit;
    }
}
