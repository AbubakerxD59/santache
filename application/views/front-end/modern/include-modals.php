<!-- log-in offcanvas -->
<?php $system_settings = get_settings('system_settings', true); ?>
<div class="offcanvas offcanvas-end" tabindex="-1" id="login-canvas" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title modal-title" id="offcanvasExampleLabel"><?= label('login', 'Sign in') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form action="<?= base_url('home/login') ?>" class='form-submit-event' id="login_form" method="post">
            <div id="login-mobile-form" class="mobile-form">
                <div class="mb-3">
                    <label for="Username" class="form-label">
                        <p class="form-lable"> <?= label('mobile_number', 'Mobile Number') ?> <sup
                                class="text-danger fw-bold">*</sup></p>
                    </label>
                    <input type="text" class="form-control" name="mobile" pattern="\d*" maxlength="16"
                        placeholder="<?= label('mobile_number', 'Mobile number') ?>" <?= (ALLOW_MODIFICATION == 0) ? 'value="9876543210"' : ""; ?>>
                </div>
            </div>
            <div id="login-email-form" class="email-form d-none">
                <div class="mb-3">
                    <label for="email-login" class="form-label">
                        <p class="form-lable"> <?= label('email', 'Email') ?> <sup class="text-danger fw-bold">*</sup>
                        </p>
                    </label>
                    <input type="email" class="form-control login-email" name="email"
                        placeholder="<?= label('email', 'Email') ?>" <?= (ALLOW_MODIFICATION == 0) ? 'value="demo@example.com"' : ""; ?>>
                </div>
            </div>
            <div class="mb-3">
                <label for="setpassword" class="form-label">
                    <p class="form-lable"><?= label('password', 'Password') ?> <sup class="text-danger fw-bold">*</sup>
                    </p>
                </label>
                <span class="password-insert form-control d-flex p-0 align-items-center">
                    <input type="password" class="form-control" id="fill-password" name="password"
                        placeholder="<?= label('password', 'Password') ?>" <?= (ALLOW_MODIFICATION == 0) ? 'value="12345678"' : ""; ?>>
                    <span class="eye-icons mx-0">
                        <ion-icon name="eye-outline" class="eye-btn password-show">
                        </ion-icon>
                        <ion-icon name="eye-off-outline" class="eye-btn password-hide">
                        </ion-icon>
                    </span>
                </span>
            </div>
            <div class="mb-3">
                <a href="<?= base_url('') ?>">
                    <button type="submit"
                        class="submit_btn btn Register-btn submit-btn"><?= label('login', 'Login') ?></button>
                </a>
            </div>
            <div class="d-flex justify-content-between">
                <div class="form-check">
                    <input class="form-check-input border-active" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label p-0 w-auto" for="flexCheckDefault">
                        <?= label('remember_me', 'Remember Me') ?>
                    </label>
                </div>
                <a href="<?= base_url('register#forget-password-section') ?>"
                    class="forget_password_sec"><?= label('forgot_password', 'Forget Password?') ?>?</a>
            </div>
            <div class="separator">
                <span></span>
                <?= label('OR', 'OR') ?>
                <span></span>
            </div>
            <div class="d-flex justify-content-around my-2">
                <a href="#" id="login-canvas-emailLogin-btn" class="emailLogin">
                    <div class="thirdparty-login">
                        <img src="<?= base_url('assets/front_end/modern/image/pictures/mail.png') ?> " alt="">
                        <p class="m-0"><?= label('Email', 'Email') ?></p>
                    </div>
                </a>
                <a href="#" id="login-canvas-phoneLogin-btn" class="phoneLogin d-none">
                    <div class="thirdparty-login">
                        <img src="<?= base_url('assets/front_end/modern/image/pictures/telephone.png') ?> " alt="">
                        <p class="m-0"><?= label('Phone', 'Phone') ?></p>
                    </div>
                </a>
                <?php if ((!empty($system_settings['google_login']) && $system_settings['google_login'] == 1)) { ?>
                    <?php if (!empty($system_settings['google_login']) && $system_settings['google_login'] != '' && ($system_settings['google_login'] == 1 || $system_settings['google_login'] = '1')) { ?>
                        <a href="#" id="googleLogin">
                            <div class="thirdparty-login">
                                <img src="<?= base_url('assets/front_end/modern/image/pictures/google-logo-9825.png') ?> "
                                    alt="">
                                <p class="m-0"><?= label('Google', 'Google') ?></p>
                            </div>
                        </a>
                    <?php } ?>
                <?php } ?>
            </div>
        </form>
        <div class="d-flex align-items-center justify-content-between mt-2">
            <p class="m-0"><?= label('No account Yet?', 'No account Yet?') ?></p>
            <a href="<?= base_url('register') ?>">
                <button type="button"
                    class="btn btn btn-link"><?= label('create_new_account', 'Create New Account') ?></button>
            </a>
        </div>
    </div>
