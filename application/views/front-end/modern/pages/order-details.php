<main>
    <section class="container py-5">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-2 overflow-hidden">
                    <div class="card-body p-4">
                        <div class="row gy-4">
                            <!-- Order Details -->
                            <div class="col-lg-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                                        <i class="fas fa-receipt text-primary fa-lg"></i>
                                    </div>
                                    <h5 class="mb-0 fw-semibold"><?= label('order_detail', 'Order Detail') ?></h5>
                                </div>
                                <div class="ps-4 ps-lg-0 mt-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted"><?= label('order_id', 'Order ID') ?></span>
                                        <span class="fw-semibold"><?= $order['id'] ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted"><?= label('place_on', 'Place On') ?></span>
                                        <span class="fw-semibold"><?= $order['date_added'] ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted"><?= label('otp', 'OTP') ?></span>
                                        <span class="fw-semibold"><?= $order['otp'] ?></span>
                                    </div>
                                    <?php if (!empty($order['notes'])) { ?>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">Note</span>
                                            <span class="fw-semibold"><?= $order['notes'] ?></span>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <!-- Shipping Details -->
                            <div class="col-lg-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-success bg-opacity-10 rounded-3 p-3 me-3">
                                        <i class="fas fa-map-marker-alt text-success fa-lg"></i>
                                    </div>
                                    <h5 class="mb-0 fw-semibold"><?= label('shipping_details', 'Shipping Details') ?>
                                    </h5>
                                </div>
                                <div class="ps-4 ps-lg-0 mt-3">
                                    <p class="fw-semibold text-dark mb-1"><?= $order['username'] ?></p>
                                    <p class="text-muted small mb-2"><?= $order['address'] ?></p>
                                    <p class="text-muted small mb-1"><i
                                            class="fas fa-phone me-1"></i><?= $order['mobile'] ?></p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="col-lg-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-info bg-opacity-10 rounded-3 p-3 me-3">
                                        <i class="fas fa-cog text-info fa-lg"></i>
                                    </div>
                                    <h5 class="mb-0 fw-semibold"><?= label('more_actions', 'Actions') ?></h5>
                                </div>
                                <div class="ps-4 ps-lg-0 mt-3 d-flex gap-2 flex-column">
                                    <a href="#" onclick="downloadInvoicePDF(<?= $order['id'] ?>); return false;"
                                        class="btn btn-outline-primary btn-sm">
                                        <i
                                            class="fas fa-download me-2"></i><?= label('Download Invoice', 'Download Invoice') ?>
                                    </a>
                                    <a target="_blank"
                                        href="<?= base_url('my-account/order-invoice/' . $order['id']) ?>"
                                        class='btn btn-primary btn-sm'>
                                        <i class="fas fa-file-pdf me-2"></i><?= label('invoice', 'View Invoice') ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tracking Section (if applicable) -->
        <?php if ($order['is_pos_order'] == 1) { ?>
            <!-- POS Order - No tracking URL/Number needed -->
        <?php } elseif ($order['order_items'][0]['type'] != 'digital_product') { ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-2">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning bg-opacity-10 rounded-3 p-3 me-3">
                                    <i class="fas fa-box text-warning fa-lg"></i>
                                </div>
                                <h5 class="mb-0 fw-semibold"><?= label('order_tracking', 'Order Tracking') ?></h5>
                            </div>
                            <div class="row gy-3 mt-2">
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Tracking URL</span>
                                        <span class="fw-semibold">
                                            <?php if (!empty($order['url'])) { ?>
                                                <a href="<?= $order['url'] ?>" target="_blank" class="text-decoration-none">
                                                    <i class="fas fa-external-link-alt me-1"></i><?= $order['url'] ?>
                                                </a>
                                            <?php } else { ?>
                                                <span class="text-muted">Not available</span>
                                            <?php } ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Tracking Number</span>
                                        <span class="fw-semibold">
                                            <?php if (!empty($order['tracking_id'])) { ?>
                                                <?= $order['tracking_id'] ?>
                                            <?php } else { ?>
                                                <span class="text-muted">Not available</span>
                                            <?php } ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Courier Agency</span>
                                        <span class="fw-semibold">
                                            <?php if (!empty($order['courier_agency'])) { ?>
                                                <?= $order['courier_agency'] ?>
                                            <?php } else { ?>
                                                <span class="text-muted">Not available</span>
                                            <?php } ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <!-- Order Items with Status Steppers -->
        <div class="row mb-4">
            <div class="col-12">
                <h5 class="fw-semibold mb-4">Order Items</h5>
                <div class="row g-4">
                    <?php foreach ($order['order_items'] as $key => $item) {
                        $status = ["awaiting", "received", "processed", "shipped", "delivered", "cancelled", "returned", "return_request_pending", "return_request_approved"];
                        $cancelable_till = $item['cancelable_till'];
                        $active_status = $item['active_status'];
                        $cancellable_index = array_search($cancelable_till, $status);
                        $active_index = array_search($active_status, $status);
                        ?>
                        <div class="col-12">
                            <div class="card border-0 shadow-sm rounded-2 h-100 overflow-hidden">
                                <div class="card-body p-4">
                                    <!-- Product Info Row -->
                                    <div class="row gy-3 mb-4 pb-4 border-bottom">
                                        <!-- Product Image -->
                                        <div class="col-auto">
                                            <div class="rounded-3 overflow-hidden"
                                                style="width: 120px; height: 120px;">
                                                <img class="w-100 h-100 object-fit-contain"
                                                    src="<?= !empty($item['image_sm']) ? $item['image_sm'] : base_url(NO_IMAGE) ?>"
                                                    alt="Product Image"
                                                    onerror="this.onerror=null;this.src='<?= base_url(NO_IMAGE) ?>';" />
                                            </div>
                                        </div>

                                        <!-- Product Details -->
                                        <div class="col-md-auto flex-grow-1">
                                            <h6 class="fw-semibold text-dark mb-2"><?= $item['name'] ?></h6>
                                            <p class="text-muted small mb-2"><?= $item['variant_name'] ?></p>
                                            <?php
                                            $unit_price = (isset($item['discounted_price']) && $item['discounted_price'] != '' && $item['discounted_price'] > 0 && $item['price'] > $item['discounted_price']) ? $item['discounted_price'] : $item['price'];
                                            $line_total = isset($item['sub_total']) && $item['sub_total'] != '' ? $item['sub_total'] : ($unit_price * $item['quantity']);
                                            ?>
                                            <div class="mb-3">
                                                <div class="badge bg-light text-dark mb-1">
                                                    <i class="fas fa-cube me-1"></i>Qty: <?= $item['quantity'] ?>
                                                </div>
                                                <div class="text-muted small">Price: <span class="fw-semibold"><?= $settings['currency'] . ' ' . number_format($unit_price, 2) ?></span></div>
                                                <div class="text-muted small">Subtotal: <span class="fw-semibold"><?= $settings['currency'] . ' ' . number_format($line_total, 2) ?></span></div>
                                            </div>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <?php
                                                if (!$item['is_already_cancelled'] && $item['product_is_cancelable'] && $active_index !== false && $cancellable_index !== false && $active_index <= $cancellable_index && $item['type'] != 'digital_product') { ?>
                                                    <button class="btn btn-danger btn-sm update-order-item"
                                                        data-status="cancelled" data-item-id="<?= $item['id'] ?>">
                                                        <i class="fas fa-times me-1"></i><?= label('cancel', 'Cancel') ?>
                                                    </button>
                                                <?php } elseif ($item['is_already_cancelled']) { ?>
                                                    <span class="badge bg-danger">Cancelled</span>
                                                <?php } elseif ($item['active_status'] == 'return_request_pending') { ?>
                                                    <span class="badge bg-warning">Return Request Pending</span>
                                                <?php } elseif ($item['active_status'] == 'return_request_approved') { ?>
                                                    <span class="badge bg-success">Return Approved</span>
                                                <?php } elseif ($item['active_status'] == 'returned' || $item['is_already_returned']) { ?>
                                                    <span class="badge bg-danger">Returned</span>
                                                <?php } ?>

                                                <?php
                                                if (
                                                    $item['product_is_returnable'] &&
                                                    !$item['is_already_returned'] &&
                                                    !in_array($item['active_status'], ['return_request_pending', 'return_request_approved', 'returned']) &&
                                                    isset($item['status'][3][1]) && !empty($item['status'][3][1])
                                                ) {
                                                    $order_date = $item['status'][3][1];
                                                    $settings = get_settings('system_settings', true);
                                                    $timestemp = strtotime($order_date);
                                                    $date = date('Y-m-d', $timestemp);
                                                    $today = date('Y-m-d');
                                                    $return_till = date('Y-m-d', strtotime($order_date . ' + ' . $settings['max_product_return_days'] . ' days'));
                                                    if ($today < $return_till && $item['type'] != 'digital_product') { ?>
                                                        <button class="btn btn-danger btn-sm" data-status="returned"
                                                            data-item-id="<?= $item['id'] ?>" data-bs-toggle="modal"
                                                            data-bs-target="#returnModal_<?= $item['id'] ?>"
                                                            data-order-id="<?= $order['id'] ?>">
                                                            <i class="fas fa-redo me-1"></i><?= label('return', 'Return') ?>
                                                        </button>
                                                    <?php } ?>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Status Stepper Row (Original Style) -->
                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="fw-semibold mb-3">Status</h6>
                                            <!-- <div class="d-md-flex d-block row justify-content-around mb-2" id="progressbar">
                                                <?php
                                                $order_status_data = $item['status'];
                                                $i = 1;
                                                foreach ($order_status_data as $index => $status_item) {
                                                    $status_name = $status_item[0];
                                                    $status_date = $status_item[1];
                                                    $is_completed = in_array($status_name, array_column(array_slice($order_status_data, 0, $index + 1), 0));
                                                    ?>
                                                    <div class="col-md d-md-block d-flex ms-md-0 ms-3 mb-md-0 mb-2 text-center"
                                                        style="flex: 1;">
                                                        <div id="steps">
                                                            <?php if ($is_completed) { ?>
                                                                <div class="step done d-inline-flex align-items-center justify-content-center"
                                                                    style="width: 40px; height: 40px; margin: 0 auto;">
                                                                    <i class="fas fa-check"></i>
                                                                </div>
                                                            <?php } else { ?>
                                                                <div class="step d-inline-flex align-items-center justify-content-center"
                                                                    style="width: 40px; height: 40px; margin: 0 auto;">
                                                                    <i class="fa fa-circle" style="font-size: 0.5rem;"></i>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                        <div class="ms-md-0">
                                                            <p class="mt-2 mb-1 fw-semibold"
                                                                style="font-size: 0.85rem; text-transform: uppercase;">
                                                                <?= str_replace('_', ' ', $status_name) ?>
                                                            </p>
                                                            <p style="font-size: 0.75rem; color: #6c757d;">
                                                                <?= !empty($status_date) ? $status_date : 'Pending' ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <?php $i++; ?>
                                                <?php } ?>
                                            </div> -->



                                            <?php if ($order['is_pos_order'] == 1) { ?>
                                                <!-- POS Order - Show only Delivered step -->
                                                <div class="col-lg-12 mt-4">
                                                    <div class="d-md-flex d-block row justify-content-center mb-4"
                                                        id="progressbar">
                                                        <div class="active col-2 d-md-block d-flex ms-md-0 ms-4 mb-md-0 mb-4 progressbar-box"
                                                            id="step1">
                                                            <div id="steps">
                                                                <div class="step done"><i class="fa fa-check"></i></div>
                                                            </div>
                                                            <div class="ms-md-0 ms-4">
                                                                <p class="mt-2"><?= label('delivered', 'DELIVERED') ?></p>
                                                                <p><?= label('delivered_via_pos', 'Delivered via POS') ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } elseif ($order['order_items'][0]['type'] != 'digital_product') { ?>
                                                <div class="col-lg-12 mt-4">
                                                    <?//php foreach ($item as $itemIndex => $item) { ?>

                                                        <div class="mb-5">
                                                           

                                                            <div
                                                                class="d-md-flex d-block row justify-content-around mb-4 progressbar">

                                                                <?php
                                                                $pickup = ($order['is_local_pickup'] == 1) ? 'ready_to_pickup' : 'shipped';
                                                                $status_order = ['received', 'processed', $pickup, 'delivered'];

                                                                // 🔑 ITEM-WISE STATUS
                                                                $order_status_data = $item['status'];

                                                                $status_history_arr = array_column($order_status_data, 0);
                                                                $status_history_dates = array_column($order_status_data, 1, 0);

                                                                // Cancellation / return statuses
                                                                $cancellation_statuses = [
                                                                    'cancelled',
                                                                    'returned',
                                                                    'return_request_pending',
                                                                    'return_request_approved'
                                                                ];

                                                                $is_cancelled_or_returned = false;
                                                                foreach ($cancellation_statuses as $neg_status) {
                                                                    if (in_array($neg_status, $status_history_arr)) {
                                                                        $is_cancelled_or_returned = true;
                                                                        break;
                                                                    }
                                                                }

                                                                $i = 1;

                                                                if ($is_cancelled_or_returned) {

                                                                    foreach ($order_status_data as $value) {
                                                                        $class = in_array($value[0], $cancellation_statuses) ? 'cancel' : '';
                                                                        ?>
                                                                        <div
                                                                            class="active col-2 d-md-block d-flex progressbar-box <?= $class ?>">
                                                                            <div id="steps">
                                                                                <div class="step done"><i class="fa fa-check"></i></div>
                                                                            </div>
                                                                            <div class="ms-md-0 ms-4">
                                                                                <p class="mt-2">
                                                                                    <?= strtoupper(str_replace('_', ' ', $value[0])) ?></p>
                                                                                <p><?= $value[1] ?></p>
                                                                            </div>
                                                                        </div>
                                                                        <?php
                                                                        $i++;
                                                                    }

                                                                } else {

                                                                    // Find last reached step
                                                                    $last_reached_index = -1;
                                                                    foreach ($status_order as $idx => $s) {
                                                                        if (in_array($s, $status_history_arr)) {
                                                                            $last_reached_index = $idx;
                                                                        }
                                                                    }

                                                                    foreach ($status_order as $index => $step) {
                                                                        $is_reached = $index <= $last_reached_index;
                                                                        $date = $status_history_dates[$step] ?? '';
                                                                        ?>

                                                                        <div
                                                                            class="<?= $is_reached ? 'active' : '' ?> col-2 d-md-block d-flex progressbar-box">
                                                                            <div id="steps">
                                                                                <div class="step <?= $is_reached ? 'done' : '' ?>">
                                                                                    <?= $is_reached ? '<i class="fa fa-check"></i>' : '<i class="ionicon-ellipse"></i>' ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="ms-md-0 ms-4">
                                                                                <p class="mt-2">
                                                                                    <?= strtoupper(str_replace('_', ' ', $step)) ?></p>
                                                                                <?php if ($date) { ?>
                                                                                    <p><?= $date ?></p><?php } ?>
                                                                            </div>
                                                                        </div>

                                                                        <?php
                                                                        $i++;
                                                                    }
                                                                }
                                                                ?>

                                                            </div>
                                                        </div>

                                                    <?//php } ?>

                                                </div>
                                            <?php } else { ?>
                                                <div class="col-lg-12 mt-4">
                                                    <div class="d-md-flex d-block row justify-content-around mb-4"
                                                        id="progressbar">
                                                        <?php
                                                        $status = ['received', 'delivered'];
                                                        $i = 1;
                                                        foreach ($order['order_items'][0]['status'] as $value) { ?>
                                                            <?php
                                                            if (in_array($value[0], ['processed', 'shipped'])) {
                                                                continue;
                                                            }
                                                            $class = (in_array($value[0], ['cancelled', 'return_request_pending', 'return_request_approved', 'returned'])) ? 'cancel' : '';
                                                            $status_value = str_replace('_', ' ', $value[0]);
                                                            ?>
                                                            <div class="active col-3 d-md-block d-flex ms-md-0 ms-4 mb-md-0 mb-4 progressbar-box <?= $class ?>"
                                                                id="step<?= $i ?>">
                                                                <div id="steps">
                                                                    <div class="step done"><i class="fa fa-check"></i></div>
                                                                </div>
                                                                <div class="ms-md-0 ms-4">
                                                                    <p class="mt-2">
                                                                        <?= strtoupper(str_replace('_', ' ', $value[0])) ?>
                                                                    </p>
                                                                    <p><?= $value[1] ?></p>
                                                                </div>
                                                            </div>
                                                            <?php
                                                            $i++;
                                                            if (($ar_key = array_search($value[0], $status)) !== false) {
                                                                unset($status[$ar_key]);
                                                            }
                                                            ?>
                                                        <?php } ?>
                                                        <?php foreach ($status as $value) { ?>
                                                            <div class="col-3 d-md-block d-flex ms-md-0 ms-4 mb-md-0 mb-4 progressbar-box"
                                                                id="step<?= $i ?>">
                                                                <div id="steps">
                                                                    <div class="step"><i class="ionicon-ellipse"></i></div>
                                                                </div>
                                                                <div class="ms-md-0 ms-4">
                                                                    <p class="mt-2"><?= strtoupper(str_replace('_', ' ', $value)) ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <?php $i++; ?>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            <?php } ?>






                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Return Modal -->
                        <div class="modal fade" id="returnModal_<?= $item['id'] ?>" tabindex="-1"
                            aria-labelledby="returnModalLabel_<?= $item['id'] ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-md">
                                <div class="modal-content rounded-4 shadow-lg">
                                    <div class="modal-header bg-light py-4 border-0">
                                        <h5 class="modal-title fw-semibold" id="returnModalLabel_<?= $item['id'] ?>">
                                            <i class="fas fa-redo text-danger me-2"></i>Return Item: <?= $item['name'] ?>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body p-4">
                                        <div class="d-flex flex-column gap-3">

                                            <input type="hidden" id="returnItemId_<?= $item['id'] ?>"
                                                value="<?= $item['id'] ?>">
                                            <input type="hidden" id="status_<?= $item['id'] ?>" value="returned">

                                            <p class="fw-semibold mb-2">Select Return Reason</p>

                                            <?php foreach ($return_reasons as $return_reason) { ?>
                                                <label
                                                    class="d-flex align-items-center justify-content-between p-3 border rounded-3 cursor-pointer w-100 transition"
                                                    style="cursor: pointer; border: 2px solid #e9ecef;">

                                                    <!-- LEFT: Radio -->
                                                    <div class="flex-shrink-2 me-3">
                                                        <input type="radio" name="return_reason_<?= $item['id'] ?>"
                                                            value="<?= $return_reason['return_reason'] ?>"
                                                            class="reason-radio form-check-input">
                                                    </div>

                                                    <!-- CENTER: Text -->
                                                    <div class="flex-grow-1 text-center fw-medium px-2">
                                                        <?= $return_reason['return_reason'] ?>
                                                    </div>

                                                    <!-- RIGHT: Image -->
                                                    <div class="flex-shrink-2 ms-1">
                                                        <img src="<?= !empty($return_reason['image']) ? base_url($return_reason['image']) : base_url(NO_IMAGE) ?>"
                                                            alt="Reason Icon" class="rounded" width="40" height="40"
                                                            style="object-fit: cover;"
                                                            onerror="this.onerror=null;this.src='<?= base_url(NO_IMAGE) ?>';">
                                                    </div>

                                                </label>
                                            <?php } ?>

                                            <!-- Other option -->
                                            <label
                                                class="d-flex align-items-center justify-content-between p-3 border rounded-3 cursor-pointer w-100 transition"
                                                style="cursor: pointer; border: 2px solid #e9ecef;">

                                                <div class="flex-shrink-2 me-1">
                                                    <input type="radio" name="return_reason_<?= $item['id'] ?>"
                                                        value="other" class="reason-radio form-check-input"
                                                        id="otherReasonRadio_<?= $item['id'] ?>">
                                                </div>

                                                <div class="flex-grow-1 text-center fw-medium px-2">
                                                    <?= !empty($this->lang->line('other')) ? $this->lang->line('other') : 'Other' ?>
                                                </div>

                                                <div class="flex-shrink-0 ms-3">
                                                    <img src="<?= base_url() . NO_IMAGE ?>" alt="Reason Icon"
                                                        class="rounded" width="40" height="40" style="object-fit: cover;">
                                                </div>

                                            </label>

                                            <!-- Other reason field -->
                                            <input type="text" id="otherReasonField_<?= $item['id'] ?>"
                                                class="form-control rounded-3" placeholder="Enter your reason"
                                                style="display: none;">

                                            <!-- Image upload -->
                                            <div class="border rounded-3 p-3 bg-light">
                                                <label for="return_item_image_<?= $item['id'] ?>"
                                                    class="form-label fw-semibold mb-2">
                                                    <i class="fas fa-image me-2"></i>Upload Image of Item
                                                </label>
                                                <input type="file" class="form-control rounded-3"
                                                    id="return_item_image_<?= $item['id'] ?>"
                                                    name="return_item_images_<?= $item['id'] ?>[]" accept="image/*"
                                                    multiple>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="modal-footer border-0 pt-0 pb-4 px-4">
                                        <button type="button" class="btn btn-outline-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-danger confirmReturn"
                                            data-item-id="<?= $item['id'] ?>">
                                            <i class="fas fa-check me-1"></i>Confirm Return
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Price Details Section -->
        <div class="row mb-4">
            <div class="col-lg-6 offset-lg-6">
                <div class="card border-0 shadow-sm rounded-2">
                    <div class="card-body p-4">
                        <h5 class="fw-semibold mb-4 pb-3 border-bottom">
                            <i
                                class="fas fa-calculator me-2 text-primary"></i><?= label('price_details', 'Price Details') ?>
                        </h5>

                        <div class="row gy-2">
                            <!-- Total Order Price -->
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Total Order Price</span>
                                    <span
                                        class="fw-semibold"><?= $settings['currency'] . ' ' . number_format($order['total'] + $order['bulk_discount'], 2) ?></span>
                                </div>
                            </div>

                            <!-- Delivery Charge -->
                            <?php if ($order['order_items'][0]['type'] != 'digital_product') { ?>
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Delivery Charge</span>
                                        <span
                                            class="fw-semibold"><?= $settings['currency'] . ' ' . number_format($order['delivery_charge'], 2) ?></span>
                                    </div>
                                </div>
                            <?php } ?>

                            <!-- Platform Fees -->
                            <?php if (!empty($order['platform_fees']) && $order['platform_fees'] > 0) { ?>
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Platform Fees</span>
                                        <span
                                            class="fw-semibold"><?= $settings['currency'] . ' ' . number_format($order['platform_fees'], 2) ?></span>
                                    </div>
                                </div>
                            <?php } ?>

                            <!-- Custom Charges -->
                            <?php
                            $custom_charges = (isset($order['custom_charges']) && !empty($order['custom_charges'])) ? $order['custom_charges'] : [];
                            if (is_string($custom_charges)) {
                                $custom_charges = json_decode($custom_charges, true);
                            }
                            if (!empty($custom_charges)) {
                                foreach ($custom_charges as $charge) {
                                    ?>
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between">
                                            <span
                                                class="text-muted"><?= htmlspecialchars($charge['name'] ?? 'Custom Charge') ?></span>
                                            <span
                                                class="fw-semibold"><?= $settings['currency'] . ' ' . number_format($charge['amount'], 2) ?></span>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>

                            <!-- Discounts and Adjustments -->
                            <?php if ($order['bulk_discount'] > 0) { ?>
                                <div class="col-12">
                                    <div class="d-flex justify-content-between text-success">
                                        <span class="text-muted">Bulk Discount</span>
                                        <span
                                            class="fw-semibold">-<?= $settings['currency'] . ' ' . number_format($order['bulk_discount'], 2) ?></span>
                                    </div>
                                </div>
                            <?php } ?>

                            <?php if ($order['wallet_balance'] > 0) { ?>
                                <div class="col-12">
                                    <div class="d-flex justify-content-between text-success">
                                        <span class="text-muted">Wallet Used</span>
                                        <span
                                            class="fw-semibold">-<?= $settings['currency'] . ' ' . number_format($order['wallet_balance'], 2) ?></span>
                                    </div>
                                </div>
                            <?php } ?>

                            <?php if (!empty($order['promo_code']) && !empty($order['promo_discount'])) { ?>
                                <div class="col-12">
                                    <div class="d-flex justify-content-between text-success">
                                        <span class="text-muted">Promo Code (<?= $order['promo_code'] ?>)</span>
                                        <span
                                            class="fw-semibold">-<?= $settings['currency'] . ' ' . number_format($order['promo_discount'], 2) ?></span>
                                    </div>
                                </div>
                            <?php } ?>

                            <?php if ($order['total_tax_amount'] > 0) { ?>
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Tax (<?= $order['total_tax_percent'] ?>%)</span>
                                        <span
                                            class="fw-semibold"><?= $settings['currency'] . ' ' . number_format($order['total_tax_amount'], 2) ?></span>
                                    </div>
                                </div>
                            <?php } ?>

                            <!-- Final Total -->
                            <div class="col-12 pt-3 border-top">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold text-dark">Final Total</span>
                                    <div class="text-end">
                                        <h5 class="fw-bold text-dark mb-1">
                                            <?= $settings['currency'] . ' ' . number_format($order['final_total'], 2) ?>
                                        </h5>
                                        <small class="text-muted">via <?= ($order['payment_method'] == 'Stripe') ? 'Debit/Credit Card' : $order['payment_method'] ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        </div>
        <!-- Bank Transfer Section (if applicable) -->
        <div class="justify-content-center mt-3 row gap-2">
            <?php if ($order['payment_method'] == "Bank Transfer" && $bank_transfer[0]['status'] != 2 && (empty($bank_transfer[0]['attachments']) || $bank_transfer[0]['status'] == 1)) { ?>
                <div class="row col-12">
                    <?php if (empty($bank_transfer[0]['attachments'])) { ?>
                        <div class="col-md-12 mb-3">
                            <div class="alert alert-warning alert-dismissible fade show rounded-4" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Bank Payment Receipt Required!</strong>
                                <p class="mb-0 mt-2">Please upload your bank payment receipt to confirm your payment.</p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    <?php } ?>
                    <form class="col-12" id="send_bank_receipt_form" action="<?= base_url('cart/send-bank-receipt'); ?>"
                        method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>"
                            value="<?= $this->security->get_csrf_hash() ?>">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <div class="form-group mb-3">
                                    <label for="receipt" class="form-label fw-semibold mb-2">
                                        <i
                                            class="fas fa-file-upload me-2 text-primary"></i><?= !empty($this->lang->line('bank_payment_receipt')) ? $this->lang->line('bank_payment_receipt') : 'Bank Payment Receipt' ?>
                                    </label>
                                    <input type="file" class="form-control rounded-3" name="attachments[]" id="receipt"
                                        multiple required />
                                    <small class="text-muted d-block mt-2">Accepted formats: PDF, JPG, PNG</small>
                                </div>
                                <div class="d-flex gap-2 mt-4">
                                    <button type="reset" class="btn btn-outline-secondary rounded-3">
                                        <i
                                            class="fas fa-redo me-1"></i><?= !empty($this->lang->line('reset')) ? $this->lang->line('reset') : 'Reset' ?>
                                    </button>
                                    <button type="submit" class="btn btn-success rounded-3" id="submit_btn">
                                        <i
                                            class="fas fa-paper-plane me-1"></i><?= !empty($this->lang->line('send')) ? $this->lang->line('send') : 'Send' ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            <?php } ?>

            <?php if (!empty($bank_transfer[0]['attachments']) && $bank_transfer[0]['status'] == 0) { ?>
                <div class="col-md-12 mt-3">
                    <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong><?= !empty($this->lang->line('bank_payment_receipt_sent')) ? $this->lang->line('bank_payment_receipt_sent') : 'Bank Payment Receipt Sent' ?></strong>
                        <p class="mb-0 mt-2">Your payment receipt has been submitted for verification. Please wait for our
                            confirmation.</p>
                    </div>
                </div>
            <?php } elseif (!empty($bank_transfer[0]['attachments']) && $bank_transfer[0]['status'] == 1) { ?>
                <div class="col-md-12 mt-3">
                    <div class="alert alert-danger alert-dismissible fade show rounded-4" role="alert">
                        <i class="fas fa-times-circle me-2"></i>
                        <strong><?= !empty($this->lang->line('bank_payment_receipt_rejected')) ? $this->lang->line('bank_payment_receipt_rejected') : 'Bank Payment Receipt Rejected' ?></strong>
                        <p class="mb-0 mt-2">Your payment receipt was rejected. Please upload a clearer image or correct
                            document.</p>
                    </div>
                </div>
            <?php } elseif (!empty($bank_transfer[0]['attachments']) && $bank_transfer[0]['status'] == 2) { ?>
                <div class="col-md-12 mt-3">
                    <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong><?= !empty($this->lang->line('bank_payment_receipt_accepted')) ? $this->lang->line('bank_payment_receipt_accepted') : 'Bank Payment Receipt Accepted' ?></strong>
                        <p class="mb-0 mt-2">Your payment has been verified successfully. Thank you!</p>
                    </div>
                </div>
            <?php } ?>

            <?php if (!empty($bank_transfer)) { ?>
                <div class="col-md-12 mt-3">
                    <div class="d-flex gap-2 flex-wrap">
                        <?php $i = 1;
                        foreach ($bank_transfer as $row1) { ?>
                            <a href="<?= base_url() . $row1['attachments'] ?>" target="_blank"
                                class="btn btn-sm btn-outline-primary rounded-3">
                                <i class="fas fa-download me-1"></i>Attachment <?= $i ?>
                            </a>
                            <?php $i++;
                        } ?>
                    </div>
                </div>
            <?php } ?>

            <?php if ($order['payment_method'] == "Bank Transfer") { ?>
                <div class="col-md-12 mt-3">
                    <?php if ($bank_transfer[0]['status'] == 0) { ?>
                        <div class="badge bg-warning rounded-3 px-3 py-2">
                            <i
                                class="fas fa-hourglass-half me-1"></i><?= !empty($this->lang->line('pending')) ? $this->lang->line('pending') : 'Verification Pending' ?>
                        </div>
                    <?php } else if ($bank_transfer[0]['status'] == 1) { ?>
                            <div class="badge bg-danger rounded-3 px-3 py-2">
                                <i
                                    class="fas fa-ban me-1"></i><?= !empty($this->lang->line('rejected')) ? $this->lang->line('rejected') : 'Verification Rejected' ?>
                            </div>
                    <?php } else if ($bank_transfer[0]['status'] == 2) { ?>
                                <div class="badge bg-success rounded-3 px-3 py-2">
                                    <i
                                        class="fas fa-check me-1"></i><?= !empty($this->lang->line('accepted')) ? $this->lang->line('accepted') : 'Verification Accepted' ?>
                                </div>
                    <?php } else { ?>
                                <div class="badge bg-secondary rounded-3 px-3 py-2">
                                    <i
                                        class="fas fa-exclamation me-1"></i><?= !empty($this->lang->line('invalid_value')) ? $this->lang->line('invalid_value') : 'Invalid Value' ?>
                                </div>
                    <?php } ?>
                </div>
            <?php } ?>

            <!-- Digital Product Download -->
            <?php if ($order['order_items'][0]['active_status'] == 'received' || $order['order_items'][0]['active_status'] == 'delivered') { ?>
                <?php if ($order['order_items'][0]['type'] == 'digital_product' && $order['order_items'][0]['download_allowed'] == 1) {
                    $download_link = $order['order_items'][0]['download_link'];
                    $is_download = fetch_details('order_items', ['id' => $order['order_items'][0]['id']], 'is_download');
                    ?>
                    <div class="col-md-12 mt-3">
                        <?php
                        if ($order['payment_method'] == 'Bank Transfer') {
                            if ($bank_transfer[0]['status'] == 2) {
                                if ($is_download[0]['is_download'] == 0) { ?>
                                    <a target="_blank" href="<?= $download_link ?>" class="btn btn-primary rounded-3">
                                        <i class="fas fa-download me-2"></i>Download Digital Product
                                    </a>
                                <?php } else { ?>
                                    <div class="alert alert-info rounded-4 mb-0">
                                        <i class="fas fa-info-circle me-2"></i>This digital product has already been downloaded.
                                    </div>
                                <?php }
                            }
                        } else {
                            if ($is_download[0]['is_download'] == 0) { ?>
                                <a target="_blank" href="<?= $download_link ?>" class="btn btn-primary rounded-3">
                                    <i class="fas fa-download me-2"></i>Download Digital Product
                                </a>
                            <?php } else { ?>
                                <div class="alert alert-info rounded-4 mb-0">
                                    <i class="fas fa-info-circle me-2"></i>This digital product has already been downloaded.
                                </div>
                            <?php }
                        } ?>
                    </div>
                <?php } ?>
                <?php if ($order['order_items'][0]['type'] == 'digital_product' && $order['order_items'][0]['download_allowed'] == 0) { ?>
                    <div class="col-md-12 mt-3">
                        <div class="alert alert-info rounded-4 mb-0">
                            <i class="fas fa-envelope me-2"></i>You will receive this digital product from the seller via email.
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </section>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>