<main>
    <section class="my-account-section container">
        <div class="row">
            <div class="col-md-3 myaccount-navigation py-3">
                <?php $this->load->view('front-end/' . THEME . '/pages/my-account-sidebar') ?>
            </div>
            <div class="col-md-9 padding-16-30">
                <h3 class="section-title "><?= !empty($this->lang->line('delete_account')) ? $this->lang->line('delete_account') : 'Delete Account' ?></h3>
                <div>
                    <?php
                    $type = fetch_details('users', ['id' => $_SESSION['user_id']], ['type']);
                    if ($type[0]['type'] == 'phone') {
                    ?>
                        <form class="form-horizontal form-submit-event" id="stock_adjustment_form" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-12">
                                    <label for="Mobile_number" class="form-label">
                                        <?= label('mobile_number', 'Mobile Number') ?>
                                    </label>
                                    <input type="text" class="form-control current_stock" name="Mobile_number" id="Mobile_number" value="<?= (isset($_SESSION['mobile']) && !empty($_SESSION['mobile'])) ? $_SESSION['mobile'] : '' ?>" readonly>
                                </div>
                                <div class="col-12">
                                    <label for="password" class="form-label">
                                        <?= label('password', 'Password') ?>
                                    </label>
                                    <input type="password" class="form-control" name="password" id="password">
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary delete_user_account mt-3" value="Save"><?= label('submit', 'Submit') ?></button>
                        </form>
                    <?php } elseif ($type[0]['type'] == 'email') { ?>
                        <?php $id = ($_SESSION['user_id']);  ?>
                        <input type="hidden" class="form-control" name="user_id" id="session_user_id" value="<?= $id ?>">
                        <!-- Email User Account Deletion Form -->
                        <form class="form-horizontal" id="email_delete_form" method="POST">
                            <div class="row">
                                <div class="col-12">
                                    <label for="Email_address" class="form-label">
                                        <?= label('email', 'Email Address') ?>
                                    </label>
                                    <input type="email" class="form-control" name="Email_address" id="Email_address" value="<?= (isset($_SESSION['email']) && !empty($_SESSION['email'])) ? $_SESSION['email'] : '' ?>" readonly>
                                </div>
                                <div class="col-12">
                                    <label for="password" class="form-label">
                                        <?= label('password', 'Password') ?>
                                    </label>
                                    <input type="password" class="form-control" name="password" id="password" required>
                                </div>
                            </div>
                            <button type="button" class="btn btn-danger delete_email_user_account mt-3"><?= label('delete_account', 'Delete Account') ?></button>
                        </form>
                    <?php } else { ?>
                        <!-- Social Account Deletion -->
                        <?php $id = ($_SESSION['user_id']);  ?>
                        <input type="hidden" class="form-control" name="user_id" id="session_user_id" value="<?= $id ?>">
                        <button type="submit" class="btn btn-primary delete_social_account" value="Save"><?= label('delete_account', 'Delete Account') ?></button>
                    <?php } ?>
            </div>
        </div>
    </section>
</main>