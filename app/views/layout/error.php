<?php checkLoggedIn('true'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php includeDefault('google-analytics'); ?>
        <title><?php echo CONFIGURATION_SYSTEM_NAME; ?> <?php echo CONFIGURATION_SYSTEM_VERSION; ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1.0, user-scalable=no">
        <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
        <meta name="robots" content="noindex,nofollow">
        <link rel="image_src" href="/public/img/logo.jpg" />
        <link rel="icon" type="image/x-icon" href="/public/img/favicon.ico" />
        <link rel="shortcut icon" type="image/x-icon" href="/public/img/favicon.ico" />
        <!-- vendor css -->
        <link href="/public/lib/font-awesome/css/font-awesome.css" rel="stylesheet">
        <link href="/public/lib/medium-editor/default.css" rel="stylesheet">
        <!-- Bracket CSS -->
        <link rel="stylesheet" href="/public/css/bracket.css?ver=<?php echo strtotime(date('Ymd')); ?>">

        <script src="/public/lib/jquery/jquery.js"></script>
        <script src="/public/lib/bootstrap/js/bootstrap.js"></script>
    </head>
    <body id="<?php echo (getVar('controller') ? getVar('controller') : '').'-'.(getVar('view') ? getVar('view') : ''); ?>">
        <?php require_once('routes.php'); ?>        
    </body>
</html>