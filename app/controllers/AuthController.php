<?php
// app/controllers/AuthController.php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\User;
use App\Models\BhaktiSadan;
use App\Models\Lookup;

class AuthController extends BaseController {

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = new User();
            $user = $userModel->findByMobile($_POST['mobile_number']);

            if ($user && password_verify($_POST['password'], $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role_name'];
                header("Location: " . url('dashboard'));
                exit;
            } else {
                $data['error'] = 'Invalid mobile number or password.';
                echo $this->view('auth/login', $data);
            }
        } else {
            echo $this->view('auth/login');
        }
    }

    public function register() {
        $step = isset($_GET['step']) ? (int)$_GET['step'] : 1;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!validate_csrf_token($_POST['csrf_token'])) {
                showError('Invalid CSRF token.', 403);
            }

            // --- START: IMMEDIATE FILE UPLOAD FIX ---
            $_SESSION['registration_data'] = array_merge($_SESSION['registration_data'] ?? [], $_POST);

            // If a photo is uploaded in this step, process it immediately
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
                $photoPath = $this->handlePhotoUpload($_FILES['photo']);
                if ($photoPath) {
                    // Store the permanent path in the session
                    $_SESSION['registration_data']['photo'] = $photoPath;
                } else {
                    // If upload fails, the handlePhotoUpload method will show the error and stop execution
                    return;
                }
            }
            // --- END: IMMEDIATE FILE UPLOAD FIX ---

            $nextStep = $step + 1;
            if ($nextStep > 5) {
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

        // --- VALIDATION ---
        if ($data['password'] !== $data['confirm_password']) {
            $this->returnWithError('Passwords do not match.', 1);
        }
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->returnWithError('Please enter a valid email address.', 2);
        }
        if (!preg_match('/^[0-9]{10}$/', $data['mobile_number'])) {
            $this->returnWithError('Mobile number must be exactly 10 digits.', 2);
        }

        // --- PREPARE USER DATA ---
        $userData = [
            'full_name' => $data['full_name'],
            'gender' => $data['gender'],
            'photo' => $data['photo'] ?? null, // Get the permanent path from session data
            'date_of_birth' => $data['date_of_birth'],
            'marital_status' => $data['marital_status'],
            'marriage_anniversary_date' => !empty($data['marriage_anniversary_date']) ? $data['marriage_anniversary_date'] : null,
            'password' => $data['password'],
            'email' => $data['email'] ?? null,
            'mobile_number' => $data['mobile_number'],
            'address' => $data['address'],
            'city' => $data['city'],
            'state' => $data['state'],
            'pincode' => $data['pincode'],
            'country' => $data['country'] ?? 'India',
            'education_id' => $data['education_id'],
            'profession_id' => $data['profession_id'],
            'is_initiated' => $data['is_initiated'] ?? null,
            'spiritual_master_name' => ($data['is_initiated'] === 'Yes') ? ($data['spiritual_master_name'] ?? null) : null,
            'chanting_rounds' => ($data['is_initiated'] === 'No') ? ($data['chanting_rounds'] ?? null) : null,
            'second_initiation' => $data['second_initiation'] ?? null,
            'bhakti_sadan_id' => $data['bhakti_sadan_id'],
            'has_life_membership' => $data['has_life_membership'] ?? null,
            'life_member_no' => ($data['has_life_membership'] === 'Yes') ? ($data['life_member_no'] ?? null) : null,
            'life_member_temple' => ($data['has_life_membership'] === 'Yes') ? ($data['life_member_temple'] ?? null) : null,
        ];

        $userId = $userModel->create($userData);

        if ($userId) {
            if (!empty($data['languages'])) $userModel->assignLanguages($userId, $data['languages']);
            if (!empty($data['sevas'])) $userModel->assignSevas($userId, $data['sevas']);
            if (!empty($data['shiksha_levels'])) $userModel->assignShikshaLevels($userId, $data['shiksha_levels']);
            if (!empty($data['dependants'])) $userModel->addDependants($userId, $data['dependants']);

            unset($_SESSION['registration_data'], $_SESSION['registration_files']);
            header("Location: " . url('login', ['success' => 1]));
            exit;
        } else {
            $this->returnWithError('Registration failed. The mobile number or email may already be in use.', 5);
        }
    }

    private function handlePhotoUpload($file) {
        $maxFileSize = 2 * 1024 * 1024;
        if ($file['size'] > $maxFileSize) {
            $this->returnWithError('Error: File size is larger than the allowed limit of 2MB.', 1);
            return false;
        }

        $targetDir = "uploads/photos/";
        $fileName = uniqid() . '_' . basename($file["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png'];

        if (!in_array($fileType, $allowedTypes)) {
            $this->returnWithError('Error: Only JPG, JPEG, and PNG files are allowed.', 1);
            return false;
        }

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $source_image = $file["tmp_name"];
        $image_info = getimagesize($source_image);
        $image_mime = $image_info['mime'];
        $image = ($image_mime == 'image/jpeg') ? imagecreatefromjpeg($source_image) : imagecreatefrompng($source_image);

        if ($image) {
            $quality = 75;
            if ($image_mime == 'image/jpeg') {
                imagejpeg($image, $targetFilePath, $quality);
            } else {
                imagepng($image, $targetFilePath, floor($quality / 10));
            }
            imagedestroy($image);
            return $targetFilePath;
        }

        $this->returnWithError('Error: There was a problem uploading your file.', 1);
        return false;
    }

    private function returnWithError($message, $step) {
        $data = array_merge($_SESSION['registration_data'], $this->_get_registration_data());
        $data['error'] = $message;
        $data['step'] = $step;
        $data['csrf_token'] = csrf_token();
        echo $this->view('auth/register', $data);
    }

    private function _get_registration_data() {
        return [
            'bhaktiSadans' => (new BhaktiSadan())->getAll(),
            'educations' => (new Lookup('educations'))->getAll(),
            'professions' => (new Lookup('professions'))->getAll(),
            'languages' => (new Lookup('languages'))->getAll(),
            'sevas' => (new Lookup('sevas'))->getAll(),
            'shiksha_levels' => (new Lookup('shiksha_levels'))->getAll(),
        ];
    }

    public function logout() {
        session_destroy();
        header("Location: " . url('login'));
        exit;
    }
}
