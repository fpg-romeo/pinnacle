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
                             'gcash'             => [
                                                    'claim', 'claimSummary', 'importClaimUpload', 'importClaimJson', 'importClaimView', 
                                                    'declaration', 'declarationJson', 'importDeclarationView', 'importDeclarationManage'
                                                   ],
                            'cron'              => [
                                                    'testEmail'
                                                   ],
                            'master'            => [
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
                            'account'           => [
                                                    'login', 'logout', 'all', 'manage', 'deleteJson',
                                                    'perTeamJson', 'perGroupJson', 
                                                    'forgotPassword', 'resetPassword', 'profile', 
                                                    'googleSigninCallback', 'importOnlineMember',
                                                     'importView',
                                                    'attachmentJson','removeAttachmentJson','perDepartmentManagerJson'
                                                   ],
                            'page'              => [
                                                    'error400', 'error401', 'error403', 'error404', 'error500', 'errorModal', 'dashboard', 
                                                    'manual', 'comingSoon', 'underMaintenance'
                                                   ],
                            'notification'      => ['email', 'emailJson'],
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
