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
                                                    'login', 'logout', 'all', 'user', 'profile', 'manage', 'security', 'deleteJson', 'importView',
                                                    'forgotPassword', 'resetPassword', 'changePassword', 'verify', 'newPassword', 'resendValidation',
                                                    'attachmentJson','removeAttachmentJson',
                                                    'googleSigninCallback'
                                                   ],
                            'finance'           => [
                                                    'soaImmediate', 'soaScheduled', 'soaSetting', 'soaDownload', 'importSoaDownload', 'importSoaDownloadJson', 'soaEmailTemplate' , 'soaEmailGeneric' 
                                                   ],
                            'collection'        => [
                                                    'all', 'manage'
                                                   ],
                            'report'            => [
                                                    'manual', 'automatic'
                                                   ],
                            'master'            => [
                                                    'topro', 'toproJson',
                                                    'branch', 'branch_json',
                                                    'segment', 'segment_json',
                                                    'handler', 'handler_json',
                                                    'teamLeader', 'teamLeader_json',
                                                    'salesChannel', 'salesChannel_json',
                                                    'intermediary', 'intermediaryJson',
                                                    'classBusiness', 'classBusinessJson',

                                                    'accountRole', 'accountRoleJson',
                                                    'accountStatus', 'accountStatusJson',
                                                    'accountType', 'accountTypeJson',
                                                    'accountTeam', 'accountTeamJson',
                                                    'accountLevel', 'accountLevelJson',
                                                    'accountCompany', 'accountCompanyJson',
                                                    'accountDepartment', 'accountDepartmentJson', 'perDepartmentJson',
                                                    'accountDesignation', 'accountDesignationJson',
                                                    'accountUnitRole', 'accountUnitRoleJson'
                                                   ],
                            'cron'              => [
                                                    'schedule','testEmail'
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
