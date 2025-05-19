<?php
    class MasterController{

        public function __construct() {
            checkLoggedIn('true');
            accessRole(['1', '57', '58', '98']);
        }
        
        public function accountCompany(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['company'] = Master::getDynamic('master_account_company');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.account-company', $data);
        }

        public function accountCompanyJson(){
            $CONFIGURATION = Configuration::general();

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_account_company', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_account_company', $_POST['id']);

                    if($result['status'] == 'success'){

                        $record_data = serialize([
                            'table' => 'master_account_company',
                            'action' => 'delete',
                            'id' => $_POST['id']
                        ]);

                        Shortcode::sendToEveryCompany($record_data);

                    }
                    
                }else{
                    $id                     = htmlEncode($_POST['id']);
                    $field['name']          = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_account_company', $field);

                            if($result['status'] == 'success'){
                                $record = recastArray(Master::getDynamicById('master_account_company', $result['id']));

                                if($record){
                                    $record_data = serialize([
                                        'table' => 'master_account_company',
                                        'action' => 'edit',
                                        'data' => $record
                                    ]);

                                    Shortcode::sendToEveryCompany($record_data);

                                }
                            }
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $record_data = array(
                                'table_name' => 'master_account_company',
                            );

                            $last_id = Shortcode::databaseTableMasterLastId($record_data);
                            if(!empty($last_id)){
                                $field['id'] = $last_id + 1;
                            }

                            $result = Master::addDynamic('master_account_company', $field);

                            if($result['status'] == 'success'){
                                $record = recastArray(Master::getDynamicById('master_account_company', $result['id']));

                                if($record){
                                    unset($record['updated_when']);
                                    unset($record['updated_by']);
                                    $record_data = serialize([
                                        'table' => 'master_account_company',
                                        'action' => 'add',
                                        'data' => $record
                                    ]);

                                    Shortcode::sendToEveryCompany($record_data);

                                }
                            }
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }
        
        public function industry(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record']             = Master::getDynamic('master_status');
            $data['industry']           = Master::getIndustry();
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.industry', $data);  
        }

        public function industry_json(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_industry', $_POST['id']);
                    if(is_array($record)){
                        $result = recastArray($record);
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteIndustry($_POST['id']);
                    
                }else{
                    $id                      = htmlEncode($_POST['id']);
                    $field['name']           = htmlEncode($_POST['name']);
                    $field['description']    = htmlEncode($_POST['description']);
                    $field['status_id']      = htmlEncode($_POST['status_id']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_industry', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_industry', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function industrySubCategory(){
            includeModel(['Master']);

            $data = array();
            $industry_id                      = idDecrypt(getVar('id'));
            $data['industry_details']           = recastArray(Master::getIndustryById($industry_id));
            $data['industry']                   = Master::getIndustry(); 
            $data['status']                     = Master::getDynamic('master_status');
            $data['industry_sub_categories']    = Master::getIndustrySubCategoryByIndustryId($industry_id);

            views('master.industry-sub-category', $data);  
        }
        
        public static function industrySubCategoryJson(){
           
            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_industry_sub_category', $_POST['id']);
                    if(is_array($record)){
                        $result = recastArray($record);
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_industry_sub_category', $_POST['id']);
                    
                }else{
                    $id                      = htmlEncode($_POST['id']);
                    $field['industry_id']    = htmlEncode($_POST['industry_id']);
                    $field['name']           = htmlEncode($_POST['name']);
                    $field['status_id']      = htmlEncode($_POST['status_id']);
                 
                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_industry_sub_category', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_industry_sub_category', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function perIndustryJson(){

            if(isset($_POST['industry_id']) && !empty($_POST['industry_id'])){
                $record   = Master::getIndustrySubCategoryByIndustryId($_POST['industry_id']);
                if(is_array($record)){   
                    $result = $record; 
                }else{
                    $result['status']  = 'forbidden';
                    $result['message'] = 'Access to this resource on the server is denied';
                }
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }

            echo json_encode($result);
        }

        public function source(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_source');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.source', $data);  
        }

        public function source_json(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_source', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                            $result['type']             = htmlDecode($row['type']);
                            $result['category']         = htmlDecode($row['category']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_source', $_POST['id']);
                    
                }else{
                    $id                = htmlEncode($_POST['id']);
                    $field['name']     = htmlEncode($_POST['name']);
                    $field['type']     = htmlEncode($_POST['type']);
                    $field['category'] = htmlEncode($_POST['category']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_source', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_source', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function serviceStatus(){
            accessRole(['1', '58']);

            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_service_status');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.service-status', $data);  
        }

        public function serviceStatus_json(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_service_status', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_service_status', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_service_status', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_service_status', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function currency(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_currency');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.currency', $data);  
        }

        public function currency_json(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_currency', $_POST['id']);
                    if(is_array($record)){   
                        $result = recastArray($record); 
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_currency', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['code']    = htmlEncode($_POST['code']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('code', 'name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_currency', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_currency', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function psgType(){
            accessRole(['1', '57']);

            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_psg_type');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.psg-type', $data);  
        }

        public function psgType_json(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_psg_type', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_psg_type', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_psg_type', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_psg_type', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function paymentTerm(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_payment_term');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.payment-term', $data);  
        }

        public function paymentTermJson(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_payment_term', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_payment_term', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_payment_term', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_payment_term', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function paymentType(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_payment_type');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.payment-type', $data);  
        }

        public function paymentTypeJson(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_payment_type', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_payment_type', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_payment_type', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_payment_type', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function contractType(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_contract_type');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.contract-type', $data);  
        }

        public function contractType_json(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_contract_type', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_contract_type', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_contract_type', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_contract_type', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function country(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_country');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.country', $data);  
        }

        public function country_json(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_country', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_country', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_country', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_country', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function mediaChannel(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_service');
            $data['media_channel'] = Master::getMediaChannel();
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.media-channel', $data);  
        }

        public function mediaChannelJson(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_media_channel', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['service_id']       = htmlDecode($row['service_id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_media_channel', $_POST['id']);
                    
                }else{
                    $id                  = htmlEncode($_POST['id']);
                    $field['service_id'] = htmlEncode($_POST['service_id']);
                    $field['name']       = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editMediaChannel($field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addMediaChannel($field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function campaignDuration(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_campaign_duration');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.campaign-duration', $data);  
        }

        public function campaignDuration_json(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_campaign_duration', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_campaign_duration', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_campaign_duration', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_campaign_duration', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function service(){
            accessRole(['1', '58']);

            $data = array();
            $data = Master::getDynamic('master_service');

            views('master.service', $data);  
        }

        public function service_json(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_service', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_service', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_service', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_service', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function marketingContent(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_marketing_content');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.marketing-content', $data);  
        }

        public function marketingContent_json(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_marketing_content', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_marketing_content', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_marketing_content', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_marketing_content', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function cms(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_cms');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.cms', $data);  
        }

        public function cms_json(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_cms', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_cms', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_cms', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_cms', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function projectType(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_project_type');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.project-type', $data);  
        }

        public function projectType_json(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_project_type', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_project_type', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_project_type', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_project_type', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function websiteMaintenanceType(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_website_maintenance_type');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.website-maintenance-type', $data);  
        }

        public function websiteMaintenanceType_json(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_website_maintenance_type', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_website_maintenance_type', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_website_maintenance_type', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_website_maintenance_type', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function keywordNumber(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_keyword_number');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.keyword-number', $data);  
        }

        public function keywordNumber_json(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_keyword_number', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_keyword_number', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_keyword_number', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_keyword_number', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function meetingRoom(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_meeting_room');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.meeting-room', $data);  
        }

        public function meetingRoom_json(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_meeting_room', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['type']             = htmlDecode($row['type']);
                            $result['name']             = htmlDecode($row['name']);
                            $result['color']            = htmlDecode($row['color']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_meeting_room', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['type']    = htmlEncode($_POST['type']);
                    $field['name']    = htmlEncode($_POST['name']);
                    $field['color']   = htmlEncode($_POST['color']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_meeting_room', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_meeting_room', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function perMeetingTypeJson(){

            if(isset($_POST['type']) && !empty($_POST['type'])){
                $record = Master::getMeetingRoomByType($_POST['type']);
                if(is_array($record)){   
                    $result = $record; 
                }else{
                    $result['status']  = 'forbidden';
                    $result['message'] = 'Access to this resource on the server is denied';
                }
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }

            echo json_encode($result);
        }

        public function accountRole(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_account_role');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.account-role', $data);  
        }

        public function accountRole_json(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_account_role', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']          = htmlDecode($row['id']);
                            $result['name']        = htmlDecode($row['name']);
                            $result['controller']  = htmlDecode($row['controller']);
                            $result['view']        = htmlDecode($row['view']);
                            $result['description'] = htmlDecode($row['description']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_account_role', $_POST['id']);
                    
                }else{
                    $id                   = htmlEncode($_POST['id']);
                    $field['name']        = htmlEncode($_POST['name']);
                    $field['controller']  = strtolower(htmlEncode($_POST['controller']));
                    $field['view']        = strtolower(htmlEncode($_POST['view']));
                    $field['description'] = htmlEncode($_POST['description']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_account_role', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_account_role', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function accountStatus(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_account_status');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.account-status', $data);  
        }

        public function accountStatus_json(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_account_status', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_account_status', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_account_status', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_account_status', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function accountType(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_account_type');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.account-type', $data);  
        }

        public function accountType_json(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_account_type', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_account_type', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_account_type', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_account_type', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function accountTeam(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_account_team');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.account-team', $data);  
        }

        public function accountTeam_json(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_account_team', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_account_team', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_account_team', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_account_team', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function accountLevel(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_account_level');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.account-level', $data);  
        }

        public function accountLevel_json(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_account_level', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_account_level', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_account_level', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_account_level', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function accountDepartment(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['departments'] = Master::getDynamic('master_account_department');
            $data['account_roles'] = Master::getDynamic('master_account_role');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.account-department', $data);  
        }

        public function accountDepartment_json(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_account_department', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                            $result['account_role_ids'] = htmlDecode($row['account_role_ids']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_account_department', $_POST['id']);
                    
                }else{
                    $id                                     = htmlEncode($_POST['id']);
                    $field['account_role_ids']              = isset($_POST['account_role_ids']) ? Shortcode::concatId($_POST['account_role_ids']) : 0;
                    $field['name']                          = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_account_department', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_account_department', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function perDepartmentJson(){

            if(isset($_POST['id']) && !empty($_POST['id'])){
                $record = Master::getAccountDesignationByDepartmentId($_POST['id']);
                if(is_array($record)){   
                    $result = $record; 
                }else{
                    $result['status']  = 'forbidden';
                    $result['message'] = 'Access to this resource on the server is denied';
                }
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }

            echo json_encode($result);
        }

        public function accountDesignation(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['department']  = Master::getDynamic('master_account_department');
            $data['designation'] = Master::getAccountDesignation();
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.account-designation', $data);  
        }

        public function accountDesignation_json(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_account_designation', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['department_id']    = htmlDecode($row['department_id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_account_designation', $_POST['id']);
                    
                }else{
                    $id                     = htmlEncode($_POST['id']);
                    $field['department_id'] = htmlEncode($_POST['department_id']);
                    $field['name']          = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editAccountDesignation($field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addAccountDesignation($field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }
        
        public function archiveReason(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_archive_reason');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.archive-reason', $data);  
        }

        public function archiveReasonJson(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_archive_reason', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_archive_reason', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_archive_reason', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_archive_reason', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function accountUnitRole(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_account_unit_role');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.account-unit-role', $data);  
        }

        public function accountUnitRole_json(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_account_unit_role', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_account_unit_role', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_account_unit_role', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_account_unit_role', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function companyBranch(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_company_branch');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.company-branch', $data);  
        }

        public function companyBranch_json(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_company_branch', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_company_branch', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_company_branch', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_company_branch', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function holiday(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['holiday'] = Master::getHoliday();
            if(!empty($data['holiday'])){
                foreach ($data['holiday'] as $key_holiday => $value_holiday) {

                    $company_branch_id = explode('-', $value_holiday['company_branch_ids']);

                    $data['holiday'][$key_holiday]['company_branch_name'] = '';
                    $ctr = 1; 
                    foreach($company_branch_id as $id){
                        if(count($company_branch_id) > $ctr){
                            $delimeter = ', ';
                        }else{
                            $delimeter = '';
                        }
                        $data['holiday'][$key_holiday]['company_branch_name'] .= recastArray(Master::getDynamicById('master_company_branch', $id))['name'].$delimeter;

                        $ctr++;
                    }
                }
            }

            $data['company_branch'] = Master::getDynamic('master_company_branch');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.holiday', $data);  
        }

        public function holiday_json(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_holiday', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']                = htmlDecode($row['id']);
                            $result['name']              = htmlDecode($row['name']);
                            $result['date_set']          = dateReformat($row['date_set'], 'm/d/Y');
                            $result['company_branch_ids'] = htmlDecode($row['company_branch_ids']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_holiday', $_POST['id']);
                    
                }else{
                    $id                          = htmlEncode($_POST['id']);
                    $field['name']               = htmlEncode($_POST['name']);
                    $field['date_set']           = dateSaveDB($_POST['date_set']);
                    $field['company_branch_ids'] = Shortcode::concatId($_POST['company_branch_ids']); 

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_holiday', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_holiday', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }



        public function quickbooksPaymentMethod(){
            $data = array();

            includeModel(['Quickbooks']);
            
            $data['payment_method'] = Quickbooks::getPaymentMethod();

            views('master.quickbooks-payment-method', $data);  
        }

        public function quickbooksTerm(){
            $data = array();

            includeModel(['Quickbooks']);
            
            $data['term'] = Quickbooks::getTerm();

            views('master.quickbooks-term', $data);  
        }

        public function quickbooksItem(){
            includeModel(['Quickbooks']);
            includeDefault(['intuit-quickbooks']);
            IntuitQuickbooks::authorizedToken();            

            $data          = array();
            $CONFIGURATION = Configuration::general();
            
            $data['item']  = Quickbooks::getItem();

            $data['quickbooks_administrator']      = $CONFIGURATION['QUICKBOOKS_ADMINISTRATOR'];

            $activeToken                           = quickbooksToken();
            $data['quickbooks_administrator_name'] = is_array($activeToken) && !empty($activeToken) ? $activeToken['first_name'] : '';

            if(!isset($_SESSION['accessTokenJson']) && empty($_SESSION['accessTokenJson'])){ 
                $data['quickbooks'] = IntuitQuickbooks::login();
            }

            views('master.quickbooks-item', $data);  
        }

        public function quickbooksItemJson(){

            includeDefault(['intuit-quickbooks']);
            includeModel(['Quickbooks']);

            $result   = Array();
            $response = IntuitQuickbooks::itemAll();
            $json     = json_decode(json_encode($response), true);
            $count    = 0;

            if(is_array($json)){

                $item = Quickbooks::deleteAllItem();

                if($item['status'] == 'success'){

                    foreach($json as $key => $value){
                        $field = Array();
                        $field['id']                           = htmlEncode($value['Id']);
                        $field['name']                         = htmlEncode($value['Name']);
                        $field['description']                  = htmlEncode($value['Description']);
                        $field['status_id']                    = htmlEncode($value['Active']) == 'true' ? 1 : 0;
                        $field['tax_include']                  = htmlEncode($value['Taxable']);
                        $field['revenue_account']              = htmlEncode($value['UnitPrice']);
                        $field['quickbooks_income_account_id'] = htmlEncode($value['IncomeAccountRef']);
                        $field['updated_when']                 = dateTimeStamp();
                        //$field['quickbooks_response']          = safe_b64encode(serialize($value));
                        $result = Quickbooks::addItem($field);                        
                    }   
                }
            }

            echo json_encode($result);
            exit;
        }

        public function perQuickbooksItemJson(){
            includeModel(['Quickbooks']);

            if(isset($_POST['id']) && !empty($_POST['id'])){
                $record = Quickbooks::getItemById($_POST['id']);
                if(is_array($record)){   
                    $result                = recastArray($record);
                    $result['description'] = htmlDecode($result['description']);
                }else{
                    $result['status']  = 'forbidden';
                    $result['message'] = 'Access to this resource on the server is denied';
                }
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }

            echo json_encode($result);
        }


        /* DONT DELETE : FOR FUTURE REFERENCE QUICKBOOKS ITEM 
        public function serviceQuickbooks_json(){
            $result = array();
            includeDefault(['intuit-quickbooks']);

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['service_id']) && isset($_POST['action']) && $_POST['action'] == 'dropdown'){

                    $record = Master::getServiceQuickbooksByServiceId($_POST['service_id']);
                    if(is_array($record)){    
                        $result = $record;
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_service_quickbooks', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']              = htmlDecode($row['id']);
                            $result['service_id']      = htmlDecode($row['service_id']);
                            $result['name']            = htmlDecode($row['name']);
                            $result['description']     = htmlDecode($row['description']);
                            $result['tax_sales']       = htmlDecode($row['tax_sales']);
                            $result['tax_include']     = htmlDecode($row['tax_include']);
                            $result['revenue_account'] = htmlDecode($row['revenue_account']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_service_quickbooks', $_POST['id']);
                    
                }else{
                    $id                       = postVar('id');
                    $field['service_id']      = postVar('service_id', 0);
                    $field['name']            = postVar('name');
                    $field['description']     = postVar('description');
                    $field['tax_sales']       = postVar('tax_sales');
                    $field['tax_include']     = postVar('tax_include');
                    $field['revenue_account'] = postVar('revenue_account');

                    $data = checkRequiredPost(array('name', 'description'));

                    if(!array_key_exists('error', $data)){  

                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_service_quickbooks', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_service_quickbooks', $field);
                        }
                        
                        unset($field);

                        if($result['status'] == 'success'){

                            $item     = recastArray(Master::getDynamicById('master_service_quickbooks', $result['id']));
                            $response = IntuitQuickbooks::itemManage($item);
                            $json     = json_decode(json_encode($response), true);

                            if(isset($json['Id']) && !empty($json['Id']) && isset($json['IncomeAccountRef']) && !empty($json['IncomeAccountRef'])){
                                $field['id']                           = $result['id']; 
                                $field['quickbooks_item_id']           = $json['Id'];
                                $field['quickbooks_income_account_id'] = $json['IncomeAccountRef'];
                                $field['quickbooks_response']          = safe_b64encode(serialize($json));
                                $field['updated_by']                   = ACCOUNT_ID;
                                $field['updated_when']                 = dateTimeStamp();

                                $result = Master::editDynamic('master_service_quickbooks', $field);

                            }else{
                                $result['status']  = 'Error';
                                $result['message'] = 'Encounter technical error. Pls try again';
                            } 
                        }
                    }else{
                        $result['status']  = 'failed';
                        $result['message'] = 'Please check all required fields';
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }
        */

        // public function servicePackage(){
        //     accessRole(['1', '58']);

        //     $data          = array();
        //     $CONFIGURATION = Configuration::general();
            
        //     $data['package']         = Master::getServicePackage();
        //     $data['service']         = Master::getDynamic('master_service');
        //     $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

        //     views('master.service-package', $data);  
        // }

        // public function servicePackageJson(){
        //     if(isset($_POST) && !empty($_POST)){

        //         if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

        //             $record = Master::getDynamicById('master_service_package', $_POST['id']);
        //             if(is_array($record)){    
        //                 foreach($record as $row){
        //                     $result['id']        = htmlDecode($row['id']);
        //                     $result['name']      = htmlDecode($row['name']);
        //                     $result['status_id'] = htmlDecode($row['status_id']);
        //                 }
        //             }

        //         }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'duplicate'){
        //             $record =  recastArray(Master::getDynamicById('master_service_package', $_POST['id']));
        //             if(!empty($record)){
        //                 $field                      = [];
        //                 $field['id']                = 0;
        //                 $field['status_id']         = $record['status_id'];
        //                 $field['name']              = $record['name']."(DUPLICATE)";
        //                 $field['created_by']        = ACCOUNT_ID;
        //                 $field['created_when']      = dateTimeStamp();

        //                 $result                     = Master::addDynamic('master_service_package', $field);

        //                 if($result['status'] == 'success'){
        //                     $combos                 = Master::getServiceComboByPackageId($_POST['id']);
        //                     if(is_array($combos) && !empty($combos) ){
        //                         foreach($combos as $combo){

        //                             if($combo['name'] == 'Customize'){
        //                                 continue;
        //                             }
                                    
        //                             $combo_field                      = [];
        //                             $combo_field['id']                = 0;
        //                             $combo_field['package_id']        = $result['id'];
        //                             $combo_field['name']              = $combo['name']."(DUPLICATE)";
        //                             $combo_field['alias']             = $combo['alias'];
        //                             $combo_field['description']       = $combo['description'];
        //                             $combo_field['item']              = $combo['item'];
        //                             $combo_field['status_id']         = $combo['status_id'];
        //                             $combo_field['management_fee']    = $combo['management_fee'];
        //                             $combo_field['setup_fee']         = $combo['setup_fee'];
        //                             $combo_field['media_budget']      = $combo['package_id'];
        //                             $combo_field['other_fee']         = $combo['other_fee'];
        //                             $combo_field['discount']          = $combo['discount'];
        //                             $combo_field['created_by']        = ACCOUNT_ID;
        //                             $combo_field['created_when']      = dateTimeStamp();
        //                             Master::addDynamic('master_service_combo', $combo_field);
        //                         }
        //                     }
        //                 }
        //             }else{
        //                 $result['status']  = 'failed';
        //                 $result['message'] = 'Something Went Wrong!';
        //             }
        //         }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

        //             $result = Master::deleteDynamic('master_service_package', $_POST['id']);
                    
        //         }else{
        //             $id                 = postVar('id',0);
        //             $field['status_id'] = postVar('status_id');
        //             $field['name']      = postVar('name');

        //             $data = checkRequiredPost(array('name'));

        //             if(!array_key_exists('error', $data)){  

        //                 if(!empty($id)){
        //                     $field['id']                = $id;
        //                     $field['updated_by']        = ACCOUNT_ID;
        //                     $field['updated_when']      = dateTimeStamp();

        //                     $result = Master::editDynamic('master_service_package', $field);
        //                 }else{
        //                     $field['created_by']        = ACCOUNT_ID;
        //                     $field['created_when']      = dateTimeStamp();

        //                     $result = Master::addDynamic('master_service_package', $field);
        //                 }
                        
        //             }else{
        //                 $result['status']  = 'failed';
        //                 $result['message'] = 'Please check all required fields';
        //             }
        //         }

        //     }else{
        //         $result['status']  = 'forbidden';
        //         $result['message'] = 'Access to this resource on the server is denied';
        //     }
            
        //     echo json_encode($result);
        // }

        public function servicePackage(){
            accessRole(['1', '58']);
            includeModel('Package');
            $data          = array();
            $CONFIGURATION = Configuration::general();
            
            // $data['package']                     = Master::getServicePackage();
            // $data['service']                     = Master::getDynamic('master_service');

            $data['service']                    = Master::getDynamic('master_service');
            // $data['package']                    = Package::getDynamic('package');
            $data['package']                    = Master::getServicePackage();
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];
        
        
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];
            views('master.service-package', $data);  
        }

        public function servicePackageJson(){
            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('package', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']          = htmlDecode($row['id']);
                            $result['name']        = htmlDecode($row['name']);
                            $result['service_id']  = htmlDecode($row['package_service_id']);
                            $result['status_id']   = htmlDecode($row['status_id']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'duplicate'){
                    $record =  recastArray(Master::getDynamicById('master_service_package', $_POST['id']));
                    if(!empty($record)){
                        $field                      = [];
                        $field['id']                = 0;
                        $field['status_id']         = $record['status_id'];
                        $field['name']              = $record['name']."(DUPLICATE)";
                        $field['created_by']        = ACCOUNT_ID;
                        $field['created_when']      = dateTimeStamp();

                        $result                     = Master::addDynamic('master_service_package', $field);

                        if($result['status'] == 'success'){
                            $combos                 = Master::getServiceComboByPackageId($_POST['id']);
                            if(is_array($combos) && !empty($combos) ){
                                foreach($combos as $combo){

                                    if($combo['name'] == 'Customize'){
                                        continue;
                                    }
                                    
                                    $combo_field                      = [];
                                    $combo_field['id']                = 0;
                                    $combo_field['package_id']        = $result['id'];
                                    $combo_field['name']              = $combo['name']."(DUPLICATE)";
                                    $combo_field['alias']             = $combo['alias'];
                                    $combo_field['description']       = $combo['description'];
                                    $combo_field['item']              = $combo['item'];
                                    $combo_field['status_id']         = $combo['status_id'];
                                    $combo_field['management_fee']    = $combo['management_fee'];
                                    $combo_field['setup_fee']         = $combo['setup_fee'];
                                    $combo_field['media_budget']      = $combo['package_id'];
                                    $combo_field['other_fee']         = $combo['other_fee'];
                                    $combo_field['discount']          = $combo['discount'];
                                    $combo_field['created_by']        = ACCOUNT_ID;
                                    $combo_field['created_when']      = dateTimeStamp();
                                    Master::addDynamic('master_service_combo', $combo_field);
                                }
                            }
                        }
                    }else{
                        $result['status']  = 'failed';
                        $result['message'] = 'Something Went Wrong!';
                    }
                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_service_package', $_POST['id']);
                    
                }else{
                    $id                          = postVar('id',0);
                    $field['status_id']          = postVar('status_id');
                    $field['package_service_id'] = postVar('service_id');
                    $field['name']               = postVar('name');

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  

                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('package', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('package', $field);
                        }
                        
                    }else{
                        $result['status']  = 'failed';
                        $result['message'] = 'Please check all required fields';
                    }
                }

            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function perPackageJson(){
            $result = Array();

            $CONFIGURATION = Configuration::general();
            
            if(isset($_POST) && !empty($_POST)){
                $record = Master::getServiceComboByPackageId($_POST['id'], $CONFIGURATION['MASTER_STATUS_ACTIVE']);
                if(is_array($record)){
                    foreach($record as $key => $row){
                        $result['option'][$key]['id']             = htmlDecode($row['id']);
                        $result['option'][$key]['package_id']     = htmlDecode($row['package_id']);
                        $result['option'][$key]['name']           = htmlDecode($row['name']);
                    }
                }
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }

            echo json_encode($result);
        }

        public function serviceCombo(){
            accessRole(['1', '58']);
            $CONFIGURATION = Configuration::general();

            $data                    = array();
            $data['package_id']      = idDecrypt(getVar('id'));
            $data['package_details'] = recastArray(Master::getDynamicById('master_service_package', $data['package_id']));

            emptyRedirectPage($data['package_details']);

            $data['combo']           = Master::getServiceComboByPackageId($data['package_id']);
            $data['package']         = Master::getServicePackage();
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.service-combo', $data);  
        }

        /*
        public function serviceCombo_json(){
            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_service_combo', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']             = htmlDecode($row['id']);
                            $result['package_id']     = htmlDecode($row['package_id']);
                            $result['name']           = htmlDecode($row['name']);
                            $result['alias']          = htmlDecode($row['alias']);
                            $result['description']    = htmlDecode($row['description']);
                            $result['item']           = htmlDecode($row['item']);
                            $result['status_id']      = htmlDecode($row['status_id']);
                            $result['management_fee'] = htmlDecode($row['management_fee']);
                            $result['setup_fee']      = htmlDecode($row['setup_fee']);
                        }
                        
                        $result['service_item'] = Master::getServiceItemByComboId($result['id']);
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_service_combo', $_POST['id']);
                    
                }else{
                    $id                      = postVar('id');
                    $field['package_id']     = postVar('package_id', 0);
                    $field['name']           = postVar('name');
                    $field['alias']          = postVar('alias');
                    $field['description']    = postVar('description');
                    $field['item']           = postVar('item');
                    $field['status_id']      = postVar('status_id');
                    $field['management_fee'] = moneyClean(postVar('management_fee', '0.00'));
                    $field['setup_fee']      = moneyClean(postVar('setup_fee', '0.00'));

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  

                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_service_combo', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_service_combo', $field);
                        }
                        
                    }else{
                        $result['status']  = 'failed';
                        $result['message'] = 'Please check all required fields';
                    }
                }

            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }
        */

        public function serviceComboJson(){
            if(isset($_POST) && !empty($_POST)){
                
                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_service_combo', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']             = htmlDecode($row['id']);
                            $result['package_id']     = htmlDecode($row['package_id']);
                            $result['name']           = htmlDecode($row['name']);
                            $result['alias']          = htmlDecode($row['alias']);
                            $result['description']    = htmlDecode($row['description']);
                            $result['item']           = htmlDecode($row['item']);
                            $result['status_id']      = htmlDecode($row['status_id']);
                            $result['management_fee'] = htmlDecode($row['management_fee']);
                            $result['setup_fee']      = htmlDecode($row['setup_fee']);
                            $result['media_budget']   = htmlDecode($row['media_budget']);
                            $result['other_fee']      = htmlDecode($row['other_fee']);
                            $result['discount']       = htmlDecode($row['discount']);
                        }
                        
                        $result['service_item'] = Master::getServiceItemByComboId($result['id']);
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'duplicate'){
                    $record =  recastArray(Master::getDynamicById('master_service_combo', $_POST['id']));
                    if(!empty($record)){
                        $field                      = [];
                        $field['id']                = 0;
                        $field['package_id']        = $record['package_id'];
                        $field['name']              = $record['name']."(DUPLICATE)";
                        $field['alias']             = $record['alias'];
                        $field['description']       = $record['description'];
                        $field['item']              = $record['item'];
                        $field['status_id']         = $record['status_id'];
                        $field['management_fee']    = $record['management_fee'];
                        $field['setup_fee']         = $record['setup_fee'];
                        $field['media_budget']      = $record['package_id'];
                        $field['other_fee']         = $record['other_fee'];
                        $field['discount']          = $record['discount'];
                        $field['created_by']        = ACCOUNT_ID;
                        $field['created_when']      = dateTimeStamp();
                        $result                     = Master::addDynamic('master_service_combo', $field);
                    }else{
                        $result['status']  = 'failed';
                        $result['message'] = 'Something Went Wrong!';
                    }
                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_service_combo', $_POST['id']);

                }else{
                    $id                      = postVar('id');
                    $field['package_id']     = postVar('package_id', 0);
                    $field['name']           = postVar('name');
                    $field['alias']          = postVar('alias');
                    $field['description']    = postVar('description');
                    $field['item']           = postVar('item');
                    $field['status_id']      = postVar('status_id');
                    $field['management_fee'] = moneyClean(postVar('management_fee', '0.00'));
                    $field['setup_fee']      = moneyClean(postVar('setup_fee', '0.00'));
                    $field['media_budget']   = moneyClean(postVar('media_budget', '0.00'));
                    $field['other_fee']      = moneyClean(postVar('other_fee', '0.00'));
                    $field['discount']       = moneyClean(postVar('discount', '0.00'));

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  

                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_service_combo', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_service_combo', $field);
                        }
                        
                    }else{
                        $result['status']  = 'failed';
                        $result['message'] = 'Please check all required fields';
                    }
                }

            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        /*
        public function serviceItem(){
            accessRole(['1', '58']);

            $data = array();
            
            $data['item']               = Master::getServiceItem();
            $data['package']            = Master::getDynamic('master_service_package');
            $data['combo']              = Master::getServiceCombo();
            $data['account_department'] = Master::getDynamic('master_account_department');

            views('master.service-item', $data);  
        }
        */

        /*
        public function serviceItem_json(){
            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_service_item', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']                    = htmlDecode($row['id']);
                            $result['combo_ids']             = htmlDecode($row['combo_ids']);
                            $result['name']                  = htmlDecode($row['name']);
                            $result['description']           = htmlDecode($row['description']);
                            $result['account_department_id'] = htmlDecode($row['account_department_id']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_service_item', $_POST['id']);
                    
                }else{
                    $id                             = postVar('id');
                    $field['combo_ids']             = isset($_POST['combo_ids']) ? Shortcode::concatId($_POST['combo_ids']) : 0;
                    $field['name']                  = postVar('name');
                    $field['description']           = postVar('description');
                    $field['account_department_id'] = postVar('account_department_id', 0);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  

                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_service_item', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_service_item', $field);
                        }
                        
                    }else{
                        $result['status']  = 'failed';
                        $result['message'] = 'Please check all required fields';
                    }
                }

            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }
        */

        public function psgPackage(){
            accessRole(['1', '57']);

            $CONFIGURATION  = Configuration::general();
            $data = array();
            
            $data['package']         = Master::getPsgPackage(); 
            $data['type']            = Master::getDynamic('master_psg_type');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];
            $data['status']          = Master::getDynamic('master_status');
            
            views('master.psg-package', $data);  
        }

        /*
        public function psgAttachmentUploadJson(){
            $result         = Array();
            $CONFIGURATION  = Configuration::general();
            $folder         = postVar('folder');

            if(isset($_FILES['file']) && !empty($_FILES['file'])){
                if(!empty(postVar('attached'))){
                    $explode_file = explode(',', postVar('attached'));
                    foreach($explode_file as $value_file){
                        deleteFile($folder, $value_file);
                    }
                }
                $no_files = count($_FILES["file"]['name']);
                for($ctr = 0; $ctr < $no_files; $ctr++){
                    if(!empty($_FILES['file']['name'][$ctr]) && $_FILES['file']['error'][$ctr] == 0){
                        $file_name  = $_FILES['file']['name'][$ctr];
                        $file_name  = $_FILES['file']['name'][$ctr];
                        $file_size  = $_FILES['file']['size'][$ctr];
                        $file_tmp   = $_FILES['file']['tmp_name'][$ctr];
                        $file_type  = $_FILES['file']['type'][$ctr];
                        $file_ext   = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                        $filename   = pathinfo($file_name, PATHINFO_FILENAME);
                        $new_name   = nameClean($filename).'_'.dateTimeAsId().'.'.$file_ext;
                        $extensions = $CONFIGURATION['ALLOWED_FILE'];
                        if(!in_array($file_ext, $extensions)){
                            $result['message'] = 'File format is not allowed';
                        }else{
                            if(!file_exists('upload/'.$folder)){
                                mkdir('upload/'.$folder, 0777, true);
                            }
                            if(move_uploaded_file($file_tmp, uploadFile($folder, $new_name))){
                                $result['filename'][] = $new_name;
                            }else{
                                $result['message'] = 'Encounter technical error. Pls try again';
                            }
                        }
                    }else{
                        $result['message'] = 'You uploaded an empty file.';
                    }
                }
            }

            echo json_encode($result);
        }
        */

        /*
        public function psgAttachmentRemoveJson(){
            includeModel('Master');
            
            $result         = Array();
            $id             = postVar('id');
            $folder         = postVar('folder');
            $file           = postVar('file');
            $attachment_psg = postVar('attachment_psg');

            if(file_exists('upload/'.$folder.'/'.$file)){
                removeFile('upload/'.$folder.'/'.$file);
                $result['status'] = 'success';
            }

            if(!empty($id)){
                $field['id']           = $id;
                $field['type_id']      = postVar('type_id');
                $field['name']         = postVar('name');
                $field['updated_by']   = ACCOUNT_ID;
                $field['updated_when'] = dateTimeStamp();
                $attachment = explode(',', $attachment_psg);
                if(is_array($attachment)){
                    $new_attachment = array();
                    foreach($attachment as $key => $value){
                        if($file != $value){
                            $new_attachment[] = $value;
                        }
                    }
                    $field['file'] = implode(',', $new_attachment);
                }
                Master::editPsgPackage($field);
            }

            echo json_encode($result);
        }
        */

        public function psgPackageJson(){
            if(isset($_POST) && !empty($_POST)){
                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){
                    $record = Master::getPsgPackageById($_POST['id']);
                    if(is_array($record)){
                        $result = recastArray($record);
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){
                    $result = Master::deleteDynamic('master_psg_package', $_POST['id']);
                    /*
                    if($result['status'] == 'success' && !empty($result['record']['file'])){
                        $explode_file = explode(',', $result['record']['file']);
                        foreach($explode_file as $value_file){
                            deleteFile('psg', $value_file);
                        }
                    }
                    */

                }else{
                    $id                  = postVar('id', 0);
                    $field['type_id']    = postVar('type_id', 0);
                    $field['name']       = postVar('name');
                    $field['status_id']  = postVar('status_id', 0);
                    $field['quickbooks'] = postVar('quickbooks');
                    $field['version']    = postVar('version');
                    
                    $data = checkRequiredPost(array('type_id', 'name', 'status_id', 'quickbooks'));
                    if(!array_key_exists('error', $data)){
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();
                            $result = Master::editPsgPackage($field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();
                            $result = Master::addPsgPackage($field);
                        }
                    }else{
                        $result['status']  = 'failed';
                        $result['message'] = 'Please check all required fields';
                    }
                }
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }

            echo json_encode($result);
        }

        public function psgItem(){
            accessRole(['1', '57']);
            $CONFIGURATION = Configuration::general();

            includeModel(['Service', 'Quickbooks']);
            $data = array();
            $package_id              = idDecrypt(getVar('id'));
            $data['package_details'] = recastArray(Master::getPsgPackageById($package_id));
            $data['type']            = Master::getDynamic('master_psg_type');
            $data['package']         = Master::getPsgPackage(); 
            $data['service']         = Master::getDynamic('master_service');
            $data['item']            = Master::getPsgItemByPackageId($package_id);
            $data['quickbooks_item'] = Quickbooks::getItemForInvoice();

            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.psg-item', $data);  
        }

        public static function psgItemJson(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getPsgItemById($_POST['id']);

                    if(is_array($record)){   

                        $result = recastArray($record);
                        foreach($record as $row){
                            $result['id']                 = htmlDecode($row['id']);
                            $result['type_id']            = htmlDecode($row['type_id']);
                            $result['package_id']         = htmlDecode($row['package_id']);
                            $result['description']        = htmlDecode($row['description']);
                            $result['quantity']           = htmlDecode($row['quantity']);
                            $result['unit_name']          = htmlDecode($row['unit_name']);
                            $result['unit_price']         = htmlDecode($row['unit_price']);
                            $result['amount']             = htmlDecode($row['amount']);
                            $result['service_id']         = htmlDecode($row['service_id']);
                            $result['quickbooks_item_id'] = htmlDecode($row['quickbooks_item_id']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_psg_item', $_POST['id']);
                    
                }else{
                    $id                          = postVar('id', 0);
                    $field['type_id']            = postVar('type_id', 0);
                    $field['package_id']         = postVar('package_id', 0);
                    $field['description']        = postVar('description','');
                    $field['quantity']           = postVar('quantity', '0');
                    $field['unit_name']          = postVar('unit_name','');
                    $field['unit_price']         = moneyClean(postVar('unit_price','0.00'));
                    $field['amount']             = moneyClean(postVar('amount','0.00'));
                    $field['service_id']         = postVar('service_id','0');
                    $field['quickbooks_item_id'] = postVar('quickbooks_item_id','0');
                    $field['sort']               = postVar('sort', '0');

                    $data = checkRequiredPost(array('type_id', 'package_id', 'description', 'quantity', 'unit_price', 'amount'));

                    if(!array_key_exists('error', $data)){  

                        if(!empty($id)){
                            $field['id']           = $id;
                            $field['updated_by']   = ACCOUNT_ID;
                            $field['updated_when'] = dateTimeStamp();

                            $result = Master::editPsgItem($field);
                        }else{
                            $field['created_by']   = ACCOUNT_ID;
                            $field['created_when'] = dateTimeStamp();

                            $result = Master::addPsgItem($field);
                        }
                        
                    }else{
                        $result['status']  = 'failed';
                        $result['message'] = 'Please check all required fields';
                    }
                }

            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function psgPurchase(){
            accessRole(['1', '57']);
            $CONFIGURATION = Configuration::general();

            includeModel(['Service', 'Quickbooks']);
            $data = array();
            $package_id              = idDecrypt(getVar('id'));
            $data['package_details'] = recastArray(Master::getPsgPackageById($package_id));
            $data['type']            = Master::getDynamic('master_psg_type');
            $data['package']         = Master::getPsgPackage(); 
            $data['service']         = Master::getDynamic('master_service');
            $data['purchase']        = Master::getPsgPurchaseByPackageId($package_id);
            $data['quickbooks_item'] = Quickbooks::getItemForInvoice();

            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.psg-purchase', $data);  
        }

        public static function psgPurchaseJson(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getPsgPurchaseById($_POST['id']);

                    if(is_array($record)){   

                        $result = recastArray($record);
                        foreach($record as $row){
                            $result['id']                 = htmlDecode($row['id']);
                            $result['type_id']            = htmlDecode($row['type_id']);
                            $result['package_id']         = htmlDecode($row['package_id']);
                            $result['description']        = htmlDecode($row['description']);
                            $result['quantity']           = htmlDecode($row['quantity']);
                            $result['unit_price']         = htmlDecode($row['unit_price']);
                            $result['amount']             = htmlDecode($row['amount']);
                            $result['service_id']         = htmlDecode($row['service_id']);
                            $result['quickbooks_item_id'] = htmlDecode($row['quickbooks_item_id']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_psg_purchase', $_POST['id']);
                    
                }else{
                    $id                          = postVar('id', 0);
                    $field['type_id']            = postVar('type_id', 0);
                    $field['package_id']         = postVar('package_id', 0);
                    $field['description']        = postVar('description','');
                    $field['quantity']           = postVar('quantity', '0');
                    $field['unit_price']         = moneyClean(postVar('unit_price','0.00'));
                    $field['amount']             = moneyClean(postVar('amount','0.00'));
                    $field['service_id']         = postVar('service_id','0');
                    $field['quickbooks_item_id'] = postVar('quickbooks_item_id','0');
                    $field['sort']               = postVar('sort', '0');

                    $data = checkRequiredPost(array('type_id', 'package_id', 'description', 'quantity', 'unit_price', 'amount'));

                    if(!array_key_exists('error', $data)){  

                        if(!empty($id)){
                            $field['id']           = $id;
                            $field['updated_by']   = ACCOUNT_ID;
                            $field['updated_when'] = dateTimeStamp();

                            $result = Master::editDynamic('master_psg_purchase', $field);
                        }else{
                            $field['created_by']   = ACCOUNT_ID;
                            $field['created_when'] = dateTimeStamp();

                            $result = Master::addPsgPurchase($field);
                        }
                        
                    }else{
                        $result['status']  = 'failed';
                        $result['message'] = 'Please check all required fields';
                    }
                }

            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function perPsgTypeJson(){
            $CONFIGURATION = Configuration::general();

            if(isset($_POST['id']) && !empty($_POST['id'])){
                $record = Master::getPsgPackageByTypeId($_POST['id'], $CONFIGURATION['MASTER_STATUS_ACTIVE']);
                if(is_array($record)){   
                    $result = $record; 
                }else{
                    $result['status']  = 'forbidden';
                    $result['message'] = 'Access to this resource on the server is denied';
                }
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }

            echo json_encode($result);
        }

        public function perPsgPackageJson(){
            if(isset($_POST['id']) && !empty($_POST['id'])){
                $record = Master::getPsgItemByPackageId($_POST['id']);
                if(is_array($record)){   
                    $result = $record; 
                }else{
                    $result['status']  = 'forbidden';
                    $result['message'] = 'Access to this resource on the server is denied';
                }
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }

            echo json_encode($result);
        }






























        public function mediaAccountType(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_media_account_type');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.media-account-type', $data);  
        }

        public function mediaAccountTypeJson(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_media_account_type', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_media_account_type', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_media_account_type', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_media_account_type', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function mobileDevice(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_mobile_device');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.mobile-device', $data);  
        }

        public function mobileDeviceJson(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_mobile_device', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_mobile_device', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_mobile_device', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_mobile_device', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function advertisingType(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_advertising_type');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.advertising-type', $data);  
        }

        public function advertisingTypeJson(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_advertising_type', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_advertising_type', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_advertising_type', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_advertising_type', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function creditTerm(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_credit_term');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.credit-term', $data);  
        }

        public function creditTermJson(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_credit_term', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_credit_term', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_credit_term', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_credit_term', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function logAction(){
            $data = array();
            $data = Master::getDynamic('master_log_action');

            views('master.log-action', $data);  
        }

        public function logActionJson(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_log_action', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_log_action', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_log_action', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_log_action', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function clientType(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_client_type');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.client-type', $data);  
        }

        public function clientTypeJson(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_client_type', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_client_type', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_client_type', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_client_type', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function businessModel(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_business_model');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.business-model', $data);  
        }

        public function businessModelJson(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_business_model', $_POST['id']);
                    if(is_array($record) && !empty($record)){    
                        $result = recastArray($record);
                    }else{
                        $result['status']  = 'failed';
                        $result['message'] = 'No Record Found';
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_business_model', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_business_model', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_business_model', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function businessAttachmentType(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_business_attachment_type');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.business-attachment-type', $data);  
        }

        public function businessAttachmentTypeJson(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_business_attachment_type', $_POST['id']);
                    if(is_array($record) && !empty($record)){    
                        $result = recastArray($record);
                    }else{
                        $result['status']  = 'failed';
                        $result['message'] = 'No Record Found';
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_business_attachment_type', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_business_attachment_type', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_business_attachment_type', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function pwpType(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_pwp_type');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.pwp-type', $data);  
        }

        public function pwpTypeJson(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_pwp_type', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_pwp_type', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_pwp_type', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_pwp_type', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function opportunityPackage(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['service']                    = Master::getDynamic('master_service');
            $data['package']                    = Master::getOpportunityPackage();
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.opportunity-package', $data);  
        }

        public function opportunityPackageJson(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getOpportunityPackageById($_POST['id']);
                    if(is_array($record)){ 
                        $result = recastArray($record);
                    }else{
                        $result['status']  = 'failed';
                        $result['message'] = 'No record found';
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){
                    $result = Master::deleteDynamic('master_opportunity_package', $_POST['id']);
                    
                }else{
                    $id                     = postVar('id');
                    $field['service_ids']   = isset($_POST['service_ids']) ? Shortcode::concatId($_POST['service_ids']) : 0;
                    $field['name']          = postVar('name');
                    $field['split_fee']     = postVar('split_fee');
                    $field['merge_invoice'] = postVar('merge_invoice');

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_opportunity_package', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_opportunity_package', $field);
                        }
                    }else{
                        $result['status']  = 'failed';
                        $result['message'] = 'Please check all required fields'; 
                    } 
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
    
            echo json_encode($result);
        }

        public function perOpportunityPackageJson(){
            if(isset($_POST['id']) && !empty($_POST['id'])){
                $record = Master::getOpportunityPackageById($_POST['id']);
                if(is_array($record)){   
                    $result = recastArray($record); 
                }else{
                    $result['status']  = 'failed';
                    $result['message'] = 'No record found';
                }
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }

            echo json_encode($result);
        }

        public function perPsgPaymentTermJson(){
            includeModel(['Master']);
            $CONFIGURATION  = Configuration::general();
            $records        =  Master::getDynamic('master_payment_term', 'sort ASC');

            if(isset($_POST['psg']) && !empty($_POST['psg']) && $_POST['psg'] == 'Yes'){

                $psg_payment_terms = Master::getDynamicIn('master_payment_term', 'id',[$CONFIGURATION['PAYMENT_TERM_100'],$CONFIGURATION['PAYMENT_TERM_50_50']]);
                if(is_array($psg_payment_terms)){   
                    $records    = $psg_payment_terms; 
                }
                $result['status']   = 'success';
                $result['message']  = 'Successfully Retrieved';
                $result['records']  = $records;
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
                $result['records'] = $records;
            }

            echo json_encode($result);
        }

        public function utm(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['utm'] = Master::getUtm();
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.utm', $data);  
        }

        public function utmJson(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getUtmById($_POST['id']);
                    if(is_array($record)){   
                        $result = recastArray($record);
                    }else{
                        $result['status']  = 'failed';
                        $result['message'] = 'No Record Found';
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteUtm($_POST['id']);
                    
                }else{
                    $id                    = postVar('id', 0);
                    $field['source']       = postVar('source');
                    $field['sub_source']   = postVar('sub_source');
                    $field['utm_source']   = postVar('utm_source');
                    $field['utm_medium']   = postVar('utm_medium');
                    $field['utm_campaign'] = postVar('utm_campaign');
                    $field['utm_content']  = postVar('utm_content');
                    $field['utm_term']     = postVar('utm_term');
                    $field['remarks']      = postVar('remarks');

                    $data = checkRequiredPost(array('source', 'sub_source'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']           = $id;
                            $field['updated_by']   = ACCOUNT_ID;
                            $field['updated_when'] = dateTimeStamp();

                            $result = Master::editUtm($field);
                        }else{
                            $field['created_by']   = ACCOUNT_ID;
                            $field['created_when'] = dateTimeStamp();

                            $result = Master::addUtm($field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function landingPage(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_landing_page');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.landing-page', $data);  
        }

        public function landingPageJson(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_landing_page', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_landing_page', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_landing_page', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_landing_page', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function ticketPriority(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_ticket_priority');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.ticket-priority', $data);  
        }

        public function ticketPriorityJson(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_ticket_priority', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_ticket_priority', $_POST['id']);
                    
                }else{
                    $id                    = htmlEncode($_POST['id']);
                    $field['name']         = htmlEncode($_POST['name']);
                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_ticket_priority', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_ticket_priority', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function ticketRequestType(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_ticket_request_type');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.ticket-request-type', $data);  
        }

        public function ticketRequestTypeJson(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_ticket_request_type', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_ticket_request_type', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_ticket_request_type', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_ticket_request_type', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function ticketStatus(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_ticket_status');
            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.ticket-status', $data);
        }

        public function ticketStatusJson(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_ticket_status', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_ticket_status', $_POST['id']);
                    
                }else{
                    $id               = htmlEncode($_POST['id']);
                    $field['name']    = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_ticket_status', $field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_ticket_status', $field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }



        public function serviceSubPackage(){
            
            accessRole(['1', '58']);

            $data                       = [];
            $CONFIGURATION              = Configuration::general();
            $package_id                 = idDecrypt(getVar('id')); // Get package ID instead of service ID
            $data['package_name']       = recastArray(Master::getPackageById($package_id));
            $data['freebie']            = recastArray(Master::getDynamic('package_freebie'));
            $data['package_period']     = recastArray(Master::getDynamic('master_package_period'));
            $data['campaign_duration']  = recastArray(Master::getDynamic('master_campaign_duration'));
            $data['payment_term']       = recastArray(Master::getDynamic('master_payment_term'));
            
            $data['sub_package']        = [];
            
            // get sub-packages by package ID
            $sub_packages = Master::getSubPackageByPackageId($package_id);
            if (is_array($sub_packages)) {
                    foreach ($sub_packages as $sub_package) {
                        $sub_package_field                = [];
                        $sub_package_field['id']          = arrayKeyExist($sub_package, 'id');
                        $sub_package_field['package_id']  = arrayKeyExist($sub_package, 'package_id');
                        $sub_package_field['name']        = arrayKeyExist($sub_package, 'name');
                        $sub_package_field['combo']       = [];

                        // get combos
                        $combos = Master::getComboBySubPackageId($sub_package_field['id']);
                        if (is_array($combos)) {
                            foreach ($combos as $combo) {
                                $combo_field                          = [];
                                $combo_field['id']                    = arrayKeyExist($combo, 'id');
                                $combo_field['package_id']            = arrayKeyExist($combo, 'package_id');
                                $combo_field['subpackage_id']         = arrayKeyExist($combo, 'subpackage_id');
                                $combo_field['name']                  = arrayKeyExist($combo, 'name');
                                $combo_field['deliverables']          = arrayKeyExist($combo, 'deliverables');
                                $combo_field['period_id']             = arrayKeyExist($combo, 'period_id');
                                $combo_field['management_fee']        = arrayKeyExist($combo, 'management_fee');
                                $combo_field['campaign_duration']     = [];

                                // get campaign durations
                                $campaign_durations = Master::getCampaignDurationByComboId($combo_field['id']);
                                if (is_array($campaign_durations)) {
                                foreach ($campaign_durations as $campaign_duration) {
                                        $campaign_duration_field                          = [];
                                        $campaign_duration_field['id']                    = arrayKeyExist($campaign_duration, 'id');
                                        $campaign_duration_field['package_id']            = arrayKeyExist($campaign_duration, 'package_id');
                                        $campaign_duration_field['combo_id']              = arrayKeyExist($campaign_duration, 'combo_id');
                                        $campaign_duration_field['subpackage_id']         = arrayKeyExist($campaign_duration, 'subpackage_id');
                                        $campaign_duration_field['campaign_duration_id']  = arrayKeyExist($campaign_duration, 'campaign_duration_id');
                                        $campaign_duration_field['name']                  = arrayKeyExist($campaign_duration, 'campaign_duration_name');
                                        $campaign_duration_field['payment_term']          = [];

                                        // get payment terms
                                        $payment_terms = Master::getPaymentTermByCampaignDurationId($campaign_duration_field['id']);
                                        if (is_array($payment_terms)) {
                                            foreach ($payment_terms as $payment_term) {
                                                $payment_term_field                                        = [];
                                                $payment_term_field['id']                                  = arrayKeyExist($payment_term, 'id');
                                                $payment_term_field['package_id']                          = arrayKeyExist($payment_term, 'package_id');
                                                $payment_term_field['combo_campaign_duration_id']  = arrayKeyExist($payment_term, 'combo_campaign_duration_id');
                                                $payment_term_field['subpackage_id']                       = arrayKeyExist($payment_term, 'subpackage_id');
                                                $payment_term_field['combo_id']                            = arrayKeyExist($payment_term, 'combo_id        ');
                                                $payment_term_field['name']                                = arrayKeyExist($payment_term, 'payment_term_name');
                                                $payment_term_field['payment_term_id']                     = arrayKeyExist($payment_term, 'payment_term_id');
                                                $payment_term_field['freebie_ids']                         = arrayKeyExist($payment_term, 'freebie_ids');
                                                $payment_term_field['discount']                            = [];

                                                // get discounts
                                                $discounts = Master::getDiscountByPaymentTermId($payment_term_field['id']);
                                                if (is_array($discounts)) {
                                                    foreach ($discounts as $discount) {
                                                    $discount_field                                 = [];
                                                    $discount_field['id']                           = arrayKeyExist($discount, 'id');
                                                    $discount_field['package_id']                   = arrayKeyExist($discount, 'package_id');
                                                    $discount_field['subpackage_id']                = arrayKeyExist($discount, 'subpackage_id');
                                                    $discount_field['combo_campaign_duration_id']   = arrayKeyExist($discount, 'combo_campaign_duration_id');
                                                    $discount_field['combo_payment_term_id']        = arrayKeyExist($discount, 'combo_payment_term_id');
                                                    $discount_field['name']                         = arrayKeyExist($discount, 'name');
                                                    $discount_field['minimum_discount']             = arrayKeyExist($discount, 'minimum_discount');
                                                    $discount_field['maximum_discount']             = arrayKeyExist($discount, 'maximum_discount');
                                                    $payment_term_field['discount'][]               = $discount_field;
                                                    }
                                                }

                                                $campaign_duration_field['payment_term'][] = $payment_term_field;
                                            }
                                        }

                                        $combo_field['campaign_duration'][] = $campaign_duration_field;
                                }
                                }

                                $sub_package_field['combo'][] = $combo_field;
                            }
                        }

                        $data['sub_package'][] = $sub_package_field;
                    }
            }

            $data['account_type_administrator'] = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            $template                           = recastArray(cURLApiRestGET($CONFIGURATION['WORKFLOW_API_URL'].'/api/template/', idDecrypt($CONFIGURATION['WORKFLOW_API_USERNAME']), idDecrypt($CONFIGURATION['WORKFLOW_API_PASSWORD'])));
            
            if(!empty($template)){
                    $data['workflow_template'] = $template['data'];
            }
            views('master.service-sub-package', $data);
        }

        public function managePackageJson(){

            $CONFIGURATION        = Configuration::general();
            $result               = array();
            $name                 = postVar('name');
            $id                   = postVar('id');
            $template_id          = postVar('template_id', 0);
            $campaign_duration_id = postVar('campaign_duration_id', 0);
            $payment_term_id      = postVar('payment_term_id'     , 0);
            $package_id           = postVar('package_id'     , 0);
            $field_data           = postVar('field');
            
            if(isset($_POST) && !empty($_POST)){
                    if(!empty($id) && !empty($name) && $_POST['action'] == 'package_edit'){
                        $field['id']                             = $id;
                        $field['name']                           = $name;
                        if(!empty($template_id)){
                            $field['workflow_template_id']           = $template_id;
                        }
                        $result = Master::editDynamic('master_service_package', $field);
                        


                    }

                    else if(!empty($id) && !empty($name) && $_POST['action'] == 'sub_package_edit'){
                        $field['id']   = $id;
                        $field['name'] = $name;

                        $result = Master::editDynamic('master_service_subpackage', $field);
                    }
                    else if(!empty($id) && !empty($name) && $_POST['action'] == 'sub_package_add'){
                        $field['package_id']   = $id;
                        $field['name']         = $name;

                        $result = Master::addDynamic('master_service_subpackage', $field);
                    }

                    // COMBO
                    else if(!empty($id) && !empty($name) && $_POST['action'] == 'combo_add'){
                        $field['package_id']              = $package_id;
                        $field['subpackage_id']           = $id;
                        $field['name']                    = $name;
                        $result = Master::addDynamic('master_service_combo', $field);
                        
                    }
                    else if(!empty($id) && !empty($name) && $_POST['action'] == 'combo_edit'){
                        $field['id']                      = $id;
                        $field['name']                    = $name;

                        $result = Master::editDynamic('master_service_combo', $field);
                    }
                    else if(!empty($id) && $_POST['action'] == 'combo_update_details'){
                        $field['id']              = $id;
                        $field[$field_data]       = $name;

                        $result = Master::editDynamic('master_service_combo', $field);
                    }

                    // CAMPAIGN DURATION
                    else if (!empty($id) && !empty($campaign_duration_id) && $_POST['action'] == 'campaign_duration_add') {
                        $field['package_id']            = $package_id;
                        $field['subpackage_id']         = $id;
                        $field['combo_id']              = postVar('combo_id');
                        $field['name']                  = '';
                        $field['campaign_duration_id']  = $campaign_duration_id;
                        
                    
                        $result = Master::addDynamic('master_service_combo_campaign_duration', $field);
                    }
                    else if(!empty($id) && !empty($campaign_duration_id) && $_POST['action'] == 'campaign_duration_edit'){
                        $field['id']                      = $id;
                        $field['campaign_duration_id']    = $campaign_duration_id;
                        
                        $result = Master::editDynamic('master_service_combo_campaign_duration', $field);
                    }
                    else if (!empty($id) && $_POST['action'] == 'campaign_duration_update_details') {
                        $field['id'] = $id;
                        $field[$field_data] = $name;
                    
                    
                        $result = Master::editDynamic('master_service_combo_campaign_duration', $field);
                    }

                    // PAYMENT TERM
                    else if(!empty($id) && !empty($payment_term_id) && $_POST['action'] == 'payment_term_add'){
                        $field['name']                                = '';
                        $field['package_id']                          = $package_id;
                        $field['subpackage_id']                       = $id;
                        $field['combo_campaign_duration_id']  = postVar('campaign_duration_id');
                        $field['combo_id']                            = postVar('combo_id');
                        $field['payment_term_id']                     = $payment_term_id;
                        $field['freebie_ids']                         = postVar('freebie_ids'); // Save selected freebies
                        $result = Master::addDynamic('master_service_combo_payment_term', $field);
                    }
                    else if(!empty($id) && !empty($payment_term_id) && $_POST['action'] == 'payment_term_edit'){
                        $field['id']                      = $id;
                        $field['payment_term_id']         = $payment_term_id;

                        $result = Master::editDynamic('master_service_combo_payment_term', $field);
                    }
                    else if(!empty($id) && $_POST['action'] == 'payment_term_update_details'){
                        $field['id']              = $id;
                        $field[$field_data]       = $name;
                        
                        
                        // Handle freebie updates
                        if ($field_data === 'freebie_ids') {
                            $field['freebie_ids'] = postVar('freebie_ids');
                        }
                        
                        $result = Master::editDynamic('master_service_combo_payment_term', $field);
                        
                        
                    }

                    // DISCOUNT
                    else if(!empty($id) && !empty($name) && $_POST['action'] == 'discount_add'){
                        $field['package_id']                   = $package_id;
                        $field['subpackage_id']                = $id;
                        //$field['package_combo_id']             = postVar('combo_id');
                        $field['combo_campaign_duration_id']   = postVar('campaign_duration_id');
                        $field['combo_payment_term_id']        = postVar('payment_term_id');
                        $field['name']                         = $name;

                        $result = Master::addDynamic('master_service_combo_payment_term_discount', $field);
                        
                    }
                    else if(!empty($id) && !empty($name) && $_POST['action'] == 'discount_edit'){
                        $field['id']                      = $id;
                        $field['name']                    = $name;

                        $result = Master::editDynamic('master_service_combo_payment_term_discount', $field);
                    }
                    else if(!empty($id) && $_POST['action'] == 'discount_update_details'){
                        $field['id']              = $id;
                        $field[$field_data]       = $name;
                        
                        $result = Master::editDynamic('master_service_combo_payment_term_discount', $field);
                    }


                    // DELETE
                    else if ($_POST['action'] == 'sub_package_delete') {
                        // delete related combos and their child records
                        $combos = Master::getComboBySubPackageId($id);
                        if (is_array($combos)) {
                            foreach ($combos as $combo) {
                                $campaigns = Master::getCampaignDurationByComboId($combo['id']);
                                if (is_array($campaigns)) {
                                    foreach ($campaigns as $campaign) {
                                        $payments = Master::getPaymentTermByCampaignDurationId($campaign['id']);
                                        if (is_array($payments)) {
                                            foreach ($payments as $payment) {
                                                $discounts = Master::getDiscountByPaymentTermId($payment['id']);
                                                if (is_array($discounts)) {
                                                    foreach ($discounts as $discount) {
                                                        Master::deleteDynamic('master_service_combo_payment_term_discount', $discount['id']);
                                                    }
                                                }
                                                Master::deleteDynamic('master_service_combo_payment_term', $payment['id']);
                                            }
                                        }
                                        Master::deleteDynamic('master_service_combo_campaign_duration', $campaign['id']);
                                    }
                                }
                                Master::deleteDynamic('master_service_combo', $combo['id']);
                            }
                        }
                        $result = Master::deleteDynamic('master_service_subpackage', $id);
                    } 
                    else if ($_POST['action'] == 'combo_delete') {
                        $campaigns = Master::getCampaignDurationByComboId($id);
                        if (is_array($campaigns)) {
                            foreach ($campaigns as $campaign) {
                                $payments = Master::getPaymentTermByCampaignDurationId($campaign['id']);
                                if (is_array($payments)) {
                                    foreach ($payments as $payment) {
                                        $discounts = Master::getDiscountByPaymentTermId($payment['id']);
                                        if (is_array($discounts)) {
                                            foreach ($discounts as $discount) {
                                                Master::deleteDynamic('master_service_combo_payment_term_discount', $discount['id']);
                                            }
                                        }
                                        Master::deleteDynamic('master_service_combo_payment_term', $payment['id']);
                                    }
                                }
                                Master::deleteDynamic('master_service_combo_campaign_duration', $campaign['id']);
                            }
                        }
                        $result = Master::deleteDynamic('master_service_combo', $id);
                    }
                    else if ($_POST['action'] == 'campaign_delete') {
                        $payments = Master::getPaymentTermByCampaignDurationId($id);
                        if (is_array($payments)) {
                            foreach ($payments as $payment) {
                                $discounts = Master::getDiscountByPaymentTermId($payment['id']);
                                if (is_array($discounts)) {
                                    foreach ($discounts as $discount) {
                                        Master::deleteDynamic('master_service_combo_payment_term_discount', $discount['id']);
                                    }
                                }
                                Master::deleteDynamic('master_service_combo_payment_term', $payment['id']);
                            }
                        }
                        $result = Master::deleteDynamic('master_service_combo_campaign_duration', $id);
                    }
                    else if ($_POST['action'] == 'payment_delete') {
                        $discounts = Master::getDiscountByPaymentTermId($id);
                        if (is_array($discounts)) {
                            foreach ($discounts as $discount) {
                                Master::deleteDynamic('master_service_combo_payment_term_discount', $discount['id']);
                            }
                        }
                        $result = Master::deleteDynamic('master_service_combo_payment_term', $id);
                    }
                    else if ($_POST['action'] == 'discount_delete') {
                        $result = Master::deleteDynamic('master_service_combo_payment_term_discount', $id);
                    }
                    
                    else{
                        $result['status']  = 'failed';
                        $result['message'] = 'Something Went Wrong!';
                    }
            }else{
                    $result['status']  = 'forbidden';
                    $result['message'] = 'Access to this resource on the server is denied';
            }
            echo json_encode($result);
        }


        public function serviceFreebie(){
            accessRole(['1', '58']);

            $data                      = array();
            $CONFIGURATION             = Configuration::general();
            $campaign_duration_id      = idDecrypt(getVar('id'));

            $data['freebie']           = Master::getFreebies();
                
            
            $data['account_type_administrator']  = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.service-freebie', $data);  
        }

        public function freebieJson(){
            if(isset($_POST) && !empty($_POST)){

                    if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                        $record = Master::getDynamicById('master_service_freebie', $_POST['id']);
                        if(is_array($record)){    
                            foreach($record as $row){
                                $result['id']                           = htmlDecode($row['id']);
                                $result['name']                         = htmlDecode($row['name']);
                            }
                        }

                    else{
                            $result['status']  = 'failed';
                            $result['message'] = 'Something Went Wrong!';
                        }
                    }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                        $result = Master::deleteDynamic('master_service_freebie', $_POST['id']);
                        
                    }else{
                        $id                                     = postVar('id',0);
                        $field['name']                          = postVar('name');
                    
                        $data = checkRequiredPost(array('name'));

                        if(!array_key_exists('error', $data)){  

                            if (!empty($id)){
                                
                                $field['id']                = $id;
                                $field['updated_by']        = ACCOUNT_ID;
                                $field['updated_when']      = dateTimeStamp();
                                
                                $result = Master::editDynamic('master_service_freebie', $field);
                                
                            }else{
                                $field['created_by']        = ACCOUNT_ID;
                                $field['created_when']      = dateTimeStamp();

                                $result = Master::addDynamic('master_service_freebie', $field);
                                
                                
                                
                            }
                            
                        }else{
                            $result['status']  = 'failed';
                            $result['message'] = 'Please check all required fields';
                        }
                    }

            }else{
                    $result['status']  = 'forbidden';
                    $result['message'] = 'Access to this resource on the server is denied';
            }

            echo json_encode($result); 
        }

        public function subPackageByPackageJson(){
            $data          = array();
            $CONFIGURATION = Configuration::general();

            $package_id = postVar('package_id');

            $result = recastArray(Master::getSubPackageByPackageId($package_id));

            echo json_encode($result); 
            
        } 

        
        public function comboBySubPackageJson(){
            $data          = array();
            $CONFIGURATION = Configuration::general();

            $subpackage_id = postVar('subpackage_id');

            $result = recastArray(Master::getComboBySubPackageId($subpackage_id));
            echo json_encode($result); 
            
        }

        public function campaignDurationBySubPackageJson(){
            $result          = array();
            $CONFIGURATION = Configuration::general();

            $subpackage_id = postVar('subpackage_id');

            $result = recastArray(Master::getCampaignDurationBySubPackageId($subpackage_id));
            echo json_encode($result); 
            
        }

        public function campaignDurationByComboJson(){
            $result          = array();
            $CONFIGURATION = Configuration::general();

            $combo_id = postVar('combo_id');

            $result = recastArray(Master::getCampaignDurationByComboId($combo_id));
            echo json_encode($result); 
            
        }

        public function paymentTermByCampaignDurationJson(){
            $result          = array();
            $CONFIGURATION = Configuration::general();

            $campaign_duration_id = postVar('campaign_duration_id');

            $result = recastArray(Master::getPaymentTermByCampaignDurationId($campaign_duration_id));
            echo json_encode($result); 
            
        }

        public function discountByPaymentTermJson(){
            $result          = array();
            $CONFIGURATION = Configuration::general();

            $payment_term_id = postVar('payment_term_id');

            $result = recastArray(Master::getDiscountByPaymentTermId($payment_term_id));
            echo json_encode($result); 
            
        }

        public function getPerPackageJson(){
                includeModel(['Package','Master']);
                $result                = array();
                $CONFIGURATION         = Configuration::general();

                $package_id            = postVar('package_id');
                $subpackage_id         = postVar('subpackage_id');
                $campaign_duration_id  = postVar('campaign_duration_id');
                $combo_id              = postVar('combo_id');
                $payment_term_id       = postVar('payment_term_id');

                if($_POST['action'] == 'get-subpackage'){
                    $result = recastArray(Master::getSubPackageByPackageId($package_id));
                }

                else if($_POST['action'] == 'get-freebie'){
                    $result['freebies']                     = '';
                    if(!empty($combo_id)){

                        $payment_term_details                   = recastArray(Master::getPaymentTermByComboIdAndCampaignDurationIdAndPaymentTermId($combo_id,$campaign_duration_id,$payment_term_id));
                        if(!empty($payment_term_details)){
                            $freebie_ids                       = isset($payment_term_details['freebie_ids']) && !empty($payment_term_details['freebie_ids']) ? explode('-',$payment_term_details['freebie_ids']) : '';
                            $result['payment_term_details']    = $payment_term_details;
                            $result['freebies']                = $freebie_ids != '' ? Master::getPackageFreebieByIds($freebie_ids) : '';
                    }
                }
                }
                else if($_POST['action'] == 'get-combo'){
                    $result = Master::getComboBySubPackageId($subpackage_id);
                }
                else if($_POST['action'] == 'get-campaign-duration'){
                    $result['campaign_durations']      = '';
                    $result['combo_details']           = '';
                    if(!empty($combo_id)){
                        $package_campaign_durations = Master::getCampaignDurationByComboId($combo_id);
                            
                        if(!empty($package_campaign_durations) && is_array($package_campaign_durations)){
                            $campaign_durations_ids = [];
                            foreach($package_campaign_durations as $value){
                                    if(!in_array($value['campaign_duration_id'],$campaign_durations_ids)){
                                    $campaign_durations_ids[] = $value['campaign_duration_id'];
                                    }
                            }
                            $result['campaign_durations'] = Master::getDynamicIn('master_campaign_duration','id',$campaign_durations_ids, 'sort ASC');
                        }else{
                            $result['campaign_durations']   = Master::getDynamic('master_campaign_duration', 'sort ASC');
                        }
                        $result['combo_details']      = recastArray(Master::getComboById($combo_id));
                    }else{
                        $result['campaign_durations']   = Master::getDynamic('master_campaign_duration', 'sort ASC');
                    }

                    

                }
                else if($_POST['action'] == 'get-payment-term'){
                    
                    if(!empty($combo_id)){

                        $campaign_duration_payment_terms = Master::getPaymentTermByComboIdAndCampaignDurationIdAndPaymentTermId($combo_id,$campaign_duration_id);
                        
                        if(!empty($campaign_duration_payment_terms) && is_array($campaign_duration_payment_terms)){
                            $payment_term_ids = [];
                            foreach($campaign_duration_payment_terms as $value){
                                    if(!in_array($value['payment_term_id'],$payment_term_ids)){
                                    $payment_term_ids[] = $value['payment_term_id'];
                                    }
                            }
                            $result['payment_terms'] = Master::getDynamicIn('master_payment_term','id',$payment_term_ids, 'sort ASC');
                        }else{
                            $result['payment_terms']   = Master::getDynamic('master_payment_term', 'sort ASC');
                        }
                        // $result['campaign_duration_details']      = recastArray(Package::getCampaignDurationById($campaign_duration_id));

                    }else{
                        $result['payment_terms']   = Master::getDynamic('master_payment_term', 'sort ASC');
                    }
                    
                }
                else if($_POST['action'] == 'get-discount'){
                    $result['discount']                     = '';
                    $result['freebies']                     = '';
                    $result['payment_term_details']         = '';
                    if(!empty($combo_id)){

                        $payment_term_details                   = recastArray(Master::getPaymentTermByComboIdAndCampaignDurationIdAndPaymentTermId($combo_id,$campaign_duration_id,$payment_term_id));
                        if(!empty($payment_term_details)){
                            $freebie_ids                       = isset($payment_term_details['freebie_ids']) && !empty($payment_term_details['freebie_ids']) ? explode('-',$payment_term_details['freebie_ids']) : '';
                            $result['payment_term_details']    = $payment_term_details;
                            $result['discount']                = recastArray(Master::getDiscountByPaymentTermId($payment_term_details['id']));
                            $result['freebies']                = $freebie_ids != '' ? Master::getPackageFreebieByIds($freebie_ids) : '';
                        }
                    }
                    // $result = recastArray(Package::getDiscountByPaymentTermId($payment_term_id));

                }

                echo json_encode($result);

        }


        public function importPackageJson(){
            $data        = Array();
            
            $CONFIGURATION = Configuration::general();
            $service_id  = postVar('service_id');

            if(isset($_POST) && !empty($_POST)){
                    $field['package_service_id'] = $service_id;
                    $field['name']               = postVar('name');

                    $result = Master::addDynamic('master_service_package', $field);
            }else{
                    $result['status']  = 'forbidden';
                    $result['message'] = 'Access to this resource on the server is denied';
                }
            
            
        
            echo json_encode($result);
        }

        public function duplicatePackageJson() {
            $package_id = postVar('package_id');
        
            if (empty($package_id)) {
                echo json_encode(['status' => 'failed', 'message' => 'Package ID is required']);
                return;
            }
        
            // Fetch the original package
            $package = recastArray(Master::getPackageById($package_id));
            if (empty($package)) {
                echo json_encode(['status' => 'failed', 'message' => 'Package not found']);
                return;
            }
        
            // create a new package with "(copy)" added to the name
            $new_package = [];
            $new_package['service_id']           = $package['service_id'];
            $new_package['name']                 = $package['name'] . " (copy)";
            $new_package['status_id']            = $package['status_id'];
            $new_package['workflow_template_id'] = $package['workflow_template_id'];

            
            $new_package_id = recastArray(Master::addDynamic('master_service_package', $new_package));
        
            if (!$new_package_id) {
                echo json_encode(['status' => 'failed', 'message' => 'Failed to duplicate package']);
                return;
            }
        
            // Fetch and duplicate sub-packages
            $sub_packages = Master::getSubPackageByPackageId($package_id);
            if (!empty($sub_packages) && is_array($sub_packages)) {
                foreach ($sub_packages as $sub_package) {
                        $new_sub_package                = [];
                        $new_sub_package['package_id']  = $new_package_id['id'];
                        $new_sub_package['name']        = $sub_package['name'];

                        $new_sub_package_id = recastArray(Master::addDynamic('master_service_subpackage', $new_sub_package));
        
                    // Fetch and duplicate combos
                    $combos = Master::getComboBySubPackageId($sub_package['id']);
                    if (!empty($combos) && is_array($combos)) {
                        foreach ($combos as $combo) {
                            $new_combo                           = [];
                            $new_combo['subpackage_id']          = $new_sub_package_id['id'];
                            $new_combo['package_id']             = $new_package_id['id'];
                            $new_combo['name']                   = $combo['name'];
                            $new_combo['period_id']              = $combo['period_id'];
                            $new_combo['deliverables']           = $combo['deliverables'];
                            $new_combo['management_fee']         = $combo['management_fee'];
                            $new_combo_id                       = recastArray(Master::addDynamic('master_service_combo', $new_combo));
        
                            // Fetch and duplicate campaign durations
                            $campaign_durations = Master::getCampaignDurationByComboId($combo['id']);
                            if (!empty($campaign_durations) && is_array($campaign_durations)) {
                                foreach ($campaign_durations as $campaign_duration) {
                                        $new_campaign_duration                           = [];
                                        $new_campaign_duration['combo_id']               = $new_combo_id['id'];
                                        $new_campaign_duration['package_id']             = $new_package_id['id'];
                                        $new_campaign_duration['subpackage_id']          = $campaign_duration['id'];
                                        $new_campaign_duration['campaign_duration_id']   = $campaign_duration['campaign_duration_id'];
                                        $new_campaign_duration['name']                   = $campaign_duration['name'];

                                    $new_campaign_duration_id = recastArray(Master::addDynamic('master_service_combo_campaign_duration', $new_campaign_duration));
        
                                    // Fetch and duplicate payment terms
                                    $payment_terms = Master::getPaymentTermByCampaignDurationId($campaign_duration['id']);
                                    if (!empty($payment_terms) && is_array($payment_terms)) {
                                        foreach ($payment_terms as $payment_term) {
                                            $new_payment_term                                        = [];
                                            $new_payment_term['combo_id']                            = $new_combo_id['id'];
                                            $new_payment_term['combo_campaign_duration_id']          = $new_campaign_duration_id['id'];
                                            $new_payment_term['subpackage_id']                       = $new_sub_package_id['id'];
                                            $new_payment_term['package_id']                          = $new_package_id['id'];
                                            $new_payment_term['payment_term_id']                     = $payment_term['payment_term_id'];
                                            $new_payment_term['freebie_ids']                         = $payment_term['freebie_ids'];
                                            $new_payment_term['name']                                = $payment_term['name'];
                                            $new_payment_term_id = recastArray(Master::addDynamic('master_service_combo_payment_term', $new_payment_term));
        
                                            // Fetch and duplicate discounts
                                            $discounts = Master::getDiscountByPaymentTermId($payment_term['id']);
                                            if (!empty($discounts) && is_array($discounts)) {
                                                foreach ($discounts as $discount) {
                                                    $new_discount                                        = [];
                                                    $new_discount['combo_payment_term_id']               = $new_payment_term_id['id'];
                                                    $new_discount['combo_campaign_duration_id']          = $new_campaign_duration_id['id'];
                                                    $new_discount['subpackage_id']                       = $new_sub_package_id['id'];
                                                    $new_discount['package_id']                          = $new_package_id['id'];
                                                    $new_discount['name']                                = $discount['name'];
                                                    $new_discount['minimum_discount']                    = formatMoney($discount['minimum_discount']);
                                                    $new_discount['maximum_discount']                    = formatMoney($discount['maximum_discount']);
                                                    Master::addDynamic('master_service_combo_payment_term_discount', $new_discount);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        

            // Redirect to the newly duplicated package page
            echo json_encode([
                'status' => 'success',
                'message' => 'Package duplicated successfully',
                'redirect_url' => '/master/service-sub-package/' . idEncrypt($new_package_id['id'])
            ]);
        }


        public function period(){
            $data          = array();
            $CONFIGURATION = Configuration::general();

            // $data['company'] = Master::getDynamic('master_account_company');
            $data['package_period']              = Master::getDynamic('master_package_period');
            $data['account_type_administrator']  = $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR'];

            views('master.period', $data);  
        }

        public function periodJson(){
            $CONFIGURATION = Configuration::general();
            
            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Master::getDynamicById('master_package_period', $_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['name']             = htmlDecode($row['name']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Master::deleteDynamic('master_package_period', $_POST['id']);

                    
                    
                }else{
                    $id                                     = htmlEncode($_POST['id']);
                    $field['name']                          = htmlEncode($_POST['name']);

                    $data = checkRequiredPost(array('name'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Master::editDynamic('master_package_period', $field);

                            
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Master::addDynamic('master_package_period', $field);

                            
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

    }
?>