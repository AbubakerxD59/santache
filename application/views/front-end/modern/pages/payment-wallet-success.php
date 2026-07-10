<main>
    <section class="container py-4">
        <div class="row">
            <div class="col-md-12 col-12 mt-4 pt-2">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active rounded p-4 text-center" id="dash" role="tabpanel" aria-labelledby="dashboard">
                        <i class="fas fa-check-circle fa-4x text-success"></i>
                        <h4 class="h4 text-success fw-semibold"><?= label('wallet_recharged', 'Wallet Recharged') ?></h4>
                        <p class="fs-5 fw-medium"><?= label('wallet_recharged_Successfully', 'Wallet recharged Successfully') ?>.</p>
                        <a class="btn btn-primary" href="<?= base_url('my_account/wallet') ?>"><?= label('Return_to_wallet', 'Return to wallet') ?></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>