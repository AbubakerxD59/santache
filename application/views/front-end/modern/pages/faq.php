<main>
    <section class="container home_faq_sec py-4" id="faq_sec">
        <div class="main-content">
            <h3><span class="section-title"><?= !empty($this->lang->line('faq')) ? $this->lang->line('faq') : 'FAQ' ?></span></h3>
            <div class="align-items-center d-flex justify-content-between">
                <div class="home_faq col-md-7">
                    <?php if (!empty($faq['data'])) { ?>
                        <?php foreach ($faq['data'] as $row) { ?>
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="<?= "h-" . $row['id'] ?>">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?= "c-" . $row['id'] ?>" aria-expanded="true" aria-controls="collapseOne">
                                            <?= html_escape($row['question']) ?>
                                        </button>
                                    </h2>
                                    <div id="<?= "c-" . $row['id'] ?>" class="accordion-collapse collapse" aria-labelledby="<?= "h-" . $row['id'] ?>" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <?= html_escape($row['answer']) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php } ?>
                    <?php } else { ?>
                        <div class="h2 text-center">

                            <?= !empty($this->lang->line('no_faq_found')) ? $this->lang->line('no_faq_found') : 'No FAQ Found' ?>
                        </div>

                    <?php } ?>

                </div>
                <div class="col-md-5">
                    <div class="faq_image">
                        <img src="<?= base_url('assets/front_end/modern/image/pictures/faq-img.png') ?>" alt="faq">
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>