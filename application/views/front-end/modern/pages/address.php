<main>
    <section class="container py-5">
        <div class="row">
            <div class="col-md-3 myaccount-navigation py-3">
                <?php $this->load->view('front-end/' . THEME . '/pages/my-account-sidebar') ?>
            </div>
            <div class="col-md-9 py-3 padding-16-30">
                <h4 class="section-title mb-2"><?= label('address', 'Address') ?></h4>
                <?php
                $profile_name = !empty($user->username) ? htmlspecialchars($user->username, ENT_QUOTES, 'UTF-8') : '';
                $profile_mobile = !empty($user->mobile) ? htmlspecialchars($user->mobile, ENT_QUOTES, 'UTF-8') : '';
                ?>
                <form action="<?= base_url('my-account/add-address') ?>" method="POST" id="add-address-form">
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label for="address_name" class="form-label"><?= label('name', 'Name') ?> <sup
                                    class="text-danger fw-bold">*</sup></label>
                            <input type="text" class="form-control address-required" id="address_name" name="name"
                                value="<?= $profile_name ?>"
                                placeholder="<?= !empty($this->lang->line('name')) ? $this->lang->line('name') : 'Name' ?>">
                            <div class="invalid-feedback d-block address-field-error" data-for="address_name"></div>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="mobile_number" class="form-label"><?= label('contact_number', 'Contact Number') ?>
                                <sup class="text-danger fw-bold">*</sup></label>
                            <input type="text" class="form-control address-required" id="mobile_number" name="mobile"
                                value="<?= $profile_mobile ?>"
                                placeholder="<?= label('contact_number', 'Contact Number') ?>">
                            <div class="invalid-feedback d-block address-field-error" data-for="mobile_number"></div>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="form-label" for="address"><?= label('address', 'Address') ?> <sup
                                    class="text-danger fw-bold">*</sup></label>
                            <textarea class="form-control address-required" name="address" id="address" rows="3"
                                placeholder="e.g. House No. 12, Green Avenue, Near Metro Station"></textarea>
                            <div class="invalid-feedback d-block address-field-error" data-for="address"></div>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="country" class="form-label"><?= label('country', 'Country') ?> <sup
                                    class="text-danger fw-bold">*</sup></label>
                            <input type="text" class="form-control address-required" id="country" name="country"
                                value="United States" readonly>
                            <div class="invalid-feedback d-block address-field-error" data-for="country"></div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="state" class="form-label"><?= label('state', 'State') ?> <sup
                                    class="text-danger fw-bold">*</sup></label>
                            <input type="text" class="form-control address-required" id="state" name="state"
                                placeholder="<?= !empty($this->lang->line('state')) ? $this->lang->line('state') : 'State' ?>">
                            <div class="invalid-feedback d-block address-field-error" data-for="state"></div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="zipcode" class="form-label"><?= label('zipcode', 'ZIP Code') ?> <sup
                                    class="text-danger fw-bold">*</sup></label>
                            <div class="position-relative zipcode-autocomplete-wrap">
                                <input type="text" class="form-control address-required" id="zipcode" name="pincode"
                                    inputmode="numeric" autocomplete="off" maxlength="10"
                                    placeholder="e.g. 10001 or 10001-1234">
                                <div id="zipcode-suggestions" class="list-group zipcode-suggestions-list" style="display:none;"></div>
                            </div>
                            <div class="invalid-feedback d-block address-field-error" data-for="zipcode"></div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="city" class="form-label"><?= label('city', 'City') ?> <sup
                                    class="text-danger fw-bold">*</sup></label>
                            <select class="form-control address-required" id="city" name="city_id">
                                <option value=""><?= label('city', 'Select City') ?></option>
                                <?php if (!empty($cities)) {
                                    foreach ($cities as $city) { ?>
                                        <option value="<?= $city['id'] ?>"><?= htmlspecialchars($city['name'], ENT_QUOTES, 'UTF-8') ?></option>
                                <?php }
                                } ?>
                            </select>
                            <input type="hidden" name="city_name" id="city_name" value="" />
                            <div class="invalid-feedback d-block address-field-error" data-for="city"></div>
                        </div>

                        <div class="col-12 mb-3 d-none" id="address-additional-section">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3"><?= label('additional_information', 'Additional Information') ?></h6>
                                    <div class="mb-3">
                                        <label for="alternate_mobile"
                                            class="form-label"><?= label('alternate_mobile', 'Alternate Mobile') ?></label>
                                        <input type="text" class="form-control" id="alternate_mobile" name="alternate_mobile"
                                            placeholder="<?= label('alternate_mobile', 'Alternate Mobile') ?>">
                                    </div>
                                    <div>
                                        <label class="form-label"><?= label('type', 'Type') ?></label>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" class="form-check-input" name="type" id="home" value="home" checked />
                                            <label for="home"
                                                class="form-check-label text-dark"><?= label('home', 'Home') ?></label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" class="form-check-input" name="type" id="office" value="office" />
                                            <label for="office"
                                                class="form-check-label text-dark"><?= label('office', 'Office') ?></label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" class="form-check-input" name="type" id="other" value="other" />
                                            <label for="other"
                                                class="form-check-label text-dark"><?= label('other', 'Other') ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" id="save-address-submit-btn"
                        value="Save"><?= label('save_address', 'Save Address') ?></button>
                    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                        <div id="save-address-result"></div>
                    </div>
                </form>
            </div>
            <div>
                <table id="address_list_table" class='table-striped' data-toggle="table"
                    data-url="<?= base_url('my-account/get-address-list') ?>" data-click-to-select="true"
                    data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]"
                    data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false"
                    data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar=""
                    data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]'
                    data-export-options='{"fileName": "address-list", "ignoreColumn": ["operate"]}'
                    data-query-params="queryParams">
                    <thead>
                        <tr>
                            <th data-field="id" data-sortable="true">ID</th>
                            <th data-field="name" data-sortable="false">
                                <?= !empty($this->lang->line('name')) ? $this->lang->line('name') : 'Name' ?>
                            </th>
                            <th data-field="type" data-sortable="false" style="width: 110px; white-space: nowrap;">
                                <?= !empty($this->lang->line('type')) ? $this->lang->line('type') : 'Type' ?>
                            </th>
                            <th data-field="mobile" data-sortable="false">
                                <?= !empty($this->lang->line('mobile_number')) ? $this->lang->line('mobile_number') : 'Mobile' ?>
                            </th>
                            <th data-field="alternate_mobile" data-sortable="false">
                                <?= !empty($this->lang->line('alternate_mobile')) ? $this->lang->line('alternate_mobile') : 'Alternate Mobile' ?>
                            </th>
                            <th data-field="address" data-sortable="false" style="min-width: 320px;">
                                <?= !empty($this->lang->line('address')) ? $this->lang->line('address') : 'Address' ?>
                            </th>
                            <th data-field="city" data-sortable="false">
                                <?= !empty($this->lang->line('city')) ? $this->lang->line('city') : 'City' ?>
                            </th>
                            <th data-field="state" data-sortable="false">
                                <?= !empty($this->lang->line('state')) ? $this->lang->line('state') : 'State' ?>
                            </th>
                            <th data-field="country" data-sortable="false">
                                <?= !empty($this->lang->line('country')) ? $this->lang->line('country') : 'Country' ?>
                            </th>
                            <th data-field="pincode" data-sortable="false">
                                <?= !empty($this->lang->line('zipcode')) ? $this->lang->line('zipcode') : 'ZIP Code' ?>
                            </th>
                            <th data-field="action" data-sortable="true">
                                <?= !empty($this->lang->line('action')) ? $this->lang->line('action') : 'Action' ?>
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>

    <!-- address edit modal -->
    <div class="modal fade" data-bs-keyboard="false" tabindex="-1" id="address-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="staticBackdropLabel"><?= label('edit_address', 'Edit Address') ?></h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="<?= base_url('my-account/edit-address') ?>" method="POST" id="edit-address-form">
                        <input type="hidden" name="id" id="address_id" value="" />
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="edit_name" class="form-label fw-semibold small text-uppercase fw-bold"><?= label('name', 'Name') ?> <sup class="text-danger">*</sup></label>
                                <input type="text" class="form-control shadow-none edit-address-required" id="edit_name" name="name" placeholder="Enter Full Name" />
                                <div class="invalid-feedback d-block edit-address-field-error" data-for="edit_name"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_mobile" class="form-label fw-semibold small text-uppercase fw-bold"><?= label('contact_number', 'Contact Number') ?> <sup class="text-danger">*</sup></label>
                                <input type="text" class="form-control shadow-none edit-address-required" id="edit_mobile" name="mobile" placeholder="Enter Contact Number" />
                                <div class="invalid-feedback d-block edit-address-field-error" data-for="edit_mobile"></div>
                            </div>
                            <div class="col-12">
                                <label for="edit_address" class="form-label fw-semibold small text-uppercase fw-bold"><?= label('address', 'Address') ?> <sup class="text-danger">*</sup></label>
                                <textarea class="form-control shadow-none edit-address-required" name="address" id="edit_address" rows="2" placeholder="e.g. House No. 12, Green Avenue, Near Metro Station"></textarea>
                                <div class="invalid-feedback d-block edit-address-field-error" data-for="edit_address"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_country" class="form-label fw-semibold small text-uppercase fw-bold"><?= label('country', 'Country') ?> <sup class="text-danger">*</sup></label>
                                <input type="text" class="form-control shadow-none edit-address-required" name="country" id="edit_country" value="United States" readonly />
                                <div class="invalid-feedback d-block edit-address-field-error" data-for="edit_country"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_state" class="form-label fw-semibold small text-uppercase fw-bold"><?= label('state', 'State') ?> <sup class="text-danger">*</sup></label>
                                <input type="text" class="form-control shadow-none edit-address-required" id="edit_state" name="state" placeholder="Enter State" />
                                <div class="invalid-feedback d-block edit-address-field-error" data-for="edit_state"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_zipcode" class="form-label fw-semibold small text-uppercase fw-bold"><?= label('zipcode', 'ZIP Code') ?> <sup class="text-danger">*</sup></label>
                                <div class="position-relative zipcode-autocomplete-wrap">
                                    <input type="text" class="form-control shadow-none edit-address-required" id="edit_zipcode" name="pincode"
                                        inputmode="numeric" autocomplete="off" maxlength="10"
                                        placeholder="e.g. 10001 or 10001-1234" />
                                    <div id="edit-zipcode-suggestions" class="list-group zipcode-suggestions-list" style="display:none;"></div>
                                </div>
                                <div class="invalid-feedback d-block edit-address-field-error" data-for="edit_zipcode"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_city" class="form-label fw-semibold small text-uppercase fw-bold"><?= label('city', 'City') ?> <sup class="text-danger">*</sup></label>
                                <select class="form-control shadow-none edit-address-required" id="edit_city" name="city_id">
                                    <option value=""><?= label('city', 'Select City') ?></option>
                                    <?php if (!empty($cities)) {
                                        foreach ($cities as $city) { ?>
                                            <option value="<?= $city['id'] ?>"><?= htmlspecialchars($city['name'], ENT_QUOTES, 'UTF-8') ?></option>
                                    <?php }
                                    } ?>
                                </select>
                                <input type="hidden" name="city_name" id="edit_city_name" value="" />
                                <div class="invalid-feedback d-block edit-address-field-error" data-for="edit_city"></div>
                            </div>

                            <div class="col-12" id="edit-address-additional-section">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-3"><?= label('additional_information', 'Additional Information') ?></h6>
                                        <div class="mb-3">
                                            <label for="edit_alternate_mobile" class="form-label fw-semibold small text-uppercase fw-bold"><?= label('alternate_mobile', 'Alternate Mobile') ?></label>
                                            <input type="text" class="form-control shadow-none" id="edit_alternate_mobile" name="alternate_mobile" placeholder="Enter Alternate Mobile" />
                                        </div>
                                        <label class="form-label fw-semibold small text-uppercase fw-bold mb-3 d-block"><?= label('address_type', 'Address Type') ?></label>
                                        <div class="btn-group w-100" role="group" aria-label="Address Type Selection">
                                            <input type="radio" class="btn-check" name="type" id="edit_home" value="home" autocomplete="off" checked>
                                            <label class="btn btn-outline-primary py-2" for="edit_home"><?= label('home', 'Home') ?></label>

                                            <input type="radio" class="btn-check" name="type" id="edit_office" value="office" autocomplete="off">
                                            <label class="btn btn-outline-primary py-2" for="edit_office"><?= label('office', 'Office') ?></label>

                                            <input type="radio" class="btn-check" name="type" id="edit_other" value="other" autocomplete="off">
                                            <label class="btn btn-outline-primary py-2" for="edit_other"><?= label('other', 'Other') ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold text-uppercase" id="edit-address-submit-btn"><?= label('save_address', 'Save Address') ?></button>
                            </div>
                            <div class="col-12 text-center">
                                <div id="edit-address-result"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
            </div>
        </div>
    </div>
