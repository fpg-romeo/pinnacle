<?php
    class FinanceController{
        
        public function __construct() {
            checkLoggedIn('true');
        }

        public function soaImmediate(){
            $data = array();

            views('finance.soa-immediate', $data);  
        } 

        public function soaScheduled(){
            $data = array();

            views('finance.soa-scheduled', $data);  
        } 

        public function soaSetting(){
            $data = array();

            views('finance.soa-setting', $data);  
        } 

        public function soaDownload(){
            $data = array();

            views('finance.soa-download', $data);  
        } 

        public function importSoaDownload(){
            $data = array();

            views('finance.import-soa-download', $data);  
        } 

        public function importSoaDownloadJson(){
            $result = array();

            echo json_encode($result); 
        } 

        public function soaEmailTemplate(){
            $data = array();

            views('finance.soa-email-template', $data);  
        } 

        public function soaEmailGeneric(){
            $data = array();

            views('finance.soa-email-generic', $data);  
        } 

        public function soaMasterlist(){
            $data = array();
            includeModel('Master');

            $keyword                    = urldecode(getVar('keyword'));
            $data['masterlists']        = Finance::getAllMasterlists($keyword, pagination('start'), pagination('limit'));
            $data['total_record']       = Finance::countAllMasterlist($keyword);
            $data['total_page']         = pagination('total', $data['total_record']); 

            $data['intermediaries']     = Master::getActiveIntermediary();
            $data['branches']           = Master::getActiveBranches();
            $data['segments']           = Master::getActiveSegments();
            $data['sales_channels']     = Master::getActiveSalesChannels();
            $data['topros']             = Master::getActiveTOPROs();
            $data['cobs']               = Master::getActiveCOBs();
            $data['handlers']           = Master::getActiveHandlers();
            $data['team_leaders']       = Master::getActiveTeamLeaders();

            views('finance.soa-masterlist', $data);  
        } 
        public function soaLetter(){
            $data = array();

            views('finance.soa-letter', $data);  
        } 

        public function soaLetterManage(){
            $data = array();

            views('finance.soa-letter-manage', $data);  
        } 

        
    }
?>