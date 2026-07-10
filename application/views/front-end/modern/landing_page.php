<?php
// $web_doctor_brown = get_settings('web_doctor_brown', true);
$settings = get_settings('system_settings', true);
// $web_doctor_brown = (isset($web_doctor_brown) && !empty($web_doctor_brown)) ? $web_doctor_brown['code_bravo'] : '';
?>

<section class='error_404'>
    <div class="content-wrapper">
        <div class="justify-center align-items-center d-flex flex-column">
            <h1 data-shadow='oops!'>OPEN IN APP</h1>
        </div>
        <input type="hidden" name="android_app_store_link" id="android_app_store_link" value="<?= (isset($settings['android_app_store_link']) && !empty($settings['android_app_store_link'])) ? $settings['android_app_store_link'] : '' ?>">
        <input type="hidden" name="ios_app_store_link" id="ios_app_store_link" value="<?= (isset($settings['ios_app_store_link']) && !empty($settings['ios_app_store_link'])) ? $settings['ios_app_store_link'] : '' ?>">
        <input type="hidden" name="scheme" id="scheme" value="<?= (isset($settings['scheme']) && !empty($settings['scheme'])) ? $settings['scheme'] : '' ?>">
        <input type="hidden" name="host" id="host" value="<?= (isset($settings['host']) && !empty($settings['host'])) ? $settings['host'] : '' ?>">
        <input type="hidden" name="web_doctor_brown" id="web_doctor_brown" value="<? $web_doctor_brown ?>">
        <input type="hidden" name="share_slug" id="share_slug" value="true">

        <!-- Password Reset Form Section -->
        <form id="resetForm" style="display: none;">
            <div class="col-md-6 px-5 text-center" id="resetPasswordSection">
                <h4 class="mb-3 section-title">RESET PASSWORD</h4>
                <p class="mb-3">
                    Enter your new password below to reset your account access.
                </p>

                <div class="mb-3">
                    <div class="input-group">
                        <input type="password" id="newPassword" class="form-control" placeholder="Enter new password"
                            required />
                        <button class="btn btn-outline-secondary togglePassword" type="button">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn fw-bold btn-primary" id="reset_pass_btn">Reset Password</button>
        </form>

        <link rel="stylesheet" href="<?= THEME_ASSETS_URL . 'bootstrap-5.3.0-dist/css/bootstrap.min.css' ?>">
        <script src="<?= THEME_ASSETS_URL . 'js/jquery.min.js' ?>"></script>
        
    </div>
</section>
<style>
    .bottom-sheet {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: #fff;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        transform: translateY(100%);
        transition: transform 0.3s ease-out;
        z-index: 1050;
    }

    .bottom-sheet.show {
        transform: translateY(0);
    }
</style>

<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-auth.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Initialize Firebase (you may need to adjust this based on your firebase config)
    <?php
    $firebase_config = get_settings('firebase_settings', true);
    if (!empty($firebase_config)) {
        $api_key = isset($firebase_config['apiKey']) ? $firebase_config['apiKey'] : '';
        $auth_domain = isset($firebase_config['authDomain']) ? $firebase_config['authDomain'] : '';
        $project_id = isset($firebase_config['projectId']) ? $firebase_config['projectId'] : '';
        $storage_bucket = isset($firebase_config['storageBucket']) ? $firebase_config['storageBucket'] : '';
        $messaging_sender_id = isset($firebase_config['messagingSenderId']) ? $firebase_config['messagingSenderId'] : '';
        $app_id = isset($firebase_config['appId']) ? $firebase_config['appId'] : '';
        $measurement_id = isset($firebase_config['measurementId']) ? $firebase_config['measurementId'] : '';
    ?>
        var firebaseConfig = {
            apiKey: "<?= $api_key ?>",
            authDomain: "<?= $auth_domain ?>",
            projectId: "<?= $project_id ?>",
            storageBucket: "<?= $storage_bucket ?>",
            messagingSenderId: "<?= $messaging_sender_id ?>",
            appId: "<?= $app_id ?>",
            measurementId: "<?= $measurement_id ?>"
        };

        if (!firebase.apps.length) {
            firebase.initializeApp(firebaseConfig);
        }
    <?php } ?>

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    const base_url = "<?= base_url() ?>";

</script>
<script src="<?= THEME_ASSETS_URL . 'js/deeplink.js' ?>"></script>