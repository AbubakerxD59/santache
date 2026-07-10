<main>
    <section class="container py-5">
        <div class="row">
            <div class="col-md-3 myaccount-navigation py-3">
                <?php $this->load->view('front-end/' . THEME . '/pages/my-account-sidebar') ?>
            </div>
            <div class="col-md-9 padding-16-30">
                <form class="form-submit-event" method="POST" action="<?= base_url('login/update_user') ?>">
                    <div class="d-flex form-group justify-content-center profile_image">
                        <?php if (!empty($users->image)) { ?>
                            <img class="avatar" src="<?= base_url($users->image) ?>" alt="<?= !empty($this->lang->line('profile_image')) ? $this->lang->line('profile_image') : 'Profile Image' ?>">
                        <?php } else { ?>
                            <img class="avatar" src="<?= base_url() . NO_USER_IMAGE ?>" alt="<?= !empty($this->lang->line('profile_image')) ? $this->lang->line('profile_image') : 'Profile Image' ?>">
                        <?php } ?>
                    </div>
                    <div class="col-md-6 form-group px-0 mb-3">
                        <label for="profile_image" class="col-form-label"><?= !empty($this->lang->line('profile_image')) ? $this->lang->line('profile_image') : 'Profile Image' ?></label>
                        <input type="file" class="form-control" name="profile_image[]" id="profile_image" />
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div>
                                <label for="username" class="form-label">
                                    <?= label('username', 'Username') ?> </label>
                                <input type="text" class="form-control gray-700" id="username" name="username" placeholder="Username" value="<?= $users->username ?>">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <?php if ($identity_column == 'email') { ?>
                                <div>
                                    <label for="email" class="form-label">
                                        <?= label('email', 'Email Address') ?><sup class="text-danger fw-bold"> *</sup>
                                    </label>
                                    <input type="text" class="form-control gray-700" id="email" name="email" value="<?= $users->email ?>" readonly>
                                </div>
                            <?php } else { ?>
                                <div class="form-group col-md-6">
                                    <label for="mobile" class="form-label"><?= !empty($this->lang->line('mobile')) ? $this->lang->line('mobile') : 'Mobile' ?>*</label>
                                    <div>
                                        <input type="phone" class="form-control gray-700" id="mobile" placeholder="Mobile No. here" name="mobile" value="<?= $users->mobile ?>" readonly>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="mt-4 p-4 password-update-section">
                        <h5 class="fw-bold mb-4"><?= label('password_change', 'Password Change') ?></h5>
                        <?php
                        $type = fetch_details('users', ['id' => $_SESSION['user_id']], ['type']);
                        if ($type[0]['type'] == 'phone' || $type[0]['type'] == 'email') {
                        ?>
                          <div>
                                <div class="mb-4">
                                    <label for="old" class="form-label"><?= label('current_password', 'Current password') ?></label>
                                    <span class="password-insert form-control d-flex p-0 align-items-center">
                                        <input type="password" class="form-control gray-700" id="old" name="old" placeholder="Current password">
                                        <span class="eye-icons mx-0">
                                            <ion-icon name="eye-outline" class="eye-btn password-show"></ion-icon>
                                            <ion-icon name="eye-off-outline" class="eye-btn password-hide"></ion-icon>
                                        </span>
                                    </span>
                                </div>
                                <div class="mb-4">
                                    <label for="new" class="form-label"><?= label('new_password', 'New password') ?></label>
                                    <span class="password-insert form-control d-flex p-0 align-items-center">
                                        <input type="password" class="form-control gray-700" id="new" name="new" placeholder="New password">
                                        <span class="eye-icons mx-0">
                                            <ion-icon name="eye-outline" class="eye-btn password-show"></ion-icon>
                                            <ion-icon name="eye-off-outline" class="eye-btn password-hide"></ion-icon>
                                        </span>
                                    </span>
                                </div>
                                <div class="mb-4">
                                    <label for="new_confirm" class="form-label"><?= label('confirm_new_password', 'Confirm new password') ?></label>
                                    <span class="password-insert form-control d-flex p-0 align-items-center">
                                        <input type="password" class="form-control gray-700" id="new_confirm" name="new_confirm" placeholder="Confirm password">
                                        <span class="eye-icons mx-0">
                                            <ion-icon name="eye-outline" class="eye-btn password-show"></ion-icon>
                                            <ion-icon name="eye-off-outline" class="eye-btn password-hide"></ion-icon>
                                        </span>
                                    </span>
                                </div>
                                <!-- Hidden fields to store user type and email for JavaScript -->
                                <input type="hidden" id="user_type" value="<?= $type[0]['type'] ?>">
                                <input type="hidden" id="user_email" value="<?= $users->email ?>">
                            </div>
                        <?php } ?>
                    </div>
                    <button type="submit" class="btn submit-btn mt-4 submit_btn"><?= label('save_changes', 'Save Changes') ?></button>
                </form>
            </div>
        </div>
    </section>
</main>