<?php
// app/controllers/UserController.php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\User;
use App\Models\BhaktiSadan;
use App\Models\Role;
use App\Models\Lookup;

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
     * Fetches all necessary profile data for a given user.
     * @param int $userId The ID of the user.
     * @return array The data array for the view.
     */
    private function _getUserProfileData($userId) {
        $lookupModel = new Lookup();
        $bhaktiSadanModel = new BhaktiSadan();

        $data['user'] = $this->userModel->findById($userId);
        $data['blood_groups'] = $lookupModel->getAll('blood_groups');
        $data['educations'] = $lookupModel->getAll('educations');
        $data['professions'] = $lookupModel->getAll('professions');
        $data['languages'] = $lookupModel->getAll('languages');
        $data['shiksha_levels'] = $lookupModel->getAll('shiksha_levels');
        $data['sevas'] = $lookupModel->getAll('sevas');
        $data['bhaktiSadans'] = $bhaktiSadanModel->getAll();

        // Fetch related data for the user
        $data['user_languages'] = $this->userModel->getUserRelation('user_languages', $userId, 'language_id');
        $data['user_sevas'] = $this->userModel->getUserRelation('user_sevas', $userId, 'seva_id');
        $data['user_shiksha_levels'] = $this->userModel->getUserRelation('user_shiksha_levels', $userId, 'shiksha_level_id');
        $data['user_dependants'] = $this->userModel->getUserDependants($userId);

        return $data;
    }

    /**
     * Display a read-only view of a user's profile (Admin only).
     */
    public function viewUser($id) {
        if ($_SESSION['user_role'] !== 'Admin') {
            showError('Forbidden: You do not have permission to access this page.', 403);
        }
        $data = $this->_getUserProfileData($id);
        echo $this->view('dashboard/view_user', $data);
    }

    /**
     * Display and handle updates for the user's own profile.
     */
    public function profile() {
        $userId = $_SESSION['user_id'];
        $lookupModel = new Lookup();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Move all post data into a data array for easier handling
            $postData = $_POST;

            // --- VALIDATION ---
            $errors = [];
            if (empty($postData['full_name'])) {
                $errors[] = 'Full name is required.';
            }
            if (!empty($postData['email']) && !filter_var($postData['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Please enter a valid email address.';
            }
            if (!preg_match('/^[0-9]{10}$/', $postData['mobile_number'])) {
                $errors[] = 'Mobile number must be exactly 10 digits.';
            }
            if (!empty($postData['password']) && strlen($postData['password']) < 8) {
                $errors[] = 'Password must be at least 8 characters long.';
            }

            if (!empty($errors)) {
                $data['error'] = implode('<br>', $errors);
            } else {
                // --- PREPARE DATA: Convert empty strings to NULL for optional fields ---
                $optionalFields = [
                    'marriage_anniversary_date', 'email', 'education_id',
                    'profession_id', 'blood_group_id', 'is_initiated', 'spiritual_master_name',
                    'chanting_rounds', 'second_initiation', 'bhakti_sadan_id', 'has_life_membership',
                    'life_member_no', 'life_member_temple'
                ];
                foreach ($optionalFields as $field) {
                    if (empty($postData[$field])) {
                        $postData[$field] = null;
                    }
                }

                // Also handle optional fields for dependants
                if (!empty($postData['dependants'])) {
                    foreach ($postData['dependants'] as $key => $dependant) {
                        if (empty($dependant['date_of_birth'])) {
                            $postData['dependants'][$key]['date_of_birth'] = null;
                        }
                    }
                }

                // Handle file upload for photo
                if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
                    $targetDir = "uploads/profile_photos/";
                    // Create directory if it doesn't exist
                    if (!file_exists($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }
                    $fileName = time() . '_' . basename($_FILES["photo"]["name"]);
                    $targetFilePath = $targetDir . $fileName;

                    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
                        $postData['photo'] = $targetFilePath;
                    }
                }

                // Update the main user record
                if ($this->userModel->update($userId, $postData)) {
                    // --- Handle Many-to-Many Relationships ---
                    // 1. Languages
                    $this->userModel->deleteUserRelations('user_languages', $userId);
                    if (!empty($postData['languages'])) {
                        $this->userModel->assignLanguages($userId, $postData['languages']);
                    }

                    // 2. Sevas
                    $this->userModel->deleteUserRelations('user_sevas', $userId);
                    if (!empty($postData['sevas'])) {
                        $this->userModel->assignSevas($userId, $postData['sevas']);
                    }

                    // 3. Shiksha Levels
                    $this->userModel->deleteUserRelations('user_shiksha_levels', $userId);
                    if (!empty($postData['shiksha_levels'])) {
                        $this->userModel->assignShikshaLevels($userId, $postData['shiksha_levels']);
                    }

                    // --- Handle One-to-Many: Dependants ---
                    $this->userModel->deleteUserRelations('dependants', $userId);
                    if (!empty($postData['dependants'])) {
                        $this->userModel->addDependants($userId, $postData['dependants']);
                    }

                    $data['success'] = true;
                } else {
                    $data['error'] = 'Failed to update profile. The mobile number or email may already be in use by another user.';
                }
            }
        }

        // --- Fetch all data needed for the view ---
        // Refactored to use the helper method
        $viewData = $this->_getUserProfileData($userId);

        // Merge post-submission status (success/error) with the profile data
        $data = array_merge($viewData, $data ?? []);


        echo $this->view('dashboard/profile', $data);
    }
}
