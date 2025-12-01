<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISKCON Seshadripuram :: Admin Dashboard :: Devotee Care Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="manifest" href="manifest.json">
    <style>
        body {
            display: flex;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
        }

        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 1.1em;
            color: #adb5bd;
            display: block;
        }

        .sidebar a:hover {
            color: #fff;
            background-color: #495057;
        }

        .content {
            flex-grow: 1;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h3 class="text-center">Admin Panel</h3>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo url('dashboard'); ?>">Dashboard</a>
            </li>
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'Admin'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo url('users'); ?>">User Management</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="manageLookupsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Manage Lookups
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="manageLookupsDropdown">
                        <li><a class="dropdown-item" href="<?php echo url('bhakti-sadan'); ?>">Bhakti Sadan</a></li>
                        <li><a class="dropdown-item" href="<?php echo url('lookup/educations'); ?>">Education</a></li>
                        <li><a class="dropdown-item" href="<?php echo url('lookup/professions'); ?>">Profession</a></li>
                        <li><a class="dropdown-item" href="<?php echo url('lookup/blood_groups'); ?>">Blood Group</a></li>
                        <li><a class="dropdown-item" href="<?php echo url('lookup/languages'); ?>">Languages</a></li>
                        <li><a class="dropdown-item" href="<?php echo url('lookup/sevas'); ?>">Seva</a></li>
                    </ul>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo url('profile'); ?>">My Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo url('logout'); ?>">Logout</a>
            </li>
        </ul>
    </div>
    <div class="content">