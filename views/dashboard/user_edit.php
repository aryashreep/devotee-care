<?php
// views/dashboard/user_edit.php

include_once __DIR__ . '/../includes/dashboard_header.php';
?>

<div class="container">
    <h2>Edit User</h2>

    <form method="POST" action="<?php echo url('user/edit/' . $data['user']['id']); ?>">
        <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($data['user']['full_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="mobile_number" class="form-label">Mobile Number</label>
            <input type="text" class="form-control" id="mobile_number" name="mobile_number" value="<?php echo htmlspecialchars($data['user']['mobile_number']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($data['user']['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="role_id" class="form-label">Role</label>
            <select class="form-control" id="role_id" name="role_id">
                <?php foreach ($data['roles'] as $role): ?>
                    <option value="<?php echo $role['id']; ?>" <?php echo ($data['user']['role_id'] == $role['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($role['role_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="bhakti_sadan_id" class="form-label">Bhakti Sadan</label>
            <select class="form-control" id="bhakti_sadan_id" name="bhakti_sadan_id">
                <option value="">None</option>
                <?php foreach ($data['bhaktiSadans'] as $bhaktiSadan): ?>
                    <option value="<?php echo $bhaktiSadan['id']; ?>" <?php echo ($data['user']['bhakti_sadan_id'] == $bhaktiSadan['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($bhaktiSadan['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<?php
include_once __DIR__ . '/../includes/dashboard_footer.php';
?>
