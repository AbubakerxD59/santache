<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header mt-4">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h4>Manage Area</h4>
                </div>
                <div class="col-sm-4 d-flex justify-content-end">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Area</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
    <div class="card card-info">
        <form class="form-horizontal form-submit-event"
              action="<?= base_url('admin/area/add_area'); ?>"
              method="POST"
              id="add_product_form"
              enctype="multipart/form-data">

            <?php if (isset($fetched_data[0]['id'])) { ?>
                <input type="hidden" name="edit_area" value="<?= $fetched_data[0]['id'] ?>">
                <input type="hidden" name="update_id" value="1">
            <?php } ?>

            <div class="card-body">

                <!-- Area Name -->
                <div class="form-group row mb-3">
                    <label for="area_name" class="col-md-3 col-form-label">
                        Area Name <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-6">
                        <input type="text"
                               class="form-control"
                               name="area_name"
                               id="area_name"
                               value="<?= $fetched_data[0]['name'] ?? '' ?>">
                    </div>
                </div>

                <!-- City -->
                <div class="form-group row mb-3">
                    <label for="city" class="col-md-3 col-form-label">
                        City <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-6">
                        <select class="form-control select2"
                                name="city"
                                id="city"
                                style="width:100%">
                            <option value="">--- Select City ---</option>
                            <?php foreach ($city as $row) { ?>
                                <option value="<?= $row['id'] ?>"
                                    <?= (!empty($fetched_data[0]['city_id']) && $row['id'] == $fetched_data[0]['city_id']) ? 'selected' : '' ?>>
                                    <?= $row['name'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <!-- Zipcode -->
                <div class="form-group row mb-3">
                    <label for="zipcode" class="col-md-3 col-form-label">
                        Zipcode <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-6">
                        <select class="form-control select2"
                                name="zipcode"
                                id="zipcode"
                                style="width:100%">
                            <option value="">--- Select Zipcode ---</option>
                            <?php foreach ($zipcodes as $zipcode) { ?>
                                <option value="<?= $zipcode['id'] ?>"
                                    <?= (!empty($fetched_data[0]['zipcode_id']) && $zipcode['id'] == $fetched_data[0]['zipcode_id']) ? 'selected' : '' ?>>
                                    <?= $zipcode['zipcode'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <!-- Minimum Free Delivery -->
                <div class="form-group row mb-3">
                    <label for="minimum_free_delivery_order_amount"
                           class="col-md-3 col-form-label">
                        Minimum Free Delivery Amount <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-6">
                        <input type="number"
                               class="form-control"
                               name="minimum_free_delivery_order_amount"
                               id="minimum_free_delivery_order_amount"
                               min="0"
                               value="<?= $fetched_data[0]['minimum_free_delivery_order_amount'] ?? '' ?>">
                    </div>
                </div>

                <!-- Delivery Charges -->
                <div class="form-group row mb-4">
                    <label for="delivery_charges" class="col-md-3 col-form-label">
                        Delivery Charges <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-6">
                        <input type="number"
                               class="form-control"
                               name="delivery_charges"
                               id="delivery_charges"
                               min="0"
                               value="<?= $fetched_data[0]['delivery_charges'] ?? '' ?>">
                    </div>
                </div>

            </div>

            <!-- Footer Buttons -->
            <div class="card-footer text-end">
                <button type="reset" class="btn btn-warning me-2">
                    Reset
                </button>
                <button type="submit" class="btn btn-success" id="submit_btn">
                    <?= isset($fetched_data[0]['id']) ? 'Update Area' : 'Add Area' ?>
                </button>
            </div>

        </form>
    </div>
</div>

                <!--/.card-->
            </div>
            <div class="modal fade edit-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Edit Area</h5>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">

                            </button>
                        </div>
                        <div class="modal-body">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 main-content mt-4">
                <div class="card content-area p-4">
                    <div class="card-head">
                        <h4 class="card-title">Area Details</h4>
                    </div>
                    <div class="card-innr">
                        <div class="gaps-1-5x"></div>
                        <table class='table-striped' data-toggle="table"
                            data-url="<?= base_url('admin/area/view_area') ?>" data-click-to-select="true"
                            data-side-pagination="server" data-pagination="true"
                            data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true"
                            data-show-refresh="true" data-trim-on-search="false" data-sort-name="id"
                            data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true"
                            data-export-types='["txt","excel"]' data-export-options='{
                        "fileName": "area-list",
                        "ignoreColumn": ["operate"] 
                        }' data-maintain-selected="true" data-query-params="queryParams">
                            <thead>
                                <tr>
                                    <th data-field="id" data-sortable="true">ID</th>
                                    <th data-field="name" data-sortable="false">Name</th>
                                    <th data-field="city_name" data-sortable="true">City Name</th>
                                    <th data-field="zipcode" data-sortable="false">Zipcode</th>
                                    <th data-field="minimum_free_delivery_order_amount" data-sortable="false">
                                        Minimum Free Delivery Order Amount</th>
                                    <th data-field="delivery_charges" data-sortable="false">Delivery Charges</th>
                                    <th data-field="operate" data-sortable="false">Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div><!-- .card-innr -->
                </div><!-- .card -->
            </div>
        </div>
        <!-- /.row -->
</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>