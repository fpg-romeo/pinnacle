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

        public function soaMaster(){
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
            
            if(isset($_POST['action'])){
                $field['master_list'] = array(
                    'intermediary_id'      => postVar('intermediary_id'),
                    'handler_id'           => postVar('handler_id'),
                    'team_leader_id'       => postVar('team_leader_id'),
                    'intermediary_code'    => postVar('intermediary_code'),
                    'account_name'         => postVar('account_name'),
                    'created_at'           => date('Y-m-d H:i:s'),
                    'is_active'            => $_POST['is_active'],
                );

                $field['branch']               = isset($_POST['branch']) && $_POST['branch'][0] != "" ? $_POST['branch'] : "";
                $field['segment']              = isset($_POST['segment']) && $_POST['segment'][0] != "" ? $_POST['segment'] : "";
                $field['insured_name']         = postVar('insured_name');
                $field['sales_channel']        = isset($_POST['sales_channel']) && $_POST['sales_channel'][0] != "" ? $_POST['sales_channel'] : "";
                $field['topro']                = isset($_POST['topro']) && $_POST['topro'][0] != "" ? $_POST['topro'] : "";
                $field['class_of_business']    = isset($_POST['class_of_business']) && $_POST['class_of_business'][0] != "" ? $_POST['class_of_business'] : "";
                $field['or_recipients']        = isset($_POST['or_recipients']) && $_POST['or_recipients'][0] != "" ? $_POST['or_recipients'] : "";
                $field['soa_recipients']       = isset($_POST['soa_recipients']) && $_POST['soa_recipients'][0] != "" ? $_POST['soa_recipients'] : "";

                if($_POST['action'] == "add"){
                   $id = Finance::addMasterList($field);
                }

                if($_POST['action'] == "edit"){
                   $id = Finance::editMasterList($_POST['id'], $field);
                }

                header('Location: /finance/soa-master/1');
            }

            views('finance.soa-master', $data);  
        } 

        public function soaLetter(){
            $data = array();
            $id = getVar('id');
            if($id > 0){
                $data['letter'] = recastArray(Finance::getLetterTemplateById($id));
               
                $categoriesDecode = json_decode($data['letter']['categories'], true);

                $categories = array_map(function($item) {
                    return json_encode([$item]);
                }, $categoriesDecode);

                $data['source_name'] = Finance::getSourceNameByCategories("'" . implode("', '", $categories) . "'");

                views('finance.soa-letter-setting', $data);
            }
            else{
                $data['letter'] = Finance::getLetterTemplates();
                views('finance.soa-letter', $data);
            }

        } 

        public function soaLetter_json(){
            $result = Finance::updateSoaLetter($_POST);
            echo json_encode($result);
        } 

        public function soaLetterManage(){
            $data = array();

            views('finance.soa-letter-manage', $data);  
        } 

        public function soaMaster_json(){
            $result = recastArray(Finance::getMasterlistById($_POST['id']));

            $result['branch']               = recastArray(Finance::getMasterlistPivot($_POST['id'], 'soa_branch_master_list_pivot')) ?: "";
            $result['segment']              = recastArray(Finance::getMasterlistPivot($_POST['id'], 'soa_segment_master_list_pivot')) ?: "";
            $result['class_of_business']    = recastArray(Finance::getMasterlistPivot($_POST['id'], 'soa_class_of_business_master_list_pivot')) ?: "";
            $result['sales_channel']        = recastArray(Finance::getMasterlistPivot($_POST['id'], 'soa_sales_channel_master_list_pivot')) ?: "";
            $result['topro']                = recastArray(Finance::getMasterlistPivot($_POST['id'], 'soa_topro_master_list_pivot')) ?: "";
            $result['soa_recipients']       = recastArray(Finance::getMasterlistPivot($_POST['id'], 'soa_recipient')) ?: "";
            $result['official_receipt']     = recastArray(Finance::getMasterlistPivot($_POST['id'], 'soa_official_receipt_recipient')) ?: "";

            echo json_encode($result);
        }
    }
?>