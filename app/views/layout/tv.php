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
        <link href="/public/lib/Ionicons/css/ionicons.css" rel="stylesheet">
        <link href="/public/lib/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
        <link href="/public/lib/jquery-switchbutton/jquery.switchButton.css" rel="stylesheet">

        <link href="/public/lib/highlightjs/github.css" rel="stylesheet">

        <link href="/public/lib/medium-editor/medium-editor.css" rel="stylesheet">
        <link href="/public/lib/medium-editor/default.css" rel="stylesheet">
        <link href="/public/lib/summernote/summernote-bs4.css" rel="stylesheet">

        <link href="/public/lib/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
        <link href="/public/lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">

        <link href="/public/lib/select2/css/select2.min.css" rel="stylesheet">

        <link href="/public/lib/jquery.steps/jquery.steps.css" rel="stylesheet">
        
        <!-- Bracket CSS -->
        <link rel="stylesheet" href="/public/css/bracket.css?ver=<?php echo strtotime(date('Ymd')); ?>">
        <link rel="stylesheet" href="/public/lib/JiSlider/JiSlider.css">

        <script src="/public/lib/jquery/jquery.js"></script>
        <script src="/public/lib/popper/popper.min.js"></script>
        <script src="/public/lib/bootstrap/js/bootstrap.js"></script>
        <script src="/public/lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
        <script src="/public/lib/moment/moment.js"></script>
        <script src="/public/lib/summernote/summernote-bs4.js"></script>
        <script src="/public/lib/jquery-ui/jquery-ui.js"></script>
        <script src="/public/lib/jquery-switchbutton/jquery.switchButton.js"></script>
        <script src="/public/lib/peity/jquery.peity.js"></script>
        <script src="/public/lib/select2/js/select2.min.js"></script>
        <script src="/public/js/formatCurrency-1.4.0.js"></script>  
        <script src="/public/js/bracket.js"></script>   

        <script src="/public/js/default.js?ver=<?php echo strtotime(date('Ymd')); ?>"></script>   
    </head>
    <body id="tv">
        <?php require_once('routes.php'); ?>
    </body>
    <script src="/public/lib/JiSlider/JiSlider.js"></script>
</html>