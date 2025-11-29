<?php require_once __DIR__ . '/includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8 text-center mt-5">
        <div class="alert alert-danger">
            <h1>An Error Occurred</h1>
            <p><?php echo htmlspecialchars($data['message'] ?? 'Something went wrong.'); ?></p>
            <a href="<?php echo url('login'); ?>" class="btn btn-primary">Go to Homepage</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
