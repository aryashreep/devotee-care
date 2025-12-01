<?php require_once __DIR__ . '/../includes/dashboard_header.php'; ?>

<style>
    .nav-tabs .nav-link.active {
        background-color: #007bff;
        color: #fff;
        border-color: #007bff;
    }
    .tab-content {
        border: 1px solid #dee2e6;
        border-top: 0;
        padding: 1.5rem;
        border-radius: 0 0 .25rem .25rem;
    }
    .detail-label {
        font-weight: bold;
        color: #555;
    }
    .detail-value {
        margin-bottom: 1rem;
    }
</style>

<h2>View User: <?php echo htmlspecialchars($data['user']['full_name']); ?></h2>
<hr>

<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab" aria-controls="personal" aria-selected="true">Personal Details</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Contact Details</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="additional-tab" data-bs-toggle="tab" data-bs-target="#additional" type="button" role="tab" aria-controls="additional" aria-selected="false">Additional Details</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="spiritual-tab" data-bs-toggle="tab" data-bs-target="#spiritual" type="button" role="tab" aria-controls="spiritual" aria-selected="false">Spiritual Details</button>
    </li>
</ul>

<div class="tab-content" id="myTabContent">
    <!-- Personal Details Tab -->
    <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
        <div class="row">
            <div class="col-md-8">
                <div class="detail-label">Full Name</div>
                <p class="detail-value"><?php echo htmlspecialchars($data['user']['full_name']); ?></p>

                <div class="detail-label">Gender</div>
                <p class="detail-value"><?php echo htmlspecialchars($data['user']['gender'] ?? 'N/A'); ?></p>

                <div class="detail-label">Date of Birth</div>
                <p class="detail-value"><?php echo !empty($data['user']['date_of_birth']) ? date('F j, Y', strtotime($data['user']['date_of_birth'])) : 'N/A'; ?></p>

                <div class="detail-label">Marital Status</div>
                <p class="detail-value"><?php echo htmlspecialchars($data['user']['marital_status'] ?? 'N/A'); ?></p>

                <?php if (($data['user']['marital_status'] ?? '') === 'Married'): ?>
                    <div class="detail-label">Marriage Anniversary Date</div>
                    <p class="detail-value"><?php echo !empty($data['user']['marriage_anniversary_date']) ? date('F j, Y', strtotime($data['user']['marriage_anniversary_date'])) : 'N/A'; ?></p>
                <?php endif; ?>
            </div>
            <div class="col-md-4 text-center">
                <?php if (!empty($data['user']['photo'])): ?>
                    <img src="<?php echo asset($data['user']['photo']); ?>" alt="Profile Photo" class="img-fluid rounded" style="max-width: 200px; border: 1px solid #ddd;">
                <?php else: ?>
                    <p>No photo uploaded.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Contact Details Tab -->
    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
        <div class="detail-label">Mobile Number</div>
        <p class="detail-value"><?php echo htmlspecialchars($data['user']['mobile_number']); ?></p>

        <div class="detail-label">Email Address</div>
        <p class="detail-value"><?php echo htmlspecialchars($data['user']['email'] ?? 'N/A'); ?></p>

        <div class="detail-label">Address</div>
        <p class="detail-value"><?php echo nl2br(htmlspecialchars($data['user']['address'] ?? 'N/A')); ?></p>

        <div class="row">
            <div class="col-md-4">
                <div class="detail-label">City</div>
                <p class="detail-value"><?php echo htmlspecialchars($data['user']['city'] ?? 'N/A'); ?></p>
            </div>
            <div class="col-md-4">
                <div class="detail-label">State</div>
                <p class="detail-value"><?php echo htmlspecialchars($data['user']['state'] ?? 'N/A'); ?></p>
            </div>
            <div class="col-md-2">
                <div class="detail-label">Pincode</div>
                <p class="detail-value"><?php echo htmlspecialchars($data['user']['pincode'] ?? 'N/A'); ?></p>
            </div>
            <div class="col-md-2">
                <div class="detail-label">Country</div>
                <p class="detail-value">India</p>
            </div>
        </div>
    </div>

    <!-- Additional Details Tab -->
    <div class="tab-pane fade" id="additional" role="tabpanel" aria-labelledby="additional-tab">
        <div class="row">
            <div class="col-md-6">
                <div class="detail-label">Blood Group</div>
                <p class="detail-value"><?php echo htmlspecialchars(find_name_by_id($data['blood_groups'], $data['user']['blood_group_id']) ?? 'N/A'); ?></p>

                <div class="detail-label">Education</div>
                <p class="detail-value"><?php echo htmlspecialchars(find_name_by_id($data['educations'], $data['user']['education_id']) ?? 'N/A'); ?></p>

                <div class="detail-label">Profession</div>
                <p class="detail-value"><?php echo htmlspecialchars(find_name_by_id($data['professions'], $data['user']['profession_id']) ?? 'N/A'); ?></p>

                <div class="detail-label">Languages</div>
                <p class="detail-value">
                    <?php
                        $lang_names = find_names_by_ids($data['languages'], $data['user_languages']);
                        echo !empty($lang_names) ? htmlspecialchars(implode(', ', $lang_names)) : 'N/A';
                    ?>
                </p>
            </div>
            <div class="col-md-6">
                <div class="detail-label">Dependants (Kids)</div>
                <?php if (!empty($data['user_dependants'])): ?>
                    <ul class="list-group">
                        <?php foreach ($data['user_dependants'] as $dependant): ?>
                            <li class="list-group-item">
                                <?php echo htmlspecialchars($dependant['name']); ?>
                                (<?php echo htmlspecialchars($dependant['gender']); ?>, <?php echo htmlspecialchars($dependant['age']); ?> years old, DOB: <?php echo !empty($dependant['date_of_birth']) ? date('M j, Y', strtotime($dependant['date_of_birth'])) : 'N/A'; ?>)
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="detail-value">No dependants listed.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Spiritual Details Tab -->
    <div class="tab-pane fade" id="spiritual" role="tabpanel" aria-labelledby="spiritual-tab">
        <div class="row">
            <div class="col-md-6">
                <div class="detail-label">Are you Initiated?</div>
                <p class="detail-value"><?php echo htmlspecialchars($data['user']['is_initiated'] ?? 'N/A'); ?></p>

                <?php if (($data['user']['is_initiated'] ?? '') === 'Yes'): ?>
                    <div class="detail-label">Spiritual Master Name</div>
                    <p class="detail-value"><?php echo htmlspecialchars($data['user']['spiritual_master_name'] ?? 'N/A'); ?></p>
                <?php else: ?>
                    <div class="detail-label">How many rounds you are doing?</div>
                    <p class="detail-value"><?php echo htmlspecialchars($data['user']['chanting_rounds'] ?? 'N/A'); ?></p>

                    <div class="detail-label">Shiksha level</div>
                     <p class="detail-value">
                        <?php
                            $shiksha_names = find_names_by_ids($data['shiksha_levels'], $data['user_shiksha_levels']);
                            echo !empty($shiksha_names) ? htmlspecialchars(implode(', ', $shiksha_names)) : 'N/A';
                        ?>
                    </p>
                <?php endif; ?>

                <div class="detail-label">Second Initiation?</div>
                <p class="detail-value"><?php echo htmlspecialchars($data['user']['second_initiation'] ?? 'N/A'); ?></p>

                <div class="detail-label">Connected to which Bhakti Sadan</div>
                <p class="detail-value"><?php echo htmlspecialchars(find_name_by_id($data['bhaktiSadans'], $data['user']['bhakti_sadan_id']) ?? 'N/A'); ?></p>
            </div>
            <div class="col-md-6">
                <div class="detail-label">Do you have ISKCON life membership?</div>
                <p class="detail-value"><?php echo htmlspecialchars($data['user']['has_life_membership'] ?? 'N/A'); ?></p>

                 <?php if (($data['user']['has_life_membership'] ?? '') === 'Yes'): ?>
                    <div class="detail-label">Life Member No</div>
                    <p class="detail-value"><?php echo htmlspecialchars($data['user']['life_member_no'] ?? 'N/A'); ?></p>

                    <div class="detail-label">Taken from Which Temple</div>
                    <p class="detail-value"><?php echo htmlspecialchars($data['user']['life_member_temple'] ?? 'N/A'); ?></p>
                <?php endif; ?>

                <div class="detail-label">Temple Services</div>
                <p class="detail-value">
                    <?php
                        $seva_names = find_names_by_ids($data['sevas'], $data['user_sevas']);
                        echo !empty($seva_names) ? htmlspecialchars(implode(', ', $seva_names)) : 'N/A';
                    ?>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="<?php echo url('user/edit/' . $data['user']['id']); ?>" class="btn btn-primary">Edit User</a>
    <a href="<?php echo url('users'); ?>" class="btn btn-secondary">Back to User List</a>
</div>

<script>
// --- Bootstrap Tab Activation ---
document.addEventListener('DOMContentLoaded', function() {
    var triggerTabList = [].slice.call(document.querySelectorAll('#myTab button'))
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl)
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault()
            tabTrigger.show()
        })
    })
});
</script>

<?php require_once __DIR__ . '/../includes/dashboard_footer.php'; ?>
