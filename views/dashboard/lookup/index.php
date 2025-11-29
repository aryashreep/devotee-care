<?php
// views/dashboard/lookup/index.php

include_once __DIR__ . '/../../includes/dashboard_header.php';
?>

<div class="container">
    <h2><?php echo htmlspecialchars($data['lookupName']); ?> Management</h2>

    <form method="POST" action="<?php echo url('lookup/' . $data['lookupType'] . '/create'); ?>" class="mb-3">
        <div class="input-group">
            <input type="text" class="form-control" name="name" placeholder="New <?php echo htmlspecialchars($data['lookupName']); ?>" required>
            <button class="btn btn-primary" type="submit">Create</button>
        </div>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['items'] as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td>
                        <form method="POST" action="<?php echo url('lookup/' . $data['lookupType'] . '/edit/' . $item['id']); ?>" class="d-inline">
                            <input type="text" class="form-control d-inline w-50" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" required>
                            <button type="submit" class="btn btn-sm btn-info">Update</button>
                        </form>
                        <a href="<?php echo url('lookup/' . $data['lookupType'] . '/delete/' . $item['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
include_once __DIR__ . '/../../includes/dashboard_footer.php';
?>
