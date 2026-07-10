<main>
    <?php
    $flash_sale_count = count($flash_sale);
    $showFlashSale = 0;
    ?>
    <section class="container-xl mt-4 content-wrapper">
        <div class="mb-4 mb-md-5">
            <div class="swiper mySwiper grab rounded overflow-hidden">
                <div class="swiper-wrapper">
                    <?php if (!empty($sliders)) { ?>
                        <?php foreach ($sliders as $row) { ?>
                            <div class="swiper-slide">
                                <a target="_blank" href="<?= $row['link'] ?>" class="d-block w-100">
                                    <img src="<?= base_url($row['image']) ?>" class="img-fluid w-100"
                                        style="object-fit: contain;" alt="Slider Image">
                                </a>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>
    <?php if (isset($categories) && !empty($categories) && $categories != []) { ?>
        <section class="container">
            <!-- section 2 -->
            <div class="mb-4 mb-md-5">
                <div class="align-items-center d-flex flex-wrap justify-content-between pb-3">
                    <h1 class="section-title  pointer"><?= label('popular_categories', 'Popular Categories') ?></h1>

                    <a href="<?= base_url('home/categories/') ?>">
                        <button class="btn viewmorebtn"><?= label('view_more', 'View More') ?></button>
                    </a>
                </div>
                <div class="swiperForCategories swiper mySwiper2 ">
                    <div class="swiper-wrapper grab text-center">
                        <?php
                        foreach ($categories as $key => $row) {
                            ?>
                            <div class="swiper-slide overflow-hidden">
                                <div class="col p-0 fit-content">
                                    <div class="categories-card">
                                        <a href="<?= base_url('products/category/' . html_escape($row['slug'])) ?>"
                                            class="text-reset text-decoration-none">
                                            <div class="categories-image">
                                                <img src="<?= $row['image'] ?>" alt="">
                                            </div>
                                            <div class="categories-card-text">
                                                <h4><?= html_escape($row['name']) ?></h4>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="swiper-button-next">
                        <ion-icon name="chevron-forward-outline"></ion-icon>
                    </div>
                    <div class="swiper-button-prev">
                        <ion-icon name="chevron-back-outline"></ion-icon>
                    </div>
                </div>
            </div>
        </section>
    <?php } ?>


    <?php if (isset($brands) && !empty($brands) && $brands != []) { ?>
        <section class="container mt-3">
            <div class="mb-4 mb-md-5">
                <div class="align-items-center d-flex flex-wrap justify-content-between pb-3">
                    <h1 class="section-title  pointer"><?= label('brands', 'Brands') ?></h1>

                    <a href="<?= base_url('home/brands/') ?>">
                        <button class="btn viewmorebtn"><?= label('see_all', 'See All') ?></button>
                    </a>
                </div>
                <div class="swiperForBrands swiper mySwiper2 ">
                    <div class="swiper-wrapper grab text-center">
                        <?php
                        foreach ($brands as $key => $row) { ?>

                            <div class="swiper-slide overflow-hidden">
                                <div class="col p-0 fit-content">
                                    <div class="categories-card">
                                        <a href="<?= base_url('products?brand=' . html_escape($row['brand_slug'])) ?>"
                                            class="text-reset text-decoration-none">
                                            <div class="brands-image">
                                                <img src="<?= !empty($row['brand_img']) ? $row['brand_img'] : base_url(NO_IMAGE); ?>"
                                                    alt="<?= html_escape($row['brand_name']) ?>">
                                            </div>

                                            <div class="categories-card-text">
                                                <h4><?= html_escape($row['brand_name']) ?></h4>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="swiper-button-next">
                        <ion-icon name="chevron-forward-outline"></ion-icon>
                    </div>
                    <div class="swiper-button-prev">
                        <ion-icon name="chevron-back-outline"></ion-icon>
                    </div>
                </div>
            </div>
        </section>
    <?php } ?>

    <?php $offer_counter = 0;
    $offers = get_offers();
    foreach ($sections as $count_key => $row) {

        if (isset($row['product_details']) && !empty($row['product_details'])) {
            if ($row['style'] == 'default') {
                ?>
                <section class="container">
                    <!-- default style -->
                    <!-- section 3 -->
                    <div class="product-style-default mb-4 mb-md-5">
                        <div class="align-items-center d-flex flex-wrap justify-content-between pb-3">
                            <div>
                                <h1 class="section-title  pointer"><?= ucfirst($row['title']) ?></h1>
                                <p class="banner-paragraph  default-cursor"><?= strip_tags($row['short_description']) ?>
                            </div>

                            <a href="<?= base_url('products/section/' . $row['id'] . '/' . $row['slug']) ?>">
                                <button class="btn viewmorebtn"><?= label('view_more', 'View More') ?></button>
                            </a>
                        </div>
                        <div class="swiper mySwiper3 swiper-arrow swiper-wid ">
                            <div class="swiper-wrapper grab" <?= ($is_rtl == true) ? "dir='rtl'" : ""; ?>>
                                <?php foreach ($row['product_details'] as $product_row) {

                                    ?>
                                    <?php
                                    if (isset($_GET['product_id'])) {

                                        $product_row['id'] = intval($_GET['product_id']);

                                        $product_details = ($product_row['id']);

                                        echo json_encode($product_details);
                                    }
                                    ?>
                                    <div class="swiper-slide background-none">
                                        <a href="<?= base_url('products/details/' . $product_row['slug']) ?>"
                                            class="text-reset text-decoration-none">
                                            <div class="card card-h-418 product-card h-100" data-product-id="<?= $product_row['id'] ?>">
                                                <div class="product-icon-spacebtw">
                                                    <div class="add-to-fav-btn" title="like"
                                                        data-product-id="<?= isset($product_row['id']) ? $product_row['id'] : '' ?>">
                                                        <ion-icon
                                                            class="ion-icon ion-icon-hover <?= (isset($product_row['is_favorite']) && $product_row['is_favorite'] == 1) ? 'heart text-danger' : 'heart-outline text-dark' ?>"
                                                            name="<?= (isset($product_row['is_favorite']) && $product_row['is_favorite'] == 1) ? 'heart' : 'heart-outline' ?>">
                                                        </ion-icon>
                                                    </div>

                                                </div>
                                                <div class="product-img-style3">
                                                    <img alt=" " class=""
                                                        src="<?= isset($product_row['image_sm']) && !empty($product_row['image_sm']) && $product_row['image_sm'] !== null ? $product_row['image_sm'] : base_url('assets/front_end/modern/image/pictures/placeholder-image.png') ?>" />
                                                </div>

                                                <div class="card-body">
                                                    <h4 class="card-title">
                                                        <?= word_limit(output_escaping(str_replace('\r\n', '&#13;&#10;', $product_row['name']))) ?>
                                                    </h4>
                                                    <p class="card-text">
                                                        <?= output_escaping(str_replace('\r\n', '&#13;&#10;', word_limit($product_row['category_name']))) ?>
                                                    </p>
                                                    <div class="d-flex flex-column">
                                                        <input id="input-3-ltr-star-md" name="input-3-ltr-star-md"
                                                            class="kv-ltr-theme-svg-star rating-loading"
                                                            value="<?= $product_row['rating'] ?>" dir="ltr" data-size="xs"
                                                            data-show-clear="false" data-show-caption="false" readonly>

                                                        <div class="d-flex gap-1">
                                                            <h4 class="card-price" itemprop="offers">
                                                                <?php
                                                                if (($product_row['variants'][0]['special_price'] < $product_row['variants'][0]['price']) && ($product_row['variants'][0]['special_price'] != 0)) { ?>
                                                                    <p class="mb-0 price text-muted">
                                                                        <span id="price">
                                                                            <?php echo $settings['currency'] ?>
                                                                            <?php
                                                                            $price = $product_row['variants'][0]['special_price'];
                                                                            echo $price;
                                                                            ?>
                                                                        </span>
                                                                    </p>
                                                                <?php } else { ?>
                                                                    <p class="mb-0 price text-muted">
                                                                        <span id="price">
                                                                            <?php echo $settings['currency'] ?>
                                                                            <?php
                                                                            $price = $product_row['variants'][0]['price'];
                                                                            echo $price;
                                                                            ?>
                                                                        </span>
                                                                    </p>
                                                                <?php } ?>
                                                            </h4>
                                                            <p class="m-0 offer_price">
                                                                <small>
                                                                    <?php if ($product_row['is_on_sale'] == 1 && !empty($product_row['variants'][0]['sale_final_price'])) { ?>
                                                                        <span class="text-decoration-line-through fw-bold" itemprop="price">
                                                                            <?= $settings['currency'] . number_format($product_row['variants'][0]['price']) ?>
                                                                        </span>
                                                                        <span class="text-success fw-bold">
                                                                            <span
                                                                                class="product-discount-label fw-bold"><?= $product_row['sale_discount'] ?>%
                                                                                off</span>
                                                                        </span>
                                                                    <?php } elseif (!empty($product_row['variants'][0]['price']) && $product_row['variants'][0]['special_price'] < $product_row['variants'][0]['price'] && ($product_row['variants'][0]['special_price'] != 0)) {
                                                                        // Calculate discount percentage for the specific variant being displayed
                                                                        $original_price = $product_row['variants'][0]['price'];
                                                                        $special_price = $product_row['variants'][0]['special_price'];
                                                                        $discount_percentage = round((($original_price - $special_price) / $original_price) * 100);
                                                                        ?>
                                                                        <span class="text-decoration-line-through fw-bold" itemprop="price">

                                                                            <?= $settings['currency'] . number_format($product_row['variants'][0]['price']) ?>
                                                                        </span>
                                                                        <span class="text-success fw-bold">
                                                                            <span
                                                                                class="product-discount-label fw-bold"><?= $discount_percentage ?>%
                                                                                off</span>
                                                                        </span>
                                                                    <?php } ?>
                                                                </small>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <?php
                                                    if (isset($product_row['variants']) && is_array($product_row['variants']) && count($product_row['variants']) > 0) {
                                                        if (count($product_row['variants']) <= 1) {
                                                            $variant_id = isset($product_row['variants'][0]['id']) ? $product_row['variants'][0]['id'] : '';
                                                            $modal = "";
                                                            $class_modal = "";
                                                        } else {
                                                            $variant_id = "";
                                                            $class_modal = "quickview-trigger";
                                                            $modal = "#quickview";
                                                        }
                                                    } else {
                                                        // Handle case where 'variants' is not set or is an empty array
                                                        $variant_id = "";
                                                        $class_modal = "";
                                                        $modal = "";
                                                    }
                                                    ?>

                                                    <?php
                                                    // Ensure 'variants' is set and is an array with at least one element
                                                    if (isset($product_row['variants']) && is_array($product_row['variants']) && count($product_row['variants']) > 0) {
                                                        $variant = $product_row['variants'][0];

                                                        // Determine the variant price
                                                        $variant_price = (isset($variant['special_price']) && $variant['special_price'] > 0 && $variant['special_price'] !== '') ? $variant['special_price'] : (isset($variant['price']) ? $variant['price'] : 0);
                                                    } else {
                                                        $variant_price = 0; // Default price if 'variants' is not set or empty
                                                    }

                                                    // Determine minimum order quantity
                                                    $data_min = (isset($product_row['minimum_order_quantity']) && !empty($product_row['minimum_order_quantity']) && $product_row['minimum_order_quantity'] !== null) ? $product_row['minimum_order_quantity'] : 1;

                                                    // Determine quantity step size
                                                    $data_step = (isset($product_row['quantity_step_size']) && !empty($product_row['quantity_step_size']) && $product_row['quantity_step_size'] !== null) ? $product_row['quantity_step_size'] : 1;

                                                    // Determine total allowed quantity
                                                    $data_max = (isset($product_row['total_allowed_quantity']) && !empty($product_row['total_allowed_quantity']) && $product_row['total_allowed_quantity'] !== null) ? $product_row['total_allowed_quantity'] : 0;
                                                    ?>


                                                    <a href="#" data-tip="Add to Cart"
                                                        class="btn add-in-cart-btn add_to_cart w-100 btn-sm px-1 <?= $class_modal ?>"
                                                        data-product-id="<?= $product_row['id'] ?>"
                                                        data-product-variant-id="<?= $variant_id ?>"
                                                        data-product-title="<?= $product_row['name'] ?>"
                                                        data-product-image="<?= $product_row['image']; ?>"
                                                        data-product-price="<?= $variant_price; ?>" data-min="<?= $data_min; ?>"
                                                        data-max="<?= $data_max; ?>" data-step="<?= $data_step; ?>"
                                                        data-product-description="<?= $product_row['short_description']; ?>"
                                                        data-bs-toggle="modal" data-bs-target="<?= $modal ?>"><span
                                                            class="add-in-cart"><?= label('add_to_cart', 'Add to Cart') ?></span><span
                                                            class="add-in-cart-icon"><i class="fa-solid fa-cart-shopping
                                                color-white"></i></span></a>

                                                </div>
                                                <div class="product-icon-onhover">
                                                    <div class="product-icon-spacebtw">
                                                        <div class="shuffle-box">
                                                            <a class="compare text-reset text-decoration-none shuffle"
                                                                data-tip="Compare" data-product-id="<?= $product_row['id'] ?>"
                                                                data-product-variant-id="<?= $variant_id ?>">
                                                                <ion-icon name="shuffle-outline" class="ion-icon-hover pointer shuffle">
                                                                </ion-icon>
                                                            </a>
                                                        </div>
                                                    </div>

                                                    <div class="product-icon-spacebtw">
                                                        <div class="quick-search-box quickview-trigger" data-tip="Quick View"
                                                            data-product-id="<?= isset($product_row['id']) ? $product_row['id'] : '' ?>"
                                                            data-product-variant-id="<?= isset($product_row['variants'][0]['id']) ? $product_row['variants'][0]['id'] : '' ?>">
                                                            <ion-icon name="search-outline" class="ion-icon-hover pointer"
                                                                data-bs-toggle="modal" data-bs-target="#quickview">
                                                            </ion-icon>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="swiper-button-next">
                                <ion-icon name="chevron-forward-outline"></ion-icon>
                            </div>
                            <div class="swiper-button-prev">
                                <ion-icon name="chevron-back-outline"></ion-icon>
                            </div>
                        </div>
                    </div>
                </section>
            <?php } else if ($row['style'] == 'style_1') {

                ?>
                    <section class="container">

                        <div class="mb-4 mb-md-5 row">

                            <div class="col-xl-8 col-lg-12">

                                <!-- Header -->
                                <div class="align-items-center d-flex justify-content-between pb-3">
                                    <div>
                                        <h1 class="section-title pointer"><?= ucfirst($row['title']) ?></h1>
                                        <p class="banner-paragraph default-cursor">
                                        <?= strip_tags($row['short_description']) ?>
                                        </p>
                                    </div>

                                    <a href="<?= base_url('products/section/' . $row['id'] . '/' . $row['slug']) ?>">
                                        <button class="btn viewmorebtn"><?= label('view_more', 'View More') ?></button>
                                    </a>
                                </div>

                                <div class="row g-2">

                                    <?php
                                    $products = $row['product_details'];
                                    $total_products = count($products);
                                    $last_product = $products[$total_products - 1]; // Banner image
                        
                                    $i = 0;

                                    if (!empty($products)) {
                                        foreach ($products as $product_row) {

                                            if ($i >= 3)
                                                break;  // Show only first 3 items
                                            ?>
                                            <div class="col-md-4 col-6">
                                                <a href="<?= base_url('products/details/' . $product_row['slug']) ?>"
                                                    class="text-reset text-decoration-none">

                                                    <div class="card-temp card p-0 mb-3 pointer product-card"
                                                        data-product-id="<?= $product_row['id'] ?>">

                                                        <!-- Favorite -->
                                                        <div class="product-icon-spacebtw">
                                                            <div class="add-to-fav-btn" title="like"
                                                                data-product-id="<?= $product_row['id'] ?>">
                                                                <ion-icon
                                                                    class="ion-icon ion-icon-hover <?= ($product_row['is_favorite'] == 1) ? 'heart text-danger' : 'heart-outline text-dark' ?>"
                                                                    name="<?= ($product_row['is_favorite'] == 1) ? 'heart' : 'heart-outline' ?>">
                                                                </ion-icon>
                                                            </div>
                                                        </div>

                                                        <!-- Product Image -->
                                                        <div class="product-image-1">
                                                            <img class="pic-1 lazy card-img-top"
                                                                src="<?= base_url('assets/front_end/modern/image/pictures/placeholder-image.png') ?>"
                                                                data-src="<?= $product_row['image_sm'] ?: base_url('assets/front_end/modern/image/pictures/placeholder-image.png') ?>">
                                                        </div>

                                                        <!-- Card Body -->
                                                        <div class="card-body">

                                                            <h4 class="card-title">
                                                            <?= word_limit(output_escaping($product_row['name'])) ?>
                                                            </h4>

                                                            <p class="card-text">
                                                            <?= word_limit(output_escaping($product_row['category_name'])) ?>
                                                            </p>

                                                            <!-- Price Section -->
                                                            <div class="d-flex gap-1">

                                                                <h4 class="card-price" itemprop="offers">
                                                                    <p class="mb-0 price text-muted">
                                                                        <span id="price">
                                                                        <?= $settings['currency'] ?>
                                                                            <?php
                                                                            $variant = $product_row['variants'][0];
                                                                            $price = ($variant['special_price'] > 0 && $variant['special_price'] < $variant['price'])
                                                                                ? $variant['special_price']
                                                                                : $variant['price'];
                                                                            echo $price;
                                                                            ?>
                                                                        </span>
                                                                    </p>
                                                                </h4>

                                                                <!-- Discounts -->
                                                                <p class="m-0 offer_price">
                                                                    <small>
                                                                        <?php
                                                                        if ($product_row['is_on_sale'] == 1 && !empty($variant['sale_final_price'])) {
                                                                            ?>
                                                                            <span class="text-decoration-line-through fw-bold">
                                                                            <?= $settings['currency'] . number_format($variant['price']) ?>
                                                                            </span>
                                                                            <span class="text-success fw-bold">
                                                                            <?= $product_row['sale_discount'] ?>% off
                                                                            </span>

                                                                        <?php
                                                                        } elseif ($variant['special_price'] > 0 && $variant['special_price'] < $variant['price']) {

                                                                            $discount_percentage = round((($variant['price'] - $variant['special_price']) / $variant['price']) * 100);
                                                                            ?>
                                                                            <span class="text-decoration-line-through fw-bold">
                                                                            <?= $settings['currency'] . number_format($variant['price']) ?>
                                                                            </span>
                                                                            <span class="text-success fw-bold">
                                                                            <?= $discount_percentage ?>% off
                                                                            </span>
                                                                    <?php } ?>
                                                                    </small>
                                                                </p>
                                                            </div>

                                                            <!-- Variant handling -->
                                                            <?php
                                                            if (count($product_row['variants']) <= 1) {
                                                                $variant_id = $variant['id'];
                                                                $modal = "";
                                                                $class_modal = "";
                                                            } else {
                                                                $variant_id = "";
                                                                $class_modal = "quickview-trigger";
                                                                $modal = "#quickview";
                                                            }

                                                            $variant_price = ($product_row['is_on_sale'] == 1 && $variant['sale_final_price'] > 0)
                                                                ? $variant['sale_final_price']
                                                                : (($variant['special_price'] > 0) ? $variant['special_price'] : $variant['price']);

                                                            $data_min = $product_row['minimum_order_quantity'] ?: 1;
                                                            $data_step = $product_row['quantity_step_size'] ?: 1;
                                                            $data_max = $product_row['total_allowed_quantity'] ?: 0;
                                                            ?>

                                                            <!-- Add to Cart -->
                                                            <a href="#"
                                                                class="btn add-in-cart-btn add_to_cart w-100 p-1 btn-sm <?= $class_modal ?>"
                                                                data-product-id="<?= $product_row['id'] ?>"
                                                                data-product-variant-id="<?= $variant_id ?>"
                                                                data-product-title="<?= $product_row['name'] ?>"
                                                                data-product-image="<?= $product_row['image'] ?>"
                                                                data-product-price="<?= $variant_price ?>" data-min="<?= $data_min ?>"
                                                                data-max="<?= $data_max ?>" data-step="<?= $data_step ?>"
                                                                data-product-description="<?= $product_row['short_description'] ?>"
                                                                data-bs-toggle="modal" data-bs-target="<?= $modal ?>">

                                                                <span class="add-in-cart"><?= label('add_to_cart', 'Add to Cart') ?></span>
                                                                <span class="add-in-cart-icon">
                                                                    <i class="fa-solid fa-cart-shopping color-white"></i>
                                                                </span>
                                                            </a>

                                                        </div>

                                                        <!-- Hover Icons -->
                                                        <div class="product-icon-onhover">
                                                            <div class="product-icon-spacebtw">
                                                                <div class="shuffle-box">
                                                                    <a class="compare" data-product-id="<?= $product_row['id'] ?>"
                                                                        data-product-variant-id="<?= $variant_id ?>">
                                                                        <ion-icon name="shuffle-outline"
                                                                            class="ion-icon-hover pointer"></ion-icon>
                                                                    </a>
                                                                </div>
                                                            </div>

                                                            <div class="product-icon-spacebtw">
                                                                <div class="quick-search-box quickview-trigger"
                                                                    data-product-id="<?= $product_row['id'] ?>"
                                                                    data-product-variant-id="<?= $variant_id ?>">
                                                                    <ion-icon name="search-outline" class="ion-icon-hover pointer"
                                                                        data-bs-toggle="modal" data-bs-target="#quickview"></ion-icon>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </a>
                                            </div>

                                            <?php
                                            $i++;
                                        }
                                    }
                                    ?>

                                </div>

                            </div>

                            <!-- Right Banner -->
                            <div class="col-4">
                                <div class="border-radius-10 overflow-hidden product-banner-container">
                                    <a href="<?= base_url('products/section/' . $row['id'] . '/' . $row['slug']) ?>">
                                        <img class="pic-1 product-banner lazy"
                                            src="<?= base_url('assets/front_end/modern/image/pictures/placeholder-image.png') ?>"
                                            data-src="<?= $last_product['image_sm'] ?>">
                                    </a>
                                </div>
                            </div>

                        </div>

                    </section>

            <?php } else if ($row['style'] == 'style_2') {

                ?>
                        <section class="container">
                            <!-- Style 2 Design-->
                            <!-- section 5 -->
                            <div class="mb-4 mb-md-5">
                        <?php $first_product = isset($row['product_details'][0]) ? $row['product_details'][0] : ""; ?>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="border-radius-10 overflow-hidden product-banner-container">

                                            <a
                                                href="<?= isset($row['id']) && isset($row['slug']) ? base_url('products/section/' . $row['id'] . '/' . $row['slug']) : '#' ?>">
                                                <img class="pic-1 product-banner lazy"
                                                    src="<?= base_url('assets/front_end/modern/image/pictures/placeholder-image.png') ?>"
                                                    data-src="<?= isset($first_product['image_sm']) && !empty($first_product['image_sm']) && $first_product['image_sm'] !== null ? $first_product['image_sm'] : base_url('assets/front_end/modern/image/pictures/placeholder-image.png') ?>">
                                            </a>

                                        </div>
                                    </div>
                                    <div class="col-xl-8 col-lg-12">
                                        <div class="align-items-center d-flex flex-wrap justify-content-between pb-3">
                                            <div>
                                                <h1 class="section-title mb-0"><?= ucfirst($row['title']) ?></h1>
                                                <p class="banner-paragraph  default-cursor"><?= strip_tags($row['short_description']) ?>
                                            </div>

                                            <a href="<?= base_url('products/section/' . $row['id'] . '/' . $row['slug']) ?>">
                                                <button class="btn viewmorebtn"><?= label('view_more', 'View More') ?></button>
                                            </a>
                                        </div>
                                        <div class="row g-2">
                                    <?php $product_count = count($row['product_details']) - 1; ?>
                                        <?php
                                        $i = 0;
                                        if (count($row['product_details']) > 0) {
                                            foreach ($row['product_details'] as $key => $product_row) {
                                                if ($i == 3) {
                                                    break;
                                                }
                                                ?>
                                            <?php if ($key != 0) { ?>
                                                        <div class="col-md-4 col-6">
                                                            <a href="<?= base_url('products/details/' . $product_row['slug']) ?>"
                                                                class="text-reset text-decoration-none">
                                                                <div class="card-temp card p-0 mb-3 mb-md-0 pointer product-card"
                                                                    data-product-id="<?= $product_row['id'] ?>">
                                                                    <div class="product-icon-spacebtw">

                                                                        <div class="add-to-fav-btn" title="like"
                                                                            data-product-id="<?= isset($product_row['id']) ? $product_row['id'] : '' ?>">
                                                                            <ion-icon
                                                                                class="ion-icon ion-icon-hover <?= (isset($product_row['is_favorite']) && !empty($product_row['is_favorite']) && $product_row['is_favorite'] !== null && $product_row['is_favorite'] == 1) ? 'heart text-danger' : 'heart-outline text-dark' ?>"
                                                                                name="<?= (isset($product_row['is_favorite']) && !empty($product_row['is_favorite']) && $product_row['is_favorite'] !== null && $product_row['is_favorite'] == 1) ? 'heart' : 'heart-outline' ?>">
                                                                            </ion-icon>
                                                                        </div>

                                                                    </div>

                                                                    <div class="product-image-1">
                                                                        <img class="pic-1 lazy card-img-top"
                                                                            src="<?= base_url('assets/front_end/modern/image/pictures/placeholder-image.png') ?>"
                                                                            data-src="<?= isset($product_row['image_sm']) && !empty($product_row['image_sm']) && $product_row['image_sm'] !== null ? $product_row['image_sm'] : base_url('assets/front_end/modern/image/pictures/placeholder-image.png') ?>">
                                                                    </div>

                                                                    <div class="card-body">
                                                                        <h4 class="card-title">
                                                                    <?= word_limit(output_escaping(str_replace('\r\n', '&#13;&#10;', $product_row['name']))) ?>
                                                                        </h4>
                                                                        <p class="card-text">
                                                                    <?= output_escaping(str_replace('\r\n', '&#13;&#10;', word_limit($product_row['category_name']))) ?>
                                                                        </p>
                                                                        <div class="d-flex flex-column">
                                                                            <input id="input-3-ltr-star-md" name="input-3-ltr-star-md"
                                                                                class="kv-ltr-theme-svg-star rating-loading"
                                                                                value="<?= $product_row['rating'] ?>" dir="ltr" data-size="xs"
                                                                                data-show-clear="false" data-show-caption="false" readonly>

                                                                        </div>
                                                                        <div class="d-flex gap-1">
                                                                            <h4 class="card-price" itemprop="offers">
                                                                            <?php
                                                                            if (($product_row['variants'][0]['special_price'] < $product_row['variants'][0]['price']) && ($product_row['variants'][0]['special_price'] != 0)) { ?>
                                                                                    <p class="mb-0 price text-muted">
                                                                                        <span id="price">
                                                                                    <?php echo $settings['currency'] ?>
                                                                                        <?php
                                                                                        $price = $product_row['variants'][0]['special_price'];
                                                                                        echo $price;
                                                                                        ?>
                                                                                        </span>
                                                                                    </p>
                                                                        <?php } else { ?>
                                                                                    <p class="mb-0 price text-muted">
                                                                                        <span id="price">
                                                                                    <?php echo $settings['currency'] ?>
                                                                                        <?php
                                                                                        $price = $product_row['variants'][0]['price'];
                                                                                        echo $price;
                                                                                        ?>
                                                                                        </span>
                                                                                    </p>
                                                                        <?php } ?>
                                                                            </h4>
                                                                            <p class="m-0 offer_price">
                                                                                <small>
                                                                            <?php if ($product_row['is_on_sale'] == 1 && !empty($product_row['variants'][0]['sale_final_price'])) { ?>
                                                                                        <span class="text-decoration-line-through fw-bold" itemprop="price">
                                                                                    <?= $settings['currency'] . number_format($product_row['variants'][0]['price']) ?>
                                                                                        </span>
                                                                                        <span class="text-success fw-bold">
                                                                                            <span
                                                                                                class="product-discount-label fw-bold"><?= $product_row['sale_discount'] ?>%
                                                                                                off</span>
                                                                                        </span>
                                                                            <?php } elseif (!empty($product_row['variants'][0]['price']) && $product_row['variants'][0]['special_price'] < $product_row['variants'][0]['price'] && ($product_row['variants'][0]['special_price'] != 0)) {
                                                                                // Calculate discount percentage for the specific variant being displayed
                                                                                $original_price = $product_row['variants'][0]['price'];
                                                                                $special_price = $product_row['variants'][0]['special_price'];
                                                                                $discount_percentage = round((($original_price - $special_price) / $original_price) * 100);
                                                                                ?>
                                                                                        <span class="text-decoration-line-through fw-bold" itemprop="price">

                                                                                    <?= $settings['currency'] . number_format($product_row['variants'][0]['price']) ?>
                                                                                        </span>
                                                                                        <span class="text-success fw-bold">
                                                                                            <span
                                                                                                class="product-discount-label fw-bold"><?= $discount_percentage ?>%
                                                                                                off</span>
                                                                                        </span>
                                                                            <?php } ?>
                                                                                </small>
                                                                            </p>
                                                                        </div>
                                                                    <?php
                                                                    if (
                                                                        isset($product_row['variants']) && !empty($product_row['variants']) && count($product_row['variants']) <= 1 &&
                                                                        isset($product_row['variants'][0]['id']) && $product_row['variants'][0]['id'] !== null
                                                                    ) {
                                                                        $variant_id = $product_row['variants'][0]['id'];
                                                                        $modal = "";
                                                                        $class_modal = "";
                                                                    } else {
                                                                        $variant_id = "";
                                                                        $class_modal = "quickview-trigger";
                                                                        $modal = "#quickview";
                                                                    }

                                                                    if (
                                                                        isset($product_row['is_on_sale']) && $product_row['is_on_sale'] == 1 &&
                                                                        isset($product_row['variants'][0]['sale_final_price']) && $product_row['variants'][0]['sale_final_price'] > 0 &&
                                                                        $product_row['variants'][0]['sale_final_price'] !== ''
                                                                    ) {
                                                                        $variant_price = $product_row['variants'][0]['sale_final_price'];
                                                                    } else {
                                                                        $variant_price = (isset($product_row['variants'][0]['special_price']) && $product_row['variants'][0]['special_price'] > 0 &&
                                                                            $product_row['variants'][0]['special_price'] !== '') ?
                                                                            $product_row['variants'][0]['special_price'] : (isset($product_row['variants'][0]['price']) ?
                                                                                $product_row['variants'][0]['price'] : 0);
                                                                    }

                                                                    $data_min = (isset($product_row['minimum_order_quantity']) && !empty($product_row['minimum_order_quantity']) &&
                                                                        $product_row['minimum_order_quantity'] !== null) ?
                                                                        $product_row['minimum_order_quantity'] : 1;

                                                                    $data_step = (isset($product_row['quantity_step_size']) && !empty($product_row['quantity_step_size']) &&
                                                                        $product_row['quantity_step_size'] !== null) ?
                                                                        $product_row['quantity_step_size'] : 1;

                                                                    $data_max = (isset($product_row['total_allowed_quantity']) && !empty($product_row['total_allowed_quantity']) &&
                                                                        $product_row['total_allowed_quantity'] !== null) ?
                                                                        $product_row['total_allowed_quantity'] : 0;
                                                                    ?>
                                                                        <a href="#"
                                                                            class="btn add-in-cart-btn add_to_cart w-100 px-1 btn-sm <?= $class_modal ?>"
                                                                            data-product-id="<?= isset($product_row['id']) ? $product_row['id'] : '' ?>"
                                                                            data-product-variant-id="<?= $variant_id ?>"
                                                                            data-product-title="<?= isset($product_row['name']) ? $product_row['name'] : '' ?>"
                                                                            data-product-image="<?= isset($product_row['image']) ? $product_row['image'] : '' ?>"
                                                                            data-product-price="<?= $variant_price ?>" data-min="<?= $data_min ?>"
                                                                            data-max="<?= $data_max; ?>" data-step="<?= $data_step ?>"
                                                                            data-product-description="<?= isset($product_row['short_description']) ? $product_row['short_description'] : '' ?>"
                                                                            data-bs-toggle="modal" data-bs-target="<?= $modal ?>">
                                                                            <span class="add-in-cart"><?= label('add_to_cart', 'Add to Cart') ?></span>
                                                                            <span class="add-in-cart-icon"><i
                                                                                    class="fa-solid fa-cart-shopping color-white"></i></span>
                                                                        </a>

                                                                    </div>
                                                                    <div class="product-icon-onhover">
                                                                        <div class="product-icon-spacebtw">
                                                                            <div class="shuffle-box">
                                                                                <a class="compare text-reset text-decoration-none shuffle"
                                                                                    data-tip="Compare" data-product-id="<?= $product_row['id'] ?>"
                                                                                    data-product-variant-id="<?= $variant_id ?>">
                                                                                    <ion-icon name="shuffle-outline"
                                                                                        class="ion-icon-hover pointer shuffle"></ion-icon>
                                                                                </a>
                                                                            </div>
                                                                        </div>

                                                                        <div class="product-icon-spacebtw">
                                                                            <div class="quick-search-box quickview-trigger" data-tip="Quick View"
                                                                                data-product-id="<?= isset($product_row['id']) ? $product_row['id'] : '' ?>"
                                                                                data-product-variant-id="<?= (isset($product_row['variants'][0]['id']) && !empty($product_row['variants'][0]['id']) && $product_row['variants'][0]['id'] !== null) ? $product_row['variants'][0]['id'] : '' ?>">
                                                                                <ion-icon name="search-outline" class="ion-icon-hover pointer"
                                                                                    data-bs-toggle="modal" data-bs-target="#quickview"></ion-icon>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                <?php $i++;
                                            }
                                            } ?>
                                    <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
            <?php } else if ($row['style'] == 'style_3') {

                ?>
                            <!-- Style 3 Design-->
                            <!-- section 6 -->
                            <section class="container mt-3">
                                <div class="mb-4 mb-md-5">
                                    <div class="align-items-center d-flex justify-content-between pb-3">
                                        <div>
                                            <h1 class="section-title  pointer"><?= ucfirst($row['title']) ?></h1>
                                            <p class="banner-paragraph  default-cursor"><?= strip_tags($row['short_description']) ?>
                                        </div>

                                        <a href="<?= base_url('products/section/' . $row['id'] . '/' . $row['slug']) ?>">
                                            <button class="btn viewmorebtn"><?= label('view_more', 'View More') ?></button>
                                        </a>
                                    </div>
                                    <div class="swiper swiper-arrow mySwiper4">
                                        <div class="swiper-wrapper my-5  grab">
                                <?php foreach ($row['product_details'] as $product_row) {

                                    ?>
                                                <div class="swiper-slide box-shadow">
                                                    <a href="<?= base_url('products/details/' . $product_row['slug']) ?>"
                                                        class="text-reset text-decoration-none">
                                                        <div class="card5">
                                                            <img class="pic-1 lazy card-img-top pointer"
                                                                src="<?= base_url('assets/front_end/modern/image/pictures/placeholder-image.png') ?>"
                                                                data-src="<?= $product_row['image_sm'] ?>">
                                                            <div class="card-body">
                                                                <h5 class="card-title">
                                                        <?= word_limit(output_escaping(str_replace('\r\n', '&#13;&#10;', $product_row['name']))) ?>
                                                                </h5>
                                                                <input id="input-3-ltr-star-md" name="input-3-ltr-star-md"
                                                                    class="kv-ltr-theme-svg-star rating-loading"
                                                                    value="<?= $product_row['rating'] ?>" dir="ltr" data-size="xs"
                                                                    data-show-clear="false" data-show-caption="false" readonly>
                                                                <div class="d-flex gap-1">
                                                                    <h4 class="card-price" itemprop="offers">
                                                                <?php
                                                                if (($product_row['variants'][0]['special_price'] < $product_row['variants'][0]['price']) && ($product_row['variants'][0]['special_price'] != 0)) { ?>
                                                                            <p class="mb-0 price text-muted">
                                                                                <span id="price">
                                                                        <?php echo $settings['currency'] ?>
                                                                            <?php
                                                                            $price = $product_row['variants'][0]['special_price'];
                                                                            echo $price;
                                                                            ?>
                                                                                </span>
                                                                            </p>
                                                            <?php } else { ?>
                                                                            <p class="mb-0 price text-muted">
                                                                                <span id="price">
                                                                        <?php echo $settings['currency'] ?>
                                                                            <?php
                                                                            $price = $product_row['variants'][0]['price'];
                                                                            echo $price;
                                                                            ?>
                                                                                </span>
                                                                            </p>
                                                            <?php } ?>
                                                                    </h4>
                                                                    <p class="m-0 offer_price">
                                                                        <small>
                                                                <?php if ($product_row['is_on_sale'] == 1 && !empty($product_row['variants'][0]['sale_final_price'])) { ?>
                                                                                <span class="text-decoration-line-through fw-bold" itemprop="price">
                                                                        <?= $settings['currency'] . number_format($product_row['variants'][0]['price']) ?>
                                                                                </span>
                                                                                <span class="text-success fw-bold">
                                                                                    <span
                                                                                        class="product-discount-label fw-bold"><?= $product_row['sale_discount'] ?>%
                                                                                        off</span>
                                                                                </span>
                                                                <?php } elseif (!empty($product_row['variants'][0]['price']) && $product_row['variants'][0]['special_price'] < $product_row['variants'][0]['price'] && ($product_row['variants'][0]['special_price'] != 0)) {
                                                                    // Calculate discount percentage for the specific variant being displayed
                                                                    $original_price = $product_row['variants'][0]['price'];
                                                                    $special_price = $product_row['variants'][0]['special_price'];
                                                                    $discount_percentage = round((($original_price - $special_price) / $original_price) * 100);
                                                                    ?>
                                                                                <span class="text-decoration-line-through fw-bold" itemprop="price">

                                                                        <?= $settings['currency'] . number_format($product_row['variants'][0]['price']) ?>
                                                                                </span>
                                                                                <span class="text-success fw-bold">
                                                                                    <span
                                                                                        class="product-discount-label fw-bold"><?= $discount_percentage ?>%
                                                                                        off</span>
                                                                                </span>
                                                                <?php } ?>
                                                                        </small>
                                                                    </p>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                <?php } ?>
                                        </div>
                                        <div class="swiper-button-next">
                                            <ion-icon name="chevron-forward-outline"></ion-icon>
                                        </div>
                                        <div class="swiper-button-prev">
                                            <ion-icon name="chevron-back-outline"></ion-icon>
                                        </div>
                                    </div>
                                </div>
                            </section>
            <?php } else if ($row['style'] == 'style_4') { ?>
                                <!-- Style 4 Design-->
                                <!-- section 7 -->
                                <section class="container">
                                    <div class="mb-4 mb-md-5">
                                        <div class="align-items-center d-flex flex-wrap justify-content-between pb-3">
                                            <div>
                                                <h1 class="section-title  pointer"><?= ucfirst($row['title']) ?></h1>
                                                <p class="banner-paragraph  default-cursor"><?= strip_tags($row['short_description']) ?>
                                            </div>

                                            <a href="<?= base_url('products/section/' . $row['id'] . '/' . $row['slug']) ?>">
                                                <button class="btn viewmorebtn"><?= label('view_more', 'View More') ?></button>
                                            </a>
                                        </div>
                                        <div class="swiper mySwiper5 swiper-arrow">
                                            <div class="swiper-wrapper grab" <?= ($is_rtl == true) ? "dir='rtl'" : ""; ?>>
                                <?php foreach ($row['product_details'] as $product_row) {

                                    ?>
                                                    <div class="swiper-slide-5 swiper-slide">
                                                        <a href="<?= base_url('products/details/' . $product_row['slug']) ?>"
                                                            class="text-reset text-decoration-none">
                                                            <div class="card card6 mb-3 box-shadows product-card"
                                                                data-product-id="<?= $product_row['id'] ?>">
                                                                <div class="d-flex">
                                                                    <div class="card6-img">
                                                                        <img class="pic-1 img-fluid rounded-start lazy"
                                                                            src="<?= base_url('assets/front_end/modern/image/pictures/placeholder-image.png') ?>"
                                                                            data-src="<?= $product_row['image_sm'] ?>">
                                                                    </div>
                                                                    <div class="card-body card-body-6">
                                                                        <h5 class="card-title">
                                                            <?= word_limit(output_escaping(str_replace('\r\n', '&#13;&#10;', $product_row['name']))) ?>
                                                                        </h5>
                                                                        <p class="card-text">
                                                            <?= word_limit(output_escaping(str_replace('\r\n', '&#13;&#10;', $product_row['category_name']))) ?>
                                                                        </p>
                                                                        <input id="input-3-ltr-star-md" name="input-3-ltr-star-md"
                                                                            class="kv-ltr-theme-svg-star rating-loading"
                                                                            value="<?= $product_row['rating'] ?>" dir="ltr" data-size="xs"
                                                                            data-show-clear="false" data-show-caption="false" readonly>

                                                                        <div class="d-flex gap-1">
                                                                            <h4 class="card-price" itemprop="offers">
                                                                    <?php
                                                                    if (($product_row['variants'][0]['special_price'] < $product_row['variants'][0]['price']) && ($product_row['variants'][0]['special_price'] != 0)) { ?>
                                                                                    <p class="mb-0 price text-muted">
                                                                                        <span id="price">
                                                                            <?php echo $settings['currency'] ?>
                                                                                <?php
                                                                                $price = $product_row['variants'][0]['special_price'];
                                                                                echo $price;
                                                                                ?>
                                                                                        </span>
                                                                                    </p>
                                                                <?php } else { ?>
                                                                                    <p class="mb-0 price text-muted">
                                                                                        <span id="price">
                                                                            <?php echo $settings['currency'] ?>
                                                                                <?php
                                                                                $price = $product_row['variants'][0]['price'];
                                                                                echo $price;
                                                                                ?>
                                                                                        </span>
                                                                                    </p>
                                                                <?php } ?>
                                                                            </h4>
                                                                            <p class="m-0 offer_price">
                                                                                <small>
                                                                    <?php if ($product_row['is_on_sale'] == 1 && !empty($product_row['variants'][0]['sale_final_price'])) { ?>
                                                                                        <span class="text-decoration-line-through fw-bold" itemprop="price">
                                                                            <?= $settings['currency'] . number_format($product_row['variants'][0]['price']) ?>
                                                                                        </span>
                                                                                        <span class="text-success fw-bold">
                                                                                            <span
                                                                                                class="product-discount-label fw-bold"><?= $product_row['sale_discount'] ?>%
                                                                                                off</span>
                                                                                        </span>
                                                                    <?php } elseif (!empty($product_row['variants'][0]['price']) && $product_row['variants'][0]['special_price'] < $product_row['variants'][0]['price'] && ($product_row['variants'][0]['special_price'] != 0)) {
                                                                        // Calculate discount percentage for the specific variant being displayed
                                                                        $original_price = $product_row['variants'][0]['price'];
                                                                        $special_price = $product_row['variants'][0]['special_price'];
                                                                        $discount_percentage = round((($original_price - $special_price) / $original_price) * 100);
                                                                        ?>
                                                                                        <span class="text-decoration-line-through fw-bold" itemprop="price">

                                                                            <?= $settings['currency'] . number_format($product_row['variants'][0]['price']) ?>
                                                                                        </span>
                                                                                        <span class="text-success fw-bold">
                                                                                            <span
                                                                                                class="product-discount-label fw-bold"><?= $discount_percentage ?>%
                                                                                                off</span>
                                                                                        </span>
                                                                    <?php } ?>
                                                                                </small>
                                                                            </p>
                                                                        </div>

                                                                        <small class="product-disc"><?= $product_row['short_description'] ?></small>
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
                                                            <?php
                                                            if ($product_row['is_on_sale'] == 1) {
                                                                $variant_price = ($product_row['variants'][0]['sale_final_price'] > 0 && $product_row['variants'][0]['sale_final_price'] != '') ? $product_row['variants'][0]['sale_final_price'] : $product_row['variants'][0]['price'];
                                                            } else {
                                                                $variant_price = ($product_row['variants'][0]['special_price'] > 0 && $product_row['variants'][0]['special_price'] != '') ? $product_row['variants'][0]['special_price'] : $product_row['variants'][0]['price'];
                                                                ;
                                                            }
                                                            $data_min = (isset($product_row['minimum_order_quantity']) && !empty($product_row['minimum_order_quantity'])) ? $product_row['minimum_order_quantity'] : 1;
                                                            $data_step = (isset($product_row['minimum_order_quantity']) && !empty($product_row['quantity_step_size'])) ? $product_row['quantity_step_size'] : 1;
                                                            $data_max = (isset($product_row['total_allowed_quantity']) && !empty($product_row['total_allowed_quantity'])) ? $product_row['total_allowed_quantity'] : 0;
                                                            ?>
                                                                        <a href="#"
                                                                            class="btn add-in-cart-btn add_to_cart w-100 btn-sm px-1 <?= $class_modal ?>"
                                                                            data-product-id="<?= $product_row['id'] ?>"
                                                                            data-product-variant-id="<?= $variant_id ?>"
                                                                            data-product-title="<?= $product_row['name'] ?>"
                                                                            data-product-image="<?= $product_row['image']; ?>"
                                                                            data-product-price="<?= $variant_price; ?>" data-min="<?= $data_min; ?>"
                                                                            data-max="<?= $data_max; ?>" data-step="<?= $data_step; ?>"
                                                                            data-product-description="<?= $product_row['short_description']; ?>"
                                                                            data-bs-toggle="modal" data-bs-target="<?= $modal ?>"><span
                                                                                class="add-in-cart"><?= label('add_to_cart', 'Add to Cart') ?></span><span
                                                                                class="add-in-cart-icon"><i class="fa-solid fa-cart-shopping
                                                color-white"></i></span></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                <?php } ?>
                                            </div>
                                            <div class="swiper-button-next">
                                                <ion-icon name="chevron-forward-outline"></ion-icon>
                                            </div>
                                            <div class="swiper-button-prev">
                                                <ion-icon name="chevron-back-outline"></ion-icon>
                                            </div>
                                        </div>
                                    </div>
                                </section>
            <?php } ?>
        <?php }
        if ($flash_sale_count > $showFlashSale) {
            ?>
            <div class="container-fluid bg-gradient-design mb-4 mb-md-5">
                <?php
                fetch_active_flash_sale();
                ?>
                <div class="container pt-4">
                    <div class="row">
                        <div class="col-12 text-center align-self-center">
                            <div class="sale-section">
                                <h4 class="banner-heading default-cursor"><?= ucfirst($flash_sale[$showFlashSale]['title']) ?>
                                </h4>
                                <p class="banner-paragraph  default-cursor">
                                    <?= strip_tags($flash_sale[$showFlashSale]['short_description']) ?>
                                </p>
                                <input type="hidden" name="sale_timer" id="sale_timer" value="5/17/2024">
                            </div>
                            <p class="d-none get_e_time" data-value="<?= $flash_sale[$showFlashSale]['id'] ?>">
                                <?php print_r($flash_sale[$showFlashSale]['end_date']); ?>
                            </p>
                            <div class="flash_sale_timers countdown" id="timer-<?= $flash_sale[$showFlashSale]['id'] ?>"
                                data-value="<?= $flash_sale[$showFlashSale]['id'] ?>"
                                data-value="<?php print_r($flash_sale[$showFlashSale]['end_date']); ?>">
                            </div>
                        </div>
                        <div class="swiper swiper-arrow mySwiper4">
                            <div class="swiper-wrapper my-5 grab">
                                <?php foreach ($flash_sale[$showFlashSale]['product_details'] as $product_row) {
                                    // echo "<pre>";
                                    // print_r($product_row);
                                    // Ensure `price` and `discount` are set and valid before calculating sale price
                                    $price = isset($product_row['variants'][0]['price']) ? $product_row['variants'][0]['price'] : 0;
                                    $discount = isset($flash_sale[$showFlashSale]['discount']) ? $flash_sale[$showFlashSale]['discount'] : 0;
                                    $sale_price = get_flash_sale_price($price, $discount);
                                    ?>
                                    <div class="swiper-slide box-shadow">
                                        <a href="<?= isset($product_row['slug']) ? base_url('products/details/' . $product_row['slug']) : '#' ?>"
                                            class="text-reset text-decoration-none">
                                            <div class="card5">
                                                <img class="pic-1 lazy card-img-top pointer"
                                                    src="<?= base_url('assets/front_end/modern/image/pictures/placeholder-image.png') ?>"
                                                    data-src="<?= isset($product_row['image_sm']) && !empty($product_row['image_sm']) && $product_row['image_sm'] !== null ? $product_row['image_sm'] : base_url('assets/front_end/modern/image/pictures/placeholder-image.png') ?>">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        <?= word_limit(output_escaping(str_replace('\r\n', '&#13;&#10;', isset($product_row['name']) ? $product_row['name'] : ''))) ?>
                                                    </h5>
                                                    <input id="input-3-ltr-star-md" name="input-3-ltr-star-md"
                                                        class="kv-ltr-theme-svg-star rating-loading"
                                                        value="<?= isset($product_row['rating']) ? $product_row['rating'] : 0 ?>"
                                                        dir="ltr" data-size="xs" data-show-clear="false" data-show-caption="false"
                                                        readonly>
                                                    <h5 class="card-price">
                                                        <?php
                                                        $special_price = isset($product_row['variants'][0]['special_price']) ? $product_row['variants'][0]['special_price'] : 0;
                                                        $regular_price = isset($product_row['variants'][0]['price']) ? $product_row['variants'][0]['price'] : 0;

                                                        ?>
                                                        <p class="mb-0 mt-2 price text-muted">
                                                            <span id="price" class='font-size-20px'>
                                                                <?php echo isset($settings['currency']) ? $settings['currency'] : '' ?>
                                                                <?php echo number_format($product_row['variants'][0]['sale_final_price'], 2); ?>
                                                            </span>
                                                        </p>

                                                    </h5>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="swiper-button-next">
                                <ion-icon name="chevron-forward-outline"></ion-icon>
                            </div>
                            <div class="swiper-button-prev">
                                <ion-icon name="chevron-back-outline"></ion-icon>
                            </div>
                        </div>
                    </div>
                </div>
                <?php ?>
            </div>
            <?php
            $showFlashSale = $showFlashSale + 1;
        }
    } ?>
    <section class="container">
        <!-- section 9 -->
        <?php if (isset($web_settings['app_download_section']) && $web_settings['app_download_section'] == 1) { ?>
            <div class="py-4 bg-white my-4 border-radius-10">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mobile-app-wrapper">
                            <img src="<?= base_url('assets/front_end/modern/image/avtars/4861083.jpg') ?>" alt="">
                        </div>
                    </div>
                    <div class="col-md-6 d-flex justify-content-center align-items-center">
                        <div class="text-center">
                            <div>
                                <h3 class="section-title fs-1"><?= $web_settings['app_download_section_title'] ?></h3>
                                <h3 class="fs-4 fw-medium gray-700"><?= $web_settings['app_download_section_tagline'] ?>
                                </h3>
                            </div>
                            <p class="m-0 gray-700"><?= $web_settings['app_download_section_short_description'] ?></p>
                            <div class="mt-3">
                                <a href="<?= $web_settings['app_download_section_appstore_url'] ?>" target="_blank"><img
                                        src="<?= base_url('assets/front_end/modern/image/app-store/app-store.png') ?>"
                                        alt="" class="download_section" width="150"></a>
                                <a href="<?= $web_settings['app_download_section_playstore_url'] ?>" target="_blank"><img
                                        src="<?= base_url('assets/front_end/modern/image/app-store/google-play-store.png') ?>"
                                        alt="" class="download_section" width="150"></a>
                            </div>
                        </div>
                    </div>
                </div> <!-- end of row -->
            </div> <!-- end of container -->
        </section>
    <?php } ?>
    <?php if ($web_settings['shipping_mode'] == 1 || $web_settings['safety_security_mode'] == 1 || $web_settings['support_mode'] == 1 || $web_settings['return_mode'] == 1) { ?>
        <section class="freedel-sec bg-nav">
            <div class="container py-3">
                <div class="row row-fluid dark-footer-margin">
                    <?php if (isset($web_settings['shipping_mode']) && $web_settings['shipping_mode'] == 1) { ?>
                        <div class="col text-left text-md-center">
                            <div class="column_inner custom_column">
                                <div class="wrapper">
                                    <div class="info-box-wrapper inline-element">
                                        <div class="box-icon info-box custom light-color">
                                            <div class="icon-box-wrapper">
                                                <div class="info-box-icon">
                                                    <div class="svg-wrapper">
                                                        <svg xmlns="http://www.w3.org/2000/svg" height="30pt"
                                                            viewBox="1 -104 511.99975 511" width="60pt">
                                                            <path class="cls-1"
                                                                d="m203.84375 227.585938h156.140625c17.679687-52.050782 91.449219-51.550782 108.957031 0h13.046875v-75.027344c0-9.347656-7.660156-17.007813-17.003906-17.007813-38.347656 0-76.695313 0-115.042969 0-8.289062 0-15.007812-6.71875-15.007812-15.003906v-90.035156h-257.09375v18.003906h27.589844c19.753906 0 19.753906 30.011719 0 30.011719h-27.589844v29.011718h72.027344c19.757812 0 19.757812 30.011719 0 30.011719h-72.027344v90.03125h17.050781c17.675781-52.050781 91.449219-51.542969 108.953125.003907zm-156.015625-149.058594h-18.003906c-19.757813 0-19.757813-30.011719 0-30.011719h18.003906v-33.011719c0-8.285156 6.71875-15.003906 15.007813-15.003906 22.964843 0 275.351562.097656 275.351562 0 41.078125 0 76.859375 20.335938 97.914062 55.667969l29.421876 49.375c25.675781.289062 46.476562 21.292969 46.476562 47.011719v90.035156c0 8.285156-6.71875 15.003906-15.003906 15.003906h-26.160156c-5.378907 26.484375-28.78125 46.015625-56.371094 46.015625s-50.992188-19.53125-56.371094-46.015625h-152.355469c-5.378906 26.484375-28.78125 46.015625-56.371093 46.015625-27.589844 0-50.992188-19.53125-56.371094-46.015625h-30.160156c-8.289063 0-15.007813-6.71875-15.007813-15.003906v-45.019532h-33.011719c-19.753906 0-19.753906-30.007812 0-30.007812h33.011719v-30.011719h-33.011719c-19.753906 0-19.753906-30.011719 0-30.011719h33.011719zm386.089844 148.109375c10.742187 10.742187 10.742187 28.164062 0 38.90625-17.28125 17.28125-46.964844 4.988281-46.964844-19.453125s29.683594-36.734375 46.964844-19.453125zm-3.222657-121.097657h-65.75v-70.960937c19.144532 6.136719 34.800782 19.019531 45.480469 36.945313zm-261.875 121.097657c10.742188 10.742187 10.742188 28.164062 0 38.90625-17.28125 17.28125-46.964843 4.988281-46.964843-19.453125.003906-24.441406 29.683593-36.734375 46.964843-19.453125zm0 0"
                                                                fill-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="info-box-content">
                                                <h5 class="info-title"><?= $web_settings['shipping_title'] ?></h5>
                                                <div class="info-box-inner text-muted">
                                                    <p><?= $web_settings['shipping_description'] ?></p>
                                                </div>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (isset($web_settings['return_mode']) && $web_settings['return_mode'] == 1) { ?>
                        <div class="col text-left text-md-center">
                            <div class="column_inner custom_column">
                                <div class="wrapper">
                                    <div class="info-box-wrapper inline-element">
                                        <div class="box-icon info-box custom light-color">
                                            <div class="icon-box-wrapper">
                                                <div class="info-box-icon">
                                                    <div class="svg-wrapper">
                                                        <svg xmlns="http://www.w3.org/2000/svg" height="30pt"
                                                            viewBox="0 0 512 512" width="60pt">
                                                            <path class="cls-1"
                                                                d="m212 367h89c33.085938 0 60-26.914062 60-60v-43.402344c9.128906-1.851562 16-9.921875 16-19.597656v-70c0-11.046875-8.953125-20-20-20h-201c-11.046875 0-20 8.953125-20 20v70c0 9.675781 6.871094 17.746094 16 19.597656v43.402344c0 33.085938 26.914062 60 60 60zm89-40h-89c-11.027344 0-20-8.972656-20-20v-41h46v8c0 11.046875 8.953125 20 20 20s20-8.953125 20-20v-8h43v41c0 11.027344-8.972656 20-20 20zm-125-133h161v30h-161zm-176-60v-48c0-11.046875 8.953125-20 20-20s20 8.953125 20 20v32.535156c19.679688-30.890625 45.8125-57.316406 76.84375-77.445312 41.4375-26.878906 89.554688-41.089844 139.15625-41.089844 68.378906 0 132.667969 26.628906 181.019531 74.980469 48.351563 48.351562 74.980469 112.640625 74.980469 181.019531 0 11.046875-8.953125 20-20 20s-20-8.953125-20-20c0-119.101562-96.898438-216-216-216-75.664062 0-145.871094 40.15625-184.726562 104h26.726562c11.046875 0 20 8.953125 20 20s-8.953125 20-20 20h-48c-27.570312 0-50-22.429688-50-50zm512 244v47c0 11.046875-8.953125 20-20 20s-20-8.953125-20-20v-33.105469c-19.789062 31.570313-46.289062 58.542969-77.84375 79.011719-41.4375 26.882812-89.554688 41.09375-139.15625 41.09375-68.339844 0-132.464844-26.644531-180.5625-75.023438-48-48.285156-74.4375-112.554687-74.4375-180.976562 0-11.046875 8.953125-20 20-20s20 8.953125 20 20c0 119.101562 96.449219 216 215 216 75.667969 0 145.871094-40.15625 184.726562-104h-26.726562c-11.046875 0-20-8.953125-20-20s8.953125-20 20-20h49c27.570312 0 50 22.429688 50 50zm0 0">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="info-box-content">
                                                <h5 class="info-title"><?= $web_settings['return_title'] ?></h5>
                                                <div class="info-box-inner text-muted">
                                                    <p><?= $web_settings['return_description'] ?></p>
                                                </div>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (isset($web_settings['support_mode']) && $web_settings['support_mode'] == 1) { ?>
                        <div class="col text-left text-md-center">
                            <div class="column_inner custom_column">
                                <div class="wrapper">
                                    <div class="info-box-wrapper inline-element">
                                        <div class="box-icon info-box custom light-color">
                                            <div class="icon-box-wrapper">
                                                <div class="info-box-icon">
                                                    <div class="svg-wrapper">
                                                        <svg xmlns="http://www.w3.org/2000/svg" height="30pt"
                                                            viewBox="-21 -21 682.66669 682.66669" width="60pt">
                                                            <path class="cls-1"
                                                                d="m546.273438 93.726562c-60.4375-60.441406-140.800782-93.726562-226.273438-93.726562s-165.835938 33.285156-226.273438 93.726562c-60.441406 60.4375-93.726562 140.800782-93.726562 226.273438s33.285156 165.835938 93.726562 226.273438c60.4375 60.441406 140.800782 93.726562 226.273438 93.726562 2.574219 0 5.195312-.03125 7.78125-.09375 10.359375-.253906 18.546875-8.847656 18.292969-19.199219-.25-10.355469-8.808594-18.523437-19.199219-18.289062-2.285156.050781-4.601562.082031-6.875.082031-155.773438 0-282.5-126.726562-282.5-282.5s126.726562-282.5 282.5-282.5 282.5 126.726562 282.5 282.5c0 56.867188-16.898438 111.78125-48.867188 158.8125-6.230468 9.175781-14.601562 14.414062-25.570312 16.023438-10.976562 1.605468-20.492188-1.011719-29.097656-8.003907l-14.855469-12.074219c1.78125-1.429687 3.527344-2.898437 5.242187-4.421874 11.019532-9.785157 16.503907-23.269532 15.4375-37.972657-1.0625-14.703125-8.4375-27.253906-20.757812-35.351562l-59.386719-39.027344c-16.769531-11.023437-38.542969-10.058594-54.183593 2.386719-8.796876 6.996094-20.945313 6.308594-28.890626-1.636719l-60.304687-60.300781c-7.945313-7.945313-8.632813-20.097656-1.636719-28.894532 12.445313-15.636718 13.410156-37.414062 2.386719-54.183593l-39.027344-59.386719c-8.097656-12.320312-20.648437-19.6875-35.351562-20.757812-14.707031-1.054688-28.1875 4.417968-37.972657 15.4375-48.261718 54.347656-45.792968 137.199218 5.625 188.617187l125.445313 125.445313c26.75 26.753906 62.007813 40.257812 97.34375 40.253906 17.484375 0 34.972656-3.339844 51.46875-9.984375l25.757813 20.949219c16.730468 13.601562 36.851562 19.140624 58.191406 16.007812 21.339844-3.125 39.027344-14.207031 51.152344-32.039062 36.210937-53.277344 55.351562-115.484376 55.351562-179.898438 0-85.472656-33.285156-165.835938-93.726562-226.273438zm0 0" />
                                                            <path class="cls-1"
                                                                d="m537.148438 275.257812c-8.117188 0-15.59375-5.304687-17.988282-13.492187-24.882812-85.101563-101.113281-145-189.6875-149.046875-10.339844-.472656-18.34375-9.242188-17.871094-19.589844.472657-10.339844 9.238282-18.339844 19.585938-17.871094 104.578125 4.78125 194.585938 75.503907 223.964844 175.984376 2.902344 9.9375-2.792969 20.355468-12.734375 23.257812-1.753907.515625-3.527344.757812-5.269531.757812zm0 0" />
                                                            <path class="cls-1"
                                                                d="m465.160156 296.308594c-8.113281 0-15.59375-5.3125-17.988281-13.492188-15.886719-54.34375-64.558594-92.589844-121.121094-95.179687-10.34375-.46875-18.347656-9.238281-17.871093-19.585938.472656-10.347656 9.242187-18.347656 19.585937-17.871093 72.5625 3.316406 135.015625 52.390624 155.402344 122.109374 2.90625 9.941407-2.796875 20.351563-12.734375 23.257813-1.757813.515625-3.527344.761719-5.273438.761719zm0 0" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="info-box-content">
                                                <h5 class="info-title"><?= $web_settings['support_title'] ?></h5>
                                                <div class="info-box-inner text-muted">
                                                    <p><?= output_escaping(str_replace('\r\n', '&#13;&#10;', $web_settings['support_description'])) ?>
                                                    </p>
                                                </div>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (isset($web_settings['safety_security_mode']) && $web_settings['safety_security_mode'] == 1) { ?>
                        <div class="col text-left text-md-center">
                            <div class="column_inner custom_column">
                                <div class="wrapper">
                                    <div class="info-box-wrapper inline-element">
                                        <div class="box-icon info-box custom light-color">
                                            <div class="icon-box-wrapper">
                                                <div class="info-box-icon">
                                                    <div class="svg-wrapper">
                                                        <svg width="60pt" viewBox="-38 0 512 512.00142" version="1.1"
                                                            height="30pt" xmlns="http://www.w3.org/2000/svg">
                                                            <g id="surface1">
                                                                <path class="cls-1"
                                                                    d="M 435.488281 138.917969 L 435.472656 138.519531 C 435.25 133.601562 435.101562 128.398438 435.011719 122.609375 C 434.59375 94.378906 412.152344 71.027344 383.917969 69.449219 C 325.050781 66.164062 279.511719 46.96875 240.601562 9.042969 L 240.269531 8.726562 C 227.578125 -2.910156 208.433594 -2.910156 195.738281 8.726562 L 195.40625 9.042969 C 156.496094 46.96875 110.957031 66.164062 52.089844 69.453125 C 23.859375 71.027344 1.414062 94.378906 0.996094 122.613281 C 0.910156 128.363281 0.757812 133.566406 0.535156 138.519531 L 0.511719 139.445312 C -0.632812 199.472656 -2.054688 274.179688 22.9375 341.988281 C 36.679688 379.277344 57.492188 411.691406 84.792969 438.335938 C 115.886719 468.679688 156.613281 492.769531 205.839844 509.933594 C 207.441406 510.492188 209.105469 510.945312 210.800781 511.285156 C 213.191406 511.761719 215.597656 512 218.003906 512 C 220.410156 512 222.820312 511.761719 225.207031 511.285156 C 226.902344 510.945312 228.578125 510.488281 230.1875 509.925781 C 279.355469 492.730469 320.039062 468.628906 351.105469 438.289062 C 378.394531 411.636719 399.207031 379.214844 412.960938 341.917969 C 438.046875 273.90625 436.628906 199.058594 435.488281 138.917969 Z M 384.773438 331.523438 C 358.414062 402.992188 304.605469 452.074219 220.273438 481.566406 C 219.972656 481.667969 219.652344 481.757812 219.320312 481.824219 C 218.449219 481.996094 217.5625 481.996094 216.679688 481.820312 C 216.351562 481.753906 216.03125 481.667969 215.734375 481.566406 C 131.3125 452.128906 77.46875 403.074219 51.128906 331.601562 C 28.09375 269.097656 29.398438 200.519531 30.550781 140.019531 L 30.558594 139.683594 C 30.792969 134.484375 30.949219 129.039062 31.035156 123.054688 C 31.222656 110.519531 41.207031 100.148438 53.765625 99.449219 C 87.078125 97.589844 116.34375 91.152344 143.234375 79.769531 C 170.089844 68.402344 193.941406 52.378906 216.144531 30.785156 C 217.273438 29.832031 218.738281 29.828125 219.863281 30.785156 C 242.070312 52.378906 265.921875 68.402344 292.773438 79.769531 C 319.664062 91.152344 348.929688 97.589844 382.246094 99.449219 C 394.804688 100.148438 404.789062 110.519531 404.972656 123.058594 C 405.0625 129.074219 405.21875 134.519531 405.453125 139.683594 C 406.601562 200.253906 407.875 268.886719 384.773438 331.523438 Z M 384.773438 331.523438 ">
                                                                </path>
                                                                <path class="cls-1"
                                                                    d="M 217.996094 128.410156 C 147.636719 128.410156 90.398438 185.652344 90.398438 256.007812 C 90.398438 326.367188 147.636719 383.609375 217.996094 383.609375 C 288.351562 383.609375 345.59375 326.367188 345.59375 256.007812 C 345.59375 185.652344 288.351562 128.410156 217.996094 128.410156 Z M 217.996094 353.5625 C 164.203125 353.5625 120.441406 309.800781 120.441406 256.007812 C 120.441406 202.214844 164.203125 158.453125 217.996094 158.453125 C 271.785156 158.453125 315.546875 202.214844 315.546875 256.007812 C 315.546875 309.800781 271.785156 353.5625 217.996094 353.5625 Z M 217.996094 353.5625 ">
                                                                </path>
                                                                <path class="cls-1"
                                                                    d="M 254.667969 216.394531 L 195.402344 275.660156 L 179.316406 259.574219 C 173.449219 253.707031 163.9375 253.707031 158.070312 259.574219 C 152.207031 265.441406 152.207031 274.953125 158.070312 280.816406 L 184.78125 307.527344 C 187.714844 310.460938 191.558594 311.925781 195.402344 311.925781 C 199.246094 311.925781 203.089844 310.460938 206.023438 307.527344 L 275.914062 237.636719 C 281.777344 231.769531 281.777344 222.257812 275.914062 216.394531 C 270.046875 210.523438 260.535156 210.523438 254.667969 216.394531 Z M 254.667969 216.394531 ">
                                                                </path>
                                                            </g>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="info-box-content">
                                                <h5 class="info-title"><?= $web_settings['safety_security_title'] ?></h5>
                                                <div class="info-box-inner text-muted">
                                                    <p><?= $web_settings['safety_security_description'] ?></p>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>
    <?php } ?>
    <?php $web_settings = get_settings('web_settings', true);
    $system_settings = get_settings('system_settings', true);
    if (isset($system_settings['is_offer_popup_on']) && ($system_settings['is_offer_popup_on'] == 1) && (isset($system_settings['offer_popup_method']))) {
        $offer_popup_value = $system_settings['offer_popup_method'];

        if ($this->session->userdata('popup') == '') {
            $this->session->set_userdata('popup', $system_settings['offer_popup_method']);
            $offer_popup_value = $this->session->userdata('popup');
        } elseif ($this->session->userdata('popup') == "session_storage") {
            $this->session->set_userdata('popup', $system_settings['offer_popup_method']);
            $offer_popup_value = "null";
        } elseif ($this->session->userdata('popup') == "refresh") {
            $this->session->set_userdata('popup', $system_settings['offer_popup_method']);
            $offer_popup_value = $this->session->userdata('popup');
        }

        ?>
        <div class="offer_popup_value">
            <input type="hidden" name="offer_popup_value" id="offer_popup_value" value="<?= $offer_popup_value ?>">
        </div>

    <?php } ?>
    <?php
    if (isset($_SESSION['popup'])) {

        $resualt = $this->db->query('select  * from popup_offers where status=1');
        $offer_image = $resualt->result_array();
        $id = isset($offer_image[0]['id']) ? $offer_image[0]['id'] : '';
        $image = isset($offer_image[0]['image']) ? $offer_image[0]['image'] : '';
    }

    if (isset($id) && !empty($id)) { ?>
        <div class="modal fade" id="offer_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-transparent bg-transparent border-color-transparent border-0">
                    <button type="button" class="quickview-close-btn align-items-center d-flex justify-content-center"
                        data-bs-dismiss="modal" aria-label="Close">
                        <ion-icon name="close-outline"></ion-icon>
                    </button>
                    <div class="modal-body modal_border_none cursor-pointer p-0"
                        onclick="location.href='<?= base_url('products/manage_popup_offers/' . $id) ?>'">
                        <img class="w-100 d-block" onerror="this.src='<?= base_url($image) ?>'"
                            src="<?= base_url($image) ?>" alt="">
                    </div>

                </div>
            </div>
        </div>
    <?php } ?>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('<?= base_url('/firebase-messaging-sw.js'); ?>')
                .then(function (registration) { })
                .catch(function (error) { });
        }
    </script>

    <script defer type="text/babel" src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app.js"></script>
    <script defer type="text/babel" src="https://www.gstatic.com/firebasejs/9.23.0/firebase-auth.js"></script>
    <script defer type="text/babel" src="https://www.gstatic.com/firebasejs/9.23.0/firebase-messaging.js"></script>
    <!-- chat -->
    <script type="module" src="<?= base_url('assets/seller/components-chat-box.js') ?>"></script>

</main>