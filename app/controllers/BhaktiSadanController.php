<?php
// app/controllers/BhaktiSadanController.php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\BhaktiSadan;
use App\Models\User;

class BhaktiSadanController extends BaseController {

    private $bhaktiSadanModel;
    private $userModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . url('login'));
            exit;
        }
        $this->bhaktiSadanModel = new BhaktiSadan();
        $this->userModel = new User();
    }

    public function index() {
        if ($_SESSION['user_role'] !== 'Admin') {
            showError('Forbidden: You do not have permission to access this page.', 403);
        }

        $bhaktiSadans = $this->bhaktiSadanModel->getAll();
        $data['bhaktiSadans'] = $bhaktiSadans;
        echo $this->view('dashboard/bhakti_sadan/index', $data);
    }

    public function create() {
        if ($_SESSION['user_role'] !== 'Admin') {
            showError('Forbidden: You do not have permission to access this page.', 403);
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'address' => trim($_POST['address']),
                'leader_ids' => $_POST['leader_ids'] ?? []
            ];

            $bhaktiSadanId = $this->bhaktiSadanModel->create($data);
            if ($bhaktiSadanId) {
                $this->bhaktiSadanModel->assignLeaders($bhaktiSadanId, $data['leader_ids']);
                header('Location: ' . url('bhakti-sadan'));
                exit;
            } else {
                $data['error'] = 'Failed to create Bhakti Sadan.';
            }
        }

        $data['users'] = $this->userModel->getAll();
        echo $this->view('dashboard/bhakti_sadan/create', $data);
    }

    public function edit($id) {
        if ($_SESSION['user_role'] !== 'Admin') {
            showError('Forbidden: You do not have permission to access this page.', 403);
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'address' => trim($_POST['address']),
                'leader_ids' => $_POST['leader_ids'] ?? []
            ];

            if ($this->bhaktiSadanModel->update($id, $data)) {
                $this->bhaktiSadanModel->assignLeaders($id, $data['leader_ids']);
                header('Location: ' . url('bhakti-sadan'));
                exit;
            } else {
                $data['error'] = 'Failed to update Bhakti Sadan.';
            }
        }

        $bhaktiSadan = $this->bhaktiSadanModel->findById($id);
        $leaders = $this->bhaktiSadanModel->getLeaders($id);
        $users = $this->userModel->getAll();

        $data['bhaktiSadan'] = $bhaktiSadan;
        $data['leader_ids'] = array_column($leaders, 'id');
        $data['users'] = $users;

        echo $this->view('dashboard/bhakti_sadan/edit', $data);
    }

    public function delete($id) {
        if ($_SESSION['user_role'] !== 'Admin') {
            showError('Forbidden: You do not have permission to access this page.', 403);
        }

        // First, remove leader associations
        $this->bhaktiSadanModel->removeLeaders($id);

        if ($this->bhaktiSadanModel->delete($id)) {
            header('Location: ' . url('bhakti-sadan'));
            exit;
        } else {
            showError('Failed to delete Bhakti Sadan.', 500);
        }
    }
}
