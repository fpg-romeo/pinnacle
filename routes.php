<?php
    function call($controller, $view) {
        require_once('app/controllers/'.ucfirst($controller).'Controller.php');

        $model_url = 'app/models/'.ucfirst($controller).'.php';
        if(file_exists($model_url)){
            require_once($model_url);
        }
            
        $conClass   = ucfirst($controller).'Controller';
        $controller = new $conClass();
        $controller->{$view}();
    }

    $controllers = array(
                            'account'           => [
                                                    'login', 'logout', 'all', 'all_json' , 'user', 'user_json', 'profile', 'manage', 'security', 'deleteJson', 'importView',
                                                    'forgotPassword', 'resetPassword', 'changePassword', 'verify', 'newPassword', 'resendValidation',
                                                    'attachmentJson','removeAttachmentJson',
                                                    'googleSigninCallback'
                                                   ],
                            'finance'           => [
                                                    'soa-dashboard', 'soaMaster', 'soaMaster_json', 'soaEmailTemplate',
                                                    'soaLetter', 'soaLetterManage', 'soaLogsEmail',
                                                    'importSoaDownload', 'importSoaDownloadJson', 'soaLetter_json', 'soaLetterDownload_json'
                                                   ],
                            'collection'        => [
                                                    'all', 'manage'
                                                   ],
                            'report'            => [
                                                    'test',
                                                    'manual', 'automatic',
                                                    'account'
                                                   ],
                            'master'            => [
                                                    'topro', 'topro_json',
                                                    'branch', 'branch_json',
                                                    'segment', 'segment_json',
                                                    'handler', 'handler_json',
                                                    'teamLeader', 'teamLeader_json',
                                                    'salesChannel', 'salesChannel_json',
                                                    'intermediary', 'intermediary_json',
                                                    'classBusiness', 'classBusiness_json',

                                                    'accountRole', 'accountRoleJson',
                                                    'accountStatus', 'accountStatusJson',
                                                    'accountType', 'accountTypeJson',
                                                    'accountTeam', 'accountTeamJson',
                                                    'accountLevel', 'accountLevelJson',
                                                    'accountCompany', 'accountCompanyJson',
                                                    'accountDepartment', 'accountDepartmentJson', 'perDepartmentJson',
                                                    'accountDesignation', 'accountDesignationJson',
                                                    'accountUnitRole', 'accountUnitRoleJson',
                                                    'syncSOA_json',
                                                   ],
                            'cron'              => [
                                                    'testEmail', 'notificationEmail', 
                                                    'soaCollectionReminder'
                                                   ],
                            'miscellaneous'     => [
                                                    'cronJob', 'faq'
                                                   ],
                            'notification'      => [
                                                    'email', 'emailJson'
                                                   ],
                            'page'              => [
                                                    'error400', 'error401', 'error403', 'error404', 'error500', 'errorModal', 'dashboard', 
                                                    'manual', 'comingSoon', 'underMaintenance'
                                                    ],
                            'email'             => [
                                                    'generateAttachment'
                                                   ]
                        );

    if(array_key_exists($controller, $controllers)){
        if(in_array($view, $controllers[$controller])){
            call($controller, $view);
        }else{
            call('page', 'error404');
        }
    }else{
        call('page', 'error404');
    }
?>  
