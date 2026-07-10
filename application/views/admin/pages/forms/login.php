<div class="login-box">
    <!-- /.login-logo -->
    <div class="authentication-wrapper authentication-cover">
        <div class="authentication-inner row m-0">
            <!-- /Left Text -->
            <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center p-5">
                <div class="d-flex justify-content-center my-0 mx-auto max-w65">
                    <img src="<?= base_url('assets/admin/img/backgrounds/Login_IMG.png') ?>" class="img-fluid" alt="Login image" width="700" data-app-dark-img="illustrations/boy-with-rocket-dark.png" data-app-light-img="illustrations/boy-with-rocket-light.png">
                </div>
            </div>
            <!-- /Left Text -->

            <!-- Login -->
            <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-sm-5 p-4 login-background-color">
                <div class="w-px-400 mx-auto">
                    <?php if (ALLOW_MODIFICATION == 0) { ?>
                        <div class="alert alert-warning">
                            Note: If you cannot login here, please close the codecanyon frame by clicking on x Remove Frame button from top right corner on the page or <a href="<?= base_url('/admin') ?>" target="_blank" class="text-danger"> >> Click here << </a>
                        </div>
                    <?php } ?>
                    <!-- Logo -->
                    <div class="login-logo">
                        <a href="<?= base_url() . 'admin/login' ?>"><img src="<?= base_url() . $logo ?>"></a>
                    </div>
                    <!-- /Logo -->
                    <h4>
                        <p class="login-box-msg">Sign in to start your session</p>
                    </h4>

                    <form action="<?= base_url('auth/login') ?>" class='form-submit-event' method="post">
                        <input type='hidden' name='<?= $this->security->get_csrf_token_name() ?>' value='<?= $this->security->get_csrf_hash() ?>'>
                        <input type="hidden" id="web_fcm" name="web_fcm" value="">

                        <div class="input-group mb-3">
                            <input type="text" id="number_input" class="form-control" name="identity" maxlength="15" placeholder="<?= ucfirst($identity_column) ?>" <?= (ALLOW_MODIFICATION == 0) ? 'value="9876543210"' : ""; ?> onkeypress="return isValidPhoneChar(event)" required>

                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="password" placeholder="Password" <?= (ALLOW_MODIFICATION == 0) ? 'value="12345678"' : ""; ?>>
                            <span class="input-group-text togglePassword" style="cursor: pointer;">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                        <div class="align-items-center d-flex justify-content-between">
                            <div class="icheck-primary">
                                <input type="checkbox" name="remember" id="remember">
                                <label for="remember"> Remember Me </label>
                            </div>
                            <div class="text-right">
                                <a href="<?= base_url('/admin/login/forgot_password') ?>" class="forgot-btn"><?= !empty($this->lang->line('forgot_password')) ? $this->lang->line('forgot_password') : 'Forgot Password' ?> ?</a>
                            </div>
                        </div>

                        <!-- /.col -->
                        <div class="col-12 mt-3">
                            <button type="submit" id="submit_btn" class="btn btn-primary btn-block col-md-12">Sign In</button>
                        </div>

                </div>
                </form>
            </div>

        </div>
        <!-- /Login -->
    </div>
</div>
</div>
<!-- /.login-box -->