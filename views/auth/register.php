<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mt-5">
            <div class="card-header">
                <h2>Register - Step <?php echo $data['step']; ?> of 3</h2>
            </div>
            <div class="card-body">
                <?php if (isset($data['error'])): ?>
                    <div class="alert alert-danger"><?php echo $data['error']; ?></div>
                <?php endif; ?>

                <form action="<?php echo url('register', ['step' => $data['step']]); ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo $data['csrf_token']; ?>">

                    <?php if ($data['step'] == 1): ?>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="full_name" name="full_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="initiated_name" class="form-label">Initiated Name</label>
                                <input type="text" class="form-control" id="initiated_name" name="initiated_name">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                <select class="form-control" id="gender" name="gender" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="photo" class="form-label">Photo <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="photo" name="photo" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="marital_status" class="form-label">Marital Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="marital_status" name="marital_status" required>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Divorced">Divorced</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="marriage_anniversary_date" class="form-label">Marriage Anniversary Date</label>
                            <input type="date" class="form-control" id="marriage_anniversary_date" name="marriage_anniversary_date">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                        </div>

                    <?php elseif ($data['step'] == 2): ?>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="mobile_number" class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="mobile_number" name="mobile_number" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                <select class="form-control" id="city" name="city" required>

                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                                <select class="form-control" id="state" name="state" required>

                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pincode" class="form-label">Pincode <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="pincode" name="pincode" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" class="form-control" id="country" name="country" value="India" readonly>
                            </div>
                        </div>

                    <?php elseif ($data['step'] == 3): ?>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="education_id" class="form-label">Education <span class="text-danger">*</span></label>
                                <select class="form-control" id="education_id" name="education_id" required>
                                    <?php foreach ($data['educations'] as $education): ?>
                                        <option value="<?php echo $education['id']; ?>"><?php echo htmlspecialchars($education['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="profession_id" class="form-label">Profession <span class="text-danger">*</span></label>
                                <select class="form-control" id="profession_id" name="profession_id" required>
                                    <?php foreach ($data['professions'] as $profession): ?>
                                        <option value="<?php echo $profession['id']; ?>"><?php echo htmlspecialchars($profession['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="languages" class="form-label">Languages <span class="text-danger">*</span></label>
                            <select class="form-control" id="languages" name="languages[]" multiple required>
                                <?php foreach ($data['languages'] as $language): ?>
                                    <option value="<?php echo $language['id']; ?>"><?php echo htmlspecialchars($language['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="bhakti_sadan_id" class="form-label">Connected to which Bhakti Sadan <span class="text-danger">*</span></label>
                            <select class="form-control" id="bhakti_sadan_id" name="bhakti_sadan_id" required>
                                <?php foreach ($data['bhaktiSadans'] as $bhaktiSadan): ?>
                                    <option value="<?php echo $bhaktiSadan['id']; ?>"><?php echo htmlspecialchars($bhaktiSadan['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="life_member_no" class="form-label">Life Member No</label>
                                <input type="text" class="form-control" id="life_member_no" name="life_member_no">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="life_member_temple" class="form-label">Taken from Which Temple</label>
                                <input type="text" class="form-control" id="life_member_temple" name="life_member_temple">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="sevas" class="form-label">Temple Services <span class="text-danger">*</span></label>
                            <select class="form-control" id="sevas" name="sevas[]" multiple required>
                                <?php foreach ($data['sevas'] as $seva): ?>
                                    <option value="<?php echo $seva['id']; ?>"><?php echo htmlspecialchars($seva['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div id="dependants">
                            <label class="form-label">Dependants (Kids)</label>
                            <div class="dependant-group">
                                <div class="row">
                                    <div class="col-md-3 mb-2"><input type="text" name="dependants[0][name]" class="form-control" placeholder="Name"></div>
                                    <div class="col-md-2 mb-2"><input type="number" name="dependants[0][age]" class="form-control" placeholder="Age"></div>
                                    <div class="col-md-3 mb-2">
                                        <select name="dependants[0][gender]" class="form-control">
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-2"><input type="date" name="dependants[0][date_of_birth]" class="form-control"></div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary" id="add-dependant">Add Dependant</button>

                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" value="" id="declaration" required>
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
                            <?php echo ($data['step'] == 3) ? 'Register' : 'Next'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Logic for State and City dropdowns
    const states = {
        "Karnataka": ["Bengaluru", "Mysuru", "Mangaluru"],
        "Maharashtra": ["Mumbai", "Pune", "Nagpur"],
        "Tamil Nadu": ["Chennai", "Coimbatore", "Madurai"]
    };

    const stateDropdown = document.getElementById('state');
    const cityDropdown = document.getElementById('city');

    if (stateDropdown) {
        for (const state in states) {
            const option = document.createElement('option');
            option.value = state;
            option.textContent = state;
            stateDropdown.appendChild(option);
        }

        stateDropdown.addEventListener('change', function() {
            const selectedState = this.value;
            cityDropdown.innerHTML = ''; // Clear existing options
            if (selectedState && states[selectedState]) {
                states[selectedState].forEach(function(city) {
                    const option = document.createElement('option');
                    option.value = city;
                    option.textContent = city;
                    cityDropdown.appendChild(option);
                });
            }
        });

        // Trigger change event to populate cities for the default selected state
        stateDropdown.dispatchEvent(new Event('change'));
    }

    // Logic for adding dependants
    const addDependantButton = document.getElementById('add-dependant');
    if (addDependantButton) {
        let dependantIndex = 1;
        addDependantButton.addEventListener('click', function() {
            const dependantGroup = document.createElement('div');
            dependantGroup.classList.add('dependant-group', 'mt-2');
            dependantGroup.innerHTML = `
                <div class="row">
                    <div class="col-md-3 mb-2"><input type="text" name="dependants[${dependantIndex}][name]" class="form-control" placeholder="Name"></div>
                    <div class="col-md-2 mb-2"><input type="number" name="dependants[${dependantIndex}][age]" class="form-control" placeholder="Age"></div>
                    <div class="col-md-3 mb-2">
                        <select name="dependants[${dependantIndex}][gender]" class="form-control">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-2"><input type="date" name="dependants[${dependantIndex}][date_of_birth]" class="form-control"></div>
                </div>
            `;
            document.getElementById('dependants').appendChild(dependantGroup);
            dependantIndex++;
        });
    }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
