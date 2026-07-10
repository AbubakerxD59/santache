<main>
    <section class="container py-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <li class="breadcrumb-item active"><?= !empty($this->lang->line('about_us')) ? $this->lang->line('about_us') : 'About Us' ?></li>
            </ol>
        </nav>
        <div class="text-center">
            <h2 class="section-title"><?= label('about_us', 'About us') ?></h2>
        </div>
        <div class="text-justify">
            <div class="hrDiv text-body-secondary">
                <p>
                    <?= $about_us ?>
                </p>
            </div>
        </div>
    </section>
</main>