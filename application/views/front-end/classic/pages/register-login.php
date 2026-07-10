<?php
$registrationSectionDisplay = 'none';
$loginSectionDisplay = 'none';
$verifyOtpForm = 'style="display:none"';
$signUpForm = 'style="display:none"';
?>
<main>
    <section class="container my-5">
        <div class="row register-login-section">
            <!-- registration section-->
            <div id="register_div" class="col-md-6 px-5 registration-section">
                <h4 class="mb-3 section-title"><?= label('register', 'REGISTER') ?></h4>
                <?php if ((!empty($system_settings['email_login']) && $system_settings['email_login'] == 1)) { ?>
                <div class="mb-3 mt-3">
                    <a href="#" id="emailLogin" class="text-decoration-underline email-login">Register with Email?</a>
                </div>
                <?php } ?>
                <form id='send-otp-form' class='send-otp-form cmxform' action='#'>
                    <div class="mb-3 sign-up-verify-number">
                        <label for="exampleFormUsername" class="form-label">
                            <p class="form-lable"><?= label('mobile_number', 'Mobile Number') ?><sup
                                    class="text-danger fw-bold">*</sup></p>
                        </label>
                        <input type="text" class='form-input form-control phone-number-input' name="mobileNumber"
                            pattern="\d*" placeholder="Enter Mobile Number" id="phone-number" required>
                    </div>
                    <div id="recaptcha-container"></div>
                    <div id='is-user-exist-error' class='text-center text-danger'></div>
                    <div id='recaptcha-error' class='text-center text-danger'></div>
                    <div class="mb-3">
                        <button type="submit" id='send-otp-button'
                            class="btn Register-btn submit-btn send_otp_button"><?= label('send_otp', 'Send OTP') ?></button>
                    </div>
                </form>
                <?php if ((!empty($system_settings['email_login']) && $system_settings['email_login'] == 1)) { ?>
                <div class="mb-3 mt-3">
                    <a href="#" id="phoneLogin" class="text-decoration-underline phone-login d-none">Register with Phone?</a>
                </div>
                <?php } ?>
                <form id='verify-otp-form' class='verify-otp-form' action='<?= base_url('auth/register-user') ?>'
                    method="POST" <?php echo $verifyOtpForm; ?>>
                    <div class="col-12 d-flex justify-content-center pb-4">
                        <input type="hidden" id="web_fcm" name="web_fcm" value="">
                        <input type="hidden" class='form-input' id="type" name="type" value="phone">
                    </div>
                    <div class="mb-3 sign-up-verify-number">
                        <label for="exampleFormUsername" class="form-label">
                            <p class="form-lable"><?= label('otp', 'Otp') ?><sup class="text-danger fw-bold">*</sup></p>
                        </label>
                        <input type="number" class='form-input form-control' placeholder="OTP" id="otp" name="otp"
                            autocomplete="off" required>
                    </div>
                    <div class="mb-3 sign-up-verify-number">
                        <label for="exampleFormUsername" class="form-label">
                            <p class="form-lable"><?= label('username', 'Username') ?><sup
                                    class="text-danger fw-bold">*</sup></p>
                        </label>
                        <input type="text" class='form-input form-control' placeholder="Username" id="name" name="name"
                            required>
                    </div>
                    <div class="mb-3 sign-up-verify-number">
                        <label for="exampleFormUsername" class="form-label">
                            <p class="form-lable"><?= label('email', 'Email') ?><sup class="text-danger fw-bold">*</sup>
                            </p>
                        </label>
                        <input type="email" class='form-input form-control' placeholder="Email" id="email" name="email"
                            required>
                    </div>
                    <div class="mb-3 sign-up-verify-number">
                        <label for="exampleFormUsername" class="form-label">
                            <p class="form-lable"><?= label('password', 'Password') ?><sup
                                    class="text-danger fw-bold">*</sup></p>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password" required>
                            <button type="button" class="btn btn-outline-secondary togglePassword">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <?php $referal_code = substr(str_shuffle(str_repeat("AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz1234567890", 8)), 0, 8);
                    ?> <input type="hidden" class='form-input' name="referral_code" value=<?= $referal_code; ?>>
                    <div class="mb-3 sign-up-verify-number">
                        <label for="exampleFormUsername" class="form-label">
                            <p class="form-lable"><?= label('referral_code', 'referral code') ?></p>
                        </label>
                        <input type="text" class='form-input form-control' placeholder="referral code" id="friends_code"
                            name="friends_code">
                    </div>
                    <div id='registration-error' class='text-center text-danger'></div>
                    <button type="submit" id='register_submit_btn'
                        class="btn btn-primary Register-btn register_submit_btn"><?= label('submit', 'Submit') ?></button>
                </form>
                <form id='sign-up-form' class='sign-up-form' action='#' <?php echo $signUpForm; ?>>
                    <div class="mb-3 sign-up-verify-number">
                        <label for="exampleFormUsername" class="form-label">
                            <p class="form-lable"><?= label('username', 'Username') ?><sup
                                    class="text-danger fw-bold">*</sup></p>
                        </label>
                        <input type="text" class='form-input form-control' placeholder="Username" name="username"
                            required>
                    </div>
                    <div class="mb-3 sign-up-verify-number">
                        <label for="exampleFormUsername" class="form-label">
                            <p class="form-lable"><?= label('email', 'Email') ?><sup class="text-danger fw-bold">*</sup>
                            </p>
                        </label>
                        <input type="email" class='form-input form-control' placeholder="Email" name="email" required>
                    </div>
                    <div class="mb-3 sign-up-verify-number">
                        <label for="exampleFormUsername" class="form-label">
                            <p class="form-lable"><?= label('password', 'Password') ?><sup
                                    class="text-danger fw-bold">*</sup></p>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                            <button type="button" class="btn btn-outline-secondary togglePassword">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div id='sign-up-error' class='text-center text-danger'></div>
                    <button type="submit" class="btn btn-primary Register-btn"><?= label('register', 'Register') ?></button>
                </form>
            </div>

            <!-- login section-->
            <div id="login-text" class="col-md-6 px-5 login-section">
                <h4 class="mb-3 section-title"><?= label('login', 'LOGIN') ?></h4>
                
                <!-- Mobile Login Form -->
                <div id="mobile-form">
                    <form id='login_form' class='form-submit-event' method="POST" action='<?= base_url('home/login') ?>'>
                        <div class="mb-3 login-mobile-no">
                            <label for="exampleFormUsername" class="form-label">
                                <p class="form-lable"><?= label('mobile_number', 'Mobile Number') ?><sup
                                        class="text-danger fw-bold">*</sup></p>
                            </label>
                            <input type="text" class="form-control" name="identity" placeholder="Enter Mobile Number" 
                                pattern="\d*" required>
                        </div>
                        <div class="mb-3 login-mobile-no">
                            <label for="exampleFormUsername" class="form-label">
                                <p class="form-lable"><?= label('password', 'Password') ?><sup
                                        class="text-danger fw-bold">*</sup></p>
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password" placeholder="Password" required>
                                <button type="button" class="btn btn-outline-secondary togglePassword">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div id='error_box' class='text-center text-danger'></div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary login-submit-btn submit_btn"><?= label('login', 'Login') ?></button>
                        </div>
                    </form>
                </div>

                <!-- Email Login Form -->
                <div id="email-form" class="d-none">
                    <form id='login_form_email' class='form-submit-event' method="POST" action='<?= base_url('home/login') ?>'>
                        <div class="mb-3 login-mobile-no">
                            <label for="exampleFormUsername" class="form-label">
                                <p class="form-lable"><?= label('email', 'Email') ?><sup
                                        class="text-danger fw-bold">*</sup></p>
                            </label>
                            <input type="email" class="form-control" name="identity" placeholder="Enter Email Address" required>
                        </div>
                        <div class="mb-3 login-mobile-no">
                            <label for="exampleFormUsername" class="form-label">
                                <p class="form-lable"><?= label('password', 'Password') ?><sup
                                        class="text-danger fw-bold">*</sup></p>
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password" placeholder="Password" required>
                                <button type="button" class="btn btn-outline-secondary togglePassword">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div id='error_box_email' class='text-center text-danger'></div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary login-submit-btn submit_btn"><?= label('login', 'Login') ?></button>
                        </div>
                    </form>
                </div>

                <!-- Toggle Links -->
                <?php if ((!empty($system_settings['email_login']) && $system_settings['email_login'] == 1)) { ?>
                <div class="mb-3 mt-3 login-email-login">
                    <a href="#" id="login-emailLogin" class="text-decoration-underline">Login with Email?</a>
                </div>
                <div class="mb-3 mt-3 login-phone-login d-none">
                    <a href="#" id="login-phoneLogin" class="text-decoration-underline">Login with Phone?</a>
                </div>
                <?php } ?>

                <!-- Forgot Password Links -->
                <div class="text-center mt-3">
                    <a href="#" class="forget_password_sec text-decoration-underline"><?= label('forgot_password', 'Forgot Password?') ?></a>
                </div>
                <?php if ((!empty($system_settings['email_login']) && $system_settings['email_login'] == 1)) { ?>
                <div class="text-center mt-2">
                    <a href="#" id="forgot-password-email-link" class="text-decoration-underline">Forgot password via Email?</a>
                </div>
                <?php } ?>

                <!-- Forgot Password Form -->
                <form id="forgot-password-email-form" class="d-none mt-3" method="post" action="#">
                    <div class="mb-3">
                        <label for="forgot_password_email" class="form-label">
                            <p class="form-lable"><?= label('email', 'Email Address') ?></p>
                        </label>
                        <input type="email" class="form-control" name="email" id="forgot_password_email" 
                            placeholder="Enter your email address" required>
                    </div>
                    <div id="forgot_pass_email_error_box" class="text-center p-2"></div>
                    <div class="mb-3">
                        <button type="submit" id="send_firebase_reset_email_btn" class="btn btn-primary">Send Reset Email</button>
                    </div>
                    <?php if ((!empty($system_settings['email_login']) && $system_settings['email_login'] == 1)) { ?>
                    <div id="forgot-password-phone-div" class="text-center mt-2 d-none">
                        <a href="#" id="forgot-password-phone" class="text-decoration-underline">Forgot password via Phone?</a>
                    </div>
                    <?php } ?>
                </form>
            </div>
        </div>

        <!-- Password Reset Form -->
        <div class="row mt-5">
            <div class="col-12">
                <form id="resetForm" style="display: none;">
                    <div class="text-center mb-4">
                        <h4>Reset Your Password</h4>
                        <p>Enter your new password below</p>
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="newPassword" name="newPassword" 
                                placeholder="Enter new password" required>
                            <button type="button" class="btn btn-outline-secondary togglePassword">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>