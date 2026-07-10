<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header mt-4">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h4>Slider Image For Add-on Offers and other benefits </h4>
                </div>
                <div class="col-sm-4 d-flex justify-content-end">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Slider</li>
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
                                <h5 class="modal-title">Edit Slider Details</h5>
                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                                </button>
                            </div>
                            <div class="modal-body p-0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card card-info">
                        <form class="form-horizontal form-submit-event"
                            action="<?= base_url('admin/slider/add_slider'); ?>" method="POST" id="payment_setting_form"
                            enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <?php if (isset($fetched_data[0]['id'])) {
                                        ?>
                                        <input type="hidden" name="edit_slider" value="<?= $fetched_data[0]['id'] ?>">
                                    <?php } ?>
                                    <input type="hidden" name="csrf_token" value="$csrf_token()" />
                                    <label for="slider_type" class="mt-2">Type <span
                                            class='text-danger text-sm'>*</span> </label>
                                    <select name="slider_type" id="slider_type"
                                        class="form-control type_event_trigger mt-2" required="">
                                        <option value=" ">Select Type</option>
                                        <option value="default" <?= (@$fetched_data[0]['type'] == "default") ? 'selected' : ' ' ?>>Default</option>
                                        <option value="categories" <?= (@$fetched_data[0]['type'] == "categories") ? 'selected' : ' ' ?>>Category</option>
                                        <option value="brand" <?= (@$fetched_data[0]['type'] == "brand") ? 'selected' : ' ' ?>>Brand</option>
                                        <option value="products" <?= (@$fetched_data[0]['type'] == "products") ? 'selected' : ' ' ?>>Product</option>
                                        <option value="slider_url" <?= (@$fetched_data[0]['type'] == "slider_url") ? 'selected' : ' ' ?>>Slider URL</option>
                                    </select>
                                </div>
                                <div id="type_add_html">
                                    <?php $hiddenStatus = (isset($fetched_data[0]['id']) && $fetched_data[0]['type'] == 'categories') ? '' : 'd-none' ?>
                                    <div class="form-group slider-categories <?= $hiddenStatus ?>">
                                        <label for="slider_category_id" class="mt-2">
                                            Categories <span class="text-danger text-sm">*</span>
                                        </label>

                                        <select name="category_id" id="slider_category_id"
                                            class="form-control select2 mt-2" style="width: 100%;">

                                            <option value="">Select category</option>

                                            <?php
                                            if (!empty($categories)) {
                                                foreach ($categories as $row) {
                                                    $selected = (
                                                        isset($fetched_data[0]['type_id']) &&
                                                        strtolower($fetched_data[0]['type']) === 'categories' &&
                                                        $row['id'] == $fetched_data[0]['type_id']
                                                    ) ? 'selected' : '';
                                                    ?>
                                                    <option value="<?= $row['id'] ?>" <?= $selected ?>>
                                                        <?= htmlspecialchars($row['name']) ?>
                                                    </option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <?php $hiddenStatus = (isset($fetched_data[0]['id']) && $fetched_data[0]['type'] == 'brand') ? '' : 'd-none' ?>
                                    <div class="form-group slider-brand <?= $hiddenStatus ?> ">

                                        <label for="brand_id" class="mt-2"> Brands <span
                                                class='text-danger text-sm'>*</span></label>
                                        <select name="brand_id" id="slider_brand_id"
                                            class="form-control slider-select2 mt-2" data-placeholder="Select brand">
                                            <option value="">Select brand </option>
                                            <?php


                                            if (!empty($brands)) {
                                                foreach ($brands as $row) {
                                                    $selected = ($row['brand_id'] == $fetched_data[0]['type_id'] && strtolower($fetched_data[0]['type']) == 'brands') ? 'selected' : '';
                                                    ?>
                                                    <option value="<?= $row['brand_id'] ?>" <?= $selected ?>>
                                                        <?= $row['brand_name'] ?>
                                                    </option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <?php $hiddenStatus = (isset($fetched_data[0]['id']) && $fetched_data[0]['type'] == 'products') ? '' : 'd-none' ?>
                                    <div class="form-group row slider-products <?= $hiddenStatus ?>">
                                        <label for="product_id" class="control-label mt-2">Products <span
                                                class='text-danger text-sm'>*</span></label>
                                        <div class="col-md-12 mb-4 mt-2">
                                            <select name="product_id" class="search_product w-100"
                                                data-placeholder=" Type to search and select products"
                                                onload="multiselect()">
                                                <?php
                                                if (isset($fetched_data[0]['id']) && $fetched_data[0]['type'] == 'products') {
                                                    $product_details = fetch_details('products', ['id' => $fetched_data[0]['type_id']], 'id,name');
                                                    if (!empty($product_details)) {
                                                        ?>
                                                        <option value="<?= $product_details[0]['id'] ?>" selected>
                                                            <?= $product_details[0]['name'] ?>
                                                        </option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <?php $hiddenStatus = (isset($fetched_data[0]['id']) && $fetched_data[0]['type'] == 'slider_url') ? '' : 'd-none' ?>
                                <div class="form-group slider-url <?= $hiddenStatus ?> ">

                                    <label for="slider_url" class="mt-2">
                                        Link <span class="text-danger text-sm">*</span>
                                    </label>

                                    <input type="text" class="form-control mt-2" placeholder="https://example.com"
                                        name="link"
                                        value="<?= isset($fetched_data[0]['link']) ? output_escaping($fetched_data[0]['link']) : '' ?>">
                                </div>
                                <div class="form-group mt-2">
                                    <div><label for="image" class="mt-2">Slider Image <span
                                                class='text-danger text-sm'>*</span><small>(Recommended Size : 1648 x
                                                610 pixels)</small></label></div>
                                    <div class="col-sm-10 mt-2">
                                        <div class='col-md-3'><a
                                                class="uploadFile img btn btn-primary text-white btn-sm"
                                                data-input='image' data-isremovable='0'
                                                data-is-multiple-uploads-allowed='0' data-toggle="modal"
                                                data-target="#media-upload-modal" value="Upload Photo"><i
                                                    class='fa fa-upload'></i> Upload</a></div>
                                        <?php
                                        if (file_exists(FCPATH . @$fetched_data[0]['image']) && !empty(@$fetched_data[0]['image'])) { ?>
                                            <input type="hidden" name="image" value='<?= $fetched_data[0]['image'] ?>'>
                                            <?php $fetched_data[0]['image'] = get_image_url($fetched_data[0]['image'], 'thumb', 'sm');
                                            ?>
                                            <label class="text-danger mt-3">*Only Choose When Update is necessary</label>
                                            <div class="container-fluid row image-upload-section">
                                                <div
                                                    class="col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image">
                                                    <div class='image-upload-div'><img class="img-fluid mb-2"
                                                            src="<?= $fetched_data[0]['image'] ?>" alt="Image Not Found">
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        } else { ?>
                                            <div class="container-fluid row image-upload-section">
                                                <div
                                                    class="col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image d-none">
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success slider_update"
                                        id="submit_btn"><?= (isset($fetched_data[0]['id'])) ? 'Update Slider' : 'Add Slider' ?></button>
                                </div>

                            </div>
                        </form>
                    </div>
                    <!--/.card-->
                </div>
                <div class="col-md-7 main-content">
                    <div class="card content-area p-4">

                        <div class="card-innr">
                            <div class="gaps-1-5x"></div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="type_filter" class="control-label">Filter by Type</label>
                                    <select id="type_filter" class="form-control"
                                        onchange="$('.table-striped').bootstrapTable('refresh')">
                                        <option value="">All</option>
                                        <option value="default">Default</option>
                                        <option value="categories">Category</option>
                                        <option value="brand">Brand</option>
                                        <option value="products">Product</option>
                                        <option value="slider_url">Slider URL</option>
                                    </select>
                                </div>
                            </div>
                            <table class='table-striped' data-toggle="table"
                                data-url="<?= base_url('admin/slider/view_slider') ?>" data-click-to-select="true"
                                data-side-pagination="server" data-pagination="true"
                                data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true"
                                data-show-refresh="true" data-trim-on-search="false" data-sort-name="id"
                                data-sort-order="desc" data-mobile-responsive="true" data-toolbar=""
                                data-show-export="true" data-maintain-selected="true"
                                data-query-params="sliderTypeQueryParams">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true">ID</th>
                                        <th data-field="type" data-sortable="false">Type</th>
                                        <th data-field="type_id" data-sortable="true">Type Name</th>
                                        <th data-field="image" data-sortable="true" class="col-md-6">Image</th>
                                        <th data-field="link" data-sortable="true" data-align='center'>Link</th>
                                        <th data-field="operate" data-sortable="false">Action</th>
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
<script>

</script>