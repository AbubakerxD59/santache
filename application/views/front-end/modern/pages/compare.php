<main>
    <section class="container py-4">
        <div>
            <h2 class="section-title"><?= label('compare', 'Compare') ?></h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                    <li class="breadcrumb-item active"><?= !empty($this->lang->line('compare')) ? $this->lang->line('compare') : 'Compare' ?></li>
                </ol>
            </nav>
        </div>
        <div class="entry-content py-4">
            <div class="overflow-auto" id="compare-items">
                <div class="text-center ">
                    <h2 class="fw-semibold"><?= label('compare_list_is_empty','Compare list is empty.')?></h2>
                    <p class="mb-2 text-secondary"><?=label ('compare_subtitle','No products added in the compare list. You must add some products to compare them.
                        You will find a lot of interesting products on our "Shop" page.')?></p>
                    <div class="text-center mt-2"><a class="btn btn-primary" href="<?= base_url('products') ?>"> <?= label('return_to_shop', 'Return to shop') ?></a></div>
                </div>
            </div>
            <div id="comparison_empty_msg"></div>
        </div>
    </section>
</main>