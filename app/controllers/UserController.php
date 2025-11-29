<?php
// app/controllers/UserController.php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\User;
use App\Models\BhaktiSadan;
use App\Models\Role;

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
        $isLeader = $this->userModel->isBhaktiSadanLeader($_SESSION['user_id']);

        if ($_SESSION['user_role'] === 'Admin') {
            $users = $this->userModel->getAll();
        } elseif ($isLeader) {
            $user = $this->userModel->findById($_SESSION['user_id']);
            $users = $this->userModel->getUsersByBhaktiSadan($user['bhakti_sadan_id']);
        } else {
            showError('Forbidden: You do not have permission to access this page.', 403);
        }

        $bhaktiSadanModel = new BhaktiSadan();
        $bhaktiSadans = $bhaktiSadanModel->getAll();

        $data['users'] = $users;
        $data['bhaktiSadans'] = $bhaktiSadans;
        echo $this->view('dashboard/users', $data);
    }

    public function updateBhaktiSadan() {
        if ($_SESSION['user_role'] !== 'Admin') {
            showError('Forbidden: You do not have permission to access this page.', 403);
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_POST['user_id'];
            $bhaktiSadanId = $_POST['bhakti_sadan_id'];

            if ($this->userModel->update($userId, ['bhakti_sadan_id' => $bhaktiSadanId])) {
                header('Location: ' . url('users'));
                exit;
            } else {
                showError('Failed to update Bhakti Sadan.', 500);
            }
        }
    }

    public function delete($id) {
        if ($_SESSION['user_role'] !== 'Admin') {
            showError('Forbidden: You do not have permission to access this page.', 403);
        }

        if ($this->userModel->delete($id)) {
            header('Location: ' . url('users'));
            exit;
        } else {
            showError('Failed to delete user.', 500);
        }
    }

    public function edit($id) {
        if ($_SESSION['user_role'] !== 'Admin') {
            showError('Forbidden: You do not have permission to access this page.', 403);
        }

        $user = $this->userModel->findById($id);
        $roleModel = new Role();
        $roles = $roleModel->getAll();
        $bhaktiSadanModel = new BhaktiSadan();
        $bhaktiSadans = $bhaktiSadanModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'full_name' => trim($_POST['full_name']),
                'mobile_number' => trim($_POST['mobile_number']),
                'email' => trim($_POST['email']),
                'role_id' => $_POST['role_id'],
                'bhakti_sadan_id' => $_POST['bhakti_sadan_id']
            ];

            if ($this->userModel->update($id, $data)) {
                header('Location: ' . url('users'));
                exit;
            } else {
                $data['error'] = 'Failed to update user.';
            }
        }

        $data['user'] = $user;
        $data['roles'] = $roles;
        $data['bhaktiSadans'] = $bhaktiSadans;

        echo $this->view('dashboard/user_edit', $data);
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
