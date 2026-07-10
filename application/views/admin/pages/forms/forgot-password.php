        <div class="authentication-wrapper authentication-cover">
            <div class="authentication-inner row">
                <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center">
                    <div class="d-flex justify-content-center my-0 mx-auto">
                        <img src="<?= base_url('assets/admin/img/backgrounds/Login_IMG.png') ?>" class="img-fluid" alt="Login image" width="700" data-app-dark-img="illustrations/boy-with-rocket-dark.png" data-app-light-img="illustrations/boy-with-rocket-light.png">
                    </div>
                </div>
                <!-- /.login-logo -->
                <div class="d-flex col-12 col-sm-5 col-md-4 align-items-center authentication-bg p-sm-5 login-background-color">
                    <div class="card-body login-card-body">
                        <div class="forgot-logo">
                            <a href="<?= base_url() . 'admin/login' ?>"><img src="<?= base_url() . $logo ?>"></a>
                        </div>
                       <p class="login-box-msg"> <span class="fw-bold fs-4">You Forgot Your Password?</span> <br> <SPAN class="fw-bold"> Here you can easily retrieve a new password.</p> </SPAN>
                        <form action="<?= base_url('auth/forgot_password') ?>" id="forgot_password_page" method="POST">
                        <input type="hidden" name="csrf_token" value="$csrf_token()"/>
                            <div class="input-group mb-3">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                                <input type="email" class="form-control" name="identity" placeholder="Email">
                                <?= form_error('identity', '<div class="text-danger">', '</div>'); ?>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-text">
                                    <span class="fas fa-phone"></span>
                                </div>
                                
                                <input type="text" class="form-control" name="mobile" placeholder="Mobile">
                                <?= form_error('mobile', '<div class="text-danger">', '</div>'); ?>
                            </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-block col-md-12" id="submit_btn">Send Email</button>
                                </div>
                            <div class="col-md-12 col-6 text-danger text-center m-1" id="result"></div>
                        </form>

                        <p class="mt-4 text-center">
                            <a class="login fs-5" href="<?= base_url('admin/home/') ?>">Login</a>
                        </p>
                    </div>
                    <!-- /.login-card-body -->
                </div>
            </div>
        </div>
 



        