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
    <body id="<?php echo (getVar('controller') ? getVar('controller') : '').'-'.(getVar('view') ? getVar('view') : ''); ?>">
        <div class="br-logo">
            <a href="/">
                <img src="/public/img/logo.png" class="logo">
            </a>
        </div>
        <div class="br-sideleft overflow-y-auto">
            <label class="sidebar-label pd-x-10 mg-t-20 op-3">MENU</label>
            <ul class="br-sideleft-menu">
                <li class="br-menu-item">
                    <a href="/dashboard" class="br-menu-link <?php activeDashboard('active'); ?>">
                        <i class="fa fa-pie-chart tx-20"></i>
                        <span class="menu-item-label">Dashboard</span>
                    </a>
                </li>
            </ul>

            <label class="sidebar-label pd-x-10 mg-t-20 op-3">MODULES</label>  
            <ul class="br-sideleft-menu">
                <li class="br-menu-item">
                    <a href="#" class="br-menu-link with-sub <?php activeView(['gcash'], ['claim','claim-summary','policy'], 'active'); ?>">
                        <i class="fa fa-ioxhost tx-20"></i>
                        <span class="menu-item-label">GCash</span>
                    </a>
                    <ul class="br-menu-sub">
                        <li class="sub-item"><a href="/gcash/claim-summary/1/" class="sub-link <?php activeView(['gcash'], ['claim','claim-summary'], 'active'); ?>">Claims</a></li>  
                        <li class="sub-item"><a href="/gcash/policy/1/" class="sub-link <?php activeView(['gcash'], ['policy'], 'active'); ?>">Issued Policy</a></li>
                    </ul>
                </li>  
            </ul>

            <label class="sidebar-label pd-x-10 mg-t-20 op-3">MAINTENANCE</label>
            <ul class="br-sideleft-menu">        
                <li class="br-menu-item">
                    <a href="#" class="br-menu-link with-sub 
                        <?php activeView(['master'], [
                                                      'account-department', 'account-designation', 'account-type', 'account-team', 'account-level', 'account-role', 'account-status'
                                                      ], 'active'); 
                        ?>
                    ">
                        <i class="fa fa-cogs tx-18"></i>
                        <span class="menu-item-label">Master</span>
                    </a>
                    <ul class="br-menu-sub">
                        <li class="sub-item">
                            <a href="" class="sub-link <?php activeView(['master'], ['account-department', 'account-designation', 'account-type', 'account-team', 'account-level', 'account-role', 'account-status'], 'active'); ?>">Account</a>
                            <ul>
                                <li class="sub-item"><a href="/master/account-department" class="sub-link <?php activeView(['master'], ['account-department'], 'active'); ?>">Department</a></li>
                                <li class="sub-item"><a href="/master/account-designation" class="sub-link <?php activeView(['master'], ['account-designation'], 'active'); ?>">Designation</a></li>
                                <li class="sub-item"><a href="/master/account-type" class="sub-link <?php activeView(['master'], ['account-type'], 'active'); ?>">Type</a></li>
                                <li class="sub-item"><a href="/master/account-team" class="sub-link <?php activeView(['master'], ['account-team'], 'active'); ?>">Team</a></li>
                                <li class="sub-item"><a href="/master/account-level" class="sub-link <?php activeView(['master'], ['account-level'], 'active'); ?>">Level</a></li>
                                <li class="sub-item"><a href="/master/account-role" class="sub-link <?php activeView(['master'], ['account-role'], 'active'); ?>">Role</a></li>
                                <li class="sub-item"><a href="/master/account-status" class="sub-link <?php activeView(['master'], ['account-status'], 'active'); ?>">Status</a></li>
                            </ul>
                        </li> 
                    </ul>
                </li>
            </ul>
            <hr>
            <ul class="br-sideleft-menu">
                <li class="br-menu-item">
                    <a href="#" class="br-menu-link with-sub <?php activeView(['account'], ['all', 'manage', 'view'], 'active'); ?>">
                        <i class="fa fa-users tx-20"></i>
                        <span class="menu-item-label">Human Resources</span>
                    </a>
                    <ul class="br-menu-sub">
                        <li class="sub-item"><a href="/account/all/1" class="sub-link <?php activeView(['account'], ['all', 'manage', 'view'], 'active'); ?>">Employee Record</a></li>
                    </ul>
                </li>   
            </ul>
            <hr>

            <ul class="br-sideleft-menu">
                <li class="br-menu-item">
                    <a href="#" class="br-menu-link with-sub <?php activeView(['notification', 'miscellaneous', 'company', 'finance'], ['tv', 'email', 'database', 'cron-job', 'owner-manage', 'quickbooks-token'], 'active'); ?>">
                        <i class="fa fa-sliders tx-20"></i>
                        <span class="menu-item-label">Control Panel</span>
                    </a>
                    <ul class="br-menu-sub">
                        <li class="sub-item"><a href="/notification/email/1" class="sub-link <?php activeView(['notification'], ['email'], 'active'); ?>">Email Notification</a></li>
                        <li class="sub-item"><a href="/miscellaneous/cron-job" class="sub-link <?php activeView(['miscellaneous'], ['cron-job'], 'active'); ?>">Cron Jobs</a></li>
                        <li class="sub-item"><a href="/miscellaneous/database" class="sub-link <?php activeView(['miscellaneous'], ['database'], 'active'); ?>">Database Backup</a></li>
                    </ul>
                </li>  
            </ul>
        </div>

        <div class="br-header">
            <div class="br-header-left">
                <div class="navicon-left hidden-md-down"><a id="btnLeftMenu" href=""><i class="icon ion-navicon-round"></i></a></div>
                <div class="navicon-left hidden-lg-up"><a id="btnLeftMenuMobile" href=""><i class="icon ion-navicon-round"></i></a></div>
                    <!--
                    <form id="form-search" method="post" action="/search/result/">
                        <div class="input-group transition pd-y-10">
                            <input name="keyword_search" type="text" class="form-control" placeholder="Search" required>
                            <span class="input-group-btn">
                                <button name="submit-search" type="submit" class="btn btn-secondary"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </form>
                    -->
                <?php echo '<h1 class="tx-danger mg-0 mg-t-10 mg-l-10">'.serverCurrent().'</h1>'; ?>
            </div>
            
            <div class="br-header-right">
                <nav class="nav">
                    <!--
                    <div class="dropdown">
                        <a href="" class="nav-link pd-x-7 pos-relative" data-toggle="dropdown">
                            <i class="icon ion-ios-bell-outline tx-24"></i>
                            <span class="square-8 bg-danger pos-absolute t-15 r-5 rounded-circle"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-header">
                            <div class="dropdown-menu-label">
                                <label>Notifications</label>
                                <a href="">Mark All as Read</a>
                            </div>
                            <div class="media-list">
                                <a href="" class="media-list-link read">
                                    <div class="media">
                                        <img src="/public/img/no-photo.jpg" alt="">
                                        <div class="media-body">
                                            <p class="noti-text"><strong>Juan Dela Cruz</strong> lorem ipsum dolor sit amet.</p>
                                            <span>December 03, 2022 8:45am</span>
                                        </div>
                                    </div>
                                </a>
                                <a href="" class="media-list-link read">
                                    <div class="media">
                                        <img src="/public/img/no-photo.jpg" alt="">
                                        <div class="media-body">
                                            <p class="noti-text"><strong>Juan Dela Cruz</strong> Lorem ipsum dolor sit amet <strong>Consectetur Adipiscing Elit</strong></p>
                                            <span>December 02, 2022 12:44am</span>
                                        </div>
                                    </div>
                                </a>
                                <a href="" class="media-list-link read">
                                    <div class="media">
                                        <img src="/public/img/no-photo.jpg" alt="">
                                        <div class="media-body">
                                            <p class="noti-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit <strong>Lorem Ipsum</strong></p>
                                            <span>December 01, 2022 10:20pm</span>
                                        </div>
                                    </div>
                                </a>
                                <a href="" class="media-list-link read">
                                    <div class="media">
                                        <img src="/public/img/no-photo.jpg" alt="">
                                        <div class="media-body">
                                            <p class="noti-text"><strong>Juan Dela Cruz</strong> lorem ipsum dolor sit amet, consectetur adipiscing elit <strong>Pellentesque Ornare Libero</strong></p>
                                            <span>December 01, 2022 6:08pm</span>
                                        </div>
                                    </div>
                                </a>
                                <div class="dropdown-footer">
                                    <a href="/notification/prompt/1"><i class="fa fa-angle-down"></i> Show All Notifications</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    -->
                    
                    <div class="dropdown">
                        <a href="" class="nav-link nav-link-profile" data-toggle="dropdown">
                            <span class="logged-name hidden-md-down">Hi <b><?php echo ACCOUNT_ALIAS; ?></b>!, 
                                <?php 
                                    if(IS_BIRTHDAY == true){
                                        // echo 'Happy Birthday!! &#127828';
                                        echo 'Happy Birthday  🎉🎁🍰🍾';
                                    }else{
                                        echo loginGreetings(); 
                                    }
                                    //cursor: url('cake.png'), auto;
                                ?>
                            </span>
                            <img src="<?php echo ACCOUNT_PHOTO; ?>" class="wd-32 rounded-circle" alt="">
                            <span class="square-10 bg-success"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-header wd-250" id="profile-dropdown-menu">
                            <div class="tx-center">
                                <a href=""><img src="<?php echo ACCOUNT_PHOTO; ?>" class="wd-80 rounded-circle" alt=""></a>
                                <h6 class="logged-fullname"><?php echo ACCOUNT_ALIAS; ?></h6>
                                <p><?php echo ACCOUNT_EMAIL; ?></p>
                            </div>
                            <hr>
                            <ul class="list-unstyled user-profile-nav">
                                <li><a href="#" class="btn-view-online-member" id="online-member" data-toggle="modal" data-target="#modal-online-member"><i class=" fa fa-circle fa-lg tx-success"></i> &nbsp; Online Member</a></li>
                                <hr>
                                <li><a href="/account/profile/<?php echo idEncrypt(ACCOUNT_ID) ?>"><i class="fa fa-user-circle fa-lg"></i> &nbsp; My Profile</a></li>
                                <li><a href="/logout"><i class="fa fa-sign-out fa-lg"></i> &nbsp; Sign Out</a></li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <?php require_once('routes.php'); ?>        
    </body>
