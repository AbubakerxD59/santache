<!-- breadcrumb -->
<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h2><?= !empty($this->lang->line('refer_and_earn')) ? $this->lang->line('refer_and_earn') : 'Refer and earn' ?></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('my-account') ?>"><?= !empty($this->lang->line('dashboard')) ? $this->lang->line('dashboard') : 'Dashboard' ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?= !empty($this->lang->line('refer_and_earn')) ? $this->lang->line('refer_and_earn') : 'Refer and earn' ?></a></li>
            </ol>
        </nav>
    </div>

</section>
<!-- end breadcrumb -->

<section class="my-account-section">
    <div class="main-content">
        <div class="col-md-12 mt-5 mb-3">
            <div class="user-detail align-items-center">
                <div class="ml-3">
                    <h6 class="text-muted mb-0"><?= !empty($this->lang->line('hello')) ? $this->lang->line('hello') : 'Hello' ?></h6>
                    <h5 class="mb-0"><?= $user->username ?></h5>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-4">
                <?php $this->load->view('front-end/' . THEME . '/pages/my-account-sidebar') ?>
            </div>
            <div class="col-md-8">
                <div class='card border-0'>
                    <div class="card-header bg-white">
                        <h1 class="h4 "><?= !empty($this->lang->line('refer_and_earn')) ? $this->lang->line('refer_and_earn') : 'Refer and earn' ?></h1>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <h6 class="h6"><?=label('sub_title','Invite your friends to join and get the reward as soon as your friend places his first order')?></h6>
                    <h1 class="h1 mt-2"><?= label('your_referral_code','Your Referral Code')?></h1>
                    <div class=" row col-12 d-flex justify-content-center">
                        <div class="col-md-4 border refer_and_earn_border">
                            <?php

                            $referral_code = fetch_details('users', ['id' => $_SESSION['user_id']], 'referral_code');
                            if (empty($referral_code[0]['referral_code']) && $referral_code[0]['referral_code'] == '') {
                                $referral_generate_code = substr(str_shuffle(str_repeat("AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz1234567890", 8)), 0, 8);
                                update_details(['referral_code' => $referral_generate_code], ['id' => $_SESSION['user_id']], 'users');
                            }
                            ?>
                            <h2 class="mt-2" id="text-to-copy"><?= $referral_code[0]['referral_code']; ?></h2>
                        </div>
                    </div>
                    <button class="mt-3 btn btn-success btn-sm copy-button" ><?= label('tap_to_copy','Tap to copy')?></button>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!--end container-->
</section>