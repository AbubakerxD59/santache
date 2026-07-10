<?php $total_images = 0; ?>
<main>
    <section class="container py-4">
        <div class="Product-Detail-card row">
            <!-- pc screen product photo view -->
            <div class="d-none d-lg-block product-img-section-md col-lg-6 row g-0 align-content-start">
                <div class="row align-items-center d-flex flex-row-reverse my-2">
                    <div class="col-md-10">
                        <div class="swiper mySwiper-preview gallery-top-1">
                            <div class="swiper-wrapper">
                                <div class="product-swiper-slide swiper-slide">
                                    <link itemprop="image" href="<?= $product['product'][0]['image'] ?>" />
                                    <img src="<?= $product['product'][0]['image'] ?>" class="zoom_03">
                                </div>
                                <?php
                                $variant_images_md = !empty($product['product'][0]['variants']) && is_array($product['product'][0]['variants'])
                                    ? array_column($product['product'][0]['variants'], 'images_md')
                                    : [];
                                if (!empty($variant_images_md)) {
                                    foreach ($variant_images_md as $variant_images) {
                                        if (!empty($variant_images) && is_array($variant_images)) {
                                            foreach ($variant_images as $image) {
                                                ?>
                                                <div class="product-swiper-slide swiper-slide">
                                                    <link itemprop="image" href="<?= $image ?>" />
                                                    <img src="<?= $image ?>" class="zoom_03" alt="">
                                                </div>
                                            <?php }
                                        }
                                    }
                                }
                                ?>
                                <?php
                                if (!empty($product['product'][0]['other_images']) && isset($product['product'][0]['other_images'])) {
                                    foreach ($product['product'][0]['other_images'] as $other_image) {
                                        $total_images++;
                                        ?>
                                        <div class="product-swiper-slide swiper-slide">
                                            <link itemprop="image" href="<?= $other_image ?>" />
                                            <img src="<?= $other_image ?>" class="zoom_03">
                                        </div>
                                    <?php }
                                } ?>
                                <?php
                                if (isset($product['product'][0]['video_type']) && !empty($product['product'][0]['video_type'])) {
                                    $total_images++;
                                    ?>
                                    <div class="swiper-slide">
                                        <?php if ($product['product'][0]['video_type'] == 'self_hosted') { ?>
                                            <video controls width="400" height="auto"
                                                src="<?= $product['product'][0]['video'] ?>">
                                                Your browser does not support the video tag.
                                            </video>
                                        <?php } else if ($product['product'][0]['video_type'] == 'youtube' || $product['product'][0]['video_type'] == 'vimeo') {
                                            if ($product['product'][0]['video_type'] == 'vimeo') {
                                                $url = explode("/", $product['product'][0]['video']);
                                                $id = end($url);
                                                $url = 'https://player.vimeo.com/video/' . $id;
                                            } else if ($product['product'][0]['video_type'] == 'youtube') {
                                                if (strpos($product['product'][0]['video'], 'watch?v=') !== false) {
                                                    $url = str_replace("watch?v=", "embed/", $product['product'][0]['video']);
                                                } else if (strpos($product['product'][0]['video'], "youtu.be/") !== false) {
                                                    $url = explode("/", $product['product'][0]['video']);
                                                    $url = "https://www.youtube.com/embed/" . end($url);
                                                }
                                            } else {
                                                $url = $product['product'][0]['video'];
                                            } ?>
                                                <iframe width="400" height="auto" src="<?= $url ?>" title="YouTube video player"
                                                    frameborder="0"
                                                    allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture;embedded=true"
                                                    allowfullscreen></iframe>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                    </div>
                    <div class="image-Thumbnail-container col-md-2 p-1">
                        <div class="xzoom-thumbs">
                            <div thumbsSlider="" class="swiper mySwiper-thumb gallery-thumbs-1">
                                <div class="swiper-wrapper">
                                    <div class="product-swiper-slide swiper-slide">
                                        <link itemprop="image" href="<?= $product['product'][0]['image'] ?>" />
                                        <img src="<?= $product['product'][0]['image'] ?>">
                                    </div>
                                    <?php if (!empty($variant_images_md)) {
                                        foreach ($variant_images_md as $variant_images) {
                                            if (!empty($variant_images)) {
                                                foreach ($variant_images as $image) {
                                                    ?>
                                                    <div class="product-swiper-slide swiper-slide">
                                                        <link itemprop="image" href="<?= $image ?>" />
                                                        <img src="<?= $image ?>" data-zoom-image="">
                                                    </div>
                                                <?php }
                                            }
                                        }
                                    } ?>
                                    <?php
                                    if (!empty($product['product'][0]['other_images']) && isset($product['product'][0]['other_images'])) {
                                        foreach ($product['product'][0]['other_images'] as $other_image) { ?>
                                            <div class="product-swiper-slide swiper-slide">
                                                <link itemprop="image" href="<?= $other_image ?>" />
                                                <img src="<?= $other_image ?>">
                                            </div>
                                        <?php }
                                    } ?>
                                    <?php
                                    if (isset($product['product'][0]['video_type']) && !empty($product['product'][0]['video_type'])) {
                                        $total_images++;
                                        ?>
                                        <div class="swiper-slide">
                                            <img src="<?= base_url('assets/admin/images/video-file.png') ?>">
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- mobile screen product photo view -->
            <div class="d-sm-block d-lg-none col-lg-6 row g-0 align-content-start">
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper height-auto">
                        <div class="swiper-slide text-center position-relative"><img
                                src="<?= $product['product'][0]['image'] ?>"></div>
                        <?php
                        if (!empty($product['product'][0]['other_images']) && isset($product['product'][0]['other_images'])) {
                            foreach ($product['product'][0]['other_images'] as $other_image) { ?>
                                <div class="swiper-slide text-center position-relative"><img src="<?= $other_image ?>"></div>
                            <?php }
                        } ?>
                        <?php if (!empty($variant_images_md)) {
                            foreach ($variant_images_md as $variant_images) {
                                if (!empty($variant_images)) {
                                    foreach ($variant_images as $image) {
                                        ?>
                                        <div class="swiper-slide text-center position-relative"><img src="<?= $image ?>"
                                                data-zoom-image=""></div>

                                    <?php }
                                }
                            }
                        } ?>
                        <?php
                        if (isset($product['product'][0]['video_type']) && !empty($product['product'][0]['video_type'])) {
                            $total_images++;
                            ?>
                            <div class="swiper-slide position-relative">
                                <?php if ($product['product'][0]['video_type'] == 'self_hosted') { ?>
                                    <video controls width="100%" height="auto" src="<?= $product['product'][0]['video'] ?>">
                                        Your browser does not support the video tag.
                                    </video>
                                <?php } else if ($product['product'][0]['video_type'] == 'youtube' || $product['product'][0]['video_type'] == 'vimeo') {
                                    if ($product['product'][0]['video_type'] == 'vimeo') {
                                        $url = explode("/", $product['product'][0]['video']);
                                        $id = end($url);
                                        $url = 'https://player.vimeo.com/video/' . $id;
                                    } else if ($product['product'][0]['video_type'] == 'youtube') {
                                        if (strpos($product['product'][0]['video'], 'watch?v=') !== false) {
                                            $url = str_replace("watch?v=", "embed/", $product['product'][0]['video']);
                                        } else {
                                            $url = $product['product'][0]['video'];
                                        }
                                    } else {
                                        $url = $product['product'][0]['video'];
                                    }
                                    ?>
                                        <iframe width="560" height="315" src="<?= $url ?>" title="YouTube video player"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen></iframe>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
                <div class="d-none d-lg-none d-xl-block product-add-section align-self-center px-3">
                    <<div class="input-group plus-minus-input mb-3 num-block">
                        <div class="input-group-button">
                            <button type="button" class="button hollow circle minus-btn minus" data-quantity="minus"
                                data-field="quantity"
                                data-min="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['minimum_order_quantity'])) ? $product['product'][0]['minimum_order_quantity'] : 1 ?>"
                                data-step="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['quantity_step_size'])) ? $product['product'][0]['quantity_step_size'] : 1 ?>">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                        </div>
                        <div class="product-quantity product-sm-quantity border-0 m-0">
                            <input class="input-group-field input-field-cart-modal in-num" type="number" name="qty"
                                value="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['minimum_order_quantity'])) ? $product['product'][0]['minimum_order_quantity'] : 1 ?>"
                                data-step="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['quantity_step_size'])) ? $product['product'][0]['quantity_step_size'] : 1 ?>"
                                data-min="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['minimum_order_quantity'])) ? $product['product'][0]['minimum_order_quantity'] : 1 ?>"
                                data-max="<?= (isset($product['product'][0]['total_allowed_quantity']) && !empty($product['product'][0]['total_allowed_quantity'])) ? $product['product'][0]['total_allowed_quantity'] : '1' ?>">
                        </div>
                        <div class="input-group-button">
                            <button type="button" class="button hollow circle plus-btn plus" data-quantity="plus"
                                data-field="quantity"
                                data-max="<?= (isset($product['product'][0]['total_allowed_quantity']) && !empty($product['product'][0]['total_allowed_quantity'])) ? $product['product'][0]['total_allowed_quantity'] : '1' ?> "
                                data-step="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['quantity_step_size'])) ? $product['product'][0]['quantity_step_size'] : 1 ?>">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="p-3 default-cursor">
                <h4 class="product-title">
                    <?= output_escaping(str_replace('\r\n', '&#13;&#10;', $product['product'][0]['name'])) ?>
                </h4>
                <p class="text-muted"><?= ucfirst($product['product'][0]['short_description']) ?>
                </p>
                <?php if (isset($product['product'][0]['rating']) && isset($product['product'][0]['no_of_ratings']) && !empty($product['product'][0]['no_of_ratings']) && !empty($product['product'][0]['rating']) && $product['product'][0]['no_of_ratings'] != "") { ?>
                    <div class=" d-flex cursor-not-allowed" itemprop="aggregateRating" itemscope
                        itemtype="https://schema.org/AggregateRating">
                        <meta itemprop="reviewCount" content="<?= $product['product'][0]['no_of_ratings'] ?>" />

                        <meta itemprop="ratingValue" content="<?= $product['product'][0]['rating'] ?>" />
                        <input id="input-3-ltr-star-md" name="input-3-ltr-star-md"
                            class="kv-ltr-theme-svg-star rating-loading" value="<?= $product['product'][0]['rating'] ?>"
                            dir="ltr" data-size="xs" data-show-clear="false" data-show-caption="false" readonly>
                        <span class="ms-2"><small>( <?= $product['product'][0]['no_of_ratings'] ?> Reviews)
                            </small></span>
                    </div>
                <?php } ?>
                <div itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                    <?php if ($product['product'][0]['is_on_sale'] == 1) { ?>
                        <meta itemprop="price"
                            content="<?= $product['product'][0]['variants'][0]['sale_final_price']; ?>" />
                    <?php } else { ?>
                        <meta itemprop="price" content="<?= $product['product'][0]['variants'][0]['price']; ?>" />
                    <?php } ?>
                    <meta itemprop="priceCurrency" content="<?= $settings['currency'] ?>" />
                </div>

                <?php if ($product['product'][0]['type'] == "simple_product") { ?>
                    <p class="mb-0 mt-2 price" id="price">
                        <?php
                        $price = ($product['product'][0]['is_on_sale'] == 1) ?
                            number_format($product['product'][0]['variants'][0]['sale_final_price'], 2) :
                            get_price_range_of_product($product['product'][0]['id'])['range'];

                        echo $price;
                        ?>
                        <?php if ($product['product'][0]['variants'][0]['special_price'] < $product['product'][0]['variants'][0]['price'] && $product['product'][0]['variants'][0]['special_price'] != 0) { ?>
                            <sup>
                                <span class="special-price striped-price">
                                    <s><?= !empty($product['product'][0]['variants'][0]['special_price']) &&
                                        $product['product'][0]['variants'][0]['special_price'] != NULL ?
                                        $settings['currency'] . '</i>' .
                                        number_format($product['product'][0]['variants'][0]['price']) : '' ?>
                                    </s>
                                </span>
                            </sup>
                        <?php } ?>
                    </p>
                <?php } else { ?>
                    <p class="mb-0 mt-2 price text-muted">
                        <span id="price" class='font-size-20px'>
                            <?php
                            $price = ($product['product'][0]['is_on_sale'] == 1) ?
                                $product['product'][0]['variants'][0]['sale_final_price'] :
                                $product['product'][0]['variants'][0]['special_price'];

                            echo $settings['currency'] . number_format($price, 2);
                            ?>
                        </span>
                        <?php if ($product['product'][0]['variants'][0]['special_price'] < $product['product'][0]['variants'][0]['price'] && $product['product'][0]['variants'][0]['special_price'] != 0) { ?>
                            <sup>
                                <span class="special-price striped-price text-danger" id="product-striped-price-div">
                                    <s id="striped-price">
                                        <?= $settings['currency'] . number_format($product['product'][0]['variants'][0]['price'], 2); ?>
                                    </s>
                                </span>
                            </sup>
                        <?php } ?>
                    </p>
                <?php } ?>
                <?php
                $color_code = $style = "";
                $product['product'][0]['variant_attributes'] = isset($product['product'][0]['variant_attributes']) ? array_values($product['product'][0]['variant_attributes']) : "";

                if (isset($product['product'][0]['variant_attributes']) && !empty($product['product'][0]['variant_attributes'])) {

                    ?>

                    <?php
                    foreach ($product['product'][0]['variant_attributes'] as $attribute) {

                        $attribute_values = explode(',', $attribute['values']);
                        $attribute_ids = explode(',', $attribute['ids']);
                        $swatche_types = explode(',', $attribute['swatche_type']);
                        $swatche_values = explode(',', $attribute['swatche_value']);
                        for ($i = 0; $i < count($swatche_types); $i++) {
                            if (!empty($swatche_types[$i])) {
                                $style = '<style> .product-page-details .btn-group>.active { background-color: #ffffff; color: #000000; border: 1px solid black;}</style>';
                            } else if ($swatche_types[$i] == 0) {
                                $style1 = '<style> .product-page-details .btn-group>.active { background-color: var(--primary-color);color: white!important;}</style>';
                            }
                        }
                        ?>
                        <ul class="d-flex gap-2 mb-2 flex-wrap align-items-center list-unstyled quickview-variant-sec"
                            id="<?= $attribute['attr_name'] ?>">
                            <li class="text-dark-emphasis fw-semibold"><?= $attribute['attr_name'] ?> :</li>
                            <?php foreach ($attribute_values as $key => $row) {
                                if (isset($swatche_types[$key]) && $swatche_types[$key] == "1") {
                                    echo '<style> .product-page-details .btn-group>.active { border: 1px solid black;}</style>';
                                    $color_code = "style='background-color:" . (isset($swatche_values[$key]) ? $swatche_values[$key] : '#ffffff') . "; position: relative;'"; ?>

                                    <li class="color-swatch swatch">
                                        <label class="product-color m-0" <?= $color_code ?>>
                                            <input type="radio" name="<?= $attribute['attr_name'] ?>"
                                                value="<?= isset($attribute_ids[$key]) ? $attribute_ids[$key] : '' ?>"
                                                autocomplete="off" class="filter-input attributes">
                                        </label>
                                    </li>
                                <?php } else if (isset($swatche_types[$key]) && $swatche_types[$key] == "2") { ?>
                                    <?= $style ?>
                                        <li class="image-swatch swatch">
                                            <label class="color-swatch-img m-0">
                                                <img class="swatche-image"
                                                    src="<?= isset($swatche_values[$key]) ? $swatche_values[$key] : '' ?>">
                                                <input type="radio" name="<?= $attribute['attr_name'] ?>"
                                                    value="<?= isset($attribute_ids[$key]) ? $attribute_ids[$key] : '' ?>"
                                                    autocomplete="off" class="filter-input attributes">
                                            </label>
                                        </li>
                                <?php } else { ?>
                                    <?= '<style> .product-page-details .btn-group>.active { background-color: var(--primary-color);color: white!important;}</style>'; ?>
                                        <li class="default-swatch swatch">
                                            <label class="m-0 position-relative">
                                                <input type="radio" name="<?= $attribute['attr_name'] ?>"
                                                    value="<?= isset($attribute_ids[$key]) ? $attribute_ids[$key] : '' ?>"
                                                    autocomplete="off" class="filter-input attributes">
                                            <?= $row ?>
                                            </label>
                                        </li>
                                <?php } ?>
                            <?php } ?>
                        </ul>
                        <?php
                    }
                }
                if (!empty($product['product'][0]['variants']) && isset($product['product'][0]['variants'])) {
                    $total_images = 1;
                    foreach ($product['product'][0]['variants'] as $variant) {
                        ?>
                        <input type="hidden" class="variants" name="variants_ids" data-image-index="<?= $total_images ?>"
                            data-name="" value="<?= $variant['variant_ids'] ?>" data-id="<?= $variant['id'] ?>"
                            data-price="<?= $variant['price'] ?>" data-special_price="<?= $variant['special_price'] ?>" />
                        <?php $total_images += count($variant['images']);
                    }
                } ?>
                <p class="mb-1 mt-1"><span
                        class="text-muted"><small><?= label('inclusive_of_all_taxes', '(Inclusive of all taxes)') ?></small></span>
                </p>
            </div>
            <?php
            if (!empty($product['product'][0]['bulk_discount_min_qty']) && !empty($product['product'][0]['bulk_discount_amount'])) { ?>
                <div class="bulk-discount px-3">
                    <p class="mb-0 text-success">
                        <strong><?= label('bulk_order_discount', 'Bulk Order Discount') ?>:</strong>
                        <?= $settings['currency'] . number_format($product['product'][0]['bulk_discount_amount'], 2) ?>
                        <?= label('off_when_you_order', 'off when you order') ?>
                        <?= $product['product'][0]['bulk_discount_min_qty'] ?>     <?= label('or_more', 'or more') ?>
                    </p>
                </div>
            <?php } ?>
            <div>
                <?php
                $indicator = (isset($product['product'][0]['indicator']) && !empty($product['product'][0]['indicator']) ? $product['product'][0]['indicator'] : '');
                if ($indicator == '1') { ?>
                    <span class="text-success">Veg</span>
                <?php } elseif ($indicator == '2') { ?>
                    <span class="text-danger">Non veg</span>
                <?php } ?>
            </div>

            <input type="hidden" class="variants_data" id="variants_data"
                data-name="<?= $product['product'][0]['name'] ?>" data-image="<?= $product['product'][0]['image'] ?>"
                data-id="<?= $variant['id'] ?>" data-price="<?= $variant['price'] ?>"
                data-special_price="<?= $variant['special_price'] ?>">
            <div class="compare-share-btn py-3 px-3">
                <ul class="d-flex gap-3 list-unstyled m-0">
                    <li>
                        <?php
                        if (count($product['product'][0]['variants']) >= 1) {
                            $variant_id = $product['product'][0]['variants'][0]['id'];
                        } else {
                            $variant_id = "";
                        }
                        ?>
                        <a class="btn d-flex p-0 compare text-reset text-decoration-none shuffle" data-tip="Compare"
                            data-product-id="<?= $product['product'][0]['id'] ?>"
                            data-product-variant-id="<?= $variant_id ?>">
                            <img src="<?= base_url("assets/front_end/modern/image/icons/shuffle.png") ?>" alt="">
                            <p><?= label('compare', 'Compare') ?></p>
                        </a>
                    </li>
                    <li>
                        <div class="d-flex align-items-center"><?= label('share', 'Share') ?>: <span
                                class="ms-1 quick_view_share" id="quick_view_share"></span></div>
                    </li>
                </ul>
            </div>
            <div class="row py-3 px-3 product-add-section align-self-center">
                <div itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                    <?php if ($product['product'][0]['is_on_sale'] == 1) { ?>

                        <meta itemprop="price"
                            content="<?= $product['product'][0]['variants'][0]['sale_final_price']; ?>" />
                    <?php } else { ?>

                        <meta itemprop="price" content="<?= $product['product'][0]['variants'][0]['price']; ?>" />
                    <?php } ?>
                    <meta itemprop="priceCurrency" content="<?= $settings['currency'] ?>" />
                </div>
                <?php
                $shiprocket_settings = get_settings('shipping_method', true);
                if ((isset($settings['pincode_wise_deliverability']) && $settings['pincode_wise_deliverability'] == 1) || (isset($shiprocket_settings['local_shipping_method']) && isset($shiprocket_settings['shiprocket_shipping_method']) && $shiprocket_settings['local_shipping_method'] == 1 && $shiprocket_settings['shiprocket_shipping_method'] == 1)) {
                    ?>
                    <form class="mt-2" id="validate-zipcode-form" method="POST">
                        <div class="d-flex flex-wrap gap-2 form-row">
                            <div class=" col-md-6">
                                <input type="hidden" name="product_id" value="<?= $product['product'][0]['id'] ?>">
                                <input type="text" class="form-control" id="zipcode"
                                    placeholder="<?= !empty($this->lang->line('zipcode')) ? $this->lang->line('zipcode') : 'Zipcode' ?>"
                                    name="zipcode" autocomplete="off" required
                                    value="<?= $product['product'][0]['zipcode']; ?>">
                            </div>
                            <button type="submit" class="btn btn-primary"
                                id="validate_zipcode"><?= label('check_availability', 'Check Availability') ?></button>
                        </div>
                        <div class="mt-2" id="error_box">
                            <?php if (!empty($product['product'][0]['zipcode'])) { ?>
                                <p class="m-0 text-<?= ($product['product'][0]['is_deliverable']) ? "success" : "danger" ?>">
                                    Product is <?= ($product['product'][0]['is_deliverable']) ? "" : "not" ?> delivarable on
                                    &quot; <?= $product['product'][0]['zipcode']; ?> &quot; </p>
                            <?php } ?>
                        </div>
                    </form>
                <?php }
                if (isset($settings['city_wise_deliverability']) && $settings['city_wise_deliverability'] == 1 && $shiprocket_settings['shiprocket_shipping_method'] != 1) { ?>
                    <form class="mt-2" id="validate-city-form" method="POST">
                        <div class="d-flex flex-wrap gap-2 form-row">
                            <div class=" col-md-6">
                                <input type="hidden" name="product_id" value="<?= $product['product'][0]['id'] ?>">
                                <input type="text" class="form-control" id="zipcode" placeholder="City" name="city"
                                    autocomplete="off" required value="<?= $product['product'][0]['zipcode']; ?>">
                            </div>
                            <button type="submit" class="btn btn-primary"
                                id="validate_city"><?= label('check_availability', 'Check Availability') ?></button>
                        </div>
                        <div class="mt-2" id="error_box">
                            <?php if (!empty($product['product'][0]['zipcode'])) { ?>
                                <p class="m-0 text-<?= ($product['product'][0]['is_deliverable']) ? "success" : "danger" ?>">
                                    Product is <?= ($product['product'][0]['is_deliverable']) ? "" : "not" ?> delivarable on
                                    &quot; <?= $product['product'][0]['zipcode']; ?> &quot; </p>
                            <?php } ?>
                        </div>
                    </form>
                <?php } ?>

                <div class="input-group plus-minus-input mb-3 num-block">
                    <div class="input-group-button">
                        <button type="button" class="button hollow circle minus-btn minus" data-quantity="minus"
                            data-field="quantity"
                            data-min="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['minimum_order_quantity'])) ? $product['product'][0]['minimum_order_quantity'] : 1 ?>"
                            data-step="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['quantity_step_size'])) ? $product['product'][0]['quantity_step_size'] : 1 ?>">
                            <i class="fa-solid fa-minus"></i>
                        </button>
                    </div>
                    <div class="product-quantity product-sm-quantity border-0 m-0">
                        <input id="prod_qty" class="input-group-field input-field-cart-modal in-num" type="number"
                            name="qty"
                            value="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['minimum_order_quantity'])) ? $product['product'][0]['minimum_order_quantity'] : 1 ?>"
                            data-step="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['quantity_step_size'])) ? $product['product'][0]['quantity_step_size'] : 1 ?>"
                            data-min="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['minimum_order_quantity'])) ? $product['product'][0]['minimum_order_quantity'] : 1 ?>"
                            data-max="<?= (isset($product['product'][0]['total_allowed_quantity']) && !empty($product['product'][0]['total_allowed_quantity'])) ? $product['product'][0]['total_allowed_quantity'] : '1' ?>"
                            data-price="<?= $product['product'][0]['min_max_price']['special_price'] ?>"
                            data-id="<?= $product['product'][0]['id'] ?>">
                    </div>
                    <div class="input-group-button">
                        <button type="button" class="button hollow circle plus-btn plus" data-quantity="plus"
                            data-field="quantity"
                            data-max="<?= (isset($product['product'][0]['total_allowed_quantity']) && !empty($product['product'][0]['total_allowed_quantity'])) ? $product['product'][0]['total_allowed_quantity'] : '1' ?> "
                            data-step="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['quantity_step_size'])) ? $product['product'][0]['quantity_step_size'] : 1 ?>">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <?php
                    // echo "<pre>";
                    // print_r($variant);
                    if ($product['product'][0]['variants'][0]['cart_count'] != 0) { ?>
                        <a class="buttons btn-6-6 extra-small m-0"><button type="submit" title="Add in Cart"
                                class="col-md-12 btn add-btn"><?= label('add_in_cart', 'Add in Cart') ?></button></a>
                    <?php } else { ?>
                        <a href="#" class="">
                            <button type="button" name="add_cart" class="add_to_cart btn btn-primary" id="add_cart"
                                title="Add in Cart" data-product-id="<?= $product['product'][0]['id'] ?>"
                                data-product-title="<?= $product['product'][0]['name'] ?>"
                                data-product-image="<?= $product['product'][0]['image'] ?>"
                                data-product-price="<?= $variant['special_price']; ?>"
                                data-product-description="<?= $product['product'][0]['short_description']; ?>"
                                data-step="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['quantity_step_size'])) ? $product['product'][0]['quantity_step_size'] : 1 ?>"
                                data-min="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['minimum_order_quantity'])) ? $product['product'][0]['minimum_order_quantity'] : 1 ?>"
                                data-max="<?= (isset($product['product'][0]['total_allowed_quantity']) && !empty($product['product'][0]['total_allowed_quantity'])) ? $product['product'][0]['total_allowed_quantity'] : '1' ?>"
                                data-product-variant-id="<?= $variant_id ?>">
                                <?= label('add_in_cart', 'Add in Cart') ?>
                            </button>
                        </a>
                        <a href="<?= base_url("cart") ?>" name="buy_it_now" class="add_to_cart btn btn-success buy_now"
                            id="buy_it_now" title="Buy it now" data-product-id="<?= $product['product'][0]['id'] ?>"
                            data-product-title="<?= $product['product'][0]['name'] ?>"
                            data-product-image="<?= $product['product'][0]['image'] ?>"
                            data-product-price="<?= $variant['special_price']; ?>"
                            data-product-description="<?= $product['product'][0]['short_description']; ?>"
                            data-step="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['quantity_step_size'])) ? $product['product'][0]['quantity_step_size'] : 1 ?>"
                            data-min="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['minimum_order_quantity'])) ? $product['product'][0]['minimum_order_quantity'] : 1 ?>"
                            data-max="<?= (isset($product['product'][0]['total_allowed_quantity']) && !empty($product['product'][0]['total_allowed_quantity'])) ? $product['product'][0]['total_allowed_quantity'] : '1' ?>"
                            data-product-variant-id="<?= $variant_id ?>">
                            <?= label('buy_it_now', 'Buy It Now') ?>
                        </a>
                    <?php } ?>
                    <a href="https://api.whatsapp.com/send?phone= <?= ($settings['whatsapp_number'] != '' && isset($settings['whatsapp_number'])) ? $settings['whatsapp_number'] : ((!defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) ? str_repeat("X", strlen($settings['whatsapp_number']) - 3) . substr($settings['whatsapp_number'], -3) : $settings['whatsapp_number']) ?> &amp;text=Hello, I Seen This <?= $product['product'][0]['name'] ?> In Your Website And I Want to Buy This <?= base_url('products/details/' . $product['product'][0]['slug']) ?>"
                        target="_blank" title="Order From Whatsapp"><button type="button"
                            class="btn whatsapp d-flex justify-content-center align-items-center"><ion-icon
                                name="logo-whatsapp"
                                class="me-2 fs-4"></ion-icon><?= label('order_from_whatsapp', 'Order From Whatsapp') ?></button></a>
                </div>
            </div>
            <?php
            $product_quantity = ($product['product'][0]);
            ?>
            <div class="delivery-service py-3 px-3">
                <ul class="gap-2 list-unstyled m-0">
                    <?php if (!empty($product['product'][0]['warranty_period']) && isset($product['product'][0]['warranty_period'])) { ?>
                        <li>
                            <img src="<?= base_url("assets/front_end/modern/image/icons/warranty.png") ?>" alt="">
                            <p class="m-0 text-muted"><?= $product_quantity['warranty_period'] ?></p>
                            <p class="m-0 text-muted"><?= label('warranty', 'Warranty') ?></p>
                        </li>
                    <?php } else { ?>
                        <li>
                            <img src="<?= base_url("assets/front_end/modern/image/icons/no-warranty.png") ?>" alt="">
                            <p class="m-0 text-muted"><?= label('no_warranty', 'No Warranty') ?></p>
                        </li>
                    <?php }
                    if (!empty($product['product'][0]['guarantee_period']) && isset($product['product'][0]['guarantee_period'])) { ?>
                        <li>
                            <img src="<?= base_url("assets/front_end/modern/image/icons/guarantee.png") ?>" alt="">
                            <p class="m-0 text-muted"><?= $product_quantity['guarantee_period'] ?>
                                <?= label('guarantee', 'Guarantee') ?>
                            </p>
                        </li>
                    <?php } ?>
                    <?php if (!empty($product['product'][0]['cod_allowed']) && isset($product['product'][0]['cod_allowed'])) { ?>
                        <li>
                            <img src="<?= base_url("assets/front_end/modern/image/icons/cash-on-delivery.png") ?>" alt="">
                            <p class="m-0 text-muted"><?= label('cod', 'COD ') ?><?= label('avilable', 'Avilable') ?></p>
                        </li>
                    <?php } else { ?>
                        <li>
                            <img src="<?= base_url("assets/front_end/modern/image/icons/cod-not-avilable.png") ?>" alt="">
                            <p class="m-0 text-muted">
                                <?= label('cod', 'COD ') ?>     <?= label('not_Avilable', 'Not Avilable') ?>
                            </p>
                        </li>
                    <?php } ?>
                    <?php if (!empty($product['product'][0]['is_returnable']) && isset($product['product'][0]['is_returnable'])) { ?>
                        <li>
                            <img src="<?= base_url("assets/front_end/modern/image/icons/return-box.png") ?>" alt="">
                            <p class="m-0 text-muted"><?= label('returnable', 'Returnable') ?></p>
                        </li>
                    <?php } else { ?>
                        <li>
                            <img src="<?= base_url("assets/front_end/modern/image/icons/non-returnable.png") ?>" alt="">
                            <p class="m-0 text-muted"><?= label('non', 'Non ') ?>     <?= label('returnable', 'Returnable') ?>
                            </p>
                        </li>
                    <?php } ?>

                    <?php if (!empty($product['product'][0]['is_cancelable']) && isset($product['product'][0]['is_cancelable'])) { ?>
                        <li>
                            <img src="<?= base_url("assets/front_end/modern/image/icons/cancel.png") ?>" alt="">
                            <p class="m-0 text-muted"><?= label('cancelable', 'cancelable') ?></p>
                        </li>
                    <?php } else { ?>
                        <li>
                            <img src="<?= base_url("assets/front_end/modern/image/icons/cancel.png") ?>" alt="">
                            <p class="m-0 text-muted"><?= label('non', 'Non ') ?>     <?= label('cancelable', 'cancelable') ?>
                            </p>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <?php
            if (!empty($product['product'][0]['attributes']) && isset($product['product'][0]['attributes'])) {
                $attr_names = array();
                ?>
                <div class="specification py-3 px-3">
                    <h4 class="element-title fw-semibold default-cursor"><?= label('specification', 'Specification') ?></h4>
                    <table class="w-100">
                        <tbody>
                            <?php
                            foreach ($product['product'][0]['attributes'] as $child_array) {
                                if (isset($child_array['attr_name'])) {
                                    ?>
                                    <tr>
                                        <td class="col-5"><span class="fw-bold"><?= $child_array['attr_name'] ?></span></td>
                                        <td class="col-7"><span><?= $child_array['value'] ?></span></td>
                                    </tr>
                                <?php }
                            } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>


            <?php
            if (isset($product['product'][0]['description']) && !empty($product['product'][0]['description'])) {
                ?>
                <div class="item-detail pb-3 px-3 overflow-hidden">
                    <h4 class="element-title fw-semibold default-cursor"><?= label('about_this_item', 'About this item') ?>
                    </h4>


                    <?php if (!empty($product['product'][0]['made_in'])) { ?>
                        <hr>
                        <p class="fw-semibold default-cursor">
                            <?= !empty($this->lang->line('made_in')) ? $this->lang->line('made_in') : 'Made In:' ?></strong>
                            <?= ucfirst($product['product'][0]['made_in']) ?>
                        </p>
                        <hr>
                    <?php } ?>

                    <?php if (!empty($product['product'][0]['brand'])) { ?>

                        <p class="fw-semibold default-cursor">
                            <?= !empty($this->lang->line('brand')) ? $this->lang->line('brand') : 'Brand Name:' ?></strong>
                            <?= ucfirst($product['product'][0]['brand']) ?>
                        </p>
                        <p></p>
                        <hr>
                    <?php } ?>


                </div>
            <?php } ?>
        </div>
    </section>

    <!-- product-tabs -->
    <section class="container">
        <div class="row mt-4">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <?php if (isset($product['product'][0]['description']) && !empty($product['product'][0]['description'])) { ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane"
                            type="button" role="tab" aria-controls="home-tab-pane"
                            aria-selected="true"><?= !empty($this->lang->line('description')) ? $this->lang->line('description') : 'Description' ?></button>
                    </li>
                <?php } ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane"
                        type="button" role="tab" aria-controls="profile-tab-pane"
                        aria-selected="false"><?= !empty($this->lang->line('reviews')) ? $this->lang->line('reviews') : 'Reviews' ?></button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane"
                        type="button" role="tab" aria-controls="contact-tab-pane"
                        aria-selected="false"><?= !empty($this->lang->line('faq')) ? $this->lang->line('faq') : 'FAQ' ?></button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab"
                    tabindex="0">
                    <div class="row">
                        <div class="col-12 description overflow-auto description_img">
                            <?= (isset($product['product'][0]['description']) && !empty($product['product'][0]['description'])) ? $product['product'][0]['description'] : "" ?>
                        </div>
                    </div>
                </div>
                <!-- product review tab -->
                <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab"
                    tabindex="0">
                    <?php
                    if (!empty($review_images['total_images'])) {
                        if ($review_images['total_images'] > 0) { ?>
                            <h3 class="review-title"> User Review Images (<span><?= $review_images['total_images'] ?></span>)
                            </h3>
                            <?php
                        }
                    }
                    ?>
                    <div class="row justify-content-center border-radius-10 mt-3">
                        <!-- REVIEW-box -->
                        <?php
                        $rating1 = isset($product_ratings['star_1']) ? $product_ratings['star_1'] : 0;
                        $rating2 = isset($product_ratings['star_2']) ? $product_ratings['star_2'] : 0;
                        $rating3 = isset($product_ratings['star_3']) ? $product_ratings['star_3'] : 0;
                        $rating4 = isset($product_ratings['star_4']) ? $product_ratings['star_4'] : 0;
                        $rating5 = isset($product_ratings['star_5']) ? $product_ratings['star_5'] : 0;

                        $ratings = array(
                            'star_1' => $rating1,
                            'star_2' => $rating2,
                            'star_3' => $rating3,
                            'star_4' => $rating4,
                            'star_5' => $rating5
                        );

                        $highest = max($rating1, $rating2, $rating3, $rating4, $rating5);
                        $highest_rating_key = array_search($highest, $ratings);
                        if ($highest > 0) {
                            ?>
                            <div class="col-md-6">
                                <div class="row customar-review py-4 border-0">
                                    <div class="customar-review-view">
                                        <h4 class="element-title fw-semibold default-cursor m-0 pb-3">
                                            <?= label('customer_reviews', 'Customer Reviews') ?>
                                        </h4>

                                        <div class="text-center">
                                            <h1 class="fw-semibold"><span
                                                    id="no_ratings"><?= $product['product'][0]['no_of_ratings'] ?></span>
                                            </h1>
                                            <input id="input-2-ltr-star-sm" name="rating"
                                                class="kv-ltr-theme-svg-star rating-loading"
                                                value="<?= $product['product'][0]['rating'] ?>" dir="ltr" data-size="sm"
                                                data-show-clear="false" data-show-caption="false" readonly>
                                            <small><span
                                                    id="no_ratings"><?= $product['product'][0]['no_of_ratings'] ?></span>
                                                Reviews</small>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <input id="input-3-ltr-star-md" name="input-3-ltr-star-md"
                                                class="kv-ltr-theme-svg-star rating-loading" value="5" dir="ltr"
                                                data-size="xs" data-show-clear="false" data-show-caption="false" readonly>
                                            <div class="review-line">
                                                <div class="progress" role="progressbar" aria-label="Basic example"
                                                    aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                    <div class="progress-bar bg-success"
                                                        style="width: <?php echo ($rating5 / $highest) * 100; ?>%"></div>
                                                </div>
                                            </div>
                                            <div><?= $product_ratings['star_5'] ?></div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <input id="input-3-ltr-star-md" name="input-3-ltr-star-md"
                                                class="kv-ltr-theme-svg-star rating-loading" value="4" dir="ltr"
                                                data-size="xs" data-show-clear="false" data-show-caption="false" readonly>
                                            <div class="review-line">
                                                <div class="progress" role="progressbar" aria-label="Basic example"
                                                    aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                    <div class="progress-bar bg-success"
                                                        style="width: <?php echo ($rating4 / $highest) * 100; ?>%"></div>
                                                </div>
                                            </div>
                                            <div><?= $product_ratings['star_4'] ?></div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <input id="input-3-ltr-star-md" name="input-3-ltr-star-md"
                                                class="kv-ltr-theme-svg-star rating-loading" value="3" dir="ltr"
                                                data-size="xs" data-show-clear="false" data-show-caption="false" readonly>
                                            <div class="review-line">
                                                <div class="progress" role="progressbar" aria-label="Basic example"
                                                    aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                    <div class="progress-bar bg-success"
                                                        style="width: <?php echo ($rating3 / $highest) * 100; ?>%"></div>
                                                </div>
                                            </div>
                                            <div><?= $product_ratings['star_3'] ?></div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <input id="input-3-ltr-star-md" name="input-3-ltr-star-md"
                                                class="kv-ltr-theme-svg-star rating-loading" value="2" dir="ltr"
                                                data-size="xs" data-show-clear="false" data-show-caption="false" readonly>
                                            <div class="review-line">
                                                <div class="progress" role="progressbar" aria-label="Basic example"
                                                    aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                    <div class="progress-bar bg-warning"
                                                        style="width: <?php echo ($rating2 / $highest) * 100; ?>%"></div>
                                                </div>
                                            </div>
                                            <div><?= $product_ratings['star_2'] ?></div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <input id="input-3-ltr-star-md" name="input-3-ltr-star-md"
                                                class="kv-ltr-theme-svg-star rating-loading" value="1" dir="ltr"
                                                data-size="xs" data-show-clear="false" data-show-caption="false" readonly>
                                            <div class="review-line">
                                                <div class="progress" role="progressbar" aria-label="Basic example"
                                                    aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                    <div class="progress-bar bg-danger"
                                                        style="width: <?php echo ($rating1 / $highest) * 100; ?>%"></div>
                                                </div>
                                            </div>
                                            <div><?= $product_ratings['star_1'] ?></div>
                                        </div>
                                    </div>
                                </div>
                                <nav class="d-flex mt-10">
                                    <?php if (isset($product_ratings) && !empty($product_ratings)) { ?>
                                    <?php } ?>
                                </nav>
                            </div>
                        <?php }
                        ?>

                        <!-- create review -->
                        <?php if ($product['product'][0]['is_purchased'] == 1) {
                            $form_link = (!empty($my_rating)) ? base_url('products/edit-rating') : base_url('products/save-rating');
                            ?>
                            <div class="create-review-section col-md-6 <?= (!empty($my_rating)) ? 'd-none' : '' ?>"
                                id="rating-box">
                                <h4 class="element-title fw-semibold default-cursor m-0 pb-3">
                                    <?= label('create_review', 'Submit a Review') ?>
                                </h4>
                                <form action="<?= $form_link ?>" id="product-rating-form" method="POST">
                                    <div class="post-rating py-2">
                                        <div
                                            class="rating-title overoll-rate-heading d-flex align-items-baseline justify-content-between">
                                            <h5 class="fw-semibold default-cursor">
                                                <?= label('overall_rating', 'Overall Rating') ?>
                                            </h5>
                                            <button type="radio"
                                                class="btn text-primary clear-rating text-hover-effect d-none"
                                                data-target="#star-rating"
                                                title="Clear"><?= label('clear', 'Clear') ?></button>
                                        </div>
                                        <?php if (!empty($my_rating)) { ?>
                                            <input type="hidden" name="rating_id"
                                                value="<?= $my_rating['product_rating'][0]['id'] ?>">
                                        <?php } ?>
                                        <input type="hidden" name="product_id" value="<?= $product['product'][0]['id'] ?>">
                                        <input id="star-rating" name="rating" data-step="1"
                                            class="kv-ltr-theme-svg-star rating-loading star-rating"
                                            value="<?= isset($my_rating['product_rating'][0]['rating']) ? $my_rating['product_rating'][0]['rating'] : '0' ?>"
                                            dir="ltr" data-size="sm" data-show-clear="false" data-show-caption="false">
                                    </div>
                                    <div class="review-textarea py-2">
                                        <h5 class="rating-title fw-semibold default-cursor mb-4">
                                            <?= label('write_your_review', 'Write Your Review') ?>
                                        </h5>
                                        <textarea name="comment" placeholder="Write Your Review Here" class="form-control"
                                            rows="5"><?= isset($my_rating['product_rating'][0]['comment']) ? $my_rating['product_rating'][0]['comment'] : '' ?></textarea>
                                    </div>
                                    <div class="review-textarea py-2">
                                        <h5 class="rating-title fw-semibold default-cursor mb-4">
                                            <?= label('add_photo_or_Video', 'Add Photo') ?>
                                        </h5>
                                        <input type="file" name="images[]" accept="image/x-png,image/gif,image/jpeg"
                                            multiple>
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" title="Submit" class="btn submit-btn"
                                            id="rating-submit-btn"><?= label('submit', 'Submit') ?></button>
                                    </div>
                                </form>
                            </div>
                        <?php } ?>

                        <!--Edit review section  -->
                        <?php if ($product['product'][0]['is_purchased'] == 1 && !empty($my_rating)) {
                            $form_link = (!empty($my_rating)) ? base_url('products/save-rating') : base_url('products/save-rating');
                            ?>
                            <div class="create-review-section col-md-6" id="rating-box">
                                <h4 class="element-title fw-semibold default-cursor m-0 pb-3">
                                    <?= label('edit_your_review', 'Edit Your Review') ?>
                                </h4>
                                <form action="<?= $form_link ?>" id="product-edit-rating-form" method="POST">
                                    <?php if (!empty($my_rating)) { ?>
                                        <input type="hidden" name="rating_id"
                                            value="<?= $my_rating['product_rating'][0]['id'] ?>">
                                    <?php } ?>
                                    <input type="hidden" name="product_id" value="<?= $product['product'][0]['id'] ?>">
                                    <div class="post-rating py-2">
                                        <div
                                            class="rating-title overoll-rate-heading d-flex align-items-baseline justify-content-between">
                                            <h5 class="fw-semibold default-cursor">
                                                <?= label('your_rating', 'Your Rating') ?>
                                            </h5>
                                            <button type="radio"
                                                class="btn text-primary clear-rating text-hover-effect d-none"
                                                data-target="#star-rating"
                                                title="Clear"><?= label('clear', 'Clear') ?></button>
                                        </div>
                                        <input id="star-rating" name="rating" data-step="1"
                                            class="kv-ltr-theme-svg-star rating-loading star-rating"
                                            value="<?= isset($my_rating['product_rating'][0]['rating']) ? $my_rating['product_rating'][0]['rating'] : '0' ?>"
                                            dir="ltr" data-size="sm" data-show-clear="false" data-show-caption="false">
                                    </div>
                                    <div class="review-textarea py-2">
                                        <h5 class="rating-title fw-semibold default-cursor mb-4">
                                            <?= label('your_review', 'Your Review') ?>
                                        </h5>
                                        <textarea name="comment" placeholder="Write Your Review Here" class="form-control"
                                            rows="5"><?= isset($my_rating['product_rating'][0]['comment']) ? $my_rating['product_rating'][0]['comment'] : '' ?></textarea>
                                        <div class="review-textarea py-2">
                                            <h5 class="rating-title fw-semibold default-cursor mb-4">
                                                <?= label('add_photo_or_Video', 'Add Photo') ?>
                                            </h5>
                                            <input type="file" name="images[]" accept="image/x-png,image/gif,image/jpeg"
                                                multiple>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" title="Submit" class="btn submit-btn"
                                                id="edit-rating-submit-btn"><?= label('submit', 'Submit') ?></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="product-reviews-display px-2">
                        <div class="mb-3">
                            <h4 class="element-title fw-semibold m-0">

                                <?php if ((int) $product['product'][0]['no_of_ratings'] > 0): ?>

                                    <span id="no_ratings">
                                        <?= (int) $product['product'][0]['no_of_ratings'] ?>
                                        <?= label('review_of_product', 'Review Of Product') ?>
                                    </span>

                                <?php else: ?>

                                    <div class="d-flex justify-content-center align-items-center mt-2">
                                        <img src="<?= base_url('assets/front_end/modern/image/no-review.webp') ?>"
                                            alt="No Reviews" class="img-fluid" style="max-width: 120px;">
                                    </div>

                                <?php endif; ?>

                                <?= ucfirst(html_escape($product['product'][0]['name'])) ?>

                            </h4>

                        </div>
                        <div class="row gy-3 mb-3 me-0">
                            <?php
                            if (!empty($product_ratings['product_rating']) && is_array($product_ratings['product_rating'])) {
                                foreach ($product_ratings['product_rating'] as $my_row) { ?>
                                    <div class="col-md-6">
                                        <div class="comment-text">
                                            <div class="d-flex flex-wrap justify-content-between mb-1">
                                                <p class="comment-title"><?= $my_row['user_name'] ?></p>
                                                <p class="comment-time"><?= $my_row['data_added'] ?></p>
                                            </div>
                                            <div class="d-flex flex-wrap justify-content-between mb-1">
                                                <input id="star-rating" name="input-3-ltr-star-xs" data-step="1"
                                                    class="kv-ltr-theme-svg-star rating-loading star-rating"
                                                    value="<?= $my_row['rating'] ?>" dir="ltr" data-size="xs"
                                                    data-show-clear="false" data-show-caption="false" readonly>
                                            </div>
                                            <div class="description 123">
                                                <p><?= $my_row['comment'] ?></p>
                                            </div>
                                            <div class="comment-image">
                                                <div class="d-flex overflow-auto">
                                                    <?php foreach ($my_row['images'] as $image) { ?>
                                                        <div>
                                                            <a href="<?= base_url(FCPATH . REVIEW_IMG_PATH . $image) ? $image : base_url() . NO_IMAGE; ?>"
                                                                data-lightbox="review-images">
                                                                <div class="img-box-100">
                                                                    <img src="<?= base_url(FCPATH . REVIEW_IMG_PATH . $image) ? $image : base_url() . NO_IMAGE; ?>"
                                                                        alt="Review Image">
                                                                </div>
                                                            </a>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                            } ?>
                        </div>
                        <?php if (isset($product_ratings) && !empty($product_ratings['product_rating']) && count($product_ratings['product_rating']) > 5) { ?>
                            <div class="col-md-12">
                                <div class="row gy-3 me-0" id="review-list">
                                    <div class="text-center mt-3 load-more-container">
                                        <button class="btn viewmorebtn" id="load-user-ratings"
                                            data-product="<?= $product['product'][0]['id'] ?>"
                                            data-limit="<?= $user_rating_limit ?>"
                                            data-offset="<?= $user_rating_offset ?>">All <span
                                                id="no_ratings"><?= $product['product'][0]['no_of_ratings'] ?></span>
                                            reviews...</button>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <!-- product faq tab -->
                <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab"
                    tabindex="0">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="accordion accordion-wrapper" id="accordionSimpleExample">
                                    <div>
                                        <?php if ($this->ion_auth->logged_in()) { ?>
                                            <div class="add-faqs-form float-right">
                                                <button class="btn btn-primary border-radius-10 btn-xs mt-2"
                                                    data-bs-toggle="modal" data-bs-target="#add-faqs-form"><i
                                                        class="uil uil-plus" aria-hidden="true"></i> &nbsp;Add your question
                                                    here</button>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="modal fade" id="add-faqs-form">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Add Faq</h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="post" action='<?= base_url('products/add_faqs') ?>'
                                                        id="add-faqs">
                                                        <div class="form-group">
                                                            <input type="hidden"
                                                                name=" <?php echo $this->security->get_csrf_token_name(); ?>"
                                                                value="<?php echo $this->security->get_csrf_hash(); ?>" />
                                                            <input type="hidden" name="user_id"
                                                                value="<?= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>">
                                                            <input type="hidden" name="product_id"
                                                                value="<?= $product['product'][0]['id'] ?>">
                                                            <input type="text" class="form-control" id="question"
                                                                placeholder="Enter Your Question Here" name="question">
                                                        </div>
                                                        <button type="submit"
                                                            class="btn btn-primary btn-sm border-radius-10 mt-2"
                                                            name="add-faqs" value="Save">Add Question</button>
                                                        <div class="mt-3">
                                                            <div id="add_faqs_result"></div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ((!isset($faq['data']) && empty($faq['data'])) || $faq['data'] == []) { ?>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="d-flex flex-column">
                                                <img class=""
                                                    src="<?= base_url('assets/front_end/modern/image/no-faqs.webp') ?>"
                                                    alt="No Faq" width="200px" />
                                                <h4 class="text-center">
                                                    <?= !empty($this->lang->line('no_faqs_found')) ? $this->lang->line('no_faqs_found') : 'No FAQs Found' ?>
                                                </h4>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <?php foreach ($faq['data'] as $row) {
                                            ?>
                                            <?php if (isset($row['answer']) && !empty($row['answer']) && ($row['answer'] != '')) {
                                                ?>
                                                <div class="plain accordion-item mt-2">
                                                    <div class="card-header" id="<?= "h-" . $row['id'] ?>">
                                                        <button class="accordion-button collapsed" data-bs-toggle="collapse"
                                                            data-bs-target="#<?= "c-" . $row['id'] ?>" aria-expanded="true"
                                                            aria-controls="collapseSimpleOne">

                                                            <?= html_escape($row['question']) ?>

                                                            <small class="text-muted ms-2">
                                                                (<?= !empty($this->lang->line('asked_by')) ? $this->lang->line('asked_by') : 'Asked by' ?>
                                                                <?= html_escape($row['username']) ?>)
                                                            </small>

                                                        </button>
                                                    </div>
                                                    <!--/.card-header -->
                                                    <?php $product_data = fetch_details('users', ['id' => $row['answered_by']], 'username'); ?>
                                                    <div id="<?= "c-" . $row['id'] ?>" class="accordion-collapse collapse"
                                                        aria-labelledby="<?= "h-" . $row['id'] ?>"
                                                        data-bs-parent="#accordionSimpleExample">
                                                        <div class="card-body">
                                                            <p class="mb-1"><?= html_escape($row['answer']) ?></p>
                                                            <p class="text-dark m-0">
                                                                <?= !empty($this->lang->line('answer_by')) ? $this->lang->line('answer_by') : 'Answer by' ?>
                                                                :
                                                                <?= isset($product_data[0]['username']) && !empty($product_data[0]['username']) ? html_escape($product_data[0]['username']) : "" ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        <?php }
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-3 pb-5">
            <div class="align-items-center d-flex gap-4 justify-content-between">
                <h1 class="section-title  pointer"><?= label('related_products', 'Related products') ?></h1>
            </div>
            <?php if (isset($related_products['product'][0]) && !empty($related_products['product'][0])) { ?>
                <div class="swiper mySwiper3 swiper-arrow">
                    <div <?= ($is_rtl == true) ? "dir='rtl'" : ""; ?> class="swiper-wrapper grab">
                        <?php
                        foreach ($related_products['product'] as $row) { ?>
                            <div class="swiper-slide background-none">
                                <a href="<?= base_url('products/details/' . $row['slug']) ?>"
                                    class="text-reset text-decoration-none">
                                    <div class="card card-h-418 product-card" data-product-id="<?= $row['id'] ?>">
                                        <div class="product-icon-spacebtw">
                                            <div class="add-to-fav-btn" title="like" data-product-id="<?= $row['id'] ?>">
                                                <ion-icon
                                                    class="ion-icon ion-icon-hover add-to-fav-btn <?= ($row['is_favorite'] == 1) ? 'heart text-danger' : 'heart-outline text-dark' ?>"
                                                    name="<?= ($row['is_favorite'] == 1) ? 'heart' : 'heart-outline' ?>"></ion-icon>
                                            </div>
                                        </div>
                                        <div class="product-img">
                                            <img data-src="<?= $row['image_sm'] ?>" class="card-img-top pic-1 lazy" alt="...">
                                        </div>
                                        <div class="card-body">
                                            <h4 class="card-title"><?= $row['name'] ?></h4>
                                            <p class="card-text">
                                                <?= output_escaping(str_replace('\r\n', '&#13;&#10;', word_limit($row['category_name']))) ?>
                                            </p>
                                            <div class="d-flex flex-column">
                                                <input id="input-3-ltr-star-md" name="input-3-ltr-star-md"
                                                    class="kv-ltr-theme-svg-star rating-loading" value="<?= $row['rating'] ?>"
                                                    dir="ltr" data-size="xs" data-show-clear="false" data-show-caption="false"
                                                    readonly>
                                                <h4 class="card-price">
                                                    <?php if ($row['is_on_sale'] == 1) { ?>
                                                        <?php
                                                        echo $settings['currency'] . '' . $row['variants'][0]['sale_final_price']; ?>
                                                    <?php } else { ?>

                                                        <?php $price = get_price_range_of_product($row['id']);
                                                        echo $price['range']; ?>

                                                    <?php } ?>
                                                </h4>
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
                                                <?php
                                                if ($row['is_on_sale'] == 1) {
                                                    $variant_price = ($row['variants'][0]['sale_final_price'] > 0 && $row['variants'][0]['sale_final_price'] != '') ? $row['variants'][0]['sale_final_price'] : $row['variants'][0]['price'];
                                                } else {
                                                    $variant_price = ($row['variants'][0]['special_price'] > 0 && $row['variants'][0]['special_price'] != '') ? $row['variants'][0]['special_price'] : $row['variants'][0]['price'];
                                                }
                                                $data_min = (isset($row['minimum_order_quantity']) && !empty($row['minimum_order_quantity'])) ? $row['minimum_order_quantity'] : 1;
                                                $data_step = (isset($row['minimum_order_quantity']) && !empty($row['quantity_step_size'])) ? $row['quantity_step_size'] : 1;
                                                $data_max = (isset($row['total_allowed_quantity']) && !empty($row['total_allowed_quantity'])) ? $row['total_allowed_quantity'] : 0;
                                                ?>
                                            </div>
                                            <a href="#"
                                                class="btn add-in-cart-btn w-100 add_to_cart px-1 btn-sm <?= $class_modal ?>"
                                                data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $variant_id ?>"
                                                data-product-title="<?= $row['name'] ?>"
                                                data-product-image="<?= $row['image'] ?>"
                                                data-product-price="<?= $variant_price; ?>" data-min="<?= $data_min; ?>"
                                                data-step="<?= $data_step; ?>"
                                                data-product-description="<?= $row['short_description']; ?>"
                                                data-bs-toggle="modal" data-bs-target="<?= $modal ?>">
                                                <span class="add-in-cart"><?= label('add_to_cart', 'Add to Cart') ?></span><span
                                                    class="add-in-cart-icon"><i class="fa-solid fa-cart-shopping
                                        color-white"></i></span></a>
                                        </div>
                                        <div class="product-icon-onhover">
                                            <div class="product-icon-spacebtw">
                                                <div class="shuffle-box">
                                                    <a class="compare text-reset text-decoration-none shuffle"
                                                        data-tip="Compare" data-product-id="<?= $row['id'] ?>"
                                                        data-product-variant-id="<?= $variant_id ?>">
                                                        <ion-icon name="shuffle-outline"
                                                            class="ion-icon-hover pointer shuffle"></ion-icon>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="product-icon-spacebtw">
                                                <div class="quick-search-box quickview-trigger" data-tip="Quick View"
                                                    data-product-id="<?= $row['id'] ?>"
                                                    data-product-variant-id="<?= $row['variants'][0]['id'] ?>">
                                                    <ion-icon name="search-outline" class="ion-icon-hover pointer"
                                                        data-bs-toggle="modal" data-bs-target="#quickview"></ion-icon>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>

                    </div>
                    <div class="swiper-button-next"><ion-icon name="chevron-forward-outline"></ion-icon></div>
                    <div class="swiper-button-prev"><ion-icon name="chevron-back-outline"></ion-icon></div>
                </div>
            <?php } else { ?>
                <div class="row gy-3 mb-3 me-0">
                    <p class="mt-4 text-muted">No related products found.</p>
                </div>
            <?php } ?>
        </div>
    </section>
</main>