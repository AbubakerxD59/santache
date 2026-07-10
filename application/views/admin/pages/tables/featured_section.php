<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header mt-4">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h4>Manage Featured Section (Show Products Exclusively)</h4>
                </div>
                <div class="col-sm-4 d-flex justify-content-end">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Featured Section </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-5">
                    <div class="card card-info">
                        <!-- form start -->
                        <form class="form-horizontal form-submit-event"
                            action="<?= base_url('admin/Featured_sections/add_featured_section'); ?>" method="POST"
                            enctype="multipart/form-data">
                            <?php if (isset($fetched_data[0]['id'])) { ?>
                                <input type="hidden" id="edit_featured_section" name="edit_featured_section"
                                    value="<?= @$fetched_data[0]['id'] ?>">
                                <input type="hidden" id="update_id" name="update_id" value="1">
                            <?php } ?>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="title" class="control-label mt-2">Title for section <span
                                            class='text-danger text-sm'>*</span></label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control mt-2" name="title" id="title"
                                            value="<?= (isset($fetched_data[0]['title']) ? stripslashes($fetched_data[0]['title']) : '') ?>"
                                            placeholder="Title">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="short_description" class="control-label mt-2">Short description <span
                                            class='text-danger text-sm'>*</span></label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control mt-2" name="short_description"
                                            id="short_description"
                                            value="<?= (isset($fetched_data[0]['short_description']) ? stripslashes($fetched_data[0]['short_description']) : '') ?>"
                                            placeholder="Short description">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <?php
                                    $product_type = ['new_added_products', 'products_on_sale', 'top_rated_products', 'most_selling_products', 'custom_products', 'digital_product'];

                                    ?>
                                    <label for="product_type" class="control-label mt-2">Product Types <span
                                            class='text-danger text-sm'> * </span></label>
                                    <div class="col-md-12 mt-2">
                                        <select name="product_type" class="form-control product_type">
                                            <option value=" ">Select Types</option>
                                            <?php foreach ($product_type as $row) { ?>
                                                <option value="<?= $row ?>" <?= (isset($fetched_data[0]['id']) && $fetched_data[0]['product_type'] == $row) ? "Selected" : "" ?>>
                                                    <?= ucwords(str_replace('_', ' ', $row)) ?>
                                                </option>
                                                <?php
                                            } ?>
                                        </select>
                                        <?php ?>
                                    </div>
                                </div>
                                <div class="form-group row select-categories">
                                    <label for="categories" class="control-label mt-2">Categories</label>
                                    <div class="col-md-12 mt-2">
                                        <select name="categories[]" class=" select_multiple w-100 " multiple
                                            data-placeholder=" Type to search and select categories">
                                            <option value="">
                                                <?= (isset($categories) && empty($categories)) ? 'No Categories Exist' : 'Select Categories' ?>
                                            </option>
                                            <?php
                                            $selected_val = (isset($fetched_data[0]['id']) && !empty($fetched_data[0]['id'])) ? $fetched_data[0]['categories'] : '';
                                            $selected_vals = explode(',', $selected_val ?? '');
                                            echo get_categories_option_html($categories, $selected_vals);
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <?php
                                    $style = ['default', 'style_1', 'style_2', 'style_3', 'style_4'];
                                    ?>
                                    <label for="style" class="control-label mt-2">Style <span
                                            class='text-danger text-sm'>*</span></label>
                                    <div class="col-md-12 mt-2">
                                        <select name="style" class="form-control">
                                            <option value=" ">Select Style</option>
                                            <?php foreach ($style as $row) { ?>
                                                <option value="<?= $row ?>" <?= (isset($fetched_data[0]['style']) && $fetched_data[0]['style'] == $row) ? 'Selected' : '' ?>>
                                                    <?= ucwords(str_replace('_', ' ', $row)) ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <?php ?>
                                    </div>
                                </div>

                                <!-- for custom product -->

                                <div
                                    class="form-group row custom_products <?= (isset($fetched_data[0]['id']) && $fetched_data[0]['product_type'] == 'custom_products') ? '' : 'd-none' ?>">
                                    <label for="product_ids" class="control-label">Products<span
                                            class='text-danger text-sm'> *</label>
                                    <div class="col-md-12">
                                        <select name="product_ids[]" class="search_product w-100" multiple
                                            data-placeholder=" Type to search and select products"
                                            onload="multiselect()">
                                            <?php
                                            if (isset($fetched_data[0]['id'])) {
                                                $product_id = explode(",", $fetched_data[0]['product_ids']);

                                                foreach ($product_details as $row) {
                                                    ?>
                                                    <option value="<?= $row['id'] ?>" selected><?= $row['name'] ?></option>
                                                    <?php
                                                }
                                            }

                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- for digital product -->

                                <div
                                    class="form-group row digital_products <?= (isset($fetched_data[0]['id']) && $fetched_data[0]['product_type'] == 'digital_product') ? '' : 'd-none' ?>">
                                    <label for="digital_product_ids" class="control-label">Products <span
                                            class='text-danger text-sm'> *</label>
                                    <div class="col-md-12">
                                        <select name="digital_product_ids[]" class="search_admin_digital_product w-100"
                                            multiple data-placeholder=" Type to search and select products"
                                            onload="multiselect()">
                                            <?php
                                            if (isset($fetched_data[0]['id'])) {
                                                $product_id = explode(",", $fetched_data[0]['product_ids']);

                                                foreach ($product_details as $row) {

                                                    ?>
                                                    <option value="<?= $row['id'] ?>" selected><?= $row['name'] ?></option>
                                                    <?php
                                                }
                                            }

                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mt-4">
                                    <button type="reset" class="btn btn-warning m-1">Reset</button>
                                    <button type="submit" class="btn btn-success feature_sec_update m-1"
                                        id="submit_btn"><?= (isset($fetched_data[0]['id'])) ? 'Update Fetured Section' : 'Add Fetured Section' ?></button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

                <div class="col-md-7 main-content mt-4">
                    
                    
                    <div class="card content-area p-4">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="product_type_filter" class="control-label">
                                    Product Type
                                </label>
                                <select id="product_type_filter" class="form-control">
                                    <option value="">All</option>
                                    <?php foreach ($product_type as $row) { ?>
                                        <option value="<?= $row ?>">
                                            <?= ucwords(str_replace('_', ' ', $row)) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
    
                            <div class="col-md-2 align-self-end">
                                <button type="button" id="reset_filters" class="btn btn-secondary w-100">
                                    Reset
                                </button>
                            </div>
                        </div>
                        <!-- <div class="card-head">
                            <h4 class="card-title">Feature Section</h4>
                        </div> -->
                        <div class="card-innr">
                            <div class="gaps-1-5x"></div>
                            <table class='table-striped' data-toggle="table"
                                data-url="<?= base_url('admin/Featured_sections/get_section_list') ?>"
                                data-click-to-select="true" data-side-pagination="server" data-pagination="true"
                                data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true"
                                data-show-refresh="true" data-trim-on-search="false" data-sort-name="id"
                                data-sort-order="desc" data-mobile-responsive="true" data-toolbar=""
                                data-show-export="true" data-maintain-selected="true"
                                data-export-types='["txt","excel"]' data-query-params="sectionQueryParams">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true">ID</th>
                                        <th data-field="title" data-sortable="true">Title</th>
                                        <th data-field="short_description" data-sortable="false">Short description</th>
                                        <th data-field="style" data-sortable="false">Style</th>
                                        <th data-field="categories" data-sortable="true">Categories</th>
                                        <th data-field="product_type" data-sortable="true">Product Type</th>
                                        <th data-field="date" data-sortable="false">Date</th>
                                        <th data-field="operate">Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div><!-- .card -->
                </div>
                <!--/.card-->
            </div>
            <div class="modal fade edit-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Edit Fetured Section Details</h5>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>