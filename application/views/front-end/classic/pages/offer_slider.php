<?php foreach ($offer_slider as $row) {
    $offer_ids = $row['offer_ids'];
    $ids = explode(",", $offer_ids);
?>
    <section class="mt-2">
        <?php if ($row['style'] == 'style_1' || $row['style'] == 'default') { ?>
            <div class="main-content">
                <div class=''>
                    <!-- Swiper -->
                    <div class="swiper-container banner-swiper">
                        <div class="swiper-wrapper">
                            <?php if (isset($ids) && !empty($ids)) { ?>
                                <?php foreach ($ids as $rows) {

                                    $offer_details = fetch_details('offers', ['id' => $rows]);
                                ?>
                                    <div class="swiper-slide center-swiper-slide">
                                        <a href="<?= base_url('products/manage_offers/' . $rows) ?>">
                                            <img src="<?= isset($offer_details[0]['image']) ? base_url($offer_details[0]['image']) : '' ?>">
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
            <br>
        <?php } elseif ($row['style'] == 'style_2') { ?>
            <div class="offer-section container-fluid text-center">
                <!-- Swiper -->
                <div class="swiper-container category-swiper swiper-container-initialized swiper-container-horizontal">
                    <div class="swiper-wrapper">
                        <?php if (isset($ids) && !empty($ids)) { ?>
                            <?php foreach ($ids as $rows) {

                                $offer_details = fetch_details('offers', ['id' => $rows]);
                            ?>
                                <div class="swiper-slide">
                                    <div class="category-grid">
                                        <div class="category-image">
                                            <div class="product-image-container">
                                                <a href="<?= base_url('products/manage_offers/' . $rows) ?>">
                                                    <img src="<?= base_url($offer_details[0]['image']) ?>">
                                                </a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } ?>

                    </div>
                    <!-- Add Pagination -->
                    <div class="swiper-pagination category-swiper-pagination swiper-pagination-bullets"><span class="swiper-pagination-bullet swiper-pagination-bullet-active"></span></div>
                    <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                    <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                </div>
            </div>
            <br>
        <?php } elseif ($row['style'] == 'style_3') { ?>
            <div class="offer-section2 container-fluid text-center">
                <!-- Swiper -->
                <div class="swiper-container category-swiper swiper-container-initialized swiper-container-horizontal">
                    <div class="swiper-wrapper">
                        <?php if (isset($ids) && !empty($ids)) { ?>
                            <?php foreach ($ids as $rows) {
                                $offer_details = fetch_details('offers', ['id' => $rows]);
                            ?>
                                <div class="swiper-slide ">
                                    <div class="category-grid">
                                        <div class="category-image">
                                            <div class="product-image-container">
                                                <a href="<?= base_url('products/manage_offers/' . $rows) ?>">
                                                    <img src="<?= base_url($offer_details[0]['image']) ?>">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } ?>
                    </div>
                    <!-- Add Pagination -->
                    <div class="swiper-pagination category-swiper-pagination swiper-pagination-bullets">
                        <span class="swiper-pagination-bullet swiper-pagination-bullet-active"></span>
                    </div>
                    <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                    <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                </div>
            </div>
        <?php } elseif ($row['style'] == 'style_4') { ?>
            <div class="offer-section3 container-fluid text-center">
                <!-- Swiper -->
                <div class="swiper-container category-swiper1 swiper-container-initialized swiper-container-horizontal">
                    <div class="swiper-wrapper">
                        <?php if (isset($ids) && !empty($ids)) { ?>
                            <?php foreach ($ids as $rows) {
                                $offer_details = fetch_details('offers', ['id' => $rows]);
                                if(count($offer_details) >= 1){

                            ?>
                                    <div class="swiper-slide">
                                        <div class="category-grid">
                                            <div class="category-image">
                                                <div class="product-image-container card-image">
                                                    <a href="<?= base_url('products/manage_offers/' . $rows) ?>">
                                                        <img src="<?= base_url($offer_details[0]['image']) ?>">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                        <?php }
                            }
                        } ?>
                    </div>
                    <!-- Add Pagination -->
                    <div class="swiper-pagination category-swiper-pagination swiper-pagination-bullets"><span class="swiper-pagination-bullet swiper-pagination-bullet-active"></span></div>
                    <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                    <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>

                </div>
            </div>
        <?php } ?>
    </section>
<?php }  ?>