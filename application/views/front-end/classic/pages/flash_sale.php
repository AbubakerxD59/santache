<?php
foreach ($flash_sale as $count_key => $row) {
    fetch_active_flash_sale();
?>

    <!-- Style 4 Design -->

    <div class="product-style-2 product-style-2-left product-section py-2 bg-white mt-2 get_flash_sale_timer">
        <div class="col-12 col-md-12 row products-list mx-auto">
            <div class="col-md-2 col-6">
                <div class="product-grid">
                    <div class="product-image featured-section">
                        <div class="product-image-container">
                            <div class="featured-section-title">
                                <div class="col-md-12 text-left">
                                    <p class="d-none get_e_time" data-value="<?= $row['id'] ?>">
                                        <?php print_r($row['end_date']); ?>
                                    </p>

                                    <div class="flash_sale_timer mt-5 col-center p-1 rounded h6" id="timer-<?= $row['id'] ?>" data-value="<?= $row['id'] ?>" data-value="<?php print_r($row['end_date']); ?>">
                                    </div>

                                    <h3 class="section-title offers-title"><?= ucfirst($row['title']) ?></h3>
                                    <div class="title-sm"><?= strip_tags($row['short_description']) ?></div>
                                    <div class="col-6 mt-1"><a href="<?= base_url('products/flash_sale/' . $row['id'] . '/' . $row['slug']) ?>" class="featured-section-view-more"><?= !empty($this->lang->line('view_more')) ? $this->lang->line('view_more') : 'View More' ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php foreach ($row['product_details'] as $product_row) {

                $sale_price = get_flash_sale_price($product_row['variants'][0]['price'], $row['discount']);


            ?>
                <div class="col-md-2 col-6">
                    <div class="product-grid">
                        <aside class="add-fav">
                            <button type="button" class="btn far fa-heart add-to-fav-btn <?= ($product_row['is_favorite'] == 1) ? 'fa text-danger' : '' ?>" data-product-id="<?= $product_row['id'] ?>"></button>
                        </aside>
                        <div class="product-image">
                            <div class="product-image-container">
                                <a href="<?= base_url('products/details/' . $product_row['slug']) ?>">
                                    <img class="pic-1 lazy" data-src="<?= $product_row['image'] ?>">
                                </a>
                            </div>
                            <ul class="social">
                                <li>
                                    <a href="#" class="quick-view-btn" data-tip="Quick View" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $product_row['variants'][0]['id'] ?>" data-izimodal-open="#quick-view">
                                        <i class="fa fa-search"></i>
                                    </a>
                                </li>
                                <li>
                                    <?php
                                    if (count($product_row['variants']) <= 1) {
                                        $variant_id = $product_row['variants'][0]['id'];
                                        $modal = "";
                                    } else {
                                        $variant_id = "";
                                        $modal = "#quick-view";
                                    }
                                    ?>
                                    <?php
                                    $variant_price = (isset($sale_price) && !empty($sale_price) ?  $sale_price : $product_row['variants'][0]['special_price']);
                                    $data_min = (isset($product_row['minimum_order_quantity']) && !empty($product_row['minimum_order_quantity'])) ? $product_row['minimum_order_quantity'] : 1;
                                    $data_step = (isset($product_row['minimum_order_quantity']) && !empty($product_row['quantity_step_size'])) ? $product_row['quantity_step_size'] : 1;
                                    $data_max = (isset($product_row['total_allowed_quantity']) && !empty($product_row['total_allowed_quantity'])) ? $product_row['total_allowed_quantity'] : 0;
                                    ?>
                                    <a href="#" data-tip="Add to Cart" class="add_to_cart" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-product-title="<?= $product_row['name'] ?>" data-product-image="<?= $product_row['image']; ?>" data-product-price="<?= $variant_price; ?>" data-min="<?= $data_min; ?>" data-step="<?= $data_step; ?>" data-product-description="<?= $product_row['short_description']; ?>" data-izimodal-open="<?= $modal ?>">
                                        <i class="fa fa-shopping-cart"></i>
                                    </a>
                                </li>
                                <li>
                                    <?php
                                    if (count($product_row['variants']) <= 1) {
                                        $variant_id = $product_row['variants'][0]['id'];
                                    } else {
                                        $variant_id = "";
                                    }
                                    ?>
                                    <a href="#" class="compare" data-tip="Compare" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $variant_id ?>">
                                        <i class="fa fa-random"></i>
                                    </a>
                                </li>
                            </ul>
                            <?php if (isset($product_row['min_max_price']['special_price']) && $product_row['min_max_price']['special_price'] != '' && $product_row['min_max_price']['special_price'] != 0 && $product_row['min_max_price']['special_price'] < $product_row['min_max_price']['min_price']) { ?>
                                <span class="product-new-label"><?= !empty($this->lang->line('sale')) ? $this->lang->line('sale') : 'Sale' ?></span>
                                <span class="product-discount-label"><?= $row['discount'] ?>%</span>
                            <?php } ?>
                        </div>
                        <div itemscope itemtype="https://schema.org/Product">
                            <?php if (isset($product_row['rating']) && isset($product_row['no_of_rating']) && !empty($product_row['no_of_rating']) &&  !empty($product_row['rating']) && $product_row['no_of_rating'] != "") { ?>
                                <div class="col-md-12 mb-3 product-rating-small" dir="ltr" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
                                    <meta itemprop="reviewCount" content="<?= $product_row['no_of_rating'] ?>" />
                                    <meta itemprop="ratingValue" content="<?= $product_row['rating'] ?>" />
                                    <input type="text" class="kv-fa rating-loading" value="<?= $product_row['rating'] ?>" data-size="sm" title="" readonly>
                                </div>
                            <?php } else { ?>
                                <div class="col-md-12 mb-3 product-rating-small" dir="ltr">
                                    <input type="text" class="kv-fa rating-loading" value="<?= $product_row['rating'] ?>" data-size="sm" title="" readonly>
                                </div>
                            <?php } ?>
                            <div class="product-content">
                                <h3 class="title" title="<?= $product_row['name'] ?>" itemprop="name">
                                    <a href="<?= base_url('products/details/' . $product_row['slug']) ?>"><?= $product_row['name'] ?></a>
                                </h3>
                                <div itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                    <meta itemprop="price" content="<?= $product_row['variants'][0]['price']; ?>" />
                                </div>
                                <div class="price mb-1">
                                    <?php
                                    $system_settings = get_settings('system_settings', true);
                                    $currency = (isset($system_settings['currency']) && !empty($system_settings['currency'])) ? $system_settings['currency'] : '';

                                    echo $currency . " " . $sale_price;
                                    ?>
                                </div>
                                <?php
                                $variant_price = (isset($sale_price) && !empty($sale_price) ?  $sale_price : $product_row['variants'][0]['special_price']);

                                $data_min = (isset($product_row['minimum_order_quantity']) && !empty($product_row['minimum_order_quantity'])) ? $product_row['minimum_order_quantity'] : 1;
                                $data_step = (isset($product_row['minimum_order_quantity']) && !empty($product_row['quantity_step_size'])) ? $product_row['quantity_step_size'] : 1;
                                $data_max = (isset($product_row['total_allowed_quantity']) && !empty($product_row['total_allowed_quantity'])) ? $product_row['total_allowed_quantity'] : 0;
                                ?>
                                <a href="#" class="add-to-cart add_to_cart" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-product-title="<?= $product_row['name'] ?>" data-product-image="<?= $product_row['image'] ?>" data-product-price="<?= $variant_price; ?>" data-min="<?= $data_min; ?>" data-step="<?= $data_step; ?>" data-product-description="<?= $product_row['short_description']; ?>" data-izimodal-open="<?= $modal ?>"><i class="fas fa-cart-plus"></i> <?= !empty($this->lang->line('add_to_cart')) ? $this->lang->line('add_to_cart') : 'Add To Cart' ?></a>
                            </div>
                        </div>
                    </div>
                </div>

            <?php } ?>
        </div>
    </div>
<?php
} ?>