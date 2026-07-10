<main>
    <section id="content" class="container py-4">
        <h2 class="section-title text-center pb-3">
            <?= !empty($this->lang->line('contact_us')) ? $this->lang->line('contact_us') : 'Contact Us' ?>
        </h2>
        <div class="main-content">
            <div class="row <?= !isset($web_settings['map_iframe']) || empty($web_settings['map_iframe']) ? 'justify-content-center' : '' ?>">
                <?php if (isset($web_settings['map_iframe']) && !empty($web_settings['map_iframe'])) { ?>
                    <div class="col-lg-7">
                        <div class="sign-up-image">
                            <?= html_entity_decode(stripcslashes($web_settings['map_iframe'])) ?>
                        </div>
                    </div>
                    <div class="col-lg-5 login-form mt-md-0 mt-3">
                <?php } else { ?>
                    <div class="col-lg-5 login-form mt-md-0 mt-3 m-0">
                <?php } ?>
                       <form id="contact-us-form" action="<?= base_url('home/send-contact-us-email') ?>" method="POST">
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">
                <?= !empty($this->lang->line('username')) ? $this->lang->line('username') : 'User Name' ?>
            </label>
            <input type="text" class="form-control" name="username"
                placeholder="<?= !empty($this->lang->line('username')) ? $this->lang->line('username') : 'User Name' ?>">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">
                <?= !empty($this->lang->line('email')) ? $this->lang->line('email') : 'Email' ?>
            </label>
            <input type="email" class="form-control" name="email"
                placeholder="<?= !empty($this->lang->line('email')) ? $this->lang->line('email') : 'Email' ?>">
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">
            <?= !empty($this->lang->line('subject')) ? $this->lang->line('subject') : 'Subject' ?>
        </label>
        <input type="text" class="form-control" name="subject"
            placeholder="<?= !empty($this->lang->line('subject')) ? $this->lang->line('subject') : 'Subject' ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">
            <?= !empty($this->lang->line('message')) ? $this->lang->line('message') : 'Message' ?>
        </label>
        <textarea class="form-control" name="message" rows="4"
            placeholder="<?= !empty($this->lang->line('message')) ? $this->lang->line('message') : 'Message' ?>"></textarea>
    </div>

    <button id="contact-us-submit-btn" class="btn btn-primary mt-2 w-100">
        <?= !empty($this->lang->line('send_message')) ? $this->lang->line('send_message') : 'Send Message' ?>
    </button>

</form>

                    </div>
                <?php if (isset($web_settings['map_iframe']) && !empty($web_settings['map_iframe'])) { ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</main>
