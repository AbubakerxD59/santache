<main>
    <section class="container py-4">
        <div class="text-center">
            <h3 class="section-title"><?= !empty($this->lang->line('contact_us')) ? $this->lang->line('contact_us') : 'Contact Us' ?></h3>
        </div>
        <div class="text-justify">
            <?= $contact_us ?>
        </div>
    </section>
</main>