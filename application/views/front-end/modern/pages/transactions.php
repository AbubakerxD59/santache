<main>
    <section class="container py-5">
        <div class="row">
            <div class="col-md-3 myaccount-navigation py-3">
                <?php $this->load->view('front-end/' . THEME . '/pages/my-account-sidebar') ?>
            </div>
            <div class="col-md-9 padding-16-30">
                <h1 class="section-title"><?= !empty($this->lang->line('transactions')) ? $this->lang->line('transactions') : 'Transactions' ?></h1>
                <table class='table-striped' data-toggle="table" data-url="<?= base_url('my-account/get-transactions') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true">
                    <thead>
                        <tr>
                            <th data-field="id" data-sortable="true"><?= label('id', 'Id') ?></th>
                            <th data-field="name" data-sortable="false"><?= label('user_name', 'User Name') ?></th>
                            <th data-field="order_id" data-sortable="false"><?= label('order_id', 'Order Id') ?></th>
                            <th data-field="txn_id" data-sortable="false"><?= label('transaction', 'Transaction') ?> Id</th>
                            <th data-field="payu_txn_id" data-sortable="false" data-visible="false"><?= label('pay_transaction_id', 'Pay Transaction Id') ?></th>
                            <th data-field="amount" data-sortable="false"><?= label('amount', 'Amount') ?></th>
                            <th data-field="status" data-sortable="false"><?= label('status', 'Status') ?></th>
                            <th data-field="message" data-sortable="false" data-visible="false"><?= label('message', 'Message') ?></th>
                            <th data-field="txn_date" data-sortable="false"><?= label('date', 'Date') ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
</main>