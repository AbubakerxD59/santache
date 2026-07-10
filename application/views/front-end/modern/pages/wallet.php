<main>
    <section class="container py-5">
        <div class="row">
            <div class="col-md-3 myaccount-navigation py-3">
                <?php $this->load->view('front-end/' . THEME . '/pages/my-account-sidebar') ?>
            </div>
            <div class="col-md-9 padding-16-30 home_faq">
                <h1 class="section-title mb-2"><?= label('wallet', 'Wallet') ?></h1>
                <?php $payment_methods = get_settings('payment_method', true); ?>
                <div class="gap-3">
                    <div class="wallet-card">
                        <p class="wallet-text m-0"><?= label('total_balance', 'Total Balance') ?></p>
                        <p class="wallet-amount m-0"><?= $settings['currency'] . ' ' . number_format($user->balance, 2)  ?></p>
                        <p class="wallet-currency m-0"><?= $settings['supported_locals'] ?></p>
                        <p class="wallet-user-name m-0"><?= $user->username ?></p>
                    </div>
                    <div class="d-flex align-items-end gap-3 mt-md-0 mt-3 pt-1">
                        <button type="button" class="transaction-btn add-money-btn" data-bs-toggle="modal" data-bs-target="#add-money"><?= label('add_money', 'Add Money') ?></button>
                        <button type="button" class="transaction-btn Withdrawal-btn" data-bs-toggle="modal" data-bs-target="#withdrawal"><?= label('withdrawal', 'Withdrawal') ?></button>
                    </div>
                </div>                <div class="mt-3">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true"><?= label('wallet_transaction', 'Wallet Transaction') ?></button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false"><?= label('wallet_requests', 'Wallet Requests') ?></button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                            <table class='table-striped price' data-toggle="table" data-url="<?= base_url('my-account/get-wallet-transactions') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-query-params="customer_wallet_query_params">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true"><?= label('id', 'ID') ?></th>
                                        <th data-field="name" data-sortable="false"><?= label('user_name', 'User Name') ?></th>
                                        <th data-field="type" data-sortable="false"><?= label('type', 'Type') ?></th>
                                        <th data-field="amount" data-sortable="false"><?= label('amount', 'Amount') ?> <?= $settings['currency'] ?></th>
                                        <th data-field="status" data-sortable="false"><?= label('status', 'Status') ?></th>
                                        <th data-field="message" data-sortable="false"><?= label('message', 'Message') ?></th>
                                        <th data-field="date" data-sortable="false"><?= label('date', 'Date') ?></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                            <table class='table-striped price' data-toggle="table" data-url="<?= base_url('my-account/get_withdrawal_request') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel","csv"]' data-export-options='{"fileName": "order-item-list","ignoreColumn": ["state"] }' data-query-params="customer_withdrawal_query_params">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true"><?= label('id', 'ID') ?></th>
                                        <th data-field="name" data-sortable="false"><?= label('user_name', 'User Name') ?></th>
                                        <th data-field="payment_address" data-sortable="false"><?= label('payment_address', 'Payment Address') ?></th>
                                        <th data-field="amount_requested" data-sortable="false"><?= label('amount', 'Amount') ?> <?= $settings['currency'] ?></th>
                                        <th data-field="status" data-sortable="false"><?= label('status', 'Status') ?></th>
                                        <th data-field="date_created" data-sortable="false"><?= label('date', 'Date') ?></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <div class="modal fade" id="add-money" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"><?= label('add_money', 'Add money') ?></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="ship-details-wrapper mt-3 payment-methods">
                        <form class="form-horizontal form-submit-event" method="POST" id="wallet_form" enctype="multipart/form-data">
                            <?php
                            $CUR_USERID = $_SESSION['user_id'];
                            $order_id = 'wallet-refill-user' . "-" . $CUR_USERID . "-" . time() . "-" . rand("900", "999");


                            $payment_methods = get_settings('payment_method', true);
                            $name = fetch_details('users', ['id' => $_SESSION['user_id']]);


                            ?>

                            <input type="hidden" name="app_name" id="app_name" value="<?= $settings['app_name'] ?>" />
                            <input type="hidden" id="flutterwave_currency" value="<?= $payment_methods['flutterwave_currency_code'] ?>" />
                            <input type="hidden" id="user_email" value="<?= $_SESSION['email']  ?>" />

                            <input type="hidden" id="username" value="<?= $username['username'] ?>" />
                            <input type="hidden" id="user_contact" value="<?= $username['mobile'] ?>" />
                            <input type="hidden" name="logo" id="logo" value="<?= base_url(get_settings('web_logo')) ?>" />


                            <input type="hidden" name="order_id" id="order_id" value="<?= $order_id ?>">
                            <input type="number" name="amount" id="amount" min="1" required class="col-md-11 ml-4 form-control" placeholder="Enter amount">


                            <input type="text" name="message" class="col-md-11 ml-4 mt-3 ticket_msg form-control " id="message_input" placeholder="Type Message ...">
                            <br>

                            <div class="align-item-center ship-title-details justify-content-between d-flex ml-3">
                                <h5><?= !empty($this->lang->line('payment_method')) ? $this->lang->line('payment_method') : 'Payment Method' ?></h5>
                            </div>
                            <div class="shipped-details mt-3 col-md-6">
                                <div class="form-check">
                                    <?php if (isset($payment_methods['phonepe_payment_method']) && $payment_methods['phonepe_payment_method'] == 1) { ?>
                                        <label class="form-check-label d-flex align-items-center gap-3" for="phonepe">
                                            <input id="phonepe" class="form-check-input" name="payment_method" type="radio" value="phonepe" required>
                                            <div class="payment-type-img-box">
                                                <img src="<?= base_url('assets/front_end/modern/image/pictures/phonepay-logo.png') ?>" class="payment-gateway-images" alt="instamojo">
                                            </div>
                                        </label>
                                    <?php } ?>
                                    <?php if (isset($payment_methods['paypal_payment_method']) && $payment_methods['paypal_payment_method'] == 1) { ?>
                                        <label for="paypal" class="form-check-label d-flex align-items-center gap-3">
                                            <input class="form-check-input" id="paypal" name="payment_method" type="radio" value="Paypal" required>
                                            <div class="payment-type-img-box">
                                                <img src="<?= base_url('assets/front_end/modern/image/pictures/paypal-logo.png') ?>" class="" alt="Paypal">
                                            </div>
                                        </label>
                                    <?php } ?>
                                    <?php if (isset($payment_methods['razorpay_payment_method']) && $payment_methods['razorpay_payment_method'] == 1) { ?>
                                        <label class="form-check-label d-flex align-items-center gap-3" for="razorpay">
                                            <input class="form-check-input" id="razorpay" name="payment_method" type="radio" value="Razorpay" required>
                                            <div class="payment-type-img-box">
                                                <img src="<?= base_url('assets/front_end/modern/image/pictures/Razorpay_logo.png') ?>" class="payment-gateway-images" alt="Razorpay">
                                            </div>
                                        </label>
                                    <?php } ?>
                                    <?php if (isset($payment_methods['paystack_payment_method']) && $payment_methods['paystack_payment_method'] == 1) { ?>
                                        <label class="form-check-label d-flex align-items-center gap-3" for="paystack">
                                            <input class="form-check-input" id="paystack" name="payment_method" type="radio" value="Paystack" required>
                                            <div class="payment-type-img-box">
                                                <img src="<?= base_url('assets/front_end/modern/image/pictures/Paystack_Logo.png') ?>" class="payment-gateway-images" alt="Paystack">
                                            </div>
                                        </label>
                                    <?php } ?>
                                    <?php if (isset($payment_methods['payumoney_payment_method']) && $payment_methods['payumoney_payment_method'] == 1) { ?>
                                        <label class="form-check-label d-flex align-items-center gap-3" for="payumoney">
                                            <input class="form-check-input" id="payumoney" name="payment_method" type="radio" value="Payumoney" required>
                                            <div class="payment-type-img-box">
                                                <img src="<?= base_url('assets/front_end/modern/image/pictures/PayUmoney_Logo.jpg') ?>" class="payment-gateway-images" alt="Payumoney">
                                            </div>
                                        </label>
                                    <?php } ?>
                                    <?php if (isset($payment_methods['flutterwave_payment_method']) && $payment_methods['flutterwave_payment_method'] == 1) { ?>
                                        <label class="form-check-label d-flex align-items-center gap-3" for="flutterwave">
                                            <input class="form-check-input" id="flutterwave" name="payment_method" type="radio" value="Flutterwave" required>
                                            <div class="payment-type-img-box">
                                                <img src="<?= base_url('assets/front_end/modern/image/pictures/flutterwave-1.svg') ?>" class="payment-gateway-images" alt="Flutterwave">
                                            </div>
                                        </label>
                                        <?php foreach ($name as $username) { ?>
                                            <input type="hidden" name="flutterwave_public_key" id="flutterwave_public_key" value="<?= $payment_methods['flutterwave_public_key'] ?>" />
                                            <input type="hidden" name="flutterwave_transaction_id" id="flutterwave_transaction_id" value="" />
                                            <input type="hidden" name="flutterwave_transaction_ref" id="flutterwave_transaction_ref" value="" />
                                            <input type="hidden" name="promo_set" id="promo_set" value="" />
                                        <?php  } ?>
                                    <?php } ?>
                                    <?php if (isset($payment_methods['paytm_payment_method']) && $payment_methods['paytm_payment_method'] == 1) { ?>
                                        <label class="form-check-label d-flex align-items-center gap-3" for="paytm">
                                            <input class="form-check-input" id="paytm" name="payment_method" type="radio" value="Paytm" required>
                                            <div class="payment-type-img-box">
                                                <img src="<?= base_url('assets/front_end/modern/image/pictures/paytm_logo.png') ?>" class="payment-gateway-images" alt="Paytm">
                                            </div>
                                        </label>
                                    <?php } ?>
                                    <?php if (isset($payment_methods['stripe_payment_method']) && $payment_methods['stripe_payment_method'] == 1) { ?>
                                        <label class="form-check-label d-flex align-items-center gap-3" for="stripe">
                                            <input class="form-check-input" id="stripe" name="payment_method" type="radio" value="Stripe" required>
                                            <div class="payment-type-img-box">
                                                <img src="<?= base_url('assets/front_end/modern/fontawesome-free-6.4.0-web/svgs/regular/credit-card.svg') ?>" class="payment-gateway-images" alt="Debit/Credit Card">
                                            </div>
                                        </label>
                                    <?php } ?>

                                    <?php if (isset($payment_methods['midtrans_payment_method']) && $payment_methods['midtrans_payment_method'] == 1) { ?>
                                        <label class="form-check-label d-flex align-items-center gap-3" for="midtrans">
                                            <input class="form-check-input" id="midtrans" name="payment_method" type="radio" value="Midtrans" required>
                                            <div class="payment-type-img-box">
                                                <img src="<?= base_url('assets/front_end/modern/image/pictures/midtrans-logo.png') ?>" class="payment-gateway-images" alt="Midtrans">
                                            </div>
                                        </label>
                                    <?php } ?>


                                    <?php if (isset($payment_methods['myfaoorah_payment_method']) && $payment_methods['myfaoorah_payment_method'] == 1) { ?>
                                        <label class="form-check-label d-flex align-items-center gap-3" for="my_fatoorah">
                                            <input class="form-check-input" id="my_fatoorah" name="payment_method" type="radio" value="my_fatoorah" required>
                                            <div class="payment-type-img-box">
                                                <img src="<?= base_url('assets/front_end/modern/image/pictures/my-fatoorah-paddedV2.png') ?>" class="payment-gateway-images" alt="myfatoorah">
                                            </div>
                                        </label>
                                    <?php } ?>
                                    <?php if (isset($payment_methods['instamojo_payment_method']) && $payment_methods['instamojo_payment_method'] == 1) { ?>
                                        <label class="form-check-label d-flex align-items-center gap-3" for="instamojo">
                                            <input id="instamojo" class="form-check-input" name="payment_method" type="radio" value="instamojo" required>
                                            <div class="payment-type-img-box">
                                                <img src="<?= base_url('assets/front_end/modern/image/pictures/logo_instamojo.webp') ?>" class="payment-gateway-images" alt="instamojo">
                                            </div>
                                        </label>
                                    <?php } ?>
                                </div>
                            </div>
                            <div id="stripe_div">
                                <div id="stripe-card-element">
                                    <!--Stripe.js injects the Card Element-->
                                </div>
                                <p id="card-error" role="alert"></p>
                                <p class="result-message hidden"></p>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="wallet_refill" value="Save"><?= labels('Refill', 'Refill') ?></button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="withdrawal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"><?= label('withdraw_money', 'Withdraw money') ?></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="number" name="amount" id="withdrawal_amount" min="1" required class="col-md-11 ml-4 form-control" placeholder="withdrawal amount">
                    <input type="hidden" id="user_id" name='user_id' value="<?= $_SESSION['user_id']  ?>" />
                    <textarea type="text" name="payment_address" id="payment_address" class="col-md-11 ml-4 mt-3 ticket_msg form-control " id="message_input" placeholder="Enter bank details..." cols="30" rows="10"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="withdraw_amount" value="Save"><?= labels('Withdraw', 'Withdraw') ?></button>
                </div>
            </div>
        </div>
    </div>