</main>

<style>
.zipcode-autocomplete-wrap { position: relative; }
.zipcode-suggestions-list {
    position: absolute; z-index: 1050; left: 0; right: 0; top: 100%;
    max-height: 220px; overflow-y: auto; margin-top: 2px;
    box-shadow: 0 4px 12px rgba(0,0,0,.12);
}
.zipcode-suggestion-item { font-size: 0.9rem; cursor: pointer; }
</style>
<script>
window.ADDRESS_ZIPCODES = <?= json_encode(!empty($zipcodes) ? $zipcodes : [], JSON_UNESCAPED_UNICODE) ?>;
</script>
<script src="<?= base_url('assets/front_end/zipcode-autocomplete.js') ?>"></script>

<script>
(function initAddressPageScript() {
    if (!window.jQuery || !window.AddressZipcodeAutocomplete) {
        return setTimeout(initAddressPageScript, 50);
    }

    (function ($) {
    var addZipBinding = window.AddressZipcodeAutocomplete.bind({
        zipcodes: window.ADDRESS_ZIPCODES,
        zipInput: '#zipcode',
        citySelect: '#city',
        suggestionsList: '#zipcode-suggestions'
    });
    var editZipBinding = window.AddressZipcodeAutocomplete.bind({
        zipcodes: window.ADDRESS_ZIPCODES,
        zipInput: '#edit_zipcode',
        citySelect: '#edit_city',
        suggestionsList: '#edit-zipcode-suggestions'
    });

    var addressFieldRules = {
        address_name: { label: 'Name', validate: function (v) { return v.trim() !== '' || 'Name is required.'; } },
        mobile_number: { label: 'Contact Number', validate: function (v) {
            if (v.trim() === '') return 'Contact Number is required.';
            if (!/^\d{7,16}$/.test(v.trim())) return 'Enter a valid contact number.';
            return true;
        }},
        address: { label: 'Address', validate: function (v) { return v.trim() !== '' || 'Address is required.'; } },
        country: { label: 'Country', validate: function (v) { return v.trim() !== '' || 'Country is required.'; } },
        state: { label: 'State', validate: function (v) { return v.trim() !== '' || 'State is required.'; } },
        zipcode: { label: 'ZIP Code', validate: function (v) {
            if (v.trim() === '') return 'ZIP Code is required.';
            if (!/^\d{5}(-\d{4})?$/.test(v.trim())) return 'Enter a valid US ZIP Code (e.g. 10001 or 10001-1234).';
            return true;
        }},
        city: { label: 'City', validate: function (v) { return v.trim() !== '' || 'City is required.'; } }
    };

    function showAddressFieldError(fieldId, message) {
        var $field = $('#' + fieldId);
        var $error = $('.address-field-error[data-for="' + fieldId + '"]');
        if (message) {
            $field.addClass('is-invalid');
            $error.text(message).show();
            return false;
        }
        $field.removeClass('is-invalid');
        $error.text('').hide();
        return true;
    }

    function validateAddressField(fieldId) {
        var rule = addressFieldRules[fieldId];
        if (!rule) return true;
        var value = $('#' + fieldId).val() || '';
        var result = rule.validate(value);
        if (result === true) {
            return showAddressFieldError(fieldId, '');
        }
        return showAddressFieldError(fieldId, result);
    }

    function allRequiredAddressFieldsValid() {
        return Object.keys(addressFieldRules).every(function (fieldId) {
            return validateAddressField(fieldId);
        });
    }

    function toggleAdditionalAddressSection() {
        if (allRequiredAddressFieldsValid()) {
            $('#address-additional-section').removeClass('d-none');
        }
    }

    var defaultProfileName = $('#address_name').val();
    var defaultProfileMobile = $('#mobile_number').val();

    function resetAddAddressForm() {
        $('#add-address-form')[0].reset();
        $('#address_name').val(defaultProfileName);
        $('#mobile_number').val(defaultProfileMobile);
        $('#country').val('United States');
        $('#city').val('');
        $('#city_name').val('');
        $('#address-additional-section').addClass('d-none');
        $('input[name="type"][value="home"]').prop('checked', true);
        $('.address-field-error').text('').hide();
        $('.address-required').removeClass('is-invalid');
    }

    $('#add-address-form .address-required').on('blur', function () {
        validateAddressField(this.id);
        toggleAdditionalAddressSection();
    });

    $('#add-address-form').off('submit').on('submit', function (e) {
        e.preventDefault();
        if (!allRequiredAddressFieldsValid()) {
            toggleAdditionalAddressSection();
            return false;
        }
        var formdata = new FormData(this);
        formdata.append(csrfName, csrfHash);
        var currentUrl = window.location.href;
        $.ajax({
            type: 'POST',
            data: formdata,
            url: $(this).attr('action'),
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#save-address-submit-btn').prop('disabled', true);
            },
            success: function (result) {
                csrfName = result.csrfName;
                csrfHash = result.csrfHash;
                if (result.error == false) {
                    if (typeof Toast !== 'undefined') {
                        Toast.fire({ icon: 'success', title: result.message });
                    }
                    resetAddAddressForm();
                    $('#address_list_table').bootstrapTable('refresh');
                    if (currentUrl.includes('/checkout')) {
                        $('#address-modal').modal('show');
                    }
                } else if (typeof Toast !== 'undefined') {
                    Toast.fire({ icon: 'error', title: result.message });
                } else {
                    $('#save-address-result').html("<div class='alert alert-danger'>" + result.message + '</div>');
                }
                $('#save-address-submit-btn').prop('disabled', false);
            }
        });
    });

    var editAddressFieldRules = {
        edit_name: addressFieldRules.address_name,
        edit_mobile: addressFieldRules.mobile_number,
        edit_address: addressFieldRules.address,
        edit_country: addressFieldRules.country,
        edit_state: addressFieldRules.state,
        edit_zipcode: addressFieldRules.zipcode,
        edit_city: addressFieldRules.city
    };

    function showEditAddressFieldError(fieldId, message) {
        var $field = $('#' + fieldId);
        var $error = $('.edit-address-field-error[data-for="' + fieldId + '"]');
        if (message) {
            $field.addClass('is-invalid');
            $error.text(message).show();
            return false;
        }
        $field.removeClass('is-invalid');
        $error.text('').hide();
        return true;
    }

    function validateEditAddressField(fieldId) {
        var rule = editAddressFieldRules[fieldId];
        if (!rule) return true;
        var value = $('#' + fieldId).val() || '';
        var result = rule.validate(value);
        if (result === true) {
            return showEditAddressFieldError(fieldId, '');
        }
        return showEditAddressFieldError(fieldId, result);
    }

    $('#edit-address-form .edit-address-required').on('blur', function () {
        validateEditAddressField(this.id);
    });

    $('#edit-address-form').off('submit').on('submit', function (e) {
        e.preventDefault();
        var isValid = Object.keys(editAddressFieldRules).every(function (fieldId) {
            return validateEditAddressField(fieldId);
        });
        if (!isValid) {
            return false;
        }
        var formdata = new FormData(this);
        formdata.append(csrfName, csrfHash);
        $.ajax({
            type: 'POST',
            data: formdata,
            url: $(this).attr('action'),
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#edit-address-submit-btn').prop('disabled', true);
            },
            success: function (result) {
                csrfName = result.csrfName;
                csrfHash = result.csrfHash;
                if (result.error == false) {
                    if (typeof Toast !== 'undefined') {
                        Toast.fire({ icon: 'success', title: result.message });
                    }
                    $('#address_list_table').bootstrapTable('refresh');
                    setTimeout(function () {
                        $('#address-modal').modal('hide');
                    }, 1000);
                } else if (typeof Toast !== 'undefined') {
                    Toast.fire({ icon: 'error', title: result.message });
                }
                $('#edit-address-submit-btn').prop('disabled', false);
            }
        });
    });

    $(window).on('load', function () {
        function populateEditAddressForm(row) {
            $("#address_id").val(row.id);
            $("#edit_name").val(row.name);
            $("#edit_mobile").val(row.mobile);
            $("#edit_alternate_mobile").val(row.alternate_mobile);
            $("#edit_address").val(row.address);
            $("#edit_state").val(row.state);
            $("#edit_country").val("United States");
            $("#edit_zipcode").val(row.pincode);
            if (editZipBinding) {
                editZipBinding.setCityByIdOrName(row.city_id, row.city);
                editZipBinding.setZipcode(row.pincode);
            }
            $('#edit-address-form input[name="type"]').prop('checked', false);
            $('#edit-address-form input[name="type"][value="' + (row.type || 'home').toLowerCase() + '"]').prop('checked', true);
        }

        window.editAddress = {
            'click .edit-address': function (e, value, row) {
                populateEditAddressForm(row);
            }
        };

        $("#address_list_table").off('click-cell.bs.table').on('click-cell.bs.table', function (e, field, value, row) {
            if (field !== 'action') {
                return;
            }
            populateEditAddressForm(row);
        });
    });
    })(window.jQuery);
})();
</script>

