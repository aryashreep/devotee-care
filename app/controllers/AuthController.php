<?php
// app/controllers/AuthController.php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\User;
use App\Models\BhaktiSadan;
use App\Models\Lookup;

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
        $step = isset($_GET['step']) ? (int)$_GET['step'] : 1;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!validate_csrf_token($_POST['csrf_token'])) {
                showError('Invalid CSRF token.', 403);
            }

            $_SESSION['registration_data'] = array_merge($_SESSION['registration_data'] ?? [], $_POST);

            $nextStep = $step + 1;
            if ($nextStep > 3) {
                // Final step, process the data
                $this->processRegistration();
            } else {
                header('Location: ' . url('register', ['step' => $nextStep]));
                exit;
            }
        } else {
            $data = $this->_get_registration_data();
            $data['step'] = $step;
            $data['csrf_token'] = csrf_token();
            echo $this->view('auth/register', $data);
        }
    }

    private function processRegistration() {
        $userModel = new User();
        $data = $_SESSION['registration_data'];

        // Handle file upload
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $targetDir = "uploads/photos/";
            $fileName = basename($_FILES["photo"]["name"]);
            $targetFilePath = $targetDir . $fileName;
            move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath);
            $data['photo'] = $targetFilePath;
        }

        $userId = $userModel->create($data);

        if ($userId) {
            // Handle multiple selections
            $userModel->assignLanguages($userId, $data['languages']);
            $userModel->assignSevas($userId, $data['sevas']);
            $userModel->addDependants($userId, $data['dependants']);

            unset($_SESSION['registration_data']);
            header("Location: " . url('login', ['success' => 1]));
            exit;
        } else {
            $data = $this->_get_registration_data();
            $data['error'] = 'Registration failed. The mobile number or email may already be in use.';
            $data['step'] = 3; // Go back to the last step
            echo $this->view('auth/register', $data);
        }
    }

    private function _get_registration_data() {
        $bhaktiSadanModel = new BhaktiSadan();
        $educationModel = new Lookup('educations');
        $professionModel = new Lookup('professions');
        $languageModel = new Lookup('languages');
        $sevaModel = new Lookup('sevas');

        return [
            'bhaktiSadans' => $bhaktiSadanModel->getAll(),
            'educations' => $educationModel->getAll(),
            'professions' => $professionModel->getAll(),
            'languages' => $languageModel->getAll(),
            'sevas' => $sevaModel->getAll(),
        ];
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
