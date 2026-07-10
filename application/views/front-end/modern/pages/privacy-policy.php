<main>
    <section class="container py-4">
        <div class="text-center">
            <h3 class="section-title"><?= !empty($this->lang->line('privacy_policy')) ? $this->lang->line('privacy_policy') : 'Privacy Policy' ?></h3>
        </div>
        <div class="text-justify">
            <?= $privacy_policy ?>
        </div>
    </section>
</main>