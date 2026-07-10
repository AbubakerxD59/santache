<?php foreach ($offer_slider as $row) {
    $offer_ids = $row['offer_ids'];
    $ids = explode(",", $offer_ids);
?>
    <main>
        <section class="my-4 bg-white">
            <?php if ($row['style'] == 'style_1' || $row['style'] == 'default') { ?>
                <div class="swiper mySwiper grab rounded-3">
    <div class="swiper-wrapper">

        <?php if (!empty($ids)) : ?>
            <?php foreach ($ids as $rows) : ?>
                <?php
                    $offer_details = fetch_details('offers', ['id' => $rows]);
                    if (count($offer_details) < 1) continue;
                ?>

                <div class="swiper-slide d-flex justify-content-center">
                    <a href="<?= ($offer_details[0]['type'] == 'offer_url') 
                            ? $offer_details[0]['link'] 
                            : base_url('products/manage_offers/' . $rows) ?>"
                       target="_blank"
                       class="d-block w-100 text-decoration-none">

                        <div class="card border-0 shadow-sm rounded overflow-hidden">
                            <div class="ratio ratio-16x9 bg-light d-flex align-items-center justify-content-center">
                                <img 
                                    src="<?= !empty($offer_details[0]['image']) 
                                            ? base_url($offer_details[0]['image']) 
                                            : base_url(NO_IMAGE) ?>"
                                    class="img-fluid w-75 h-75 object-fit-contain"
                                    alt="Offer Image"
                                    onerror="this.onerror=null;this.src='<?= base_url(NO_IMAGE) ?>';"
                                >
                            </div>
                        </div>

                    </a>
                </div>

            <?php endforeach; ?>
        <?php endif; ?>

    </div>

    <!-- Pagination -->
    <div class="swiper-pagination"></div>
</div>

            <?php } elseif ($row['style'] == 'style_2') { ?>
                <div class="offer-section container-fluid text-center">
                    <!-- Swiper2 -->
                    <div class="swiper mySwiper3 swiper-arrow swiper-wid">
                        <div class="swiper-wrapper grab">
                            <?php if (isset($ids) && !empty($ids)) { ?>
                                <?php foreach ($ids as $rows) {

                                    $offer_details = fetch_details('offers', ['id' => $rows]);
                                    if(count($offer_details) >= 1){
                                ?>
                                    <div class="swiper-slide background-none">
                                        <a href="<?= ($offer_details[0]['type'] == 'offer_url') ? $offer_details[0]['link'] : base_url('products/manage_offers/' . $rows) ?>" target="_blank">
                                            <img src="<?= isset($offer_details[0]['image']) ? base_url($offer_details[0]['image']) : '' ?>">
                                        </a>
                                    </div>
                                <?php } } ?>
                            <?php } ?>
                        </div>
                        <!-- Add Pagination -->
                        <div class="swiper-pagination swiper1-pagination"></div>
                        <!-- Add Pagination -->
                        <div class="swiper-button-next"><ion-icon name="chevron-forward-outline"></ion-icon></div>
                        <div class="swiper-button-prev"><ion-icon name="chevron-back-outline"></ion-icon></div>
                    </div>
                <?php } elseif ($row['style'] == 'style_3') { ?>
                    <div class="offer-section2 container-fluid text-center">
                        <!-- Swiper3 -->
                        <div class="swiper mySwiper3 swiper-arrow swiper-wid">
                            <div class="swiper-wrapper grab">
                                <?php if (isset($ids) && !empty($ids)) { ?>
                                    <?php foreach ($ids as $rows) {

                                        $offer_details = fetch_details('offers', ['id' => $rows]);
                                        if(count($offer_details) >= 1){
                                    ?>
                                        <div class="swiper-slide background-none">
                                            <a href="<?= ($offer_details[0]['type'] == 'offer_url') ? $offer_details[0]['link'] : base_url('products/manage_offers/' . $rows) ?>" target="_blank">
                                                <img src="<?= isset($offer_details[0]['image']) ? base_url($offer_details[0]['image']) : '' ?>">
                                            </a>
                                        </div>
                                    <?php } } ?>
                                <?php } ?>
                            </div>
                            <!-- Add Pagination -->
                            <div class="swiper-pagination swiper1-pagination"></div>
                            <!-- Add Pagination -->
                            <div class="swiper-button-next"><ion-icon name="chevron-forward-outline"></ion-icon></div>
                            <div class="swiper-button-prev"><ion-icon name="chevron-back-outline"></ion-icon></div>
                        </div>
                    </div>
                <?php } elseif ($row['style'] == 'style_4') { ?>
                    <div class="offer-section3 container-fluid text-center">
                        <!-- Swiper4 -->
                        <div class="swiper mySwiper3 swiper-arrow swiper-wid">
                            <div class="swiper-wrapper grab">
                                <?php if (isset($ids) && !empty($ids)) { ?>
                                    <?php foreach ($ids as $rows) {

                                        $offer_details = fetch_details('offers', ['id' => $rows]);
                                        if(count($offer_details) >= 1){
                                    ?>
                                        <div class="swiper-slide background-none">
                                            <a href="<?= ($offer_details[0]['type'] == 'offer_url') ? $offer_details[0]['link'] : base_url('products/manage_offers/' . $rows) ?>" target="_blank">
                                                <img src="<?= isset($offer_details[0]['image']) ? base_url($offer_details[0]['image']) : '' ?>">
                                            </a>
                                        </div>
                                    <?php } } ?>
                                <?php } ?>
                            </div>
                            <!-- Add Pagination -->
                            <div class="swiper-pagination swiper1-pagination"></div>
                            <!-- Add Pagination -->
                            <div class="swiper-button-next"><ion-icon name="chevron-forward-outline"></ion-icon></div>
                            <div class="swiper-button-prev"><ion-icon name="chevron-back-outline"></ion-icon></div>
                            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                        </div>
                    </div>
                <?php } ?>
        </section>
    </main>
<?php }  ?>