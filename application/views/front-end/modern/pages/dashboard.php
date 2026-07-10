<main>
    <section class="container py-5">
        <div class="row">
            <div class="col-md-3 myaccount-navigation py-3">
                <?php $this->load->view('front-end/' . THEME . '/pages/my-account-sidebar') ?>
            </div>
            <div class="col-md-9 padding-16-30">
                <h1><?= !empty($this->lang->line('my_account')) ? $this->lang->line('my_account') : 'Profile' ?></h1>
                <form class="form-submit-event" method="POST" action="<?= base_url('login/update_user') ?>">
                    <!-- Profile Image -->
                    <div class="d-flex justify-content-center mb-3 profile_image">
                        <?php if (!empty($user->image)) { ?>
                            <img class="avatar rounded-circle shadow-sm" src="<?= base_url($user->image) ?>"
                                alt="<?= !empty($this->lang->line('profile_image')) ? $this->lang->line('profile_image') : 'Profile Image' ?>">
                        <?php } else { ?>
                            <img class="avatar rounded-circle shadow-sm" src="<?= base_url() . NO_USER_IMAGE ?>"
                                alt="<?= !empty($this->lang->line('profile_image')) ? $this->lang->line('profile_image') : 'Profile Image' ?>">
                        <?php } ?>
                    </div>

                    <!-- Upload -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <label for="profile_image" class="form-label fw-semibold">
                                <?= !empty($this->lang->line('profile_image')) ? $this->lang->line('profile_image') : 'Profile Image' ?>
                            </label>
                            <input type="file" class="form-control" name="profile_image[]" id="profile_image" />
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="row g-4">

                        <!-- Username -->
                        <div class="col-12">
                            <label for="username" class="form-label fw-semibold">
                                <?= label('username', 'Username') ?>
                            </label>
                            <input type="text" class="form-control gray-700" id="username" name="username"
                                placeholder="Username" value="<?= $user->username ?>">
                        </div>

                        <!-- Email / Mobile -->
                        <div class="col-12">
                            <?php
                            $type = fetch_details('users', ['id' => $_SESSION['user_id']], ['type']);
                            if ($type[0]['type'] == 'email') { ?>

                                <label for="email" class="form-label fw-semibold">
                                    <?= label('email', 'Email Address') ?>
                                    <sup class="text-danger fw-bold">*</sup>
                                </label>
                                <input type="text" class="form-control gray-700" id="email" name="email"
                                    value="<?= $user->email ?>" readonly>

                            <?php } else { ?>

                                <label for="mobile" class="form-label fw-semibold">
                                    <?= !empty($this->lang->line('mobile')) ? $this->lang->line('mobile') : 'Mobile' ?>
                                    <sup class="text-danger fw-bold">*</sup>
                                </label>
                                <input type="phone" class="form-control gray-700" id="mobile" name="mobile"
                                    placeholder="Mobile No. here" value="<?= $user->mobile ?>" readonly>

                            <?php } ?>
                        </div>

                    </div>

                    <div class="mt-4 p-4 password-update-section-div rounded-3 border">

                        <h5 class="fw-bold mb-4"><?= label('password_change', 'Password Change') ?></h5>

                        <?php if ($type[0]['type'] == 'phone' || $type[0]['type'] == 'email') { ?>

                            <div class="row g-4">

                                <!-- Current Password -->
                                <div class="col-12">
                                    <label for="old" class="form-label fw-semibold">
                                        <?= label('current_password', 'Current password') ?>
                                    </label>
                                    <div class="password-insert form-control d-flex p-0 align-items-center">
                                        <input type="password" class="form-control gray-700 border-0" id="old" name="old"
                                            placeholder="Current password">
                                        <span class="eye-icons px-2">
                                            <ion-icon name="eye-outline" class="eye-btn password-show"></ion-icon>
                                            <ion-icon name="eye-off-outline" class="eye-btn password-hide"></ion-icon>
                                        </span>
                                    </div>
                                </div>

                                <!-- New Password -->
                                <div class="col-12">
                                    <label for="new" class="form-label fw-semibold">
                                        <?= label('new_password', 'New password') ?>
                                    </label>
                                    <div class="password-insert form-control d-flex p-0 align-items-center">
                                        <input type="password" class="form-control gray-700 border-0" id="new" name="new"
                                            placeholder="New password">
                                        <span class="eye-icons px-2">
                                            <ion-icon name="eye-outline" class="eye-btn password-show"></ion-icon>
                                            <ion-icon name="eye-off-outline" class="eye-btn password-hide"></ion-icon>
                                        </span>
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-12">
                                    <label for="new_confirm" class="form-label fw-semibold">
                                        <?= label('confirm_new_password', 'Confirm new password') ?>
                                    </label>
                                    <div class="password-insert form-control d-flex p-0 align-items-center">
                                        <input type="password" class="form-control gray-700 border-0" id="new_confirm"
                                            name="new_confirm" placeholder="Confirm password">
                                        <span class="eye-icons px-2">
                                            <ion-icon name="eye-outline" class="eye-btn password-show"></ion-icon>
                                            <ion-icon name="eye-off-outline" class="eye-btn password-hide"></ion-icon>
                                        </span>
                                    </div>
                                </div>

                            </div>

                        <?php } elseif ($type[0]['type'] == 'email') { ?>

                            <!-- Forgot Password Form -->
                            <form id="forgot-password-email-form" class="d-none mt-3" method="post" action="#">

                                <div class="row g-3">

                                    <div class="col-12">
                                        <label for="forgot_password_email" class="form-label fw-semibold">
                                            <?= label('email', 'Email Address') ?>
                                        </label>
                                        <input type="email" class="form-control" name="email" id="forgot_password_email"
                                            placeholder="Enter your email address" readonly value="<?= $user->email ?>">
                                    </div>

                                    <div id="forgot_pass_email_error_box" class="text-center p-2"></div>

                                    <div class="col-12">
                                        <button type="submit" id="send_firebase_reset_email_btn"
                                            class="btn btn-primary w-100">
                                            Send Reset Email
                                        </button>
                                    </div>

                                    <?php if ((!empty($system_settings['email_login']) && $system_settings['email_login'] == 1)) { ?>
                                        <div id="forgot-password-phone-div" class="text-center mt-2 d-none">
                                            <a href="#" id="forgot-password-phone" class="text-decoration-underline">
                                                Forgot password via Phone?
                                            </a>
                                        </div>
                                    <?php } ?>

                                </div>

                            </form>

                        <?php } ?>

                    </div>

                    <button type="submit"
                        class="btn submit-btn mt-4 submit_btn <?= (ALLOW_MODIFICATION == 0) ? 'd-none' : '' ?>">
                        <?= label('save_changes', 'Save Changes') ?>
                    </button>
                </form>
            </div>
        </div>
    </section>
</main>