<main>
    <section class="container py-5">
        <div class="row">
            <div class="col-lg-3 myaccount-navigation py-3">
                <?php $this->load->view('front-end/' . THEME . '/pages/my-account-sidebar') ?>
            </div>
            <div class="col-lg-9 padding-16-30">
                <div class="mb-3">
                    <h4 class="section-title"><?= label('orders', 'Orders') ?></h4>
                </div>
                <?php
                if (empty($orders['order_data'])) { ?>
                    <div class="col-lg-11 m-5">
                        <div class="text-center">
                            <i class="ionicon-cart-outline-2"></i>
                            <h5 class="h2"><?= label('no_order_has_been_made_yet', 'No order has been made yet') ?>.</h5>
                            <a href="<?= base_url('products') ?>" class="button button-rounded button-warning">
                                <button class="btn btn-primary">
                                    <?= label('go_to_shop', 'Go to Shop') ?>
                                </button>
                            </a>
                        </div>
                    </div>
                    <?php
                } else {
                    foreach ($orders['order_data'] as $row) {
                        $images = $row['order_items']; ?>
                        <div class="card order-card mb-3">
                            <div class="row g-0">
                                <div class="col-md-2">
                                    <?php foreach ($images as $item) { ?>
                                        <?php if ($item['product_image'] == '') { ?>

                                            <div class="img-box-150">
                                                <img src="<?= !empty($item['image_sm']) ? $item['image_sm'] : base_url(NO_IMAGE) ?>"
                                                    class="img-fluid rounded-start p-2" alt="Product Image"
                                                    onerror="this.onerror=null;this.src='<?= base_url(NO_IMAGE) ?>';" />
                                            </div>
                                        <?php } else { ?>
                                            <div class="img-box-150">
                                                <img src="<?= !empty($item['image_sm']) ? $item['image_sm'] : base_url(NO_IMAGE) ?>"
                                                    class="img-fluid rounded-start p-2" alt="Product Image"
                                                    onerror="this.onerror=null;this.src='<?= base_url(NO_IMAGE) ?>';" />
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                                <div class="col-md-4">
                                    <?php foreach ($row['order_items'] as $key => $item) { ?>
                                        <div class="card-body mx-3">
                                            <h6 class="card-title"><?= $item['name'] ?></h6>
                                            <p class="m-0"><small class="card-text"><?= $item['variant_name'] ?></small></p>
                                            <p class="m-0"><small class="card-text"><?= label('quantity', 'quantity') ?> :
                                                    <?= $item['quantity'] ?></small></p>
                                            <p class="m-0"><small class="card-text"><?= label('order_id', 'Order ID') ?> :
                                                    <?= $row['id'] ?></small></p>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="col-md-2">
                                    <div class="card-body">
                                        <h4 class="card-price"><i><?= $settings['currency'] ?></i></span>
                                            <?= number_format($row['final_total'], 2) ?></h6>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card-body">
                                        <h6 class="card-title"><ion-icon name="ellipse" class="text-success"></ion-icon>
                                            <?= str_replace('_', ' ', $item['active_status']) ?></h6>
                                        <p><small class="card-text"><?= label('place_on', 'Place On') ?> :
                                                <?= $row['date_added'] ?></small></p>
                                        <h5 class="btn viewmorebtn p-2">
                                            <a href="<?= base_url('my-account/order-details/' . $row['id']) ?>">
                                                <?= label('view_details', 'View details') ?>
                                            </a>
                                        </h5>
                                        <?php
                                        $items = $row["order_items"];
                                        $variants = "";
                                        $qty = "";
                                        foreach ($items as $item) {
                                            if ($variants != "") {
                                                $variants .= ",";
                                                $qty .= ",";
                                            }
                                            $variants .= $item["product_variant_id"];
                                            $qty .= $item["quantity"];
                                        }

                                        ?>
                                        <h5 class="btn btn-lg btn-primary mx-2">
                                            <a class="reorder-btn block button-lg buttonss text-white m-0"
                                                data-variants="<?= $variants ?>"
                                                data-quantity="<?= $qty ?>"><?= label('reorder', 'Reorder') ?></a>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <?php if ($row['is_pos_order'] == 1) { ?>
                                <!-- POS Order - Show only Delivered step -->
                                <div class="d-md-flex d-block row justify-content-center mt-2 mb-4" id="progressbar">
                                    <div class="active d-md-block d-flex ms-md-0 ms-4 mb-md-0 mb-4 col-2 progressbar-box"
                                        id="step1">
                                        <div id="steps">
                                            <div class="step done"><i class="fa fa-check"></i></div>
                                        </div>
                                        <div class="ms-md-0 ms-4">
                                            <p class="mt-2"><?= label('delivered', 'DELIVERED') ?></p>
                                            <p><?= label('instant_fulfilled', 'Delivered via POS') ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php } elseif ($row['order_items'][0]['type'] != 'digital_product') { ?>
                                <div class="d-md-flex d-block row justify-content-around mt-2 mb-4" id="progressbar">
                                    <?php
                                    $pickup = ($row['is_local_pickup'] == 1) ? 'ready_to_pickup' : 'shipped';
                                    $status_order = array('received', 'processed', $pickup, 'delivered');
                                    
                                    // Use the first item's status as the order status
                                    $order_status_data = $row['order_items'][0]['status'];
                                    $status_history_arr = array_column($order_status_data, 0);
                                    $status_history_dates = array_column($order_status_data, 1, 0);

                                    // Check if cancelled or returned
                                    $is_cancelled = in_array('cancelled', $status_history_arr);
                                    $is_returned = in_array('returned', $status_history_arr);

                                    if ($is_cancelled || $is_returned) {
                                         $i = 1;
                                         foreach ($order_status_data as $value) { 
                                             $class = ($value[0] == "cancelled" || $value[0] == "returned") ? 'cancel' : '';
                                             ?>
                                             <div class="active d-md-block d-flex ms-md-0 ms-4 mb-md-0 mb-4 col-2 progressbar-box <?= $class ?>" id="step<?= $i ?>">
                                                <div id="steps">
                                                    <div class="step done"><i class="fa fa-check"></i></div>
                                                </div>
                                                <div class="ms-md-0 ms-4">
                                                    <p class="mt-2"><?= strtoupper(str_replace('_', ' ', $value[0])) ?></p>
                                                    <p><?= $value[1] ?></p>
                                                </div>
                                            </div>
                                            <?php $i++;
                                         }
                                         if ($is_cancelled) {
                                            $last_reached_index = -1;
                                            foreach ($status_order as $idx => $s) {
                                                if (in_array($s, $status_history_arr)) {
                                                    $last_reached_index = $idx;
                                                }
                                            }

                                            for ($k = $last_reached_index + 2; $k < count($status_order); $k++) {
                                                $step = $status_order[$k]; ?>
                                                <div class="col-2 d-md-block d-flex ms-md-0 ms-4 mb-md-0 mb-4 progressbar-box" id="step<?= $i ?>">
                                                    <div id="steps">
                                                        <div class="step"><i class="ionicon-ellipse"></i></div>
                                                    </div>
                                                    <div class="ms-md-0 ms-4">
                                                        <p class="mt-2"><?= strtoupper(str_replace('_', ' ', $step)) ?></p>
                                                    </div>
                                                </div>
                                                <?php $i++;
                                            }
                                        }
                                    } else {
                                        $last_reached_index = -1;
                                        foreach($status_order as $idx => $s) {
                                            if (in_array($s, $status_history_arr)) {
                                                $last_reached_index = $idx;
                                            }
                                        }

                                        $i = 1;
                                        foreach ($status_order as $index => $step) {
                                            $is_reached = $index <= $last_reached_index;
                                            $date = isset($status_history_dates[$step]) ? $status_history_dates[$step] : '';
                                            
                                            if ($is_reached) { ?>
                                                <div class="active d-md-block d-flex ms-md-0 ms-4 mb-md-0 mb-4 col-2 progressbar-box" id="step<?= $i ?>">
                                                    <div id="steps">
                                                        <div class="step done"><i class="fa fa-check"></i></div>
                                                    </div>
                                                    <div class="ms-md-0 ms-4">
                                                        <p class="mt-2"><?= strtoupper(str_replace('_', ' ', $step)) ?></p>
                                                        <?php if(!empty($date)) { ?>
                                                            <p><?= $date ?></p>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                 <div class="col-2 d-md-block d-flex ms-md-0 ms-4 mb-md-0 mb-4 progressbar-box" id="step<?= $i ?>">
                                                    <div id="steps">
                                                        <div class="step"><i class="ionicon-ellipse"></i></div>
                                                    </div>
                                                    <div class="ms-md-0 ms-4">
                                                       <p class="mt-2"><?= strtoupper(str_replace('_', ' ', $step)) ?></p>
                                                    </div>
                                                </div>
                                            <?php }
                                            $i++;
                                        }
                                    }
                                    ?>
                                </div>
                            <?php } ?>
                            <div class="row text-center ">
                                <?php
                                $status = ["awaiting", "received", "processed", "shipped", "delivered", "cancelled", "returned"];
                                $cancelable_till = $item['cancelable_till'];
                                $active_status = $item['active_status'];
                                $cancellable_index = array_search($cancelable_till, $status);
                                $active_index = array_search($active_status, $status);

                                $order_date = $row['order_items'][0]['status'][3][1];
                                if ($row['is_returnable'] && !$row['is_already_returned'] && isset($order_date) && !empty($order_date)) { ?>
                                    <?php
                                    $settings = get_settings('system_settings', true);
                                    $timestemp = strtotime($order_date);
                                    $date = date('Y-m-d', $timestemp);
                                    $today = date('Y-m-d');
                                    $return_till = date('Y-m-d', strtotime($order_date . ' + ' . $settings['max_product_return_days'] . ' days'));
                                    echo "<br>";
                                    if ($today < $return_till) { ?>
                                        <!-- <div class="col my-auto ">
                                            <h5 class="btn btn-primary">
                                                <a href="<?= base_url('my-account/order-details/' . $row['id']) ?>" class="update-order block buttons button-sm btn-6-3 text-white mt-3 m-0" data-status="returned" data-order-id="<?= $row['id'] ?>"><?= label('return', 'Return') ?></a>
                                            </h5>
                                        </div> -->
                                    <?php } ?>
                                <?php } ?>
                                <?php if ($row['payment_method'] == 'Bank Transfer' && $bank_transfer[0]['status'] == 2) { ?>
                                    <div class="col my-auto ">
                                        <h5 class="btn btn-primary">
                                            <a class="block button-sm buttons btn-6-5 text-white mt-3 m-0"
                                                href="<?= base_url('my-account/order-details/' . $row['id']) ?>"><?= label('send_bank_payment_receipt', 'Send Bank Payment Receipt') ?></i>
                                            </a>
                                        </h5>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php }
                } ?>
                <div class="text-center">
                    <?= (isset($links)) ? $links : '' ?>
                </div>
            </div>
        </div>
    </section>
</main>