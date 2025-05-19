<!DOCTYPE html>
<html lang="en">
    <head>
        <?php includeDefault('google-analytics'); ?>
        <title><?php echo CONFIGURATION_SYSTEM_NAME; ?> <?php echo CONFIGURATION_SYSTEM_VERSION; ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no, maximum-scale=1.0, user-scalable=no">
        <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
        <meta name="robots" content="noindex,nofollow">
        <link rel="image_src" href="/public/img/logo.jpg" />
        <link rel="icon" type="image/x-icon" href="/public/img/favicon.ico" />
        <link rel="shortcut icon" type="image/x-icon" href="/public/img/favicon.ico" />
        <!-- vendor css -->
        <link href="/public/lib/font-awesome/css/font-awesome.css" rel="stylesheet">
        <link href="/public/lib/Ionicons/css/ionicons.css" rel="stylesheet">
        <!-- Bracket CSS -->
        <link rel="stylesheet" href="/public/css/bracket.css?ver=<?php echo strtotime(date('Ymd')); ?>">
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    </head>
    <body id="account">
        <div class="d-flex align-items-center justify-content-center ht-100v">
            <img src="/public/img/background-gray.jpeg" class="wd-100p ht-100p object-fit-cover" alt="">
            <div class="overlay-body  d-flex align-items-center justify-content-center">
                <div class="login-wrapper wd-500 wd-xs-350 pd-25 pd-xs-40 rounded bd bd-white-2 bg-black-8">
                    <div class="signin-logo mg-b-40">
                        <img src="/public/img/logo.png" class="img-fluid">
                    </div>
                    <?php require_once('routes.php'); ?>
                </div><!-- login-wrapper -->
            </div><!-- overlay-body -->
        </div><!-- d-flex -->
    </body>
    <script src="/public/lib/jquery/jquery.js"></script>
    <script src="/public/lib/popper/popper.min.js"></script>
    <script src="/public/lib/bootstrap/js/bootstrap.js"></script>
</html>