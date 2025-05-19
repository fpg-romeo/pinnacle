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
                                                    'claim', 'claimSummary', 'importClaim', 'importClaimJson'
                                                   ],
                            'cron'              => [
                                                    'testEmail'
                                                   ],
                            'master'            => [
                                                    'industry', 'industry_json',
                                                    'landingPage', 'landingPage_json',
                                                    'source', 'source_json', 'utm', 'utmJson',
                                                    'serviceStatus', 'serviceStatus_json',
                                                    'currency', 'currency_json',
                                                    'psgType', 'psgType_json', 'psgPackage', 'psgPackageJson','psgItem','psgItemJson','perPsgTypeJson', 'perPsgPackageJson',
                                                    'psgPurchase', 'psgPurchaseJson',
                                                    'paymentTerm', 'paymentTermJson', 'paymentType', 'paymentTypeJson', 
                                                    'contractType', 'contractType_json',
                                                    'country', 'country_json',
                                                    'mediaChannel', 'mediaChannelJson', 'mediaAccountType', 'mediaAccountTypeJson',
                                                    'campaignDuration', 'campaignDuration_json',
                                                    'service', 'service_json',
                                                    'marketingContent', 'marketingContent_json',
                                                    'projectType', 'projectType_json',
                                                    'cms', 'cms_json',
                                                    'websiteMaintenanceType', 'websiteMaintenanceType_json',
                                                    'keywordNumber', 'keywordNumber_json',
                                                    'meetingRoom', 'meetingRoom_json', 'perMeetingTypeJson',
                                                    'accountRole', 'accountRoleJson',
                                                    'accountStatus', 'accountStatusJson',
                                                    'accountType', 'accountTypeJson',
                                                    'accountTeam', 'accountTeamJson',
                                                    'accountLevel', 'accountLevelJson',
                                                    'accountCompany', 'accountCompanyJson',
                                                    'accountDepartment', 'accountDepartmentJson', 'perDepartmentJson',
                                                    'accountDesignation', 'accountDesignationJson',
                                                    'accountUnitRole', 'accountUnitRoleJson',

                                                    'accountRole_json', 'accountStatus_json', 'accountType_json', 'accountTeam_json', 'accountLevel_json', 'accountDepartment_json', 'accountDesignation_json', 'accountUnitRole_json',
                                                    
                                                    'archiveReason', 'archiveReasonJson',
                                                    'holiday', 'holiday_json',
                                                    'companyBranch', 'companyBranch_json',
                                                    'servicePackage', 'servicePackageJson', 'serviceCombo', 'serviceComboJson', 'perPackageJson',
                                                    'creditTerm', 'creditTermJson', 
                                                    'mobileDevice', 'mobileDeviceJson', 
                                                    'advertisingType', 'advertisingTypeJson',
                                                    'clientType', 'clientTypeJson',
                                                    'quickbooksClass', 'quickbooksItem', 'quickbooksPaymentMethod', 'quickbooksTerm', 'quickbooksItemJson', 'perQuickbooksItemJson',
                                                    'psgAttachmentUploadJson', 'psgAttachmentRemoveJson',
                                                    'businessModel', 'businessModelJson', 'businessAttachmentType', 'businessAttachmentTypeJson',
                                                    'pwpType', 'pwpTypeJson',
                                                    'opportunityPackage', 'opportunityPackageJson', 'perOpportunityPackageJson',
                                                    'quickbooksClassJson','perPsgPaymentTermJson',
                                                    'industrySubCategory','industrySubCategoryJson','perIndustryJson',
                                                    'landingPage', 'landingPageJson',
                                                    'ticketPriority', 'ticketPriorityJson',
                                                    'ticketRequestType', 'ticketRequestTypeJson',
                                                    'ticketStatus', 'ticketStatusJson',
                                                    'serviceSubPackage', 'managePackageJson',
                                                    'serviceFreebie', 'freebieJson',
                                                    'subPackageByPackageJson', 'comboBySubPackageJson', 'campaignDurationBySubPackageJson', 'campaignDurationByComboJson', 'paymentTermByCampaignDurationJson', 'discountByPaymentTermJson',
                                                    'getPerPackageJson', 'importPackageJson', 'duplicatePackageJson',
                                                    'period', 'periodJson'
                                                   ],
                            'account'           => [
                                                    'login', 'logout', 'all', 'manage', 'viewJson', 'deleteJson',
                                                    'unit', 'unitTeamJson', 'perTeamJson', 'unitGroupJson', 'perGroupJson', 'unitMemberJson', 
                                                    'forgotPassword', 'resetPassword', 'profile', 
                                                    'googleSigninCallback', 'importOnlineMember',
                                                    'blacklist', 'blacklist_json', 'importView', 'assessment',
                                                    'accountPromotionJson','pusherAuthJson',
                                                    'applicant','manageApplicantJson','importApplicantStatus','attachmentJson','removeAttachmentJson','perDepartmentManagerJson',
                                                    'applicantImportJson','importApplicantAssign','applicantAssignJson',
                                                    'user', 'userJson'
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
