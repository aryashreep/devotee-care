<?php
// views/dashboard/bhakti_sadan/create.php

include_once __DIR__ . '/../../includes/dashboard_header.php';
?>

<div class="container">
    <h2>Create Bhakti Sadan</h2>

    <form method="POST" action="<?php echo url('bhakti-sadan/create'); ?>">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" id="address" name="address" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="leader_ids" class="form-label">Bhakti Sadan Leaders</label>
            <select class="form-control" id="leader_ids" name="leader_ids[]" multiple>
                <?php foreach ($data['users'] as $user): ?>
                    <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['full_name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>

<?php
include_once __DIR__ . '/../../includes/dashboard_footer.php';
?>
