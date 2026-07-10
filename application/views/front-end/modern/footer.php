<?php $web_settings = get_settings('web_settings', true); ?>
<?php $system_settings = get_settings('system_settings', true);
?>
<footer>
    <div class="container-fluid p-0 border-top bg-white">
        <div class="container pt-5">
            <div class="pb-5 border-bottom">
                <ul class="nav justify-content-between">
                    <?php if (isset($web_settings['address']) && !empty($web_settings['address'])) { ?>
                        <li class="nav-item d-flex ">
                            <div class="pe-2">
                                <i class="fa fa-2x fa-location-dot" aria-hidden="true"></i>
                            </div>
                            <div class="default-cursor">
                                <h5 class="fw-bold">
                                    <?= !empty($this->lang->line('find_us')) ? $this->lang->line('find_us') : 'Find Us' ?>
                                </h5>
                                <p class="text-capitalize">
                                    <?= output_escaping(str_replace('\r\n', '</br>', $web_settings['address'])) ?></p>
                            </div>
                        </li>
                    <?php } ?>
                    <?php if (isset($web_settings['support_number']) && !empty($web_settings['support_number'])) { ?>
                        <a href="tel:<?= $web_settings['support_number'] ?>" class="text-reset text-decoration-none">
                            <li class="nav-item d-flex ">
                                <div class="pe-2">
                                    <i class="fa-solid fa-phone-volume fa-2x"></i>
                                </div>
                                <div class="default-cursor">
                                    <h5 class="fw-bold">
                                        <?= !empty($this->lang->line('call_us')) ? $this->lang->line('call_us') : 'Call us' ?>
                                    </h5>
                                    <p class="text-capitalize"><?= $web_settings['support_number'] ?></p>
                                </div>
                            </li>
                        </a>
                    <?php } ?>
                    <?php if (isset($web_settings['support_email']) && !empty($web_settings['support_email'])) { ?>
                        <a href="mailto:<?= $web_settings['support_email'] ?>" class="text-reset text-decoration-none">
                            <li class="nav-item d-flex ">
                                <div class="pe-2">
                                    <i class="fa-regular fa-envelope-open fa-2x"></i>
                                </div>
                                <div class="default-cursor">
                                    <h5 class="fw-bold">
                                        <?= !empty($this->lang->line('mail_us')) ? $this->lang->line('mail_us') : 'Mail us' ?>
                                    </h5>
                                    <p><?= $web_settings['support_email'] ?></p>
                                </div>
                            </li>
                        </a>
                    <?php } ?>
                </ul>
            </div>
            <div class="row overflow-hidden pt-5">
                <div class="col-lg-5 col-md-12 footer-icons-section">
                    <?php $logo = get_settings('web_logo'); ?>
                    <a class="footer-logo pointer" href="<?= base_url() ?>">
                        <img src="<?= base_url($logo) ?>" data-src="<?= base_url($logo) ?>" class="">
                    </a>
                    <h5 class="fw-bold mx-2 my-3"><?= label('subscribe', 'Subscribe Us') ?></h5>

                    <?php if (isset($web_settings['twitter_link']) && !empty($web_settings['twitter_link'])) { ?>
                        <a href="<?= $web_settings['twitter_link'] ?>" class="style-none" target="_blank">
                            <ion-icon name="logo-twitter" class="social-media-icon pointer"></ion-icon>
                        </a>
                    <?php } ?>
                    <?php if (isset($web_settings['instagram_link']) && !empty($web_settings['instagram_link'])) { ?>
                        <a href="<?= $web_settings['instagram_link'] ?>" class="style-none" target="_blank">
                            <ion-icon name="logo-instagram" class="social-media-icon pointer"></ion-icon>
                        </a>
                    <?php } ?>
                    <?php if (isset($web_settings['youtube_link']) && !empty($web_settings['youtube_link'])) { ?>
                        <a href="<?= $web_settings['youtube_link'] ?>" class="style-none" target="_blank"><ion-icon
                                name="logo-youtube" class="social-media-icon pointer"></ion-icon></a>
                    <?php } ?>
                    <?php if (isset($web_settings['whatsapp_link']) && !empty($web_settings['whatsapp_link'])) { ?>
                        <a href="<?= $web_settings['whatsapp_link'] ?>" class="style-none" target="_blank">
                            <ion-icon name="logo-whatsapp" class="social-media-icon pointer"></ion-icon>
                        </a>
                    <?php } ?>
                    <?php if (isset($web_settings['linkedin_link']) && !empty($web_settings['linkedin_link'])) { ?>
                        <a href="<?= $web_settings['linkedin_link'] ?>" class="style-none" target="_blank">
                            <ion-icon name="logo-linkedin" class="social-media-icon pointer"></ion-icon>
                        </a>
                    <?php } ?>
                    <?php if (isset($web_settings['tiktok_link']) && !empty($web_settings['tiktok_link'])) { ?>
                        <a href="<?= $web_settings['tiktok_link'] ?>" class="style-none" target="_blank">
                            <ion-icon name="logo-tiktok" class="social-media-icon pointer"></ion-icon>
                        </a>
                    <?php } ?>
                </div>
                <div class="col-lg-7 col-md-12 footer-text-section">
                    <div class="row justify-content-around ps-md-4">
                        <div class="col-4 pe-1 default-cursor">
                            <h5><?= label('company', 'Company') ?></h5>
                            <a href="<?= base_url('home/about-us') ?>" class="text-reset text-decoration-none">
                                <p class="m-0">
                                    <?= !empty($this->lang->line('about_us')) ? $this->lang->line('about_us') : 'About Us' ?>
                                </p>
                            </a>
                            <a href="<?= base_url('home/system-contact-us') ?>" class="text-reset text-decoration-none">
                                <p class="m-0">
                                    <?= !empty($this->lang->line('contact_us')) ? $this->lang->line('contact_us') : 'Contact Us' ?>
                                </p>
                            </a>
                            <a href="<?= base_url('products') ?>" class="text-reset text-decoration-none">
                                <p class="m-0">
                                    <?= !empty($this->lang->line('products')) ? $this->lang->line('products') : 'Products' ?>
                                </p>
                            </a>
                            <a href="<?= base_url('home/categories') ?>" class="text-reset text-decoration-none">
                                <p class="m-0">
                                    <?= !empty($this->lang->line('categories')) ? $this->lang->line('categories') : 'Categories' ?>
                                </p>
                            </a>
                        </div>
                        <div class="col-4 pe-1 default-cursor">
                            <h5><?= label('legal', 'Legal') ?></h5>
                            <a href="<?= base_url('home/privacy-policy') ?>" class="text-reset text-decoration-none">
                                <p class="m-0">
                                    <?= !empty($this->lang->line('privacy_policy')) ? $this->lang->line('privacy_policy') : 'Privacy Policy' ?>
                                </p>
                            </a>
                            <a href="<?= base_url('home/terms-and-conditions') ?>"
                                class="text-reset text-decoration-none">
                                <p class="m-0">
                                    <?= !empty($this->lang->line('terms_and_condition')) ? $this->lang->line('terms_and_condition') : 'Terms & Conditions' ?>
                                </p>
                            </a>
                            <a href="<?= base_url('home/return-policy') ?>" class="text-reset text-decoration-none">
                                <p class="m-0">
                                    <?= !empty($this->lang->line('return_policy')) ? $this->lang->line('return_policy') : 'Return Policy' ?>
                                </p>
                            </a>
                            <a href="<?= base_url('home/shipping-policy') ?>" class="text-reset text-decoration-none">
                                <p class="m-0">
                                    <?= !empty($this->lang->line('shipping_policy')) ? $this->lang->line('shipping_policy') : 'Shipping Policy' ?>
                                </p>
                            </a>
                        </div>
                        <div class="col-4 default-cursor">
                            <h5><?= label('resources', 'Resources') ?></h5>
                            <a href="<?= base_url('home/contact-us') ?>">
                                <p class="m-0"><?= label('support', 'Support') ?></p>
                            </a>
                                    <?php if (isset($web_settings['app_download_section']) && $web_settings['app_download_section'] == 1) { ?>
                            <a href="<?= $web_settings['app_download_section_playstore_url'] ?>" target="_blank">
                                <p class="m-0"><?= label('android_app', 'Android App') ?></p>
                            </a>
                            <a href="<?= $web_settings['app_download_section_appstore_url'] ?>" target="_blank">
                                <p class="m-0"><?= label('ios_app', 'Ios App') ?></p>
                            </a>
                                    <?php } ?>
                            <a href="<?= base_url('home/faq') ?>" class="text-reset text-decoration-none">
                                <p class="m-0">
                                    <?= !empty($this->lang->line('faqs')) ? $this->lang->line('faqs') : 'FAQs' ?>
                                </p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="copyright-section default-cursor">
            <?php if (!empty($web_settings['copyright_details'])): ?>
                <a href="<?= base_url() ?>">
                    <span><?= output_escaping(str_replace('\r\n', '&#13;&#10;', $web_settings['copyright_details'])) ?></span>
                </a><?php else: ?>
                <span>Copyright &copy; <?= date('Y') - 1 ?> - <?= date('Y') ?>, All Rights Reserved <a target="_blank"
                        href="<?= base_url() ?>"><?= base_url() ?></a></span>
            <?php endif; ?>
        </div>

    </div>
