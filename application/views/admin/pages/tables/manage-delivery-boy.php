<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header mt-4">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12 col-md-8 mb-2 mb-md-0">
                    <h4>Manage Delivery Boy</h4>
                </div>
                <div class="col-12 col-md-4 d-flex justify-content-md-end justify-content-start">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Delivery Boy</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->

        <div class="container-fluid">
            <div class="row">
                <div class="modal fade edit-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Edit Delivery boy</h5>
                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">

                                </button>
                            </div>
                            <div class="modal-body p-0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id='fund_transfer_delivery_boy'>
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Fund Transfer Delivery boy</h5>
                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">

                                </button>
                            </div>
                            <div class="modal-body p-0">
                                <form class="form-horizontal form-submit-event" action="<?= base_url('admin/fund_transfer/add-fund-transfer'); ?>" method="POST" enctype="multipart/form-data">
                                    <div class="card-body row">
                                        <input type="hidden" name='delivery_boy_id' id="delivery_boy_id">
                                        <div class="form-group col-12 col-md-6 mb-3">
                                            <label for="name" class="col-form-label">Name</label>
                                            <input type="text" class="form-control" id="name" name="name" readonly>
                                        </div>
                                        <div class="form-group col-12 col-md-6 mb-3">
                                            <label for="mobile" class="col-form-label">Mobile</label>
                                            <input type="number" class="form-control" id="mobile" name="mobile" readonly>
                                        </div>
                                        <div class="form-group col-12 col-md-6 mb-3">
                                            <label for="balance" class="col-form-label">Balance</label>
                                            <input type="number" min="0" class="form-control" id="balance" name="balance" readonly>
                                        </div>
                                        <div class="form-group col-12 col-md-6 mb-3">
                                            <label for="transfer_amt" class="col-form-label">Transfer Amount</label>
                                            <input type="number" min="0" class="form-control" id="transfer_amt" name="transfer_amt">
                                        </div>
                                        <div class="form-group col-12 mb-3">
                                            <label for="message" class="col-form-label">Message</label>
                                            <input type="text" class="form-control" id="message" name="message">
                                        </div>
                                        <div class="form-group mt-3 col-12">
                                            <button type="button" id="fund-transfer-rest-btn" class="btn btn-warning mb-2 mb-sm-0">Reset</button>
                                            <button type="submit" class="btn btn-success" id="submit_btn">Transfer Fund</button>
                                        </div>

                                        <!-- /.card-body -->
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="row">


                        <div class="col-12 col-lg-5 mb-4 mb-lg-0">
                            <div class="card card-info">
                                <!-- form start -->
                                <form class="form-horizontal form-submit-event" method="POST" id="add_product_form" enctype="multipart/form-data">
                                    <?php if (isset($fetched_data[0]['id'])) { ?>
                                        <input type="hidden" name="edit_delivery_boy" value="<?= $fetched_data[0]['id'] ?>">
                                    <?php
                                    } ?>
                                    <div class="card-body">
                                        <div class="form-group row mb-3">
                                            <label for="db_name" class="col-12 col-sm-3 col-form-label">Name <span class='text-danger text-sm'>*</span></label>
                                            <div class="col-12 col-sm-9">
                                                <input type="text" class="form-control" id="db_name" placeholder="Delivery Boy Name" name="name" value="<?= @$fetched_data[0]['username'] ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label for="db_mobile" class="col-12 col-sm-3 col-form-label">Mobile <span class='text-danger text-sm'>*</span></label>
                                            <div class="col-12 col-sm-9">
                                                <input type="text" maxlength="16" oninput="validateNumberInput(this)" class="form-control" id="db_mobile" placeholder="Enter Mobile" name="mobile" value="<?= @$fetched_data[0]['mobile'] ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label for="email" class="col-12 col-sm-3 col-form-label">Email <span class='text-danger text-sm'>*</span></label>
                                            <div class="col-12 col-sm-9">
                                                <input type="email" class="form-control" id="email" placeholder="Enter Email" name="email" value="<?= @$fetched_data[0]['email'] ?>">
                                            </div>
                                        </div>
                                        <?php
                                        if (!isset($fetched_data[0]['id'])) {
                                        ?>
                                            <div class="form-group row mb-3">
                                                <label for="password" class="col-12 col-sm-3 col-form-label">Password <span class='text-danger text-sm'>*</span></label>
                                                <div class="col-12 col-sm-9">
                                                    <div class="input-group">
                                                        <input type="password" class="form-control" id="password" placeholder="Enter Passsword" name="password" value="<?= @$fetched_data[0]['password'] ?>">
                                                        <span class="input-group-text togglePassword btn">
                                                            <i class="fa fa-eye"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label for="confirm_password" class="col-12 col-sm-3 col-form-label">Confirm Password <span class='text-danger text-sm'>*</span></label>
                                                <div class="col-12 col-sm-9">
                                                    <div class="input-group">
                                                        <input type="password" class="form-control" id="confirm_password" placeholder="Enter Confirm Password" name="confirm_password">
                                                        <span class="input-group-text togglePassword btn">
                                                            <i class="fa fa-eye"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                        <div class="form-group row mb-3">
                                            <label for="address" class="col-12 col-sm-3 col-form-label">Address <span class='text-danger text-sm'>*</span></label>
                                            <div class="col-12 col-sm-9">
                                                <input type="text" class="form-control" id="address" placeholder="Enter Address" name="address" value="<?= @$fetched_data[0]['address'] ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <?php
                                            $bonus_type = ['fixed_amount_per_order', 'percentage_per_order'];
                                            ?>
                                            <label for="bonus_type" class="col-12 col-sm-3 control-label">Bonus Types <span class='text-danger text-sm'> * </span></label>
                                            <div class="col-12 col-sm-9">
                                                <select name="bonus_type" class="form-control bonus_type" id="bonus_type">
                                                    <option value=" ">Select Types</option>
                                                    <?php foreach ($bonus_type as $row) { ?>
                                                        <option value="<?= $row ?>" <?= (isset($fetched_data[0]['id']) &&  $fetched_data[0]['bonus_type'] == $row) ? "Selected" : "" ?>><?= ucwords(str_replace('_', ' ', $row)) ?></option>
                                                    <?php
                                                    } ?>
                                                </select>
                                                <?php ?>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3 fixed_amount_per_order <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['bonus_type'] == 'fixed_amount_per_order') ? '' : 'd-none' ?>">
                                            <label for="bonus_amount" class="col-12 col-sm-3 col-form-label">Amount <span class='text-danger text-sm'>*</span></label>
                                            <div class="col-12 col-sm-9">
                                                <input type="number" class="form-control" min='1' id="bonus_amount" placeholder="Enter amount to be given to the delivery boy on successful order delivery" name="bonus_amount" value="<?= @$fetched_data[0]['bonus'] ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3 percentage_per_order <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['bonus_type'] == 'percentage_per_order') ? '' : 'd-none' ?>">
                                            <label for="bonus_percentage" class="col-12 col-sm-3 col-form-label">Bonus(%) <span class='text-danger text-sm'>*</span></label>
                                            <div class="col-12 col-sm-9">
                                                <input type="number" min='1' max="100" class="form-control" id="bonus_percentage" placeholder="Enter Bonus(%) to be given to the delivery boy on successful order delivery" name="bonus_percentage" value="<?= @$fetched_data[0]['bonus'] ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label for="driving_license" class="col-12 col-sm-3 col-form-label">Driving License <span class='text-danger text-sm'>*</span></label>
                                            <div class="col-12 col-sm-9">
                                                <?php if (isset($fetched_data[0]['driving_license']) && !empty($fetched_data[0]['driving_license'])) { ?>
                                                    <span class="text-danger">*Leave blank if there is no change</span>
                                                <?php } else { ?>
                                                    <span class="text-danger">*Add Driving License's front and back image</span>
                                                <?php } ?>
                                                <input type="file" class="form-control" name="driving_license[]" id="driving_license" accept="image/*" multiple />
                                            </div>
                                        </div>

                                        <?php if (isset($fetched_data[0]['driving_license']) && !empty($fetched_data[0]['driving_license'])) { ?>
                                            <div class="form-group row mb-3">
                                                <div class="col-12 col-sm-9 offset-sm-3">
                                                    <div class="row">
                                                        <?php
                                                        $images = explode(",", $fetched_data[0]['driving_license']);
                                                        foreach ($images as $image) {
                                                            if (!empty(trim($image))) {
                                                        ?>
                                                            <div class="col-sm-6 mb-2">
                                                                <div class="driving-license-image">
                                                                    <a href="<?= base_url(trim($image)); ?>" data-toggle="lightbox" data-gallery="gallery_seller">
                                                                        <img src="<?= base_url(trim($image)); ?>" class="img-fluid rounded w-100 h-auto">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        <?php
                                                            }
                                                        } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="form-group row mb-4">
                                            <label class="col-12 col-sm-3 col-form-label">Status <span class='text-danger text-sm'>*</span></label>
                                            <div class="col-12 col-sm-9" id="status">
                                                <label class="btn btn-primary mt-1" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                                    <input type="radio" name="status" value="1" required <?= (isset($fetched_data[0]['status']) && $fetched_data[0]['status'] == '1') ? 'Checked' : '' ?>> Approved
                                                </label>
                                                <label class="btn btn-danger mt-1" data-toggle-class="btn-danger" data-toggle-passive-class="btn-default">
                                                    <input type="radio" name="status" value="0" required <?= (isset($fetched_data[0]['status']) && $fetched_data[0]['status'] == '0') ? 'Checked' : '' ?>> Not-Approved
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group mt-3">
                                            <button type="reset" class="btn btn-warning me-2">Reset</button>
                                            <button type="submit" class="btn btn-success add_product_form"><?= (isset($fetched_data[0]['id'])) ? 'Update Delivery Boy' : 'Add Delivery Boy' ?></button>
                                        </div>
                                    </div>

                                    <!-- /.card-footer -->
                                </form>
                            </div>
                            <!--/.card-->
                        </div>

                        <div class="col-12 col-lg-7 main-content">
                            <div class="card content-area p-3 p-md-4">
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="delivery_boy_type_filter" class="col-form-label fs-6">Filter Delivery Boy By Type </label>
                                    <select id="delivery_boy_type_filter" name="delivery_boy_type_filter" placeholder="Select Type" required="" class="form-control">
                                        <option value="">All</option>
                                        <option value="fixed_amount_per_order">Fixed Amount Per Order</option>
                                        <option value="percentage_per_order">Percentage Per Order</option>
                                    </select>
                                </div>
                                <div class="card-innr">
                                    <div class="row col-md-6">
                                    </div>
                                    <div class="gaps-1-5x"></div>
                                    <div class="table-responsive">
                                    <table class='table-striped' id='fund_transfer' data-toggle="table" data-url="<?= base_url('admin/delivery_boys/view_delivery_boys') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                        "fileName": "delivery-boy-list",
                        "ignoreColumn": ["operate"] 
                        }' data-query-params="deliveryBoyQueryParams">
                                        <thead>
                                            <tr>
                                                <th data-field="id" data-sortable="true">ID</th>
                                                <th data-field="name" data-sortable="false">Name</th>
                                                <th data-field="email" data-sortable="false">Email</th>
                                                <th data-field="mobile" data-sortable="true">Mobile No</th>
                                                <th data-field="address" data-sortable="true">Address</th>
                                                <th data-field="bonus_type" data-sortable="true">Bonus Type</th>
                                                <th data-field="bonus" data-sortable="true">Bonus</th>
                                                <th data-field="balance" data-sortable="true">Balance</th>
                                                <th data-field="status" data-sortable="true">Status</th>
                                                <th data-field="date" data-sortable="false">Date</th>
                                                <th data-field="operate">Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
                                    </div>
                                </div><!-- .card-innr -->
                            </div><!-- .card -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>