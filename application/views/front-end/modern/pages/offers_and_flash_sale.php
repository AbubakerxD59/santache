<main>

    <!-- Flash Sale Top Section -->
    <?php $this->load->view('front-end/modern/pages/flash_sale'); ?>

    <section class="container py-4">

        <!-- Title Row -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2 class="fw-bold mb-0">
                <?= label('flash_sale', 'Flash Sale'); ?>
            </h2>
        </div>

        <?php if (!empty($sliders)) : ?>

            <!-- Banner Slider Card -->
            <div class="bg-white rounded-4 shadow-sm p-3 p-md-4">

                <div class="swiper banner-swiper">

                    <div class="swiper-wrapper">

                        <?php foreach ($sliders as $row) : ?>

                            <div class="swiper-slide d-flex justify-content-center">

                                <a href="<?= $row['link']; ?>" class="d-block w-100">

                                    <!-- Fixed Ratio Banner -->
                                    <div class="ratio ratio-16x9 rounded-3 overflow-hidden">

                                        <img
                                            src="<?= !empty($row['image']) ? base_url($row['image']) : base_url(NO_IMAGE); ?>"
                                            class="img-fluid w-100 h-100 object-fit-cover"
                                            alt="Flash Sale Banner"
                                            loading="lazy"
                                            onerror="this.onerror=null;this.src='<?= base_url(NO_IMAGE) ?>';">

                                    </div>

                                </a>

                            </div>

                        <?php endforeach; ?>

                    </div>

                    <!-- Pagination -->
                    <div class="swiper-pagination mt-3 position-static"></div>

                    <!-- Navigation -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>

                </div>

            </div>

        <?php endif; ?>

        <!-- Offer Slider -->
        <div class="mt-5">
            <?php $this->load->view('front-end/modern/pages/offer_slider'); ?>
        </div>

    </section>

</main>
