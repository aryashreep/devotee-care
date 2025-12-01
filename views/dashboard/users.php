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
                    <a href="<?php echo url('user/view/' . $user['id']); ?>" class="btn btn-sm btn-success">View</a>
                    <a href="<?php echo url('user/edit/' . $user['id']); ?>" class="btn btn-sm btn-info">Edit</a>
                    <a href="<?php echo url('user/delete/' . $user['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../includes/dashboard_footer.php'; ?>
