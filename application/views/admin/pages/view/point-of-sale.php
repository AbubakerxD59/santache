<div class="content-wrapper">
    <section class="content-header mt-4">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-lg-10">
                    <h4>Point Of Sale</h4>
                </div>
                <div class="col-lg-2">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Point Of Sale</li>
                    </ol>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div id="get_categories" class="col-sm-4 dropdown mb-3">
                            <h5 class="card-title">Filter Product By Category</h5>
                            <select class="form-control" id="product_categories">
                                <option value="" selected>
                                    <?= (isset($categories) && empty($categories)) ? 'No Categories Exist' : 'Select Categories' ?>
                                </option>
                                <?= get_categories_option_html($categories); ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <h5 class="card-title">Search Your Product</h5>
                            <input type="search" name="search_products" class="form-control" id="search_products"
                                value="" placeholder="Search Products">
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <input type="hidden" name="limit" id="limit" value="15" />
                            <input type="hidden" name="offset" id="offset" value="0" />
                            <input type="hidden" name="total" id="total_products" />
                            <input type="hidden" name="current_page" id="current_page" value="0" />
                            <input type="hidden" name="bulk_discount" id="bulk_discount" value="0" />


                            <!-- Custom Charges Data -->
                            <?php
                            $settings = get_settings('system_settings', true);
                            $custom_charges = get_settings('custom_charges', true);
                            $custom_charges_pos = '1';
                            ?>
                            <input type="hidden" id="custom_charges_pos_enabled" value="<?= $custom_charges_pos ?>">
                            <input type="hidden" id="custom_charges_json" value='<?= json_encode($custom_charges) ?>'>
                            <div class="row d-flex justify-content-center p-4 align-content-center"
                                class="img-thumbnail" id="get_products">
                                <!-- product display in this container -->
                            </div>
                            <div class="pagination-container"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <section class="container content-section mt-4">
                                <form id="pos_form" method="post"
                                    action='<?= base_url('admin/point_of_sale/place_order') ?>'>
                                    <div class="d-flex justify-content-between align-items-center mb-2 mt-2">
                                        <label class="mb-0">Select User </label>
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                            data-target="#register">Register New User</button>
                                    </div>
                                    <!-- select user -->
                                    <input type="hidden" name=" <?php echo $this->security->get_csrf_token_name(); ?>"
                                        value="<?php echo $this->security->get_csrf_hash(); ?>" />
                                    <input type="hidden" name="user_id" id="pos_user_id" value="">
                                    <input type="hidden" name="product_variant_id" value="">
                                    <input type="hidden" name="quantity" value="">
                                    <input type="hidden" name="total" value="">
                                    <div class="mb-3">
                                        <select class="select_user form-control" id="select_user_id">
                                            <!-- user name display here  -->
                                        </select>
                                    </div>
                                
                                    
                                <div class="mb-3"></div>
                                   
                                    <div class="cart-items border p-3" style="min-height: 100px;">
                                    </div>
                                    <?php $settings = get_settings('system_settings', true); ?>
                                    <?php $currency = !empty($settings['currency']) ? $settings['currency'] : ''; ?>

                                    <div class="mt-4" id="cart-totals-section">
                                        <div class="bg-light p-3 rounded border">
                                            
                                            <!-- Subtotal -->
                                            <div class="invoice-detail-item d-flex justify-content-between align-items-center mb-2">
                                                <span class="cart-total font-weight-bold"><?= labels('subtotal', 'Subtotal') ?></span>
                                                <span class="cart-total-price font-weight-bold"
                                                    id="cart-total-price" data-currency="<?= $currency ?>">
                                                    <!-- filled by JS -->
                                                </span>
                                            </div>

                                            <!-- Bulk Discount (hidden by default) -->
                                            <div class="invoice-detail-item d-flex justify-content-between align-items-center mb-2 text-success"
                                                id="bulk-discount-section" style="display: none;">
                                                <span class="cart-total font-weight-bold"><?= labels('bulk_discount', 'Bulk Discount') ?></span>
                                                <span class="bulk-discount-amount font-weight-bold"
                                                    id="bulk-discount-amount" data-currency="<?= $currency ?>">
                                                    -
                                                </span>
                                            </div>

                                            <!-- Dynamic custom charges will be inserted here -->
                                            <div id="custom-charges-list" class="w-100" style="display: none;"></div>

                                            <hr class="m-0 mb-2">

                                            <!-- Final Total -->
                                            <div class="invoice-detail-item d-flex justify-content-between align-items-center">
                                                <span class="cart-total h5 mb-0 font-weight-bold"><?= labels('final_total', 'Final Total') ?></span>
                                                <span class="final-total-price h5 mb-0 font-weight-bold text-success"
                                                    id="final-total-price" data-currency="<?= $currency ?>">
                                                    <!-- filled by JS -->
                                                </span>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <p class="h5 text-primary mb-3">Delivery Type</p>
                                        <div class="bg-light p-3 rounded border mb-4">
                                            <div class="form-check mb-2 self_pickup">
                                                <input id="local_pickup" type="radio" name="self_pickup[]" value="1"
                                                    class="form-check-input self_pickup" checked />
                                                <label class="form-check-label" for="local_pickup">
                                                    Local Pickup
                                                </label>
                                            </div>
                                            <div class="form-check self_pickup">
                                                <input id="door_step_delivery" type="radio" name="self_pickup[]"
                                                    value="0" class="form-check-input self_pickup" />
                                                <label class="form-check-label" for="door_step_delivery">
                                                    Door Step Delivery
                                                </label>
                                            </div>
                                        </div>

                                        <p class="h5 text-primary mb-3">Payment Methods</p>
                                        <div class="bg-light p-3 rounded border">
                                            <div class="form-check mb-2 cash_payment">
                                                <input id="cod" type="radio" name="payment_method[]" value="COD"
                                                    class="form-check-input payment_method" />
                                                <label class="form-check-label" for="cod">
                                                    Cash
                                                </label>
                                            </div>
                                            <div class="form-check mb-2 card_payment">
                                                <input id="card_payment" type="radio" name="payment_method[]"
                                                    value="card_payment" class="form-check-input payment_method">
                                                <label class="form-check-label" for="card_payment">
                                                    Card Payment
                                                </label>
                                            </div>
                                            <div class="form-check mb-2 bar_code">
                                                <input id="bar_code" type="radio" name="payment_method[]"
                                                    value="bar_code" class="form-check-input payment_method">
                                                <label class="form-check-label" for="bar_code">
                                                    Bar Code / QR Code Scan
                                                </label>
                                            </div>
                                            <div class="form-check mb-2 net_banking">
                                                <input id="net_banking" type="radio" name="payment_method[]"
                                                    value="net_banking" class="form-check-input payment_method">
                                                <label class="form-check-label" for="net_banking">
                                                    Net Banking
                                                </label>
                                            </div>
                                            <div class="form-check mb-2 online_payment">
                                                <input id="online_payment" type="radio" name="payment_method[]"
                                                    value="online_payment" class="form-check-input payment_method">
                                                <label class="form-check-label" for="online_payment">
                                                    Online Payment
                                                </label>
                                            </div>
                                            <div class="form-check mb-3 other">
                                                <input id="other" type="radio" name="payment_method[]" value="other"
                                                    class="form-check-input payment_method">
                                                <label class="form-check-label" for="other">
                                                    Other
                                                </label>
                                            </div>
                                            
                                            <div class="payment_method_name mt-3 form-group">
                                                <label for="payment_method_name">Enter Payment method Name</label>
                                                <input type="text" class="form-control" name="payment_method_name" id="payment_method_name">
                                            </div>
                                            <div class="transaction_id mt-3 form-group">
                                                <label for="transaction_id">Enter Transaction ID</label>
                                                <input type="text" class="form-control" name="transaction_id" id="transaction_id">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center mt-4">
                                        <button class="btn btn-sm btn-clear_cart btn-danger mb-2 mx-3" type="submit"
                                            id="place_order_btn">Clear Cart</button>
                                        <button class="btn btn-sm btn-purchase btn-primary mb-2" type="submit"
                                            id="place_order_btn">Place Order</button>
                                    </div>
                                </form>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="register">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Register User</h4>
                <button type="button" class="btn-close" data-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form method="post" action='<?= base_url('admin/point_of_sale/register_user') ?>' id="register_form">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="hidden" name=" <?php echo $this->security->get_csrf_token_name(); ?>"
                            value="<?php echo $this->security->get_csrf_hash(); ?>" />
                        <input type="text" class="form-control" id="name" placeholder="Enter Your Name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="mobile">Mobile:</label>
                        <input type="text" maxlength="16" oninput="validateNumberInput(this)" class="form-control"
                            id="mobile" placeholder="Enter Your Mobile Number" name="mobile">
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="password" value="12345678"
                                placeholder="Enter Password" name="password">
                            <span class="input-group-text togglePassword" style="cursor: pointer;">
                                <i class="fa fa-eye"></i>
                            </span>

                        </div>
                    </div>
                    <div class="mt-3">
                        <div id="save-register-result"></div>
                    </div>
                </form>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" form="register_form" class="btn btn-primary" id="save-register-result-btn"
                    name="register" value="Save">Register</button>
            </div>
        </div>
    </div>
</div>