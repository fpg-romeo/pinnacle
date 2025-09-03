<?php
    class UnderwritingController{

        public function __construct() {
            checkLoggedIn('true');
        }

        public function renewalSummary(){
            includeModel(['Account']);

            $data                   = array();
            $CONFIGURATION          = Configuration::general();
            $account_id             = urldecode(getVar('account_id'));
            $account_id             = $account_id == "all" ? '' : $account_id;

            $data['summary']        = Underwriting::getPolicySummary($account_id, pagination('start'), pagination('limit'));
            $data['total_record']   = recastArray(Underwriting::getPolicySummary($account_id, '', '', 'count'))['count'] ?? 0;
            $data['total_page']     = pagination('total', $data['total_record']);
            $data['accounts']       = Account::getByStatusId($CONFIGURATION['ACCOUNT_STATUS_ACTIVE']);

            views('underwriting.renewal-summary', $data); 
        }
    }
?>