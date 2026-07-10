<?php $current_version = get_current_version(); ?>
<nav class="layout-navbar container-fluid navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex flex-wrap align-items-center mt-auto" id="navbar-collapse">

        <ul class="navbar-nav">

            <li class="nav-item my-auto version-media">
                <span class="badge bg-primary">v <?= (isset($current_version) && !empty($current_version)) ? $current_version : '1.0' ?></span>
            </li>
            <?php
            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            ?>
                <li class="nav-item my-auto m-2">
                    <span class="badge bg-danger">Demo mode</span>
                </li>
            <?php } ?>
        </ul>


       <ul class="navbar-nav flex-row align-items-center ms-auto flex-wrap">

    <!-- GOOGLE TRANSLATE (Only ONE ID) -->
    <li class="nav-item mx-2">
        <div id="google_translate_element"></div>
    </li>

    <!-- NOTIFICATIONS -->
    <?php
    $notifications = fetch_details('system_notification', NULL, '*', '3', '0', 'read_by', 'ASC');
    $count_noti = fetch_details('system_notification', ["read_by" => 0], 'count(id) as total');
    ?>

    <li class="nav-item dropdown mx-2">
        <a href="javascript:void(0);" id="notification_count"
           class="nav-link notification-toggle nav-link-lg"
           data-toggle="dropdown">
            <i class="fas fa-bell fa-2x"></i>
            <span class="badge bg-danger navbar-badge order_notification">
                <?= $count_noti[0]['total'] ?>
            </span>
        </a>

        <!-- Dynamic Notification List -->
        <div id="list" class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-2"></div>

        <!-- Fixed Dropdown -->
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-2">
            <?php if ($this->ion_auth->is_admin()) { ?>

                <a class="dropdown-item" href="#">
                    <div class="d-flex"></div>
                </a>

                <a class="dropdown-item">
                    <i class="fas fa-user-circle mr-2 fa-lg"></i> Profile
                </a>

                <a href="<?= base_url('admin/home/logout') ?>" class="dropdown-item">
                    <i class="fa fa-sign-out-alt mr-2 fa-lg"></i> Log Out
                </a>

            <?php } else { ?>

                <a href="#" class="dropdown-item">
                    Welcome <b><?= ucfirst($this->ion_auth->user()->row()->username) ?></b>!
                </a>

                <a href="<?= base_url('delivery_boy/home/profile') ?>" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> Profile
                </a>

                <a href="<?= base_url('delivery_boy/home/logout') ?>" class="dropdown-item">
                    <i class="fa fa-sign-out-alt mr-2"></i> Log Out
                </a>

            <?php } ?>
        </div>
    </li>

    <!-- USER PROFILE -->
    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <div class="form-control mr-sm-2 d-flex p-0">
                <div class="avatar avatar-online">
                    <img src="<?= base_url('/assets/admin/img/avatars/Admin_Profile.png') ?>"
                         class="w-px-40 h-auto rounded-circle avatar avatar-online" />
                </div>

                <b>
                    <p class="image-text">
                        Hi, <?= ucfirst($this->ion_auth->user()->row()->username) ?>
                    </p>
                </b>
            </div>
        </a>

        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

            <?php if ($this->ion_auth->is_admin()) { ?>

                <a class="dropdown-item" href="#">
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                                <img src="<?= base_url('/assets/admin/img/avatars/Admin_Profile.png') ?>"
                                     class="w-px-40 h-auto rounded-circle avatar avatar-online" />
                            </div>
                        </div>

                        <div class="flex-grow-1 p-2">
                            <?php $username = $this->ion_auth->user()->row()->username; ?>
                            <p class="image-text" title="<?= ucfirst($username) ?>">
                                Hi, <?= ucfirst(strlen($username) > 15 ? substr($username, 0, 12) . '...' : $username) ?>
                            </p>
                        </div>
                    </div>
                </a>

                <div class="dropdown-divider"></div>

                <a href="<?= base_url('admin/home/profile') ?>" class="dropdown-item">
                    <i class="fas fa-user-circle mr-2 fa-lg"></i> Profile
                </a>

                <a href="<?= base_url('admin/home/logout') ?>" class="dropdown-item">
                    <i class="fa fa-sign-out-alt mr-2 fa-lg"></i> Log Out
                </a>

            <?php } else { ?>

                <a href="#" class="dropdown-item">
                    Welcome <b><?= ucfirst($this->ion_auth->user()->row()->username) ?></b>!
                </a>

                <a href="<?= base_url('delivery_boy/home/profile') ?>" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> Profile
                </a>

                <a href="<?= base_url('delivery_boy/home/logout') ?>" class="dropdown-item">
                    <i class="fa fa-sign-out-alt mr-2"></i> Log Out
                </a>

            <?php } ?>

        </div>
    </li>
</ul>

    </div>
</nav>