<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header mt-4">

        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h4>View Order</h4>
                </div>
                <div class="col-sm-4 d-flex justify-content-end">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Orders</li>
                    </ol>

                </div>
            </div>

        </div>
        <!-- /.container-fluid -->

        <div class="container-fluid">
            <div class="row">
                <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="transaction_modal"
                    data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="user_name">Order Tracking</h5>
                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">

                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-info">
                                            <!-- form start -->
                                            <form class="form-horizontal " id="order_tracking_form"
                                                action="<?= base_url('admin/orders/update-order-tracking/'); ?>"
                                                method="POST" enctype="multipart/form-data">
                                                <input type="hidden" name="order_id" id="order_id">
                                                <input type="hidden" name="order_item_id" id="order_item_id">
                                                <div class="card-body pad">
                                                    <div class="form-group mt-2 ">
                                                        <label for="courier_agency">Courier Agency</label>
                                                        <input type="text" class="form-control mt-2"
                                                            name="courier_agency" id="courier_agency"
                                                            placeholder="Courier Agency" />
                                                    </div>
                                                    <div class="form-group mt-2 ">
                                                        <label for="tracking_id">Tracking Id</label>
                                                        <input type="text" class="form-control mt-2" name="tracking_id"
                                                            id="tracking_id" placeholder="Tracking Id" />
                                                    </div>
                                                    <div class="form-group mt-2 ">
                                                        <label for="url">URL</label>
                                                        <input type="text" class="form-control mt-2" name="url" id="url"
                                                            placeholder="URL" />
                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <button type="reset" class="btn btn-warning">Reset</button>
                                                        <button type="submit" class="btn btn-success"
                                                            id="submit_btn">Save</button>
                                                    </div>
                                                </div>
                                                <!-- /.card-body -->
                                            </form>
                                        </div>
                                        <!--/.card-->
                                    </div>
                                    <!--/.col-md-12-->
                                </div>
                                <!-- /.row -->

                            </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <!-- The time line -->
                    <section class="time-line-box text-center">
                        <div class="swiper-wrapper col-12">
                            <?php
                            $status = json_decode($order_detls[0]['status']);
                            $status_wise_class = [
                                'awaiting' => ['fa fa-xs fa-history', 'bg-red'],
                                'received' => ['fa fa-xs fa-level-down-alt', 'bg-indigo'],
                                'processed' => ['fa fa-xs fa-people-carry ', 'bg-navy'],
                                'shipped' => ['fa fa-xs fa-shipping-fast ', 'bg-yellow'],
                                'ready_to_pickup' => ['fa fa-xs fa-shipping-fast ', 'bg-yellow'],
                                'delivered' => ['fa fa-xs fa-user-check ', 'bg-success'],
                                'cancelled' => ['fa fa-xs fa-times-circle ', 'bg-red'],
                                'returned' => ['fa fa-xs fa-level-up-alt ', 'bg-orange'],
                            ];
                            foreach ($status as $row) {
                            ?>
                                <div class="swiper-slide">
                                    <div class="max-auto col-md-6 offset-md-3">
                                        <div class="<?= $status_wise_class[$row[0]][1] ?> pt-2 pb-2 rounded"> <span
                                                class="fa-lg"><i class="<?= $status_wise_class[$row[0]][0] ?>"></i></span>
                                        </div>
                                    </div>
                                    <div class="timestamp m-1"><small class="date"><i
                                                class="fas fa-clock"></i>&nbsp;<?= strtoupper($row[1]) ?> </small> </div>
                                    <div class="status text-bold"><span> <?= strtoupper($row[0]) ?> </span></div>
                                </div>
                            <?php } ?>

                        </div>
                    </section>
                </div>
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-body">
                            <table class="table">
                                <tr>
                                    <input type="hidden" name="hidden" id="order_id"
                                        value="<?= $order_detls[0]['id']; ?>">
                                    <th class="w-10px">ID</th>
                                    <td><?= $order_detls[0]['id']; ?></td>
                                </tr>
                                <tr>
                                    <th class="w-10px">Name</th>
                                    <td><?= "Account Holder Person : " . $order_detls[0]['uname'] . " | Order Recipient Person :  " . $order_detls[0]['user_name']; ?>
                                    </td>
                                </tr>
                                <?php if (isset($order_detls[0]['email']) && !empty($order_detls[0]['email']) && $order_detls[0]['email'] != '' && $order_detls[0]['email'] != ' ') { ?>
                                    <tr>
                                        <th class="w-10px">Email</th>
                                        <td><?= (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) ? str_repeat("X", strlen($order_detls[0]['email']) - 3) . substr($order_detls[0]['email'], -3) : $order_detls[0]['email']; ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <th class="w-10px">Contact</th>
                                    <?php
                                    $recipient_contact = '';
                                    if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                                        $length = strlen($order_detls[0]['recipient_contact']);
                                        if ($length >= 3) {
                                            $recipient_contact = str_repeat("X", $length - 3) . substr($order_detls[0]['recipient_contact'], -3);
                                        } else {
                                            $recipient_contact = str_repeat("X", $length);
                                        }
                                    } else {
                                        $recipient_contact = $order_detls[0]['recipient_contact'];
                                    } ?>
                                    <td><?= "Account Holder Contact : " . $mobile . " | Order Recipient Contact :  " . $recipient_contact; ?>
                                    </td>
                                </tr>
                                <?php if (isset($items[0]['product_type']) && $items[0]['product_type'] != 'digital_product') { ?>
                                    <?php if ($order_detls[0]['otp'] != 0) { ?>
                                        <tr>
                                            <th class="w-10px">OTP</th>
                                            <td><?= $order_detls[0]['otp']; ?></td>
                                        </tr>
                                <?php }
                                } ?>
                                <?php if (!empty($order_detls[0]['notes'])) { ?>
                                    <tr>
                                        <th class="w-10px">Order note</th>
                                        <td><?php echo $order_detls[0]['notes']; ?></td>
                                    </tr>
                                <?php } ?>

                                <?php if (!empty($order_detls[0]['attachments'])) {
                                    $order_attachment = json_decode($order_detls[0]['attachments']); ?>
                                    <tr>
                                        <th class="w-10px">Order Attachments</th>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <?php foreach ($order_attachment as $o_attachment) {
                                                        $file = new SplFileInfo($o_attachment);
                                                        $extension = $file->getExtension();
                                                        $image_extension = array('jpg', 'png', 'jpeg');
                                                        if (in_array($extension, $image_extension)) { ?>
                                                            <a href='<?= base_url($o_attachment) ?>' data-toggle="lightbox">
                                                                <img src=' <?= base_url($o_attachment) ?> '
                                                                    class="img-fluid rounded" height="100px" width="100px">
                                                            </a>
                                                        <?php } else { ?>
                                                            <a href='<?= base_url($o_attachment) ?>' target='_blank'>
                                                                <img src=' <?= base_url('assets/admin/images/doc-file.png') ?> '
                                                                    class="img-fluid rounded" height="100px" width="100px">
                                                            </a>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>

                                <?php if (isset($order_tracking) && !empty($order_tracking) && isset($order_detls[0]['active_status']) && $order_detls[0]['active_status'] != 'awaiting') { ?>
                                    <?php if (isset($shiprocket_settings['local_shipping_method']) && $shiprocket_settings['local_shipping_method'] == 1) { ?>
                                        <tr>
                                            <th class="w-10px">Order Tracking</th>
                                            <td>
                                                <a href="javascript:void(0)"
                                                    class="edit_order_tracking btn btn-success btn-xs mr-1 "
                                                    title="Order Tracking" data-order_id=' <?= $order_detls[0]['id']; ?>'
                                                    data-courier_agency=' <?= $order_tracking[0]['courier_agency'] ?> '
                                                    data-tracking_id=' <?= $order_tracking[0]['tracking_id'] ?> '
                                                    data-url=' <?= $order_tracking[0]['url'] ?> '
                                                    data-target="#transaction_modal" data-toggle="modal"><i
                                                        class="fa fa-map-marker-alt"></i> Click Here to View</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>

                                <tr>
                                    <th class="w-10px">Items</th>
                                    <td>
                                        <?php foreach ($items as $item) {
                                            if (isset($item['product_type']) && ($item['product_type'] != 'digital_product')) { ?>
                                                <?php if (isset($shiprocket_settings['shiprocket_shipping_method']) && $shiprocket_settings['shiprocket_shipping_method'] == 1) { ?>
                                                    <?php if (has_permissions('create', 'orders') || has_permissions('update', 'orders')) { ?>
                                                        <div class="row mb-1">
                                                            <div class="col-12">
                                                                <button type="button" disabled
                                                                    class="btn btn-primary float-right create_shiprocket_order"
                                                                    data-target="#order_parcel_modal" data-toggle="modal"> Create
                                                                    Shiprocket Order</button>
                                                            </div>
                                                        </div>
                                        <?php
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                        <?php $total = 0;
                                        $tax_amount = 0;
                                        echo '<div class="container-fluid row">';

                                        foreach ($items as $item) {

                                            $item['discounted_price'] = ($item['discounted_price'] == '') ? 0 : $item['discounted_price'];

                                            $total += $subtotal = ($item['quantity'] != 0 && ($item['discounted_price'] != '' && $item['discounted_price'] > 0) && $item['price'] > $item['discounted_price']) ? ($item['price'] - $item['discounted_price']) : ($item['price'] * $item['quantity']);
                                            $tax_amount += (int) $item['tax_amount'];
                                            $total += $subtotal = $tax_amount;
                                            $order_tracking_data = get_shipment_id($item['id'], $order_detls[0]['id']);

                                        ?>
                                            <div class="card col-md-3 col-sm-12 p-3 mb-2 bg-white rounded m-1 grow">
                                                <?php if (isset($shiprocket_settings['shiprocket_shipping_method']) && $shiprocket_settings['shiprocket_shipping_method'] == 1) { ?>
                                                    <div class="row mb-1">
                                                        <div class="col-md-7">
                                                            <?php
                                                            // Fetch the pickup location details
                                                            $pickup_location_name = fetch_details('products', ['id' => $item['product_id']], 'pickup_location');
                                                            ?>
                                                            <?php if (empty($order_tracking_data[0]['shipment_id']) && $item['product_type'] != 'digital_product') { ?>
                                                                <input type="checkbox" class="check_create_order"
                                                                    id="<?php echo isset($pickup_location_name[0]['pickup_location']) ? $pickup_location_name[0]['pickup_location'] : ''; ?>"
                                                                    <?php echo empty($pickup_location_name[0]['pickup_location']) ? 'disabled' : ''; ?> />
                                                            <?php } ?>
                                                        </div>
                                                        <?php if (isset($order_tracking_data[0]['shipment_id']) && !empty($order_tracking_data[0]['shipment_id']) && empty($order_tracking_data[0]['is_canceled']) && $order_tracking_data[0]['is_canceled'] != 1) { ?>
                                                            <div class="col-lg-12">
                                                                <span class="badge badge-success">Order created</span>
                                                            </div>
                                                        <?php } ?>
                                                        <?php if (isset($item['product_type']) && ($item['product_type'] != 'digital_product')) { ?>
                                                            <?php if (empty($order_tracking_data[0]['shipment_id'])) { ?>
                                                                <div class="col-lg-12">
                                                                    <span class="badge badge-primary">Order not created</span>
                                                                </div>
                                                        <?php }
                                                        } ?>

                                                        <?php if (isset($order_tracking_data[0]['is_canceled']) && $order_tracking_data[0]['is_canceled'] != 0) { ?>
                                                            <div class="col-lg-12">
                                                                <span class="badge badge-danger">Order cancelled</span>
                                                            </div>
                                                        <?php } ?>

                                                        <div class="col-12 mt-2">
                                                            <?php if (isset($order_tracking_data[0])) { ?>
                                                                <?php if (isset($order_tracking_data[0]['shipment_id']) && empty($order_tracking_data[0]['awb_code'])) { ?>
                                                                    <a href="" title="Generate AWB"
                                                                        class="btn btn-primary btn-xs mr-1 generate_awb" id=<?php print_r($order_tracking_data[0]['shipment_id']); ?>>AWB</a>
                                                                <?php } else { ?>
                                                                    <?php if (empty($order_tracking_data[0]['pickup_scheduled_date'])) { ?>
                                                                        <a href="" title="Send Pickup Request"
                                                                            class="btn btn-primary btn-xs mr-1 send_pickup_request"
                                                                            name=<?php print_r($order_tracking_data[0]['shipment_id']); ?>><i class="fas fa-shipping-fast "></i></a>
                                                                    <?php }
                                                                    if (isset($order_tracking_data[0]['is_canceled']) && $order_tracking_data[0]['is_canceled'] == 0) { ?>
                                                                        <a href="" title="Cancel Order"
                                                                            class="btn btn-primary btn-xs mr-1 cancel_shiprocket_order"
                                                                            name=<?php print_r($order_tracking_data[0]['shiprocket_order_id']); ?>><i
                                                                                class="fas fa-sync-alt"></i></a>
                                                                    <?php } ?>

                                                                    <?php if (isset($order_tracking_data[0]['label_url']) && !empty($order_tracking_data[0]['label_url'])) { ?>
                                                                        <a href="<?php print_r($order_tracking_data[0]['label_url']); ?>"
                                                                            title="Download Label"
                                                                            class="btn btn-primary btn-xs mr-1 download_label"><i
                                                                                class="fas fa-download"></i> Label</a>
                                                                    <?php } else { ?>
                                                                        <a href="" title="Generate Label"
                                                                            class="btn btn-primary btn-xs mr-1 generate_label" name=<?php print_r($order_tracking_data[0]['shipment_id']); ?>><i
                                                                                class="fas fa-tags"></i></a>
                                                                    <?php } ?>

                                                                    <?php if (isset($order_tracking_data[0]['invoice_url']) && !empty($order_tracking_data[0]['invoice_url'])) { ?>
                                                                        <a href="<?php print_r($order_tracking_data[0]['invoice_url']); ?>"
                                                                            title="Download Invoice"
                                                                            class="btn btn-primary btn-xs mr-1 download_invoice"><i
                                                                                class="fas fa-download"></i> Invoice</a>
                                                                    <?php } else { ?>
                                                                        <a href="" title="Generate Invoice"
                                                                            class="btn btn-primary btn-xs mr-1 generate_invoice" name=<?php print_r($order_tracking_data[0]['shiprocket_order_id']); ?>><i
                                                                                class="far fa-money-bill-alt"></i></a>
                                                                    <?php }
                                                                    if (isset($order_tracking_data[0]['awb_code']) && !empty($order_tracking_data[0]['awb_code'])) { ?>
                                                                        <a href="https://shiprocket.co/tracking/<?php echo $order_tracking_data[0]['awb_code']; ?>"
                                                                            target=" _blank" title="Track Order"
                                                                            class="btn btn-primary btn-xs mr-1 track_order" name=<?php print_r($order_tracking_data[0]['shiprocket_order_id']); ?>><i
                                                                                class="fas fa-map-marker-alt"></i></a>
                                                                    <?php } ?>

                                                                <?php } ?>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                <?php } ?>

                                                <?php
                                                if (empty($order_tracking_data[0]['shipment_id']) && $order_tracking_data[0]['shipment_id'] == "") {
                                                ?>
                                                    <div class="row mt-2">
                                                        <?php if (isset($items[0]['product_type']) && $items[0]['product_type'] == 'digital_product' && $items[0]['is_sent'] == 0 && $item['download_allowed'] == 0) { ?>
                                                            <div class="col-md-7 text-center">
                                                                <select class="form-control-sm w-100 mb-2">
                                                                    <option value="1">Mail Sent</option>
                                                                </select>
                                                            </div>
                                                            <?php
                                                            $user_details = fetch_details('order_items', ['id' => $item['id']], 'order_id');
                                                            $user_email = fetch_details('orders', ['id' => $user_details[0]['order_id']], 'email');
                                                            ?>
                                                            <div class="col-md-5  d-flex align-items-center">
                                                                <a href="javascript:void(0);" title="Update status"
                                                                    data-id='<?= $item['id'] ?>'
                                                                    class="btn btn-primary btn-xs update_mail_status_admin mr-1"><i
                                                                        class="far fa-arrow-alt-circle-up"></i></a>
                                                                <a href="javascript:void(0)"
                                                                    class="edit_btn btn btn-primary btn-xs mr-1 mb-1" title="Edit"
                                                                    data-id="<?= $item['id'] ?>" data-url="admin/orders/"><i
                                                                        class="fas fa-paper-plane"></i></a>
                                                                <a href="https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=<?= $user_email[0]['email'] ?> "
                                                                    class="btn btn-danger btn-xs mr-1 mb-1" target="_blank"><i
                                                                        class="fab fa-google"></i></a>
                                                            </div>

                                                        <?php } ?>
                                                    </div>

                                                    <?php if (isset($items[0]['product_type']) && $items[0]['product_type'] == 'digital_product' && $items[0]['is_sent'] == 1) { ?>
                                                        <div class="row mt-2 order_status">
                                                            <div class="col-md-7 text-center">

                                                                <select class="form-control-sm w-100">
                                                                    <?php if (isset($items[0]['product_type']) && $items[0]['product_type'] == 'digital_product') { ?>
                                                                        <option value="delivered"
                                                                            <?= (strtolower($item['active_status']) == 'delivered') ? 'selected' : '' ?>>
                                                                            delivered</option>
                                                                    <?php } else { ?>
                                                                        <option value="processed"
                                                                            <?= (strtolower($item['active_status']) == 'processed') ? 'selected' : '' ?>>
                                                                            Processed</option>

                                                                        <?php if ($order_detls[0]['is_local_pickup'] == 0) { ?>
                                                                            <option value="shipped"
                                                                                <?= (strtolower($item['active_status']) == 'shipped') ? 'selected' : '' ?>>
                                                                                Shipped</option>
                                                                        <?php } else { ?>
                                                                            <option value="ready_to_pickup"
                                                                                <?= (strtolower($item['active_status']) == 'ready_to_pickup') ? 'selected' : '' ?>>
                                                                                Ready To Pickup</option>
                                                                        <?php } ?>
                                                                        <option value="delivered"
                                                                            <?= (strtolower($item['active_status']) == 'delivered') ? 'selected' : '' ?>>
                                                                            delivered</option>
                                                                        <option value="returned"
                                                                            <?= (strtolower($item['active_status']) == 'returned') ? 'selected' : '' ?>>
                                                                            Return</option>
                                                                        <option value="cancelled"
                                                                            <?= (strtolower($item['active_status']) == 'cancelled') ? 'selected' : '' ?>>
                                                                            Cancel</option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-5 d-flex align-items-center">

                                                                <a href="javascript:void(0);" title="Update status"
                                                                    data-id='<?= $item['id'] ?>'
                                                                    class="btn btn-primary btn-xs update_status_admin mr-1"><i
                                                                        class="far fa-arrow-alt-circle-up"></i></a>
                                                                <a href=" <?= BASE_URL('admin/product/view-product?edit_id=' . $item['product_id'] . '') ?> "
                                                                    title="View Product" class="btn btn-primary btn-xs mr-1"><i
                                                                        class="fa fa-eye"></i></a>
                                                                <?php $transaction_data = fetch_details('transactions', ['order_item_id' => $item['id']], 'txn_id,amount');
                                                                if ((($order_detls[0]['payment_method'] == 'Flutterwave') || ($order_detls[0]['payment_method'] == 'RazorPay' || $order_detls[0]['payment_method'] == 'razorpay' || $order_detls[0]['payment_method'] == 'Razorpay') || ($order_detls[0]['payment_method'] == 'Paystack')) && ($item['active_status'] == 'cancelled' || $item['active_status'] == 'returned')) { ?>
                                                                    <a href="javascript:void(0)"
                                                                        class="edit_order_refund btn shipped-box btn-xs mr-1 "
                                                                        title="Refund" data-order_id=' <?= $order_detls[0]['id']; ?>'
                                                                        data-order_item_id=' <?= $item['id'] ?>'
                                                                        data-txn_id=' <?= $transaction_data[0]['txn_id'] ?>'
                                                                        data-txn_amount=' <?= $transaction_data[0]['amount'] ?>'
                                                                        data-target="#refund_modal" data-toggle="modal"><i
                                                                            class="fa fa-undo"></i></a>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                <?php
                                                    }
                                                } ?>
                                              <?php
                                               $image = (!empty($item['product_image'])) 
                                                   ? base_url($item['product_image']) 
                                                   : base_url(NO_IMAGE);
                                               ?>
                                               
                                               <div class="order-product-image">
                                                   <a href="<?= $image ?>" data-toggle="lightbox" data-gallery="order-images">
                                                       <img src="<?= $image ?>" class="h-75">
                                                   </a>
                                               </div>
                                                <div><span class="text-bold">Product Type :
                                                    </span><small><?= ucwords(str_replace('_', ' ', $item['product_type'])); ?>
                                                    </small></div>
                                                <div><span class="text-bold">Variant ID :
                                                    </span><?= $item['product_variant_id'] ?> </div>
                                                <?php if (isset($item['product_variants']) && !empty($item['product_variants'])) { ?>
                                                    <div><span class="text-bold">Variants :
                                                        </span><?= str_replace(',', ' | ', $item['product_variants'][0]['variant_values']) ?>
                                                    </div>
                                                <?php } ?>
                                                <div><span class="text-bold">Name : </span><small><?= $item['pname'] ?>
                                                    </small></div>
                                                <div><span class="text-bold">Quantity : </span><?= $item['quantity'] ?>
                                                </div>
                                                <div><span class="text-bold">Price : </span><?= $item['price'] ?></div>
                                                <div><span class="text-bold">Discounted Prices : </span>
                                                    <?= $item['discounted_price'] ?> </div>
                                                <div><span class="text-bold">Subtotal :
                                                    </span><?= $item['price'] * $item['quantity'] ?> </div>
                                                <?php
                                                if (isset($item['product_type']) && ($item['product_type'] != 'digital_product')) { ?>
                                                    <?php if (isset($item['pickup_location']) && !empty($item['pickup_location'])) { ?>
                                                        <div><span class="text-bold">Pickup Location :
                                                            </span><?= $item['pickup_location'] ?> </div>
                                                    <?php } ?>
                                                    <?php if (isset($order_tracking_data[0]['shipment_id'])) { ?>
                                                        <div><span class="text-bold">Shipment Id :
                                                            </span><?= $order_tracking_data[0]['shipment_id'] ?>
                                                        </div>
                                                <?php }
                                                }
                                                ?>
                                                <?php
                                                $badges = [
                                                    "awaiting" => "badge bg-secondary",
                                                    "received" => "badge bg-primary",
                                                    "processed" => "badge bg-info",
                                                    "shipped" => "badge bg-warning",
                                                    "ready_to_pickup" => "badge bg-warning",
                                                    "delivered" => "badge bg-success",
                                                    "returned" => "badge bg-danger",
                                                    "cancelled" => "badge bg-danger",
                                                    "return_request_approved" => "badge bg-success",
                                                    "return_request_decline" => "badge bg-danger",
                                                    "return_request_pending" => "badge bg-warning",
                                                    "draft" => "badge bg-secondary"
                                                ];

                                                $badgeClass = array_key_exists($item['active_status'], $badges) ? $badges[$item['active_status']] : 'secondary';
                                                ?>
                                                <?php if (isset($item['updated_by'])) { ?>
                                                    <div><span class="text-bold">Updated By : </span><?= $item['updated_by'] ?>
                                                    </div>
                                                <?php } ?>
                                                <div><span class="text-bold">Active Status : </span> <span
                                                        class="badge badge-<?= $badges[$item['active_status']] ?>"> <small
                                                            class="text-white active_class_badge"><?php print_r(str_replace('_', ' ', $item['active_status'])) ?></small></span>
                                                </div>
                                                <?php if (!empty($item['attachment'])) { ?>
                                                    <div class="d-flex gap-3 mt-2">
                                                        <div><span class="text-bold">Attachment :</span></div>

                                                        <div class="order-product-image">
                                                            <a href=' <?= base_url() . $item['attachment'] ?>'
                                                                data-toggle='lightbox' data-gallery='order-images'> <img
                                                                    src='<?= base_url() . $item['attachment'] ?>'
                                                                    class='h-75'></a>
                                                        </div>
                                                    </div>
                                                <?php } ?>

                                            </div>
                                        <?php

                                        }
                                        echo '</div>';
                                        ?>
                                        <div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="w-10px">Total(<?= $settings['currency'] ?>)</th>
                                    <td id='amount'><?php echo str_replace('-', '', $order_detls[0]['order_total']);
                                                    $total = $order_detls[0]['order_total'];
                                                    ?></td>
                                </tr>

                                <tr class="d-none">
                                    <th class="w-10px">Tax(<?= $settings['currency'] ?>)</th>
                                    <td id='amount'><?= $tax_amount; ?></td>
                                </tr>
                                <?php foreach ($items as $item) {
                                }
                                if (isset($item['product_type']) && ($item['product_type'] != 'digital_product')) { ?>
                                    <?php if ($order_detls[0]['is_local_pickup'] == 0) { ?>
                                        <tr>
                                            <th class="w-10px">Delivery Charge(<?= $settings['currency'] ?>)</th>
                                            <td id='delivery_charge'>
                                                <?= $order_detls[0]['delivery_charge'];
                                                $total = $total + (float) $order_detls[0]['delivery_charge']; ?>
                                            </td>
                                        </tr>
                                <?php }
                                }
                                ?>

                                <tr>
                                    <th class="w-10px">Wallet Balance(<?= $settings['currency'] ?>)</th>
                                    <td><?php echo $order_detls[0]['wallet_balance'];
                                        $total = $total - (floatval($order_detls[0]['wallet_balance']));
                                        if (trim(strtolower($order_detls[0]['payment_method'])) != 'cod' && $order_detls[0]['payment_method'] != 'bank_transfer') {
                                            /* If any other payment methods are used like razorpay, paytm, flutterwave or stripe then 
                                        obviously customer would have paid complete amount so making total_payable = 0 */
                                            $total = 0;
                                        }
                                        ?></td>
                                </tr>

                                <input type="hidden" name="total_amount" id="total_amount"
                                    value="<?php echo $order_detls[0]['order_total'] + (float) $order_detls[0]['delivery_charge'] ?>">
                                <input type="hidden" name="final_amount" id="final_amount"
                                    value="<?php echo $order_detls[0]['final_total']; ?>">

                                <tr>
                                    <th class="w-10px">Promo Code Discount (<?= $settings['currency'] ?>)</th>
                                    <td><?php echo $order_detls[0]['promo_discount'];
                                        $total = ($total > 0) ? floatval($total - floatval($order_detls[0]['promo_discount'])) : $total; ?>
                                        <?= (!empty(trim($order_detls[0]['promo_code']))) ? "(" . $order_detls[0]['promo_code'] . ")" : ""; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="w-10px">Bulk Discount (<?= $settings['currency'] ?>)</th>
                                    <td><?php echo $order_detls[0]['bulk_discount'];
                                        $total = ($total > 0) ? floatval($total - floatval($order_detls[0]['bulk_discount'])) : $total; ?>
                                    </td>
                                </tr>
                               
                                <!-- Custom Charges -->
                                
                             
                                
                              <?php if (!empty($order_detls[0]['custom_charges'])): ?>
                                    <?php 
                                    $custom_charges = json_decode($order_detls[0]['custom_charges'], true);
                                    if (is_array($custom_charges) && count($custom_charges) > 0): 
                                    ?>
                                    <?php foreach ($custom_charges as $c): ?>
                                       <tr>
                                        <th class="w-10px">
                                            <?= htmlspecialchars(ucwords(str_replace('_', ' ', $c['name']))) ?>
                                            (<?= $settings['currency'] ?>)
                                        </th>
                                        <td>
                                            <?= number_format($c['amount'], 2) ?>
                                            <?php
                                            // Add custom charge to total
                                            $total = floatval($total) + floatval($c['amount']);
                                            ?>
                                        </td>
                                    </tr>

                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php
                                if (isset($order_detls[0]['discount']) && $order_detls[0]['discount'] > 0 && $total > 0) {
                                    $discount = $order_detls[0]['total_payable'] * ($order_detls[0]['discount'] / 100);
                                    $total = round($order_detls[0]['total_payable'] - $discount, 2);
                                } ?>
                                <tr>
                                    <th class="w-10px">Payable Total(<?= $settings['currency'] ?>)</th>
                                    <td><input type="text" class="form-control" id="final_total" name="final_total"
                                            value="<?= str_replace('-', '', $order_detls[0]['total_payable']); ?>"
                                            disabled></td>
                                </tr>

                                <?php if (isset($shiprocket_settings['local_shipping_method']) && $shiprocket_settings['local_shipping_method'] == 1 && $items[0]['product_type'] != 'digital_product') { ?>
                                    <?php if ($order_detls[0]['is_local_pickup'] == 0) { ?>
                                        <tr>
                                            <th>Deliver By</th>
                                            <td>
                                                <select id="deliver_by" name="deliver_by"
                                                    class="form-control col-md-7 col-xs-12" required
                                                    <?= (isset($order_detls[0]['active_status']) && in_array($order_detls[0]['active_status'], ['draft', 'awaiting'])) ? 'disabled' : ''; ?>>

                                                    <option value="">Select Delivery Boy</option>
                                                    <?php
                                                    foreach ($delivery_res as $row) {
                                                        $selected = (!empty($order_detls[0]['delivery_boy_id']) && $order_detls[0]['delivery_boy_id'] == $row['user_id']) ? 'selected' : '';
                                                    ?>
                                                        <option value="<?= $row['user_id'] ?>" <?= $selected ?>>
                                                            <?= $row['username'] ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                        </tr>

                                <?php }
                                } ?>
                                <tr>
                                    <th class="w-10px">Payment Method</th>
                                    <td><?php echo ucwords(str_replace('_', ' ', $order_detls[0]['payment_method'])); ?></td>
                                </tr>
                                <?php if (!empty($bank_transfer)) { ?>
                                    <tr>
                                        <th class="w-10px">Bank Transfers</th>
                                        <td>
                                            <div class="col-md-6">
                                                <?php $status = ["history", "ban", "check"]; ?>
                                                <a class="btn btn-primary btn-xs mr-1 mb-1 " title="Current Status: Pending"
                                                    href="javascript:void(0)" data-id="<?= $order_detls[0]['id']; ?>"><i
                                                        class="fa fa-<?= $status[$bank_transfer[0]['status']] ?>"></i></a>
                                                <?php $i = 1;
                                                foreach ($bank_transfer as $row1) { ?>
                                                    <small>[<a href="<?= base_url() . $row1['attachments'] ?>"
                                                            target="_blank">Attachment <?= $i ?> </a>] </small>
                                                    <a class="delete-receipt btn btn-danger btn-xs mr-1 mb-1" title="Delete"
                                                        href="javascript:void(0)" data-id="<?= $row1['id']; ?>"><i
                                                            class="fa fa-trash"></i></a>
                                                <?php $i++;
                                                } ?>
                                                <select name="update_receipt_status" id="update_receipt_status"
                                                    class="form-control status" data-id="<?= $order_detls[0]['id']; ?>"
                                                    data-user_id="<?= $order_detls[0]['user_id']; ?>">
                                                    <option value=''>Select Status</option>
                                                    <option value="1" <?= (isset($bank_transfer[0]['status']) && $bank_transfer[0]['status'] == 1) ? "selected" : ""; ?>>
                                                        Rejected</option>
                                                    <option value="2" <?= (isset($bank_transfer[0]['status']) && $bank_transfer[0]['status'] == 2) ? "selected" : ""; ?>>
                                                        Accepted</option>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <?php if (isset($items[0]['product_type']) && $items[0]['product_type'] != 'digital_product') { ?>
                                    <tr>
                                        <th class="w-10px">Address</th>
                                        <td><?= $order_detls[0]['address']; ?></td>
                                    </tr>

                                    <tr>
                                        <th class="w-10px">Delivery Date & Time</th>
                                        <td><?php echo (!empty($order_detls[0]['delivery_date']) && $order_detls[0]['delivery_date'] != NUll) ? date('d-M-Y', strtotime($order_detls[0]['delivery_date'])) . " - " . $order_detls[0]['delivery_time'] : "Anytime"; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="w-10px">Order Date</th>
                                        <td><?php echo date('d-M-Y', strtotime($order_detls[0]['date_added'])); ?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <select name="status" id="status" class="form-control" data-isjson="true"
                                            data-orderid="<?= isset($order_detls[0]['id']) ? $order_detls[0]['id'] : ''; ?>"
                                            <?= (isset($order_detls[0]['active_status']) && in_array($order_detls[0]['active_status'], ['draft', 'awaiting'])) ? 'disabled' : ''; ?>>

                                            <?php if (isset($items[0]['product_type']) && $items[0]['product_type'] == 'digital_product') { ?>
                                                <option value="awaiting" <?= (isset($order_detls[0]['active_status']) && $order_detls[0]['active_status'] == 'awaiting') ? 'selected' : ''; ?>>
                                                    Awaiting
                                                </option>
                                                <option value="processed" <?= (isset($order_detls[0]['active_status']) && $order_detls[0]['active_status'] == 'processed') ? 'selected' : ''; ?>>
                                                    Processed
                                                </option>
                                                <option value="delivered" <?= (isset($order_detls[0]['active_status']) && strtolower($order_detls[0]['active_status']) == 'delivered') ? 'selected' : ''; ?>>
                                                    Delivered
                                                </option>
                                            <?php } else { ?>
                                                <?php if (isset($order_detls[0]['payment_method']) && $order_detls[0]['payment_method'] != 'bank_transfer') { ?>
                                                    <option value="received" <?= (isset($order_detls[0]['active_status']) && $order_detls[0]['active_status'] == 'received') ? 'selected' : ''; ?>>
                                                        Received
                                                    </option>
                                                <?php } ?>
                                                <option value="processed" <?= (isset($order_detls[0]['active_status']) && $order_detls[0]['active_status'] == 'processed') ? 'selected' : ''; ?>>
                                                    Processed
                                                </option>
                                                <?php if (isset($order_detls[0]['is_local_pickup']) && $order_detls[0]['is_local_pickup'] == 0) { ?>
                                                    <option value="shipped" <?= (isset($order_detls[0]['active_status']) && $order_detls[0]['active_status'] == 'shipped') ? 'selected' : ''; ?>>
                                                        Shipped
                                                    </option>
                                                <?php } else { ?>
                                                    <option value="ready_to_pickup" <?= (isset($order_detls[0]['active_status']) && $order_detls[0]['active_status'] == 'ready_to_pickup') ? 'selected' : ''; ?>>
                                                        Ready To Pickup
                                                    </option>
                                                <?php } ?>
                                                <option value="delivered" <?= (isset($order_detls[0]['active_status']) && $order_detls[0]['active_status'] == 'delivered') ? 'selected' : ''; ?>>
                                                    Delivered
                                                </option>
                                                <option value="cancelled" <?= (isset($order_detls[0]['active_status']) && $order_detls[0]['active_status'] == 'cancelled') ? 'selected' : ''; ?>>
                                                    Cancelled
                                                </option>
                                                <option value="returned" <?= (isset($order_detls[0]['active_status']) && $order_detls[0]['active_status'] == 'returned') ? 'selected' : ''; ?>>
                                                    Returned
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>


                                <?php if ($order_detls[0]['is_local_pickup'] == 1 && !in_array(strtolower($order_detls[0]['active_status']), ['delivered', 'cancelled', 'returned'])) {
                                ?>
                                    <tr>
                                        <td colspan="2">
                                            <h5><b>Local / Store Pickup</b></h5>
                                            <hr>
                                            <div class="row">
                                                <div class="form-group col-md-5">
                                                    <label for="">Admin Notes</label>
                                                    <input type="text" class="form-control" name="seller_notes"
                                                        id="seller_notes" placeholder="Admin Notes"
                                                        value="<?= $order_detls[0]['seller_notes'] ?>" />
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <label for="">Pickup Time</label>
                                                    <?php $dateTime = new DateTime($order_detls[0]['pickup_time']);
                                                    $date = $dateTime->format('Y-m-d');
                                                    $time = $dateTime->format('H:i');
                                                    $pickup_time = $date . 'T' . $time; ?>
                                                    <div class="col-sm-10">
                                                        <input type="datetime-local" class="form-control" name="pickup_time"
                                                            id="pickup_time" value="<?= $pickup_time ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <a href="https://api.whatsapp.com/send?phone= <?= ($order_detls[0]['mobile'] != '' && isset($order_detls[0]['mobile'])) ? $order_detls[0]['mobile'] : ((!defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) ? str_repeat("X", strlen($mobile_data[0]['mobile']) - 3) . substr($mobile_data[0]['mobile'], -3) : $mobile_data[0]['mobile']) ?> &amp;text=Hello <?= $order_detls[0]['uname'] ?>, Your order with ID : <?= $order_detls[0]['order_id'] ?> and is <?= $order_detls[0]['oi_active_status'] ?>. Please take a note of it. If you have further queries feel free to contact us. Thank you."
                                                target="_blank" title="Send Whatsapp Notification"
                                                class="btn btn-success"><ion-icon class="align-bottom fs-3"
                                                    name="logo-whatsapp"></ion-icon> Send Whatsapp Notification</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <button type="reset" class="btn btn-warning">Reset</button>
                                            <button type="submit" class="btn btn-success update_order"
                                                id="submit_btn">Update Order</button>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!--/.card-->
                </div>
                <!--/.col-md-12-->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="refund_modal" data-backdrop="static"
    data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="user_name">Payment Refund</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">

                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-info">
                            <!-- form start -->
                            <form class="form-horizontal " id="refund_form"
                                action="<?= base_url('admin/orders/refund_payment'); ?>" method="POST"
                                enctype="multipart/form-data">
                                <div class="form-group">
                                    <input type="hidden" name=" <?php echo $this->security->get_csrf_token_name(); ?>"
                                        value="<?php echo $this->security->get_csrf_hash(); ?>" />
                                    <input type="hidden" name="item_id" id="item_id">
                                    <input type="hidden" name="payment_method" id="refund_payment_method">
                                </div>
                                <div class="card-body pad">
                                    <div class="form-group ">
                                        <label for="transaction_id">Transaction Id</label>
                                        <input type="text" class="form-control" name="transaction_id"
                                            id="transaction_id" placeholder="Transaction Id" disabled />
                                    </div>
                                    <div class="form-group ">
                                        <label for="txn_amount">Amount</label>
                                        <input type="text" class="form-control" name="txn_amount" id="txn_amount"
                                            placeholder="Amount" disabled />
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-secondary" id="submit_btn">Refund</button>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </form>
                        </div>
                        <!--/.card-->
                    </div>
                    <!--/.col-md-12-->
                </div>
                <!-- /.row -->

            </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for shiprocket order parcel -->

<section class="content-header">
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="order_parcel_modal"
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Shipprocket Order Parcel</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">

                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-info">
                                <!-- form start -->
                                <form class="form-horizontal " id="shiprocket_order_parcel_form"
                                    action="<?= base_url('admin/orders/create_shiprocket_order/'); ?>" method="POST">

                                    <?php
                                    $total_items = count($items);
                                    ?>
                                    <div class="card-body pad">
                                        <div class="form-group">
                                            <input type="hidden"
                                                name=" <?php echo $this->security->get_csrf_token_name(); ?>"
                                                value="<?php echo $this->security->get_csrf_hash(); ?>" />
                                            <input type="hidden" id="order_id" name="order_id"
                                                value="<?php print_r($order_detls[0]['id']); ?>" />
                                            <input type="hidden" name="user_id" id="user_id"
                                                value="<?php echo $order_detls[0]['user_id']; ?>" />
                                            <input type="hidden" name="total_order_items" id="total_order_items"
                                                value="<?php echo $total_items; ?>" />
                                            <textarea id="order_items" name="order_items[]"
                                                hidden><?= json_encode($items, JSON_FORCE_OBJECT); ?></textarea>
                                        </div>
                                        <div class="mt-1 p-2 bg-danger text-white rounded">
                                            <p><b>Note:</b> Make your pickup location associated with the order is
                                                verified from <a
                                                    href="https://app.shiprocket.in/company-pickup-location?redirect_url="
                                                    target="_blank" class="text-decoration-underline color-white">
                                                    Shiprocket Dashboard </a> and then in <a
                                                    href="<?php base_url('admin/Pickup_location/manage-pickup-locations'); ?>"
                                                    target="_blank" class="text-decoration-underline color-white"> admin
                                                    panel </a>. If it is not verified you will not be able to generate
                                                AWB later on.</p>
                                        </div>
                                        <div class="form-group row mt-4">
                                            <div class="col-4">
                                                <label for="txn_amount">Pickup location</label>
                                            </div>
                                            <div class="col-8">
                                                <input type="text" class="form-control" name="pickup_location"
                                                    id="pickup_location" placeholder="Pickup Location" value=""
                                                    readonly />
                                            </div>
                                        </div>
                                        <div class="form-group row mt-3">
                                            <div class="col-6">
                                                <label for="txn_amount">Total Weight of Box</label>
                                            </div>
                                        </div>
                                        <div class="form-group row mt-4">
                                            <div class="col-3">
                                                <label for="parcel_weight" class="control-label col-md-12">Weight
                                                    <small>(kg)</small> <span
                                                        class='text-danger text-xs'>*</span></label>
                                                <input type="number" class="form-control" name="parcel_weight"
                                                    placeholder="Parcel Weight" id="parcel_weight" value="" step=".01"
                                                    oninput="this.value = this.value < 0 ? '' : this.value">
                                            </div>
                                            <div class="col-3">
                                                <label for="parcel_height" class="control-label col-md-12">Height
                                                    <small>(cms)</small> <span
                                                        class='text-danger text-xs'>*</span></label>
                                                <input type="number" class="form-control" name="parcel_height"
                                                    placeholder="Parcel Height" id="parcel_height" value="" min="1"
                                                    oninput="this.value = this.value < 0 ? '' : this.value">
                                            </div>
                                            <div class="col-3">
                                                <label for="parcel_breadth" class="control-label col-md-12">Breadth
                                                    <small>(cms)</small> <span
                                                        class='text-danger text-xs'>*</span></label>
                                                <input type="number" class="form-control" name="parcel_breadth"
                                                    placeholder="Parcel Breadth" id="parcel_breadth" value="" min="1"
                                                    oninput="this.value = this.value < 0 ? '' : this.value">
                                            </div>
                                            <div class="col-3">
                                                <label for="parcel_length" class="control-label col-md-12">Length
                                                    <small>(cms)</small> <span
                                                        class='text-danger text-xs'>*</span></label>
                                                <input type="number" class="form-control" name="parcel_length"
                                                    placeholder="Parcel Length" id="parcel_length" value="" min="1"
                                                    oninput="this.value = this.value < 0 ? '' : this.value">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-success create_shiprocket_parcel">Create
                                            Order</button>
                                    </div>
                                    <!-- /.card-body -->

                                </form>
                            </div>
                            <!--/.card-->
                        </div>
                        <!--/.col-md-12-->
                    </div>
                    <!-- /.row -->
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div id="product_faq_value_id" class="modal fade edit-modal-lg " tabindex="-1" role="dialog"
                aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-m ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Manage Digital Product</h5>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">

                            </button>
                        </div>

                        <div class="modal-body ">
                            <form class="form-horizontal form-submit-event"
                                action="<?= base_url('admin/orders/send_digital_product'); ?>" method="POST"
                                enctype="multipart/form-data">

                                <div class="card-body">
                                    <input type="hidden" name="order_id" value="<?= $this->input->get('edit_id') ?>">
                                    <div class="row form-group">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="product_name">Customer Email-ID </label>
                                                <input type="text" class="form-control" id="email" name="email"
                                                    value="<?= $fetched[0]['email'] ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="product_name">Message </label>
                                                <input type="text" class="form-control" id="message"
                                                    placeholder="Enter Message for email" name="message" value="">
                                            </div>
                                        </div>
                                        <div class="col-12 mt-2" id="digital_media_container">
                                            <label for="image" class="ml-2">File <span
                                                    class='text-danger text-sm'>*</span></label>
                                            <div class='col-md-6'><a
                                                    class="uploadFile img btn btn-primary text-white btn-sm"
                                                    data-input='pro_input_file' data-isremovable='1'
                                                    data-media_type='archive,document'
                                                    data-is-multiple-uploads-allowed='0' data-toggle="modal"
                                                    data-target="#media-upload-modal" value="Upload Photo"><i
                                                        class='fa fa-upload'></i> Upload</a></div>
                                            <div class="container-fluid row image-upload-section">
                                                <div
                                                    class="col-md-6 col-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image d-none">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success mt-3" id="submit_btn"
                                        value="Save"><?= labels('send_mail', 'Send Mail') ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>


    <div class='conatiner-fluid'>
        <div id="product_faq_value_id" class="modal fade edit-modal-lg " tabindex="-1" role="dialog"
            aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Manage Digital Product</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">

                        </button>
                    </div>
                    <?php
                    $user_details = fetch_details('order_items', ['id' => $item['id']], 'user_id');
                    $user_name = fetch_details('users', ['id' => $user_details[0]['user_id']], 'username');
                    ?>
                    <div class="modal-body ">
                        <form class="form-horizontal form-submit-event"
                            action="<?= base_url('admin/orders/send_digital_product'); ?>" method="POST"
                            enctype="multipart/form-data">


                            <div class="card-body">
                                <input type="hidden" name="order_id" value="<?= $order_detls[0]['id'] ?>">
                                <input type="hidden" name="order_item_id" value="<?= $item['id'] ?>">
                                <input type="hidden" name="username" value="<?= $user_name[0]['username'] ?>">
                                <div class="row form-group">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="product_name">Customer Email-ID </label>
                                            <input type="text" class="form-control" id="email" name="email" value=""
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="subject">Subject </label>
                                            <input type="text" class="form-control" id="subject"
                                                placeholder="Enter Subject for email" name="subject" value="">
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="product_name">Message </label>
                                            <textarea type="text" class="form-control textarea addr_editor" rows="6"
                                                placeholder="Message for Email" name="message"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-2" id="digital_media_container">
                                        <label for="image" class="ml-2">File <span
                                                class='text-danger text-sm'>*</span></label>
                                        <div class='col-md-6'><a
                                                class="uploadFile img btn btn-primary text-white btn-sm"
                                                data-input='pro_input_file' data-isremovable='1'
                                                data-media_type='archive,document' data-is-multiple-uploads-allowed='0'
                                                data-toggle="modal" data-target="#media-upload-modal"
                                                value="Upload Photo"><i class='fa fa-upload'></i> Upload</a></div>
                                        <div class="container-fluid row image-upload-section">
                                            <div
                                                class="col-md-6 col-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image d-none">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success mt-3" id="submit_btn"
                                    value="Save"><?= labels('send_mail', 'Send Mail') ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
