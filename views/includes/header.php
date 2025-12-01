<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISKCON Seshadripuram :: Devotee Care Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css"> <!-- We can add custom styles later -->
    <link rel="manifest" href="manifest.json">
    <style>
        .auth-header {
            text-align: center;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        .auth-header img {
            max-width: 100px;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php $appConfig = require __DIR__ . '/../../config/app.php'; ?>
        <div class="auth-header">
            <img src="https://iskconbangalore.co.in/themes/contrib/akara/images/logo/logo.png" alt="ISKCON Seshadripuram Logo">
            <h1><?php echo htmlspecialchars($appConfig['header_line_1']); ?></h1>
            <p class="lead"><?php echo htmlspecialchars($appConfig['header_line_2']); ?></p>
        </div>