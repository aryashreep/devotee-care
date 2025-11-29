<?php require_once __DIR__ . '/../includes/dashboard_header.php'; ?>

<h2>User Management</h2>
<a href="<?php echo url('register'); ?>" class="btn btn-primary mb-3">Create User</a>
<hr>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Mobile Number</th>
            <th>Email</th>
            <th>Role</th>
            <th>Bhakti Sadan</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data['users'] as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                <td><?php echo htmlspecialchars($user['mobile_number']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['role_name']); ?></td>
                <td><?php echo htmlspecialchars($user['bhakti_sadan_name'] ?? 'N/A'); ?></td>
                <td>
                    <form method="POST" action="<?php echo url('user/update-bhakti-sadan'); ?>" class="d-inline">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <select class="form-control d-inline w-50" name="bhakti_sadan_id">
                            <option value="">None</option>
                            <?php foreach ($data['bhaktiSadans'] as $bhaktiSadan): ?>
                                <option value="<?php echo $bhaktiSadan['id']; ?>" <?php echo ($user['bhakti_sadan_id'] == $bhaktiSadan['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($bhaktiSadan['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Save</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../includes/dashboard_footer.php'; ?>
