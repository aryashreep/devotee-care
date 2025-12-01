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
</style>

<h2>My Profile</h2>
<hr>

<?php if (isset($data['success'])): ?>
    <div class="alert alert-success">Profile updated successfully!</div>
<?php endif; ?>
<?php if (isset($data['error'])): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($data['error']); ?></div>
<?php endif; ?>

<form method="POST" action="<?php echo url('profile'); ?>" enctype="multipart/form-data">
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
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($data['user']['full_name']); ?>">
                    </div>
                     <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <div>
                            <input type="radio" id="male" name="gender" value="Male" class="form-check-input" <?php echo ($data['user']['gender'] ?? '') === 'Male' ? 'checked' : ''; ?>> <label for="male">Male</label>
                            <input type="radio" id="female" name="gender" value="Female" class="form-check-input" <?php echo ($data['user']['gender'] ?? '') === 'Female' ? 'checked' : ''; ?>> <label for="female">Female</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?php echo htmlspecialchars($data['user']['date_of_birth'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Marital Status</label>
                        <div>
                            <input type="radio" name="marital_status" value="Single" class="form-check-input" <?php echo ($data['user']['marital_status'] ?? '') === 'Single' ? 'checked' : ''; ?>> <label>Single</label>
                            <input type="radio" name="marital_status" value="Married" class="form-check-input" <?php echo ($data['user']['marital_status'] ?? '') === 'Married' ? 'checked' : ''; ?>> <label>Married</label>
                            <input type="radio" name="marital_status" value="Divorced" class="form-check-input" <?php echo ($data['user']['marital_status'] ?? '') === 'Divorced' ? 'checked' : ''; ?>> <label>Divorced</label>
                        </div>
                    </div>
                    <div class="mb-3" id="marriage_anniversary_date_div" style="display: <?php echo ($data['user']['marital_status'] ?? '') === 'Married' ? 'block' : 'none'; ?>;">
                        <label for="marriage_anniversary_date" class="form-label">Marriage Anniversary Date</label>
                        <input type="date" class="form-control" id="marriage_anniversary_date" name="marriage_anniversary_date" value="<?php echo htmlspecialchars($data['user']['marriage_anniversary_date'] ?? ''); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="photo" class="form-label">Update Photo</label>
                        <input type="file" class="form-control" id="photo" name="photo">
                        <?php if (!empty($data['user']['photo'])): ?>
                            <div class="mt-2">
                                <img src="<?php echo url($data['user']['photo']); ?>" alt="Current Photo" style="max-width: 150px; border-radius: 5px;">
                                <p><small>Current Photo</small></p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <hr>
                    <h5>Change Password</h5>
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Details Tab -->
        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
             <div class="mb-3">
                <label for="mobile_number" class="form-label">Mobile Number</label>
                <input type="text" class="form-control" id="mobile_number" name="mobile_number" value="<?php echo htmlspecialchars($data['user']['mobile_number']); ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($data['user']['email']); ?>">
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($data['user']['address'] ?? ''); ?></textarea>
            </div>
            <div class="row">
                <div class="col-md-4">
                     <div class="mb-3">
                        <label for="state" class="form-label">State</label>
                        <select class="form-control" id="state" name="state"></select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <select class="form-control" id="city" name="city"></select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="pincode" class="form-label">Pincode</label>
                        <input type="text" class="form-control" id="pincode" name="pincode" value="<?php echo htmlspecialchars($data['user']['pincode'] ?? ''); ?>">
                    </div>
                </div>
                 <div class="col-md-2">
                     <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" class="form-control" id="country" name="country" value="India" readonly>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Details Tab -->
        <div class="tab-pane fade" id="additional" role="tabpanel" aria-labelledby="additional-tab">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="blood_group_id" class="form-label">Blood Group</label>
                        <select class="form-control" id="blood_group_id" name="blood_group_id">
                             <?php foreach ($data['blood_groups'] as $item): ?>
                                <option value="<?php echo $item['id']; ?>" <?php echo ($data['user']['blood_group_id'] ?? '') == $item['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($item['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="education_id" class="form-label">Education</label>
                        <select class="form-control" id="education_id" name="education_id">
                             <?php foreach ($data['educations'] as $item): ?>
                                <option value="<?php echo $item['id']; ?>" <?php echo ($data['user']['education_id'] ?? '') == $item['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($item['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="profession_id" class="form-label">Profession</label>
                        <select class="form-control" id="profession_id" name="profession_id">
                            <?php foreach ($data['professions'] as $item): ?>
                                <option value="<?php echo $item['id']; ?>" <?php echo ($data['user']['profession_id'] ?? '') == $item['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($item['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                     <div class="mb-3">
                        <label class="form-label">Languages</label>
                        <div>
                        <?php foreach ($data['languages'] as $item): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="languages[]" value="<?php echo $item['id']; ?>" id="lang_<?php echo $item['id']; ?>" <?php echo in_array($item['id'], $data['user_languages'] ?? []) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="lang_<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['name']); ?></label>
                            </div>
                        <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="dependants">
                        <label class="form-label">Dependants (Kids)</label>
                        <?php foreach ($data['user_dependants'] as $index => $dependant): ?>
                            <div class="dependant-group row mt-2">
                                <div class="col-md-4"><input type="text" name="dependants[<?php echo $index; ?>][name]" class="form-control" placeholder="Name" value="<?php echo htmlspecialchars($dependant['name']); ?>"></div>
                                <div class="col-md-2"><input type="number" name="dependants[<?php echo $index; ?>][age]" class="form-control" placeholder="Age" value="<?php echo htmlspecialchars($dependant['age']); ?>"></div>
                                <div class="col-md-3">
                                    <select name="dependants[<?php echo $index; ?>][gender]" class="form-control">
                                        <option value="Male" <?php echo ($dependant['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo ($dependant['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                    </select>
                                </div>
                                <div class="col-md-3"><input type="date" name="dependants[<?php echo $index; ?>][date_of_birth]" class="form-control" value="<?php echo htmlspecialchars($dependant['date_of_birth']); ?>"></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary mt-2" id="add-dependant">Add Dependant</button>
                </div>
            </div>
        </div>

        <!-- Spiritual Details Tab -->
        <div class="tab-pane fade" id="spiritual" role="tabpanel" aria-labelledby="spiritual-tab">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Are you Initiated?</label>
                        <div>
                            <input type="radio" name="is_initiated" value="Yes" class="form-check-input" <?php echo ($data['user']['is_initiated'] ?? '') === 'Yes' ? 'checked' : ''; ?>> <label>Yes</label>
                            <input type="radio" name="is_initiated" value="No" class="form-check-input" <?php echo ($data['user']['is_initiated'] ?? '') === 'No' ? 'checked' : ''; ?>> <label>No</label>
                        </div>
                    </div>
                     <div id="initiated_details" style="display: <?php echo ($data['user']['is_initiated'] ?? '') === 'Yes' ? 'block' : 'none'; ?>;">
                         <div class="mb-3">
                            <label for="spiritual_master_name" class="form-label">Spiritual Master Name</label>
                            <input type="text" class="form-control" id="spiritual_master_name" name="spiritual_master_name" value="<?php echo htmlspecialchars($data['user']['spiritual_master_name'] ?? ''); ?>">
                        </div>
                    </div>
                     <div id="not_initiated_details" style="display: <?php echo ($data['user']['is_initiated'] ?? '') === 'No' ? 'block' : 'none'; ?>;">
                        <div class="mb-3">
                            <label for="chanting_rounds" class="form-label">How many rounds you are doing?</label>
                             <select class="form-control" id="chanting_rounds" name="chanting_rounds">
                                <?php for ($i = 0; $i <= 16; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo ($data['user']['chanting_rounds'] ?? '') == $i ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                         <div class="mb-3">
                            <label class="form-label">Shiksha level</label>
                            <?php foreach ($data['shiksha_levels'] as $level): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="shiksha_levels[]" value="<?php echo $level['id']; ?>" id="shiksha_<?php echo $level['id']; ?>" <?php echo in_array($level['id'], $data['user_shiksha_levels'] ?? []) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="shiksha_<?php echo $level['id']; ?>"><?php echo htmlspecialchars($level['name']); ?></label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Second Initiation?</label>
                         <div>
                            <input type="radio" name="second_initiation" value="Yes" class="form-check-input" <?php echo ($data['user']['second_initiation'] ?? '') === 'Yes' ? 'checked' : ''; ?>> <label>Yes</label>
                            <input type="radio" name="second_initiation" value="No" class="form-check-input" <?php echo ($data['user']['second_initiation'] ?? '') === 'No' ? 'checked' : ''; ?>> <label>No</label>
                        </div>
                    </div>
                     <div class="mb-3">
                         <label for="bhakti_sadan_id" class="form-label">Connected to which Bhakti Sadan</label>
                         <select class="form-control" id="bhakti_sadan_id" name="bhakti_sadan_id">
                             <?php foreach ($data['bhaktiSadans'] as $item): ?>
                                <option value="<?php echo $item['id']; ?>" <?php echo ($data['user']['bhakti_sadan_id'] ?? '') == $item['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($item['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Do you have ISKCON life membership?</label>
                         <div>
                            <input type="radio" name="has_life_membership" value="Yes" class="form-check-input" <?php echo ($data['user']['has_life_membership'] ?? '') === 'Yes' ? 'checked' : ''; ?>> <label>Yes</label>
                            <input type="radio" name="has_life_membership" value="No" class="form-check-input" <?php echo ($data['user']['has_life_membership'] ?? '') === 'No' ? 'checked' : ''; ?>> <label>No</label>
                        </div>
                    </div>
                    <div id="life_membership_details" style="display: <?php echo ($data['user']['has_life_membership'] ?? '') === 'Yes' ? 'block' : 'none'; ?>;">
                        <div class="mb-3">
                            <label for="life_member_no" class="form-label">Life Member No</label>
                            <input type="text" class="form-control" id="life_member_no" name="life_member_no" value="<?php echo htmlspecialchars($data['user']['life_member_no'] ?? ''); ?>">
                        </div>
                         <div class="mb-3">
                            <label for="life_member_temple" class="form-label">Taken from Which Temple</label>
                            <input type="text" class="form-control" id="life_member_temple" name="life_member_temple" value="<?php echo htmlspecialchars($data['user']['life_member_temple'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Temple Services</label>
                         <?php foreach ($data['sevas'] as $item): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sevas[]" value="<?php echo $item['id']; ?>" id="seva_<?php echo $item['id']; ?>" <?php echo in_array($item['id'], $data['user_sevas'] ?? []) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="seva_<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['name']); ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Marital Status ---
    const maritalStatusRadios = document.querySelectorAll('input[name="marital_status"]');
    const anniversaryDiv = document.getElementById('marriage_anniversary_date_div');
    maritalStatusRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            anniversaryDiv.style.display = (this.value === 'Married') ? 'block' : 'none';
        });
    });

    // --- State/City Dropdowns ---
    const states = {
        "Karnataka": ["Bengaluru", "Mysuru", "Mangaluru"],
        "Maharashtra": ["Mumbai", "Pune", "Nagpur"],
        "Tamil Nadu": ["Chennai", "Coimbatore", "Madurai"]
        // Add more states and cities as needed
    };
    const stateDropdown = document.getElementById('state');
    const cityDropdown = document.getElementById('city');
    const selectedState = "<?php echo $data['user']['state'] ?? 'Karnataka'; ?>";
    const selectedCity = "<?php echo $data['user']['city'] ?? ''; ?>";

    for (const state in states) {
        let option = new Option(state, state, state === selectedState, state === selectedState);
        stateDropdown.add(option);
    }

    function updateCities() {
        cityDropdown.innerHTML = '';
        const cities = states[stateDropdown.value] || [];
        cities.forEach(city => {
            let option = new Option(city, city, city === selectedCity, city === selectedCity);
            cityDropdown.add(option);
        });
    }

    stateDropdown.addEventListener('change', updateCities);
    updateCities(); // Initial population

    // --- Dependants ---
    const addDependantBtn = document.getElementById('add-dependant');
    const dependantsContainer = document.getElementById('dependants');
    let dependantIndex = <?php echo count($data['user_dependants']); ?>;
    addDependantBtn.addEventListener('click', function() {
        const div = document.createElement('div');
        div.className = 'dependant-group row mt-2';
        div.innerHTML = `
            <div class="col-md-4"><input type="text" name="dependants[${dependantIndex}][name]" class="form-control" placeholder="Name"></div>
            <div class="col-md-2"><input type="number" name="dependants[${dependantIndex}][age]" class="form-control" placeholder="Age"></div>
            <div class="col-md-3">
                <select name="dependants[${dependantIndex}][gender]" class="form-control">
                    <option value="Male">Male</option><option value="Female">Female</option>
                </select>
            </div>
            <div class="col-md-3"><input type="date" name="dependants[${dependantIndex}][date_of_birth]" class="form-control"></div>
        `;
        dependantsContainer.appendChild(div);
        dependantIndex++;
    });


    // --- Spiritual Details ---
    const initiatedRadios = document.querySelectorAll('input[name="is_initiated"]');
    const initiatedDetails = document.getElementById('initiated_details');
    const notInitiatedDetails = document.getElementById('not_initiated_details');
    initiatedRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            initiatedDetails.style.display = (this.value === 'Yes') ? 'block' : 'none';
            notInitiatedDetails.style.display = (this.value === 'No') ? 'block' : 'none';
        });
    });

    const lifeMembershipRadios = document.querySelectorAll('input[name="has_life_membership"]');
    const lifeMembershipDetails = document.getElementById('life_membership_details');
    lifeMembershipRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            lifeMembershipDetails.style.display = (this.value === 'Yes') ? 'block' : 'none';
        });
    });

    // --- Bootstrap Tab Activation ---
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
