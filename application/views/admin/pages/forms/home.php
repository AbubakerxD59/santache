<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid p-3">
            <div class="row">
                <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="transaction_modal"
                    data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog ">
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
                                                <div class="card-body pad">
                                                    <div class="form-group mt-2">
                                                        <label for="courier_agency">Courier Agency</label>
                                                        <input type="text" class="form-control mt-2"
                                                            name="courier_agency" id="courier_agency"
                                                            placeholder="Courier Agency" />
                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <label for="tracking_id">Tracking Id</label>
                                                        <input type="text" class="form-control mt-2" name="tracking_id"
                                                            id="tracking_id" placeholder="Tracking Id" />
                                                    </div>
                                                    <div class="form-group mt-2">
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
                <div class="col-xl-3 col-lg-6 col-md-6 col-12">

                    <a href="<?= base_url('admin/orders/') ?>" class="text-decoration-none">
                        <div class="card card-style">
                            <div class="card-body">
                                <div class="media d-flex align-items-center justify-content-md-between">
                                    <div class="align-self-center text-primary card-icon-div">
                                        <i class="ion-icon-cart4-outline display-4"></i>
                                    </div>
                                    <div class="media-body text-end <?= ($current_url == base_url('admin/orders')) ?>">
                                        <span class="text-muted text-bold-500 card-h5-style mx-2">Orders</span>
                                        <h3 class="text-bold-600 m-4 card-h3-style"><?= $order_counter ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>


                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                    <a href="<?= base_url('admin/system-users/') ?>" class="text-decoration-none">
                        <div class="card card-style">
                            <div class="card-body">
                                <div class="media d-flex align-items-center justify-content-md-between">

                                    <div class="align-self-center text-primary card-icon-div">
                                        <i class="ion-ios-personadd-outline display-4"></i>
                                    </div>

                                    <div
                                        class="media-body text-end <?= ($current_url == base_url('admin/system-users')) ?>">
                                        <span class="text-muted text-bold-500 card-h5-style mx-2">New Sign Up</span>
                                        <h3 class="text-bold-600 m-4 card-h3-style"><?= $user_counter ?></h3>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                    <a href="<?= base_url('admin/delivery-boys/manage-delivery-boy') ?>" class="text-decoration-none">
                        <div class="card card-style">
                            <div class="card-body">
                                <div class="media d-flex align-items-center justify-content-md-between">

                                    <div class="align-self-center text-primary card-icon-div">
                                        <i class="ion-ios-people-outline display-4"></i>
                                    </div>

                                    <div
                                        class="media-body text-end <?= ($current_url == base_url('admin/system-users')) ?>">
                                        <span class="text-muted text-bold-500 card-h5-style mx-2">Delivery Boys</span>
                                        <h3 class="text-bold-600 m-4 card-h3-style"><?= $delivery_boy_counter ?></h3>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                    <a href="<?= base_url('admin/product/') ?>" class="text-decoration-none">
                        <div class="card card-style">
                            <div class="card-body">
                                <div class="media d-flex align-items-center justify-content-md-between">

                                    <div class="align-self-center text-primary card-icon-div">
                                        <i class="ion-ios-albums-outline display-4"></i>
                                    </div>

                                    <div
                                        class="media-body text-end <?= ($current_url == base_url('admin/system-users')) ?>">
                                        <span class="text-muted text-bold-500 card-h5-style mx-2">Products</span>
                                        <h3 class="text-bold-600 m-4 card-h3-style"><?= $product_counter ?></h3>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-xl-6 col-12" id="ecommerceChartView">
                    <div class="card chart-height mt-3 h-100">
                        <h3 class="card-title m-3 mb-0">Product Sales</h3>
                        <div class="card-header card-header-transparent py-20 border-0 p-2">
                            <ul class="nav nav-pills nav-pills-rounded chart-action float-right btn-group" role="group">
                                <li class="nav-item"><a class="nav-link active" data-toggle="tab"
                                        href="#scoreLineToDay">Day</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab"
                                        href="#scoreLineToWeek">Week</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab"
                                        href="#scoreLineToMonth">Month</a></li>
                            </ul>
                        </div>
                        <div class="widget-content tab-content bg-white p-20">
                            <div class="ct-chart tab-pane active scoreLine" id="scoreLineToDay"></div>
                            <div class="ct-chart tab-pane scoreLine" id="scoreLineToWeek"></div>
                            <div class="ct-chart tab-pane scoreLine" id="scoreLineToMonth"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Category Wise Product's Sales -->
                    <div class="card mt-3 h-100">
                        <h3 class="card-title m-3">Category Wise Product's Count</h3>
                        <div class="card-body">
                            <div id="piechart_3d" class='piechat_height'></div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-md-6 col-xs-12 mt-4">
                    <div class="alert alert-danger alert-dismissible">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn-close" data-dismiss="alert" aria-hidden="true"></button>
                        </div>
                        <h6><i class="icon fa fa-info"></i> <?= $count_products_availability_status ?> Product(s) sold
                            out!</h6>
                        <a href="<?= base_url('admin/product/?flag=sold') ?>"
                            class="text-decoration-none small-box-footer">More info <i
                                class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <?php $settings = get_settings('system_settings', true); ?>
                <div class="col-md-6 col-xs-12 mt-4">
                    <div class="alert alert-primary alert-dismissible">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn-close" data-dismiss="alert" aria-hidden="true"></button>
                        </div>
                        <h6><i class="icon fa fa-info"></i> <?= $count_products_low_status ?> Product(s) low in
                            stock!<small> (Low stock limit
                                <?= isset($settings['low_stock_limit']) ? $settings['low_stock_limit'] : '5' ?>)</small>
                        </h6>
                        <a href="<?= base_url('admin/product/?flag=low') ?>"
                            class="text-decoration-none small-box-footer">More info <i
                                class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-12 mt-4">
                    <h5 class="fw-bold">Order Outlines</h5>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                            <div class="card border border-secondary mt-4">
                                <div
                                    class="card-body  d-flex align-items-center justify-content-between order-outline-card">
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold d-block mb-1 col-md-12 h7">Pending</span>

                                        <h3 class="card-title mb-2 h8"><?= $status_counts['draft'] ?></h3>
                                    </div>
                                    <div
                                        class="d-flex flex-column justify-content-center rounded-circle bg-secondary circle">
                                        <i
                                            class="fa fa-xs fa-history link-color fa-lg d-flex justify-content-center text-white circle-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                            <div class="card border border-secondary mt-4">
                                <div
                                    class="card-body  d-flex align-items-center justify-content-between order-outline-card">
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold d-block mb-1 col-md-12 h7">Ready To Pickup</span>
                                        <h3 class="card-title mb-2 h8"><?= $status_counts['ready_to_pickup'] ?></h3>
                                    </div>
                                    <div
                                        class="d-flex flex-column justify-content-center rounded-circle bg-secondary circle">
                                        <i
                                            class="fa fa-xs fa-history link-color fa-lg d-flex justify-content-center text-white circle-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                            <div class="card border border-secondary mt-4">
                                <div
                                    class="card-body  d-flex align-items-center justify-content-between order-outline-card">
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold d-block mb-1 col-md-12 h7">Return Request
                                            Pending</span>
                                        <h3 class="card-title mb-2 h8"><?= $status_counts['return_request_pending'] ?>
                                        </h3>
                                    </div>
                                    <div
                                        class="d-flex flex-column justify-content-center rounded-circle bg-secondary circle">
                                        <i
                                            class="fa fa-xs fa-history link-color fa-lg d-flex justify-content-center text-white circle-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                            <div class="card border border-secondary mt-4">
                                <div
                                    class="card-body  d-flex align-items-center justify-content-between order-outline-card">
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold d-block mb-1 col-md-12 h7">Awaiting</span>
                                        <h3 class="card-title mb-2 h8"><?= $status_counts['awaiting'] ?></h3>
                                    </div>
                                    <div
                                        class="d-flex flex-column justify-content-center rounded-circle bg-secondary circle">
                                        <i
                                            class="fa fa-xs fa-history link-color fa-lg d-flex justify-content-center text-white circle-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                            <div class="card border border-primary mt-4 ">
                                <div
                                    class="card-body  d-flex align-items-center justify-content-between order-outline-card">
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold d-block mb-1 col-md-12 h7">Received</span>
                                        <h3 class="card-title mb-2 h8"><?= $status_counts['received'] ?></h3>
                                    </div>
                                    <div
                                        class="d-flex flex-column justify-content-center rounded-circle bg-secondary circle">
                                        <i
                                            class="fa fa-xs fa-level-down-alt link-color fa-lg d-flex justify-content-center text-white circle-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                            <div class="card border border-primary mt-4">
                                <div
                                    class="card-body  d-flex align-items-center justify-content-between order-outline-card">
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold d-block mb-1 col-md-12 h7">Processed</span>
                                        <h3 class="card-title mb-2 h8"><?= $status_counts['processed'] ?></h3>
                                    </div>
                                    <div
                                        class="d-flex flex-column justify-content-center rounded-circle bg-secondary circle">
                                        <i
                                            class="fa fa-xs fa-people-carry link-color fa-lg d-flex justify-content-center text-white circle-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                            <div class="card border border-primary mt-4">
                                <div
                                    class="card-body  d-flex align-items-center justify-content-between order-outline-card">
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold d-block mb-1 col-md-12 h7">Shipped</span>
                                        <h3 class="card-title mb-2 h8"><?= $status_counts['shipped'] ?></h3>
                                    </div>
                                    <div
                                        class="d-flex flex-column justify-content-center rounded-circle bg-secondary circle">
                                        <i
                                            class="fa fa-xs fa-shipping-fast link-color fa-lg d-flex justify-content-center text-white circle-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                            <div class="card border border-primary mt-4 ">
                                <div
                                    class="card-body  d-flex align-items-center justify-content-between order-outline-card">
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold d-block mb-1 col-md-12 h7">Delivered</span>
                                        <h3 class="card-title mb-2 h8"><?= $status_counts['delivered'] ?></h3>
                                    </div>
                                    <div
                                        class="d-flex flex-column justify-content-center rounded-circle bg-secondary circle">
                                        <i
                                            class="fa fa-xs fa-user-check link-color fa-lg d-flex justify-content-center text-white circle-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                            <div class="card border border-primary mt-4">
                                <div
                                    class="card-body  d-flex align-items-center justify-content-between order-outline-card">
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold d-block mb-1 col-md-12 h7">Cancelled</span>
                                        <h3 class="card-title mb-2 h8"><?= $status_counts['cancelled'] ?></h3>
                                    </div>
                                    <div
                                        class="d-flex flex-column justify-content-center rounded-circle bg-secondary circle">
                                        <i
                                            class="fa fa-xs fa-times-circle link-color fa-lg d-flex justify-content-center text-white circle-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                            <div class="card border border-primary mt-4">
                                <div
                                    class="card-body  d-flex align-items-center justify-content-between order-outline-card">
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold d-block mb-1 col-md-12 h7">Returned</span>
                                        <h3 class="card-title mb-2 h8"><?= $status_counts['returned'] ?></h3>
                                    </div>
                                    <div
                                        class="d-flex flex-column justify-content-center rounded-circle bg-secondary circle">
                                        <i
                                            class="fa fa-xs fa-level-up-alt link-color fa-lg d-flex justify-content-center text-white circle-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 main-content mt-3">
                    <div class="card content-area p-4">
                        <h5 class="col">Order Details</h5>
                        <div class="card-innr">
                            <div class="gaps-1-5x row d-flex adjust-items-center">
                                <div class="row col-md-12 mt-4">
                                    <div class="form-group col-md-3">
                                        <label>Date range:</label>
                                        <div class="input-group col-md-12">

                                            <input type="text" class="form-control float-right" id="datepicker">
                                            <input type="hidden" id="start_date" class="form-control float-right">
                                            <input type="hidden" id="end_date" class="form-control float-right">
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                    <div class="form-group col-md-2">
                                        <div>
                                            <label>Status</label>
                                            <select id="order_status" name="order_status" placeholder="Select Status"
                                                required="" class="form-control">
                                                <option value="">All Orders</option>
                                                <option value="awaiting">Awaiting</option>
                                                <option value="received">Received</option>
                                                <option value="processed">Processed</option>
                                                <option value="shipped">Shipped</option>
                                                <option value="delivered">Delivered</option>
                                                <option value="cancelled">Cancelled</option>
                                                <option value="returned">Returned</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Filter By payment  -->
                                    <div class="form-group col-md-2">
                                        <div>
                                            <label>Delivery Boy</label>
                                            <select id="delivery_boy" class="form-control">
                                                <option value="">All delivery boy</option>
                                                <?php

                                                foreach ($delivery_res as $row) {
                                                    ?>
                                                    <option value="<?= $row['user_id'] ?>" <?= $selected ?>>
                                                        <?= $row['username'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <div>
                                            <label>Payment Method</label>
                                            <select id="payment_method" name="payment_method"
                                                placeholder="Select Payment Method" required="" class="form-control">
                                                <option value="">All Payment Methods</option>
                                                <option value="COD">Cash On Delivery</option>
                                                <option value="Paypal">Paypal</option>
                                                <option value="RazorPay">RazorPay</option>
                                                <option value="Paystack">Paystack</option>
                                                <option value="Flutterwave">Flutterwave</option>
                                                <option value="Paytm">Paytm</option>
                                                <option value="Stripe">Stripe</option>
                                                <option value="Phonepe">PhonePe</option>
                                                <option value="bank_transfer">Direct Bank Transfers</option>
                                                <option value="midtrans">Midtrans</option>
                                                <option value="instamojo">Instamojo</option>
                                                <option value="my_fatoorah">My Fatoorah</option>
                                                <option value="wallet">Wallet</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-1">
                                        <div>
                                            <label>Product Type</label>
                                            <select id="order_type" name="order_type" placeholder="Select Order Type"
                                                required="" class="form-control">
                                                <option value="">All Orders</option>
                                                <option value="physical_order">Physical Orders</option>
                                                <option value="digital_order">Digital Orders</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- FILTER BUTTON -->
                                    <div class="form-group col-md-2 d-flex align-items-center mt-4">

                                        <button type="button" class="btn btn-outline-primary btn-sm me-2"
                                            onclick="status_date_wise_search()">Filter</button>

                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                            onclick="reset_filters()">Reset</button>

                                    </div>

                                </div>
                            </div>
                            <table class='table-striped' data-toggle="table"
                                data-url="<?= base_url('admin/orders/view_orders') ?>" data-click-to-select="true"
                                data-side-pagination="server" data-pagination="true"
                                data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true"
                                data-show-refresh="true" data-trim-on-search="false" data-sort-name="id"
                                data-sort-order="desc" data-mobile-responsive="true" data-toolbar=""
                                data-show-export="true" data-maintain-selected="true"
                                data-export-types='["txt","excel"]' data-export-options='{
                        "fileName": "orders-list",
                        "ignoreColumn": ["operate"] 
                        }' data-query-params="home_query_params">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable='true' data-footer-formatter="totalFormatter">
                                            Order ID</th>
                                        <th data-field="user_id" data-sortable='true' data-visible="false">User ID</th>
                                        <th data-field="qty" data-sortable='true' data-visible="false">Qty</th>
                                        <th data-field="name" data-sortable='true'>User Name</th>
                                        <th data-field="mobile" data-sortable='true' data-visible="false">Mobile</th>
                                        <th data-field="items" data-sortable='true' data-visible="false">Items</th>
                                        <th data-field="total" data-sortable='true' data-visible="true">
                                            Total(<?= $curreny ?>)</th>
                                        <th data-field="delivery_charge" data-sortable='true'
                                            data-footer-formatter="delivery_chargeFormatter" data-visible="true">
                                            D.Charge</th>
                                        <th data-field="wallet_balance" data-sortable='true' data-visible="true">Wallet
                                            Used(<?= $curreny ?>)</th>
                                        <th data-field="promo_code" data-sortable='true' data-visible="false">Promo Code
                                        </th>
                                        <th data-field="promo_discount" data-sortable='true' data-visible="true">Promo
                                            disc.(<?= $curreny ?>)</th>
                                        <th data-field="discount" data-sortable='true' data-visible="false">Discount
                                            <?= $curreny ?>(%)
                                        </th>
                                        <th data-field="final_total" data-sortable='true'>Final Total(<?= $curreny ?>)
                                        </th>
                                        <th data-field="deliver_by" data-sortable='true' data-visible='false'>Deliver By
                                        </th>
                                        <th data-field="payment_method" data-sortable='true' data-visible="true">Payment
                                            Method</th>
                                        <th data-field="address" data-sortable='true'>Address</th>
                                        <th data-field="notes" data-sortable='false' data-visible='false'>O. Notes</th>
                                        <th data-field="delivery_date" data-sortable='true' data-visible='false'>
                                            Delivery Date</th>
                                        <th data-field="delivery_time" data-sortable='true' data-visible='false'>
                                            Delivery Time</th>
                                        <th data-field="status" data-sortable='true' data-visible='false'>Status</th>
                                        <th data-field="active_status" data-sortable='true' data-visible='true'>Active
                                            Status</th>
                                        <th data-field="local_pickup" data-sortable='true' data-visible='true'>Pickup
                                        </th>
                                        <th data-field="date_added" data-sortable='true'>Order Date</th>
                                        <th data-field="operate">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div><!-- .card-innr -->
                    </div><!-- .card -->
                </div>
            </div>
        </div>
    </section>
</div>