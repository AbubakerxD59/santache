<main>
    <section class="container py-5">
        <div class="row">
            <div class="col-md-3 myaccount-navigation py-3">
                <?php $this->load->view('front-end/' . THEME . '/pages/my-account-sidebar') ?>
            </div>
            <div class="col-md-9">
                <div class="padding-16-30">
                    <div class="mb-3">
                        <h4 class="section-title"><?= label('your_wishlist', 'Your Wishlist') ?></h4>
                    </div>
                    <div class="wishlist-group-head d-flex flex-wrap gap-3 justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="m-0"><?= label('wishlist', 'Wishlist') ?></h5>
                        </div>
                    </div>
                    
                    <div class="row wishlist-product-container m-0">
                        <?php
                        if (isset($products) && !empty($products)) {
                            foreach ($products as $row) { 
                                if ($row['status'] == 0) {
                                    continue;
                                }?>
                                <div class="col-lg-4 col-md-6 col-12 mb-4 fav-product" data-product-id="<?= $row['id'] ?>">
                                    <div class="wishlist-product-actions d-flex mb-1">
                                        <div class="wishlist-product-remove">
                                            <a href="#" class="align-middle gray-700 gray-500-hover favremove">
                                                <ion-icon name="close-outline" class="vertical-align-middle" data-product-id="<?= $row['id'] ?>"></ion-icon>
                                                <?= label('remove', 'remove') ?>
                                            </a>
                                        </div>
                                    </div>
                                    <a href="<?= base_url('products/details/' . $row['slug']) ?>" class="text-reset text-decoration-none">
                                        <div class="card wishlist-card overflow-hidden product-card" data-product-id="<?= $row['id'] ?>">
                                            <div class="product-img">
                                                <img class="pic-1" src="<?= $row['image_sm'] ?>">
                                            </div>
                                            <div class="card-body">
                                                <h4 class="card-title"><?= $row['name'] ?></h4>
                                                <div class="d-flex flex-column">
                                                    <input id="input-3-ltr-star-md" name="input-3-ltr-star-md" class="kv-ltr-theme-svg-star rating-loading" value="<?= $row['rating'] ?>" dir="ltr" data-size="xs" data-show-clear="false" data-show-caption="false" readonly>
                                                    <h4 class="card-price">
                                                        <?php $price = get_price_range_of_product($row['id']);
                                                        echo $price['range'];
                                                        ?></span></h4>
                                                </div>
                                                <?php
                                                if (count($row['variants']) <= 1) {
                                                    $variant_id = $row['variants'][0]['id'];
                                                    $modal = "";
                                                    $class_modal = "";
                                                } else {
                                                    $variant_id = "";
                                                    $modal = "#quickview";
                                                    $class_modal = "quickview-trigger";
                                                }
                                                ?>
                                                <a href="" class="btn add-in-cart-btn add_to_cart w-100 <?= $class_modal?>" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-bs-toggle="modal" data-bs-target="<?= $modal ?>"><span class="add-in-cart"><?= label('add_to_cart', 'Add to Cart') ?></span><span class="add-in-cart-icon"><i class="fa-solid fa-cart-shopping
                                                color-white"></i></span></a>
                                            </div>
                                            <div class="product-icon-onhover">
                                                <div class="product-icon-spacebtw">
                                                    <?php $variant_id = isset($product_row['variants']) ? (count($product_row['variants']) <= 1) : ""; ?>
                                                    <div class="shuffle-box">
                                                        <a class="compare text-reset text-decoration-none shuffle" data-tip="Compare" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $variant_id ?>">
                                                            <ion-icon name="shuffle-outline" class="ion-icon-hover pointer shuffle"></ion-icon>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-icon-spacebtw">
                                                    <div class="quick-search-box">
                                                        
                                                        <div class="quick-search-box quickview-trigger" data-tip="Quick View" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $product_row['variants'][0]['id'] ?>" data-izimodal-open="#quick-view">
                                                            <ion-icon name="search-outline" class="ion-icon-hover pointer" data-bs-toggle="modal" data-bs-target="#quickview"></ion-icon>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php }
                        } else { ?>
                            <div class="col-lg-11 m-5">
                                <div class="text-center">
                                    <h5 class="h2"><?= label('wishlist_is_empty', 'Wishlist is Empty') ?>.</h5>
                                    <a href="<?= base_url('products') ?>" class="button button-rounded button-warning">
                                        <button class="btn btn-primary">
                                            <?= label('go_to_shop', 'Go to Shop') ?>
                                        </button>
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>