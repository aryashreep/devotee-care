<?php
// views/dashboard/bhakti_sadan/edit.php

include_once __DIR__ . '/../../includes/dashboard_header.php';
?>

<div class="container">
    <h2>Edit Bhakti Sadan</h2>

    <form method="POST" action="<?php echo url('bhakti-sadan/edit/' . $data['bhaktiSadan']['id']); ?>">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($data['bhaktiSadan']['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($data['bhaktiSadan']['address']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="leader_ids" class="form-label">Bhakti Sadan Leaders</label>
            <select class="form-control" id="leader_ids" name="leader_ids[]" multiple>
                <?php foreach ($data['users'] as $user): ?>
                    <option value="<?php echo $user['id']; ?>" <?php echo in_array($user['id'], $data['leader_ids']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($user['full_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<?php
include_once __DIR__ . '/../../includes/dashboard_footer.php';
?>
