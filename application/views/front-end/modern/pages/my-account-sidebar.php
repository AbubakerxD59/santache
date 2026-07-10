<?php $current_url = current_url(); ?>
<div class="sidebar-header border-bottom pb-3 mb-4">
    <h4 class="section-title mb-0" style="font-weight: 700; color: #333;">
        <?= label('my_account', 'My Account') ?>
    </h4>
</div>
<ul class="list-unstyled mb-0">
    <a href="<?= base_url('my-account') ?>">
        <li class="<?= ($current_url == base_url('my-account')) ? 'myaccount-navigation-link-selected' : '' ?>">
            <ion-icon name="person-circle-outline" class="me-3" style="font-size: 22px;"></ion-icon>
            <?= label('account_detail', 'Profile') ?>
        </li>
    </a>
    <a href="<?= base_url('my-account/orders') ?>">
        <li class="<?= ($current_url == base_url('my-account/orders')) ? 'myaccount-navigation-link-selected' : '' ?>">
            <ion-icon name="receipt-outline" class="me-3" style="font-size: 22px;"></ion-icon>
            <?= label('orders', 'Orders') ?>
        </li>
    </a>
    <a href="<?= base_url('my-account/notifications') ?>">
        <li class="<?= ($current_url == base_url('my-account/notifications')) ? 'myaccount-navigation-link-selected' : '' ?>">
            <ion-icon name="notifications-outline" class="me-3" style="font-size: 22px;"></ion-icon>
            <?= label('notification', 'Notifications') ?>
        </li>
    </a>
    <a href="<?= base_url('my-account/manage-address') ?>">
        <li class="<?= ($current_url == base_url('my-account/manage-address')) ? 'myaccount-navigation-link-selected' : '' ?>">
            <ion-icon name="location-outline" class="me-3" style="font-size: 22px;"></ion-icon>
            <?= label('address', 'Addresses') ?>
        </li>
    </a>
    <a href="<?= base_url('my-account/chat') ?>">
        <li class="<?= ($current_url == base_url('my-account/chat')) ? 'myaccount-navigation-link-selected' : '' ?>">
            <ion-icon name="chatbubble-ellipses-outline" class="me-3" style="font-size: 22px;"></ion-icon>
            <?= label('chat', 'Chat') ?>
        </li>
    </a>
    <a href="<?= base_url('my-account/favorites') ?>">
        <li class="<?= ($current_url == base_url('my-account/favorites')) ? 'myaccount-navigation-link-selected' : '' ?>">
            <ion-icon name="heart-outline" class="me-3" style="font-size: 22px;"></ion-icon>
            <?= label('wishlist', 'Wishlist') ?>
        </li>
    </a>
    <a href="<?= base_url('my-account/transactions') ?>">
        <li class="<?= ($current_url == base_url('my-account/transactions')) ? 'myaccount-navigation-link-selected' : '' ?>">
            <ion-icon name="swap-horizontal-outline" class="me-3" style="font-size: 22px;"></ion-icon>
            <?= label('transactions', 'Transactions') ?>
        </li>
    </a>
    <a href="<?= base_url('my-account/tickets') ?>">
        <li class="<?= ($current_url == base_url('my-account/tickets')) ? 'myaccount-navigation-link-selected' : '' ?>">
            <ion-icon name="headset-outline" class="me-3" style="font-size: 22px;"></ion-icon>
            <?= label('customer_support', 'Support') ?>
        </li>
    </a>
    <a href="<?= base_url('my-account/refer_and_earn') ?>">
        <li class="<?= ($current_url == base_url('my-account/refer_and_earn')) ? 'myaccount-navigation-link-selected' : '' ?>">
            <ion-icon name="share-social-outline" class="me-3" style="font-size: 22px;"></ion-icon>
            <?= label('refer_and_earn', 'Refer & Earn') ?>
        </li>
    </a>
    <a href="<?= base_url('my-account/wallet') ?>">
        <li class="<?= ($current_url == base_url('my-account/wallet')) ? 'myaccount-navigation-link-selected' : '' ?>">
            <ion-icon name="wallet-outline" class="me-3" style="font-size: 22px;"></ion-icon>
            <?= label('wallet', 'Wallet') ?>
        </li>
    </a>
    <div class="border-top my-3"></div>
    <a href="<?= base_url('my-account/delete_user') ?>">
        <li class="<?= ($current_url == base_url('my-account/delete_user')) ? 'myaccount-navigation-link-selected' : '' ?>">
            <ion-icon name="trash-outline" class="me-3 text-danger" style="font-size: 22px;"></ion-icon>
            <span class="text-danger"><?= label('delete_account', 'Delete Account') ?></span>
        </li>
    </a>
    <a href="#" class="logout-link px-0">
        <li class="mt-2" style="background: rgba(231, 74, 59, 0.05); color: #e74a3b;">
            <ion-icon name="log-out-outline" class="me-3" style="font-size: 22px;"></ion-icon>
            <?= label('logout', 'Logout') ?>
        </li>
    </a>
</ul>
