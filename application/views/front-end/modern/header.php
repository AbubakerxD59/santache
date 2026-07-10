<?php $current_url = current_url(); ?>
<?php
$this->load->model('category_model');
$categories = $this->category_model->get_categories(null, 8);
$language = get_languages();
$language_index = 0;
$cookie_lang = $this->input->cookie('language', true);
if (!empty($cookie_lang)) {
    $language_index = array_search($cookie_lang, array_column($language, "language"));
}
$web_settings = get_settings('web_settings', true);
$vap_id_Key = get_settings('vap_id_Key');
$auth_settings = get_settings('authentication_settings', true);

$currency = get_settings('currency');

$is_web = get_settings('web_doctor_brown');
$doctor_brown = null;
$web_doctor_brown = null;
if (!empty($is_web)) {
    $web_doctor_brown = json_decode($is_web, true);
    $web_doctor_brown = isset($web_doctor_brown['code_bravo']) ? $web_doctor_brown['code_bravo'] : '';
}
$is_app = get_settings('doctor_brown');
if (!empty($is_app)) {
    // if (true) {
    $doctor_brown = json_decode($is_app, true);
    $doctor_brown = isset($doctor_brown['code_bravo']) ? $doctor_brown['code_bravo'] : '';
    $app_name = json_decode(get_settings('system_settings'));
    if ("object" == gettype($app_name)) {
        $app_name = isset($app_name->app_name) ? $app_name->app_name : '';
    }

    if ("array" == gettype($app_name)) {
        $app_name = isset($app_name['app_name']) ? $app_name['app_name'] : '';
    }
}
?>
<input type="hidden" id="baseUrl" value="<?= base_url() ?>">
<input type="hidden" id="currency" value="<?= $currency ?>">
<input type="hidden" id="vap_id_Key" value="<?= $vap_id_Key ?>">
<input type="hidden" id="supported_locals" class="form-control" value="<?= $settings['supported_locals'] ?>">

<input type="hidden" id="auth_settings" name="auth_settings"
    value='<?= isset($auth_settings['authentication_method']) ? $auth_settings['authentication_method'] : ''; ?>'>
<input type="hidden" name="android_app_store_link" id="android_app_store_link"
    value="<?= (isset($settings['android_app_store_link']) && !empty($settings['android_app_store_link'])) ? $settings['android_app_store_link'] : '' ?>">
<input type="hidden" name="ios_app_store_link" id="ios_app_store_link"
    value="<?= (isset($settings['ios_app_store_link']) && !empty($settings['ios_app_store_link'])) ? $settings['ios_app_store_link'] : '' ?>">
<input type="hidden" name="scheme" id="scheme"
    value="<?= (isset($settings['scheme']) && !empty($settings['scheme'])) ? $settings['scheme'] : '' ?>">
<input type="hidden" name="host" id="host"
    value="<?= (isset($settings['host']) && !empty($settings['host'])) ? $settings['host'] : '' ?>">

<input type="hidden" name="host" id="doctor_brown" value="<?= (!empty($doctor_brown)) ? $doctor_brown : '' ?> ">
<input type="hidden" name="host" id="app_name" value="<?= (!empty($app_name)) ? $app_name : '' ?> ">
<input type="hidden" name="share_slug" id="share_slug" value="true">

<div class="bg-white top-nav">
    <div class="container d-none d-xl-block d-xxl-block">
        <nav class="navbar navbar-expand-xl navbar-light bg-white ">
            <?php $logo = get_settings('web_logo'); ?>
            <a class="company-logo" href="<?= base_url() ?>">
                <img src="<?= base_url($logo) ?>" data-src="<?= base_url($logo) ?>"
                    class="me-3v pointer brand-logo-link">
            </a>
            <form class="searchcontainer mx-5 searchbar">
                <select class=" me-2 search_product opacity-0" type="text" aria-label="Search">search</select>
            </form>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse fw-semibold " id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 justify-content-around width100 white-space-nowrap">
                    <li class="nav-item">
                        <a class="nav-link font-color" title="Support" aria-current="page"
                            href="<?= base_url('home/contact-us') ?>"><?= label('support', 'Support') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-color" title="About Us"
                            href="<?= base_url('home/about-us') ?>"><?= label('about_us', 'About Us') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-color" title="FAQs"
                            href="<?= base_url('home/faq') ?>"><?= label('faq', 'FAQs') ?></a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
