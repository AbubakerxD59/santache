<!--  -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header mt-4">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-8 ">
                    <h4>View Sale </h4>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active"><a href="<?= base_url('admin/flash_sale/') ?>">View Sale</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->


        <!-- Default box -->
        <div class="container-fluid">
            <div class="row m-2 shadow-sm rounded bg-white overflow-hidden">
                <!-- Image Section -->
                <div class="col-md-5 p-0 d-flex align-items-center justify-content-center bg-light" style="min-height: 400px;">
                    <div class="p-4 text-center w-100">
                        <div class="tab-pane active" id="pic-1">
                            <?php
                            $sale_image = !empty($sale_details['image']) ? base_url($sale_details['image']) : base_url() . NO_IMAGE;
                            ?>
                            <a href="<?= $sale_image ?>" data-toggle="lightbox" data-gallery="product-gallery">
                                <img src="<?= $sale_image ?>" class="img-fluid" style="max-height: 400px; object-fit: contain;">
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Details Section -->
                <div class="col-md-7 p-4">
                    <?php
                    $ids = explode(',', $sale_details['product_ids']);
                    $products = $this->db
                        ->select('name')
                        ->where_in('id', $ids)
                        ->get('products')
                        ->result_array();
                    ?>

                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h2 class="font-weight-bold text-dark mb-0"><?= $sale_details['title'] ?></h2>
                        <span class="badge badge-danger p-2 px-3 shadow-sm" style="font-size: 1.1rem; color:green;">
                            <i class="fas text-success fa-tag mr-1"></i> <?= $sale_details['discount'] ?>% OFF
                        </span>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted" style="line-height: 1.6;">
                            <?= nl2br(strip_tags($sale_details['short_description'])) ?>
                        </h6>
                    </div>

                    <div class="row mb-4 p-3 bg-light rounded mx-1">
                        <div class="col-sm-6 border-right">
                            <p class="mb-1 text-muted text-uppercase font-weight-bold" style="font-size: 0.8rem;">
                               Start Date
                            </p>
                            <h5 class="font-weight-bold text-dark mb-0"><?= $sale_details['start_date'] ?></h5>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-1 text-muted text-uppercase font-weight-bold" style="font-size: 0.8rem;">
                              </i>End Date
                            </p>
                            <h5 class="font-weight-bold text-dark mb-0"><?= $sale_details['end_date'] ?></h5>
                        </div>
                    </div>

                    <h5 class="font-weight-bold mb-3 border-bottom pb-2">
                        Included Products
                    </h5>
                    <ul class="list-unstyled row pl-2">
                        <?php foreach ($products as $product): ?>
                            <li class="col-md-6 mb-2 d-flex align-items-center text-secondary">
                                <i class="fas fa-check-circle text-success mr-2"></i> 
                                <span class="text-truncate" title="<?= htmlspecialchars($product['name']) ?>">
                                    <?= htmlspecialchars($product['name']) ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <?php
        $product_details = fetch_details('products', "", "id,name,short_description,image", "", "", "", "", 'id', $ids);
        ?>
        <div class="row m-3">
            <?php
            foreach ($product_details as $product_data) {
                ?>
                <div class="col-md-3">
                    <div class="card shadow p-3 mb-5 bg-white">
                        <div class="shop-item-image d-flex justify-content-center">
                            <?php $product_image = !empty($product_data['image']) ? base_url($product_data['image']) : base_url() . NO_IMAGE; ?>
                            <a href="<?= base_url("admin/product/view-product?edit_id=" . $product_data['id']) ?>"
                                class=" mw-100 mh-100">
                                <img class=" mw-100 mh-100" src="<?= $product_image ?>" alt="<?= $product_data['name'] ?> - image">
                            </a>
                        </div>
                        <div class="card-body">
                            <h4 class="my-2"><?= $product_data['name'] ?></h4>
                            <p class="card-text">
                                <?= htmlspecialchars(
                                    str_replace(["\\r", "\\n"], ' ', $product_data['short_description'])
                                ) ?>
                            </p>
                            <div class="price mb-1">
                                <?php $price = get_price_range_of_product($product_data['id']);
                                echo $price['range'];
                                ?>
                            </div>
                            <div class="text-center">
                                <a href="<?= base_url("admin/product/view-product?edit_id=" . $product_data['id']) ?>"
                                    class="  btn btn-sm btn-info">View Product</a>
                            </div>
                        </div>

                    </div>
                </div>
            <?php } ?>
        </div>
    </section>
</div>
<!-- /.content -->