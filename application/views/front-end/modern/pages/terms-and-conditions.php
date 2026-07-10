<main>
    <section class="container py-4">
        <div class="text-center">
            <h4 class="section-title"><?= !empty($this->lang->line('terms_and_condition')) ? $this->lang->line('terms_and_condition') : 'Terms & Conditions' ?></h4>
        </div>
        <div class="text-justify">
            <?= $terms_and_conditions ?>
        </div>
    </section>
</main>