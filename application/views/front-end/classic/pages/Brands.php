<section class="container mt-3">
    <div class="row">
        <?php
        foreach ($brands as $key => $row) { ?>
            <div class="col-md-3 col-4">
                <div class="brands-card">
                    <a href="<?= base_url('products?brand=' . html_escape($row['brand_slug'])) ?>" class="text-reset text-decoration-none">
                        <div class="brands-page-image">
                            <img src="<?= base_url($row['brand_img']) ?>" alt="<?= html_escape($row['brand_name']) ?>" />
                        </div>
                        <div class="categories-card-text">
                            <h4><?= html_escape($row['brand_name']) ?></h4>
                        </div>
                    </a>
                </div>
            </div>
        <?php } ?>
    </div>
</section>