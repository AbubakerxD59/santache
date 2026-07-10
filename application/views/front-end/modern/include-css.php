
<!-- Favicon -->
<?php $favicon = get_settings('web_favicon'); ?>

<link rel="icon" href="<?= base_url($favicon) ?>" type="image/gif" sizes="16x16">
<link rel="stylesheet" href="<?= THEME_ASSETS_URL . 'css/eshop-bundle.css' ?>" />
<link rel="stylesheet" href="<?= THEME_ASSETS_URL . 'css/eshop-bundle-main.css' ?>">
<link rel="stylesheet" href="<?= base_url('assets/admin_old/css/tagify.min.css') ?>">

<!-- ionicons -->
<script src="<?= base_url('assets/front_end/modern/ionicons/dist/ionicons/ionicons.js') ?>"></script>
<script src="<?= base_url('assets/front_end/modern/ionicons/dist/ionicons/index.esm.js') ?>"></script>


<!-- jssocials -->
<link rel="stylesheet" href="<?= base_url("assets/front_end/modern/css/jssocials.css") ?>">

<!-- chat css  -->
<link rel="stylesheet" href="<?= base_url('assets/front_end/modern/css/components.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/front_end/modern/css/dropzone.css') ?>">

<!-- custom css -->
<link rel="stylesheet" href="<?= base_url("assets/front_end/modern/css/utilas.css") ?>">

<?php if (ALLOW_MODIFICATION  == 0) { ?>
    <link rel="stylesheet" href="<?= base_url('assets/front_end/modern/css/colors/default.css') ?>" id="color-switcher">
<?php } else { ?>
    <?php
    $settings = get_settings('web_settings', true);
    $modern_theme_color = (isset($settings['modern_theme_color']) && !empty($settings['modern_theme_color'])) ? $settings['modern_theme_color'] : 'default'; ?>
    <link rel="stylesheet" href="<?= base_url("assets/front_end/modern/css/colors/" . $modern_theme_color . '.css') ?>">
<?php } ?>
<script type="text/javascript">
    <?php
    $currency = get_settings('currency');
    ?>
    base_url = "<?= base_url() ?>";
    currency = "<?= isset($currency) ?>";
    csrfName = "<?= $this->security->get_csrf_token_name() ?>";
    csrfHash = "<?= $this->security->get_csrf_hash() ?>";
</script>
<link rel="stylesheet" href="<?= base_url('assets/front_end/modern/css/custom.css') ?>">