</html>

<div id="modal-online-member" class="modal fade">
    <div class="modal-dialog modal-dialog-vertical-center modal-sm" role="document">
        <div class="modal-content bd-0">
            <div class="modal-header pd-y-15 pd-x-20">
                <h6 class="mg-b-0 tx-uppercase tx-primary tx-bold modal-title tx-center">ONLINE MEMBER</h6>
            </div>
            <div class="modal-body pd-0">
                <table class="table table-responsive d-md-table online-member-table mg-b-0 tx-12">
                   
                    <tbody id="onlineUsersTableBody">
                        <!-- Online users will be dynamically added here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div id="modal-page-pagination" class="modal fade">
    <div class="modal-dialog modal-dialog-vertical-center modal-lg" role="document">
        <div class="modal-content bd-0">
            <div class="modal-body pd-25">
                Page Pagination
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function(){
        $('.sub-dropdown-menu').prev().find('.sub-link').each(function( index ) {
            if(!$(this).hasClass('active')){
                $(this).parent().next().hide();
            }
        });

        $('.br-menu-sub-with-sub li').on('click',function(){
            id = $(this).data('id');
            if ( $(this).hasClass('active-sub-dropdown') == true ) {
                $('#dropdown-'+id).slideUp();
                $(this).removeClass('active-sub-dropdown')
            }else{
                $('#dropdown-'+id).slideDown();
                $(this).addClass('active-sub-dropdown')
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#profile-dropdown-menu').on('click', function(e){
            e.stopPropagation();
        });

        $('#online-member').on('click', function(e){
            e.preventDefault();
            $('#profile-dropdown-menu').removeClass('show');
            var display        = $('#modal-online-member .modal-body');

            var display = $('#modal-online-member .modal-body');

            $('#modal-online-member').modal('show');
        });
    });
</script>