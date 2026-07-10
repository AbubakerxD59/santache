<!-- breadcrumb -->
<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h2><?= !empty($this->lang->line('checkout')) ? $this->lang->line('checkout') : 'Checkout' ?></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <li class="breadcrumb-item"><?= !empty($this->lang->line('checkout')) ? $this->lang->line('checkout') : 'Checkout' ?></li>
            </ol>
        </nav>
    </div>

</section>
<!-- end breadcrumb -->
<!-- checkout -->
<section>
    <form class="needs-validation" id="checkout_form" method="POST" action="<?= base_url('cart/place-order') ?>">
        <div class="main-content">
            <div class="row">
                <div class="col-md-8 bg-white mt-5">
                    <!-- select pickup -->
                    <?php $shiprocket_settings = get_settings('shipping_method', true);

                    if (isset($shiprocket_settings['local_shipping_method']) && $shiprocket_settings['local_shipping_method'] == 1 && ($cart[0]['type'] != 'digital_product')) { ?>
                        <div class="form-check d-flex">
                            <input class="mr-2" id="door_step" name="local_pickup" type="radio" value="0" checked>
                            <label class="form-check-label d-flex mr-5" for="door_step">
                                <?= label('door_step_delivery', 'Door Step Delivery') ?>
                            </label>

                            <input class="mr-2" id="pickup_from_store" name="local_pickup" type="radio" value="1">
                            <label class="form-check-label d-flex" for="pickup_from_store">
                                <?= label('pickup_from_store', 'Pickup From Store') ?>
                            </label>
                        </div>
                    <?php } else { ?>
                        <div class="form-check d-flex">
                            <input class="mr-2 d-none" id="door_step" name="local_pickup" type="radio" value="0" checked>
                        </div>
                    <?php } ?>
                    <hr>

                    <!-- address module -->
                    <div class="address">
                        <h2 class="checkout-form-title"><?= !empty($this->lang->line('billing_details')) ? $this->lang->line('billing_details') : 'Billing Details' ?></h2>

                        <div class="ship-details-wrapper">
                            <input type="hidden" name="product_type" id="product_type" value="<?= $cart[0]['type']; ?>">
                            <input type="hidden" name="download_allowed" value="<?= in_array(0, array($cart['download_allowed'])) ? 0 : 1 ?>">
                            <?php if ($cart[0]['type'] != 'digital_product') { ?>
                                <div class="align-item-center ship-title-details justify-content-between user-add d-flex">
                                    <h5 class="pb-3"><?= !empty($this->lang->line('shipping_address')) ? $this->lang->line('shipping_address') : 'Shipping Address' ?></h5>
                                    <a href="#" data-izimodal-open=".address-modal"><i class="fas fa-edit edit-icon"></i></a>
                                </div>

                                <div class="shipped-details mt-3">
                                    <p class="text-muted" id="address-name-type"><?= isset($default_address) && !empty($default_address) ? $default_address[0]['name'] . ' - ' . ucfirst($default_address[0]['type']) : '' ?></p>
                                    <p class="text-muted" id="address-full"><?= isset($default_address) && !empty($default_address) ? trim($default_address[0]['address']) : '' ?></p>
                                    <p class="text-muted" id="address-country"><?php
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

                                </br>
                                <!-- checking product deliverable or not  -->
                                <div id="deliverable_status">
                                    <?php
                                    $product_not_delivarable = array();

                                    if (isset($default_address) && !empty($default_address)) {

                                        $product_delivarable = check_cart_products_delivarable($default_address[0]['area_id'], $cart[0]['user_id']);
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
                                            <b class="text-danger"><?= $deliverable_error_msg ?></b>
                                        <?php }
                                    } else { ?>
                                        <b class="text-danger">Please select address.</b>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <?php if (in_array(0, array($cart['download_allowed'])) && $cart[0]['type'] == 'digital_product') { ?>
                                <div class="input-group mt-3">
                                    <input name="email" type="text" class="form-control" placeholder="Please enter your email ID ">
                                </div>
                            <?php } ?>

                            <input type="hidden" name="address_id" id="address_id" value="<?= isset($default_address) && !empty($default_address) ? $default_address[0]['id'] : '' ?>" />
                            <input type="hidden" name="mobile" id="mobile" value="<?= isset($default_address) && !empty($default_address) ? $default_address[0]['mobile'] : $wallet_balance[0]['mobile'] ?>" />
                        </div>
                        <hr>
                    </div>

                    <input type="hidden" name="total" value="<?= number_format($cart['sub_total'], 2) ?>">
                       <?php
                           $custom_charges = get_settings('custom_charges', true);
                           $custom_charges_total = 0;
                           
                           if (!empty($custom_charges)) {
                               foreach ($custom_charges as $c) {
                                   $custom_charges_total += floatval($c['amount']);
                               }
                           }
                           ?>
                    <input type="hidden" id="custom_charges_total" name="custom_charges_total" value="<?= $custom_charges_total ?>">
                        <input type="hidden" name="custom_charges_json" id="custom_charges_json" value='<?= json_encode($custom_charges, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>'>
                    <input type="hidden" id="platform_fees" value="<?= isset($platform_fees) ? $platform_fees : 0 ?>">
                    <input type="hidden" id="bulk_discount" name="bulk_discount" value="<?= isset($bulk_discount) ? $bulk_discount : 0 ?>">
                    <input type="hidden" id="temp_total" name="temp_total" value="<?= $cart['total_arr'] ?>">
                    <input type="hidden" name="product_variant_id" value="<?= implode(',', array_column($cart, 'id')) ?>">
                    <input type="hidden" name="quantity" value="<?= implode(',', array_column($cart, 'qty')) ?>">
                    <input type="hidden" id="current_wallet_balance" value="<?= number_format($wallet_balance[0]['balance'], 2) ?>">
                    <input type="hidden" id="wallet_used" name="wallet_used">
                    <input type="hidden" id="pickup" name="pickup_type" value="">

                    <?php $shiprocket_settings = get_settings('shipping_method', true);
                    if (isset($shiprocket_settings['local_shipping_method']) && $shiprocket_settings['local_shipping_method'] == 1) { ?>
                        <input type="hidden" name="is_time_slots_enabled" id="is_time_slots_enabled" value="0">
                        <?php if ($cart[0]['type'] != 'digital_product') { ?>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Special Note for Order" name="order_note" id="order_note">
                            </div>
                            <hr>
                        <?php }
                        $settings = get_settings('system_settings', true);
                        ?>

                        <?php
                        foreach ($cart as $row) {
                            if (isset($row['is_attachment_required']) && $row['is_attachment_required'] == 1 && $cart[0]['type'] != 'digital_product') {

                                //If Allow Upload Attachment On
                        ?>
                                <h4 class="mt-3"><?= !empty($this->lang->line('allow_order_attachments')) ? $this->lang->line('allow_order_attachments') : 'Upload Order Attachments if you have any(Only images and docs are allowded)' ?></h4>
                                <div class="input-group">
                                    <input type="file" class="form-control" name="documents[]" multiple id="documents">
                                </div>
                    <?php }
                        }
                    }
                    ?>
                    <input type="hidden" name="delivery_date" id="delivery_date" class="form-control float-right">

                    <div class="align-item-center ship-title-details justify-content-between d-flex">
                        <h5><?= !empty($this->lang->line('wallet_balance')) ? $this->lang->line('wallet_balance') : 'Use wallet balance' ?></h5>
                    </div>
                    <?php $disabled = $wallet_balance[0]['balance'] == 0 ? 'disabled' : '' ?>
                    <div class="form-check d-flex">
                        <input class="form-check-input" type="checkbox" value="" id="wallet_balance" <?= $disabled ?>>
                        <label class="form-check-label d-flex" for="wallet_balance">
                            <?= !empty($this->lang->line('Available_balance')) ? $this->lang->line('Available_balance') : 'Availablebalance' ?> : <?= $currency . '<span id="available_balance">' . number_format($wallet_balance[0]['balance'], 2) . '</span>' ?>
                        </label>
                    </div>

                    <br>
                    <hr>

                    <div class="ship-details-wrapper mt-3 payment-methods">
                        <div class="align-item-center ship-title-details justify-content-between d-flex">
                            <h5><?= !empty($this->lang->line('payment_method')) ? $this->lang->line('payment_method') : 'Payment Method' ?></h5>
                        </div>
                        <div class="shipped-details mt-3 col-md-6">
                            <table class="table table-step-shipping">
                                <tbody>
                                    <?php if (isset($payment_methods['cod_method']) && $payment_methods['cod_method'] == 1 && $cart[0]['type'] != 'digital_product') { ?>
                                        <tr>
                                            <label for="cod">
                                                <td>
                                                    <label for="cod">
                                                        <input id="cod" title="<?= isset($cart[0]['is_cod_allowed']) && $cart[0]['is_cod_allowed'] == 0 ? 'Cash on delivery is not allowed for one of the item in your cart' : 'Please select one of this options.' ?>" name="payment_method" type="radio" value="COD" <?= isset($cart[0]['is_cod_allowed']) && $cart[0]['is_cod_allowed'] == 0 ? 'disabled' : '' ?>>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label for="cod">
                                                        <img src="<?= THEME_ASSETS_URL . 'images/cod.png' ?>" class="payment-gateway-images" alt="COD">
                                                    </label>
                                                </td>
                                                <td>
                                                    <label for="cod">
                                                        Cash On Delivery
                                                    </label>
                                                </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($payment_methods['phonepe_payment_method']) && $payment_methods['phonepe_payment_method'] == 1) { ?>
                                        <tr>
                                            <td>
                                                <label for="phonepe">
                                                    <input id="phonepe" name="payment_method" type="radio" value="phonepe">
                                                </label>
                                            </td>
                                            <td>
                                                <label for="phonepe">
                                                    <img src="<?= THEME_ASSETS_URL . 'images/phonepe.png' ?>" class="payment-gateway-images" alt="phonepe">
                                                </label>
                                            </td>
                                            <td>
                                                <label for="phonepe">
                                                    Phonepe
                                                </label>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($payment_methods['paypal_payment_method']) && $payment_methods['paypal_payment_method'] == 1) { ?>
                                        <tr>
                                            <td>
                                                <label for="paypal">
                                                    <input id="paypal" name="payment_method" type="radio" value="Paypal" required>
                                                </label>
                                            </td>
                                            <td>
                                                <label for="paypal">
                                                    <img src="<?= THEME_ASSETS_URL . 'images/paypal.png' ?>" class="payment-gateway-images" alt="Paypal">
                                                </label>
                                            </td>
                                            <td>
                                                <label for="paypal">
                                                    Paypal
                                                </label>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($payment_methods['razorpay_payment_method']) && $payment_methods['razorpay_payment_method'] == 1) { ?>
                                        <tr>
                                            <td>
                                                <label for="razorpay">
                                                    <input id="razorpay" name="payment_method" type="radio" value="Razorpay" required>
                                                </label>
                                            </td>
                                            <td>
                                                <label for="razorpay">
                                                    <img src="<?= THEME_ASSETS_URL . 'images/razorpay.png' ?>" class="payment-gateway-images" alt="Razorpay">
                                                </label>
                                            </td>
                                            <td>
                                                <label for="razorpay">
                                                    RazorPay
                                                </label>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($payment_methods['paystack_payment_method']) && $payment_methods['paystack_payment_method'] == 1) { ?>
                                        <tr>
                                            <td>
                                                <label for="paystack">
                                                    <input id="paystack" name="payment_method" type="radio" value="Paystack" required>
                                                </label>
                                            </td>
                                            <td>
                                                <label for="paystack">
                                                    <img src="<?= THEME_ASSETS_URL . 'images/paystack.png' ?>" class="payment-gateway-images" alt="Paystack">
                                                </label>
                                            </td>
                                            <td>
                                                <label for="paystack">
                                                    Paystack
                                                </label>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($payment_methods['payumoney_payment_method']) && $payment_methods['payumoney_payment_method'] == 1) { ?>
                                        <tr>
                                            <td>
                                                <label for="payumoney">
                                                    <input id="payumoney" name="payment_method" type="radio" value="Payumoney" required>
                                                </label>
                                            </td>
                                            <td>
                                                <label for="payumoney">
                                                    <img src="<?= THEME_ASSETS_URL . 'images/payumoney.png' ?>" class="payment-gateway-images" alt="Payumoney">
                                                </label>
                                            </td>
                                            <td>
                                                <label for="payumoney">
                                                    Payumoney
                                                </label>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($payment_methods['flutterwave_payment_method']) && $payment_methods['flutterwave_payment_method'] == 1) { ?>
                                        <tr>
                                            <td>
                                                <label for="flutterwave">
                                                    <input id="flutterwave" name="payment_method" type="radio" value="Flutterwave" required>
                                                </label>
                                            </td>
                                            <td>
                                                <label for="flutterwave">
                                                    <img src="<?= THEME_ASSETS_URL . 'images/flutterwave.png' ?>" class="payment-gateway-images" alt="Flutterwave">
                                                </label>
                                            </td>
                                            <td>
                                                <label for="flutterwave">
                                                    Flutterwave
                                                </label>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($payment_methods['paytm_payment_method']) && $payment_methods['paytm_payment_method'] == 1) { ?>
                                        <tr>
                                            <td>
                                                <label for="paytm">
                                                    <input id="paytm" name="payment_method" type="radio" value="Paytm" required>
                                                </label>
                                            </td>
                                            <td>
                                                <label for="paytm">
                                                    <img src="<?= THEME_ASSETS_URL . 'images/paytm.png' ?>" class="payment-gateway-images" alt="Paytm">
                                                </label>
                                            </td>
                                            <td>
                                                <label for="paytm">
                                                    Paytm
                                                </label>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($payment_methods['stripe_payment_method']) && $payment_methods['stripe_payment_method'] == 1) { ?>
                                        <tr>
                                            <td>
                                                <label for="stripe">
                                                    <input id="stripe" name="payment_method" type="radio" value="Stripe" required>
                                                </label>
                                            </td>
                                            <td>
                                                <label for="stripe">
                                                    <img src="<?= base_url('assets/front_end/modern/fontawesome-free-6.4.0-web/svgs/regular/credit-card.svg') ?>" class="payment-gateway-images" alt="Debit/Credit Card">
                                                </label>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($payment_methods['direct_bank_transfer']) && $payment_methods['direct_bank_transfer'] == 1) { ?>
                                        <tr>
                                            <td>
                                                <label for="bank_transfer">
                                                    <input id="bank_transfer" name="payment_method" type="radio" value="<?= BANK_TRANSFER ?>" required>
                                                </label>
                                            </td>
                                            <td>
                                                <label for="bank_transfer">
                                                    <img src="<?= THEME_ASSETS_URL . 'images/bank_transfer.png' ?>" class="payment-gateway-images" alt="Direct Bank Transfers">
                                                </label>
                                            </td>
                                            <td>
                                                <label for="bank_transfer">
                                                    Direct Bank Transfers
                                                </label>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($payment_methods['midtrans_payment_method']) && $payment_methods['midtrans_payment_method'] == 1) { ?>
                                        <tr>
                                            <td>
                                                <label for="midtrans">
                                                    <input id="midtrans" name="payment_method" type="radio" value="Midtrans" required>
                                                </label>
                                            </td>
                                            <td>
                                                <label for="midtrans">
                                                    <img src="<?= THEME_ASSETS_URL . 'images/midtrans.jpg' ?>" class="payment-gateway-images" alt="Midtrans">
                                                </label>
                                            </td>
                                            <td>
                                                <label for="midtrans">
                                                    Midtrans
                                                </label>
                                            </td>
                                        </tr>
                                    <?php } ?>


                                    <?php if (isset($payment_methods['myfaoorah_payment_method']) && $payment_methods['myfaoorah_payment_method'] == 1) { ?>
                                        <tr>
                                            <td>
                                                <label for="my_fatoorah">
                                                    <input id="my_fatoorah" name="payment_method" type="radio" value="my_fatoorah" required>
                                                </label>
                                            </td>
                                            <td>
                                                <label for="my_fatoorah">
                                                    <img src="<?= THEME_ASSETS_URL . 'images/myfatoorah.jpg' ?>" class="payment-gateway-images" alt="myfatoorah">
                                                </label>
                                            </td>
                                            <td>
                                                <label for="my_fatoorah">
                                                    My Fatoorah
                                                </label>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($payment_methods['instamojo_payment_method']) && $payment_methods['instamojo_payment_method'] == 1) { ?>
                                        <tr>
                                            <td>
                                                <label for="instamojo">
                                                    <input id="instamojo" name="payment_method" type="radio" value="instamojo">
                                                </label>
                                            </td>
                                            <td>
                                                <label for="instamojo">
                                                    <img loading="lazy" src="<?= THEME_ASSETS_URL . 'images/instamojo.png' ?>" class="payment-gateway-images height-30px" alt="instamojo">
                                                </label>
                                            </td>
                                            <td>
                                                <label for="instamojo">
                                                    Instamojo
                                                </label>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="stripe_div">
                        <div id="stripe-card-element">
                            <!--Stripe.js injects the Card Element-->
                        </div>
                        <p id="card-error" role="alert"></p>
                        <p class="result-message hidden"></p>
                    </div>

                    <div id="my_fatoorah_div">
                        <div id="card-element">
                            <!--Stripe.js injects the Card Element-->
                        </div>
                        <p id="card-error" role="alert"></p>
                        <p class="result-message hidden"></p>
                    </div>
                    <div id="bank_transfer_slide">
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
                    <hr class="mb-2">
                    <input type="hidden" name="app_name" id="app_name" value="<?= $settings['app_name'] ?>" />
                    <input type="hidden" name="username" id="username" value="<?= $user->username ?>" />
                    <input type="hidden" id="user_id" value="<?= $user->id ?>" />
                    <input type="hidden" name="user_email" id="user_email" value="<?= isset($user->email) && !empty($user->email) ? $user->email : $support_email ?>" />
                    <input type="hidden" name="user_contact" id="user_contact" value="<?= $user->mobile ?>" />
                    <input type="hidden" name="logo" id="logo" value="<?= base_url(get_settings('web_logo')) ?>" />
                    <input type="hidden" name="order_amount" id="amount" value="" />
                    <input type="hidden" name="razorpay_order_id" id="razorpay_order_id" value="" />
                    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id" value="" />
                    <input type="hidden" name="razorpay_signature" id="razorpay_signature" value="" />
                    <input type="hidden" id="paytm_transaction_token" name="paytm_transaction_token" value="" />
                    <input type="hidden" id="paytm_order_id" name="paytm_order_id" value="" />

                    <input type="hidden" name="paystack_reference" id="paystack_reference" value="" />

                    <input type="hidden" name="stripe_client_secret" id="stripe_client_secret" value="" />
                    <input type="hidden" name="stripe_payment_id" id="stripe_payment_id" value="" />
                    <input type="hidden" name="phonepe_transaction_id" id="phonepe_transaction_id" value="" />
                    <input type="hidden" name="phonepe_order_id" id="phonepe_order_id" value="" />


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
                <div class="col-md-4 mt-5">
                    <div class="checkout-order-wrapper">
                        <div class="checkout-title">
                            <h1><?= !empty($this->lang->line('order_summary')) ? $this->lang->line('order_summary') : 'Order Summary' ?></h1>
                        </div>
                        <div class="order-details">
                            <div class="product-checkout-wrapper">
                                <div class="product-checkout-title">
                                    <h2 class="clearfix mb-0 text-muted">
                                        <a class="#"><?= isset($cart[0]['cart_count']) && !empty($cart[0]['cart_count']) ? $cart[0]['cart_count'] : 0 ?> <?= label('item_in_cart', 'Item(s) in Cart') ?></a>
                                    </h2>
                                </div>
                                <div>
                                    <div class="product-checkout mt-4">
                                        <?php if (isset($cart) && !empty($cart)) {
                                            if ($cart[0]['type'] != 'digital_product') {
                                                $product_not_delivarable = array_column($product_not_delivarable, "product_id");
                                            }

                                            foreach ($cart as $row) {
                                                if (isset($row['qty']) && $row['qty'] != 0) {
                                                    $price = $row['special_price'] != '' && $row['special_price'] != null && $row['special_price'] > 0 && $row['special_price'] < $row['price'] ? $row['special_price'] : $row['price'];
                                        ?>
                                                    <div class="border-line">
                                                        <span class="product-wrap">
                                                            <div class="widget-image">
                                                                <a href="<?= base_url('products/details/' . $row['slug']) ?>">
                                                                    <img src="<?= !empty($row['image']) ? base_url($row['image']) : base_url(NO_IMAGE) ?>"
                                                                         alt="<?= html_escape($row['name'] ?? '') ?>"
                                                                         onerror="this.onerror=null;this.src='<?= base_url(NO_IMAGE) ?>';">
                                                                </a>
                                                            </div>

                                                            <!-- checking product deliverable or not  -->
                                                            <span class="product-info text-left">
                                                                <a href="<?= base_url("products/details/" . $row['slug']) ?>" class="product-title text-muted"><?= $row['name'] ?></a>
                                                                <?php if ($cart[0]['type'] != 'digital_product') { ?>
                                                                    <div id="p_<?= $row['product_id'] ?>" class="text-danger deliverable_status"><?= (isset($default_address) && !empty($default_address) && in_array($row['product_id'], $product_not_delivarable)) ? "Not deliverable" : "" ?></div>
                                                                <?php } ?>
                                                                <?php if (!empty($row['product_variants'])) { ?>
                                                                    <?= str_replace(',', ' | ', $row['product_variants'][0]['variant_values']) ?>
                                                                <?php } ?>
                                                                <div class="qty">
                                                                    <span class="text-muted">Qty :</span>
                                                                    <span class="text-muted"><?= $row['qty'] ?></span>
                                                                </div>
                                                                <?php if (isset($row['item_tax_percentage']) && !empty($row['item_tax_percentage'])) { ?>
                                                                    <div>
                                                                        <span class="text-muted">
                                                                         <?= !empty($row['tax_title']) ? $row['tax_title'] : 'Tax' ?>
                                                                         :
                                                                         <small class="fw-normal">
                                                                             (<?= !empty($row['is_prices_inclusive_tax']) && $row['is_prices_inclusive_tax'] == 1
                                                                                 ? 'Inclusive'
                                                                                 : 'Exclusive' ?>)
                                                                         </small>
                                                                     </span>

                                                                        
                                                                        <span class="text-muted"><?= $row['item_tax_percentage'] . '%' ?></span>
                                                                        
                                                                    </div>
                                                                <?php } ?>
                                                            </span>
                                                            <span class="item-price text-muted"><?= $settings['currency'] ?></i> <?= number_format($row['qty'] * $price, 2) ?></span>
                                                        </span>
                                                    </div>
                                        <?php }
                                            }
                                        } ?>
                                    </div>
                                </div>
                                <input type="hidden" id="sub_total" value="<?= $cart['sub_total'] ?>">
                                <div class="cart-total-price">
                                    <table class="table cart-products-table">
                                        <tbody>
                                            <tr>
                                                <td class="text-muted"><?= !empty($this->lang->line('subtotal')) ? $this->lang->line('subtotal') : 'Subtotal' ?></td>
                                                <td class="text-muted"><?= $settings['currency'] . ' <span class="sub_total">' . number_format($cart['sub_total'], 2) . '</span>' ?></td>
                                            </tr>

                                        
                                            <?php
                                            $shiprocket_settings = get_settings('shipping_method', true);
                                            if (isset($shiprocket_settings['shiprocket_shipping_method']) && $shiprocket_settings['shiprocket_shipping_method'] == 1) {
                                            ?>
                                                <tr>
                                                    <td>
                                                        <div class="row ">
                                                            <?php if ($cart[0]['type'] != 'digital_product') { ?>

                                                        </div>

                                                        <div class="row mt-2  d-none">
                                                            <div class="column delivery_charge  text-muted">
                                                                <?= !empty($this->lang->line('shipping_method')) ? $this->lang->line('shipping_method') : 'shipping method' ?>
                                                            </div>
                                                            <div class="column text-muted deliverycharge_currency">
                                                                <?= $settings['currency'] . ' ' ?><span class="shipping_method"></span>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-2 ">
                                                            <div class="column delivery_charge  text-muted">
                                                                <h3><?= !empty($this->lang->line('delivery_charge_with_cod')) ? $this->lang->line('delivery_charge_with_cod') : 'Delivery Charge with COD :' ?></h3>
                                                            </div>
                                                            <div class="column text-muted deliverycharge_currency">
                                                                <?= $settings['currency'] . ' ' ?><span class="delivery_charge_with_cod">0.00</span>
                                                                <input type="hidden" name="delivery_charge_with_cod" class="delivery_charge_with_cod" value="0" />
                                                            </div>
                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="column delivery_charge text-muted">
                                                                <h3><?= !empty($this->lang->line('delivery_charge_without_cod')) ? $this->lang->line('delivery_charge_without_cod') : 'Delivery Charge without COD :' ?></h3>
                                                            </div>
                                                            <div class="text-muted deliverycharge_currency">
                                                                <?= $settings['currency'] . ' ' ?><span class="delivery_charge_without_cod">0.00</span>
                                                                <input type="hidden" name="delivery_charge_without_cod" class="delivery_charge_without_cod" value="0" />
                                                            </div>
                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="column delivery_charge  text-muted">
                                                                <h3><?= !empty($this->lang->line('estimate_date')) ? $this->lang->line('estimate_date') : 'Estimated Delivery Date :' ?></h3>
                                                            </div>
                                                            <div class="text-muted">
                                                                <h3 class="estimate_date"></h3>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } else { ?>
                                                <!-- <tr>
                                                    <td class="text-muted delivery_charge"><?= !empty($this->lang->line('delivery_charge')) ? $this->lang->line('delivery_charge') : 'Delivery Charge' ?></td>
                                                    <td class="text-muted"><?= $settings['currency'] . ' ' ?><span class="delivery-charge"></span></td>
                                                </tr> -->
                                                <tr>
                                                    <td>
                                                        <div class="row ">


                                                        </div>
                                                        <div class="row mt-2 ">
                                                            <div class="column delivery_charge  text-muted">
                                                                <h3><?= !empty($this->lang->line('delivery_charge_with_cod')) ? str_replace('\\', '', $this->lang->line('delivery_charge_with_cod')) : 'Delivery Charge with COD :' ?></h3>
                                                            </div>
                                                            <div class="column text-muted deliverycharge_currency">
                                                                <?= $settings['currency'] . ' ' ?><span class="delivery_charge_with_cod">0.00</span>
                                                                <input type="hidden" name="delivery_charge_with_cod" class="delivery_charge_with_cod" value="0" />

                                                            </div>
                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="column delivery_charge text-muted">
                                                                <h3><?= !empty($this->lang->line('delivery_charge_without_cod')) ? str_replace('\\', '', $this->lang->line('delivery_charge_without_cod')) : 'Delivery Charge without COD :' ?></h3>
                                                            </div>
                                                            <div class="text-muted deliverycharge_currency">
                                                                <?= $settings['currency'] . ' ' ?><span class="delivery_charge_without_cod">0.00</span>
                                                                <input type="hidden" name="delivery_charge_without_cod" class="delivery_charge_without_cod" value="0" />
                                                            </div>
                                                        </div>


                                                    </td>
                                                </tr>
                                            <?php } ?>
                                           
                                                
                                                
                                               <?php if (!empty($custom_charges)) : ?>
    <?php 
        $is_digital = ($cart[0]['type'] == 'digital_product');
        $default_delivery_type = 'doorstep'; // Default to doorstep on page load
        
        foreach ($custom_charges as $c) : 
            $show_charge = false;
            
            if ($is_digital && isset($c['apply_digital']) && $c['apply_digital'] == 1) {
                $show_charge = true;
            } elseif (!$is_digital) {
                if (isset($c['apply_doorstep']) && $c['apply_doorstep'] == 1) {
                    $show_charge = true;
                }
            }
            
            if (!$show_charge) continue;
    ?>
        <tr class="custom-charge-row" data-apply-doorstep="<?= isset($c['apply_doorstep']) ? $c['apply_doorstep'] : 0 ?>" data-apply-pickup="<?= isset($c['apply_pickup']) ? $c['apply_pickup'] : 0 ?>" data-apply-digital="<?= isset($c['apply_digital']) ? $c['apply_digital'] : 0 ?>" data-apply-pos="<?= isset($c['apply_pos']) ? $c['apply_pos'] : 0 ?>" data-apply-is_refundable="<?= isset($c['is_refundable']) ? $c['is_refundable'] : 0 ?>" data-amount="<?= $c['amount'] ?>">
            <td class="text-muted">
                <?php
                    $charge_name = isset($c['name']) ? $c['name'] : 'Custom Charge';
                    // remove underscore only
                    $charge_name = str_replace('_', '  ', $charge_name);
                    echo htmlspecialchars($charge_name);
                ?>
            </td>
            <td class="text-muted text-end">
                <?= htmlspecialchars($settings['currency']) . ' ' . number_format((float)$c['amount'], 2) ?>
            </td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>


                                            <tr>
                                                <td class="text-muted pl-0"><?= !empty($this->lang->line('wallet')) ? $this->lang->line('wallet') : 'Wallet' ?></td>
                                                <td class="text-muted"><?= $settings['currency'] ?> <span class="wallet_used">0.00<span></td>
                                            </tr>
                                            <tr id="promocode_div" class="d-none">
                                                <td class="text-muted"><?= !empty($this->lang->line('promocode')) ? $this->lang->line('promocode') : 'Promocode' ?> <span id="promocode"></span></td>
                                                <td class="text-muted"> <i><?= $settings['currency'] ?></i> <span id="promocode_amount"></span></td>
                                            </tr>
                                            <?php if (isset($bulk_discount) && $bulk_discount > 0) { ?>
                                            <tr id="bulk_discount_div">
                                                <td class="text-muted"><?= !empty($this->lang->line('bulk_discount')) ? $this->lang->line('bulk_discount') : 'Bulk Discount' ?></td>
                                                <td class="text-success">-<?= $settings['currency'] ?> <span id="bulk_discount_amount"><?= number_format($bulk_discount, 2) ?></span></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>

                                            <tr class="total-price pl-0">
                                                <?php
                                                if ($cart[0]['type'] != 'digital_product') { ?>
                                                    <td class="pl-0"><?= !empty($this->lang->line('total_with_cod')) ? $this->lang->line('total_with_cod') : 'Total (with COD)' ?></td>
                                                    <td><?= $settings['currency'] ?> <span id="final_total" value=""><?= number_format($cart['sub_total'], 2) ?></span></td>
                                                <?php } else { ?>
                                                    <td class="pl-0"><?= !empty($this->lang->line('total_with_cod')) ? $this->lang->line('total_with_cod') : 'Total ' ?></td>
                                                    <td><?= $settings['currency'] ?> <span id="final_total" value=""><?= number_format($cart['sub_total'], 2) ?></span></td>
                                                <?php  } ?>
                                            </tr>
                                            </tr>
                                            <?php if (isset($message) && !empty($message)) { ?>
                                                <tr>
                                                    <td colspan="2" class="text-danger"><?= !empty($this->lang->line('error_message')) ? $this->lang->line('message') : $message ?></td>
                                                    <td></td>
                                                </tr>
                                            <?php } ?>

                                        </tfoot>
                                    </table>
                                </div>
                                <div class="input-group">
                                    <a href="#" data-izimodal-open=".promo_code_modal" class="mb-4 pl-0"><?= label('see_all_offers', 'See All Offers') ?></i></a>
                                </div>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="<?= label('promo_code', 'Promocodes') ?>" id="promocode_input">
                                    <div class="input-group-append">
                                        <button type="button" class="button button-primary" id="redeem_btn"><?= !empty($this->lang->line('redeem')) ? $this->lang->line('redeem') : 'Redeem' ?></button>
                                        <button type="button" class="button button-danger d-none" id="clear_promo_btn"><?= !empty($this->lang->line('clear')) ? $this->lang->line('clear') : 'Clear' ?></button>
                                    </div>
                                </div>
                                <?php $is_disabled = false;
                                foreach ($product_not_delivarable as $p_id) {
                                    if (!empty($p_id['product_id'])) {
                                        $is_disabled = true;
                                        continue;
                                    }
                                } ?>
                                <?php if (isset($message) && !empty($message)) { ?>
                                    <button disabled class="block" id="place_order_btn" type="submit" <?= ($is_disabled) ? "disabled" : ""; ?>><?= !empty($this->lang->line('place_order')) ? $this->lang->line('place_order') : 'Place Order' ?></button>
                                <?php } else { ?>
                                    <button class="block" id="place_order_btn" type="submit" <?= ($is_disabled) ? "disabled" : ""; ?>><?= !empty($this->lang->line('place_order')) ? $this->lang->line('place_order') : 'Place Order' ?></button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>

<form action="<?= base_url('payment/paypal') ?>" id="paypal_form" method="POST">
    <input type="hidden" id="csrf_token" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="order_id" id="paypal_order_id" value="" />
</form>
<input type="hidden" name="stripe_key_id" id="stripe_key_id" value="<?= $payment_methods['stripe_publishable_key'] ?>" />
<input type="hidden" name="instamojo_payment_method" id="instamojo_payment_method" value="<?= (isset($payment_methods['instamojo_payment_method']) && $payment_methods['instamojo_payment_method'] == 1) ? 1 : 0 ?>" />
<input type="hidden" name="razorpay_key_id" id="razorpay_key_id" value="<?= $payment_methods['razorpay_key_id'] ?>" />
<input type="hidden" name="paystack_key_id" id="paystack_key_id" value="<?= $payment_methods['paystack_key_id'] ?>" />
<input type="hidden" id="delivery_starts_from" value="<?= (isset($time_slot_config['delivery_starts_from']) ? $time_slot_config['delivery_starts_from'] : '') ?>">
<input type="hidden" id="delivery_ends_in" value="<?= (isset($time_slot_config['allowed_days']) ? $time_slot_config['allowed_days'] : '') ?>">
<div id="modal-custom" class="address-modal" data-iziModal-group="grupo1">
    <button data-iziModal-close class="icon-close">x</button>
    <section id="address_form">
        <div class="h4"><?= !empty($this->lang->line('shipping_address')) ? $this->lang->line('shipping_address') : 'Shipping Address' ?></div>
        <ul id="address-list"></ul>
        <div class="col-12 d-flex justify-content-start align-items-center mt-2">
            <button type="button" class="btn btn-outline-primary btn-sm" id="open-create-address-modal" title="Create Address">
                <i class="fa fa-plus"></i>
            </button>
        </div>
        <footer class="mt-4">
            <button data-iziModal-close><?= !empty($this->lang->line('cancel')) ? $this->lang->line('cancel') : 'Cancel' ?></button>
            <button class="submit" id="select_address"><?= !empty($this->lang->line('save')) ? $this->lang->line('save') : 'Save' ?></button>
        </footer>
    </section>
</div>
<div id="checkout-create-address-modal" class="checkout-create-address-modal" data-iziModal-group="grupo1">
    <section>
        <div class="h4 mb-3"><?= !empty($this->lang->line('create_a_new_address')) ? $this->lang->line('create_a_new_address') : 'Create a New Address' ?></div>
        <form id="checkout-create-address-form">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="checkout_address_name"><?= !empty($this->lang->line('name')) ? $this->lang->line('name') : 'Name' ?> <sup class="text-danger">*</sup></label>
                    <input type="text" class="form-control" id="checkout_address_name" name="name" required>
                </div>
                <div class="col-md-6 form-group">
                    <label for="checkout_mobile_number"><?= !empty($this->lang->line('contact_number')) ? $this->lang->line('contact_number') : 'Contact Number' ?> <sup class="text-danger">*</sup></label>
                    <input type="text" class="form-control" id="checkout_mobile_number" name="mobile" required>
                </div>
                <div class="col-12 form-group">
                    <label for="checkout_address"><?= !empty($this->lang->line('address')) ? $this->lang->line('address') : 'Address' ?> <sup class="text-danger">*</sup></label>
                    <textarea class="form-control" id="checkout_address" name="address" rows="3" required></textarea>
                </div>
                <div class="col-md-6 form-group">
                    <label for="checkout_country"><?= !empty($this->lang->line('country')) ? $this->lang->line('country') : 'Country' ?> <sup class="text-danger">*</sup></label>
                    <input type="text" class="form-control" id="checkout_country" name="country" value="United States" readonly required>
                </div>
                <div class="col-md-6 form-group">
                    <label for="checkout_state"><?= !empty($this->lang->line('state')) ? $this->lang->line('state') : 'State' ?> <sup class="text-danger">*</sup></label>
                    <input type="text" class="form-control" id="checkout_state" name="state" required>
                </div>
                <div class="col-md-6 form-group">
                    <label for="checkout_pincode"><?= !empty($this->lang->line('zipcode')) ? $this->lang->line('zipcode') : 'ZIP Code' ?> <sup class="text-danger">*</sup></label>
                    <div class="position-relative zipcode-autocomplete-wrap">
                        <input type="text" class="form-control" id="checkout_pincode" name="pincode"
                            inputmode="numeric" autocomplete="off" maxlength="10"
                            placeholder="e.g. 10001 or 10001-1234" pattern="^\d{5}(-\d{4})?$" required>
                        <div id="checkout-zipcode-suggestions" class="list-group zipcode-suggestions-list" style="display:none;"></div>
                    </div>
                </div>
                <div class="col-md-6 form-group">
                    <label for="checkout_city"><?= !empty($this->lang->line('city')) ? $this->lang->line('city') : 'City' ?> <sup class="text-danger">*</sup></label>
                    <div class="position-relative zipcode-autocomplete-wrap">
                        <input type="text" class="form-control" id="checkout_city" name="city_name" autocomplete="off" required
                            placeholder="<?= !empty($this->lang->line('city')) ? $this->lang->line('city') : 'City' ?>">
                        <div id="checkout-city-suggestions" class="list-group zipcode-suggestions-list" style="display:none;"></div>
                    </div>
                    <input type="hidden" name="city_id" id="checkout_city_id" value="" />
                </div>
                <div class="col-md-6 form-group">
                    <label for="checkout_alternate_mobile"><?= !empty($this->lang->line('alternate_mobile')) ? $this->lang->line('alternate_mobile') : 'Alternate Mobile' ?></label>
                    <input type="text" class="form-control" id="checkout_alternate_mobile" name="alternate_mobile">
                </div>
                <div class="col-12 form-group">
                    <label class="d-block mb-2"><?= !empty($this->lang->line('type')) ? $this->lang->line('type') : 'Type' ?></label>
                    <div class="address-type-options">
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" name="type" id="checkout_type_home" value="home" checked>
                            <label class="form-check-label" for="checkout_type_home"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" name="type" id="checkout_type_office" value="office">
                            <label class="form-check-label" for="checkout_type_office"><?= !empty($this->lang->line('office')) ? $this->lang->line('office') : 'Office' ?></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" name="type" id="checkout_type_other" value="other">
                            <label class="form-check-label" for="checkout_type_other"><?= !empty($this->lang->line('other')) ? $this->lang->line('other') : 'Other' ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="mt-4">
                <button data-iziModal-close><?= !empty($this->lang->line('cancel')) ? $this->lang->line('cancel') : 'Cancel' ?></button>
                <button class="submit" id="checkout_save_address_btn"><?= !empty($this->lang->line('save')) ? $this->lang->line('save') : 'Save' ?></button>
            </footer>
        </form>
    </section>
</div>
<div id="modal-custom" class="promo_code_modal" data-iziModal-group="grupo1">
    <button data-iziModal-close class="icon-close">x</button>
    <section id="promo_code_form">
        <div class="h4"><?= !empty($this->lang->line('promocodes')) ? $this->lang->line('promocodes') : 'Promocodes' ?></div>
        <ul id="promocode-list"></ul>
    </section>
</div>
<?php if (isset($payment_methods['paytm_payment_method']) && $payment_methods['paytm_payment_method'] == 1) {
    $url = ($payment_methods['paytm_payment_mode'] == "production") ? "https://securegw.paytm.in/" : "https://securegw-stage.paytm.in/";
?>
    <script type="application/javascript" src="<?= $url ?>merchantpgpui/checkoutjs/merchants/<?= $payment_methods['paytm_merchant_id'] ?>.js"></script>
<?php } ?>

<!-- Load SweetAlert2 before checkout.js -->
<script src="<?= THEME_ASSETS_URL . 'js/sweetalert2.min.js' ?>"></script>

<script src="https://checkout.flutterwave.com/v3.js"></script>
<script src="https://js.stripe.com/v3/"></script>
<script src="https://demo.myfatoorah.com/cardview/v2/session.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script src="https://js.instamojo.com/v1/checkout.js"></script>
<?php
$midtrans_url = $midtrans_client_key = "";
if (isset($payment_methods['midtrans_payment_mode'])) {
    $midtrans_url = (isset($payment_methods['midtrans_payment_mode']) && $payment_methods['midtrans_payment_mode'] == "sandbox") ? "https://app.sandbox.midtrans.com/snap/snap.js" : "https://app.midtrans.com/snap/snap.js";
    $midtrans_client_key = $payment_methods['midtrans_client_key'];
}
?>
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
<?php if (!empty($midtrans_url)) { ?>
<script type="text/javascript" src="<?= $midtrans_url ?>" data-client-key="<?= $midtrans_client_key ?>"></script>
<?php } ?>
<script src="<?= THEME_ASSETS_URL . '/js/checkout.js' ?>"></script>
