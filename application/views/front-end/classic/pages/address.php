<!-- breadcrumb -->
<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h2><?= !empty($this->lang->line('address')) ? $this->lang->line('address') : 'Address' ?></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a>
                </li>
                <li class="breadcrumb-item"><a
                        href="<?= base_url('my-account') ?>"><?= !empty($this->lang->line('dashboard')) ? $this->lang->line('dashboard') : 'Dashboard' ?></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <?= !empty($this->lang->line('address')) ? $this->lang->line('address') : 'Address' ?>
                </li>
            </ol>
        </nav>
    </div>

</section>
<!-- end breadcrumb -->
<section class="my-account-section">
    <div class="main-content">
        <div class="col-md-12 mt-5 mb-3">
            <div class="user-detail align-items-center">
                <div class="ml-3">
                    <h6 class="text-muted mb-0">
                        <?= !empty($this->lang->line('hello')) ? $this->lang->line('hello') : 'Hello' ?>
                    </h6>
                    <h5 class="mb-0"><?= $user->username ?></h5>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-md-4">
                <?php $this->load->view('front-end/' . THEME . '/pages/my-account-sidebar') ?>
            </div>
            <div class="col-md-8 col-12">
                <div class='card border-0'>
                    <div class="card-header bg-white">
                        <h1 class="h4">
                            <?= !empty($this->lang->line('address')) ? $this->lang->line('address') : 'Address' ?>
                        </h1>
                    </div>
                    <?php
                    $profile_name = !empty($user->username) ? htmlspecialchars($user->username, ENT_QUOTES, 'UTF-8') : '';
                    $profile_mobile = !empty($user->mobile) ? htmlspecialchars($user->mobile, ENT_QUOTES, 'UTF-8') : '';
                    ?>
                    <form action="<?= base_url('my-account/add-address') ?>" method="POST" id="add-address-form"
                        class="mt-4 p-4">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                <label for="name"
                                    class="control-label"><?= !empty($this->lang->line('name')) ? $this->lang->line('name') : 'Name' ?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control address-required" id="address_name" name="name"
                                    value="<?= $profile_name ?>"
                                    placeholder="<?= !empty($this->lang->line('name')) ? $this->lang->line('name') : 'Name' ?>" />
                                <div class="text-danger small address-field-error" data-for="address_name"></div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                <label for="mobile_number"
                                    class="control-label"><?= label('contact_number', 'Contact Number') ?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control address-required" id="mobile_number" name="mobile"
                                    value="<?= $profile_mobile ?>"
                                    placeholder="<?= label('contact_number', 'Contact Number') ?>" />
                                <div class="text-danger small address-field-error" data-for="mobile_number"></div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                <label for="address"
                                    class="control-label"><?= !empty($this->lang->line('address')) ? $this->lang->line('address') : 'Address' ?> <span class="text-danger">*</span></label>
                                <textarea name="address" class="form-control address-required" id="address" cols="30" rows="4"
                                    placeholder="e.g. House No. 12, Green Avenue, Near Metro Station"></textarea>
                                <div class="text-danger small address-field-error" data-for="address"></div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                <label for="country"
                                    class="control-label"><?= !empty($this->lang->line('country')) ? $this->lang->line('country') : 'Country' ?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control address-required" name="country" id="country"
                                    value="United States" readonly />
                                <div class="text-danger small address-field-error" data-for="country"></div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                                <label for="state"
                                    class="control-label"><?= !empty($this->lang->line('state')) ? $this->lang->line('state') : 'State' ?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control address-required" id="state" name="state"
                                    placeholder="<?= !empty($this->lang->line('state')) ? $this->lang->line('state') : 'State' ?>" />
                                <div class="text-danger small address-field-error" data-for="state"></div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                                <label for="zipcode"
                                    class="control-label"><?= !empty($this->lang->line('zipcode')) ? $this->lang->line('zipcode') : 'ZIP Code' ?> <span class="text-danger">*</span></label>
                                <div class="position-relative zipcode-autocomplete-wrap">
                                    <input type="text" class="form-control address-required" id="zipcode" name="pincode"
                                        inputmode="numeric" autocomplete="off" maxlength="10"
                                        placeholder="e.g. 10001 or 10001-1234" />
                                    <div id="zipcode-suggestions" class="list-group zipcode-suggestions-list" style="display:none;"></div>
                                </div>
                                <div class="text-danger small address-field-error" data-for="zipcode"></div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                                <label for="city"
                                    class="control-label"><?= !empty($this->lang->line('city')) ? $this->lang->line('city') : 'City' ?> <span class="text-danger">*</span></label>
                                <div class="position-relative zipcode-autocomplete-wrap">
                                    <input type="text" class="form-control address-required" id="city" name="city_name"
                                        autocomplete="off" placeholder="<?= !empty($this->lang->line('city')) ? $this->lang->line('city') : 'City' ?>" />
                                    <div id="city-suggestions" class="list-group zipcode-suggestions-list" style="display:none;"></div>
                                </div>
                                <input type="hidden" name="city_id" id="city_id" value="" />
                                <div class="text-danger small address-field-error" data-for="city"></div>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12 d-none" id="address-additional-section">
                                <div class="card bg-light border-0 mb-3">
                                    <div class="card-body">
                                        <h6 class="font-weight-bold mb-3"><?= label('additional_information', 'Additional Information') ?></h6>
                                        <div class="form-group">
                                            <label for="alternate_mobile"
                                                class="control-label"><?= !empty($this->lang->line('alternate_mobile')) ? $this->lang->line('alternate_mobile') : 'Alternate Mobile Number' ?></label>
                                            <input type="text" class="form-control" id="alternate_mobile" name="alternate_mobile"
                                                placeholder="<?= !empty($this->lang->line('alternate_mobile')) ? $this->lang->line('alternate_mobile') : 'Alternate Mobile' ?>" />
                                        </div>
                                        <div class="form-group mb-0">
                                            <label class="control-label"><?= !empty($this->lang->line('type')) ? $this->lang->line('type') : 'Type' ?></label>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input" name="type" id="home" value="home" checked />
                                                <label for="home"
                                                    class="form-check-label text-dark"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input" name="type" id="office" value="office" />
                                                <label for="office"
                                                    class="form-check-label text-dark"><?= !empty($this->lang->line('office')) ? $this->lang->line('office') : 'Office' ?></label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input" name="type" id="other" value="other" />
                                                <label for="other"
                                                    class="form-check-label text-dark"><?= !empty($this->lang->line('other')) ? $this->lang->line('other') : 'Other' ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                <input type="submit" class="btn btn-primary" id="save-address-submit-btn"
                                    value="<?= !empty($this->lang->line('save')) ? $this->lang->line('save') : 'Save' ?> " />
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                <div id="save-address-result"></div>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="card-body">
                        <table id="address_list_table" class="table-striped" data-toggle="table"
                            data-url="<?= base_url('my-account/get-address-list') ?>" data-side-pagination="server"
                            data-pagination="true" data-search="true" data-sort-name="id" data-sort-order="desc"
                            data-page-list="[5,10,20,50,100,200]" data-show-columns="true" data-show-refresh="true"
                            data-mobile-responsive="true" data-show-export="true" data-maintain-selected="true"
                            data-export-types='["txt","excel"]' data-export-options='{
                            "fileName": "address-list",
                            "ignoreColumn": ["operate"]
                        }'>

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
                                    <th data-field="action" data-sortable="false">
                                        <?= !empty($this->lang->line('action')) ? $this->lang->line('action') : 'Action' ?>
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!--end container-->
</section>
<div class="modal fade edit-modal-lg" id="address-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">
                    <?= !empty($this->lang->line('edit_address')) ? $this->lang->line('edit_address') : 'Edit Address' ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('my-account/edit-address') ?>" method="POST" id="edit-address-form"
                    class="mt-4">
                    <input type="hidden" name="id" id="address_id" value="" />
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                            <label for="name"
                                class="control-label"><?= !empty($this->lang->line('name')) ? $this->lang->line('name') : 'Name' ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control edit-address-required" id="edit_name" name="name" placeholder="Name" />
                            <div class="text-danger small edit-address-field-error" data-for="edit_name"></div>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                            <label for="mobile_number"
                                class="control-label"><?= label('contact_number', 'Contact Number') ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control edit-address-required" id="edit_mobile" name="mobile"
                                placeholder="<?= label('contact_number', 'Contact Number') ?>" />
                            <div class="text-danger small edit-address-field-error" data-for="edit_mobile"></div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="address"
                                class="control-label"><?= !empty($this->lang->line('address')) ? $this->lang->line('address') : 'Address' ?> <span class="text-danger">*</span></label>
                            <textarea class="form-control edit-address-required" name="address" id="edit_address" rows="3"
                                placeholder="e.g. House No. 12, Green Avenue, Near Metro Station"></textarea>
                            <div class="text-danger small edit-address-field-error" data-for="edit_address"></div>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                            <label for="country"
                                class="control-label"><?= !empty($this->lang->line('country')) ? $this->lang->line('country') : 'Country' ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control edit-address-required" name="country" id="edit_country"
                                value="United States" readonly />
                            <div class="text-danger small edit-address-field-error" data-for="edit_country"></div>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                            <label for="state"
                                class="control-label"><?= !empty($this->lang->line('state')) ? $this->lang->line('state') : 'State' ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control edit-address-required" id="edit_state" name="state" placeholder="State" />
                            <div class="text-danger small edit-address-field-error" data-for="edit_state"></div>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                            <label for="edit_zipcode"
                                class="control-label"><?= !empty($this->lang->line('zipcode')) ? $this->lang->line('zipcode') : 'ZIP Code' ?> <span class="text-danger">*</span></label>
                            <div class="position-relative zipcode-autocomplete-wrap">
                                <input type="text" class="form-control edit-address-required" id="edit_zipcode" name="pincode"
                                    inputmode="numeric" autocomplete="off" maxlength="10"
                                    placeholder="e.g. 10001 or 10001-1234" />
                                <div id="edit-zipcode-suggestions" class="list-group zipcode-suggestions-list" style="display:none;"></div>
                            </div>
                            <div class="text-danger small edit-address-field-error" data-for="edit_zipcode"></div>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                            <label for="edit_city"
                                class="control-label"><?= !empty($this->lang->line('city')) ? $this->lang->line('city') : 'City' ?> <span class="text-danger">*</span></label>
                            <div class="position-relative zipcode-autocomplete-wrap">
                                <input type="text" class="form-control edit-address-required" id="edit_city" name="city_name"
                                    autocomplete="off" placeholder="<?= !empty($this->lang->line('city')) ? $this->lang->line('city') : 'City' ?>" />
                                <div id="edit-city-suggestions" class="list-group zipcode-suggestions-list" style="display:none;"></div>
                            </div>
                            <input type="hidden" name="city_id" id="edit_city_id" value="" />
                            <div class="text-danger small edit-address-field-error" data-for="edit_city"></div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12" id="edit-address-additional-section">
                            <div class="card bg-light border-0 mb-3">
                                <div class="card-body">
                                    <h6 class="font-weight-bold mb-3"><?= label('additional_information', 'Additional Information') ?></h6>
                                    <div class="form-group">
                                        <label for="edit_alternate_mobile"
                                            class="control-label"><?= !empty($this->lang->line('alternate_mobile')) ? $this->lang->line('alternate_mobile') : 'Alternate Mobile' ?></label>
                                        <input type="text" class="form-control" id="edit_alternate_mobile" name="alternate_mobile"
                                            placeholder="Alternate Mobile" />
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="control-label"><?= !empty($this->lang->line('type')) ? $this->lang->line('type') : 'Type' ?></label>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" class="form-check-input" name="type" id="edit_home" value="home" checked />
                                            <label for="edit_home"
                                                class="form-check-label text-dark"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" class="form-check-input" name="type" id="edit_office" value="office" />
                                            <label for="edit_office"
                                                class="form-check-label text-dark"><?= !empty($this->lang->line('office')) ? $this->lang->line('office') : 'Office' ?></label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" class="form-check-input" name="type" id="edit_other" value="other" />
                                            <label for="edit_other"
                                                class="form-check-label text-dark"><?= !empty($this->lang->line('other')) ? $this->lang->line('other') : 'Other' ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                            <input type="submit" class="btn btn-primary" id="edit-address-submit-btn" value="Save" />
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 text-center mt-2">
                            <div id="edit-address-result"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="module" src="<?= base_url('assets/front_end/modern/ionicons/dist/ionicons/ionicons.esm.js') ?>"></script>