</main>

<form action="<?= base_url('payment/paypal_wallet') ?>" id="paypal_form" method="POST">
    <input type="hidden" id="csrf_token" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="order_id" id="paypal_order_id" value="" />
    <input type="hidden" name="amount" id="paypal_amount" value="" />
</form>



<input type="hidden" name="razorpay_key_id" id="razorpay_key_id" value="<?= $payment_methods['razorpay_key_id'] ?>" />

<input type="hidden" name="paystack_reference" id="paystack_reference" value="" />
<input type="hidden" name="paystack_key_id" id="paystack_key_id" value="<?= $payment_methods['paystack_key_id'] ?>" />

<input type="hidden" id="paytm_transaction_token" name="paytm_transaction_token" value="" />
<input type="hidden" id="paytm_order_id" name="paytm_order_id" value="" />

<input type="hidden" name="my_fatoorah_order_id" id="my_fatoorah_order_id" value="" />
<input type="hidden" name="my_fatoorah_session_id" id="my_fatoorah_session_id" value="" />
<input type="hidden" name="my_fatoorah_payment_id" id="my_fatoorah_payment_id" value="" />

<input type="hidden" name="stripe_client_secret" id="stripe_client_secret" value="" />
<input type="hidden" name="stripe_payment_id" id="stripe_payment_id" value="" />
<input type="hidden" name="stripe_key_id" id="stripe_key_id" value="<?= $payment_methods['stripe_publishable_key'] ?>" />

