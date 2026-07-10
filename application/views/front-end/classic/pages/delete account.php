<!-- breadcrumb -->
<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h2><?= !empty($this->lang->line('delete_account')) ? $this->lang->line('delete_account') : 'Delete Account' ?></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('my-account') ?>"><?= !empty($this->lang->line('dashboard')) ? $this->lang->line('dashboard') : 'Dashboard' ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?= !empty($this->lang->line('delete_account')) ? $this->lang->line('delete_account') : 'Delete Account' ?></a></li>
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
            <div class="col-md-8 col-12">
                <div class='card border-0'>
                    <div class="card-header bg-white">
                        <h1 class="h4 "><?= !empty($this->lang->line('delete_account')) ? $this->lang->line('delete_account') : 'Delete Account' ?></h1>
                    </div>


                </div>
                <div class="col-md-6 mt-3">
                    <?php
                    $type = fetch_details('users', ['id' => $_SESSION['user_id']], ['type']);
                    if ($type[0]['type'] == 'phone') {
                    ?>
                        <form class="form-horizontal form-submit-event" id="stock_adjustment_form" method="POST" enctype="multipart/form-data">
                            <div class="col-12">
                                <div class="form-group">
                                <label for="Mobile_number" class="form-label">
                                        <?= label('mobile_number', 'Mobile Number') ?>
                                    </label>
                                    <input type="text" class="form-control current_stock" name="Mobile_number" id="Mobile_number" value="<?= (isset($_SESSION['mobile']) && !empty($_SESSION['mobile'])) ? $_SESSION['mobile'] : '' ?>" readonly>
                                    
                                    <label for="password" class="form-label">
                                        <?= label('password', 'Password') ?>
                                    </label>
                                    <input type="password" class="form-control" name="password" id="password">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success delete_user_account" value="Save"><?= label('submit', 'Submit') ?></button>
                            </div>
                        </form>
                    <?php } else { ?>
                        <?php $id = ($_SESSION['user_id']);  ?>
                        <input type="hidden" class="form-control" name="user_id" id="session_user_id" value="<?= $id ?>">
                        <button type="" class="btn btn-success delete_social_account" value="Save"><?= labels('Delete Account', 'Delete Account') ?></button>

                    <?php } ?>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!--end container-->
</section>