<script nomodule src="<?= base_url('assets/front_end/modern/ionicons/dist/ionicons/ionicons.js') ?>"></script>
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
window.ADDRESS_CITIES = <?= json_encode(array_map(function ($c) {
    return ['id' => $c['id'], 'name' => $c['name']];
}, !empty($cities) ? $cities : []), JSON_UNESCAPED_UNICODE) ?>;
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
        cities: window.ADDRESS_CITIES,
        zipInput: '#zipcode',
        cityInput: '#city',
        cityIdInput: '#city_id',
        zipSuggestionsList: '#zipcode-suggestions',
        citySuggestionsList: '#city-suggestions'
    });
    var editZipBinding = window.AddressZipcodeAutocomplete.bind({
        zipcodes: window.ADDRESS_ZIPCODES,
        cities: window.ADDRESS_CITIES,
        zipInput: '#edit_zipcode',
        cityInput: '#edit_city',
        cityIdInput: '#edit_city_id',
        zipSuggestionsList: '#edit-zipcode-suggestions',
        citySuggestionsList: '#edit-city-suggestions'
    });

    var addressFieldRules = {
        address_name: { validate: function (v) { return v.trim() !== '' || 'Name is required.'; } },
        mobile_number: { validate: function (v) {
            if (v.trim() === '') return 'Contact Number is required.';
            if (!/^\d{7,16}$/.test(v.trim())) return 'Enter a valid contact number.';
            return true;
        }},
        address: { validate: function (v) { return v.trim() !== '' || 'Address is required.'; } },
        country: { validate: function (v) { return v.trim() !== '' || 'Country is required.'; } },
        state: { validate: function (v) { return v.trim() !== '' || 'State is required.'; } },
        zipcode: { validate: function (v) {
            if (v.trim() === '') return 'ZIP Code is required.';
            if (!/^\d{5}(-\d{4})?$/.test(v.trim())) return 'Enter a valid US ZIP Code (e.g. 10001 or 10001-1234).';
            return true;
        }},
        city: { validate: function (v) { return v.trim() !== '' || 'City is required.'; } }
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
        var result = rule.validate($('#' + fieldId).val() || '');
        return result === true ? showAddressFieldError(fieldId, '') : showAddressFieldError(fieldId, result);
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
        $('#city_id').val('');
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
        $.ajax({
            type: 'POST',
            data: formdata,
            url: $(this).attr('action'),
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#save-address-submit-btn').val('Please Wait...').attr('disabled', true);
            },
            success: function (result) {
                csrfName = result.csrfName;
                csrfHash = result.csrfHash;
                if (result.error == false) {
                    $('#save-address-result')
                        .html("<div class='alert alert-success'>" + result.message + '</div>')
                        .delay(1500).fadeOut();
                    resetAddAddressForm();
                    $('#address_list_table').bootstrapTable('refresh');
                } else {
                    $('#save-address-result')
                        .html("<div class='alert alert-danger'>" + result.message + '</div>')
                        .delay(1500).fadeOut();
                }
                $('#save-address-submit-btn').val('Save').attr('disabled', false);
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
        var result = rule.validate($('#' + fieldId).val() || '');
        return result === true ? showEditAddressFieldError(fieldId, '') : showEditAddressFieldError(fieldId, result);
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
                $('#edit-address-submit-btn').val('Please Wait...').attr('disabled', true);
            },
            success: function (result) {
                csrfName = result.csrfName;
                csrfHash = result.csrfHash;
                if (result.error == false) {
                    $('#edit-address-result')
                        .html("<div class='alert alert-success'>" + result.message + '</div>')
                        .delay(1500).fadeOut();
                    $('#address_list_table').bootstrapTable('refresh');
                    setTimeout(function () {
                        $('#address-modal').modal('hide');
                    }, 2000);
                } else {
                    $('#edit-address-result')
                        .html("<div class='alert alert-danger'>" + result.message + '</div>')
                        .delay(1500).fadeOut();
                }
                $('#edit-address-submit-btn').val('Save').attr('disabled', false);
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