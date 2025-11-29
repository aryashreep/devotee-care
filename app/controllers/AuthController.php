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

        // Validate passwords
        if ($data['password'] !== $data['confirm_password']) {
            $data = array_merge($data, $this->_get_registration_data());
            $data['error'] = 'Passwords do not match.';
            $data['step'] = 1; // Go back to the first step
            echo $this->view('auth/register', $data);
            return;
        }

        // Handle file upload
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $targetDir = "uploads/photos/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $fileName = uniqid() . '_' . basename($_FILES["photo"]["name"]);
            $targetFilePath = $targetDir . $fileName;
            move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath);
            $data['photo'] = $targetFilePath;
        }

        // Prepare data for insertion
        $userData = [
            'full_name' => $data['full_name'],
            'initiated_name' => $data['initiated_name'] ?? null,
            'gender' => $data['gender'],
            'photo' => $data['photo'] ?? null,
            'date_of_birth' => $data['date_of_birth'],
            'marital_status' => $data['marital_status'],
            'marriage_anniversary_date' => $data['marriage_anniversary_date'] ?? null,
            'email' => $data['email'] ?? null,
            'mobile_number' => $data['mobile_number'],
            'address' => $data['address'],
            'city' => $data['city'],
            'state' => $data['state'],
            'pincode' => $data['pincode'],
            'country' => $data['country'],
            'education_id' => $data['education_id'],
            'profession_id' => $data['profession_id'],
            'bhakti_sadan_id' => $data['bhakti_sadan_id'],
            'life_member_no' => $data['life_member_no'] ?? null,
            'life_member_temple' => $data['life_member_temple'] ?? null,
            'password' => $data['password'],
        ];

        $userId = $userModel->create($userData);

        if ($userId) {
            // Handle multiple selections
            if (!empty($data['languages'])) {
                $userModel->assignLanguages($userId, $data['languages']);
            }
            if (!empty($data['sevas'])) {
                $userModel->assignSevas($userId, $data['sevas']);
            }
            if (!empty($data['dependants'])) {
                $userModel->addDependants($userId, $data['dependants']);
            }

            unset($_SESSION['registration_data']);
            header("Location: " . url('login', ['success' => 1]));
            exit;
        } else {
            $data = array_merge($data, $this->_get_registration_data());
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
