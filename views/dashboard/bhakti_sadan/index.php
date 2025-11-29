<?php
// views/dashboard/bhakti_sadan/index.php

include_once __DIR__ . '/../../includes/dashboard_header.php';
?>

<div class="container">
    <h2>Bhakti Sadan Management</h2>
    <a href="<?php echo url('bhakti-sadan/create'); ?>" class="btn btn-primary mb-3">Create Bhakti Sadan</a>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['bhaktiSadans'] as $bhaktiSadan): ?>
                <tr>
                    <td><?php echo htmlspecialchars($bhaktiSadan['name']); ?></td>
                    <td>
                        <a href="<?php echo url('bhakti-sadan/edit/' . $bhaktiSadan['id']); ?>" class="btn btn-sm btn-info">Edit</a>
                        <a href="<?php echo url('bhakti-sadan/delete/' . $bhaktiSadan['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
include_once __DIR__ . '/../../includes/dashboard_footer.php';
?>
