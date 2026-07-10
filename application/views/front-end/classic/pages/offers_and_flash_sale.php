<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h2><?= !empty($this->lang->line('flash_sale')) ? $this->lang->line('flash_sale') : 'Flash Sale' ?></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?>
                <li class="breadcrumb-item"><a href="#"><?= !empty($this->lang->line('flash_sale')) ? $this->lang->line('flash_sale') : 'Flash Sale' ?></a></li>
            </ol>
        </nav>
    </div>
</section>
<section class="mt-3">
    <div class="main-content">
        <div class=''>
            <!-- Swiper -->
            <div class="swiper-container banner-swiper">
                <div class="swiper-wrapper">
                    <?php if (isset($sliders) && !empty($sliders)) { ?>
                        <?php foreach ($sliders as $row) { ?>
                            <div class="swiper-slide center-swiper-slide">
                                <a href="<?= $row['link'] ?>">
                                    <img src="<?= base_url($row['image']) ?>">
                                </a>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination swiper1-pagination"></div>
                <!-- Add Pagination -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </div>
</section>

<section class="main-content mt-md-2 mt-sm-0 mb-5">
 
 <!-- offer-slider -->
 <?php $this->load->view('front-end/classic/pages/offer_slider'); ?>


<!-- end offer-slider -->
<br>
<!-- flash_sale -->

<?php $this->load->view('front-end/classic/pages/flash_sale'); ?>

<!-- end flash_sale -->
</section>