</div>

<!-- Modal for quickview -->
<div class="modal fade" id="quickview" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-radius-10">
            <div class="quickview-card card border-radius-10">
                <div class="row g-0 m-0">
                    <div class="quickview-img-section col-md-6 ps-0">
                        <div class="swiper mySwiper-quickview swiper-arrow border-radius-10" id="#swatch-images">
                            <div class="swiper-wrapper swiper-wrapper-main grab"></div>
                            <div class="swiper-button-next">
                                <ion-icon name="chevron-forward-outline"></ion-icon>
                            </div>
                            <div class="swiper-button-prev">
                                <ion-icon name="chevron-back-outline"></ion-icon>
                            </div>
                        </div>
                        <a id="product-link" href="#">
                            <button
                                class="btn btn-primary view-details-btn modal_view_detail_btn"><?= label('view_details', 'View Details') ?></button>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <div class="card-body">
                            <?php
                            ?>
                            <h5 class="card-title quickview-title text-capitalize modal-product-title"></h5>
                            <p class="quickview-product-brands" id="modal-product-short-description"></p>
                            <p class="quickview-product-brands"><?= label('brand', 'Brand') ?>: <span
                                    class="text-capitalize" id="modal-product-brand"></span></p>
                            <input type="hidden" name="modal-product-type" id="modal-product-type">
                            <div class="d-flex align-items-center gap-2">
                                <input name="input-3-ltr-star-md" class="kv-ltr-theme-svg-star rating-loading"
                                    id="modal-product-rating" value="0" dir="ltr" data-size="xs" data-show-clear="false"
                                    data-show-caption="false" readonly>
                                <p class="rating-review-text">( <span id="modal-product-no-of-ratings"></span>
                                    <?= label('customer_review', 'Customer Review') ?>)</p>
                            </div>
                            <div class="quickview-pricing-section">
                                <p class="quickview-pricing" id="modal-product-price"></p>
                                <input type="hidden" class="modal-product-price">
                            </div>
                            <div id="modal-product-variant-attributes"></div>
                            <div id="modal-product-variants-div"></div>
                            <div class="d-flex flex-wrap align-items-baseline gap-2 mb-20px">
                                <div class="input-group plus-minus-input num-block">
                                    <div class="input-group-button">
                                        <button type="button" class="button hollow circle minus-btn minus quickview-btn"
                                            data-quantity="minus" data-field="quantity">
                                            <i class="fa-solid fa-minus"></i>
                                        </button>
                                    </div>
                                    <div class="product-quantity product-sm-quantity border-0 m-0">
                                        <input class="input-group-field input-field-cart-modal in-num quick-view-field"
                                            type="number" name="qty" id="modal-product-quantity">
                                    </div>
                                    <div class="input-group-button">
                                        <button type="button" class="button hollow circle plus-btn plus quickview-btn"
                                            data-quantity="plus" data-field="quantity">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden" name="whatsappNumber" id="whatsappNumber"
                                    value="<?= $settings['whatsapp_number'] ?>">
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="submit" title="Add in Cart"
                                        class="add-btn border-radius-5 btn text-nowrap "
                                        id="modal-add-to-cart-button"><?= label('add_to_cart', 'Add in Cart') ?></button>
                                    <a href="javascript:void(0);" id="whatsappButton"><button type="button"
                                            class="btn btn-success d-flex justify-content-center align-items-center"><ion-icon
                                                name="logo-whatsapp"
                                                class="me-2 fs-4"></ion-icon><?= label('order_from_whatsapp', 'Order From Whatsapp') ?></button></a>
                                </div>
                            </div>
                            <div class="posted_in">
                                <span>
                                    <span class="category-lable"><?= label('category', 'Category') ?>: </span>
                                    <a id="category-link" href="#">
                                        <span id="modal-category_name" class="text-capitalize"></span>
                                    </a>
                                </span>
                            </div>
                            <div class="media-icons">
                                <div class="d-flex align-items-center"><?= label('Share', 'Share') ?>: <span
                                        class="ms-1 quick_view_share" id="quick_view_share"></span></div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="quickview-close-btn align-items-center d-flex justify-content-center"
                        data-bs-dismiss="modal" aria-label="Close"><ion-icon name="close-outline"></ion-icon></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- cart-offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="cartmodal" aria-labelledby="offcanvasRightLabel">
    <input type="hidden" name="is_loggedin" id="is_loggedin" value="<?= (isset($user->id)) ? 1 : 0 ?>">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title modal-title" id="offcanvasRightLabel"><?= label('shopping_cart', 'Shopping cart') ?>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <?php if (isset($user->id) == 0) { ?>
    <div class="offcanvas-body p-0" id="cart-item-sidebar">

        </div>
    <?php } ?>
    <?php if (isset($user->id)) {
        $cart_items = $this->cart_model->get_user_cart($user->id);
        $subtotal = 0; ?>
        <?php if (count($cart_items) != 0) { ?>
            <div class="offcanvas-body p-0" id="cart-item-sidebar">
                <?php foreach ($cart_items as $items) {
                    $price = $items['special_price'] != '' && $items['special_price'] > 0 && $items['special_price'] != null ? $items['special_price'] : $items['price'];

                    $line_price = $items['qty'] * $price;
                    $subtotal += $line_price; ?>
                    <div class="cart-modal-card card max-width-540px">
                        <div class="row">
                            <div class="col-4">
                                <div class="cart-img-box">
                                    <img class="img-fluid rounded-start pic-1 lazy"
                                        src="<?= !empty($items['image']) ? base_url($items['image']) : base_url(NO_IMAGE) ?>"
                                        alt="<?= html_escape($items['name']) ?>" title="<?= html_escape($items['name']) ?>"
                                        onerror="this.onerror=null;this.src='<?= base_url(NO_IMAGE) ?>';" />

                                </div>
                            </div>
                            <div class="col-8">
                                <div class="card-body">
                                    <?php $check_current_stock_status = validate_stock([$items['product_variant_id']], [$items['qty']]); ?>
                                    <h5 class="card-title"><?= html_escape($items['name']) ?>
                                        <?= (isset($check_current_stock_status['error']) && $check_current_stock_status['error'] == TRUE) ? "<span class='badge badge-danger'>  Out of Stock </span>" : "" ?>
                                    </h5>
                                    <p class="card-text">
                                        <?= word_limit(output_escaping(str_replace('\r\n', '&#13;&#10;', $items['short_description']))) ?>
                                    </p>
                                    <p
                                        class="card-text text-capitalize <?= isset($items['product_variants'][0]['variant_values']) ? 'd-block' : "d-none" ?>">
                                        <?= isset($items['product_variants'][0]['variant_values']) ? $items['product_variants'][0]['variant_values'] : "" ?>
                                    </p>
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
                                            <input type="number" name="qty"
                                                class="input-group-field input-field-cart-modal form-input in-num"
                                                value="<?= $items['qty'] ?>" data-id="<?= $items['product_variant_id'] ?>"
                                                data-price="<?= $price ?>" min="<?= $items['minimum_order_quantity'] ?>"
                                                max="<?= $items['total_allowed_quantity'] ?>"
                                                step="<?= $items['quantity_step_size'] ?>">
                                        </div>
                                        <div class="input-group-button">
                                            <button type="button" class="button hollow circle plus-btn plus" data-quantity="plus"
                                                data-field="quantity" data-max="<?= $items['total_allowed_quantity'] ?>"
                                                data-step="<?= (isset($product['product'][0]['minimum_order_quantity']) && !empty($product['product'][0]['quantity_step_size'])) ? $product['product'][0]['quantity_step_size'] : 1 ?>">
                                                <i class="fa-solid fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <p class="product-line-price cart-modal-pricing mt-1 mb-1"><span
                                            class=""><?= $settings['currency'] . ' ' . number_format($line_price, 2) ?></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="product-sm-removal align-self-center">
                            <button class="remove-product btn" data-id="<?= $items['product_variant_id'] ?>">
                                <ion-icon name="close-outline"></ion-icon>
                            </button>
                        </div>
                    </div>
                    <?php
                } ?>
            </div>

        <?php } else { ?>
            <div class="offcanvas-body p-0" id="cart-item-sidebar">
                <div class="text-center py-5">
                    <ion-icon name="bag-add-outline" class="fa-6x text-body-tertiary opacity-50"></ion-icon>
                    <h5 class=""><?= label('empty_cart_message', 'Your Cart Is Empty') ?></h5>
                    <div class="text-center mt-2"><a class="btn btn-primary" href="<?= base_url('products') ?>">
                            <?= label('return_to_shop', 'return to shop') ?></a></div>
                </div>
            </div>
        <?php }
    } ?>
    <div class="offcanvas-footer">
        <?php if (isset($cart_items) && count($cart_items) != 0) { ?>
            <div class="subtotal-section">
                <h5>Subtotal:</h5>
                <h5 class="subtotal-amount" id="subtotal-amount">
                    <?= $settings['currency'] . ' ' . number_format($subtotal, 2) ?>
                </h5>
            </div>
        <?php } ?>
        <?php if (isset($user->id) == 0) {
            $subtotal = 0;
            ?>
            <div class="subtotal-section">
                <h5>Subtotal:</h5>
                <h5 class="subtotal-amount" id="subtotal-amount">
                    <?= $settings['currency'] . ' ' . number_format($subtotal, 2) ?>
                </h5>
            </div>
        <?php } ?>
        <div class="cart-modal-checkout-section">
            <?php if (isset($user->id) == 0) {
                ?>
                <a data-bs-toggle="offcanvas" data-bs-target="#login-canvas" aria-controls="offcanvasRight">
                    <button class="btn view-cart-btn w-100 mb-2"><?= label('view_cart', 'View Cart') ?></button>
                </a>
                <a data-bs-toggle="offcanvas" data-bs-target="#login-canvas" aria-controls="offcanvasRight">
                    <button class="btn checkout-btn w-100"><?= label('checkout', 'Checkout') ?></button>
                </a>
            <?php } else { ?>
                <a href="<?= base_url('cart') ?>">
                    <button class="btn view-cart-btn w-100 mb-2"><?= label('view_cart', 'View Cart') ?></button>
                </a>
                <a href="<?= base_url('cart/checkout') ?>">
                    <button class="btn checkout-btn w-100"><?= label('checkout', 'Checkout') ?></button>
                </a>
            <?php } ?>
        </div>
    </div>
</div>