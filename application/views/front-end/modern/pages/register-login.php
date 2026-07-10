<?php
$registrationSectionDisplay = 'none';
$loginSectionDisplay = 'none';
$verifyOtpForm = '';
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
                    <a href="#" id="emailLogin" class="text-decoration-underline email-login"><?= label('register_with_email', 'Register with Email?') ?></a>
                </div>
                <?php } ?>
                <form id='send-otp-form' class='send-otp-form cmxform' action='#'>
                    <div class="mb-3 sign-up-verify-number">
                        <label for="exampleFormUsername" class="form-label">
                            <p class="form-lable"><?= label('mobile_number', 'Mobile Number') ?><sup
                                    class="text-danger fw-bold">*</sup></p>
                        </label>
                        <input type="text" class='form-input form-control phone-number-input' name="mobileNumber"
                            pattern="\d*" placeholder="<?= label('enter_mobile_number', 'Enter Mobile Number') ?>" id="phone-number" required>
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
                    <a href="#" id="phoneLogin" class="text-decoration-underline phone-login d-none"><?= label('register_with_phone', 'Register with Phone?') ?></a>
                </div>
                <?php } ?>
                <form id='verify-otp-form' class='verify-otp-form d-none' action='<?= base_url('auth/register-user') ?>'
                    method="POST">
                    <div class="col-12 d-flex justify-content-center pb-4">
                        <input type="hidden" id="web_fcm" name="web_fcm" value="">
                        <input type="hidden" class='form-input' id="type" name="type" value="phone">
                    </div>
                    <div class="mb-3 sign-up-verify-number">
                        <label for="exampleFormUsername" class="form-label">
                            <p class="form-lable"><?= label('otp', 'Otp') ?><sup class="text-danger fw-bold">*</sup></p>
                        </label>
                        <input type="number" class='form-input form-control' placeholder="<?= label('otp', 'OTP') ?>" id="otp" name="otp"
                            autocomplete="off" required>
                    </div>
                    <div class="mb-3 sign-up-verify-number">
                        <label for="exampleFormUsername" class="form-label">
                            <p class="form-lable"><?= label('username', 'Username') ?><sup
                                    class="text-danger fw-bold">*</sup></p>
                        </label>
                        <input type="text" class='form-input form-control' placeholder="<?= label('username', 'Username') ?>" id="name" name="name"
                            required>
                    </div>
                    <div class="mb-3 sign-up-verify-number">
                        <label for="exampleFormUsername" class="form-label">
                            <p class="form-lable"><?= label('email', 'Email') ?><sup class="text-danger fw-bold">*</sup>
                            </p>
                        </label>
                        <input type="email" class='form-input form-control' placeholder="<?= label('email', 'Email') ?>" id="email" name="email"
                            required>
                    </div>
                    <div class="mb-3 sign-up-verify-number">
                        <label for="exampleFormUsername" class="form-label">
                            <p class="form-lable"><?= label('password', 'Password') ?><sup
                                    class="text-danger fw-bold">*</sup></p>
                        </label>
                        <span class="password-insert form-control d-flex p-0 align-items-center">
                            <input type="password" class="form-input form-control" id="password" name="password"
                                placeholder="<?= label('password', 'Password') ?>" required>
                            <span class="eye-icons mx-0">
                                <ion-icon name="eye-outline" class="eye-btn password-show">
                                </ion-icon>
                                <ion-icon name="eye-off-outline" class="eye-btn password-hide">
                                </ion-icon>
                            </span>
                        </span>
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
                        <input type="text" placeholder="<?= label('username', 'Username') ?>" name='username' class='form-input form-control'
                            required>
                    </div>
                    <div class="mb-3 sign-up-verify-number">
                        <label for="exampleFormUsername" class="form-label">
                            <p class="form-lable"><?= label('email', 'Email') ?><sup class="text-danger fw-bold">*</sup>
                            </p>
                        </label>
                        <input type="email" placeholder="<?= label('email', 'Email') ?>" name='email' class='form-input form-control' required>
                        <input type="hidden" id="web_fcm" name="web_fcm" value="">
                    </div>
                    <div class="mb-3 sign-up-verify-number">
                        <label for="exampleFormUsername" class="form-label">
                            <p class="form-lable"><?= label('password', 'Password') ?><sup
                                    class="text-danger fw-bold">*</sup></p>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-input form-control" placeholder="<?= label('password', 'Password') ?>" name='password'
                                required>
                            <button class="btn btn-outline-secondary togglePassword" type="button">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3 sign-up-verify-number">
                        <label for="exampleFormUsername" class="form-label">
                            <p class="form-lable"><?= label('confirm_password', 'Confirm Password') ?><sup
                                    class="text-danger fw-bold">*</sup></p>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-input form-control" placeholder="<?= label('confirm_password', 'Confirm Password') ?>" name='confirm_password'
                                required>
                            <button class="btn btn-outline-secondary togglePassword" type="button">
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
                        <input type="text" class='form-input form-control' placeholder="referral code" name="friends_code">
                    </div>
                    <div id='sign-up-error' class='text-center p-3'></div>
                    <button type="submit"
                        class="btn btn-primary Register-btn"><?= label('register', 'Register') ?></button>
                </form>
            </div>
            <!-- Login section-->
            <div class="col-md-6 px-5 login-section" style="display:<?php echo $registrationSectionDisplay; ?>;">
                <h4 class="mb-3 section-title"><?= label('login', 'LOGIN') ?></h4>
                <form action="<?= base_url('home/login') ?>" class='form-submit-event' id="login_form" method="post">
                    <div id="mobile-form" class="mobile-form">
                        <div class="mb-3">
                            <label for="Username" class="form-label">
                                <p class="form-lable"> <?= label('mobile_number', 'Mobile Number') ?> <sup
                                        class="text-danger fw-bold">*</sup></p>
                            </label>
                            <input type="number" class="form-control" name="identity" pattern="\d*" maxlength="16"
                                placeholder="<?= label('mobile_number', 'Mobile number') ?>" <?= (ALLOW_MODIFICATION == 0) ? 'value="9876543210"' : ""; ?>
                                required>
                        </div>
                    </div>
                    <div id="email-form" class="email-form d-none">
                        <div class="mb-3">
                            <label for="Email" class="form-label">
                                <p class="form-lable"> <?= label('email', 'Email') ?> <sup
                                        class="text-danger fw-bold">*</sup></p>
                            </label>
                            <input type="email" class="form-control" name="email" 
                                placeholder="<?= label('email_address', 'Email address') ?>">
                        </div>
                    </div>
                    <?php if ((!empty($system_settings['email_login']) && $system_settings['email_login'] == 1)) { ?>
                    <div class="mb-3 mt-3">
                        <a href="#" id="login-phoneLogin" class="text-decoration-underline login-phone-login d-none"><?= label('login_with_phone', 'Login with Phone?') ?></a>
                    </div>
                    <?php } ?>
                    <div class="mb-3">
                        <label for="setpassword" class="form-label">
                            <p class="form-lable"><?= label('password', 'Password') ?> <sup
                                    class="text-danger fw-bold">*</sup></p>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="fill-password" name="password"
                                placeholder="<?= label('password', 'Password') ?>" value="" required>
                            <button class="btn btn-outline-secondary togglePassword" type="button">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <a href="<?= base_url('#') ?>">
                            <button type="submit"
                                class="submit_btn btn Register-btn submit-btn"><?= label('login', 'Login') ?></button>
                        </a>
                    </div>
                    <div class="d-flex justify-content-between forget-password">
                        <!-- <a id="forgot_password_link">
                            <p class="m-0 pointer"><? //= label('forgot_password', 'Forget Password') 
                            ?>?</p>
                        </a> -->
                        <a href="<?= base_url('register#forget-password-section') ?>" class="forget_password_sec">
                            <p class="m-0 pointer"><?= label('forgot_password', 'Forget Password') ?>?</p>
                        </a>
                    </div>
                    <div class="separator">
                        <span></span>
                        <?= label('OR', 'OR') ?>
                        <span></span>
                    </div>
                    <?php if ((!empty($system_settings['email_login']) && $system_settings['email_login'] == 1)) {
                        ?>
                        <div class="d-flex justify-content-around my-2">
                                            <?php if ((!empty($system_settings['email_login']) && $system_settings['email_login'] == 1)) { ?>
                            <a href="#" id="login-emailLogin-btn" class="emailLogin">
                                <div class="thirdparty-login">
                                    <img src="<?= base_url('assets/front_end/modern/image/pictures/mail.png') ?> "
                                        alt="">
                                    <p class="m-0"><?= label('Email', 'Email') ?></p>
                                </div>
                            </a>

                            <a href="#" id="login-phoneLogin-btn" class="phoneLogin d-none">
                                <div class="thirdparty-login">
                                    <img src="<?= base_url('assets/front_end/modern/image/pictures/telephone.png') ?> "
                                        alt="">
                                    <p class="m-0"><?= label('Phone', 'Phone') ?></p>
                                </div>
                            </a>
                            <?php } ?>
                            <?php if (!empty($system_settings['google_login']) && $system_settings['google_login'] != '' && ($system_settings['google_login'] == 1 || $system_settings['google_login'] = '1')) { ?>
                                <input type="hidden" id="web_fcm" name="web_fcm" value="">
                                <a href="#" id="googleLogin">
                                    <div class="thirdparty-login">
                                        <img src="<?= base_url('assets/front_end/modern/image/pictures/google-logo-9825.png') ?> "
                                            alt="">
                                        <p class="m-0"><?= label('google', 'Google') ?></p>
                                    </div>
                                </a>
                            <?php } ?>
                            <!-- <?php if (!empty($system_settings['facebook_login']) && $system_settings['facebook_login'] != '' && ($system_settings['facebook_login'] == 1 || $system_settings['facebook_login'] = '1')) { ?>
                                <a href="#" id="facebookLogin">
                                    <div class="thirdparty-login">
                                        <img src="<?= base_url('assets/front_end/modern/image/pictures/Facebook_Logo_(2019).png') ?> " alt="">
                                        <p class="m-0"><?= label('Facebook', 'Facebook') ?></p>
                                    </div>
                                </a>
                            <?php } ?> -->
                        </div>
                    <?php }
                    ?>
                    <div class="d-flex justify-content-center">
                        <div class="form-group" id="error_box"></div>
                    </div>
                </form>
            </div>
            <div class="d-md-none d-flex align-items-center my-4 wd-login-divider">
                <p></p>
                <span>OR</span>
                <p></p>
            </div>
            <div class="col-md-6 px-5 text-center Register-text">
                <div class="login-text" id="login-text">
                    <h4 class="mb-3 section-title"><?= label('login', 'LOGIN') ?></h4>
                    <p class="mb-3"><?= label('login_description', 'To access your account and enjoy a seamless shopping experience, simply enter your registered Mobile Number and password in the designated fields. Once logged in, you\'ll have access to your personalized dashboard, order history, saved payment methods, and more.') ?>
                    </p>
                    <button type="button" class="btn login-register-btn login-btn fw-bold"><?= label('login', 'Login') ?></button>
                </div>
                <div class="register-text" style="display:<?php echo $registrationSectionDisplay; ?>;">
                    <h4 class="mb-3 section-title"><?= label('register', 'REGISTER') ?></h4>
                    <p class="mb-3"><?= label('register_description', 'Registering for this site allows you to access your order status and history. Just fill in the fields below, and we\'ll get a new account set up for you in no time. We will only ask you for information necessary to make the purchase process faster and easier.') ?>
                    </p>
                    <button type="button"
                        class="btn login-register-btn register-btn fw-bold cancel_reload"><?= label('register', 'Register') ?></button>
                </div>
            </div>
        </div>
        <!-- forget password section-->
        <!-- <div class="row justify-content-center forget-password-section">
                <div class="col-md-6 px-5">
                    <h4 class="mb-3 section-title"><?= label('forgot_password', 'FORGET PASSWORD') ?></h4>
                    <form id="send_forgot_password_otp_form" method="POST" action="#">
                        <div class="input-group">
                            <label for="exampleFormUsername" class="form-label">
                                <p class="form-lable"><?= label('mobile_number', 'Mobile Number') ?></p>
                            </label>
                            <input type="text" class="form-control" name="mobile_number" id="forgot_password_number" placeholder="Mobile number">
                        </div>
                        <div class="col-12 d-flex justify-content-center pb-4 mt-3">
                            <div id="recaptcha-container-2"></div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <div class="form-group" id="recaptcha-error"></div>
                        </div>
                        <footer class="mt-2">
                            <button type="button" class="btn btn-delivery cancel-btn-forget-password"><?= !empty($this->lang->line('cancel')) ? $this->lang->line('cancel') : 'Cancel' ?></button>
                            <button type="submit" id="forgot_password_send_otp_btn" class="submit_btn btn btn-primary btn-block"><?= !empty($this->lang->line('send_otp')) ? $this->lang->line('send_otp') : 'Send OTP' ?></button>
                        </footer>
                        <div class="d-flex justify-content-center">
                            <div class="form-group" id="forgot_pass_error_box"></div>
                        </div>
                    </form>
                    <form id="verify_forgot_password_otp_form" class="d-none" method="post" action="#">
                        <div class="input-group">
                            <input type="number" id="forgot_password_otp" class="form-control" name="otp" placeholder="OTP" value="" autocomplete="off" required>
                        </div>
                        <div class="input-group">
                            <input type="password" class="form-control" name="new_password" placeholder="New Password" value="" required>
                        </div>
                        <footer class="mt-2">
                            <button type="button" class="btn btn-delivery cancel-btn-forget-password"><?= !empty($this->lang->line('cancel')) ? $this->lang->line('cancel') : 'Cancel' ?></button>
                            <button type="submit" class="submit_btn  btn btn-primary btn-block" id="reset_password_submit_btn"><?= !empty($this->lang->line('submit')) ? $this->lang->line('submit') : 'Submit' ?></button>
                        </footer>
                        <div class="d-flex justify-content-center">
                            <div class="form-group" id="set_password_error_box"></div>
                        </div>
                    </form>
                </div>
            </div> -->
        <section class="row justify-content-center forget-password-section" id="forget-password-section">
            <div class="col-md-6 px-5">
                <h4 class="mb-3 section-title"><?= label('forgot_password', 'FORGET PASSWORD') ?></h4>
                <?php if ((!empty($system_settings['email_login']) && $system_settings['email_login'] == 1)) { ?>
                <div class="mb-3 mt-3">
                    <a href="#" id="forgot-password-email-link" class="text-decoration-underline"><?= label('forgot_password_with_email', 'Forgot password with Email?') ?></a>
                </div>
                <?php } ?>
                <form id="send_forgot_password_otp_form" method="POST" action="#">
                    <input type="hidden" name="forget_password_val" value="1" id="forget_password_val">

                    <div class="input-group">
                        <label for="exampleFormUsername" class="form-label">
                            <p class="form-lable"><?= label('mobile_number', 'Mobile Number') ?></p>
                        </label>
                        <input type="text" class="form-control" name="mobile_number" id="forgot_password_number"
                            placeholder="<?= label('mobile_number', 'Mobile number') ?>">
                    </div>
                    <div class="col-12 d-flex justify-content-center pb-4 mt-3">
                        <div id="recaptcha-container-2"></div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <div class="form-group" id="recaptcha-error"></div>
                    </div>
                    <footer class="mt-2">
                        <button type="button" id="cancel_reload"
                            class="btn btn-delivery cancel-btn-forget-password cancel_reload"><?= !empty($this->lang->line('cancel')) ? $this->lang->line('cancel') : 'Cancel' ?></button>
                        <button type="submit" id="forgot_password_send_otp_btn"
                            class="submit_btn btn btn-primary btn-block"><?= !empty($this->lang->line('send_otp')) ? $this->lang->line('send_otp') : 'Send OTP' ?></button>
                    </footer>
                    <div class="d-flex justify-content-center">
                        <div class="form-group" id="forgot_pass_error_box"></div>
                    </div>
                </form>
                <form id="verify_forgot_password_otp_form" class="d-none" method="post" action="#">
                    <div class="input-group">
                        <input type="number" id="forgot_password_otp" class="form-control" name="otp" placeholder="<?= label('otp', 'OTP') ?>"
                            value="" autocomplete="off" required>
                    </div>
                    <div class="input-group">
                        <input type="password" class="form-control" name="new_password" placeholder="<?= label('new_password', 'New Password') ?>"
                            value="" required>
                        <button class="btn btn-outline-secondary togglePassword" type="button">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                    <footer class="mt-2">
                        <button type="button"
                            class="btn btn-delivery cancel-btn-forget-password"><?= !empty($this->lang->line('cancel')) ? $this->lang->line('cancel') : 'Cancel' ?></button>
                        <button type="submit" class="submit_btn  btn btn-primary btn-block"
                            id="reset_password_submit_btn"><?= !empty($this->lang->line('submit')) ? $this->lang->line('submit') : 'Submit' ?></button>
                    </footer>
                    <div class="d-flex justify-content-center">
                        <div class="form-group" id="set_password_error_box"></div>
                    </div>
                </form>

                <?php if ((!empty($system_settings['email_login']) && $system_settings['email_login'] == 1)) { ?>
                <div class="mb-3 d-none" id="forgot-password-phone-div">
                    <a href="#" id="forgot-password-phone" class="text-decoration-underline"><?= label('forgot_password_with_phone', 'Forgot password with Phone Number?') ?></a>
                </div>
                <?php } ?>
                <form id="forgot-password-email-form" class="d-none" method="post" action="#">
                    <div class="input-group">
                        <label for="forgot_password_email" class="form-label">
                            <p class="form-lable"><?= label('email', 'Email') ?></p>
                        </label>
                        <input type="email" class="form-control" name="forgot_password_email" id="forgot_password_email"
                            placeholder="<?= label('email', 'Email') ?>">
                    </div>
                    <footer class="mt-2">
                        <button type="submit" class="submit_btn btn btn-primary btn-block"
                            id="send_firebase_reset_email_btn"><?= label('send_reset_email', 'Send Reset Email') ?></button>
                    </footer>
                    <div class="d-flex justify-content-center">
                        <div class="form-group" id="forgot_pass_email_error_box"></div>
                    </div>
                </form>
            </div>
        </section>
        <!-- Password Reset Form Section -->
        <form id="resetForm">
            <div class="col-md-6 px-5 text-center" id="resetPasswordSection">
                <h4 class="mb-3 section-title"><?= label('reset_password', 'RESET PASSWORD') ?></h4>
                <p class="mb-3">
                    <?= label('reset_password_description', 'Enter your new password below to reset your account access.') ?>
                </p>

                <div class="mb-3">
                    <div class="input-group">
                        <input type="password" id="newPassword" class="form-control" placeholder="<?= label('enter_new_password', 'Enter new password') ?>"
                            required />
                        <button class="btn btn-outline-secondary togglePassword" type="button">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn fw-bold btn-primary" id="reset_pass_btn"><?= label('reset_password', 'Reset Password') ?></button>
        </form>


    </section>
</main>

<style>
    /* Add your CSS styles here */
    #forget-password-section {
        display: none;
        /* Initially hide the section */
    }

    #forget-password-section.active {
        display: block;
        /* Show the section when it has the 'active' class */
    }

    #resetForm {
        display: none;
        /* Initially hide the reset form */
    }
</style>
