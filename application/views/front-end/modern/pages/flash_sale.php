<?php
foreach ($flash_sale as $count_key => $row) {
    fetch_active_flash_sale();
?>
    <main>
        <!-- Style 4 Design -->
        <div class="product-style-2 product-style-2-left product-section pt-4 get_flash_sale_timer">
            <p class="d-none get_e_time" data-value="<?= $row['id'] ?>">
                <?php print_r($row['end_date']); ?>
            </p>
            <div class="container-fluid bg-gradient-design mb-4 mb-md-5">
                <div class="container pt-4">
                    <div class="row">
                        <div class="col-md-12 col-12 text-center align-self-center">
                            <div class="sale-section">
                                <h4 class="banner-heading default-cursor"><?= ucfirst($row['title']) ?></h4>
                                <p class="banner-paragraph  default-cursor"><?= strip_tags($row['short_description']) ?>
                                </p>
                            </div>
                            <div class="flash_sale_timers countdown" id="timer-<?= $row['id'] ?>" data-value="<?= $row['id'] ?>" data-value="<?php print_r($row['end_date']); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="swiper mySwiper3 swiper-arrow swiper-wid ">
                        <div class="swiper-wrapper grab" <?= ($is_rtl == true) ? "dir='rtl'" : ""; ?>>
                            <?php foreach ($row['product_details'] as $product_row) {
                                $sale_price = get_flash_sale_price($product_row['variants'][0]['price'], $row['discount']);
                            ?>
                                <div class="swiper-slide background-none">
                                    <a href="<?= base_url('products/details/' . $product_row['slug']) ?>" class="text-reset text-decoration-none">
                                        <div class="card card-h-418 product-card" data-product-id="<?= $product_row['id'] ?>">
                                            <div class="product-img">
                                                <img alt=" " class="" src="<?= $product_row['image_sm'] ?>" />
                                            </div>
                                            <div class="card-body">
                                                <h4 class="card-title"><?= word_limit(output_escaping(str_replace('\r\n', '&#13;&#10;', $product_row['name']))) ?></h4>
                                                <p class="card-text"><?= output_escaping(str_replace('\r\n', '&#13;&#10;', word_limit($product_row['category_name']))) ?></p>
                                                <p class="card-text"><?php if (isset($product_row['min_max_price']['special_price']) && $product_row['min_max_price']['special_price'] != '' && $product_row['min_max_price']['special_price'] != 0 && $product_row['min_max_price']['special_price'] < $product_row['min_max_price']['min_price']) { ?>
                                                        <span class="product-new-label"><?= !empty($this->lang->line('sale')) ? $this->lang->line('sale') : 'Sale' ?></span>
                                                        <span class="product-discount-label"><?= $row['discount'] ?>%</span>
                                                    <?php } ?>
                                                </p>
                                                <div class="d-flex flex-column">
                                                    <input id="input-3-ltr-star-md" name="input-3-ltr-star-md" class="kv-ltr-theme-svg-star rating-loading" value="<?= $product_row['rating'] ?>" dir="ltr" data-size="xs" data-show-clear="false" data-show-caption="false" readonly>
                                                    <h4 class="card-price">
                                                        <?php
                                                        $system_settings = get_settings('system_settings', true);
                                                        $currency = (isset($system_settings['currency']) && !empty($system_settings['currency'])) ? $system_settings['currency'] : '';
                                                        echo $currency . " " . $sale_price;
                                                        ?></h4>
                                                </div>
                                                <?php
                                                if (count($product_row['variants']) <= 1) {
                                                        $variant_id = $product_row['variants'][0]['id'];
                                                        $modal = "";
                                                        $class_modal = "";
                                                    } else {
                                                        $variant_id = "";
                                                        $class_modal = "quickview-trigger";
                                                        $modal = "#quickview";
                                                    }
                                                ?>
                                                <?php $variant_price = ($product_row['variants'][0]['special_price'] > 0 && $product_row['variants'][0]['special_price'] != '') ? $product_row['variants'][0]['special_price'] : $product_row['variants'][0]['price'];
                                                $data_min = (isset($product_row['minimum_order_quantity']) && !empty($product_row['minimum_order_quantity'])) ? $product_row['minimum_order_quantity'] : 1;
                                                $data_step = (isset($product_row['minimum_order_quantity']) && !empty($product_row['quantity_step_size'])) ? $product_row['quantity_step_size'] : 1;
                                                $data_max = (isset($product_row['total_allowed_quantity']) && !empty($product_row['total_allowed_quantity'])) ? $product_row['total_allowed_quantity'] : 0;
                                                ?>

                                                <a href="#" data-tip="Add to Cart" class="btn add-in-cart-btn add_to_cart w-100  <?= $class_modal?>" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-product-title="<?= $product_row['name'] ?>" data-product-image="<?= $product_row['image']; ?>" data-product-price="<?= $variant_price; ?>" data-min="<?= $data_min; ?>" data-step="<?= $data_step; ?>" data-product-description="<?= $product_row['short_description']; ?>" data-bs-toggle="modal" data-bs-target="<?= $modal ?>"><span class="add-in-cart">
                                                    <?=label('add_to_cart','Add to Cart')?></span><span class="add-in-cart-icon"><i class="fa-solid fa-cart-shopping
                                                color-white"></i></span></a>

                                            </div>
                                            <div class="product-icon-onhover">
                                                <div class="product-icon-spacebtw">
                                                    <div class="shuffle-box">
                                                        <a class="compare text-reset text-decoration-none shuffle" data-tip="Compare" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $variant_id ?>">
                                                            <ion-icon name="shuffle-outline" class="ion-icon-hover pointer shuffle"></ion-icon>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-icon-spacebtw">
                                                    <div class="add-to-fav-btn" title="like" data-product-id="<?= $product_row['id'] ?>">
                                                        <ion-icon class="ion-icon ion-icon-hover <?= ($product_row['is_favorite'] == 1) ? 'heart text-danger' : 'heart-outline text-dark' ?>" name="<?= ($product_row['is_favorite'] == 1) ? 'heart' : 'heart-outline' ?>"></ion-icon>
                                                    </div>
                                                </div>
                                                <div class="product-icon-spacebtw">
                                                    <div class="quick-search-box quickview-trigger" data-tip="Quick View" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $product_row['variants'][0]['id'] ?>">
                                                        <ion-icon name="search-outline" class="ion-icon-hover pointer" data-bs-toggle="modal" data-bs-target="#quickview"></ion-icon>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
<?php
} ?>