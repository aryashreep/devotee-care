<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mt-5">
            <div class="card-header">
                <h2>Register - Step <?php echo htmlspecialchars($data['step']); ?> of 5</h2>
            </div>
            <div class="card-body">
                <?php if (isset($data['error'])): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($data['error']); ?></div>
                <?php endif; ?>

                <form action="<?php echo url('register', ['step' => $data['step']]); ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrf_token']); ?>">

                    <?php if ($data['step'] == 1): ?>
                        <h4>Create Your Account</h4>
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="full_name" name="full_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                            <div>
                                <input type="radio" id="male" name="gender" value="Male" class="form-check-input" required> <label for="male" class="form-check-label">Male</label>
                                <input type="radio" id="female" name="gender" value="Female" class="form-check-input" required> <label for="female" class="form-check-label">Female</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="photo" class="form-label">Photo <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="photo" name="photo" required>
                        </div>
                        <div class="mb-3">
                            <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Marital Status <span class="text-danger">*</span></label>
                            <div>
                                <input type="radio" id="single" name="marital_status" value="Single" class="form-check-input" required> <label for="single" class="form-check-label">Single</label>
                                <input type="radio" id="married" name="marital_status" value="Married" class="form-check-input"> <label for="married" class="form-check-label">Married</label>
                                <input type="radio" id="divorced" name="marital_status" value="Divorced" class="form-check-input"> <label for="divorced" class="form-check-label">Divorced</label>
                            </div>
                        </div>
                        <div class="mb-3" id="marriage_anniversary_date_div" style="display: none;">
                            <label for="marriage_anniversary_date" class="form-label">Marriage Anniversary Date</label>
                            <input type="date" class="form-control" id="marriage_anniversary_date" name="marriage_anniversary_date">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>

                    <?php elseif ($data['step'] == 2): ?>
                        <h4>Contact Details</h4>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="mobile_number" class="form-label">Mobile Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="mobile_number" name="mobile_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                            <select class="form-control" id="state" name="state" required></select>
                        </div>
                        <div class="mb-3">
                            <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                            <select class="form-control" id="city" name="city" required></select>
                        </div>
                        <div class="mb-3">
                            <label for="pincode" class="form-label">Pincode <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pincode" name="pincode" required>
                        </div>
                        <div class="mb-3">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="country" name="country" value="India" readonly>
                        </div>

                    <?php elseif ($data['step'] == 3): ?>
                        <h4>Additional Details</h4>
                        <div class="mb-3">
                            <label for="blood_group_id" class="form-label">Blood Group</label>
                            <select class="form-control" id="blood_group_id" name="blood_group_id">
                                <option value="">Select Blood Group</option>
                                <?php foreach ($data['blood_groups'] as $item): ?>
                                    <option value="<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="education_id" class="form-label">Education <span class="text-danger">*</span></label>
                            <select class="form-control" id="education_id" name="education_id" required>
                                <?php foreach ($data['educations'] as $item): ?>
                                    <option value="<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="profession_id" class="form-label">Profession <span class="text-danger">*</span></label>
                            <select class="form-control" id="profession_id" name="profession_id" required>
                                <?php foreach ($data['professions'] as $item): ?>
                                    <option value="<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Languages <span class="text-danger">*</span></label>
                            <div>
                                <?php foreach ($data['languages'] as $item): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="languages[]" value="<?php echo $item['id']; ?>" id="lang_<?php echo $item['id']; ?>">
                                        <label class="form-check-label" for="lang_<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['name']); ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div id="dependants">
                            <label class="form-label">Dependants (Kids)</label>
                            <!-- Dependant fields will be added here by JS -->
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary" id="add-dependant">Add Dependant</button>

                    <?php elseif ($data['step'] == 4): ?>
                        <h4>Spiritual Details</h4>
                        <div class="mb-3">
                            <label class="form-label">Are you Initiated? <span class="text-danger">*</span></label>
                            <div>
                                <input type="radio" name="is_initiated" value="Yes" class="form-check-input" required> <label class="form-check-label">Yes</label>
                                <input type="radio" name="is_initiated" value="No" class="form-check-input"> <label class="form-check-label">No</label>
                            </div>
                        </div>
                        <div id="initiated_details" style="display:none;">
                            <div class="mb-3">
                                <label for="spiritual_master_name" class="form-label">Spiritual Master Name</label>
                                <input type="text" class="form-control" id="spiritual_master_name" name="spiritual_master_name">
                            </div>
                        </div>
                        <div id="not_initiated_details" style="display:none;">
                            <div class="mb-3">
                                <label for="chanting_rounds" class="form-label">How many rounds you are doing? <span class="text-danger">*</span></label>
                                <select class="form-control" id="chanting_rounds" name="chanting_rounds">
                                    <?php for ($i = 0; $i <= 16; $i++): ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Shiksha level <span class="text-danger">*</span></label>
                                <?php foreach ($data['shiksha_levels'] as $level): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="shiksha_levels[]" value="<?php echo $level['id']; ?>" id="shiksha_<?php echo $level['id']; ?>">
                                        <label class="form-check-label" for="shiksha_<?php echo $level['id']; ?>"><?php echo htmlspecialchars($level['name']); ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Second Initiation? <span class="text-danger">*</span></label>
                            <div>
                                <input type="radio" name="second_initiation" value="Yes" class="form-check-input" required> <label class="form-check-label">Yes</label>
                                <input type="radio" name="second_initiation" value="No" class="form-check-input"> <label class="form-check-label">No</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="bhakti_sadan_id" class="form-label">Connected to which Bhakti Sadan <span class="text-danger">*</span></label>
                            <select class="form-control" id="bhakti_sadan_id" name="bhakti_sadan_id" required>
                                <?php foreach ($data['bhaktiSadans'] as $item): ?>
                                    <option value="<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Do you have ISKCON life membership? <span class="text-danger">*</span></label>
                            <div>
                                <input type="radio" name="has_life_membership" value="Yes" class="form-check-input" required> <label class="form-check-label">Yes</label>
                                <input type="radio" name="has_life_membership" value="No" class="form-check-input"> <label class="form-check-label">No</label>
                            </div>
                        </div>
                        <div id="life_membership_details" style="display:none;">
                            <div class="mb-3">
                                <label for="life_member_no" class="form-label">Life Member No</label>
                                <input type="text" class="form-control" id="life_member_no" name="life_member_no">
                            </div>
                            <div class="mb-3">
                                <label for="life_member_temple" class="form-label">Taken from Which Temple</label>
                                <input type="text" class="form-control" id="life_member_temple" name="life_member_temple">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Temple Services <span class="text-danger">*</span></label>
                            <?php foreach ($data['sevas'] as $item): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="sevas[]" value="<?php echo $item['id']; ?>" id="seva_<?php echo $item['id']; ?>">
                                    <label class="form-check-label" for="seva_<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['name']); ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    <?php elseif ($data['step'] == 5): ?>
                        <h4>Disclaimer</h4>
                        <div class="p-3 border rounded">
                            <p>The temple congregation management team works to facilitate the spiritual progress of all the associated devotees. And sometimes devotees are in need of guidance and without that, they may feel disconnected with spiritual life. For this, we are collecting details of the devotees connected to Sri Jagannath Mandir, Seshadripuram and associated Bhakti Sadans</p>
                            <h6>Key Benefits:</h6>
                            <ul>
                                <li>Get connected to devotees, who are running various services, like provision stores, software engineers, doctors, astrologers etc. For the devotee community to progress, we should stay within devotee circles whenever possible</li>
                                <li>Information about temple yatras, festivals and other events</li>
                                <li>Temple team to analyse devotees of different age groups, and possibly prepare or providing better services like separate prasadam queue for senior citizens, medical camps, general counselling, Career & job counselling etc</li>
                                <li>All sort of service opportunities connected to temple and Sadans</li>
                                <li>Facilitate devotee get connecting to nearest bhakti sadans.</li>
                                <li>Special Puja for Birthdays and Anniversaries</li>
                            </ul>
                        </div>
                        <div class="form-check mt-3">
                            <span class="text-danger">*</span><input class="form-check-input" type="checkbox" value="1" id="declaration" name="declaration" required>
                            <label class="form-check-label" for="declaration">
                                I hereby declare that the information furnished above is true and correct to the best of my knowledge. I voluntarily wish to be a part of ISKCON Seshadripuram’s spiritual and seva initiatives.
                            </label>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between mt-4">
                        <?php if ($data['step'] > 1): ?>
                            <a href="<?php echo url('register', ['step' => $data['step'] - 1]); ?>" class="btn btn-secondary">Previous</a>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-primary">
                            <?php echo ($data['step'] == 5) ? 'Submit' : 'Next'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // --- Step 1: Marital Status ---
        const maritalStatusRadios = document.querySelectorAll('input[name="marital_status"]');
        const anniversaryDiv = document.getElementById('marriage_anniversary_date_div');
        maritalStatusRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                anniversaryDiv.style.display = (this.value === 'Married') ? 'block' : 'none';
            });
        });

        // --- Step 2: State/City Dropdowns ---
        const states = {
            "Karnataka": ["Bengaluru", "Mysuru", "Mangaluru"],
            "Maharashtra": ["Mumbai", "Pune", "Nagpur"],
            "Tamil Nadu": ["Chennai", "Coimbatore", "Madurai"]
        };
        const stateDropdown = document.getElementById('state');
        const cityDropdown = document.getElementById('city');
        if (stateDropdown) {
            for (const state in states) {
                stateDropdown.add(new Option(state, state));
            }
            stateDropdown.addEventListener('change', function() {
                cityDropdown.innerHTML = '';
                const cities = states[this.value] || [];
                cities.forEach(city => cityDropdown.add(new Option(city, city)));
            });
            stateDropdown.dispatchEvent(new Event('change')); // Trigger on load
        }

        // --- Step 3: Dependants ---
        const addDependantBtn = document.getElementById('add-dependant');
        const dependantsContainer = document.getElementById('dependants');
        let dependantIndex = 0;
        if (addDependantBtn) {
            addDependantBtn.addEventListener('click', function() {
                dependantIndex++;
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
            });
        }

        // --- Step 4: Spiritual Details ---
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

    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>