<section class="container main-content mb-15 my-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
            <li class="breadcrumb-item active text-muted" aria-current="page"><?= !empty($this->lang->line('shipping_policy')) ? $this->lang->line('shipping_policy') : 'Shipping Policy' ?></li>
        </ol>
    </nav>
    <div class="text-center">
        <h1 class="section-title"><?= !empty($this->lang->line('shipping_policy')) ? $this->lang->line('shipping_policy') : 'Shipping Policy' ?></h1>
    </div>
    <div class="text-justify">
        <div class="hrDiv">
            <p>
                <?= $shipping_policy ?>
            </p>
        </div>
    </div>
</section>