<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Under Maintenance</title>
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <main>
        <section class="py-5">
            <div class="container">
                <div class="row justify-content-center g-4">
                    <div class="col-md-6">
                        <div class="card border-0 shadow p-4 text-center">
                            <h1 class="display-5 fw-bold mb-3">
                                <span class="text-primary">Under Maintenance</span>
                            </h1>
                            <div class="message-content">
                                <?php 
                                $settings = get_settings('system_settings', true);
                                if (isset($settings['is_web_under_maintenance']) && $settings['is_web_under_maintenance'] == 1 && !empty($settings['message_for_web'])) {
                                ?>
                                    <p class="lead"><?= nl2br(htmlspecialchars($settings['message_for_web'])) ?></p>
                                <?php } else { ?>
                                    <p class="lead">We're performing scheduled maintenance. We'll be back soon!</p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <!-- Image Section -->
                    <div class="col-md-6 text-center">
                        <img class="img-fluid" src="<?= THEME_ASSETS_URL . 'demo/maintenance.gif' ?>" alt="Maintenance Image">
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>