<!doctype html>
<html lang="en" class="layout-wide customizer-hide" dir="ltr" data-skin="default" data-bs-theme="light" data-assets-path="template" data-template="vertical-menu-template">
    <head>
        <?php includeDefault('google-analytics'); ?>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
        <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
        <meta name="robots" content="noindex, nofollow" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        <title><?php echo CONFIGURATION_SYSTEM_NAME; ?> <?php echo CONFIGURATION_SYSTEM_VERSION; ?></title>
        <meta name="description" content="" />
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="/public/img/favicon/favicon.ico" />
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap" rel="stylesheet" />
        <link rel="stylesheet" href="/public/vendor/fonts/iconify-icons.css" />
        <!-- Core CSS -->
        <!-- build:css assets/vendor/css/theme.css  -->
        <link rel="stylesheet" href="/public/vendor/libs/node-waves/node-waves.css" />
        <link rel="stylesheet" href="/public/vendor/libs/pickr/pickr-themes.css" />
        <link rel="stylesheet" href="/public/vendor/css/core.css" />
        <link rel="stylesheet" href="/public/css/demo.css?ver=<?php echo strtotime(date('Ymd')); ?>" />
        <!-- Vendors CSS -->
        <link rel="stylesheet" href="/public/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
        <!-- endbuild -->
        <!-- Vendor -->
        <link rel="stylesheet" href="/public/vendor/libs/@form-validation/form-validation.css" />
        <!-- Page CSS -->
        <!-- Page -->
        <link rel="stylesheet" href="/public/vendor/css/pages/page-auth.css" />

		<!-- Added Core jQuery -->
		<script src="/public/lib/jquery/jquery.js"></script>

        <!-- Helpers -->
        <script src="/public/vendor/js/helpers.js"></script>
        <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
        <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
        <script src="/public/vendor/js/template-customizer.js"></script>
        <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
        <script src="/public/vendor/js/config.js"></script>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    </head>
    <body id="<?php echo (getVar('controller') ? getVar('controller') : '') . '-' . (getVar('view') ? getVar('view') : ''); ?>">
        <div class="container-xxl">
            <div class="authentication-wrapper authentication-basic container-p-y">
                <div class="authentication-inner py-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="app-brand justify-content-center mb-6">
                            <a href="index.html" class="app-brand-link">
                                    <span class="app-brand-logo demo">
                                        <img src="/public/img/logo.png" class="logo">
                                    </span>
                                </a>
                            </div>
                            <?php require_once('routes.php'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Core JS -->
        <!-- build:js assets/vendor/js/theme.js  -->
        <script src="/public/vendor/libs/jquery/jquery.js"></script>
        <script src="/public/vendor/libs/popper/popper.js"></script>
        <script src="/public/vendor/js/bootstrap.js"></script>
        <script src="/public/vendor/libs/node-waves/node-waves.js"></script>
        <script src="/public/vendor/libs/@algolia/autocomplete-js.js"></script>
        <script src="/public/vendor/libs/pickr/pickr.js"></script>
        <script src="/public/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
        <script src="/public/vendor/libs/hammer/hammer.js"></script>
        <script src="/public/vendor/libs/i18n/i18n.js"></script>
        <script src="/public/vendor/js/menu.js"></script>
        <!-- endbuild -->
        <!-- Vendors JS -->
        <script src="/public/vendor/libs/@form-validation/popular.js"></script>
        <script src="/public/vendor/libs/@form-validation/bootstrap5.js"></script>
        <script src="/public/vendor/libs/@form-validation/auto-focus.js"></script>
        <!-- Main JS -->
        <script src="/public/js/main.js"></script>
        <!-- Page JS -->
        <script src="/public/js/pages-auth.js"></script>

		<!-- Default Javascript - customize script -->
		<script src="/public/js/default.js?ver=<?php echo strtotime(date('Ymd')); ?>"></script>
    </body>
</html>