</footer>
<?php if (ALLOW_MODIFICATION == 0) { ?>
    <div class="buy-now-btn">
        <a href="https://codecanyon.net/item/eshop-multipurpose-ecommercestore-website/31071809" target="_blank"
            class="btn btn-danger btn-sm rounded-pill"> <i class="fa fa-shopping-cart"></i>&nbsp;
            <?= !empty($this->lang->line('buy_now')) ? $this->lang->line('buy_now') : 'Buy Now' ?></a>
    </div>
<?php } ?>

<?php if (ALLOW_MODIFICATION == 0) { ?>

    <!-- color switcher -->
    <div id="colors-switcher">
        <div>
            <h6>Pick Your Theme</h6>
            <ul class="px-2 text-center">
                <li class="list-item-inline mb-3">
                    <a class="text-decoration-none text-dark" href="<?= base_url("themes/switch/modern") ?>">
                        <p class="m-0">Modern Theme</p>
                        <img src="<?= base_url("assets/front_end/modern/preview-image/modern.jpg") ?>" alt="Modern image"
                            class="w-75">

                    </a>
                </li>
                <li class="list-item-inline mb-3">
                    <a class="text-decoration-none text-dark" href="<?= base_url("themes/switch/classic") ?>">
                        <p class="m-0">Classic Theme</p>
                        <img src="<?= base_url("assets/front_end/modern/image/classic-theme.png.jpg") ?>"
                            alt="classic image" class="w-75">
                    </a>
                </li>
            </ul>
        </div>

        <div>
            <h6><?= !empty($this->lang->line('pick_your_favorite_color')) ? $this->lang->line('pick_your_favorite_color') : 'Pick Your Favorite Color' ?>
            </h6>
            <ul class="color-style text-center mb-2">
                <li class="list-item-inline">
                    <a href="#" class="blue"></a>
                </li>
                <li class="list-item-inline">
                    <a href="#" class="cyan-dark"></a>
                </li>
                <li class="list-item-inline">
                    <a href="#" class="dark-blue"></a>
                </li>
                <li class="list-item-inline">
                    <a href="#" class="dark-purple"></a>
                </li>
                <li class="list-item-inline">
                    <a href="#" class="default"></a>
                </li>
                <li class="list-item-inline">
                    <a href="#" class="green"></a>
                </li>
                <li class="list-item-inline">
                    <a href="#" class="indigo"></a>
                </li>
                <li class="list-item-inline">
                    <a href="#" class="orange"></a>
                </li>
                <li class="list-item-inline">
                    <a href="#" class="peach"></a>
                </li>
                <li class="list-item-inline">
                    <a href="#" class="pink"></a>
                </li>
                <li class="list-item-inline">
                    <a href="#" class="purple"></a>
                </li>
                <li class="list-item-inline">
                    <a href="#" class="red"></a>
                </li>
            </ul>
            <div class="color-bottom">
                <a href="#" aria-label="color-switcher" class="settings bg-white d-block"><i
                        class="fa fa-cog fa-lg fa-spin setting-icon"></i></a>
            </div>
        </div>
    </div> <!-- end color switcher -->
<?php } ?>
<div class="fixed-icon d-flex flex-column align-items-end">
    <?php if ($this->ion_auth->logged_in()) { ?>
        <?php if (isset($system_settings['whatsapp_number']) && !empty($system_settings['whatsapp_number'])) { ?>
            <div class="whatsapp-icon">
                <a href="https://api.whatsapp.com/send/?phone=<?= $system_settings['whatsapp_number'] ?>&text&type=phone_number&app_absent=0"
                    target="_blank" class="btn"><img src="<?= base_url('assets/logo/whatsapp_icon.png') ?>" alt="whatsapp"></a>
            </div>
        <?php } ?>
        <div class="d-flex align-items-center justify-content-center">
            <div id="chat-button">
                <i class="fa fa-commenting"></i>
            </div>
            <!-- Floating chat iframe -->
            <iframe src="<?= base_url('my-account/floating_chat_modern') ?>" id="chat-iframe" class="chat-iframe"></iframe>
        <?php } ?>
        <div class="progress-wrap">
            <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
                <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
            </svg>
        </div>
    </div>
</div>