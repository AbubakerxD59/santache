<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header mt-4">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h4>Shipping Methods Settings</h4>
                </div>
                <div class="col-sm-4 d-flex justify-content-end">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Shipping Methods Settings</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <form class="form-horizontal form-submit-event" action="<?= base_url('admin/Shipping_settings/update_shipping_settings'); ?>" method="POST" id="payment_setting_form">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="local_shipping_method">Enable Local Shipping <small> ( Use Local Delivery Boy For Shipping) </small>
                                        </label>
                                        <div class="card-body">
                                            <input type="checkbox" <?= (@$settings['local_shipping_method']) == '1' ? 'Checked' : '' ?> data-bootstrap-switch data-off-color="danger" data-on-color="success" name="local_shipping_method">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-12">
                                        <label for="shiprocket_shipping_method">Standard delivery method (Shiprocket) <small>( Enable/Disable ) <a href="https://app.shiprocket.in/api-user" target="_blank"> Click here </a> </small>to get credentials. <small> <a href="https://www.shiprocket.in/" target="_blank">What is shiprocket? </a></small>
                                        </label>
                                        <br>
                                        <div class="card-body">
                                            <input type="checkbox" <?= (@$settings['shiprocket_shipping_method']) == '1' ? 'Checked' : '' ?> data-bootstrap-switch data-off-color="danger" data-on-color="success" name="shiprocket_shipping_method">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-5">
                                        <label class="mb-2" for="email">Email</label>
                                        <input type="email" class="form-control mb-2" name="email" id="email" value="<?= @$settings['email'] ?>" placeholder="Shiprocket acount email" />
                                    </div>
                                    <div class="form-group col-5">
                                        <label class="mb-2" for="password">Password</label>
                                        <input type="password" class="form-control mb-2" name="password" id="" value="<?= @$settings['password'] ?>" placeholder="Shiprocket acount Password" />
                                    </div>
                                    <div class="form-group col-5">
                                        <label class="mb-2" for="webhook_url">Shiprocket Webhoook Url</label>
                                        <input type="text" class="form-control mb-2" name="webhook_url" id="" value="<?= base_url('admin/webhook/spr_webhook'); ?>" disabled />
                                    </div>
                                    <div class="form-group col-5">
                                        <label class="mb-2" for="webhook_token">Shiprocket webhook token</label>
                                        <input type="text" class="form-control mb-2" name="webhook_token" id="" value="<?= @$settings['webhook_token'] ?>" />
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="row">
                                    <div class="form-group col-12">
                                        <label for="usps_shipping_method">USPS Shipping (Dynamic Rates) <small>(Enable/Disable)</small>
                                            <a href="https://developers.usps.com/getting-started" target="_blank">Get API credentials</a>
                                        </label>
                                        <br>
                                        <div class="card-body">
                                            <input type="checkbox" <?= (@$settings['usps_shipping_method']) == '1' ? 'Checked' : '' ?> data-bootstrap-switch data-off-color="danger" data-on-color="success" name="usps_shipping_method">
                                        </div>
                                        <small class="text-muted">Uses USPS Domestic Prices API based on customer ZIP and package weight. When both USPS and Shiprocket are enabled, USPS is used for standard shipping rates.</small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-5">
                                        <label class="mb-2" for="usps_consumer_key">USPS Consumer Key</label>
                                        <input type="text" class="form-control mb-2" name="usps_consumer_key" id="usps_consumer_key" value="<?= htmlspecialchars(@$settings['usps_consumer_key'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Consumer Key (client_id)" autocomplete="off" />
                                    </div>
                                    <div class="form-group col-5">
                                        <label class="mb-2" for="usps_consumer_secret">USPS Consumer Secret</label>
                                        <input type="password" class="form-control mb-2" name="usps_consumer_secret" id="usps_consumer_secret" value="<?= htmlspecialchars(@$settings['usps_consumer_secret'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Consumer Secret (client_secret)" autocomplete="off" />
                                    </div>
                                    <div class="form-group col-5">
                                        <label class="mb-2" for="usps_origin_zip">Origin ZIP Code <small>(ship-from)</small></label>
                                        <input type="text" class="form-control mb-2" name="usps_origin_zip" id="usps_origin_zip" value="<?= htmlspecialchars(@$settings['usps_origin_zip'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="e.g. 10001" maxlength="10" />
                                    </div>
                                    <div class="form-group col-5">
                                        <label class="mb-2" for="usps_environment">API Environment</label>
                                        <select class="form-control mb-2" name="usps_environment" id="usps_environment">
                                            <option value="production" <?= (@$settings['usps_environment'] ?? 'production') === 'production' ? 'selected' : '' ?>>Production (apis.usps.com)</option>
                                            <option value="tem" <?= (@$settings['usps_environment'] ?? '') === 'tem' ? 'selected' : '' ?>>Testing / TEM (apis-tem.usps.com)</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-5">
                                        <label class="mb-2">Price Type</label>
                                        <input type="text" class="form-control mb-2" value="Retail" disabled />
                                        <small class="text-muted">Always uses Retail pricing.</small>
                                    </div>
                                    <div class="form-group col-5">
                                        <label class="mb-2">Mail Class</label>
                                        <input type="text" class="form-control mb-2" value="USPS Ground Advantage" disabled />
                                        <small class="text-muted">Always uses Ground Advantage for rate quotes.</small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-5"></div>
                                </div>
                                <div class="row">

                                    <div class="form-group mt-4">
                                        <button type="reset" class="btn btn-warning">Reset</button>
                                        <button type="submit" class="btn btn-success update_shipping" id="submit_btn">Update Shipping Settings</button>
                                    </div>
                                </div>
                        </form>
                    </div>
                    <!--/.card-->
                </div>
                <!--/.col-md-12-->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
