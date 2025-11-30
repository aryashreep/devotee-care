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
            // --- START OF SECURITY FIX ---

            // 1. File Size Validation (Max 2MB)
            $maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes
            if ($_FILES['photo']['size'] > $maxFileSize) {
                $data = array_merge($data, $this->_get_registration_data());
                $data['error'] = 'Error: File size is larger than the allowed limit of 2MB.';
                $data['step'] = 1; // Photo upload is on Step 1
                $data['csrf_token'] = csrf_token(); // Refresh token
                echo $this->view('auth/register', $data);
                return;
            }

            // 2. File Type Validation (Allow only jpg, jpeg, png)
            $targetDir = "uploads/photos/";
            $fileName = uniqid() . '_' . basename($_FILES["photo"]["name"]);
            $targetFilePath = $targetDir . $fileName;
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png'];

            if (!in_array($fileType, $allowedTypes)) {
                $data = array_merge($data, $this->_get_registration_data());
                $data['error'] = 'Error: Only JPG, JPEG, and PNG files are allowed.';
                $data['step'] = 1; // Photo upload is on Step 1
                $data['csrf_token'] = csrf_token(); // Refresh token
                echo $this->view('auth/register', $data);
                return;
            }

            // 3. Secure Directory Creation
            if (!is_dir($targetDir)) {
                // Use more secure permissions
                mkdir($targetDir, 0755, true);
            }

            // --- START: Image Compression ---
            $source_image = $_FILES["photo"]["tmp_name"];
            $quality = 75; // Compression quality (0-100)

            // Create image resource from source
            $image_info = getimagesize($source_image);
            $image_mime = $image_info['mime'];

            $image = null;
            if ($image_mime == 'image/jpeg') {
                $image = imagecreatefromjpeg($source_image);
            } elseif ($image_mime == 'image/png') {
                $image = imagecreatefrompng($source_image);
            }

            if ($image) {
                // Compress and save the image
                if ($image_mime == 'image/jpeg') {
                    imagejpeg($image, $targetFilePath, $quality);
                } elseif ($image_mime == 'image/png') {
                    // PNG compression is a bit different (0-9)
                    $png_quality = floor($quality / 10);
                    imagepng($image, $targetFilePath, $png_quality);
                }
                imagedestroy($image);
                $data['photo'] = $targetFilePath;

            } else {
                // Fallback to simple move if not a supported image type for compression
                if (move_uploaded_file($source_image, $targetFilePath)) {
                    $data['photo'] = $targetFilePath;
                } else {
                    // Handle potential upload failure
                    $data = array_merge($data, $this->_get_registration_data());
                    $data['error'] = 'Error: There was a problem uploading your file.';
                    $data['step'] = 1;
                    $data['csrf_token'] = csrf_token();
                    echo $this->view('auth/register', $data);
                    return;
                }
            }
             // --- END: Image Compression ---
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
