<?php
    class AccountController{

        public function __construct() {
            
        }

        // public function login(){
        //     checkLoggedIn('false');
        //     $data                   = array();
        //     $CONFIGURATION          = Configuration::general();
        //     $data['site_key']   = $CONFIGURATION['GOOGLE_RECAPTCHA_SITE_KEY'];

        //     if(isset($_POST['submit_form']) && !isset($_POST['google-signin'])){ 
        //         $field['username'] = htmlEncode($_POST['username']);
        //         $field['password'] = passwordEncode(htmlEncode($_POST['password']));       

        //         $data = checkRequiredPost(array('username', 'password'));

        //         if(!array_key_exists('error', $data)){

        //             //FROM HERE: API LOGIN FROM HRIS DATABASE
        //             $record = recastArray(Account::login($field['username'], $field['password']));
        //             if(!empty($record)){
        //                 //FROM HERE: UPDATE "ACCOUNT" TABLE, get data from HRIS login return msg. 
        //                 if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){  

        //                     $api_url = 'https://www.google.com/recaptcha/api/siteverify';  
        //                     $resq_data = array(  
        //                         'secret' => $CONFIGURATION['GOOGLE_RECAPTCHA_SECRET_KEY'],  
        //                         'response' => $_POST['g-recaptcha-response'],  
        //                         'remoteip' => $_SERVER['REMOTE_ADDR']  
        //                     );  
                
        //                     $curlConfig = array(  
        //                         CURLOPT_URL => $api_url,  
        //                         CURLOPT_POST => true,  
        //                         CURLOPT_RETURNTRANSFER => true,  
        //                         CURLOPT_POSTFIELDS => $resq_data  
        //                     );  
                
        //                     $ch = curl_init();  
        //                     curl_setopt_array($ch, $curlConfig);  
        //                     $response = curl_exec($ch);  
        //                     curl_close($ch);  
                
        //                     // Decode JSON data of API response in array  
        //                     $responseData = json_decode($response);  
            
        //                     if($responseData->success){ 
        //                         $_SESSION['login_id'] = idEncrypt($record['id']);
        //                         $ipAdress = getUserIpAddress();
        //                         if(isset($_POST['remember_me']) && !empty($_POST['remember_me'])){

        //                             $cookie_expiration_time = time() + $CONFIGURATION['COOKIES_EXPIRATION'];

        //                             $remember_me['username']   = $field['username'];
        //                             $remember_me['expiration'] = $cookie_expiration_time;

        //                             $token = safe_b64encode(serialize($remember_me));
        //                             setcookie("member", $token, $cookie_expiration_time,"/");

        //                             $post['id']          = $record['id'];
        //                             $post['remember_me'] = $cookie_expiration_time;
        //                             $post['ip_address']  = $ipAdress;
        //                             $post['relogin']     = '';
        //                             Account::editRecord($post);//set backend expiration
        //                         }else{

        //                             $post['id']          = $record['id'];
        //                             $post['ip_address']  = $ipAdress;
        //                             $post['relogin']     = '';
        //                             Account::editRecord($post);
        //                             setcookie("member","",1);
        //                         }

        //                         header('location: '.(!empty(getVar('redirect')) ? safe_b64decode(getVar('redirect')) : '/'));
                                
        //                     }else{  
        //                         $statusMsg = 'The reCAPTCHA verification failed, please try again.';  
        //                         promptMessage('message', $statusMsg , 'danger');
        //                     }  
        //                 }
                        
        //             }else{
        //                 $data['site_key']   = $CONFIGURATION['GOOGLE_RECAPTCHA_SITE_KEY'];
        //                 promptMessage('message', 'Invalid Username or Password. Please try again', 'danger');
        //             } 
        //         }   
        //     }

        //     if(isset($_POST['google-signin'])){
        //         includeDefault('google-signin');

        //         if(isset($_POST['remember_me']) && !empty($_POST['remember_me'])){
        //             $_SESSION['remember_me'] = 1;
        //         }

        //         $client = GoogleSignin::getClient();
        //         header('Location: '.$client->createAuthUrl());
        //         die();
        //     }

        //     views('account.login', $data);
        // }

        public function login(){
            checkLoggedIn('false');

            $data = array();

            if(isset($_POST['submit_form'])){ 
                $field['username'] = htmlEncode($_POST['username']);
                $field['password'] = htmlEncode($_POST['password']);       

                $data = checkRequiredPost(array('username', 'password'));

                if(!array_key_exists('error', $data)){   

                    $adServer = "ad.fpgins.com";
                    $ldapconn = ldap_connect($adServer) or die("Could not connect to LDAP server.");
                    $username = $field['username'];
                    $password = $field['password'];
                    $ldapuser = 'fpgins\\'.$username;
                    $ldaptree = "DC=ad,DC=fpgins,DC=com";
                    $filter   = "(samaccountname=$username)";
                    $find     = array("sn", "givenname", "samaccountname", "mail", "title");

                   

                    if($ldapconn){

                        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
                        ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

                        $ldapbind = @ldap_bind($ldapconn, $ldapuser, $password);// or die ("Error trying to bind: ".ldap_error($ldapconn));
                        
                        if($ldapbind){

                            $result = ldap_search($ldapconn, $ldaptree, $filter, $find) or die ("Error in search query: ".ldap_error($ldapconn));
                                    
                            $data   = ldap_get_entries($ldapconn, $result);

                            unset($field['password']);
                            $return = Account::ldap($field['username']);
                            if($return['status'] == 'success'){ 

                                $record = $return['record'];
                                // $_SESSION['login_session']      = $return['record']['id'].date('YmdHis');
                                $_SESSION['login_id'] = idEncrypt($record['id']);
                                ldap_close($ldapconn);
                                header('location: /');  
                            }else{
                                promptMessage('message', $return['message'], 'danger');
                            } 
                            
                            header('location: /');                             
                        }else{
                            ldap_close($ldapconn);
                            promptMessage('message', 'Username or Password is incorrect. Please try again or Please contact Service Desk for reset password.', 'danger');
                        }
                    }else{
                        ldap_close($ldapconn);
                        promptMessage('message', 'Cannot connect to server. Please check whether the Network or Active directory is available', 'danger');
                    }
                    
                }
                
            } 
            views('account.login', $data);
        }
       
    
        // public function googleSigninCallback(){
        //     checkLoggedIn('false');
        //     includeDefault('google-signin');

        //     $CONFIGURATION = Configuration::general();
            
        //     if ( isset($_GET['code']) ) {
        //         $client  = GoogleSignin::callback();
        //         $profile = GoogleSignin::getProfile($client);
        //         if ( !isset($profile['email']) ) {
        //             promptMessage('message', 'Problem logging in with Google. Please contact the developer.', 'danger');
        //             header('location: /login');
        //         }else if( isset($profile['email']) && substr($profile['email'], strpos($profile['email'], "@") + 1) !== $CONFIGURATION['SYSTEM_LOGIN_DOMAIN']){
        //             promptMessage('message', 'Your Google account is not allowed to access the system.', 'danger');
        //             header('location: /login');
        //         }else{
        //             $record = recastArray(Account::getRecordByEmail($profile['email']));
        //             if(!empty($record)){
        //                 if ( isset($record['account_status_id']) && $record['account_status_id'] != '1' ) {
        //                     promptMessage('message', 'There is a problem with your account. Please contact '.$CONFIGURATION['SYSTEM_COMPANY'].' ServiceDesk', 'danger');
        //                     header('location: /login');
        //                 }else{
        //                     $_SESSION['login_id']       = idEncrypt($record['id']);
        //                     $_SESSION['google_picture'] = arrayKeyExist($profile,'picture');
        //                     $ipAdress                   = getUserIpAddress();
        //                     if(isset($_SESSION['remember_me']) && !empty($_SESSION['remember_me'])){

        //                         $cookie_expiration_time = time() + $CONFIGURATION['COOKIES_EXPIRATION'];

        //                         $remember_me['username']   = $record['email'];
        //                         $remember_me['expiration'] = $cookie_expiration_time;

        //                         $token = safe_b64encode(serialize($remember_me));
        //                         setcookie("member", $token, $cookie_expiration_time,"/");

        //                         $post['id']          = $record['id'];
        //                         $post['remember_me'] = $cookie_expiration_time;
        //                         $post['ip_address']  = $ipAdress;
        //                         $post['relogin']     = '';
        //                         Account::editRecord($post);//set backend expiration
        //                     }else{
        //                         $post['id']          = $record['id'];
        //                         $post['ip_address']  = $ipAdress;
        //                         $post['relogin']     = '';
        //                         Account::editRecord($post);//set backend expiration
        //                         setcookie("member","",1);
        //                     }
                            
        //                     header('location: /');
        //                 }
        //             }else{
        //                 promptMessage('message', 'Your google account is not associated with any of '.$CONFIGURATION['SYSTEM_COMPANY'].' accounts.', 'danger');
        //                 header('location: /login');
        //             }
        //         }
        //     }else{
        //         promptMessage('message', 'Problem logging in with Goggle. Please contact '.$CONFIGURATION['SYSTEM_COMPANY'].' ServiceDesk', 'danger');
        //         header('location: /login');
        //     }
        //     exit;
        // }
        
        public function logout(){
            checkLoggedIn('true');

            $post['id']          = ACCOUNT_ID;
            $post['remember_me'] = "";
            Account::editRecord($post);//set backend expiration

            setcookie("member","",1);
            Account::logout();
            
            views('account.logout');
        }

        public function all(){
            $data = array();
            checkLoggedIn('true');
            accessRole(['10']);

            includeModel(['Master','Account']);

            $CONFIGURATION              = Configuration::general();  

            $department                 = getVar('department');
            $status                     = getVar('status');
            $keyword                    = urldecode(getVar('keyword'));

            $data['user']               = Account::getAccount($department, $status, $keyword, pagination('start'), pagination('limit'));
            $data['total_record']       = Account::countAccount($department, $status, $keyword);
            $data['total_page']         = pagination('total', $data['total_record']); 

            $data['account_type']       = Master::getDynamic('master_account_type');
            $data['account_status']     = Master::getDynamic('master_account_status');
            $data['account_department'] = Master::getDynamic('master_account_department');
            $data['account_role']       = Master::getDynamic('master_account_role');

            if(isset($_POST['submit-relogin'])){

                $exclude = array($CONFIGURATION['ACCOUNT_ID_IT']);
                $users   = Account::getActive($exclude);
                if(is_array($users)){
                    foreach($users as $key => $value){
                        $field['id']           = $value['account_id'];
                        $field['relogin']      = 'Yes';
                        $field['updated_when'] = dateTimeStamp();
                        $field['updated_by']   = ACCOUNT_ID;
                        Account::editRecord($field);
                    }
                }

                header('location: /');
            }
            if (!empty($data['user'])) {
                foreach ($data['user'] as $key => $user) {
                    if (empty($user['user_photo'])) {
                        $data['user'][$key]['user_photo'] = getSiteUrl().'/public/img/no-photo.jpg';
                    }
                }
            }
            
            views('account.all', $data); 
        }

        public function manage(){
            $data = array();

            $CONFIGURATION = Configuration::general();

            includeModel(['Master']);

            $account_id = idDecrypt(getVar('id'));
            
            if(isset($_POST['submit'])){

                if(isset($_POST['password']) && !empty($_POST['password'])){
                    $field['password'] = passwordEncode(postVar('password'));
                }

                $personal['first_name']               = postVar('first_name');
                $personal['last_name']                = postVar('last_name');
                $personal['middle_name']              = postVar('middle_name','');
                $personal['alias']                    = postVar('alias', '');
                $personal['gender']                   = postvar('gender','');
                $personal['nationality_id']           = postvar('nationality_id','');
                $personal['birthday']                 = dateSaveDB(postvar('birthday'));
                $personal['religion']                 = postvar('religion','');
                $personal['education']                = postvar('education','');
                $personal['marital_status']           = postvar('marital_status','');
                $personal['no_children']              = postvar('no_children', 0);
                $personal['contact_no']               = postvar('contact_no','');
                $personal['landline_no']              = postvar('landline_no','');
                $personal['email']                    = (isset($_POST['personal_email']) ? strtolower(postvar('personal_email','')) : '');
                $personal['nric_no']                  = postvar('nric_no','');
                $personal['address_current']          = postvar('address_current','');
                $personal['address_hometown']         = postvar('address_hometown','');
                $personal['medical_history']          = postvar('medical_history','');

                $bank['name']                         = postvar('bank_name','');
                $bank['account_no']                   = postvar('bank_account_no','');
                $bank['payee_name']                   = postvar('bank_payee_name','');
                $bank['code']                         = postvar('bank_code','');
                $bank['branch_code']                  = postvar('bank_branch_code','');

                $emergency['name']                    = postvar('emergency_contact_name','');
                $emergency['contact_no']              = postvar('emergency_contact_no','');
                $emergency['relationship']            = postvar('emergency_contact_relationship','');

                $employment['account_region_id']      = postvar('account_region_id',0);
                $employment['employee_no']            = postvar('employee_no',0);
                $employment['email']                  = strtolower(postVar('employee_email'));
                $employment['account_status_id']      = postVar('account_status_id', 0);
                $employment['account_type_id']        = postVar('account_type_id', 0);
                $employment['account_department_id']  = postVar('account_department_id', 0);
                $employment['account_designation_id'] = postVar('account_designation_id', 0);
                $employment['account_role_id']        = Shortcode::concatId($_POST['account_role_id']);
                $employment['start_date']             = dateSaveDB(postVar('start_date'));
                $employment['confirmation_date']      = dateSaveDB(postVar('confirmation_date'));
                $employment['exit_date']              = dateSaveDB(postVar('exit_date'));
                $employment['account_team_id']        = postVar('account_team_id',0);
                $employment['account_level_id']       = postVar('account_level_id',0);
                $employment['report_to']              = postvar('report_to', 0);
                $employment['employment_type_id']     = postvar('employment_type_id', 0);
                $equipment['computer_type']           = postvar('computer_type', '');
                $equipment['computer_serial_no']      = postvar('computer_serial_no', '');
                $equipment['locker_no']               = postvar('locker_no', '');
                $equipment['accessories']             = postvar('accessories', '');

                $field['relogin']                     = isset($_POST['relogin']) && $_POST['relogin'] == 'on' ? 'Yes' : '';

                if(!empty($account_id)){
                    $data = checkRequiredPost(array('employee_email'));
                }else{
                    $data = checkRequiredPost(array('employee_email','password'));
                }

                if(isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])){
                    $file_name          = $_FILES['file']['name'];
                    $file_size          = $_FILES['file']['size'];
                    $file_tmp           = $_FILES['file']['tmp_name'];
                    $file_type          = $_FILES['file']['type'];
                    $file_ext           = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                    $file_new_name      = $account_id.'.'.$file_ext;
                    $extensions         = $CONFIGURATION['ALLOWED_PHOTO'];
                    if(!in_array($file_ext, $extensions)){
                        $data['error']['file'] = requiredPrompt('File format is not allowed');
                    }else{
                        if(move_uploaded_file($file_tmp, uploadFile('account', $file_new_name))){                           
                            $personal['photo'] = $file_new_name;

                            thumbnailGenerate(fileUrl($file_new_name, '/file/account/'), uploadFile('account', thumbnailName($file_new_name)), "200");
                        }else{
                            promptMessage('message', 'Encounter technical error. Pls try again', 'danger');
                        }
                    }
                }
                
                if(isset($_POST['file_delete'])){
                    $personal['photo'] = '';
                    deleteFile('account', postVar('file_hidden'));
                    deleteFile('account', thumbnailName(postVar('file_hidden')));
                }

                if(!array_key_exists('error', $data)){
                    if(!empty($account_id)){
                        $field['id']                  = $account_id;
                        $field['updated_by']          = ACCOUNT_ID;
                        $field['updated_when']        = dateTimeStamp();

                        if(isset($_POST['transfer_to_house']) && $_POST['transfer_to_house'] == 'Yes'){
                            $field['transfer_leads'] = postVar('transfer_to_house');
                            $trasnferToHouse = Shortcode::transferToHouseSales($account_id);
                        }
                        $result = Account::editRecord($field);

                    }else{
                        $field['email']               = $employment['email'];
                        $field['created_by']          = ACCOUNT_ID;
                        $field['created_when']        = dateTimeStamp();
                        $result = Account::addRecord($field);
                    }

                    if($result['status'] == 'success'){
                        $account_id                   = $result['id'];
                        $bank['account_id']           = $account_id;  
                        $personal['account_id']       = $account_id;
                        $emergency['account_id']      = $account_id;
                        $employment['account_id']     = $account_id;
                        $equipment['account_id']      = $account_id;
                        
                        if(isset($_POST['add_user_to_blacklist']) && $_POST['add_user_to_blacklist'] == 'Yes'){
                            $blacklist['account_id']  = $account_id;
                            $blacklist['first_name']  = $personal['first_name'];
                            $blacklist['last_name']   = $personal['last_name'];
                            $blacklist['alias']       = $personal['alias'];
                            $blacklist['email']       = $personal['email'];
                            $blacklist['contact_no']  = $personal['contact_no'];
                            $blacklist['birthday']    = $personal['birthday'];
                            $blacklist['remarks']     = postvar('add_user_to_blacklist_remarks','');
                            $blacklist                = Account::manageDynamic('account_blacklist', $blacklist);
                        }
                        
                        $personal   = Account::manageDynamic('account_personal', $personal);
                        $bank       = Account::manageDynamic('account_bank', $bank);
                        $emergency  = Account::manageDynamic('account_emergency_contact', $emergency);
                        $employment = Account::manageDynamic('account_employment', $employment);
                        $equipment  = Account::manageDynamic('account_equipment', $equipment);

                        alertAndRedirect($result['message'], '/account/manage/'.idEncrypt($account_id));
                    }
                }  
            }

            $data['account']                    = recastArray(Account::getRecordById($account_id));
            $data['account_all']                = Account::getByStatusId($CONFIGURATION['ACCOUNT_STATUS_ACTIVE']);
            $data['account_personal']           = recastArray(Account::getDynamicByAccountId('account_personal',$account_id));
            $data['account_bank']               = recastArray(Account::getDynamicByAccountId('account_bank',$account_id));
            $data['account_emergency_contact']  = recastArray(Account::getDynamicByAccountId('account_emergency_contact',$account_id));
            $data['account_employment']         = recastArray(Account::getDynamicByAccountId('account_employment',$account_id));
            $data['account_blacklist']          = recastArray(Account::getDynamicByAccountId('account_blacklist',$account_id));
            $data['account_equipment']          = recastArray(Account::getDynamicByAccountId('account_equipment',$account_id));
            $data['account_promotion']          = Account::getAccountPromotionByAccountId($account_id);
            $data['account_department']         = Master::getDynamic('master_account_department');
            $data['account_level']              = Master::getDynamic('master_account_level');
            $data['account_type']               = Master::getDynamic('master_account_type');
            $data['account_status']             = Master::getDynamic('master_account_status');
            $data['account_role']               = Master::getDynamic('master_account_role');
            $data['account_team']               = Master::getDynamic('master_account_team');
            $data['region']                     = Master::getDynamic('master_region');
            $data['nationality']                = Master::getDynamic('master_country');
            $data['employment_type']            = Master::getDynamic('master_employment_type');

            if(!empty($data['account_role'])){
                $ctr_role          = 1;
                $data['role_list'] = '';
                $arr_list_role     = array();

                foreach($data['account_role'] as $key_list_role => $value_list_role){
                    if(!in_array($value_list_role['id'], $arr_list_role)){ 

                        if($ctr_role < count($data['account_role'])){
                            $connector = '+';
                        }else{
                            $connector = '';
                        }

                        $data['role_list'] .= $value_list_role['id'].'_'.$value_list_role['name'].'_'.$value_list_role['description'].$connector;
                        array_push($arr_list_role, $value_list_role['id']);
                    }

                    $ctr_role++;
                } 
            }

            views('account.manage', $data); 
        }

        public function deleteJson(){
            checkLoggedIn('true');
            //DOUBLE CHECK IF REALLY NEED TO DELETE 
            //OR CHANGE THE STATUS TO DELETED : add new STATUS "DELETED"
            if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){
                $result = Account::deleteRecord($_POST['id']);
                          Account::deleteAccountPersonalById($_POST['id']);
                          Account::deleteAccountBankById($_POST['id']);
                          Account::deleteAccountEmergencyContactById($_POST['id']);
                          Account::deleteAccountEmploymentById($_POST['id']);
                          Account::deleteAccountEquipmentById($_POST['id']);
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            header('Content-Type: application/json');
            echo json_encode($result);
        }
        
        public function user(){
            $data = array();
            $CONFIGURATION = Configuration::general();

            includeModel(['Master']);
            
            $departments                = ACCOUNT_DEPARTMENT_ID; 
            $status                     = '';
            $keyword                    = urldecode(getVar('keyword'));
            $round_robin                = getVar('round_robin') === 'Yes' ? 'Yes' : '';
            $auto_allocate              = getVar('auto_allocate') === 'Yes' ? 'Yes' : '';

            $data['user']               = Account::getAccount($departments, $status, $keyword, pagination('start'), pagination('limit'), $round_robin,$auto_allocate);
            $data['total_record']       = Account::countAccount($departments, $status, $keyword, $round_robin,$auto_allocate);
            $data['total_page']         = pagination('total', $data['total_record']); 

            $data['account_all']        = Account::getByStatusId($CONFIGURATION['ACCOUNT_STATUS_ACTIVE']);
            $data['account_department'] = Master::getDynamic('master_account_department');
            $data['account_level']      = Master::getDynamic('master_account_level');
            $data['account_type']       = Master::getDynamic('master_account_type');
            $data['employment_type']    = Master::getDynamic('master_employment_type');
            $data['account_team']       = Master::getDynamicNotIn('master_account_team', 'id', $CONFIGURATION['ACCOUNT_TEAM_HOUSE']); 

            views('account.user', $data); 
        }

        public function userJson(){
            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['account_id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){
                    
                    $record = Account::getRecordById($_POST['account_id']);
                    if(is_array($record)){  
                        $result = recastArray($record);  
                    }
                    
                }else{
                    $account_id                           = htmlEncode($_POST['account_id']);

                    $personal['account_id']               = $account_id;
                    $personal['first_name']               = postVar('first_name');
                    $personal['last_name']                = postVar('last_name');
                    $personal['middle_name']              = postVar('middle_name');
                    $personal['alias']                    = postVar('alias');
                    $personal['contact_no']               = postVar('contact_no');
                    $personal['updated_by']               = ACCOUNT_ID;
                    $personal['updated_when']             = dateTimeStamp();

                    $employment['account_id']             = $account_id;
                    $employment['account_department_id']  = postVar('account_department_id', 0);
                    $employment['account_designation_id'] = postVar('account_designation_id', 0);
                    $employment['monthly_sales_target']   = moneyClean(postVar('monthly_sales_target'));
                    $employment['minimum_sales_target']   = moneyClean(postVar('minimum_sales_target'));
                    $employment['maximum_sales_target']   = moneyClean(postVar('maximum_sales_target'));
                    $employment['account_team_id']        = postVar('account_team_id',0);
                    $employment['account_level_id']       = postVar('account_level_id',0);
                    $employment['report_to']              = postvar('report_to', 0);
                    $employment['employment_type_id']     = postvar('employment_type_id', 0);
                    $employment['client_appointment']     = isset($_POST['client_appointment']) && $_POST['client_appointment'] == 'on' ? 'Yes' : '';
                    $employment['auto_allocate_leads']     = isset($_POST['auto_allocate_leads']) && $_POST['auto_allocate_leads'] == 'on' ? 'Yes' : '';
                    $employment['updated_by']             = ACCOUNT_ID;
                    $employment['updated_when']           = dateTimeStamp();

                    $data = checkRequiredPost(array('account_id'));

                    if(!array_key_exists('error', $data) && !empty($account_id)){
                        Account::manageDynamic('account_personal', $personal);
                        $result = Account::manageDynamic('account_employment', $employment);
                    }else{
                        $result['status']  = 'failed';
                        $result['message'] = 'No record found';
                    }
                }

            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function perTeamJson(){

            if(isset($_POST['id']) && !empty($_POST['id'])){
                $record = Account::getUnitGroupByUnitTeamId($_POST['id']);
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

        public function perGroupJson(){

            if(isset($_POST['id']) && !empty($_POST['id'])){
                includeModel(['Account']);
                $business = recastArray(Account::getUnitGroupById($_POST['id']));
                $account  = Account::getRecordByDesignationId($business['account_designation_id']);

                if(is_array($account)){   
                    $result = $account; 
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

        public function forgotPassword(){
            checkLoggedIn('false');
            $data = array();
            $CONFIGURATION = Configuration::general();
            $data['site_key']   = $CONFIGURATION['GOOGLE_RECAPTCHA_SITE_KEY'];
            includeDefault(['email']);
             
            if(isset($_POST['submit_form'])){
                
                $field['email'] = postVar('email','');

                $data = checkRequiredPost(array('email'));
                if(!isset($data['error']['email'])){
                    $record = recastArray(Account::getRecordByEmail($field['email']));
                    if(!is_array($record)){
                        $data['error']['email'] = requiredPrompt('Your email is not associated to any of the account.');
                        $data['site_key']       = $CONFIGURATION['GOOGLE_RECAPTCHA_SITE_KEY'];
                    }
                }

                if(!array_key_exists('error', $data)){   
                    $permitted_chars           = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $generated_time            = time();
                    $date                      = date('Y-m-d H:i:s',$generated_time);
                    $random_code               = substr(str_shuffle($permitted_chars), 0, 5);
                    $user_id                   = displayVarWithDefault( arrayKeyExist($record,'id'),'' );
                    $post['id']                = $user_id;
                    $post['verification_code'] = $random_code.'|'.$generated_time;
                    $addVerificationCode       = Account::editRecord($post);

                    if($addVerificationCode['status'] == 'success'){
                        if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){  

                            $api_url = 'https://www.google.com/recaptcha/api/siteverify';  
                            $resq_data = array(  
                                'secret' => $CONFIGURATION['GOOGLE_RECAPTCHA_SECRET_KEY'],  
                                'response' => $_POST['g-recaptcha-response'],  
                                'remoteip' => $_SERVER['REMOTE_ADDR']  
                            );  
                
                            $curlConfig = array(  
                                CURLOPT_URL => $api_url,  
                                CURLOPT_POST => true,  
                                CURLOPT_RETURNTRANSFER => true,  
                                CURLOPT_POSTFIELDS => $resq_data  
                            );  
                
                            $ch = curl_init();  
                            curl_setopt_array($ch, $curlConfig);  
                            $response = curl_exec($ch);  
                            curl_close($ch);  
                
                            // Decode JSON data of API response in array  
                            $responseData = json_decode($response);  
            
                            if($responseData->success){ 
                                    $email['name']  = $record['first_name'];
                                    $email['email'] = idEncrypt($record['email']);
                                    $email['code']  = idEncrypt($random_code);

                                    Shortcode::NotificationEmail($CONFIGURATION['NOTIFICATION_EMAIL_CHANGE_PASSWORD'], $CONFIGURATION['SYSTEM_NAME'].' - Reset Password', $record['email'], '', $CONFIGURATION['EMAIL_BCC_CONVERSION'], '', serialize($email), 'resetPassword', $user_id);
                                    $data['site_key'] = $CONFIGURATION['GOOGLE_RECAPTCHA_SITE_KEY'];
                                    $data['message']  = 'We\'ve send a password reset link to your email.';
                                
                            }else{  
                                $statusMsg = 'The reCAPTCHA verification failed, please try again.';  
                                promptMessage('message', $statusMsg , 'danger');
                            }  
                        }
                    }else{
                        $data['message'] = 'Problem sending password reset link.';
                    }
                    
                    $data['site_key'] = $CONFIGURATION['GOOGLE_RECAPTCHA_SITE_KEY'];
                    promptMessage('message', $data['message'], 'warning');
                }                
            } 

            views('account.forgot-password', $data);
        }

        public function resetPassword(){
            checkLoggedIn('false');
            $data = array();
            $CONFIGURATION = Configuration::general();
            $data['site_key']   = $CONFIGURATION['GOOGLE_RECAPTCHA_SITE_KEY'];

            $email             = idDecrypt(getVar('email'));
            $code              = idDecrypt(getVar('code'));

            //validate verification code
            $record            = recastArray(Account::getRecordByEmail( $email ));
            $verification_code = displayVarWithDefault( arrayKeyExist($record,'verification_code'),'' );

            //splitcode
            $splitcode         = explode('|',$verification_code);
            $dbcode            = displayVarWithDefault( arrayKeyExist($splitcode,'0'),'' );
            $dbexpiry          = displayVarWithDefault( arrayKeyExist($splitcode,'1'),'' );
            $time              = time();

            //check if link has same code
            if ( $code != $dbcode ) {
                alertAndRedirect('Link already expired.', '/login');
                exit;

            }elseif (time() - $dbexpiry > 15 * 60) { 
                //check if the time is 15mins over
                alertAndRedirect('Link already expired.', '/login');
                exit;
            }
            
            if(isset($_POST['submit_form'])){
                
                $field['password']          = postVar('password','');
                $field['confirm_password']  = postVar('confirm_password','');

                $data = checkRequiredPost(array('password','confirm_password'));

                if ( !isset($data['error']) && $field['password'] != $field['confirm_password'] ) {
                    $data['site_key']                  = $CONFIGURATION['GOOGLE_RECAPTCHA_SITE_KEY'];
                    $data['error']['confirm_password'] = requiredPrompt('Password mismatch.');
                }

                if(!array_key_exists('error', $data)){   

                    $update_password['id']       = displayVarWithDefault( arrayKeyExist($record,'id'),'' );
                    $update_password['password'] = passwordEncode($field['password']);
                    $result                      = Account::changePasswordByAccountId( $update_password );

                    if ( $result['status'] == 'success' ) {

                        if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){  

                            $api_url = 'https://www.google.com/recaptcha/api/siteverify';  
                            $resq_data = array(  
                                'secret'   => $CONFIGURATION['GOOGLE_RECAPTCHA_SECRET_KEY'],  
                                'response' => $_POST['g-recaptcha-response'],  
                                'remoteip' => $_SERVER['REMOTE_ADDR']  
                            );  
                
                            $curlConfig = array(  
                                CURLOPT_URL => $api_url,  
                                CURLOPT_POST => true,  
                                CURLOPT_RETURNTRANSFER => true,  
                                CURLOPT_POSTFIELDS => $resq_data  
                            );  
                
                            $ch = curl_init();  
                            curl_setopt_array($ch, $curlConfig);  
                            $response = curl_exec($ch);  
                            curl_close($ch);  
                
                            // Decode JSON data of API response in array  
                            $responseData = json_decode($response);  
            
                            if($responseData->success){ 
                                   
                                //clear verification code
                                $verificationCode['id']                 = displayVarWithDefault( arrayKeyExist($record,'id'),'' );
                                $verificationCode['verification_code']  = '';
                                Account::editRecord($verificationCode);

                                alertAndRedirect($result['message'], '/login');
                                
                            }else{  
                                $statusMsg = 'The reCAPTCHA verification failed, please try again.';  
                                promptMessage('message', $statusMsg , 'danger');
                            }  
                        }

                    }else{
                        $data['site_key']   = $CONFIGURATION['GOOGLE_RECAPTCHA_SITE_KEY'];
                        alertAndRedirect($result['message'], '/account/resetPassword/'.getVar('email').'/'.getVar('code').'/');
                    }
                }

            } 

            views('account.reset-password', $data);
        }

        public function profile(){
            checkLoggedIn('true');
            $data = array();

            $CONFIGURATION = Configuration::general();
            $id            = idDecrypt(getVar('id'));

            if(isset($_POST['submit'])){
                $field['password']          = passwordEncode(postVar('password', ''));
                $field['new_password']      = passwordEncode(postVar('new_password', ''));
                $field['confirm_password']  = passwordEncode(postVar('confirm_password', ''));
                $personal['alias']          = postVar('alias', '');

                if(isset($_POST['password']) && !empty($_POST['password']) && isset($_POST['new_password']) && !empty($_POST['new_password'])){
                    $record = Account::getRecordByIdAndPassword(ACCOUNT_ID, $field['password']);
                    if(is_array($record) && !empty($record)){
                        if(empty($field['confirm_password'])){
                            $data['error']['confirm_password'] = requiredPrompt('This field is required.');
                        }else{
                            if($field['new_password'] != $field['confirm_password']){
                                $data['error']['confirm_password'] = requiredPrompt('Password mismatch.');
                            }else{
                                $field['password'] = $field['new_password'];
                                unset($field['confirm_password']);
                                unset($field['new_password']);
                            }
                        }
                    }else{
                        $data['error']['password'] = requiredPrompt('Account verification failed');
                    }
                }else{
                    unset($field['password']);
                    unset($field['confirm_password']);
                    unset($field['new_password']);
                }
                if(isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])){
                    $file_name          = $_FILES['file']['name'];
                    $file_size          = $_FILES['file']['size'];
                    $file_tmp           = $_FILES['file']['tmp_name'];
                    $file_type          = $_FILES['file']['type'];
                    $file_ext           = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                    $file_new_name      = ACCOUNT_ID.'.'.$file_ext;
                    $extensions         = $CONFIGURATION['ALLOWED_PHOTO'];
                    if(!in_array($file_ext, $extensions)){
                        $data['error']['file'] = requiredPrompt('File format is not allowed');
                    }else{
                        if(move_uploaded_file($file_tmp, uploadFile('account', $file_new_name))){                            
                            $personal['photo'] = $file_new_name;

                            thumbnailGenerate(fileUrl($file_new_name, '/file/account/'), uploadFile('account', thumbnailName($file_new_name)), "200");
                        }else{
                            promptMessage('message', 'Encounter technical error. Pls try again', 'danger');
                        }
                    }
                }
                
                if(isset($_POST['file_delete'])){
                    $personal['photo'] = '';
                    deleteFile('account', postVar('file_hidden'));
                    deleteFile('account', thumbnailName(postVar('file_hidden')));
                }

                if(!array_key_exists('error', $data)){
                    $field['id']              = $id;
                    $personal['account_id']   = $id;
                    $employment['account_id'] = $id;
                    $result                   = Account::editRecord($field);
                    $personal                 = Account::manageDynamic('account_personal', $personal);
                    $employment               = Account::manageDynamic('account_employment', $employment);

                    alertAndRedirect($result['message'], '/account/profile/'.idEncrypt($id).'/');
                }
            }
            
            $data['account'] = recastArray(Account::getRecordById($id));

            views('account.profile', $data);
        }

        public function importView(){
            $data = array();
            $account_id = idDecrypt(getVar('account_id'));
            includeModel(['Master', 'Account']);
            $CONFIGURATION = Configuration::general();
            $data['account']                    = recastArray(Account::getRecordById($account_id));
            $data['account_personal']           = recastArray(Account::getDynamicByAccountId('account_personal',$account_id));
            $data['account_bank']               = recastArray(Account::getDynamicByAccountId('account_bank',$account_id));
            $data['account_emergency_contact']  = recastArray(Account::getDynamicByAccountId('account_emergency_contact',$account_id));
            $data['account_employment']         = recastArray(Account::getDynamicByAccountId('account_employment',$account_id));
            $data['account_blacklist']          = recastArray(Account::getDynamicByAccountId('account_blacklist',$account_id));
            $data['account_equipment']          = recastArray(Account::getDynamicByAccountId('account_equipment',$account_id));
            $data['account_promotion']          = Account::getAccountPromotionByAccountId($account_id);

            if(is_array($data['account'])){

                $data['account']['account_role_name'] = '';
                if(!empty($data['account']['account_role_id'])){
                    $role_list = explode('-', $data['account']['account_role_id']);
                    $role_ctr = 1;
                    foreach($role_list as $role_item){
                        if(count($role_list) > $role_ctr){
                            $role_delimeter = ', ';
                        }else{
                            $role_delimeter = '';
                        }
                        
                        $account_role = Master::getDynamicById('master_account_role', $role_item);
                        if(!empty($account_role)){
                            $data['account']['account_role_name'] .= recastArray($account_role)['name'].$role_delimeter;
                        }

                        $role_ctr++;
                    }
                }
                
                if(is_array($data['account_employment'])){
                    $data['account_employment']['account_department_name']  = recastArray(Master::getDynamicById('master_account_department',$data['account_employment']['account_department_id']))['name'];
                    $data['account_employment']['account_designation_name'] = recastArray(Master::getDynamicById('master_account_designation',$data['account_employment']['account_designation_id']))['name'];
                    $data['account_employment']['account_team_name']        = (!empty($data['account_employment']['account_team_id']) ? recastArray(Master::getDynamicById('master_account_team',$data['account_employment']['account_team_id']))['name'] : '');
                    $data['account_employment']['account_level_name']       = (!empty($data['account_employment']['account_level_id']) ? recastArray(Master::getDynamicById('master_account_level',$data['account_employment']['account_level_id']))['name'] : '');
                    $data['account_employment']['account_region_name']      = recastArray(Master::getDynamicById('master_country',$data['account_employment']['account_region_id']))['name'] ?? '';
                    $data['account_employment']['report_name']              = (!empty($data['account_employment']['report_to']) ? recastArray(Account::getPersonalByAccountId($data['account_employment']['report_to']))['full_name'] : '');
                    $data['account_employment']['employment_type_name']     = (!empty($data['account_employment']['employment_type_id']) ? recastArray(Master::getDynamicById('master_employment_type',$data['account_employment']['employment_type_id']))['name'] : '');
                }

                if(is_array($data['account_personal'])){
                    $data['account_personal']['nationality_name'] = recastArray(Master::getDynamicById('master_country',$data['account_personal']['nationality_id']))['name'];
                }
            }
            
            views('account.import-view', $data); 
        }

        public function importOnlineMember(){
            checkLoggedIn('true');
            $data = Array();

            $CONFIGURATION = Configuration::general();

            $record = Account::getAllRecordByStatus($CONFIGURATION['ACCOUNT_STATUS_ACTIVE']);
            if(is_array($record)){
                foreach($record as $key => $value){
                    if((time() - strtotime($value['last_time_in'])) > 180) {
                        $data['offline'][$key] = $value;
                    }else{
                        $data['online'][$key] = $value;
                    }
                }
            }
            
            views('account.import-online-member', $data);
        }
        
        public function accountPromotionJson(){
            
            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Account::getAccountPromotionById($_POST['id']);
                    if(is_array($record)){    
                      $result = recastArray($record);
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){
                    $result = Account::deleteAccountPromotion($_POST['id']);
                    
                }else{

                    $id                                = postVar('id',0);
                    $field['account_id']               = postVar('account_id',0);
                    $field['account_department_id']    = postVar('account_promotion_department_id',0);
                    $field['account_designation_id']   = postVar('account_promotion_designation_id',0);
                    $field['account_team_id']          = postVar('account_promotion_team_id',0);
                    $field['account_level_id']         = postVar('account_promotion_level_id',0);
                    $field['promotion_status']         = postVar('is_promotion_active') == "true" ? 'Active' : '';

                    $data = checkRequiredPost(array('account_id','account_promotion_department_id'));
                    if(!array_key_exists('error', $data)){  
                        if(!empty($id)){
                            $field['id']                = $id;
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result                     = Account::editAccountPromotion($field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();
                            $result                     = Account::addAccountPromotion($field);
                        }

                        if($result['status'] == 'success'){;
                            if($field['promotion_status'] == 'Active'){
                                
                                $promotions = Account::getAccountPromotionByAccountId($field['account_id']);
                                if(is_array($promotions)){
                                    foreach($promotions as $promotion){
                                        if($promotion['id'] != $result['id']){
                                            $promotionDetails['id']= $promotion['id'];
                                                            $promotionDetails['promotion_status']= '';
                                                            Account::editAccountPromotion($promotionDetails);
                                                            $promotionDetails = [];
                                        }
                                    }
                                }
                                $employment['account_id']             = $field['account_id'];
                                $employment['account_department_id']  = $field['account_department_id'];
                                $employment['account_designation_id'] = $field['account_designation_id'];
                                $employment['account_team_id']        = $field['account_team_id'];
                                $employment['account_level_id']       = $field['account_level_id'];

                                $employment = Account::manageDynamic('account_employment', $employment);

                            }
                        }

                        
                    }else{
                        $result['status']  = 'error';
                        $result['message'] = 'Error On creating promotion record Please Check all fields';
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            echo json_encode($result);
        }

        public function attachmentJson(){
            $result         = Array();
            $name           = safe_b64encode(postVar('name',0));
            $request        = postVar('request');
            $path           = 'temp/'.$request.'-'.date('Ymd').ACCOUNT_ID.$name;
            $result         = Shortcode::addAttachment($path);
            echo json_encode($result);
            exit;
        }

        public function removeAttachmentJson(){
            $result         = Array();
            $name           = safe_b64encode(postvar('name',0));
            $file           = postvar('file','');
            $request        = postVar('request');
            $path           = 'upload/temp/'.$request.'-'.date('Ymd').ACCOUNT_ID.$name;
            if(file_exists($path.'/'.htmlDecode($file))){
                removeFile($path.'/'.htmlDecode($file));
                $result['status'] = 'success';
            }
            echo json_encode($result);
            exit;
        }

        public function perDepartmentManagerJson(){
            includeModel(['Account']);
            $CONFIGURATION = Configuration::general();
            // ACCOUNT_LEVEL_LEADER
            if(isset($_POST['id']) && !empty($_POST['id'])){
                $record = Account::getRecordByDepartmentIdsAndLevelIds($_POST['id'],$CONFIGURATION['ACCOUNT_LEVEL_LEADER'],$CONFIGURATION['ACCOUNT_STATUS_ACTIVE']);
                if(is_array($record)){   
                    $result = $record; 
                }else{
                    $default = Account::getRecordByLevelIdsAndStatusId($CONFIGURATION['ACCOUNT_LEVEL_LEADER'],$CONFIGURATION['ACCOUNT_STATUS_ACTIVE']);
                    $result  = $default;
                }
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }

            echo json_encode($result);
        }
    }
?>