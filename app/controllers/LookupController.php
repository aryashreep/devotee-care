<?php
// app/controllers/LookupController.php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Lookup;

class LookupController extends BaseController {

    private $lookupModel;
    private $lookupType;
    private $lookupName;

    public function __construct($lookupType, $lookupName) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . url('login'));
            exit;
        }
        if ($_SESSION['user_role'] !== 'Admin') {
            showError('Forbidden: You do not have permission to access this page.', 403);
        }

        $this->lookupType = $lookupType;
        $this->lookupName = $lookupName;
        $this->lookupModel = new Lookup();
    }

    public function index() {
        $data['items'] = $this->lookupModel->getAll($this->lookupType);
        $data['lookupType'] = $this->lookupType;
        $data['lookupName'] = $this->lookupName;
        echo $this->view('dashboard/lookup/index', $data);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = ['name' => trim($_POST['name'])];
            if ($this->lookupModel->create($this->lookupType, $data)) {
                header('Location: ' . url('lookup/' . $this->lookupType));
                exit;
            } else {
                $data['error'] = 'Failed to create ' . $this->lookupName;
                $this->index($data);
            }
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = ['name' => trim($_POST['name'])];
            if ($this->lookupModel->update($this->lookupType, $id, $data)) {
                header('Location: ' . url('lookup/' . $this->lookupType));
                exit;
            } else {
                $data['error'] = 'Failed to update ' . $this->lookupName;
                $this->index($data);
            }
        }
    }

    public function delete($id) {
        if ($this->lookupModel->delete($this->lookupType, $id)) {
            header('Location: ' . url('lookup/' . $this->lookupType));
            exit;
        } else {
            showError('Failed to delete ' . $this->lookupName, 500);
        }
    }
}
