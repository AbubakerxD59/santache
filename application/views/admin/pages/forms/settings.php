<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header mt-2">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h4>System Settings</h4>
                </div>
                <div class="col-sm-4 d-flex justify-content-end">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">System settings</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
        <div class="container-fluid">
            <div class="row">
                <form class="form-horizontal form-submit-event"
                    action="<?= base_url('admin/setting/update_system_settings') ?>" method="POST"
                    id="system_setting_form" enctype="multipart/form-data">
                    <div class="col-md-12">
                        <div id="error_box" class="d-none"></div>
                        <div class="row">
                            <div class="col-md-8 ">
                                <div class="card">
                                    <b class="m-2">
                                        System Settings
                                    </b>
                                    <hr>
                                    <input type="hidden" id="system_configurations" name="system_configurations"
                                        required="" value="1" aria-required="true">
                                    <input type="hidden" id="system_timezone_gmt" name="system_timezone_gmt"
                                        value="<?= (isset($settings['system_timezone_gmt']) && !empty($settings['system_timezone_gmt'])) ? $settings['system_timezone_gmt'] : '+05:30'; ?>"
                                        aria-required="true">
                                    <input type="hidden" id="system_configurations_id" name="system_configurations_id"
                                        value="13" aria-required="true">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="mb-2" for="app_name">App Name <span
                                                        class='text-danger text-xs'>*</span></label>
                                                <input type="text" class="form-control mb-2" name="app_name"
                                                    value="<?= (isset($settings['app_name'])) ? $settings['app_name'] : '' ?>"
                                                    placeholder="Name of the App - used in whole system" />
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="mb-2" for="support_number">Support Number <span
                                                        class='text-danger text-xs'>*</span></label>
                                                <input type="number" min="0" class="form-control mb-2"
                                                    name="support_number"
                                                    value="<?= (isset($settings['support_number'])) ? $settings['support_number'] : '' ?>"
                                                    placeholder="Customer support mobile number - used in whole system" />
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="mb-2" for="support_email">Support Email <span
                                                        class='text-danger text-xs'>*</span></label>
                                                <input type="text" class="form-control mb-2" name="support_email"
                                                    value="<?= (isset($settings['support_email'])) ? $settings['support_email'] : '' ?>"
                                                    placeholder="Customer support email - used in whole system" />
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="address">Copyright Details <span
                                                        class='text-danger text-xs'>*</span></label>
                                                <textarea name="copyright_details" id="copyright_details"
                                                    class="form-control" cols="10"
                                                    rows="2"><?= (isset($settings['copyright_details'])) ? output_escaping($settings['copyright_details']) : '' ?></textarea>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="mb-2" class="system_timezone" for="system_timezone">System
                                                    Timezone <span class='text-danger text-xs'>*</span></label>
                                                <select id="system_timezone" name="system_timezone" required
                                                    class="form-control col-md-12">
                                                    <option value=" ">--Select Timezones--</option>
                                                    <?php
                                                    foreach ($timezone as $zone) {
                                                        $checked = (isset($settings['system_timezone']) && $settings['system_timezone'] == $zone[2]) ? 'selected' : '';
                                                        ?>
                                                        <option value="<?= $zone[2] ?>" <?= $checked ?>
                                                            data-gmt="<?= $zone[1] ?>">
                                                            <?= $zone[0] . ' - GMT ' . $zone[1] . ' - ' . $zone[2] ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="mb-2" for="tax_name">Tax Name <small>( This will be
                                                        visible on your invoice )</small></label>
                                                <input type="text" class="form-control mb-2" name="tax_name"
                                                    value="<?= (isset($settings['tax_name'])) ? $settings['tax_name'] : '' ?>"
                                                    placeholder='Example : GST Number / VAT / TIN Number' />
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="mb-2" for="tax_number">Tax Number </label>
                                                <input type="text" class="form-control mb-2" name="tax_number"
                                                    value="<?= (isset($settings['tax_number'])) ? $settings['tax_number'] : '' ?>"
                                                    placeholder='Example : GSTIN240000120' />
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="mb-2" for="max_items_cart"> Low stock limit
                                                    <small>(Product will be considered as low stock)</small>
                                                </label>
                                                <input type="number" min="1" class="form-control mb-2"
                                                    name="low_stock_limit"
                                                    value="<?= (isset($settings['low_stock_limit'])) ? $settings['low_stock_limit'] : '5' ?>"
                                                    placeholder='Product low stock limit' />
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="mb-2" for="address"> Address <span
                                                        class='text-danger text-xs'>*</span></label>
                                                <textarea type="text" class="form-control mb-2" id="address"
                                                    placeholder="Address"
                                                    name="address"><?= isset($settings['address']) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $settings['address'])) : ""; ?></textarea>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="mb-2" for="admin_store_state"> Admin Store State <span
                                                        class='text-danger text-xs'>*</span></label>
                                                <textarea type="text" class="form-control mb-2" id="admin_store_state"
                                                    placeholder="admin_store_state"
                                                    name="admin_store_state"><?= isset($settings['admin_store_state']) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $settings['admin_store_state'])) : ""; ?></textarea>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="mb-2" for="latitude">Latitude <span
                                                        class='text-danger text-xs'>*</span></label>
                                                <input type="text" class="form-control mb-2" name="latitude"
                                                    value="<?= (isset($settings['latitude'])) ? $settings['latitude'] : '' ?>"
                                                    placeholder="Latitude" />
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="mb-2" for="longitude">Longitude <span
                                                        class='text-danger text-xs'>*</span></label>
                                                <input type="text" class="form-control mb-2" name="longitude"
                                                    value="<?= (isset($settings['longitude'])) ? $settings['longitude'] : '' ?>"
                                                    placeholder="Longitude" />
                                            </div>
                                            <div class="form-group col-md-6 ml-1">
                                                <label class="mb-2" for="">Max days to return item</label>
                                                <input type="number" min="0" class="form-control mb-2"
                                                    name="max_product_return_days"
                                                    value="<?= (isset($settings['max_product_return_days'])) ? $settings['max_product_return_days'] : '' ?>"
                                                    placeholder='Max days to return item' />
                                            </div>
                                            <div class="form-group col-md-6 ">
                                                <label class="mb-2" for="minimum_cart_amt">Minimum Cart
                                                    Amount(<?= $currency ?>) <span class='text-danger text-xs'>*</span>
                                                </label>
                                                <input type="number" min="0" class="form-control mb-2"
                                                    name="minimum_cart_amt"
                                                    value="<?= (isset($settings['minimum_cart_amt'])) ? $settings['minimum_cart_amt'] : '' ?>"
                                                    placeholder='Minimum Cart Amount' />
                                            </div>
                                            <div class="form-group col-md-6 ml-1">
                                                <label class="mb-2" for="max_items_cart"> Maximum Items Allowed In Cart
                                                    <span class='text-danger text-xs'>*</span>
                                                </label>
                                                <input type="number" min="1" class="form-control mb-2"
                                                    name="max_items_cart"
                                                    value="<?= (isset($settings['max_items_cart'])) ? $settings['max_items_cart'] : '' ?>"
                                                    placeholder='Maximum Items Allowed In Cart' />
                                            </div>
                                            <!-- <div class="form-group col-md-6">
                                                <label class="mb-2" for="platform_fees">Platform Fees (<?= $currency ?>)
                                                    <span class='text-danger text-xs'>*</span>
                                                </label>
                                                <input type="number" step="0.01" min="0" class="form-control mb-2"
                                                    name="platform_fees"
                                                    value="<?= (isset($settings['platform_fees'])) ? $settings['platform_fees'] : '0' ?>"
                                                    placeholder='Platform Fees Amount' />
                                            </div> -->
                                            <div class="form-group col-md-6">
                                                <label class="mb-2" for="min_cod_order_amount">Minimum COD Order Amount
                                                    (<?= $currency ?>)</label>
                                                <input type="number" min="0" step="0.01" class="form-control mb-2"
                                                    name="min_cod_order_amount"
                                                    value="<?= (isset($settings['min_cod_order_amount'])) ? $settings['min_cod_order_amount'] : '' ?>"
                                                    placeholder='Minimum Order Amount for COD' />
                                                <small class="text-muted">Leave empty for no minimum limit</small>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="mb-2" for="max_cod_order_amount">Maximum COD Order Amount
                                                    (<?= $currency ?>)</label>
                                                <input type="number" min="0" step="0.01" class="form-control mb-2"
                                                    name="max_cod_order_amount"
                                                    value="<?= (isset($settings['max_cod_order_amount'])) ? $settings['max_cod_order_amount'] : '' ?>"
                                                    placeholder='Maximum Order Amount for COD' />
                                                <small class="text-muted">Leave empty for no maximum limit</small>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 ">
                                <div class="card ">
                                    <div class="card-body">
                                        <div class="row">
                                            <b>
                                                Logo & Other Settings
                                            </b>
                                            <hr>
                                            <div class="col-md-12 form-group">
                                                <div class="col-md-12 form-group mt-4">
                                                    <b>Logo</b>
                                                    <div class="d-flex ">
                                                        <div class='col-md-8 border refer_and_earn_border'><a class=""
                                                                data-input='logo' data-isremovable='0'
                                                                data-is-multiple-uploads-allowed='0' data-toggle="modal"
                                                                data-target="#media-upload-modal"
                                                                value="Upload Photo"><i
                                                                    class='bx bx-image-add box-icon-height'></i> </a>
                                                            <br><b>Drop your image here, or</b> browse<br> Larger than
                                                            120x120 & smaller than 150x150<br>
                                                        </div>
                                                        <?php
                                                        if (!empty($logo)) {
                                                            ?>
                                                            <div class=" image-upload-section store_settings">
                                                                <div
                                                                    class='upload-media-div shadow mx-2 bg-white rounded  text-center grow image'>
                                                                    <img class="img-fluid " src="<?= BASE_URL() . $logo ?>"
                                                                        alt="Image Not Found">
                                                                </div>
                                                                <input type="hidden" name="logo" id='logo'
                                                                    value='<?= $logo ?>'>
                                                            </div>
                                                            <?php
                                                        } else { ?>
                                                            <div class="container-fluid row image-upload-section">
                                                                <div class="">
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 form-group mt-4">
                                                    <b>Favicon</b>
                                                    <div class="d-flex ">
                                                        <div class='col-md-8 border refer_and_earn_border'><a class=""
                                                                data-input='favicon' data-isremovable='0'
                                                                data-is-multiple-uploads-allowed='0' data-toggle="modal"
                                                                data-target="#media-upload-modal"
                                                                value="Upload Photo"><i
                                                                    class='bx bx-image-add box-icon-height'></i> </a>
                                                            <br><b>Drop your image here, or</b> browse<br> Larger than
                                                            120x120 & smaller than 150x150<br>
                                                        </div>
                                                        <?php
                                                        if (!empty($favicon)) {
                                                            ?>
                                                            <div class=" image-upload-section store_settings col-md-4">
                                                                <div
                                                                    class='upload-media-div shadow mx-2 bg-white rounded  text-center grow image'>
                                                                    <img class="img-fluid "
                                                                        src="<?= BASE_URL() . $favicon ?>"
                                                                        alt="Image Not Found">
                                                                </div>
                                                                <input type="hidden" name="favicon" id='favicon'
                                                                    value='<?= $favicon ?>'>
                                                            </div>
                                                            <?php
                                                        } else { ?>
                                                            <div class="container-fluid row image-upload-section">
                                                                <div class="">
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-12 d-flex justify-content-between mt-5">
                                                    <label class="mb-2" for="cart_btn_on_list"> Enable Cart Button on
                                                        Products List view? </label>
                                                    <div>
                                                        <a class="toggle form-switch  mr-1 mb-1" title="Deactivate"
                                                            href="javascript:void(0)"> <input type="checkbox"
                                                                class="form-check-input " role="switch"
                                                                name="cart_btn_on_list"
                                                                <?= (isset($settings['cart_btn_on_list']) && $settings['cart_btn_on_list'] == '1') ? 'Checked' : '' ?> /></a>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-12 d-flex justify-content-between mt-5">
                                                    <div class="row">
                                                        <label class="mb-2" for="expand_product_images"> Expand Product
                                                            Images? </label>
                                                        <small>Image will be stretched in the product image
                                                            boxes</small>
                                                    </div>
                                                    <div class="">
                                                        <a class="toggle form-switch  mr-1 mb-1" title="Deactivate"
                                                            href="javascript:void(0)"> <input type="checkbox"
                                                                class="form-check-input " role="switch"
                                                                name="expand_product_images"
                                                                <?= (isset($settings['expand_product_images']) && $settings['expand_product_images'] == '1') ? 'Checked' : '' ?> /></a>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-12 d-flex justify-content-between mt-4">
                                                    <label class="mb-2" for="local_pickup"> Enable Local / Store Pickup
                                                        ? </label>
                                                    <?php if (isset($shiprocket_settings['local_shipping_method']) && $shiprocket_settings['local_shipping_method'] == 1) { ?>
                                                        <div class="">
                                                            <a class="toggle form-switch  mr-1 mb-1" title="Deactivate"
                                                                href="javascript:void(0)"> <input type="checkbox"
                                                                    class="form-check-input " role="switch"
                                                                    name="local_pickup" <?= (isset($settings['local_pickup']) && $settings['local_pickup'] == '1') ? 'Checked' : '' ?> /></a>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="">
                                                            <a class="toggle form-switch  mr-1 mb-1" title="Deactivate"
                                                                href="javascript:void(0)"><input type="checkbox"
                                                                    name="local_pickup" class="form-check-input "
                                                                    role="switch" disabled></a>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-body">
                                    <b class="m-2">
                                        Custom Charges
                                    </b>
                                    <hr>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <label class="mb-0">Custom Charges</label>

                                                    <button type="button" id="add_custom_charge"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fa fa-plus"></i> Add
                                                    </button>
                                                </div>
                                                <a href="javascript:void(0)" class="text-primary fw-bold"
                                                    data-toggle="modal" data-target="#refundableInstructionModal">
                                                    <i class="fa fa-info-circle"></i> Refundable Charges Instruction
                                                </a>
                                                <div class="modal fade" id="refundableInstructionModal" tabindex="-1"
                                                    aria-labelledby="refundableInstructionLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">

                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="refundableInstructionLabel">
                                                                    Refundable Charges Instruction</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-dismiss="modal"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <ul class="ps-3">
                                                                    <li>Enable <b>Refundable</b> only for charges that
                                                                        should be returned to the customer on order
                                                                        cancellation or return.</li>

                                                                    <li>Choose applicable order types (POS, Doorstep
                                                                        Delivery, Pickup, Digital Product) carefully.
                                                                    </li>

                                                                    <li>If an order contains both returnable/cancellable
                                                                        and non-returnable/non-cancellable items,
                                                                        refundable custom charges will not be included
                                                                        in the refund.</li>

                                                                    <li>Refundable custom charges will be added only
                                                                        when all items are returnable and the last item
                                                                        is cancelled or returned.</li>
                                                                </ul>
                                                            </div>  

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>


                                                <div id="custom_charges_wrapper">
                                                    <?php
                                                    $i = 0;
                                                    /* Platform fee fallback checking */
                                                    $exists = in_array('platform_fees', array_column($custom_charges ?? [], 'name'));

                                                    // RENDER FALLBACK ROW IF NEEDED
                                                    if (!$exists && isset($settings['platform_fees']) && $settings['platform_fees'] > 0) { ?>
                                                        <div class="custom-charge-row border rounded p-3 mb-3 bg-light"
                                                            id="charge_row_0">
                                                            <div class="row align-items-center g-3">

                                                                <!-- Name -->
                                                                <div class="col-md-3">
                                                                    <label
                                                                        class="form-label small fw-bold mb-1">Title</label>
                                                                    <input type="text" name="custom_charges[0][name]"
                                                                        class="form-control" value="platform fees" required>
                                                                </div>

                                                                <!-- Amount -->
                                                                <div class="col-md-2">
                                                                    <label
                                                                        class="form-label small fw-bold mb-1">Amount</label>
                                                                    <input type="number" name="custom_charges[0][amount]"
                                                                        class="form-control" step="0.01" min="0"
                                                                        value="<?= $settings['platform_fees'] ?>" required>
                                                                </div>

                                                                <!-- Switches -->
                                                                <div class="col-md-6">
                                                                    <label
                                                                        class="form-label small fw-bold mb-1 d-block">Applicable
                                                                        On</label>
                                                                    <div
                                                                        class="d-flex flex-wrap gap-3 align-items-center bg-white rounded p-2 border">

                                                                        <div class="form-check form-switch mb-0">
                                                                            <input class="form-check-input" type="checkbox"
                                                                                name="custom_charges[0][apply_pos]" checked>
                                                                            <label
                                                                                class="form-check-label small">POS</label>
                                                                        </div>

                                                                        <div class="form-check form-switch mb-0">
                                                                            <input class="form-check-input" type="checkbox"
                                                                                name="custom_charges[0][apply_doorstep]"
                                                                                checked>
                                                                            <label
                                                                                class="form-check-label small">Door</label>
                                                                        </div>

                                                                        <div class="form-check form-switch mb-0">
                                                                            <input class="form-check-input" type="checkbox"
                                                                                name="custom_charges[0][apply_pickup]"
                                                                                checked>
                                                                            <label
                                                                                class="form-check-label small">Pickup</label>
                                                                        </div>

                                                                        <div class="form-check form-switch mb-0">
                                                                            <input class="form-check-input" type="checkbox"
                                                                                name="custom_charges[0][apply_digital]"
                                                                                checked>
                                                                            <label class="form-check-label small">Digital
                                                                                product</label>
                                                                        </div>

                                                                        <div class="vr"></div>

                                                                        <div class="form-check form-switch mb-0">
                                                                            <input class="form-check-input" type="checkbox"
                                                                                name="custom_charges[0][is_refundable]"
                                                                                checked>
                                                                            <label
                                                                                class="form-check-label small">Refundable</label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Action -->
                                                                <div class="col-md-1 text-center">
                                                                    <label
                                                                        class="form-label small fw-bold mb-1 d-block">Action</label>
                                                                    <button type="button"
                                                                        class="btn btn-outline-danger btn-sm remove-charge"
                                                                        data-id="0">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    <?php } ?>

                                                    <?php

                                                    if (!empty($custom_charges)) {
                                                        foreach ($custom_charges as $charge) {
                                                            $i++; ?>
                                                            <div class="custom-charge-row border rounded p-3 mb-3 bg-light"
                                                                id="charge_row_<?= $i ?>">
                                                                <div class="row align-items-center g-3">

                                                                    <!-- Name -->
                                                                    <div class="col-md-3">
                                                                        <label
                                                                            class="form-label small fw-bold mb-1">Title</label>
                                                                        <input type="text"
                                                                            name="custom_charges[<?= $i ?>][name]"
                                                                            class="form-control"
                                                                            value="<?= str_replace('_', ' ', $charge['name']) ?>"
                                                                            required>
                                                                    </div>

                                                                    <!-- Amount -->
                                                                    <div class="col-md-2">
                                                                        <label
                                                                            class="form-label small fw-bold mb-1">Amount</label>
                                                                        <input type="number"
                                                                            name="custom_charges[<?= $i ?>][amount]"
                                                                            class="form-control" step="0.01" min="0"
                                                                            value="<?= htmlspecialchars((string) $charge['amount']) ?>"
                                                                            required>
                                                                    </div>

                                                                    <!-- Switches -->
                                                                    <div class="col-md-6">
                                                                        <label
                                                                            class="form-label small fw-bold mb-1 d-block">Applicable
                                                                            On</label>
                                                                        <div
                                                                            class="d-flex flex-wrap gap-3 align-items-center bg-white rounded p-2 border">

                                                                            <div class="form-check form-switch mb-0">
                                                                                <input class="form-check-input" type="checkbox"
                                                                                    name="custom_charges[<?= $i ?>][apply_pos]"
                                                                                    <?= !empty($charge['apply_pos']) ? 'checked' : '' ?>>
                                                                                <label
                                                                                    class="form-check-label small">POS</label>
                                                                            </div>

                                                                            <div class="form-check form-switch mb-0">
                                                                                <input class="form-check-input" type="checkbox"
                                                                                    name="custom_charges[<?= $i ?>][apply_doorstep]"
                                                                                    <?= !empty($charge['apply_doorstep']) ? 'checked' : '' ?>>
                                                                                <label
                                                                                    class="form-check-label small">Door</label>
                                                                            </div>

                                                                            <div class="form-check form-switch mb-0">
                                                                                <input class="form-check-input" type="checkbox"
                                                                                    name="custom_charges[<?= $i ?>][apply_pickup]"
                                                                                    <?= !empty($charge['apply_pickup']) ? 'checked' : '' ?>>
                                                                                <label
                                                                                    class="form-check-label small">Pickup</label>
                                                                            </div>

                                                                            <div class="form-check form-switch mb-0">
                                                                                <input class="form-check-input" type="checkbox"
                                                                                    name="custom_charges[<?= $i ?>][apply_digital]"
                                                                                    <?= !empty($charge['apply_digital']) ? 'checked' : '' ?>>
                                                                                <label class="form-check-label small">Digital
                                                                                    product</label>
                                                                            </div>

                                                                            <div class="vr"></div>

                                                                            <div class="form-check form-switch mb-0">
                                                                                <input class="form-check-input" type="checkbox"
                                                                                    name="custom_charges[<?= $i ?>][is_refundable]"
                                                                                    <?= !empty($charge['is_refundable']) ? 'checked' : '' ?>>
                                                                                <label
                                                                                    class="form-check-label small">Refundable</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Action -->
                                                                    <div class="col-md-1 text-center">
                                                                        <label
                                                                            class="form-label small fw-bold mb-1 d-block">Action</label>
                                                                        <button type="button"
                                                                            class="btn btn-outline-danger btn-sm remove-charge"
                                                                            data-id="<?= $i ?>">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        <?php }
                                                    } ?>
                                                </div>

                                                <small class="text-muted">
                                                    Add any additional charges (e.g., Packaging, Handling Fee, etc.)
                                                </small>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12  mt-4">
                        <div class="row">
                            <div class="col-md-8 ">
                                <div class="card card-body">
                                    <b class="m-2">
                                        Delivery Settings
                                    </b>
                                    <hr>
                                    <div class="card-body">
                                        <div class="row">
                                            <?php $class = isset($settings['area_wise_delivery_charge']) && $settings['area_wise_delivery_charge'] == '1' ? 'col-md-12' : 'col-md-12' ?>
                                            <div>
                                                <div
                                                    class="form-group area_wise_delivery_charge d-flex justify-content-between <?= $class ?>">
                                                    <label class="mb-2" for="area_wise_delivery_charge">Zipcode Wise
                                                        Delivery Charge <small>( Enable / Disable )</small></label>
                                                    <!-- <input type="checkbox" class="form-check-input"
                                                        id="area_wise_delivery_charge" value="area_wise_delivery_charge"
                                                        role="switch" name="area_wise_delivery_charge"
                                                        <?= (isset($settings['area_wise_delivery_charge']) && $settings['area_wise_delivery_charge'] == '1') ? 'Checked' : '' ?>
                                                        data-bootstrap-switch /> -->
                                                    <a class="form-switch mr-1 mb-1" title="Deactivate"
                                                        href="javascript:void(0)">
                                                        <input type="checkbox" class="form-check-input" role="switch"
                                                            id="area_wise_delivery_charge"
                                                            name="area_wise_delivery_charge"
                                                            <?= (!isset($settings['area_wise_delivery_charge']) || $settings['area_wise_delivery_charge'] == '1') ? 'Checked' : '' ?> />
                                                    </a>
                                                </div>
                                            </div>
                                            <?php $d_none = isset($settings['area_wise_delivery_charge']) && $settings['area_wise_delivery_charge'] == '1' ? 'd-none' : '' ?>
                                            <div class="form-group col-md-6 delivery_charge <?= $d_none ?>">
                                                <label class="mb-2" for="delivery_charge">Delivery Charge Amount
                                                    (<?= $currency ?>) <span
                                                        class='text-danger text-xs'>*</span></label>
                                                <input type="number" min="0" class="form-control mb-2"
                                                    name="delivery_charge"
                                                    value="<?= (isset($settings['delivery_charge'])) ? $settings['delivery_charge'] : '' ?>"
                                                    placeholder='Delivery Charge on Shopping' />
                                            </div>
                                            <div class="form-group col-md-6 min_amount <?= $d_none ?>">
                                                <label class="mb-2" for="min_amount">Minimum Amount for Free Delivery
                                                    (<?= $currency ?>) <span class='text-danger text-xs'>*</span>
                                                </label>
                                                <input type="number" min="0" class="form-control mb-2" name="min_amount"
                                                    value="<?= (isset($settings['min_amount'])) ? $settings['min_amount'] : '' ?>"
                                                    placeholder='Minimum Order Amount for Free Delivery' />
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="mb-2" for="">Delivery Boy Bonus (%)</label>
                                                <input type="number" min="0" class="form-control mb-2"
                                                    name="delivery_boy_bonus_percentage"
                                                    value="<?= (isset($settings['delivery_boy_bonus_percentage'])) ? $settings['delivery_boy_bonus_percentage'] : '' ?>"
                                                    placeholder='Delivery Boy Bonus' />
                                            </div>
                                            <div class="form-group col-md-6 mt-5 d-flex justify-content-between">
                                                <label class="mb-2" for="is_delivery_boy_otp_setting_on"> Order Delivery
                                                    OTP System</label>
                                                <a class=" form-switch  mr-1 mb-1" title="Deactivate"
                                                    href="javascript:void(0)"> <input type="checkbox"
                                                        class="form-check-input " role="switch"
                                                        name="is_delivery_boy_otp_setting_on"
                                                        <?= (isset($settings['is_delivery_boy_otp_setting_on']) && $settings['is_delivery_boy_otp_setting_on'] == '1') ? 'Checked' : '' ?> /></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 ">
                                <div class="card card-body">
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <b class="m-2">
                                                Application Versions
                                            </b>
                                            <hr>
                                            <div class="form-group col-md-12 d-flex justify-content-between mt-3">
                                                <label class="mb-2" for="is_version_system_on">Version System Status
                                                </label>
                                                <div>
                                                    <a class="toggle form-switch  mr-1 mb-1" title="Deactivate"
                                                        href="javascript:void(0)"> <input type="checkbox"
                                                            class="form-check-input " role="switch"
                                                            name="is_version_system_on"
                                                            <?= (isset($settings['is_version_system_on']) && $settings['is_version_system_on'] == '1') ? 'Checked' : '' ?> /></a>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <labelclass="mb-2" for="current_version">Current Version Of Android APP
                                                    <span class='text-danger text-xs'>*</span></label>
                                                    <input type="text" class="form-control mb-2" name="current_version"
                                                        value="<?= (isset($settings['current_version'])) ? $settings['current_version'] : '' ?>"
                                                        placeholder='Current For Version For Android APP' />
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label class="mb-2" for="current_version">Current Version Of IOS APP
                                                    <span class='text-danger text-xs'>*</span></label>
                                                <input type="text" class="form-control mb-2" name="current_version_ios"
                                                    value="<?= (isset($settings['current_version_ios'])) ? $settings['current_version_ios'] : '' ?>"
                                                    placeholder='Current Version For IOS APP' />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-4">
                        <div class="card">
                            <div class="card-header bg-white d-flex align-items-center">
                                <i class="bx bx-brain me-2"></i>
                                <strong>AI Settings</strong>
                            </div>

                            <div class="card-body">
                                <div class="row g-4">

                                    <!-- AI Status -->
                                    <div class="col-md-6 d-flex align-items-center justify-content-between">
                                        <label class="fw-semibold mb-0">AI Settings Status</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="ai_settings_status"
                                                <?= (!empty($settings['ai_settings_status'])) ? 'checked' : '' ?>>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <hr>
                                    </div>

                                    <!-- AI Provider -->
                                    <div class="col-md-12">
                                        <label class="fw-semibold mb-2 d-block">Select AI Provider</label>
                                        <div class="btn-group" role="group">
                                            <input type="radio" class="btn-check" name="ai_provider"
                                                id="provider_gemini" value="gemini" <?= (!isset($settings['ai_provider']) || $settings['ai_provider'] == 'gemini') ? 'checked' : '' ?>>
                                            <label class="btn btn-outline-primary px-4" for="provider_gemini">
                                                Gemini
                                            </label>

                                            <input type="radio" class="btn-check" name="ai_provider"
                                                id="provider_openrouter" value="openrouter"
                                                <?= (isset($settings['ai_provider']) && $settings['ai_provider'] == 'openrouter') ? 'checked' : '' ?>>
                                            <label class="btn btn-outline-primary px-4" for="provider_openrouter">
                                                OpenRouter
                                            </label>
                                        </div>

                                    </div>

                                    <!-- Gemini API Key -->
                                    <div class="col-md-12">
                                        <label class="fw-semibold mb-2">Gemini API Key</label>
                                        <input type="text" class="form-control" name="gemini_api_key"
                                            value="<?= $settings['gemini_api_key'] ?? '' ?>"
                                            placeholder="Enter Gemini API Key">
                                    </div>

                                    <!-- OpenRouter API Key -->
                                    <div class="col-md-12">
                                        <label class="fw-semibold mb-2">OpenRouter API Key</label>
                                        <input type="text" class="form-control" name="openrouter_api_key"
                                            value="<?= $settings['openrouter_api_key'] ?? '' ?>"
                                            placeholder="Enter OpenRouter API Key">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="col-md-12 mt-4">
                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="card card-body">
                                    <b class="m-2">
                                        Refer & Earn Settings
                                    </b>
                                    <hr>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-md-4 d-flex justify-content-between mt-5">
                                                <label class="mb-2" for="is_refer_earn_on"> Refer & Earn Status?
                                                </label>
                                                <div class="">


                                                    <a class="toggle form-switch  mr-1 mb-1" title="Deactivate"
                                                        href="javascript:void(0)"> <input type="checkbox"
                                                            class="form-check-input " role="switch"
                                                            name="is_refer_earn_on"
                                                            <?= (isset($settings['is_refer_earn_on']) && $settings['is_refer_earn_on'] == '1') ? 'Checked' : '' ?> /></a>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-4 mt-3">
                                                <label class="mb-2" for="refer_earn_method">Refer & Earn Method </label>
                                                <select name="refer_earn_method" class="form-control mb-2">
                                                    <option value="">Select</option>
                                                    <option value="percentage" <?= (isset($settings['refer_earn_method']) && $settings['refer_earn_method'] == "percentage") ? "selected" : "" ?>>Percentage</option>
                                                    <option value="amount" <?= (isset($settings['refer_earn_method']) && $settings['refer_earn_method'] == "amount") ? "selected" : "" ?>>
                                                        Amount</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-4 mt-3">
                                                <label class="mb-2" for="min_refer_earn_order_amount"> Minimum Refer &
                                                    Earn Order Amount (<?= $currency ?>) </label>
                                                <input type="number" min="0" step="0.1"
                                                    name="min_refer_earn_order_amount" class="form-control mb-2"
                                                    value="<?= (isset($settings['min_refer_earn_order_amount']) && $settings['min_refer_earn_order_amount'] != '') ? $settings['min_refer_earn_order_amount'] : '' ?>"
                                                    placeholder="Amount of order eligible for bonus" />
                                            </div>

                                            <div class="form-group col-md-4 mt-4">
                                                <label class="mb-2" for="refer_earn_bonus">Refer & Earn Bonus
                                                    (<?= $currency ?> OR %)</label>
                                                <input type="number" min="0" step="0.1" class="form-control mb-2"
                                                    name="refer_earn_bonus"
                                                    value="<?= (isset($settings['refer_earn_bonus'])) ? $settings['refer_earn_bonus'] : '' ?>"
                                                    placeholder='In amount or percentages' />
                                            </div>

                                            <div class="form-group col-md-4 mt-4">
                                                <label class="mb-2" for="max_refer_earn_amount">Maximum Refer & Earn
                                                    Amount (<?= $currency ?>)</label>
                                                <input type="number" min="0" step="0.1" class="form-control mb-2"
                                                    name="max_refer_earn_amount"
                                                    value="<?= (isset($settings['max_refer_earn_amount'])) ? $settings['max_refer_earn_amount'] : '' ?>"
                                                    placeholder='Maximum Refer & Earn Bonus Amount' />
                                            </div>

                                            <div class="form-group col-md-4 mt-4">
                                                <label class="mb-2" for="refer_earn_bonus_times">Number of times Bonus
                                                    to be given to the customer</label>
                                                <input type="number" min="0" class="form-control mb-2"
                                                    name="refer_earn_bonus_times"
                                                    value="<?= (isset($settings['refer_earn_bonus_times'])) ? $settings['refer_earn_bonus_times'] : '' ?>"
                                                    placeholder='No of times customer will get bonus' />
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-4">
                        <div class="row">
                            <div class="col-md-6 ">
                                <div class="card card-body">
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <b class="m-2">
                                                Country Currency
                                            </b>
                                            <hr>
                                            <div class="form-group col-md-12">
                                                <label class="mb-2" for="supported_locals">Country Currency Code</label>
                                                <select name="supported_locals" class="form-control mb-2">
                                                    <?php
                                                    $CI = &get_instance();
                                                    $CI->config->load('eshop');
                                                    $supported_methods = $CI->config->item('supported_locales_list');
                                                    foreach ($supported_methods as $key => $value) {
                                                        $text = "$key - $value "; ?>
                                                        <option value="<?= $key ?>" <?= (isset($settings['supported_locals']) && !empty($settings['supported_locals']) && $key == $settings['supported_locals']) ? "selected" : "" ?>>
                                                            <?= $key . ' - ' . $value ?>
                                                        </option>
                                                    <?php }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-12 mt-4">
                                                <label class="mb-2" for="currency">Store Currency ( Symbol or Code - $
                                                    or USD - Anyone ) <span class='text-danger text-xs'>*</span></label>
                                                <input type="text" class="form-control mb-2" name="currency"
                                                    value="<?= (isset($settings['currency'])) ? $settings['currency'] : '' ?>"
                                                    placeholder="Either Symbol or Code - For Example $ or USD" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 ">
                                <div class=" card card-body">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 form-group">
                                                <b class="m-2">
                                                    Welcome Wallet Balance </b>
                                                <hr>
                                                <div class="form-group col-md-12 d-flex justify-content-between">
                                                    <label class="mb-2" for="welcome_wallet_balance_on"> Enable Welcome
                                                        Wallet Balance </label>
                                                    <div class="">
                                                        <a class="toggle form-switch  mr-1 mb-1" title="Deactivate"
                                                            href="javascript:void(0)"> <input type="checkbox"
                                                                class="form-check-input " role="switch"
                                                                name="welcome_wallet_balance_on"
                                                                <?= (isset($settings['welcome_wallet_balance_on']) && $settings['welcome_wallet_balance_on'] == '1') ? 'Checked' : '' ?> /></a>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-12 mt-3">
                                                    <label class="mb-2" for="wallet_balance_amount"> Wallet Balance
                                                        Amount (<?= $currency ?>) </label>
                                                    <input type="number" name="wallet_balance_amount"
                                                        class="form-control mb-2" min="0"
                                                        value="<?= (isset($settings['wallet_balance_amount']) && $settings['wallet_balance_amount'] != '') ? $settings['wallet_balance_amount'] : '' ?>"
                                                        placeholder="Amount of Welcome Wallet Balance" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-4">
                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="card card-body">
                                    <b class="m-2">
                                        Maintenance Mode
                                    </b>
                                    <p class="text-danger"> [ If you enable Maintenance Mode of App then your App will
                                        be "Under Maintenance" ] </p>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <b class="m-2">
                                                Customer App
                                            </b>
                                            <hr>
                                            <div class="form-group col-md-12">
                                                <div class="d-flex justify-content-between">
                                                    <label class="mb-2" for="is_customer_app_under_maintenance">
                                                        Customer App</label>
                                                    <div class="">
                                                        <a class="toggle form-switch  mr-1 mb-1" title="Deactivate"
                                                            href="javascript:void(0)"> <input type="checkbox"
                                                                class="form-check-input " role="switch"
                                                                name="is_customer_app_under_maintenance"
                                                                <?= (isset($settings['is_customer_app_under_maintenance']) && $settings['is_customer_app_under_maintenance'] == '1') ? 'Checked' : '' ?> /></a>
                                                    </div>
                                                </div>
                                                <label class="mb-2 mt-4" for="message_for_customer_app"> Message for
                                                    Customer App</label>
                                                <div class="card-body p-0">
                                                    <textarea type="text" class="form-control mb-2"
                                                        id="message_for_customer_app"
                                                        placeholder="Message for Customer App"
                                                        name="message_for_customer_app"><?= isset($settings['message_for_customer_app']) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $settings['message_for_customer_app'])) : ""; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <b class="m-2">
                                                Delivery Boy App</b>
                                            <hr>
                                            <div class="form-group col-md-12">
                                                <div class="d-flex justify-content-between">
                                                    <label class="mb-2" for="is_delivery_boy_app_under_maintenance">
                                                        Delivery boy App</label>
                                                    <div class="">

                                                        <a class="toggle form-switch  mr-1 mb-1" title="Deactivate"
                                                            href="javascript:void(0)"> <input type="checkbox"
                                                                class="form-check-input " role="switch"
                                                                name="is_delivery_boy_app_under_maintenance"
                                                                <?= (isset($settings['is_delivery_boy_app_under_maintenance']) && $settings['is_delivery_boy_app_under_maintenance'] == '1') ? 'Checked' : '' ?> /></a>
                                                    </div>
                                                </div>
                                                <label class="mb-2 mt-4" for="message_for_delivery_boy_app"> Message for
                                                    Delivery boy App</label>
                                                <div class="card-body p-0">
                                                    <textarea type="text" class="form-control mb-2"
                                                        id="message_for_delivery_boy_app"
                                                        placeholder="Message for Delivery boy App"
                                                        name="message_for_delivery_boy_app"><?= isset($settings['message_for_delivery_boy_app']) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $settings['message_for_delivery_boy_app'])) : ""; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-group mt-5">
                                            <b class="m-2">
                                                Admin App</b>
                                            <hr>
                                            <div class="form-group col-md-12">
                                                <div class="d-flex justify-content-between">
                                                    <label class="mb-2" for="is_admin_app_under_maintenance"> Admin
                                                        App</label>
                                                    <div class="">

                                                        <a class="toggle form-switch  mr-1 mb-1" title="Deactivate"
                                                            href="javascript:void(0)"> <input type="checkbox"
                                                                class="form-check-input " role="switch"
                                                                name="is_admin_app_under_maintenance"
                                                                <?= (isset($settings['is_admin_app_under_maintenance']) && $settings['is_admin_app_under_maintenance'] == '1') ? 'Checked' : '' ?> /></a>
                                                    </div>
                                                </div>
                                                <label class="mb-2 mt-4" for="message_for_admin_app"> Message for Admin
                                                    App</label>
                                                <div class="card-body p-0">
                                                    <textarea type="text" class="form-control mb-2"
                                                        id="message_for_admin_app" placeholder="Message for Admin App"
                                                        name="message_for_admin_app"><?= isset($settings['message_for_admin_app']) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $settings['message_for_admin_app'])) : ""; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-group mt-5">
                                            <b class="m-2">
                                                Web maintenance mode</b>
                                            <hr>
                                            <div class="form-group col-md-12">
                                                <div class="d-flex justify-content-between">
                                                    <label class="mb-2" for="is_web_under_maintenance"> Web maintenance
                                                        mode</label>
                                                    <div class="">
                                                        <a class="toggle form-switch  mr-1 mb-1" title="Deactivate"
                                                            href="javascript:void(0)"> <input type="checkbox"
                                                                class="form-check-input " role="switch"
                                                                name="is_web_under_maintenance"
                                                                <?= (isset($settings['is_web_under_maintenance']) && $settings['is_web_under_maintenance'] == '1') ? 'Checked' : '' ?> /></a>
                                                    </div>
                                                </div>
                                                <label class="mb-2 mt-4" for="message_for_web"> Message for Web
                                                    maintenance mode </label>
                                                <div class="card-body p-0">
                                                    <textarea type="text" class="form-control mb-2" id="message_for_web"
                                                        placeholder="Message for Web maintenance mode"
                                                        name="message_for_web"><?= isset($settings['message_for_web']) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $settings['message_for_web'])) : ""; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-4">
                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="card card-body">
                                    <h3 class="m-2"> Cron URL for Discount Codes </h3>
                                    <hr>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="mb-2 col-md-6" for="app_name">Add Promo Code Discount URL
                                                <span class='text-danger text-xs'>*</span> <small>(Set this URL at your
                                                    server cron job list for "once a day")</small></label>
                                            <a class="btn btn-xs btn-primary text-white h-fit" data-toggle="modal"
                                                data-target="#howItWorksModal1" title="How it works">How Promo Code
                                                Discount works?</a>
                                            <input type="text" class="form-control mb-2" name="app_name"
                                                value="<?= base_url('admin/cron_job/settle_cashback_discount') ?>"
                                                disabled />
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="mb-2 col-md-6" for="app_name">Add Flash Sale Active/Deactive
                                                URL <span class='text-danger text-xs'>*</span> <small>(Set this URL at
                                                    your server cron job list for "every five minute")</small></label>
                                            <a class="btn btn-xs btn-primary text-white h-fit" data-toggle="modal"
                                                data-target="#howFlashSaleWorksModal" title="How it works">How Flash
                                                Sale works?</a>
                                            <input type="text" class="form-control mb-2" name="app_name"
                                                value="<?= base_url('admin/cron_job/fetch_active_flash_sale') ?>"
                                                disabled />
                                        </div>
                                        <br>
                                        <hr>
                                        <h4 class="mt-3">Cron Job URL for Remaining Item in cart</h4>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="app_name">Add Remaining Item in cart URL <span
                                                        class='text-danger text-xs'>*</span> <small>(Set this URL at
                                                        your server cron job list for "once a day")</small></label>
                                                <a class="btn btn-xs btn-primary text-white mb-2" data-toggle="modal"
                                                    data-target="#howItWorksModal1" title="How it works">Cron Job URL
                                                    for Remaining Item in cart</a>
                                                <input type="text" class="form-control mt-1" name="app_name"
                                                    value="<?= base_url('admin/cron_job/remaining_cart') ?>" disabled />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-4">
                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="card card-body">
                                    <b class="m-2">
                                        Offer Popup</b>
                                    <hr>
                                    <div class="row">
                                        <div class="form-group col-md-6 d-flex justify-content-between">
                                            <label class="mb-2" for="is_offer_popup_on"> Offer popup? </label>
                                            <a class=" form-switch  mr-1 mb-1" title="Deactivate"
                                                href="javascript:void(0)"> <input type="checkbox"
                                                    class="form-check-input " role="switch" name="is_offer_popup_on"
                                                    <?= (isset($settings['is_offer_popup_on']) && $settings['is_offer_popup_on'] == true) ? 'Checked' : '' ?> /></a>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="mb-2" for="offer_popup_method">Offer popup Method </label>
                                            <select name="offer_popup_method" class="form-control mb-2">
                                                <option value="">Select</option>
                                                <option value="refresh" <?= (isset($settings['offer_popup_method']) && $settings['offer_popup_method'] == "refresh") ? "selected" : "" ?>>
                                                    Appears upon refresh</option>
                                                <option value="session_storage"
                                                    <?= (isset($settings['offer_popup_method']) && $settings['offer_popup_method'] == "session_storage") ? "selected" : "" ?>>Appears once</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-4">
                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="card card-body">
                                    <h4>Deeplink Settings For APP</h4>
                                    <hr>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="android_app_store_link">android App Store Link <span
                                                    class='text-danger text-xs'>*</span></label>
                                            <input type="text" class="form-control mt-2" id="android_app_store_link"
                                                name="android_app_store_link"
                                                value="<?= (isset($settings['android_app_store_link'])) ? output_escaping($settings['android_app_store_link']) : '' ?>"
                                                placeholder="android App Store Link" />
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="ios_app_store_link">ios App Store Link<span
                                                    class='text-danger text-xs'>*</span></label>
                                            <input type="text" class="form-control mt-2" id="ios_app_store_link"
                                                name="ios_app_store_link"
                                                value="<?= (isset($settings['ios_app_store_link'])) ? output_escaping($settings['ios_app_store_link']) : '' ?>"
                                                placeholder="ios App Store Link" />
                                        </div>
                                        <div class="form-group col-md-6 mt-3">
                                            <label for="scheme">Scheme For APP <span
                                                    class='text-danger text-xs'>*</span></label>
                                            <input type="text" class="form-control mt-2" id="scheme" name="scheme"
                                                value="<?= (isset($settings['scheme'])) ? output_escaping($settings['scheme']) : '' ?>"
                                                placeholder="Scheme For APP" />
                                        </div>
                                        <div class="form-group col-md-6 mt-3">
                                            <label for="host">Host For APP<span
                                                    class='text-danger text-xs'>*</span></label>
                                            <input type="text" class="form-control mt-2" id="host" name="host"
                                                value="<?= (isset($settings['host'])) ? output_escaping($settings['host']) : '' ?>"
                                                placeholder="Host For APP" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mt-4">
                                <div class="row">
                                    <div class="col-md-12 ">
                                        <div class="card card-body">
                                            <b class="m-2">
                                                Social login ?</b>
                                            <hr>
                                            <div class="row">
                                                <div class="form-group col-md-12 d-flex justify-content-between">
                                                    <label class="mb-2" for="social_login"> Google </label>
                                                    <a class=" form-switch  mr-1 mb-1" title="Deactivate"
                                                        href="javascript:void(0)"> <input type="checkbox"
                                                            class="form-check-input " role="switch" name="google_login"
                                                            <?= (isset($settings['google_login']) && $settings['google_login'] == true) ? 'Checked' : '' ?> /></a>
                                                    <label class="mb-2" for="social_login"> Apple </label>
                                                    <a class=" form-switch  mr-1 mb-1" title="Deactivate"
                                                        href="javascript:void(0)"> <input type="checkbox"
                                                            class="form-check-input " role="switch" name="apple_login"
                                                            <?= (isset($settings['apple_login']) && $settings['apple_login'] == true) ? 'Checked' : '' ?> /></a>
                                                    <label class="mb-2" for="email_login"> Email </label>
                                                    <a class=" form-switch  mr-1 mb-1" title="Deactivate"
                                                        href="javascript:void(0)"> <input type="checkbox"
                                                            class="form-check-input " role="switch" name="email_login"
                                                            <?= (isset($settings['email_login']) && $settings['email_login'] == true) ? 'Checked' : '' ?> /></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mt-4">
                                <div class="row">
                                    <div class="col-md-12 ">
                                        <div class="card card-body">
                                            <b class="m-2">
                                                Share Whatsapp Number</b>
                                            <hr>
                                            <div class="row">
                                                <div class="form-group col-md-12 d-flex justify-content-between">
                                                    <label class="mb-2" for="social_login">Whatsapp</label>
                                                    <a class="form-switch mr-1 mb-1" title="Deactivate"
                                                        href="javascript:void(0)"> <input type="checkbox"
                                                            class="form-check-input " id="whatsapp_status" role="switch"
                                                            name="whatsapp_status"
                                                            <?= (isset($settings['whatsapp_status']) && $settings['whatsapp_status'] == true) ? 'Checked' : '' ?> /></a>
                                                </div>
                                                <div>
                                                    <input type="number" min="0"
                                                        class="form-control <?= (isset($settings['whatsapp_status']) && $settings['whatsapp_status'] == 1) ? '' : 'collapse' ?>"
                                                        name="whatsapp_number" id="whatapp_number_input"
                                                        placeholder="Whatsapp Number"
                                                        value="<?= isset($settings['whatsapp_number']) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $settings['whatsapp_number'])) : ""; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mt-4">
                                <div class="row">
                                    <div class="col-md-12 ">
                                        <div class="card card-body">
                                            <b class="m-2">
                                                Product Deliverability</b>
                                            <hr>
                                            <?php $shipping_settings = get_settings('shipping_method', true);
                                            ?>
                                            <div class="row">
                                                <div class="form-group col-md-12 d-flex justify-content-between">
                                                    <label class="mb-2" for="social_login">Pincode Wise
                                                        Deliverability</label>
                                                    <a class="form-switch mr-1 mb-1" title="Deactivate"
                                                        href="javascript:void(0)">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="pincode_wise_deliverability" role="switch"
                                                            name="pincode_wise_deliverability"
                                                            <?= (isset($settings['pincode_wise_deliverability']) && $settings['pincode_wise_deliverability'] == true) ? 'Checked' : '' ?> />
                                                    </a>
                                                </div>
                                                <div class="form-group col-md-12 d-flex justify-content-between">
                                                    <label class="mb-2" for="social_login">City Wise Deliverability
                                                        <?php if ($shipping_settings['shiprocket_shipping_method'] == 1) { ?>
                                                            <small class="text-muted">(Disabled because standard shipping is
                                                                on from shipping method)</small>
                                                        <?php } ?>
                                                    </label>
                                                    <a class="form-switch mr-1 mb-1" title="Deactivate"
                                                        href="javascript:void(0)">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="city_wise_deliverability" role="switch"
                                                            name="city_wise_deliverability"
                                                            <?= (isset($settings['city_wise_deliverability']) && $settings['city_wise_deliverability'] == true) ? 'checked' : '' ?>
                                                            <?= ($shipping_settings['shiprocket_shipping_method'] == 1) ? 'disabled' : '' ?> />
                                                    </a>
                                                </div>
                                                <div
                                                    class="form-group city-delivery-settings <?= (isset($settings['city_wise_deliverability']) && $settings['city_wise_deliverability'] == true && (!isset($settings['pincode_wise_deliverability']) || $settings['pincode_wise_deliverability'] == false)) ? 'd-block' : 'd-none' ?>;">
                                                    <label for="global_free_delivery_amount_on_city">Global Free
                                                        Delivery Amount on City<span
                                                            class='text-danger text-xs'>*</span></label>
                                                    <input type="number" min="0" class="form-control"
                                                        id="global_free_delivery_amount_on_city"
                                                        name="global_free_delivery_amount_on_city"
                                                        value="<?= (isset($settings['global_free_delivery_amount_on_city'])) ? output_escaping($settings['global_free_delivery_amount_on_city']) : '' ?>"
                                                        placeholder="Global Free Delivery Amount on City" />
                                                </div>
                                                <div
                                                    class="form-group city-delivery-settings mt-2 <?= (isset($settings['city_wise_deliverability']) && $settings['city_wise_deliverability'] == true && (!isset($settings['pincode_wise_deliverability']) || $settings['pincode_wise_deliverability'] == false)) ? 'd-block' : 'd-none' ?>;">
                                                    <label for="global_delivery_charge_on_city">Global Delivery Charge
                                                        on City<span class='text-danger text-xs'>*</span></label>
                                                    <input type="number" min="0" class="form-control"
                                                        id="global_delivery_charge_on_city"
                                                        name="global_delivery_charge_on_city"
                                                        value="<?= (isset($settings['global_delivery_charge_on_city'])) ? output_escaping($settings['global_delivery_charge_on_city']) : '' ?>"
                                                        placeholder="Global delivery charge on City" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-4 d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-secondary">Reset</button>
                            <button type="submit" class="btn btn-primary system_setting_form" id="submit_btn">Update
                                Settings</button>
                        </div>
                </form>
                <!--/.col-md-12-->
                <div class="modal fade" id="howItWorksModal1" tabindex="-1" role="dialog"
                    aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel">How Promo Code Discount will get credited?
                                </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body ">
                                <ol>
                                    <li>Cron job must be set on your server for Promo Code Discount to be work.</li>

                                    <li> Cron job will run every mid night at 12:00 AM. </li>

                                    <li> Formula for Add Promo Code Discount is <b>Sub total (Excluding delivery charge)
                                            - promo code discount percentage / Amount</b> </li>

                                    <li> For example sub total is 1300 and promo code discount is 100 then 1300 - 100 =
                                        1200 so 100 will get credited into Users's wallet </li>

                                    <li> If Order status is delivered And Return Policy is expired then only users will
                                        get Promo Code Discount. </li>

                                    <li> Ex - 1. Order placed on 10-Sep-22 and return policy days are set to 1 so 10-Sep
                                        + 1 days = 11-Sep Promo code discount will get credited on 11-Sep-22 at 12:00 AM
                                        (Mid night) </li>

                                    <li> If Promo Code Discount doesn't works make sure cron job is set properly and it
                                        is working. If you don't know how to set cron job for once in a day please take
                                        help of server support or do search for it. </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="howFlashSaleWorksModal" tabindex="-1" role="dialog"
                    aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel">How Flash Sale Works?</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body ">
                                <ol>
                                    <li>Cron job must be set on your server for Flash Sale to work.</li>

                                    <li>Cron job will run every five minutes to check and activate/deactivate flash
                                        sales based on their scheduled time.</li>

                                    <li>Flash sales will automatically become active when the start time is reached and
                                        will be deactivated when the end time is reached.</li>

                                    <li>For example, if a flash sale is scheduled from 10:00 AM to 2:00 PM, the cron job
                                        will automatically activate it at 10:00 AM and deactivate it at 2:00 PM.</li>

                                    <li>Products included in an active flash sale will display the discounted price
                                        during the flash sale period.</li>

                                    <li>Make sure the cron job URL is set to run every 5 minutes:
                                        <code><?= base_url('admin/cron_job/fetch_active_flash_sale') ?></code>
                                    </li>

                                    <li>If Flash Sale doesn't work, make sure cron job is set properly and is running
                                        every 5 minutes. If you don't know how to set cron job, please take help of
                                        server support or search for it.</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section><!-- /.content -->
</div>