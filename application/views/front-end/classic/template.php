<!DOCTYPE html>
<html lang="en">

<?php
$settings = get_settings('web_settings', true);
$system_settings = get_settings('system_settings', true);
$primary_colour = (isset($settings['primary_color']) && !empty($settings['primary_color'])) ?  $settings['primary_color'] : '#FF6A65';
$secondary_colour = (isset($settings['secondary_color']) && !empty($settings['secondary_color'])) ?  $settings['secondary_color'] : '#FF6A65';
$font_color = (isset($settings['font_color']) && !empty($settings['font_color'])) ?  $settings['font_color'] : '#FF00EE';

?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <meta name="keywords" content='<?= $keywords ?>'>
    <meta name="description" content='<?= $description ?>'>
    <meta name="product_image" property="og:image" content='<?= isset($product_image) ? $product_image : '' ?>'>
    <meta property="og:image:type" content="image/jpg,png,jpeg,gif,bmp,eps">
    <meta property="og:image:width" content="1024">
    <meta property="og:image:height" content="1024">
    <?php echo '<link rel="canonical" href="' . base_url($this->uri->uri_string()) . '" />';
    $cookie_lang = $this->input->cookie('language', TRUE);
    $path = $is_rtl = "";
    if (!empty($cookie_lang)) {
        $language = get_languages(0, $cookie_lang, 0, 1);
        if (!empty($language)) {
            $path = ($language[0]['is_rtl'] == 1) ? 'rtl/' : "";
            $is_rtl =  ($language[0]['is_rtl'] == 1) ? true : false;
        }
    } else {
        /* read the default language */
        $lang = $this->config->item('language');
        $language = get_languages(0, $lang, 0, 1);
        if (!empty($language)) {
            $path = ($language[0]['is_rtl'] == 1) ? 'rtl/' : "";
            $is_rtl =  ($language[0]['is_rtl'] == 1) ? true : false;
        }
    }
    $data['is_rtl'] = $is_rtl;

    
    $this->load->view('front-end/' . THEME . '/include-css', $data); ?>

<style>
        * {
            --primary-color: <?= $primary_colour ?>;
            --secondary-color: <?= $secondary_colour ?>;
            --font-color: <?= $font_color ?>;
        }
    </style>
</head>
<?php $settings = get_settings('system_settings', true);
if (isset($settings) && isset($settings['is_web_under_maintenance']) && ($settings['is_web_under_maintenance'] != '') && ($settings['is_web_under_maintenance'] == 1)) { ?>

    <body id="body" data-is-rtl='<?= $is_rtl ?>'>
        <?php $this->load->view('front-end/' . THEME . '/pages/' . $main_page); ?>
        <?php $this->load->view('front-end/' . THEME . '/include-script'); ?>
    </body>
<?php } else {
?>

    <body id="body" data-is-rtl='<?= $is_rtl ?>'>
        <?php $this->load->view('front-end/' . THEME . '/header'); ?>
        <?php $this->load->view('front-end/' . THEME . '/pages/' . $main_page); ?>
        <?php $this->load->view('front-end/' . THEME . '/footer'); ?>
        <?php $this->load->view('front-end/' . THEME . '/include-script'); ?>
    </body>
<?php } ?>

</html>