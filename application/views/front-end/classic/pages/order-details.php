<!-- Demo header-->
<section class="header settings-tab">
    <div class="container py-4">
        <div class="row">
            <div class="col-md-12 orders-section settings-tab-content">
                <div class="mb-4  border-0">
                    <div class="card-header bg-white">
                        <div class="row justify-content-between">
                            <div class="col">
                                <p class="text-muted">
                                    <?= !empty($this->lang->line('order_id')) ? $this->lang->line('order_id') : 'Order ID' ?><span
                                        class="font-weight-bold text-dark"> : <?= $order['id'] ?></span>
                                </p>
                                <p class="text-muted">
                                    <?= !empty($this->lang->line('place_on')) ? $this->lang->line('place_on') : 'Place On' ?><span
                                        class="font-weight-bold text-dark"> : <?= $order['date_added'] ?></span>
                                </p>
                            </div>

                            <div class="flex-col my-auto">
                                <h6 class="ml-auto mr-3">
                                    <a target="_blank"
                                        href="<?= base_url('my-account/order-invoice/' . $order['id']) ?>"
                                        class='button button-primary-outline'><?= !empty($this->lang->line('invoice')) ? $this->lang->line('invoice') : 'Invoice' ?></a>
                                    <a href="<?= base_url('my-account/orders/') ?>"
                                        class='button button-danger-outline'><?= !empty($this->lang->line('back_to_list')) ? $this->lang->line('back_to_list') : 'Back to List' ?></a>
                                </h6>
                            </div>
                        </div>
                        <br>
                        <?php if ($order['payment_method'] == "Bank Transfer") { ?>
                            <div class="row">
                                <form class="form-horizontal " id="send_bank_receipt_form"
                                    action="<?= base_url('cart/send-bank-receipt'); ?>" method="POST"
                                    enctype="multipart/form-data">
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <div class="form-group ">
                                        <label for="receipt"> <strong>
                                                <?= !empty($this->lang->line('bank_payment_receipt')) ? $this->lang->line('bank_payment_receipt') : 'Bank Payment Receipt' ?></strong>
                                        </label>
                                        <input type="file" class="form-control" name="attachments[]" id="receipt"
                                            multiple />
                                    </div>
                                    <div class="form-group">
                                        <button type="reset"
                                            class="button button-warning-outline"><?= !empty($this->lang->line('reset')) ? $this->lang->line('reset') : 'Reset' ?></button>
                                        <button type="submit" class="button button-success-outline"
                                            id="submit_btn"><?= !empty($this->lang->line('send')) ? $this->lang->line('send') : 'Send' ?></button>
                                    </div>
                                </form>

                            </div>
                        <?php } ?>
                        <div class="row">
                            <?php if (!empty($bank_transfer)) { ?>
                                <div class="col-md-6">
                                    <?php $i = 1;
                                    foreach ($bank_transfer as $row1) { ?>
                                        <small>[<a href="<?= base_url() . $row1['attachments'] ?>"
                                                target="_blank"><?= !empty($this->lang->line('attachment')) ? $this->lang->line('attachment') : 'Attachment' ?>
                                                <?= $i ?> </a>]</small>
                                        <?php $i++;
                                    }
                                    if ($bank_transfer[0]['status'] == 0) { ?>
                                        <label
                                            class="badge badge-warning"><?= !empty($this->lang->line('pending')) ? $this->lang->line('pending') : 'Pending' ?></label>
                                    <?php } else if ($bank_transfer[0]['status'] == 1) { ?>
                                            <label
                                                class="badge badge-danger"><?= !empty($this->lang->line('rejected')) ? $this->lang->line('rejected') : 'Rejected' ?></label>
                                    <?php } else if ($bank_transfer[0]['status'] == 2) { ?>
                                                <label
                                                    class="badge badge-primary"><?= !empty($this->lang->line('accepted')) ? $this->lang->line('accepted') : 'Accepted' ?></label>
                                    <?php } else { ?>
                                                <label
                                                    class="badge badge-danger"><?= !empty($this->lang->line('invalid_value')) ? $this->lang->line('invalid_value') : 'Invalid Value' ?></label>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row order-detail-box my-3">
                        <div class="col-lg-6">
                            <div>
                               
                                <h5 class="h5"><?= label('order_tracking', 'Order Tracking') ?></h5>
                                <?php if (!empty($order['url'])) { ?>
                                    <p class="text-muted"><?= label('order_tracking', 'Tracking URL') ?>
                                        <span class="fw-bold text-dark"> :
                                            <a href="<?= $order['url'] ?>" target="_blank"><?= $order['url'] ?></a>
                                        </span>
                                    </p>
                                    <p class="text-muted"><?= label('tracking_id', 'Tracking ID') ?>
                                        <span class="fw-bold text-dark"> :
                                            <?= $order['tracking_id'] ?>
                                        </span>
                                    </p>
                                    <p class="text-muted"><?= label('courier_agency', 'Courier Agency') ?>
                                        <span class="fw-bold text-dark"> :
                                            <?= $order['courier_agency'] ?>
                                        </span>
                                    </p>
                                    <?php if (!empty($order['usps_tracking_status'])) { ?>
                                    <p class="text-muted">USPS Status
                                        <span class="fw-bold text-dark"> :
                                            <?= htmlspecialchars($order['usps_tracking_status']) ?>
                                        </span>
                                    </p>
                                    <?php } ?>
                                <?php } else { ?>
                                    Tracking information not available
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                        <?php foreach ($order['order_items'] as $key => $item) { ?>
                            <div class="media flex-column flex-sm-row">
                                <div class="media-body ">
                                    <h5 class="bold"><?= ($key + 1) . '. ' . $item['name'] ?></h5>
                                    <p class="text-muted">
                                        <?= !empty($this->lang->line('quantity')) ? $this->lang->line('quantity') : 'Quantity' ?>
                                        : <?= $item['quantity'] ?>
                                    </p>
                                    <?php if ($item['otp'] != 0) { ?>
                                        <p class="text-muted">
                                            <?= !empty($this->lang->line('otp')) ? $this->lang->line('otp') : 'OTP' ?> <span
                                                class="font-weight-bold text-dark"> : <?= $item['otp'] ?></span>
                                        </p>
                                    <?php } ?>
                                    <?php if (isset($item['courier_agency']) && !empty($item['courier_agency'])) { ?>
                                        <p> <span class="text-muted">
                                                <?= !empty($this->lang->line('courier_agency')) ? $this->lang->line('courier_agency') : 'Courier Agency' ?>
                                                : </span><a href="<?= $item['url'] ?>"
                                                title="click here to trace the order"><?= $item['courier_agency'] ?></a> </p>
                                        <p class="text-muted" data-toggle="tooltip" data-placement="top"
                                            title="Copy this Tracking ID and trace your order with Courier Agency.">
                                            <?= !empty($this->lang->line('tracking_id')) ? $this->lang->line('tracking_id') : 'Tracking ID' ?>
                                            <span class="font-weight-bold text-dark"> : <?= $item['tracking_id'] ?></span>
                                        </p>
                                    <?php } ?>
                                    <h4 class="mt-3 mb-2 bold"> <span
                                            class="mt-5"><i><?= $settings['currency'] ?></i></span>
                                        <?= number_format(($item['price'] * $item['quantity']), 2) ?> <span
                                            class="small text-muted"></span></h4>
                                    <?php
                                    $status = ["awaiting", "received", "processed", "shipped", "delivered", "cancelled", "returned"];
                                    $cancelable_till = $item['cancelable_till'];
                                    $active_status = $item['active_status'];
                                    $cancellable_index = array_search($cancelable_till, $status);
                                    $active_index = array_search($active_status, $status);
                                    if (!$item['is_already_cancelled'] && $item['product_is_cancelable'] && $active_index <= $cancellable_index && $item['type'] != 'digital_product') { ?>
                                        <button class="button button-danger button-sm update-order-item" data-status="cancelled"
                                            data-item-id="<?= $item['id'] ?>"><?= !empty($this->lang->line('cancel')) ? $this->lang->line('cancel') : 'Cancel' ?></button>
                                    <?php } ?>
                                    <?php $order_date = $order['order_items'][0]['status'][3][1];

                                    if ($item['product_is_returnable'] && !$order['is_already_returned'] && isset($order_date) && !empty($order_date)) { ?>
                                        <?php
                                        $settings = get_settings('system_settings', true);
                                        $timestemp = strtotime($order_date);
                                        $date = date('Y-m-d', $timestemp);
                                        $today = date('Y-m-d');
                                        $return_till = date('Y-m-d', strtotime($order_date . ' + ' . $settings['max_product_return_days'] . ' days'));
                                        echo "<br>";
                                        if ($today < $return_till && $item['type'] != 'digital_product') { ?>
                                            <div class="col my-auto ">
                                                <a class="update-order-item btn btn-xs btn-danger text-white mt-3 m-0"
                                                    data-status="returned" data-item-id="<?= $item['id'] ?>"
                                                    data-izimodal-open=".returnModal" href="#">
                                                    <?= !empty($this->lang->line('return')) ? str_replace('\\', '', $this->lang->line('return')) : 'Return' ?>
                                                </a>

                                            </div>
                                        <?php } ?>
                                    <?php } ?>

                                    <?php if ($item['type'] == 'digital_product' && $item['download_allowed'] == 1) {
                                        $download_link = $item['hash_link'];
                                        $is_download = fetch_details('order_items', ['id' => $item['id']], 'is_download');
                                        ?>
                                        <?php if ($is_download[0]['is_download'] == 0) { ?>
                                            <div class="media-body mt-3">
                                                <a href="<?= base_url('products/download_link_hash/' . $item['id']) ?>"
                                                    title="Download Product" class="btn btn-outline-info"><i
                                                        class="fas fa-download"></i> Download</a>
                                            </div>
                                        <?php } else { ?>
                                            <span class="text-danger">The item which you had purchased has been downloaded
                                                already!</span>

                                        <?php } ?>
                                    <?php }
                                    if ($item['type'] == 'digital_product' && $item['download_allowed'] == 0) { ?>
                                        <div class="media-body mt-3">
                                            <span class="text-danger">You will receive this item from seller via email.</span>

                                        </div>
                                    <?php } ?>
                                </div>
                                <img class="align-self-center img-fluid"
                                    src="<?= !empty($item['image_sm']) ? $item['image_sm'] : base_url(NO_IMAGE) ?>"
                                    width="180" height="180" alt="Product Image"
                                    onerror="this.onerror=null;this.src='<?= base_url(NO_IMAGE) ?>';" />

                            </div>
                            <hr>

                            <?php if ($item['type'] != 'digital_product') { ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <ul id="progressbar">
                                            <?php
                                            $status = array('received', 'processed', 'shipped', 'delivered');
                                            $i = 1;
                                            foreach ($item['status'] as $value) { ?>
                                                <?php
                                                $class = '';
                                                if ($value[0] == "cancelled" || $value[0] == "returned" || $value[0] == "return_request_pending") {
                                                    $class = 'cancel';
                                                    $status = array();
                                                } elseif (($ar_key = array_search($value[0], $status)) !== false) {
                                                    unset($status[$ar_key]);
                                                }
                                                ?>
                                                <li class="active <?= $class ?>" id="step<?= $i ?>">
                                                    <p><?= str_replace('_', ' ', strtoupper($value[0])) ?></p>
                                                    <p><?= $value[1] ?></p>
                                                </li>
                                                <?php
                                                $i++;
                                            } ?>

                                            <?php

                                            foreach ($status as $value) { ?>
                                                <li id="step<?= $i ?>">
                                                    <p><?= str_replace('_', ' ', strtoupper($value)) ?></p>
                                                </li>
                                                <?php $i++;
                                            } ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php }
                        } ?>

                        <div class="row g-4">

                            <!-- Shipping Details -->
                            <div class="col-md-6">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="h5 mb-3">
                                            <?= !empty($this->lang->line('shipping_details')) ? $this->lang->line('shipping_details') : 'Shipping Details' ?>
                                        </h6>
                                        <hr>

                                        <p class="mb-1 fw-semibold"><?= htmlspecialchars($order['username']) ?></p>
                                        <p class="mb-1 text-muted"><?= nl2br(htmlspecialchars($order['address'])) ?></p>
                                        <p class="mb-1"><?= htmlspecialchars($order['mobile']) ?></p>

                                        <?php if (!empty($order['delivery_time'])): ?>
                                            <p class="mb-1 small text-muted">
                                                <?= htmlspecialchars($order['delivery_time']) ?>
                                            </p>
                                        <?php endif; ?>

                                        <?php if (!empty($order['delivery_date'])): ?>
                                            <p class="mb-0 small text-muted">
                                                <?= htmlspecialchars($order['delivery_date']) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Price Details -->
                            <div class="col-md-6">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">

                                        <h6 class="h5 mb-3">
                                            <?= !empty($this->lang->line('price_details'))
                                                ? $this->lang->line('price_details')
                                                : 'Price Details' ?>
                                        </h6>

                                        <hr>

                                        <div class="table-responsive">
                                            <table class="table table-borderless align-middle mb-0">
                                                <tbody>

                                                    <!-- Total Order Price -->
                                                    <tr>
                                                        <td>
                                                            <?= !empty($this->lang->line('total_order_price'))
                                                                ? $this->lang->line('total_order_price')
                                                                : 'Total Order Price' ?>
                                                        </td>
                                                        <td class="text-end">
                                                            +
                                                            <?= $settings['currency'] . ' ' . number_format($order['total'] + $order['total_tax_amount'], 2) ?>
                                                        </td>
                                                    </tr>

                                                    <!-- Delivery Charge -->
                                                    <?php if ($item['type'] != 'digital_product'): ?>
                                                        <tr>
                                                            <td>
                                                                <?= !empty($this->lang->line('delivery_charge'))
                                                                    ? $this->lang->line('delivery_charge')
                                                                    : 'Delivery Charge' ?>
                                                            </td>
                                                            <td class="text-end">
                                                                +
                                                                <?= $settings['currency'] . ' ' . number_format($order['delivery_charge'], 2) ?>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>

                                                    <!-- Platform Fees -->
                                                    <?php if ($order['platform_fees'] > 0): ?>
                                                        <tr>
                                                            <td>
                                                                <?= !empty($this->lang->line('platform_fees'))
                                                                    ? $this->lang->line('platform_fees')
                                                                    : 'Platform Fees' ?>
                                                            </td>
                                                            <td class="text-end">
                                                                +
                                                                <?= $settings['currency'] . ' ' . number_format($order['platform_fees'], 2) ?>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>

                                                    <!-- Promo Code Discount -->
                                                    <?php if (!empty($order['promo_code']) && !empty($order['promo_discount'])): ?>
                                                        <tr>
                                                            <td>
                                                                <?= !empty($this->lang->line('promocode_discount'))
                                                                    ? $this->lang->line('promocode_discount')
                                                                    : 'Promocode Discount' ?>
                                                                <small class="text-muted">
                                                                    (<?= htmlspecialchars($order['promo_code']) ?>)
                                                                </small>
                                                            </td>
                                                            <td class="text-end text-danger">
                                                                -
                                                                <?= $settings['currency'] . ' ' . number_format($order['promo_discount'], 2) ?>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>

                                                    <!-- Custom Charges -->
                                                    <?php
                                                    $custom_charges = (isset($order['custom_charges']) && !empty($order['custom_charges']))
                                                        ? $order['custom_charges']
                                                        : [];

                                                    if (is_string($custom_charges)) {
                                                        $custom_charges = json_decode($custom_charges, true);
                                                    }

                                                    if (!empty($custom_charges)):
                                                        foreach ($custom_charges as $charge):
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <?= htmlspecialchars($charge['name'] ?? 'Custom Charge') ?>
                                                                </td>
                                                                <td class="text-end">
                                                                    +
                                                                    <?= $settings['currency'] . ' ' . number_format($charge['amount'], 2) ?>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        endforeach;
                                                    endif;
                                                    ?>

                                                    <!-- Wallet Used -->
                                                    <tr>
                                                        <td>
                                                            <?= !empty($this->lang->line('wallet_used'))
                                                                ? $this->lang->line('wallet_used')
                                                                : 'Wallet Used' ?>
                                                        </td>
                                                        <td class="text-end text-danger">
                                                            -
                                                            <?= $settings['currency'] . ' ' . number_format($order['wallet_balance'], 2) ?>
                                                        </td>
                                                    </tr>

                                                    <!-- Final Total -->
                                                    <tr class="border-top fw-bold">
                                                        <td class="fs-6">
                                                            <?= !empty($this->lang->line('final_total'))
                                                                ? $this->lang->line('final_total')
                                                                : 'Final Total' ?>
                                                        </td>
                                                        <td class="text-end fs-5">
                                                            <?= $settings['currency'] . ' ' . number_format($order['final_total'], 2) ?>
                                                            <span class="d-block small text-muted">
                                                                <?= !empty($this->lang->line('via'))
                                                                    ? $this->lang->line('via')
                                                                    : 'via' ?>
                                                                <?= htmlspecialchars(($order['payment_method'] == 'Stripe') ? 'Debit/Credit Card' : $order['payment_method']) ?>
                                                            </span>
                                                        </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>
                    <div class="card-footer bg-white px-sm-3 pt-sm-4 px-0">
                        <div class="row text-center ">
                            <?php
                            $status = ["awaiting", "received", "processed", "shipped", "delivered", "cancelled", "returned"];
                            $cancelable_till = $item['cancelable_till'];
                            $active_status = $item['active_status'];
                            $cancellable_index = array_search($cancelable_till, $status);
                            $active_index = array_search($active_status, $status);
                            if (!$item['is_already_cancelled'] && $item['product_is_cancelable'] && $active_index <= $cancellable_index) { ?>
                                <div class="col my-auto">
                                    <a class="update-order block button-sm buttons btn-6-1 mt-3 m-0" data-status="cancelled"
                                        data-order-id="<?= $order['id'] ?>"><?= !empty($this->lang->line('cancel')) ? $this->lang->line('cancel') : 'Cancel' ?></a>
                                </div>
                            <?php } ?>
                            <?php
                            $order_date = $order['order_items'][0]['status'][3][1];
                            if ($order['product_is_returnable'] && !$order['is_already_returned'] && isset($order_date) && !empty($order_date)) { ?>
                                <?php
                                $settings = get_settings('system_settings', true);

                                $timestemp = strtotime($order_date);
                                $date = date('Y-m-d', $timestemp);
                                $today = date('Y-m-d');
                                $return_till = date('Y-m-d', strtotime($order_date . ' + ' . $settings['max_product_return_days'] . ' days'));
                                echo "<br>";
                                if ($today < $return_till) { ?>
                                    <div class="col my-auto ">
                                        <a class="update-order block buttons button-sm btn-6-3 mt-3 m-0" data-status="returned"
                                            data-order-id="<?= $order['id'] ?>"><?= !empty($this->lang->line('return')) ? $this->lang->line('return') : 'Return' ?></a>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Return Item Modal -->
    <div id="modal-custom" class="returnModal" data-iziModal-group="grupo1">

        <div class="modal-header py-3">
            <h3 class="modal-title" id="returnModalLabel">Return Item</h3>
            <button data-iziModal-close class="icon-close">x</button>

        </div>
        <div class="modal-body py-0">
            <div class="d-flex flex-column flex-wrap gap-2 mb-3">
                <input type="hidden" id="returnItemId" value="<?= $item['id'] ?>">
                <input type="hidden" id="status" value="returned">

                <!-- Predefined reason option -->
                <?php foreach ($return_reasons as $return_reason) { ?>
                    <label class="return-reason-card py-1 mb-2 d-flex align-items-center border rounded cursor-pointer">
                        <input type="radio" name="return_reason" value="<?= $return_reason['return_reason'] ?>"
                            class="reason-radio">
                        <img src="<?= base_url() . $return_reason['image'] ?>" alt="Reason Icon" class="mx-2">
                        <p class="fs-14 mb-0"><?= $return_reason['return_reason'] ?></p>
                    </label>
                <?php } ?>

                <!-- "Other" option -->
                <label class="return-reason-card py-1 d-flex align-items-center border rounded cursor-pointer">
                    <input type="radio" name="return_reason" value="other" class="me-2 reason-radio"
                        id="otherReasonRadio">
                    <img src="<?= base_url() . NO_IMAGE ?>" alt="Reason Icon" class="me-2">
                    <p class="fs-14 mb-0">
                        <?= !empty($this->lang->line('other')) ? str_replace('\\', '', $this->lang->line('other')) : 'Other' ?>
                    </p>
                </label>

                <!-- Text field for "Other" reason (hidden by default) -->
                <input type="text" id="otherReasonField" class="form-control mt-2" placeholder="Enter your reason"
                    style="display: none;">
                <!-- Image Upload Section -->
                <div class="mt-3">
                    <label for="returnImage" class="form-label mb-1">Upload Image of Item</label>
                    <input type="file" class="form-control" id="return_item_image" name="return_item_images[]"
                        accept="image/*" multiple>
                </div>
            </div>

        </div>

        <div class="modal-footer py-3">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger btn-sm confirmReturn" id="confirmReturn">Confirm Return</button>
        </div>
    </div>

</section>