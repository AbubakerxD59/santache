<main>
    <section class="container py-4">
        <form class="needs-validation" id="checkout_form" method="POST" action="<?= base_url('cart/place-order') ?>">
            <div class="cart-page-title">
                <div class="checkout-step justify-content-around py-5   ">
                    <a href="<?= base_url('cart') ?>">
                        <h3 class="d-md-block d-none "><?= label('shopping_cart', 'SHOPPING CART') ?></h3>
                    </a>
                    <i class="fa-solid fa-arrow-right-long d-md-block d-none"></i>
                    <a href="<?= base_url('cart/checkout') ?>">
                        <h3 class="step-active pointer"><?= label('checkout', 'CHECKOUT') ?></h3>
                    </a>
                    <i class="fa-solid fa-arrow-right-long d-md-block d-none"></i>
                    <h3 class="d-md-block d-none"><?= label('order_complete', 'ORDER COMPLETE') ?></h3>
                </div>
            </div>
            <div class="row py-4">
                <div class="col-lg-7">
                    <div class="billing-detail-section">
                        <div class="checkout-page-titles"><?= label('billing_details', 'Billing Details') ?></div>
                        <!-- select pickup -->
                        <?php
                        $shiprocket_settings = get_settings('shipping_method', true);
                        $settings = get_settings('system_settings', true);
                        if (isset($shiprocket_settings['local_shipping_method']) && $shiprocket_settings['local_shipping_method'] == 1 && ($cart[0]['type'] != 'digital_product')) { ?>
                            <div class="form-check d-flex gap-3 p-2">
                                <input type="radio" class="btn-check" name="local_pickup" id="door_step" autocomplete="off" value="0" checked>
                                <label class="btn btn-delivery btn-outline-primary" for="door_step">
                                    <?= label('door_step_delivery', 'Door Step Delivery') ?>
                                </label>

                               <input type="radio"
                                   class="btn-check"
                                   name="local_pickup"
                                   value="1"
                                   id="pickup_from_store"
                                   autocomplete="off">
                            <label class="btn btn-delivery btn-outline-primary" for="pickup_from_store">
                                Pickup From Store
                            </label>
                            </div>
                        <?php } else { ?>
                            <div class="form-check d-flex">
                                <input class="mr-2 d-none" id="door_step" name="local_pickup" type="radio" value="0" checked>
                            </div>
                        <?php } ?>

                        <div class="shipping-address address <?= $cart[0]['type'] != 'digital_product' ? '' : 'd-none' ?>">
                            <input type="hidden" name="product_type" id="product_type" value="<?= $cart[0]['type']; ?>">
                            <input type="hidden" name="download_allowed" value="<?= in_array(0, array($cart['download_allowed'])) ? 0 : 1 ?>">
                            <?php if ($cart[0]['type'] != 'digital_product') { ?>
                                <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between">
                                    <h5><?= label('shipping_address', 'Shipping Address') ?></h5>
                                    <div class="d-flex align-items-center gap-2">
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="open-create-address-modal" title="Create Address">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#address-modal">
                                            <ion-icon name="create-outline"></ion-icon>
                                        </button>
                                    </div>
                                </div>
                                <div class="selected-address">
                                    <p id="address-name-type"><?= isset($default_address) && !empty($default_address) ? $default_address[0]['name'] . ' - ' . ucfirst($default_address[0]['type']) : 'No address selected' ?></p>
                                    <p id="address-full"><?= isset($default_address) && !empty($default_address) ? trim($default_address[0]['address']) : '' ?></p>
                                    <p id="address-country"><?php
                                        if (isset($default_address) && !empty($default_address)) {
                                            $address_line_two = array_filter([
                                                $default_address[0]['city'] ?? '',
                                                $default_address[0]['state'] ?? '',
                                                $default_address[0]['pincode'] ?? '',
                                                $default_address[0]['country'] ?? '',
                                            ]);
                                            echo htmlspecialchars(implode(', ', $address_line_two), ENT_QUOTES, 'UTF-8');
                                        }
                                    ?></p>
                                    <p class="text-muted" id="address-mobile"><?= isset($default_address) && !empty($default_address) ? $default_address[0]['mobile'] : '' ?></p>
                                </div>

                                <!-- checking product deliverable or not  -->
                                <div class="selected-address" id="deliverable_status">
                                    <?php
                                    $product_not_delivarable = array();

                                    if (isset($default_address) && !empty($default_address)) {
                                        $zipcode_id = fetch_details('zipcodes', ['zipcode' => $default_address[0]['pincode']], 'id');

                                        $product_delivarable = check_cart_products_delivarable($default_address[0]['user_id'], 0, "", $zipcode_id[0]['id'], "");
                                        // continue from here

                                        if (!empty($product_delivarable)) {
                                            $product_not_delivarable = array_filter($product_delivarable, function ($var) {
                                                return ($var['is_deliverable'] == false);
                                            });
                                            $product_not_delivarable = array_values($product_not_delivarable);
                                            $deliverable_error_msg = "";
                                            foreach ($product_not_delivarable as $p_id) {
                                                if (!empty($p_id['product_id'])) {

                                                    $deliverable_error_msg = (!empty($this->lang->line('product_not_delivarable_msg'))) ? $this->lang->line('product_not_delivarable_msg') : "Some of the item(s) are not delivarable on selected address. Try changing address or modify your cart items.";
                                                    continue;
                                                }
                                            }
                                    ?>
                                            <p class="text-danger"><?= $deliverable_error_msg ?></p>
                                        <?php }
                                    } else { ?>
                                        <p class="text-danger">Please select address.</p>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <?php if (in_array(0, array($cart['download_allowed'])) && $cart[0]['type'] == 'digital_product') { ?>
                                <div class="input-group mt-3">
                                    <input name="email" type="email" class="form-control" placeholder="Please enter your email ID ">
                                </div>
                            <?php } ?>
                            <input type="hidden" name="address_id" id="address_id" value="<?= isset($default_address) && !empty($default_address) ? $default_address[0]['id'] : '' ?>" />
                            <input type="hidden" name="mobile" id="mobile" value="<?= isset($default_address) && !empty($default_address) ? $default_address[0]['mobile'] : $wallet_balance[0]['mobile'] ?>" />
                        </div>
                        <input type="hidden" name="total" value="<?= number_format($cart['sub_total'], 2) ?>">
                        <input type="hidden" id="temp_total" name="temp_total" value="<?= $cart['total_arr'] ?>">
                        <input type="hidden" name="product_variant_id" value="<?= implode(',', array_column($cart, 'id')) ?>">
                        <input type="hidden" name="quantity" value="<?= implode(',', array_column($cart, 'qty')) ?>">
                        <input type="hidden" id="current_wallet_balance" value="<?= number_format($wallet_balance[0]['balance'], 2) ?>">
                        <input type="hidden" id="wallet_used" name="wallet_used">
                        <input type="hidden" id="pickup" name="pickup_type" value="">

                        <?php $shiprocket_settings = get_settings('shipping_method', true);
                        if (isset($shiprocket_settings['local_shipping_method']) && $shiprocket_settings['local_shipping_method'] == 1) { ?>
                            <?php if ($cart[0]['type'] == 'digital_product') { ?>
                                <input type="hidden" name="is_time_slots_enabled" id="is_time_slots_enabled" value="0">
                            <?php } else { ?>
                                <input type="hidden" name="is_time_slots_enabled" id="is_time_slots_enabled" value="<?= (isset($time_slot_config['is_time_slots_enabled']) && $time_slot_config['is_time_slots_enabled'] == 1) ? 1 : 0 ?>">
                            <?php  } ?>   
                            <div class="input-group py-3 border-top">
                                <input type="text" class="form-control" placeholder="Special Note for Order" name="order_note" id="order_note">
                            </div>
                            <?php if (isset($time_slot_config['is_time_slots_enabled']) && $time_slot_config['is_time_slots_enabled'] == 1) {
                                if($cart[0]['type'] != 'digital_product') {
                            ?>

                                <?php
                                $shiprocket_settings =  get_settings('shipping_method', true); ?>
                                <div class="delivary-time">
                                    <h5 class="pb-2"><?= label('preferred_delivery_date_time', 'Preferred Delivery Date / Time') ?></h5>
                                    <div class="input-group date-time-picker position-relative">
                                        <div class="input-group-prepend">
                                            <ion-icon name="calendar-outline"></ion-icon>
                                        </div>
                                        <input type="text" class="form-control ps-5" id="datepicker">
                                        
                                        <input type="hidden" id="start_date" class="form-control float-right">
                                    </div>
                                    <div class="mt-3 time-slot" id="time_slots">
                                        <?php foreach ($time_slots as $row) { ?>

                                            <div class="form-check">
                                                <label class="form-check-label" for="<?= $row['id'] ?>">
                                                    <input class="custom-control-input time-slot-inputs form-check-input" id="<?= $row['id'] ?>" name="delivery_time" type="radio" data-last_order_time="<?= $row['last_order_time'] ?>" value="<?= $row['title'] ?>">
                                                    <?= $row['title'] ?>
                                                </label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php }
                            }
                        }
                            $settings = get_settings('system_settings', true);
                            ?>

                            <?php
                            foreach ($cart as $row) {
                                if (isset($row['is_attachment_required']) && $row['is_attachment_required'] == 1 && $cart[0]['type'] != 'digital_product') {

                                    //If Allow Upload Attachments On
                            ?>
                                    <h5 class="pt-3">Upload Order Attachments if you have any(Only images and docs are allowded)</h5>
                                    <div class="input-group">
                                        <input type="file" class="form-controls" name="documents[]" multiple id="documents">
                                    </div>
                                    <span class="text-danger">Attachments are required</span>
                        <?php }
                            }
                        ?>
                        <input type="hidden" name="delivery_date" id="delivery_date" class="form-control float-right">

                        <input type="hidden" id="sub_total" value="<?= $cart['sub_total'] ?>">

                    </div>
                    <div class="wallet-use-section mt-3">
                        <div class="align-item-center ship-title-details justify-content-between d-flex">
                            <h5 class="checkout-page-titles"><?= !empty($this->lang->line('wallet_balance')) ? $this->lang->line('wallet_balance') : 'Use wallet balance' ?></h5>
                        </div>
                        <?php $disabled = $wallet_balance[0]['balance'] == 0 ? 'disabled' : '' ?>
                        <div class="form-check d-flex">
                            <input class="form-check-input" type="checkbox" value="" name="wallet_balance" id="wallet_balance" <?= $disabled ?>>
                            <label class="form-check-label d-flex ps-3 fw-semibold" for="wallet_balance">
                                Available balance : <?= $currency . '<span id="available_balance">' . number_format($wallet_balance[0]['balance'], 2) . '</span>' ?>
                            </label>
                        </div>
                    </div>

                    <div class="Payment-Method-section payment-methods">
                        <h5 class="checkout-page-titles"><?= label('select_payment_method', 'Select Payment Method') ?></h5>
                        <div class="payment-type">
                            <div class="form-check">
                                <?php if (isset($payment_methods['cod_method']) && $payment_methods['cod_method'] == 1 && $cart[0]['type'] != 'digital_product') { ?>
                                    <label class="form-check-label" for="cod">
                                        <input class="form-check-input" id="cod" title="<?= isset($cart[0]['is_cod_allowed']) && $cart[0]['is_cod_allowed'] == 0 ? 'Cash on delivery is not allowed for one of the item in your cart' : 'Please select one of this options.' ?>" name="payment_method" type="radio" value="COD" <?= isset($cart[0]['is_cod_allowed']) && $cart[0]['is_cod_allowed'] == 0 ? 'disabled' : '' ?>>
                                        <div class="payment-type-img-box">
                                            <img src="<?= base_url('assets/front_end/modern/image/pictures/cod-logo.jpg') ?>" alt="">
                                        </div>
                                    </label>
                                <?php } ?>
                                <?php if (isset($payment_methods['phonepe_payment_method']) && $payment_methods['phonepe_payment_method'] == 1) { ?>
                                    <label class="form-check-label" for="phonepe">
                                        <input id="phonepe" class="form-check-input" name="payment_method" type="radio" value="phonepe">
                                        <div class="payment-type-img-box">
                                            <img src="<?= base_url('assets/front_end/modern/image/pictures/phonepay-logo.png') ?>" class="payment-gateway-images" alt="phonepe">
                                        </div>
                                    </label>
                                <?php } ?>
                                <?php if (isset($payment_methods['paypal_payment_method']) && $payment_methods['paypal_payment_method'] == 1) { ?>
                                    <label class="form-check-label" for="paypal">
                                        <input class="form-check-input" id="paypal" name="payment_method" type="radio" value="Paypal" required>
                                        <div class="payment-type-img-box">
                                            <img src="<?= base_url('assets/front_end/modern/image/pictures/paypal-logo.png') ?>" alt="">
                                        </div>
                                    </label>
                                <?php } ?>
                                <?php if (isset($payment_methods['razorpay_payment_method']) && $payment_methods['razorpay_payment_method'] == 1) { ?>
                                    <label class="form-check-label" for="razorpay">
                                        <input class="form-check-input" id="razorpay" name="payment_method" type="radio" value="Razorpay" required>
                                        <div class="payment-type-img-box">
                                            <img src="<?= base_url('assets/front_end/modern/image/pictures/Razorpay_logo.png') ?>" alt="">
                                        </div>
                                    </label>
                                <?php } ?>
                                <?php if (isset($payment_methods['paystack_payment_method']) && $payment_methods['paystack_payment_method'] == 1) { ?>
                                    <label class="form-check-label" for="paystack">
                                        <input class="form-check-input" id="paystack" name="payment_method" type="radio" value="Paystack" required>
                                        <div class="payment-type-img-box">
                                            <img src="<?= base_url('assets/front_end/modern/image/pictures/Paystack_Logo.png') ?>" alt="">
                                        </div>
                                    </label>

                                <?php } ?>
                                <?php if (isset($payment_methods['payumoney_payment_method']) && $payment_methods['payumoney_payment_method'] == 1) { ?>
                                    <label class="form-check-label" for="payumoney">
                                        <input class="form-check-input" id="payumoney" name="payment_method" type="radio" value="Payumoney" required>
                                        <div class="payment-type-img-box">
                                            <img src="<?= base_url('assets/front_end/modern/image/pictures/PayUmoney_Logo.jpg') ?>" alt="">
                                        </div>
                                    </label>

                                <?php } ?>
                                <?php if (isset($payment_methods['flutterwave_payment_method']) && $payment_methods['flutterwave_payment_method'] == 1) { ?>
                                    <label class="form-check-label" for="flutterwave">
                                        <input class="form-check-input" id="flutterwave" name="payment_method" type="radio" value="Flutterwave" required>
                                        <div class="payment-type-img-box">
                                            <img src="<?= base_url('assets/front_end/modern/image/pictures/flutterwave-1.svg') ?>" alt="">
                                        </div>
                                    </label>

                                <?php } ?>
                                <?php if (isset($payment_methods['paytm_payment_method']) && $payment_methods['paytm_payment_method'] == 1) { ?>
                                    <label class="form-check-label" for="paytm">
                                        <input class="form-check-input" id="paytm" name="payment_method" type="radio" value="Paytm" required>
                                        <div class="payment-type-img-box">
                                            <img src="<?= base_url('assets/front_end/modern/image/pictures/paytm_logo.png') ?>" alt="">
                                        </div>
                                    </label>

                                <?php } ?>
                                <?php if (isset($payment_methods['stripe_payment_method']) && $payment_methods['stripe_payment_method'] == 1) { ?>
                                    <label class="form-check-label" for="stripe">
                                        <input class="form-check-input" id="stripe" name="payment_method" type="radio" value="Stripe" required>
                                        <div class="payment-type-img-box stripe-payment-box">
                                            <img src="<?= base_url('assets/front_end/modern/fontawesome-free-6.4.0-web/svgs/regular/credit-card.svg') ?>" alt="Debit/Credit Card">
                                        </div>
                                    </label>
                                <?php } ?>

                                <?php if (isset($payment_methods['midtrans_payment_method']) && $payment_methods['midtrans_payment_method'] == 1) { ?>
                                    <label class="form-check-label" for="midtrans">
                                        <input class="form-check-input" id="midtrans" name="payment_method" type="radio" value="Midtrans" required>
                                        <div class="payment-type-img-box">
                                            <img src="<?= base_url('assets/front_end/modern/image/pictures/midtrans-logo.png') ?>" alt="">
                                        </div>
                                    </label>

                                <?php } ?>
                                <?php if (isset($payment_methods['myfaoorah_payment_method']) && $payment_methods['myfaoorah_payment_method'] == 1) { ?>
                                    <label class="form-check-label" for="my_fatoorah">
                                        <input class="form-check-input" id="my_fatoorah" name="payment_method" type="radio" value="my_fatoorah" required>
                                        <div class="payment-type-img-box">
                                            <img src="<?= base_url('assets/front_end/modern/image/pictures/my-fatoorah-paddedV2.png') ?>" alt="">
                                        </div>
                                    </label>

                                <?php } ?>
                                <?php if (isset($payment_methods['instamojo_payment_method']) && $payment_methods['instamojo_payment_method'] == 1) { ?>
                                    <label class="form-check-label" for="instamojo">
                                        <input id="instamojo" class="form-check-input" name="payment_method" type="radio" value="instamojo">
                                        <div class="payment-type-img-box">
                                            <img src="<?= base_url('assets/front_end/modern/image/pictures/logo_instamojo.webp') ?>" class="payment-gateway-images" alt="instamojo">
                                        </div>
                                    </label>
                                <?php } ?>
                                <?php if (isset($payment_methods['direct_bank_transfer']) && $payment_methods['direct_bank_transfer'] == 1) { ?>
                                    <label class="form-check-label" for="bank_transfer">
                                        <input class="form-check-input" id="bank_transfer" name="payment_method" type="radio" value="<?= BANK_TRANSFER ?>" required>
                                        <div class="payment-type-img-box">
                                            <img src="<?= base_url('assets/front_end/modern/image/pictures/banktranfer-logo.png') ?>" alt="">
                                        </div>
                                    </label>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 bg-light-subtle" id="stripe_div" style="display: none;">
                        <div id="stripe-card-element" class="border-radius-10 p-3">
                            <!--Stripe.js injects the Card Element-->
                        </div>
                        <p id="card-error" role="alert"></p>
                        <p class="result-message hidden"></p>
                    </div>

                    <div id="my_fatoorah_div" style="display: none;">
                        <div id="card-element">
                            <!--Stripe.js injects the Card Element-->
                        </div>
                        <p id="card-error" role="alert"></p>
                        <p class="result-message hidden"></p>
                    </div>
                    <div id="bank_transfer_slide" style="display: none;">
                        <div id="account_data" class="d-none">
                            <?php if (isset($payment_methods['direct_bank_transfer']) && $payment_methods['direct_bank_transfer'] == 1) { ?>
                                <div class="row">
                                    <div class="alert alert-warning">
                                        <strong>Instruction! <br></strong> Make your payment directly into our account. Your order will not further proceed until the funds have cleared in our account.</br>
                                        You have to send your payment receipt from order details page then admin will verify that.
                                    </div>
                                    <div class="alert alert-info col-md-12">
                                        <strong>Account Details! </strong> <br><br>
                                        <ul>
                                            <li>Account Name: <?= (isset($payment_methods['account_name'])) ? $payment_methods['account_name'] : "" ?></li>
                                            <li>Account Number: <?= (isset($payment_methods['account_number'])) ? $payment_methods['account_number'] : "" ?></li>
                                            <li>Bank Name: <?= (isset($payment_methods['bank_name'])) ? $payment_methods['bank_name'] : "" ?></li>
                                            <li>Bank Code: <?= (isset($payment_methods['bank_code'])) ? $payment_methods['bank_code'] : "" ?></li>
                                        </ul>
                                    </div>
                                    <div class="alert alert-info col-md-12">
                                        <strong>Extra Details! </strong> <br><br>
                                        <p><?= (isset($payment_methods['notes'])) ? output_escaping($payment_methods['notes'])  : "" ?></p>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <input type="hidden" name="app_name" id="app_name" value="<?= $settings['app_name'] ?>" />
                    <input type="hidden" name="username" id="username" value="<?= $user->username ?>" />
                    <input type="hidden" id="user_id" value="<?= $user->id ?>" />
                    <input type="hidden" name="user_email" id="user_email" value="<?= isset($user->email) && !empty($user->email) ? $user->email : $support_email ?>" />
                    <input type="hidden" name="user_contact" id="user_contact" value="<?= $user->mobile ?>" />
                    <input type="hidden" name="logo" id="logo" value="<?= base_url(get_settings('web_logo')) ?>" />
                    <input type="hidden" name="order_amount" id="amount" value="" />
                    <input type="hidden" name="razorpay_order_id" id="razorpay_order_id" value="" />
                    <input type="hidden" name="phonepe_transaction_id" id="phonepe_transaction_id" value="" />
                    <input type="hidden" name="phonepe_order_id" id="phonepe_order_id" value="" />
                    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id" value="" />
                    <input type="hidden" name="razorpay_signature" id="razorpay_signature" value="" />
                    <input type="hidden" id="paytm_transaction_token" name="paytm_transaction_token" value="" />
                    <input type="hidden" id="paytm_order_id" name="paytm_order_id" value="" />

                    <input type="hidden" name="paystack_reference" id="paystack_reference" value="" />

                    <input type="hidden" name="stripe_client_secret" id="stripe_client_secret" value="" />
                    <input type="hidden" name="stripe_payment_id" id="stripe_payment_id" value="" />


                    <input type="hidden" name="my_fatoorah_order_id" id="my_fatoorah_order_id" value="" />
                    <input type="hidden" name="my_fatoorah_session_id" id="my_fatoorah_session_id" value="" />
                    <input type="hidden" name="my_fatoorah_payment_id" id="my_fatoorah_payment_id" value="" />

                    <input type="hidden" name="flutterwave_public_key" id="flutterwave_public_key" value="<?= $payment_methods['flutterwave_public_key'] ?>" />
                    <input type="hidden" id="flutterwave_currency" value="<?= $payment_methods['flutterwave_currency_code'] ?>" />
                    <input type="hidden" name="flutterwave_transaction_id" id="flutterwave_transaction_id" value="" />
                    <input type="hidden" name="flutterwave_transaction_ref" id="flutterwave_transaction_ref" value="" />
                    <input type="hidden" name="promo_set" id="promo_set" value="" />
                    <input type="hidden" name="instamojo_order_id" id="instamojo_order_id" value="" />
                    <input type="hidden" name="instamojo_payment_id" id="instamojo_payment_id" value="" />
                </div>
                <div class="col-lg-5">
                    <div class="order-checkout-detail">
                        <h5 class="checkout-page-titles"><?= label('my_orders', 'Your Order') ?></h5>
                        <div class="ordered-product-container">
                            <?php if (isset($cart) && !empty($cart)) {
                                if ($cart[0]['type'] != 'digital_product') {
                                    $product_not_delivarable = array_column($product_not_delivarable, "product_id");
                                }
                                foreach ($cart as $row) {
                               
                                    if (isset($row['qty']) && $row['qty'] != 0) {
                                        $price = $row['sale_price'] != '' && $row['sale_price'] != null && $row['sale_price'] > 0 && $row['sale_price'] < $row['price'] ? $row['sale_price'] : $row['price'];
                                        $special_price = $row['special_price'] != '' && $row['special_price'] != null && $row['special_price'] > 0 && $row['special_price'] < $row['price'] ? $row['special_price'] : $row['price'];
                            ?>

                                        <div class="justify-content-start ordered-product row pb-3">
                                            <div class="col-sm-3 ordered-product-img">
                                                <img 
                                                    class="w-100"
                                                    src="<?= !empty($row['image_sm']) 
                                                            ? base_url($row['image_sm']) 
                                                            : (!empty($row['image']) 
                                                                ? base_url($row['image']) 
                                                                : base_url(NO_IMAGE)) ?>"
                                                    alt=""
                                                    onerror="this.onerror=null;this.src='<?= base_url(NO_IMAGE) ?>';"
                                                />
                                            </div>
                                            <div class="col-sm-6 ordered-product-info">
                                                <p class="text-black fw-semibold pb-2"><?= word_limit(output_escaping(str_replace('\r\n', '&#13;&#10;', $row['name']))) ?></p>
                                                <?php if (!empty($row['product_variants'])) { ?>
                                                    <p><?= str_replace(',', ' | ', $row['product_variants'][0]['variant_values']) ?></p>
                                                <?php } ?>
                                                <p>Qty : <?= $row['qty'] ?></p>
                                                <?php
                                                if (isset($row['tax_types']) && !empty($row['tax_types'])) { ?>
                                                    <p>Taxes</p>
                                                    <?php foreach ($row['tax_types'] as $tax_type) { ?>
                                                        <div class="ms-2">
                                                            <span class="text-muted"><?= !empty($tax_type['title']) ? $tax_type['title'] : 'Tax' ?> :</span>
                                                            <span class="text-muted"><?= number_format($tax_type['percentage'], 2) . '%' ?></span>
                                                        </div>
                                                <?php }
                                                } ?>
                                                <div>
                                                    <span class="text-muted"><?= label('tax_amount', 'Tax Amount') ?> (<?= $settings['currency'] ?>) :
                                                        <?= number_format($row['tax_amount'] * $row['qty'], 2) ?>
                                                    </span>
                                                </div>
                                                <?php ?>
                                            </div>
                                            <div class="col-sm-3 ordered-product-price text-sm-end">
                                                <?php

                                                if(isset($row['sale_price']) && !empty($row['sale_price'])) { ?>
                                                    <p><?= $settings['currency'] ?> <?= number_format($row['qty'] * ($row['sale_price'] - $row['sale_price_tax_amount']), 2) ?></p>
                                                <?php  }elseif($row['special_price'] > 0) { ?>
                                                    <p><?= $settings['currency'] ?> <?= number_format($row['qty'] * $row['special_price'], 2) ?></p>
                                                <?php  }else{ ?>
                                                    <p><?= $settings['currency'] ?> <?= number_format($row['qty'] * $price, 2) ?></p>
                                                <?php }
                                                ?>
                                            </div>
                                            <?php if ($cart[0]['type'] != 'digital_product') { ?>
                                                <div id="p_<?= $row['product_id'] ?>" class="text-red deliverable_status"><?= (isset($default_address) && !empty($default_address) && in_array($row['product_id'], $product_not_delivarable)) ? "Not deliverable" : "" ?></div>
                                            <?php } ?>
                                        </div>
                            <?php } }
                            } ?>
                        </div>
                          <input type="hidden" name="total" value="<?= number_format($cart['sub_total'], 2) ?>">
                       <?php
                          $custom_charges = get_settings('custom_charges', true);
                        
                          $custom_charges_total = 0;
                          // Doorstep is default, so check doorstep toggle for initial total
                          $include_charges = !isset($settings['custom_charges_doorstep']) || $settings['custom_charges_doorstep'] == '1';

                          if ($include_charges && !empty($custom_charges)) {
                              foreach ($custom_charges as $c) {
                                  $custom_charges_total += floatval($c['amount']);
                              }
                          }
                           ?>
                        <input type="hidden" id="sub_total" value="<?= $cart['sub_total'] ?>">
                        <input type="hidden" id="custom_charges_total" name="custom_charges_total" value="<?= $custom_charges_total ?>">
                        <input type="hidden" id="custom_charges_json"
                        value='<?= json_encode(get_settings("custom_charges", true)) ?>'>
                        <input type="hidden" id="platform_fees" value="<?= isset($platform_fees) ? $platform_fees : 0 ?>">
                        <input type="hidden" id="bulk_discount" name="bulk_discount" value="<?= isset($bulk_discount) ? $bulk_discount : 0 ?>">
                        <input type="hidden" id="custom_charges_doorstep" value="<?= (!isset($settings['custom_charges_doorstep']) || $settings['custom_charges_doorstep'] == '1') ? '1' : '0' ?>">
                        <input type="hidden" id="custom_charges_pickup" value="<?= (isset($settings['custom_charges_pickup']) && $settings['custom_charges_pickup'] == '1') ? '1' : '0' ?>">

                        <div class="ordered-subtotal-section">
                            <div class="fw-medium"><?= label('subtotal', 'Subtotal') ?></div>
                            <div class="subtotal-price"><?= $settings['currency'] . ' <span class="sub_total">' . number_format($cart['sub_total'], 2) . '</span>' ?></div>
                        </div>
                        <?php if (!empty($cart['tax_percentage'])) { ?>
                            <div class="charges-section d-none">
                                <div class="charges-title">
                                    <p><?= label('tax', 'tax') ?> (<?= $cart['tax_percentage'] ?>%)</p>
                                </div>
                                <div class="charges">
                                    <p><?= $settings['currency'] . ' ' . number_format($cart['tax_amount'], 2) ?></p>
                                </div>
                            </div>
                        <?php } ?>
                        <?php
                        $shiprocket_settings = get_settings('shipping_method', true);
                        if (isset($shiprocket_settings['shiprocket_shipping_method']) && $shiprocket_settings['shiprocket_shipping_method'] == 1) {
                        ?>
                            <?php if ($cart[0]['type'] != 'digital_product') { ?>
                                <div class="charges-title d-none">
                                    <div class="delivery_charge charges-title">
                                        <p><?= label('shipping_method', 'shipping method') ?></p>
                                    </div>
                                    <div class="charges deliverycharge_currency">
                                        <p><?= $settings['currency'] . ' ' ?><span class="shipping_method"></span></p>
                                    </div>
                                </div>
                                <div class="charges-section">
                                    <div class="delivery_charge charges-title">
                                        <p><?= label('delivery_charge_with_cod', 'Delivery Charge with COD') ?></p>
                                    </div>
                                    <div class="charges deliverycharge_currency">
                                        <p><?= $settings['currency'] . ' ' ?><span class="delivery_charge_with_cod"></span> </p>
                                        <input type="hidden" name="delivery_charge_with_cod" class="delivery_charge_with_cod" value="" />
                                    </div>
                                </div>
                                <div class="charges-section">
                                    <div class="delivery_charge charges-title">
                                        <p><?= label('delivery_charge_without_cod', 'Delivery Charge without COD') ?></p>
                                    </div>
                                    <div class="charges deliverycharge_currency">
                                        <p><?= $settings['currency'] . ' ' ?><span class="delivery_charge_without_cod"></span></p>
                                        <input type="hidden" name="delivery_charge_without_cod" class="delivery_charge_without_cod" value="" />
                                    </div>
                                </div>
                                <div class="charges-section estimate_date_section">
                                    <div class="delivery_charge charges-title">
                                        <p><?= label('estimate_delivery_date', 'Estimated Delivery Date') ?></p>
                                    </div>
                                    <div class="charges">
                                        <p class="estimate_date"></p>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <div class="charges-section">
                                <div class="delivery_charge charges-title">
                                    <p><?= label('delivery_charge_with_cod', 'Delivery Charge with COD') ?></p>
                                </div>
                                <div class="charges deliverycharge_currency">
                                    <p><?= $settings['currency'] . ' ' ?><span class="delivery_charge_with_cod"></span> </p>
                                    <input type="hidden" name="delivery_charge_with_cod" class="delivery_charge_with_cod" value="" />
                                </div>
                            </div>
                            <div class="charges-section">
                                <div class="delivery_charge charges-title">
                                    <p><?= label('delivery_charge_without_cod', 'Delivery Charge without COD') ?></p>
                                </div>
                                <div class="charges deliverycharge_currency">
                                    <p><?= $settings['currency'] . ' ' ?><span class="delivery_charge_without_cod"></span></p>
                                    <input type="hidden" name="delivery_charge_without_cod" class="delivery_charge_without_cod" value="" />
                                </div>
                            </div>

                        <?php } ?>
                       
                         <?php
                            $custom_charges = get_settings('custom_charges', true);
                            // Check if custom charges should be shown for doorstep delivery (default)
                            $show_custom_charges = (!isset($settings['custom_charges_doorstep']) || $settings['custom_charges_doorstep'] == '1');
                            ?>
                            
                       

                          <div id="custom_charges_section" class="d-none">
                          <div id="custom_charges_list"></div>
                          </div>




                        <div id="promocode_div" class="charges-section d-none">
                            <div class="fw-semibold">
                                <?= !empty($this->lang->line('promocode')) ? $this->lang->line('promocode') : 'Promocode' ?> <span id="promocode"></span>
                            </div>
                            <div class="fw-semibold"> <span id="promocode_amount"></span>
                            </div>
                        </div>
                        <?php if (isset($bulk_discount) && $bulk_discount > 0) { ?>
                        <div id="bulk_discount_div" class="charges-section">
                            <div class="fw-semibold">
                                <?= !empty($this->lang->line('bulk_discount')) ? $this->lang->line('bulk_discount') : 'Bulk Discount' ?>
                            </div>
                            <div class="fw-semibold text-success">
                                -<?= $settings['currency'] ?> <span id="bulk_discount_amount"><?= number_format($bulk_discount, 2) ?></span>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="charges-section wallet-section d-none">
                            <div class="total-price-tital"><?= !empty($this->lang->line('wallet')) ? $this->lang->line('wallet') : 'Wallet' ?></div>
                            <div class="total-price"><?= $settings['currency'] ?> <span class="wallet_used">0.00<span></div>
                        </div>
                        <div class="final-order-total pb-0">
                            <?php
                            if ($cart[0]['type'] != 'digital_product') { ?>
                                <p class="total-price-tital"><?= label('total_with_cod', 'Total') ?></p>
                                <p class="total-price"><?= $settings['currency'] ?> <span id="final_total" value=""><?= number_format($cart['sub_total'], 2) ?></span></p>
                            <?php } else { ?>
                                <p class="total-price-tital"><?= label('total', 'Total') ?></p>
                                <p class="total-price"><?= $settings['currency'] ?> <span id="final_total" value=""><?= number_format($cart['sub_total'], 2) ?></span></p>
                            <?php  } ?>
                        </div>
                        <p class="m-0 fst-italic text-end">Inclusive of all taxes & Shipping</p>
                        <div class="coupon-box input-group gap-3 pt-3">
                            <input type="text" class="form-control" placeholder="Coupon Code" id="promocode_input">
                            <div class="input-group-append">
                                <button class="btn btn-primary" id="redeem_btn"><?= label('apply_coupon', 'Apply Coupon') ?></button>
                                <button class="btn btn-primary d-none" id="clear_promo_btn"><?= label('clear', 'Clear') ?></button>
                            </div>
                        </div>
                        <div class="input-group pt-2">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#promo_code_modal"><?= label('see_all_offers', 'See All Offers(%)') ?></a>
                        </div>
                        <?php $is_disabled = false;
                        foreach ($product_not_delivarable as $p_id) {
                            if (!empty($p_id['product_id'])) {
                                $is_disabled = true;
                                continue;
                            }
                        } ?>
                        <?php if (isset($message) && !empty($message)) { ?>
                            <button disabled class="btn btn-primary w-100 mt-3" type="submit" <?= ($is_disabled) ? "disabled" : ""; ?>><?= label('place_order', 'Place Order') ?></button>
                        <?php } else { ?>
                            <a href="<?= base_url('cart/checkout/order_placed') ?>">
                                <button class="btn btn-primary w-100 mt-3" id="place_order_btn" type="submit" <?= ($is_disabled) ? "disabled" : ""; ?>><?= label('place_order', 'Place Order') ?></button>
                            </a>
                        <?php } ?>
                    </div>

                </div>
            </div>
        </form>
    </section>

    <!-- address select modal -->
    <div class="modal fade address-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" id="address-modal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <section id="address_form">
                    <div class="modal-header">
                        <div class="address-modal-title">
                            Shipping Address
                        </div>
                    </div>
                    <div class="modal-body overflow-y-auto height70vh">
                        <div class="shipping-address-modal">
                            <div>
                                <div class="form-check form-check-address-modal py-2">
                                    <div id="address-list"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-block">
                        <div class="d-flex gap-2 pt-2">
                            <button type="button" class="btn cancle-btn border w-100" data-bs-dismiss="modal"><?= label('close', 'Close') ?></button>
                            <button type="button" class="btn btn-primary w-100 submit" id="select_address" data-bs-dismiss="modal"><?= label('save', 'Save') ?></button>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <div class="modal fade" id="checkout-create-address-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= label('create_a_new_address', 'Create a New Address') ?></h5>
                </div>
                <form id="checkout-create-address-form">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="checkout_address_name" class="form-label"><?= label('name', 'Name') ?> <sup class="text-danger">*</sup></label>
                                <input type="text" class="form-control" id="checkout_address_name" name="name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="checkout_mobile_number" class="form-label"><?= label('contact_number', 'Contact Number') ?> <sup class="text-danger">*</sup></label>
                                <input type="text" class="form-control" id="checkout_mobile_number" name="mobile" required>
                            </div>
                            <div class="col-12">
                                <label for="checkout_address" class="form-label"><?= label('address', 'Address') ?> <sup class="text-danger">*</sup></label>
                                <textarea class="form-control" id="checkout_address" name="address" rows="3" required></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="checkout_country" class="form-label"><?= label('country', 'Country') ?> <sup class="text-danger">*</sup></label>
                                <input type="text" class="form-control" id="checkout_country" name="country" required>
                            </div>
                            <div class="col-md-6">
                                <label for="checkout_state" class="form-label"><?= label('state', 'State') ?> <sup class="text-danger">*</sup></label>
                                <input type="text" class="form-control" id="checkout_state" name="state" required>
                            </div>
                            <div class="col-md-6">
                                <label for="checkout_city" class="form-label"><?= label('city', 'City') ?> <sup class="text-danger">*</sup></label>
                                <select class="form-control" id="checkout_city" name="city_id" required>
                                    <option value=""><?= label('city', 'Select City') ?></option>
                                    <?php if (!empty($cities)) {
                                        foreach ($cities as $city) { ?>
                                            <option value="<?= $city['id'] ?>"><?= htmlspecialchars($city['name'], ENT_QUOTES, 'UTF-8') ?></option>
                                    <?php }
                                    } ?>
                                </select>
                                <input type="hidden" name="city_name" id="checkout_city_name" value="" />
                            </div>
                            <div class="col-md-6">
                                <label for="checkout_pincode" class="form-label"><?= label('zipcode', 'ZIP Code') ?> <sup class="text-danger">*</sup></label>
                                <div class="position-relative zipcode-autocomplete-wrap">
                                    <input type="text" class="form-control" id="checkout_pincode" name="pincode"
                                        inputmode="numeric" autocomplete="off" maxlength="10"
                                        placeholder="e.g. 10001 or 10001-1234" pattern="^\d{5}(-\d{4})?$" required>
                                    <div id="checkout-zipcode-suggestions" class="list-group zipcode-suggestions-list" style="display:none;"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="checkout_alternate_mobile" class="form-label"><?= label('alternate_mobile', 'Alternate Mobile') ?></label>
                                <input type="text" class="form-control" id="checkout_alternate_mobile" name="alternate_mobile">
                            </div>
                            <div class="col-12">
                                <label class="form-label d-block"><?= label('type', 'Type') ?></label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="type" id="checkout_type_home" value="home" checked>
                                    <label class="form-check-label" for="checkout_type_home"><?= label('home', 'Home') ?></label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="type" id="checkout_type_office" value="office">
                                    <label class="form-check-label" for="checkout_type_office"><?= label('office', 'Office') ?></label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="type" id="checkout_type_other" value="other">
                                    <label class="form-check-label" for="checkout_type_other"><?= label('other', 'Other') ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-block">
                        <div class="d-flex gap-2 pt-2">
                            <button type="button" class="btn cancle-btn border w-100" data-bs-dismiss="modal"><?= label('close', 'Close') ?></button>
                            <button type="submit" class="btn btn-primary w-100" id="checkout_save_address_btn"><?= label('save', 'Save') ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- promo codes modal -->
    <div class="modal fade" id="promo_code_modal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <section id="promo_code_form">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel"><?= label('promo_code', 'Promocodes') ?></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="promocode-list"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><?= label('apply', 'Apply') ?></button>
                    </div>
                </div>
            </div>
    </div>
</main>

<input type="hidden" id="csrf_token" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
<form action="<?= base_url('payment/paypal') ?>" id="paypal_form" method="POST">

    <input type="hidden" name="order_id" id="paypal_order_id" value="" />
</form>
<input type="hidden" name="stripe_key_id" id="stripe_key_id" value="<?= $payment_methods['stripe_publishable_key'] ?>" />
<input type="hidden" name="instamojo_payment_method" id="instamojo_payment_method" value="<?= (isset($payment_methods['instamojo_payment_method']) && $payment_methods['instamojo_payment_method'] == 1) ? 1 : 0 ?>" />
<input type="hidden" name="razorpay_key_id" id="razorpay_key_id" value="<?= $payment_methods['razorpay_key_id'] ?>" />
<input type="hidden" name="paystack_key_id" id="paystack_key_id" value="<?= $payment_methods['paystack_key_id'] ?>" />
<input type="hidden" id="delivery_starts_from" value="<?= (isset($time_slot_config['delivery_starts_from']) ? $time_slot_config['delivery_starts_from'] : '') ?>">
<input type="hidden" id="delivery_ends_in" value="<?= (isset($time_slot_config['allowed_days']) ? $time_slot_config['allowed_days'] : '') ?>">

<?php if (isset($payment_methods['paytm_payment_method']) && $payment_methods['paytm_payment_method'] == 1) {
    $url = ($payment_methods['paytm_payment_mode'] == "production") ? "https://securegw.paytm.in/" : "https://securegw-stage.paytm.in/";
?>
    <script type="application/javascript" src="<?= $url ?>merchantpgpui/checkoutjs/merchants/<?= $payment_methods['paytm_merchant_id'] ?>.js"></script>
<?php } ?>

<?php if (isset($payment_methods['flutterwave_payment_method']) && $payment_methods['flutterwave_payment_method'] == 1) { ?>
    <script src="https://checkout.flutterwave.com/v3.js"></script>
<?php } ?>

<?php if (isset($payment_methods['stripe_payment_method']) && $payment_methods['stripe_payment_method'] == 1) { ?>
    <script src="https://js.stripe.com/v3/"></script>
<?php } ?>
<?php if (isset($payment_methods['myfaoorah_payment_method']) && $payment_methods['myfaoorah_payment_method'] == 1) { ?>
    <script src="https://demo.myfatoorah.com/cardview/v2/session.js"></script>
<?php } ?>

<?php if (isset($payment_methods['razorpay_payment_method']) && $payment_methods['razorpay_payment_method'] == 1) { ?>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<?php } ?>

<?php if (isset($payment_methods['paystack_payment_method']) && $payment_methods['paystack_payment_method'] == 1) { ?>
    <script src="https://js.paystack.co/v1/inline.js"></script>
<?php } ?>

<?php if (isset($payment_methods['instamojo_payment_method']) && $payment_methods['instamojo_payment_method'] == 1) { ?>
    <script src="https://js.instamojo.com/v1/checkout.js"></script>
<?php } ?>
<?php
$midtrans_url = $midtrans_client_key = "";
if (isset($payment_methods['midtrans_payment_mode'])) {
    $midtrans_url = (isset($payment_methods['midtrans_payment_mode']) && $payment_methods['midtrans_payment_mode'] == "sandbox") ? "https://app.sandbox.midtrans.com/snap/snap.js" : "https://app.midtrans.com/snap/snap.js";
    $midtrans_client_key = $payment_methods['midtrans_client_key'];
}
?>

<?php if (isset($payment_methods['midtrans_payment_mode'])) { ?>

    <script type="text/javascript" src="<?= $midtrans_url ?>" data-client-key="<?= $midtrans_client_key ?>"></script>
<?php } ?>
<script src="<?= base_url('assets/front_end/modern/js/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/front_end/modern/js/moment.min.js') ?>"></script>
<script src="<?= base_url('assets/front_end/modern/js/daterangepicker.js') ?>"></script>
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
<script src="<?= base_url('assets/front_end/modern/js/checkout.js') ?>"></script>
