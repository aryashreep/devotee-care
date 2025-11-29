<?php require_once __DIR__ . '/../includes/dashboard_header.php'; ?>

<h2>My Profile</h2>
<hr>

<?php if (isset($data['success'])): ?>
    <div class="alert alert-success">Profile updated successfully!</div>
<?php endif; ?>
<?php if (isset($data['error'])): ?>
    <div class="alert alert-danger"><?php echo $data['error']; ?></div>
<?php endif; ?>

<form method="POST" action="<?php echo url('profile'); ?>">
    <div class="mb-3">
        <label for="full_name" class="form-label">Full Name</label>
        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($data['user']['full_name']); ?>">
    </div>
    <div class="mb-3">
        <label for="mobile_number" class="form-label">Mobile Number</label>
        <input type="text" class="form-control" id="mobile_number" name="mobile_number" value="<?php echo htmlspecialchars($data['user']['mobile_number']); ?>">
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($data['user']['email']); ?>">
    </div>
    <hr>
    <h5>Change Password</h5>
    <div class="mb-3">
        <label for="password" class="form-label">New Password (leave blank to keep current password)</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>
    <button type="submit" class="btn btn-primary">Update Profile</button>
</form>

<?php require_once __DIR__ . '/../includes/dashboard_footer.php'; ?>
