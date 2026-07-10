<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header mt-4">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h4>System Users</h4>
                </div>
                <div class="col-sm-4 d-flex justify-content-end">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">System Users</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
        <!-- </section>
    <section class="content"> -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <form class="form-horizontal form-submit-event" action="<?= base_url('admin/system_users/update_system_user'); ?>" method="POST" id="add_product_form" enctype="multipart/form-data">
                            <div class="card-body row <?= (isset($fetched_data[0]['id'])) ? 'p-0' : '' ?>">

                                <?php
                                if (isset($fetched_data[0]['id'])) { ?>
                                    <input type='hidden' name='edit_system_user' value="<?= $fetched_data[0]['id'] ?>">
                                <?php    }
                                ?>

                                <div class="<?= (isset($fetched_data[0]['id'])) ? 'col-md-12' : 'col-md-4' ?>">

                                    <!-- form start -->
                                    <div class="form-group ">
                                        <label for="username" class="control-label">Username <span class='text-danger text-sm'>*</span></label>
                                        <div class="col-md-12 mt-2">
                                            <input type="text" class="form-control" name="username" id="username" value="<?= (isset($fetched_data[0]['username'])) ?  $fetched_data[0]['username'] : ' ' ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="mobile" class="control-label mt-2">Mobile <span class='text-danger text-sm'>*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" maxlength="16" oninput="validateNumberInput(this)" class="form-control mt-2" name="mobile" id="mobile" value="<?= (isset($fetched_data[0]['mobile'])) ?  $fetched_data[0]['mobile'] : ' ' ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="control-label mt-2">Email <span class='text-danger text-sm'>*</span></label>
                                        <div class="col-md-12">
                                            <input type="email" class="form-control mt-2" name="email" id="email" value="<?= (isset($fetched_data[0]['email'])) ?  $fetched_data[0]['email'] : ' ' ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="control-label mt-2">Password <span class='text-danger text-sm'>*</span></label>
                                        <?php if (isset($fetched_data[0]['id'])) { ?>
                                            <span class='text-danger'>*Leave blank if there is no change</span>
                                        <?php } ?>
 <div class="col-md-12 input-group">
    <input type="password" class="form-control" name="password" id="password">
    <span class="input-group-text togglePassword" style="cursor: pointer;">
        <i class="fa fa-eye"></i>
    </span>
</div>

                                    </div>
                                    <?php if (!isset($fetched_data[0]['id'])) { ?>
                                        <div class="form-group">
                                            <label for="confirm_password" class="control-label mt-2">Confirm Password <span class='text-danger text-sm'>*</span></label>
                                            <div class="col-md-12 input-group">
                                                <input type="password" class="form-control" name="confirm_password" id="confirm_password">
                                                <span class="input-group-text togglePassword" style="cursor: pointer;">
                                                    <i class="fa fa-eye"></i>
                                                </span>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="form-group">
                                        <label for="role" class="control-label mt-2">Role <span class='text-danger text-sm'>*</span></label>
                                        <div class="col-md-12">
                                            <select class="form-control system-user-role mt-2" name="role">
                                                <option value=" ">---Select role---</option>
                                                <?php
                                                foreach ($user_roles as $key => $value) { ?>
                                                    <option value="<?= $key ?>" <?= (isset($fetched_data[0]['role']) &&  $fetched_data[0]['role'] == $key) ? "Selected" : "" ?>><?= ucwords(str_replace('_', ' ', $value)) ?></option>
                                                <?php
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <?php if (!isset($fetched_data[0]['id'])) { ?>

                                        <div class="form-group mt-4">
                                            <button type="reset" class="btn btn-warning">Reset</button>
                                            <button type="submit" class="btn btn-success" id="submit_btn">Add User</button>
                                        </div>
                                    <?php } ?>
                                </div>

                                <div class="overflow-auto <?= (isset($fetched_data[0]['id'])) ? 'col-md-12 px-0' : 'col-md-8' ?> ">
                                    <?php

                                    if (isset($fetched_data[0]['id'])) {
                                        $user_permissions = isset($fetched_data[0]['permissions']) ?  json_decode($fetched_data[0]['permissions'], 1) : "";
                                    }

                                    $actions = [
                                        'create',
                                        'read',
                                        'update',
                                        'delete'
                                    ];
                                    ?>
                                    <div class="my-3 permission-table-controls <?= (isset($fetched_data[0]['role']) && $fetched_data[0]['role'] == 0) ? 'd-none' : '' ?>">
                                        <button type="button" class="btn btn-sm btn-primary mx-1" id="selectAllPermissions">
                                            <i class="fa fa-check-double"></i> Select All
                                        </button>
                                        <button type="button" class="btn btn-sm btn-secondary mx-1" id="deselectAllPermissions">
                                            <i class="fa fa-times"></i> Deselect All
                                        </button>
                                    </div>
                                    <table class="table permission-table <?= (isset($fetched_data[0]['role']) && $fetched_data[0]['role'] == 0) ? 'd-none' : '' ?>">
                                        <tr>
                                            <th>Module/Permissions</th>
                                            <?php foreach ($actions as $row) { ?>
                                                <th>
                                                    <button type="button" class="btn btn-xs btn-outline-primary mt-1 toggle-column" data-column="<?= $row ?>" title="Toggle all <?= ucfirst($row) ?> permissions">
                                                        <i class="fa fa-exchange-alt"></i>
                                                    </button>
                                                    <br>
                                                    <?= ucfirst($row) ?>
                                                </th>
                                            <?php }
                                            ?>
                                        </tr>
                                        <tbody>
                                            <?php
                                            foreach ($system_modules as $key => $value) {
                                                $flag = 0;
                                            ?>
                                                <tr>
                                                    <td><?= ucwords(str_replace('_', ' ', $key)) ?></td>
                                                    <?php for ($i = 0; $i < count($actions); $i++) {
                                                        //create,update,delete
                                                        $index = array_search($actions[$i], $value);
                                                        if ($index !== false) {
                                                            $checked = '';
                                                            if (isset($user_permissions)) {
                                                                if (isset($user_permissions[$key][$value[$index]])) {
                                                                    $checked = 'checked';
                                                                } else {
                                                                    $checked = '';
                                                                }
                                                            } else {
                                                                $checked = 'checked';
                                                            }
                                                    ?>
                                                            <td> <input type="checkbox" name="<?= 'permissions[' . $key . '][' . $value[$index] . ']' ?>" data-bootstrap-switch data-off-color="danger" class='system-users-switch' data-column="<?= $actions[$i] ?>" data-on-color="success" <?= $checked ?>></td>
                                                        <?php
                                                        } else { ?>
                                                            <td></td>
                                                        <?php   }
                                                        ?>
                                                    <?php } ?>
                                                </tr>
                                            <?php

                                            }

                                            ?>

                                        </tbody>
                                    </table>

                                    <?php if (isset($fetched_data[0]['id'])) { ?>

                                        <div class="form-group px-3">
                                            <button type="submit" class="btn btn-success" id="submit_btn">Update User</button>
                                        </div>
                                    <?php } ?>

                                </div>
                            </div>
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