<header class="d-none d-xl-block d-xxl-block search-nav">
    <div class="nav-outer bg-nav">

        <div class="container d-none d-xl-flex d-xxl-flex justify-content-between align-items-center">
            <ul class="navbar-nav flexrow white-space-nowrap">
                <li class="nav-category pointer <?= ($current_url == base_url()) ? ' active-nav' : '' ?>">
                    <a href="<?= base_url() ?>">
                        <span title="Home"><?= label('home', 'Home') ?></span>
                    </a>
                </li>
                <li class="nav-category pointer <?= ($current_url == base_url('home/categories')) ? ' active-nav' : '' ?>"
                    title="All Categories">
                    <a href="<?= base_url('home/categories') ?>">
                        <span><?= label('category', 'All Categories') ?></span>
                    </a>
                </li>
                <li class="nav-category pointer <?= ($current_url == base_url('products')) ? ' active-nav' : '' ?>">
                    <a href="<?= base_url('products') ?>">
                        <span title="Products"><?= label("products", "Products") ?></span>
                    </a>
                </li>
                <li
                    class="nav-category pointer <?= ($current_url == base_url('products/offers_and_flash_sale')) ? ' active-nav' : '' ?>">
                    <a href="<?= base_url('products/offers_and_flash_sale') ?>">
                        <span title="Top Offers"><?= label('top_offer', 'Top Offers') ?></span>
                    </a>
                </li>
                <li
                    class="nav-category pointer <?= ($current_url == base_url('home/contact-us')) ? ' active-nav' : '' ?>">
                    <a href="<?= base_url('home/contact-us') ?>">
                        <span title="Contact us"><?= label('contact_us', 'Contact us') ?></span>
                    </a>
                </li>
                <li
                    class="nav-category pointer <?= ($current_url == base_url('home/about-us')) ? ' active-nav' : '' ?>">
                    <a href="<?= base_url('home/about-us') ?>">
                        <span title="About us"><?= label('about_us', 'About us') ?></span>
                    </a>
                </li>
            </ul>
            <div class="d-flex">
                <div class="btn-group">
                    <a class="text-decoration-none dropdown-toggle py-0 align-self-center" href="#" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php if ($cookie_lang) { ?>
                            <span class="font-weight-bold"><?= ucfirst($language[$language_index]['code']) ?></span>
                        <?php } else { ?>
                            <span class="font-weight-bold">En</span>
                        <?php } ?>
                    </a>
                    <ul class="dropdown-menu pointer">
                        <?php foreach ($language as $row) { ?>
                            <li class="dropdown-item"><a
                                    href="<?= base_url('home/lang/' . strtolower($row['language'])) ?>"><?= strtoupper($row['code']) . ' - ' . ucfirst($row['language']) ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <ul class="d-flex align-items-center margin0 list-style">
                    <a href="<?= base_url('compare') ?>">
                        <li class="shopingicon mx-2 pointer" title="compare">
                            <i class="ionicon-compare-outline bghover transition-d-025"></i>
                        </li>
                    </a>
                    <?php
                    $page = $this->uri->segment(1) == 'cart' ? 'cart' : '';
                    $checkout_page = $this->uri->segment(2) == 'checkout' ? 'checkout' : ''; ?>
                    <?php if ($page == 'cart') { ?>
                        <li class="shopingicon mx-2 pointer" title="Cart">
                            <a class="d-block" href="<?= base_url('cart') ?>">
                                <i class="ionicon-bag-handle-outline bghover transition-d-025" 0></i>
                                <span class="badge badge-danger badge-sm count-box cart-count"
                                    id="cart-count"><?= (count($this->cart_model->get_user_cart($this->session->userdata('user_id'))) != 0 ? count($this->cart_model->get_user_cart($this->session->userdata('user_id'))) : ''); ?></span>
                            </a>
                        </li>
                    <?php } elseif ($checkout_page == 'checkout') { ?>
                        <li class="shopingicon mx-2 pointer" title="Cart">
                            <a class="d-block" href="<?= base_url('cart/checkout') ?>">
                                <i class="ionicon-bag-handle-outline bghover transition-d-025" 0></i>
                                <span class="badge badge-danger badge-sm count-box cart-count"
                                    id="cart-count"><?= (count($this->cart_model->get_user_cart($this->session->userdata('user_id'))) != 0 ? count($this->cart_model->get_user_cart($this->session->userdata('user_id'))) : ''); ?></span>
                            </a>
                        </li>
                    <?php } else { ?>
                        <li class="shopingicon mx-2 pointer pt-2" title="Cart" data-bs-toggle="offcanvas"
                            data-bs-target="#cartmodal" aria-controls="offcanvasRight">
                            <a href="javascript:void(0);" class="d-block">
                                <i class="ionicon-bag-handle-outline bghover transition-d-025" ></i>
                                <span class="badge badge-danger badge-sm count-box cart-count"
                                    id="cart-count"><?= (count($this->cart_model->get_user_cart($this->session->userdata('user_id'))) != 0 ? count($this->cart_model->get_user_cart($this->session->userdata('user_id'))) : ''); ?></span>
                            </a>
                        </li>
                    <?php }
                    $favorite_count = count(get_favorites($this->session->userdata('user_id')));
                    ?>
                    <a href="<?= base_url('my-account/favorites') ?>">
                        <li class="shopingicon m-2 pointer " title="Like Product">
                            <span
                                class="badge badge-danger badge-sm count-box favorite_count"><?= $favorite_count ?></span>
                            <i class="ionicon-heart bghover transition-d-025 small-icon"></i>
                        </li>
                    </a>
                    <?php if ($this->ion_auth->logged_in()) { ?>
                        <div class="dropdown profile">
                            <a href="<?= base_url('my-account') ?>" class=" dropdown-toggle" id="hoverDropdown"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <li class="shopingicon mx-2 pointer" title="Profile">
                                    <i class="ionicon-person bghover transition-d-025"></i>
                                </li>
                            </a>
                            <div class="dropdown-menu deshboard-onhover" aria-labelledby="hoverDropdown">
                                <ul class="list-unstyled ">
                                    <a href="<?= base_url('my-account') ?>">
                                        <li><?= label('dashboard', 'Dashboard') ?></li>
                                    </a>
                                    <a href="<?= base_url('my-account/orders') ?>">
                                        <li><?= label('orders', 'Orders') ?></li>
                                    </a>
                                    <a href="<?= base_url('my-account/notifications') ?>">
                                        <li><?= label('notification', 'Notifications') ?></li>
                                    </a>
                                    <a href="<?= base_url('my-account/manage-address') ?>">
                                        <li><?= label('address', 'Addresses') ?></li>
                                    </a>
                                    <a href="<?= base_url('my-account/profile') ?>">
                                        <li><?= label('account_detail', 'Account Detail') ?></li>
                                    </a>
                                    <a href="<?= base_url('my-account/favorites') ?>">
                                        <li><?= label('wishlist', 'Wishlist') ?></li>
                                    </a>
                                    <a href="<?= base_url('my-account/tickets') ?>">
                                        <li> <?= label('customer_support', 'Customer Support') ?></li>
                                    </a>
                                    <a href="<?= base_url('my-account/wallet') ?>">
                                        <li><?= label('wallet', 'wallet') ?></li>
                                    </a>
                                    <a href="<?= base_url('my-account/refer_and_earn') ?>">
                                        <li><?= label('refer_and_earn', 'Refer and earn') ?></li>
                                    </a>
                                    <a href="#" class="logout-link">
                                        <li> <?= label('logout', 'Logout') ?></li>
                                    </a>
                                </ul>
                            </div>
                        </div>
                    <?php } else { ?>
                        <a data-bs-toggle="offcanvas" data-bs-target="#login-canvas" aria-controls="offcanvasRight">
                            <li class="shopingicon mx-2 pointer" title="Sign up">
                                <i class="ionicon-person bghover transition-d-025"></i>
                            </li>
                        </a>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>

    <form class="d-xl-none d-xxl-none ">
        <input class="form-control searchbar" type="text" placeholder="Search for Product" aria-label="Search" required>
        <button class="searchicon" type="submit">
            <i class="ionicon-search-outline"></i>
        </button>
    </form>
</header>
<?php if (isset($web_settings['promo_head_description']) && !empty($web_settings['promo_head_description'])) { ?>
    <div class="promo-nav">
        <p class="promo-nav-text"><?= $web_settings['promo_head_description'] ?></p>
    </div>
<?php } ?>

<header class="search-nav content-wrapper">
    <nav class="navbar navbar-md d-xl-none d-xxl-none">
        <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample"
            aria-controls="offcanvasExample">
            <i class="fa fa-navicon text-dark-emphasis"></i>
        </button>

        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample"
            aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title d-flex align-items-center" id="offcanvasExampleLabel">
                    <ion-icon name="person-outline" class="menu-icon"></ion-icon>
                    <?php if ($this->ion_auth->logged_in()) { ?>
                        <a href="<?= base_url('my_account') ?>">
                            <?= label('Hello', 'Hello') ?>     <?= $user->username ?>
                        </a>
                    <?php } else { ?>
                        <a href="<?= base_url('register') ?>">
                            <?= label('login', 'Login') ?>/<?= label('register', 'Register') ?>
                        </a>
                    <?php } ?>
                </h5>
                <button type="button" class="btn text-reset" data-bs-dismiss="offcanvas" aria-label="Close">
                    <ion-icon name="chevron-back-outline" role="img" class="md hydrated"></ion-icon>
                </button>
            </div>
            <div class="offcanvas-body py-0">
                <ul class="list-unstyled">
                    <li>
                        <a href="<?= base_url('home') ?>">
                            <ion-icon name="home-outline" class="menu-icon"></ion-icon><span class="text-body-emphasis">
                                <?= label('home', 'Home') ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('home/categories/') ?>">
                            <ion-icon name="cube-outline" class="menu-icon"></ion-icon><span class="text-body-emphasis">
                                <?= label('category', 'Categories') ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('products') ?>">
                            <ion-icon name="storefront-outline" class="menu-icon"></ion-icon><span
                                class="text-body-emphasis"><?= label('products', 'Products') ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('products/offers_and_flash_sale') ?>">
                            <ion-icon name="grid-outline" class="menu-icon"></ion-icon><span
                                class="text-body-emphasis"><?= label('top_offers', 'Top Offers') ?></span>
                        </a>
                    </li>
                    <?php if ($this->ion_auth->logged_in()) { ?>
                        <li>
                            <a href="<?= base_url('my-account/orders') ?>">
                                <ion-icon name="timer-outline" class="menu-icon"></ion-icon><span
                                    class="text-body-emphasis"><?= label('my_orders', 'My Orders') ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('my-account/favorites') ?>">
                                <ion-icon name="heart-outline" class="menu-icon"></ion-icon><span
                                    class="text-body-emphasis"><?= label('favorite', 'Favarites') ?></span>
                            </a>
                        </li>
                    <?php } ?>
                    <li>
                        <a href="<?= base_url('compare') ?>">
                            <ion-icon name="shuffle-outline" class="menu-icon"></ion-icon><span
                                class="text-body-emphasis"><?= label('compare', 'Compare') ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('home/about-us') ?>">
                            <ion-icon name="information-circle-outline" class="menu-icon"></ion-icon><span
                                class="text-body-emphasis"><?= label('about_us', 'About Us') ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('home/contact-us') ?>">
                            <ion-icon name="mail-outline" class="menu-icon"></ion-icon><span
                                class="text-body-emphasis"><?= label('contact_us', 'Contact Us') ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('home/faq') ?>">
                            <ion-icon name="help-circle-outline" class="menu-icon"></ion-icon><span
                                class="text-body-emphasis"><?= label('faq', 'FAQs') ?></span>
                        </a>
                    </li>
                    <li class="d-flex align-items-center language-box">
                        <ion-icon name="language-outline" class="menu-icon"></ion-icon><span class="text-body-emphasis">
                            <div class="dropdown">
                                <a class="text-decoration-none dropdown-toggle py-0 align-self-center" href="#"
                                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php if ($cookie_lang) { ?>
                                        <span
                                            class="text-primary font-weight-bold"><?= ucfirst($language[$language_index]['code']) ?></span>
                                    <?php } else { ?>
                                        <span class="text-primary font-weight-bold">En</span>
                                    <?php } ?>
                                </a>
                                <ul class="dropdown-menu pointer">
                                    <?php foreach ($language as $row) { ?>
                                        <li class="dropdown-item"><a
                                                href="<?= base_url('home/lang/' . strtolower($row['language'])) ?>"><?= strtoupper($row['code']) . ' - ' . ucfirst($row['language']) ?></a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
        <a class="company-logo" href="<?= base_url() ?>">
            <img src="<?= base_url($logo) ?>" data-src="<?= base_url($logo) ?>" class="me-3v pointer brand-logo-link">
        </a>
        <!-- <ul class=" d-flex list-unstyled align-items-center fw-bold m-0 me-1">
            <li class="me-2 pointer color-primary position-relative" data-bs-toggle="offcanvas" data-bs-target="#cartmodal" aria-controls="offcanvasRight">
                <i class="ionicon-cart-outline"></i>
                <span class="badge badge-danger badge-sm count-box cart-count" id="cart-count"><?= (count($this->cart_model->get_user_cart($this->session->userdata('user_id'))) != 0 ? count($this->cart_model->get_user_cart($this->session->userdata('user_id'))) : ''); ?></span>
            </li>
            <li class="color-primary">
                <a href="<?= base_url('register') ?>">
                    <?php if ($this->ion_auth->logged_in()) { ?>
                        <a href="<?= base_url('my-account') ?>">
                            <i class="ionicon-person-outline"></i>
                        </a>
                    <?php } else { ?>
                        <a data-bs-toggle="offcanvas" data-bs-target="#login-canvas" aria-controls="offcanvasRight">
                            <i class="ionicon-person-outline"></i>
                        </a>
                    <?php } ?>
                </a>
            </li>
        </ul> -->
    </nav>
</header>
<div class="container-fluid p-3 d-flex d-xl-none d-xxl-none align-items-center">
    <form class="searchcontainer w-100">
        <select class="form-control me-2 search_product" type="text" aria-label="Search">search</select>
    </form>
    <ul class="list-unstyled mb-0">
        <li class="shopingicon mx-2 pointer" title="Cart" data-bs-toggle="offcanvas" data-bs-target="#cartmodal"
            aria-controls="offcanvasRight">
            <a href="javascript:void(0);" class="d-block">
                <i class="ionicon-bag-handle-outline bghover transition-d-025" 0></i>
                <span class="badge badge-danger badge-sm count-box cart-count"
                    id="cart-count"><?= (count($this->cart_model->get_user_cart($this->session->userdata('user_id'))) != 0 ? count($this->cart_model->get_user_cart($this->session->userdata('user_id'))) : ''); ?></span>
            </a>
        </li>
    </ul>
</div>