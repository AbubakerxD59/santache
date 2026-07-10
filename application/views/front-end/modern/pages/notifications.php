<main>
    <section class="container py-5">
        <div class="row">
            <div class="col-md-3 myaccount-navigation py-3">
                <?php $this->load->view('front-end/' . THEME . '/pages/my-account-sidebar') ?>
            </div>
            <div class="col-md-9 padding-16-30">
                <div class="mb-3">
                    <h4 class="section-title"><?= label('notification', 'Notification') ?></h4>
                </div>
                <div class="card-body px-0">
                    <table class='table table-striped' data-toggle="table" data-url="<?= base_url('admin/Notification_settings/get_notification_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]'>
                        <thead>
                            <tr>
                                <th data-field="id" data-sortable="true"><?= label('id', 'ID') ?></th>
                                <th data-field="title" data-sortable="true"><?= label('title', 'Title') ?></th>
                                <th data-field="message" data-sortable="true"><?= label('message', 'Message') ?></th>
                                <th data-field="link" data-sortable="true"><?= label('link', 'Link') ?></th>
                                <th data-field="image" data-sortable="false" class="col-md-5"><?= label('image', 'Image') ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <?php
                if (isset($products) && !empty($products)) {
                } else { ?>

                <?php } ?>
            </div>
        </div>
    </section>
</main>