<?php if (isset($payment_methods['paytm_payment_method']) && $payment_methods['paytm_payment_method'] == 1) {
    $url = ($payment_methods['paytm_payment_mode'] == "production") ? "https://securegw.paytm.in/" : "https://securegw-stage.paytm.in/";
?>
    <script type="application/javascript" src="<?= $url ?>merchantpgpui/checkoutjs/merchants/<?= $payment_methods['paytm_merchant_id'] ?>.js"></script>
<?php } ?>
<script src="https://checkout.flutterwave.com/v3.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script src="https://js.stripe.com/v3/"></script>
<script src="https://js.instamojo.com/v1/checkout.js"></script>

<?php
$midtrans_url = $midtrans_client_key = "";
if (isset($payment_methods['midtrans_payment_mode'])) {
    $midtrans_url = (isset($payment_methods['midtrans_payment_mode']) && $payment_methods['midtrans_payment_mode'] == "sandbox") ? "https://app.sandbox.midtrans.com/snap/snap.js" : "https://app.midtrans.com/snap/snap.js";
    $midtrans_client_key = $payment_methods['midtrans_client_key'];
}
?>
<script type="text/javascript" src="<?= $midtrans_url ?>" data-client-key="<?= $midtrans_client_key ?>"></script>
<script src="<?= base_url('assets/front_end/modern/js/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/front_end/modern/js/wallet.js') ?>"></script>
<script>
    function customer_wallet_query_params(p) {
        return {
            transaction_type: 'wallet',
            limit: p.limit,
            sort: p.sort,
            order: p.order,
            offset: p.offset,
            search: p.search
        }
    }

    function customer_withdrawal_query_params(p) {
        return {
            limit: p.limit,
            sort: p.sort,
            order: p.order,
            offset: p.offset,
            search: p.search
        }
    }
</script>