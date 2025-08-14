<?php
    class MasterController{

        public function __construct() {

        }

        public function accountRole(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            $data['record'] = Master::getDynamic('master_account_role');

            views('master.account-role', $data);  
        }

        public function accountRoleJson(){

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

            views('master.account-status', $data);  
        }

        public function accountStatusJson(){

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

            views('master.account-type', $data);  
        }

        public function accountTypeJson(){

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

            views('master.account-team', $data);  
        }

        public function accountTeamJson(){

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

            views('master.account-level', $data);  
        }

        public function accountLevelJson(){

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

            $data['departments']   = Master::getDynamic('master_account_department');
            $data['account_roles'] = Master::getDynamic('master_account_role');

            views('master.account-department', $data);  
        }

        public function accountDepartmentJson(){

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

            views('master.account-designation', $data);  
        }

        public function accountDesignationJson(){

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

        public function teamLeader(){
            $data['team_leaders']   = Master::getTeamLeader();
            views('master.team-leader', $data);
        }

        public function segment(){
            $data['segments']   = Master::getSegment();
            views('master.segment', $data);
        }

        public function handler(){
            $data['handlers']   = Master::getHandler();
            views('master.handler', $data);
        }
        
    }
?>