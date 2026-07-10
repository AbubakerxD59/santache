<main>
    <section class="container py-4">
        <div class="cart-page-title">
            <div class="checkout-step justify-content-around py-5   ">
                <a href="cart">
                    <h3 class="step-active"><?= label('shopping_cart', 'SHOPPING CART') ?></h3>
                </a>
                <i class="fa-solid fa-arrow-right-long d-md-block d-none"></i>
                <a href="cart/checkout">
                    <h3 class="d-md-block d-none"><?= label('checkout', 'CHECKOUT') ?></h3>
                </a>
                <i class="fa-solid fa-arrow-right-long d-md-block d-none"></i>
                <h3 class="d-md-block d-none"><?= label('order_complete', 'ORDER COMPLETE') ?></h3>
            </div>
        </div>
        <div class="row py-4">
            <div class="col-xl-8">
                <div class="mb-4 cart-table">

                    <?php if (isset($cart) && !empty($cart) && $cart['quantity'] >= 1) { ?>
                        <table id="cart_item_table" class="table w-100 mb-4">
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col" class="w-25"><?= label('product', 'PRODUCT') ?></th>
                                    <th scope="col"><?= label('price', 'PRICE') ?></th>
                                    <th scope="col"><?= label('tax', 'TAX') ?></th>
                                    <th scope="col"><?= label('quantity', 'QUANTITY') ?></th>
                                    <th scope="col"><?= label('subtotal', 'SUBTOTAL') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart as $key => $row) {
                                    if (isset($row['qty']) && $row['qty'] != 0) {
                                        if ($row['sale_price'] != 0) {
                                            $price = $row['sale_price'];
                                        } else {
                                            $price = $row['special_price'] != '' && $row['special_price'] != null && $row['special_price'] > 0 ? $row['special_price'] : $row['price'];
                                        }
                                        ?>
                                        <tr>
                                            <td class="product-removal">
                                                <ion-icon name="close-outline" class="remove-product pointer" id="remove_inventory"
                                                    data-id="<?= $row['id']; ?>" title="Remove From Cart"></ion-icon>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('products/details/' . $row['slug']) ?>" target="_blank">
                                                    <div class="product-thumbnail">
                                                        <img src="<?= !empty($row['image']) ? base_url($row['image']) : base_url(NO_IMAGE) ?>"
                                                            alt="<?= html_escape($row['name']) ?>"
                                                            onerror="this.onerror=null;this.src='<?= base_url(NO_IMAGE) ?>';" />
                                                    </div>
                                                </a>
                                            </td>
                                            <div class="id">
                                                <input type="hidden" name="<?= 'id[' . $key . ']' ?>" id="id"
                                                    value="<?= $row['id'] ?>">
                                            </div>
                                            <td>
                                                <p class="product-name"><?= $row['name'] ?></p>
                                                <p class="product-disc">
                                                    <?= output_escaping(str_replace('\r\n', '&#13;&#10;', $row['short_description'])) ?>
                                                </p>
                                                <p
                                                    class="product-disc text-capitalize <?= isset($row['product_variants'][0]['variant_values']) ? "d-block" : "d-none" ?>">
                                                    <?= isset($row['product_variants'][0]['variant_values']) ? $row['product_variants'][0]['variant_values'] : "" ?>
                                                </p>
                                                <button class="save_for_later btn btn-sm btn-primary mt-1"
                                                    data-id="<?= $row['id'] ?>"
                                                    data-save-for-later="1"><?= label('save_for_later', 'Save For Later') ?></button>
                                            </td>
                                            <td class="product-price">
                                                <span class="d-md-none d-block"><?= label('price', 'PRICE') ?></span>
                                                <p class="text-nowrap"><?= $settings['currency'] . number_format($price, 2) ?></p>
                                            </td>
                                            <?php if ($row['item_tax_percentage'] > 0) { ?>
                                                <td class="product-price">
                                                    <span class="d-md-none d-block"><?= label('tax', 'TAX') ?></span>
                                                    <p class="text-nowrap"><?= $row['item_tax_percentage'] . "%" ?></p>
                                                </td>
                                            <?php } else { ?>
                                                <td class="product-price">
                                                    <span class="d-md-none d-block"><?= label('tax', 'TAX') ?></span>
                                                    <p>N/A</p>
                                                </td>

                                            <?php } ?>
                                            <td class="product-quantity" style="min-width: 150px;">
                                                <span class="d-md-none d-block"><?= label('quantity', 'QUANTITY') ?></span>
                                                <!-- <div class="input-group plus-minus-input mb-3 num-block">
                                                    <?php $check_current_stock_status = validate_stock([$row['id']], [$row['qty']]); ?>
                                                    <?php if (isset($check_current_stock_status['error']) && $check_current_stock_status['error'] == TRUE) { ?>
                                                        <div><span class='text text-danger'> Out of Stock </span></div>
                                                    <?php } else { ?>
                                                        <div class="num-in d-flex">
                                                            <?php $price ?>
                                                            <div class="input-group-button">
                                                                <button type="button" class="button hollow circle minus-btn minus dis"
                                                                    data-quantity="minus"
                                                                    data-min="<?= (isset($row['minimum_order_quantity']) && !empty($row['minimum_order_quantity'])) ? $row['minimum_order_quantity'] : 1 ?>"
                                                                    data-step="<?= (isset($row['minimum_order_quantity']) && !empty($row['quantity_step_size'])) ? $row['quantity_step_size'] : 1 ?>">
                                                                    <i class="fa-solid fa-minus"></i>
                                                                </button>
                                                            </div>
                                                            <input class="input-group-field input-field-cart-modal in-num "
                                                                type="number" name="qty" data-page="cart" data-id="<?= $row['id']; ?>"
                                                                value="<?= $row['qty'] ?>" data-price="<?= $price ?>"
                                                                data-step="<?= (isset($row['minimum_order_quantity']) && !empty($row['quantity_step_size'])) ? $row['quantity_step_size'] : 1 ?>"
                                                                data-min="<?= (isset($row['minimum_order_quantity']) && !empty($row['minimum_order_quantity'])) ? $row['minimum_order_quantity'] : 1 ?>"
                                                                data-max="<?= (isset($row['total_allowed_quantity']) && !empty($row['total_allowed_quantity'])) ? $row['total_allowed_quantity'] : '1' ?>">
                                                            <div class="input-group-button">
                                                                <button type="button" class="button hollow circle plus-btn plus"
                                                                    data-quantity="plus" data-field="quantity"
                                                                    data-max="<?= (isset($row['total_allowed_quantity']) && !empty($row['total_allowed_quantity'])) ? $row['total_allowed_quantity'] : '1' ?> "
                                                                    data-step="<?= (isset($row['minimum_order_quantity']) && !empty($row['quantity_step_size'])) ? $row['quantity_step_size'] : 1 ?>">
                                                                    <i class="fa-solid fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div> -->
                                                <div class="input-group plus-minus-input mb-3 num-block">
                                                    <?php $check_current_stock_status = validate_stock([$row['id']], [$row['qty']]); ?>
                                                    <?php if (isset($check_current_stock_status['error']) && $check_current_stock_status['error'] == TRUE) { ?>
                                                        <div><span class='text text-danger'> Out of Stock </span></div>
                                                    <?php } else { ?>
                                                        <div class="d-flex flex-nowrap">
                                                            <div class="input-group-button">
                                                                <button type="button" class="button hollow circle minus-btn minus"
                                                                    data-quantity="minus" data-field="quantity"
                                                                    data-min="<?= (isset($row['minimum_order_quantity']) && !empty($row['minimum_order_quantity'])) ? $row['minimum_order_quantity'] : 1 ?>"
                                                                    data-step="<?= (isset($row['minimum_order_quantity']) && !empty($row['quantity_step_size'])) ? $row['quantity_step_size'] : 1 ?>">
                                                                    <i class="fa-solid fa-minus"></i>
                                                                </button>
                                                            </div>
                                                            <div class="product-quantity product-sm-quantity border-0 m-0">
                                                                <input type="number" name="qty"
                                                                    class="input-group-field input-field-cart-modal form-input in-num"
                                                                    value="<?= $row['qty'] ?>" data-page="cart"
                                                                    data-id="<?= $row['id']; ?>" data-price="<?= $price ?>"
                                                                    min="<?= $row['minimum_order_quantity'] ?>"
                                                                    max="<?= $row['total_allowed_quantity'] ?>"
                                                                    step="<?= $row['quantity_step_size'] ?>">
                                                            </div>
                                                            <div class="input-group-button">
                                                                <button type="button" class="button hollow circle plus-btn plus"
                                                                    data-quantity="plus" data-field="quantity"
                                                                    data-max="<?= $row['total_allowed_quantity'] ?>"
                                                                    data-step="<?= (isset($row['minimum_order_quantity']) && !empty($row['quantity_step_size'])) ? $row['quantity_step_size'] : 1 ?>">
                                                                    <i class="fa-solid fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>


                                            </td>
                                            <td class="product-subtotal total-price">
                                                <span class="d-md-none d-block"><?= label('subtotal', 'SUBTOTAL') ?></span>
                                                <p class="product-line-price text-break">
                                                    <?= $settings['currency'] . number_format(($row['qty'] * $price), 2) ?>
                                                </p>
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <div class="text-center py-5">
                            <ion-icon name="bag-add-outline" class="fa-6x text-body-tertiary opacity-50"></ion-icon>
                            <h5 class=""><?= label('empty_cart_message', 'Your Cart Is Empty') ?></h5>
                            <div class="text-center mt-2"><a class="btn btn-primary" href="<?= base_url('products') ?>">
                                    <?= label('return_to_shop', 'return to shop') ?></a></div>
                        </div>
                    <?php } ?>
                </div>
                <?php
                if (isset($save_for_later) && !empty($save_for_later)) { ?>
                    <div class="py-4">
                        <div class="align-items-center d-flex flex-wrap justify-content-between">
                            <h1 class="section-title  pointer"><?= label('save_for_later', 'Save For Later') ?></h1>
                        </div>
                        <div class="swiper mySwiper6 swiper-arrow swiper-wid ">
                            <div class="swiper-wrapper grab">
                                <?php
                                foreach ($save_for_later as $later_pro) {
                                    if (is_array($later_pro) && isset($later_pro['id']) && !empty($later_pro['id'])) {
                                        ?>
                                        <div class="swiper-slide background-none">
                                            <a href="<?= base_url('products/details/' . $later_pro['slug']) ?>"
                                                class="text-reset text-decoration-none">
                                                <div class="card card-h-418 product-card" data-product-id="<?= $later_pro['id'] ?>">
                                                    <div class="product-img">
                                                        <img src="<?= $later_pro['image'] ?>" class="card-img-top pic-1"
                                                            alt="<?= $later_pro['name'] ?>">
                                                    </div>
                                                    <div class="card-body">
                                                        <h4 class="card-title"><?= $later_pro['name'] ?></h4>
                                                        <p class="card-text">
                                                            <?= output_escaping(str_replace('\r\n', '&#13;&#10;', word_limit($later_pro['short_description']))) ?>
                                                        </p>
                                                        <div class="d-flex flex-column">
                                                            <h4 class="card-price">
                                                                <?php
                                                                $price = ($later_pro['special_price'] > 0 && $later_pro['special_price'] != '') ? $later_pro['special_price'] : $later_pro['price'];
                                                                echo $settings['currency'] . ' ' . $price; ?>
                                                            </h4>
                                                        </div>
                                                        <button class="btn add-in-cart-btn w-100 save_for_later"
                                                            data-id="<?= $later_pro['id'] ?>"><span class="add-in-cart">
                                                                <?= label('add_to_cart', 'Add To Cart') ?></span><span
                                                                class="add-in-cart-icon"><i
                                                                    class="fa-solid fa-cart-shopping color-white"></i></span>
                                                        </button>
                                                    </div>
                                                    <div class="product-icon-onhover">
                                                        <div class="product-icon-spacebtw">
                                                            <div class="shuffle-box">
                                                                <a class="compare text-reset text-decoration-none shuffle"
                                                                    data-tip="Compare" data-product-id="<?= $product_row['id'] ?>"
                                                                    data-product-variant-id="<?= $variant_id ?>">
                                                                    <ion-icon name="shuffle-outline"
                                                                        class="ion-icon-hover pointer shuffle ionicon-compare-outline text-dark"></ion-icon>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="product-icon-spacebtw">
                                                            <div class="add-to-fav-btn" title="like"
                                                                data-product-id="<?= $later_pro['id'] ?>">
                                                                <ion-icon
                                                                    class="ion-icon ion-icon-hover <?= ($later_pro['is_favorite'] == 1) ? 'ionicon-heart text-danger' : 'ionicon-heart-outline text-dark' ?>"
                                                                    name="<?= ($later_pro['is_favorite'] == 1) ? 'heart' : 'heart-outline' ?>"></ion-icon>
                                                            </div>
                                                        </div>
                                                        <div class="product-icon-spacebtw">
                                                            <div class="quick-search-box quickview-trigger" data-tip="Quick View"
                                                                data-product-id="<?= $later_pro['id'] ?>"
                                                                data-product-variant-id="<?= $later_pro['variants'][0]['id'] ?>"
                                                                data-izimodal-open="#quick-view">
                                                                <ion-icon name="search-outline"
                                                                    class="ion-icon-hover pointer ionicon-search-outline"
                                                                    data-bs-toggle="modal" data-bs-target="#quickview"></ion-icon>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    <?php }
                                } ?>
                            </div>
                            <div class="swiper-button-next"><ion-icon name="chevron-forward-outline"></ion-icon></div>
                            <div class="swiper-button-prev"><ion-icon name="chevron-back-outline"></ion-icon></div>
                        </div>
                    </div>
                <?php } ?>
                <div class="cart-total d-xl-none">
                    <div class="cart-titles"><?= label('cart_totals', 'Cart Totals') ?></div>
                    <table class="table cart-total-table">
                        <tbody>
                            <?php $total = !empty($cart['sub_total']) ? number_format($cart['overall_amount'] - $cart['delivery_charge'], 2) : 0 ?>
                            <tr class="order-total">
                                <th><?= label('total', 'Total') ?></th>
                                <td>
                                    <p><?= $settings['currency'] . '<span class="final_total"> ' . $total . '</span>' ?>
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="<?= base_url('cart/checkout') ?>">

                        <button
                            class="btn btn-primary w-100"><?= label('proceed_to_checkout', 'Proceed to checkout') ?></button>
                    </a>
                </div>
                <div class="py-4">
                    <div class="align-items-center d-flex flex-wrap justify-content-between">
                        <h1 class="section-title  pointer">
                            <?= label('you_may_be_interested_in', 'You May Be Interested In…') ?>
                        </h1>
                    </div>
                    <div class="swiper mySwiper6 swiper-arrow swiper-wid ">
                        <div class="swiper-wrapper grab">
                            <?php foreach ($related_products['product'] as $row) { ?>
                                <div class="swiper-slide background-none">
                                    <a href="<?= base_url('products/details/' . $row['slug']) ?>"
                                        class="text-reset text-decoration-none">
                                        <div class="card card-h-418 product-card" data-product-id="<?= $row['id'] ?>">
                                            <div class="product-img">
                                                <img src="<?= $row['image_sm'] ?>" class="card-img-top pic-1" alt="...">
                                            </div>
                                            <div class="card-body">
                                                <h4 class="card-title"><?= $row['name'] ?></h4>
                                                <p class="card-text">
                                                    <?= output_escaping(str_replace('\r\n', '&#13;&#10;', word_limit($row['category_name']))) ?>
                                                </p>
                                                <div class="d-flex flex-column">
                                                    <input id="input-3-ltr-star-md" name="input-3-ltr-star-md"
                                                        class="kv-ltr-theme-svg-star rating-loading"
                                                        value="<?= $row['rating'] ?>" dir="ltr" data-size="xs"
                                                        data-show-clear="false" data-show-caption="false" readonly>
                                                    <h4 class="card-price">
                                                        <?php if ($row['is_on_sale'] == 1) { ?>
                                                            <?php
                                                            echo $settings['currency'] . '' . $row['variants'][0]['sale_final_price']; ?>
                                                        <?php } else { ?>
                                                            <?php
                                                            $price = ($row['variants'][0]['special_price'] > 0 && $row['variants'][0]['special_price'] != '') ? $row['variants'][0]['special_price'] : $row['variants'][0]['price'];

                                                            echo $settings['currency'] . ' ' . $price; ?>
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
                                                    class="btn add-in-cart-btn w-100 add_to_cart  <?= $class_modal ?>"
                                                    data-product-id="<?= $row['id'] ?>"
                                                    data-product-variant-id="<?= $variant_id ?>"
                                                    data-product-title="<?= $row['name'] ?>"
                                                    data-product-image="<?= $row['image'] ?>"
                                                    data-product-price="<?= $variant_price; ?>" data-min="<?= $data_min; ?>"
                                                    data-step="<?= $data_step; ?>"
                                                    data-product-description="<?= $row['short_description']; ?>"
                                                    data-bs-toggle="modal" data-bs-target="<?= $modal ?>"><span
                                                        class="add-in-cart">
                                                        <?= label('add_to_cart', 'Add to Cart') ?></span><span
                                                        class="add-in-cart-icon"><i
                                                            class="fa-solid fa-cart-shopping color-white"></i></span></a>
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
                                                    <div class="add-to-fav-btn" title="like"
                                                        data-product-id="<?= $row['id'] ?>">
                                                        <ion-icon
                                                            class="ion-icon ion-icon-hover <?= ($row['is_favorite'] == 1) ? 'heart text-danger' : 'heart-outline text-dark' ?>"
                                                            name="<?= ($row['is_favorite'] == 1) ? 'heart' : 'heart-outline' ?>"></ion-icon>
                                                    </div>
                                                </div>
                                                <div class="product-icon-spacebtw">
                                                    <div class="quick-search-box quickview-trigger" data-tip="Quick View"
                                                        data-product-id="<?= $row['id'] ?>"
                                                        data-product-variant-id="<?= $row['variants'][0]['id'] ?>"
                                                        data-izimodal-open="#quick-view">
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
                </div>
            </div>
            <div class="col-xl-4 pb-4 position-sticky align-self-start top-0">
                <div class="cart-total d-xl-block d-none">
                    <div class="cart-titles"><?= label('cart_totals', 'Cart Totals') ?></div>
                    <table class="table cart-total-table">
                        <tbody>
                            <?php

                            $total = !empty($cart['sub_total']) ? number_format($cart['overall_amount'] - $cart['delivery_charge'], 2) : 0 ?>
                            <tr class="order-total">
                                <th><?= label('total', 'Total') ?></th>
                                <td>
                                    <p><?= $settings['currency'] . '<span class="final_total"> ' . $total . '</span>' ?>
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <a href="cart/checkout">
                        <button
                            class="btn btn-primary w-100"><?= label('proceed_to_checkout', 'Proceed to checkout') ?></button>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>