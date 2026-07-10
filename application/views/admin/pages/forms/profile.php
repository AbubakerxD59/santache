<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <!-- form start -->
                        <form class="form-submit-event" action="<?= base_url('admin/login/update_user') ?>" method="POST">
                            <div class="card-body">
                                <div class="form-group row mb-4">
                                    <label for="username" class="col-sm-2 col-form-label">Username <span class='text-danger text-xs'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="username" placeholder="Type Username here" name="username" value="<?= $users->username ?>">
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <?php if ($identity_column == 'email') { ?>
                                        <label for="email" class="col-sm-2 col-form-label">Email <span class='text-danger text-xs'>*</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="email" placeholder="Type Email here" name="email" value="<?= $users->email ?>">
                                        </div>
                                    <?php } else { ?>
                                        <label for="mobile" class="col-sm-2 col-form-label">Mobile <span class='text-danger text-xs'>*</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="mobile" placeholder="Type Mobile Number here" name="mobile" maxlength="16" oninput="validateNumberInput(this)" value="<?= $users->mobile ?>">
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row mb-4 position-relative">
                                    <label for="old" class="col-sm-2 col-form-label">Old Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control pr-5" id="old" placeholder="Type Old Password here" name="old">
                                        <ion-icon name="eye-outline" class="password-toggle position-absolute" data-target="old"></ion-icon>
                                    </div>
                                </div>
                                <div class="form-group row mb-4 position-relative">
                                    <label for="new" class="col-sm-2 col-form-label">New Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control pr-5" id="new" placeholder="Type New Password here" name="new">
                                        <ion-icon name="eye-outline" class="password-toggle position-absolute" data-target="new"></ion-icon>
                                    </div>
                                </div>
                                <div class="form-group row mb-4 position-relative">
                                    <label for="new_confirm" class="col-sm-2 col-form-label">Confirm New Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control pr-5" id="new_confirm" placeholder="Type Confirm Password here" name="new_confirm">
                                        <ion-icon name="eye-outline" class="password-toggle position-absolute" data-target="new_confirm"></ion-icon>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success" id="submit_btn">Update Profile</button>
                                </div>
                            </div>
                            <!-- /.card-footer -->
                        </form>
                    </div>
                    <!--/.card-->
                </div>
                <!--/.col-md-12-->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>