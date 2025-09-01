<?php
	class Shortcode{

	    public function __construct() {
	        //checkLoggedIn('true');
	    }

        public static function concatId($value=''){

            if(isset($value) && !empty($value)){
                $save = '';
                $ctr  = 1;
            
                if(is_array($value)){
                    foreach($value as $id){
                        if($ctr == 1){
                            $save  = $id;
                        }else{
                            $save .= "-".$id;
                        }
                        $ctr++;
                    }
                    $return = $save;
                }else{
                    $return = $value;
                }
            }else{
                $return = $value;
            }

            return $return;
        }

        public static function concatName($value='', $delimiter=', '){

            if(isset($value) && !empty($value)){
                $save = '';
                $ctr  = 1;
            
                if(is_array($value)){
                    foreach($value as $id){
                        if($ctr == 1){
                            $save  = $id;
                        }else{
                            $save .= $delimiter.$id;
                        }
                        $ctr++;
                    }
                    $return = $save;
                }else{
                    $return = $value;
                }
            }else{
                $return = $value;
            }

            return $return;
        }

        public static function concatView($value='', $merge='<br>', $delimiter=','){

            if(isset($value) && !empty($value)){
                $save = '';
                $ctr  = 1;

                $explode = explode($delimiter, $value);
                foreach ($explode as $value){
                    if($ctr == 1){
                        $save  = $value;
                    }else{
                        $save .= $merge.$value;
                    }
                    $ctr++;
                }

                $return = $save;
            }else{
                $return = $value;
            }

            return $return;
        }

        public static function concatQuery($array, $id){
            $return = array();

            if(isset($array) && !empty($array)){
                foreach ($array as $key => $value) {
                    $return[] = $value[$id];
                } 
            }

            return $return;
        }

        public static function countryList($country_ids){
            $return = NULL;
            if(!empty($country_ids)){
                includeModel(['Master']);

                $country = explode('-', $country_ids);
                $counter = 1; 
                foreach($country as $id){
                    if(count($country) > $counter){
                        $delimeter = ', ';
                    }else{
                        $delimeter = '';
                    }
                    $return .= recastArray(Master::getDynamicById('master_country', $id))['name'].$delimeter;

                    $counter++;
                }

            }
            return $return;
        }

        public static function notificationEmail($name, $subject, $recipient_to, $recipient_cc='', $recipient_bcc='', $attachment='', $body='', $template='', $created_by = ACCOUNT_ID, $reply_to = ''){
            includeModel(['Notification']);

            $CONFIGURATION              = Configuration::general();
            $black_listed_emails        = $CONFIGURATION['BLACKLISTED_EMAIL'];    
            
            $field['name']              = htmlEncode($name);
            $field['subject']           = htmlEncode($subject);
            
            // remove black listed emails
            if(is_array($recipient_to)){
                $recipient_to = array_diff($recipient_to,$black_listed_emails);
            }else{
                $recipient_to = !in_array($recipient_to,$black_listed_emails) ? $recipient_to : '';
            }
            // remove black listed emails
            if(is_array($recipient_cc)){
                $recipient_cc = array_diff($recipient_cc,$black_listed_emails);
            }else{
                $recipient_cc = !in_array($recipient_cc,$black_listed_emails) ? $recipient_cc : '';
            }
            // remove black listed emails
            if(is_array($recipient_bcc)){
                $recipient_bcc = array_diff($recipient_bcc,$black_listed_emails);
            }else{
                $recipient_bcc = !in_array($recipient_bcc,$black_listed_emails) ? $recipient_bcc : '';
            }

            // remove black listed emails
            if(is_array($reply_to)){
                $reply_to = array_diff($reply_to,$black_listed_emails);
            }else{
                $reply_to = !in_array($reply_to,$black_listed_emails) ? $reply_to : '';
            }
            
            $field['recipient_to']  = is_array($recipient_to) ? implode(',', $recipient_to) : $recipient_to;
            $field['recipient_cc']  = is_array($recipient_cc) ? implode(',', $recipient_cc) : $recipient_cc;
            $field['recipient_bcc'] = is_array($recipient_bcc)? implode(',', $recipient_bcc) : $recipient_bcc;
            $field['reply_to']      = is_array($reply_to)     ? implode(',', $reply_to) : $reply_to;

            $field['attachment']    = safe_b64encode($attachment);
            $field['body']          = safe_b64encode($body);
            $field['template']      = $template;
            $field['status_id']     = $CONFIGURATION['NOTIFICATION_STATUS_UNPROCESSED']; 
            $field['created_by']    = (isset($created_by) && !empty($created_by) ? $created_by : $CONFIGURATION['ENGINE_CRON_JOB']); 
            $field['created_when']  = dateTimeStamp();

            return Notification::addEmail($field);
        }

        public static function onlineMember($account_id){
            includeModel(['Account']);

            $field['id']           = $account_id;
            $field['last_time_in'] = dateTimeStamp();
            Account::editRecord($field);
        }
        
        public static function accountList($account_ids){
            $return = NULL;

            if(!empty($account_ids)){
                includeModel(['Account']);

                $account = explode('-', $account_ids);
                $counter = 1; 
                foreach($account as $id){
                    if(count($account) > $counter){
                        $delimeter = ', ';
                    }else{
                        $delimeter = '';
                    }
                    $return .= recastArray(Account::getRecordById($id))['full_name'].$delimeter;

                    $counter++;
                }

            }
            
            return $return;
        }

        public static function allUsersIdsUnderDepartmentIdAndTeamdIdAndLevelIds($department_id,$teadm_id,$level_ids){
            $return = '';
            $array  = [];
            includeModel(['Account']);

            if(!empty($department_id) && !empty($teadm_id) && !empty($level_ids)){
                $record = Account::getRecordByDepartmentIdAndTeamIdsAndLevelIds($department_id,$teadm_id,$level_ids,1);

                if(is_array($record)){
                    foreach($record as $user){
                        array_push($array,$user['id']);
                    }
                    $return = $array;
                }
            }
            return $return;
        }

        public static function addAttachment($path, $allowed_file_size=''){
            $result         = Array();
            $CONFIGURATION  = Configuration::general();
            $success        = 0;
            $failed         = 0;
            if (isset($_FILES['file']) && !empty($_FILES['file'])) {
                $no_files       = count($_FILES["file"]['name']);
                if(file_exists('upload/'.$path)){
                    removeFile('upload/'.$path);
                }

                $allowed_file_size = (!empty($allowed_file_size) ? $allowed_file_size : $CONFIGURATION['ALLOWED_FILE_SIZE']);
                $total_file_size   = isset($_FILES['file']['size']) ? array_sum($_FILES['file']['size']) : 0;
                            
                if($total_file_size > $allowed_file_size){
                    $failed++;
                    $result['message'] = 'Total file(s) size allowed is 5MB';
                }else{
                    for ($i = 0; $i < $no_files; $i++) {
                        if (!empty($_FILES['file']['name'][ $i ]) && $_FILES['file']['error'][ $i ] == 0 ) {
                            $file_name  = $_FILES['file']['name'][$i];
                            $file_size  = $_FILES['file']['size'][ $i ];
                            $file_tmp   = $_FILES['file']['tmp_name'][ $i ];
                            $file_type  = $_FILES['file']['type'][ $i ];
                            $file_ext   = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                            $extensions = $CONFIGURATION['ALLOWED_FILE'];
                            // $file_new_name = $field['contract_no'].'_'.dateTimeAsId().'-'.($i+1).'.'.$file_ext;
                            /** Validate file for required format */
                            if(!in_array($file_ext, $extensions)){
                                $failed++;
                                $result['message'] = 'File format is not allowed';
                            }else{
                                //if folder is not existing, create one
                                if (!file_exists('upload/'.$path)){
                                    mkdir('upload/'.$path, 0777, true);
                                }
                                if(move_uploaded_file($file_tmp, uploadFile($path,$file_name))){
                                    $success++;
                                    $result['filename'][] = $file_name;
                                    $result['link'][]     = '/file/'.$path.'/'.$file_name;
                                }else{
                                    $failed++;
                                    $result['message'] = 'Encounter technical error. Pls try again';
                                }
                            }
                        }else{
                            $failed++;
                            $result['message'] = 'You uploaded an empty file.';
                        }
                    }
                }
                if ( $failed == 0 && $success > 0 ) {
                    $result['status'] = 'success';
                }else{
                    $result['status'] = 'failed';
                }
            }else{
                if(file_exists('upload/'.$path)){
                    $files = scandir('upload/'.$path);
                    foreach ( $files as $filename ){
                        if ($filename != "." && $filename != ".."){
                            $result['filename'][] = $filename;
                            $result['link'][]     = '/file/'.$path.'/'.$filename;
                        }
                    }
                }
            }
            
            echo json_encode($result);
            exit;
        }
        
        public static function removeAttachment($path){
            $return = Array();
            if(file_exists($path)){
                removeFile($path);
                $result['status'] = 'success';
            }
            return $return;
        }

        public static function firstWorkingDayOfTheMonth($branch_id){

            includeModel(['Master']);
            $currentDate                 = date('Y-m-d');
            $lastMonthStartDate          = date("Y-m-01", strtotime ( '-1 month' , strtotime ( $currentDate ) )) ;
            $lastMonthEndDate            = date("Y-m-t", strtotime($lastMonthStartDate));
            $currentDateYear             = date('Y');
            $lastMonthYear               = date('Y', strtotime($lastMonthStartDate));
            // if last month year is not equal to current year. then do not check the first working day 
            // if($currentDateYear == $lastMonthYear){
                do{ 
                        $lastMonthEndDate = date('Y-m-d', strtotime($lastMonthEndDate. ' + 1 days'));
                }while(self::checkFirstValidWorkingDay($lastMonthEndDate,$branch_id));

            // }
            $firstWorkingDay = $lastMonthEndDate;
            return $firstWorkingDay;
        }

        public static function checkFirstValidWorkingDay($date,$branch_id){
            
            includeModel(['Master']);
            $year           = date('Y',strtotime($date));
            $holidays       = Master::getHolidayByYearAndCompanyBranchIds($year,$branch_id) ;
            $holiday_dates  = '';
           
            if(is_array($holidays)){
                $holiday_dates = array_column($holidays, 'date_set');
            }

            //A numeric representation of the day (0 for Sunday, 6 for Saturday)
            $numericalDay = date('w', strtotime($date));
            // check date if weekend 
            if($numericalDay == 0 || $numericalDay == 6){
                return true;
            }
            // check date if is in holiday 
            if(in_array($date,$holiday_dates)){
                return true;
            }
            
            return false;
        }

        public static function currentYearQuarterList(){
            $year       = date('Y');
            $return     = [];

            $return[1]    = [
                'quarter' => 1,
                'start_date' => $year.'-01-01',
                'end_date' => $year.'-03-31',
            ];

            $return[2]    = [
                'quarter' => 2,
                'start_date' => $year.'-04-01',
                'end_date' => $year.'-06-30',
            ];

            $return[3]    = [
                'quarter' => 3,
                'start_date' => $year.'-07-01',
                'end_date' => $year.'-09-30',
            ];

            $return[4]    = [
                'quarter' => 4,
                'start_date' => $year.'-10-01',
                'end_date' => $year.'-12-31',
            ];

            return $return;
        }

        public static function quarterDetailsByDate($date){
            $return = [];
            if(!empty($date)){
                $year = date('Y',strtotime($date));
                if(($date >= $year.'-01-01' ) && ($date <= $year.'-03-31')){
                    $firstMonthEndDate    = date('Y-m-t',strtotime($year.'-01-01'));
                    $secondMonthStartDate = date('Y-m-d',strtotime('+1 days',strtotime($firstMonthEndDate)));
                    $secondMonthEndDate   = date('Y-m-t',strtotime($secondMonthStartDate));
                    $lastMonthEndDate     = date('Y-m-t',strtotime($year.'-03-31'));
                    $return = [
                        'start_date' => $year.'-01-01',
                        'end_date' => $year.'-03-31',
                        'quarter'   => '1',
                        'last_dates_of_quarter'   => [
                                                        $firstMonthEndDate,
                                                        $secondMonthEndDate,
                                                        $lastMonthEndDate,
                                                    ]
                    ];
                }
                if(($date >= $year.'-04-01' ) && ($date <= $year.'-06-30')){
                    $firstMonthEndDate    = date('Y-m-t',strtotime($year.'-04-01'));
                    $secondMonthStartDate = date('Y-m-d',strtotime('+1 days',strtotime($firstMonthEndDate)));
                    $secondMonthEndDate   = date('Y-m-t',strtotime($secondMonthStartDate));
                    $lastMonthEndDate     = date('Y-m-t',strtotime($year.'-06-30'));
                    $return = [
                        'start_date' => $year.'-04-01',
                        'end_date' => $year.'-06-30',
                        'quarter'   => '2',
                        'last_dates_of_quarter'   => [
                                                    $firstMonthEndDate,
                                                    $secondMonthEndDate,
                                                    $lastMonthEndDate,
                                                ]
                    ];
                }
                if(($date >= $year.'-07-01' ) && ($date <= $year.'-09-30')){
                    $firstMonthEndDate    = date('Y-m-t',strtotime($year.'-07-01'));
                    $secondMonthStartDate = date('Y-m-d',strtotime('+1 days',strtotime($firstMonthEndDate)));
                    $secondMonthEndDate   = date('Y-m-t',strtotime($secondMonthStartDate));
                    $lastMonthEndDate     = date('Y-m-t',strtotime($year.'-09-30'));
                    $return  = [
                        'start_date' => $year.'-07-01',
                        'end_date' => $year.'-09-30',
                        'quarter'   => '3',
                        'last_dates_of_quarter'   => [
                                                    $firstMonthEndDate,
                                                    $secondMonthEndDate,
                                                    $lastMonthEndDate,
                                                ]
                    ];
                }
                if(($date >= $year.'-10-01' ) && ($date <= $year.'-12-31')){
                    $firstMonthEndDate    = date('Y-m-t',strtotime($year.'-10-01'));
                    $secondMonthStartDate = date('Y-m-d',strtotime('+1 days',strtotime($firstMonthEndDate)));
                    $secondMonthEndDate   = date('Y-m-t',strtotime($secondMonthStartDate));
                    $lastMonthEndDate     = date('Y-m-t',strtotime($year.'-12-31'));
                    $return = [
                        'start_date' => $year.'-10-01',
                        'end_date' => $year.'-12-31',
                        'quarter'   => '4',
                        'last_dates_of_quarter'   => [
                                                    $firstMonthEndDate,
                                                    $secondMonthEndDate,
                                                    $lastMonthEndDate,
                                                ]
                    ];
                }

            }
            return $return;
        }

        public static function currentDatesOfTheWeek(){
            $return = '';

            $currentDate        = date('Y-m-d');
            $currentDayNoInWeek = date('w',strtotime($currentDate));
            $datesToGet         = [];
            $firstDayOfWeek     = '';

            // if current date is sunday
            if($currentDayNoInWeek == 0){
                $firstDayOfWeek = date('Y-m-d' , strtotime($currentDate .'- 6 days'));
            }
            else if($currentDayNoInWeek == 1){
                // if current date is monday
                $firstDayOfWeek = date('Y-m-d' , strtotime($currentDate));
            }
            else{
                // if current date is in between monday and sunday
                $currentDayNoInWeek = $currentDayNoInWeek - 1;
                $firstDayOfWeek = date('Y-m-d' , strtotime($currentDate. '- '.$currentDayNoInWeek.' days' ));
            }
            $datesToGet[]    = $firstDayOfWeek;
            for ($counter=1; $counter <= 6 ; $counter++) { 
                $datesToGet[] = date('Y-m-d' , strtotime($firstDayOfWeek. '+ '.$counter.' days' ) );
            }
            $return = $datesToGet;
            return $return;
        }

        public static function accessGranted($account_id, $controller, $view){
            $result = array();

            includeModel(['Account', 'Master']);

            $CONFIGURATION = Configuration::general();

            //if not administrator, check access
            if(ACCOUNT_TYPE_ID != $CONFIGURATION['ACCOUNT_TYPE_ADMINISTRATOR']){   

                $role = Master::getAccountRole($controller, $view);

                if(!empty($role)){
                    $result['role']       = recastArray($role);
                    $result['controller'] = $controller;
                    $result['view']       = $view;
                    $result['account']    = recastArray(Account::getEmploymentByAccountIdAndRoleId($account_id, $result['role']['id']));

                    emptyRedirectPage($result['account']);
                }
            }
            //return $result;
        }

        public static function checkIfValidEmail($email){
            includeModel(['Account']);
            $CONFIGURATION = Configuration::general();
            // $user_email    = Account::getActiveStatusByEmail($email);
            $account          = recastArray(Account::getRowByEmail($email));

            // if existing on our records 
            if(!empty($account)){
                if($account['account_status_id'] == $CONFIGURATION['ACCOUNT_STATUS_ACTIVE'] && !in_array($email, $CONFIGURATION['BLACKLISTED_EMAIL'])){
                    return true;
                }
            }else{
                // if not existing check only on blackmail list
                if(!in_array($email, $CONFIGURATION['BLACKLISTED_EMAIL'])){
                    return true;
                }
            }
            // // check if email is not empty, active, and not blacklisted
            // if (!empty($user_email) && !in_array($email, $CONFIGURATION['BLACKLISTED_EMAIL'])) {
            //     return true; // valid recipient
            // }
            
            return false; // invalid recipient
        } 
        
        public static function soaCollectionReminderLetterGeneration($id, $folder, $action="", $body=""){
            includeDefault('pdf');

            $directory = realpath(__DIR__ . '/../../../'); // goes up 3 levels to pinnacle root
            
            $file_path = $directory . DIRECTORY_SEPARATOR . 'pinnacle' . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . 'soa' . DIRECTORY_SEPARATOR . $folder; //pinnacle - update with correct project folder name

            $document  = 'SOA-'.$id;
            $watermark = True;
            //$folder    = './upload/soa';

            if (ob_get_contents()) ob_end_clean();
            if($action == "attachment"){
                $filename = $document.'.pdf';
                pdf::generate($filename, $body, 'invoice', 'attachment', $file_path, $watermark);
                $file = array(
                            'location'  => $file_path, 
                            'file_name' => $filename
                            ); 
                return $file;
            }else{
                pdf::generate($document, $body, 'invoice', 'download', $folder, $watermark);
                exit;
            }
        }

        public static function soaStatementOfAccountGeneration($id){
            
        }

        public static function soaDownload($intermediary_id){
            $result = array();

            $base_path 			    = './upload/soa';
            $source_file_folder 	= $base_path.'/default';
            $folder_name 		    = 'SOA-'.$intermediary_id.'-'.date('YmdHis');
            $exclude_file 		    = ['index.php'];

            //FOLDER
            $new_folder = createFolder($base_path, $folder_name);
            if(!$new_folder){
                $result['error'] = "Failed to create folder";
            }

            //COPY FILES
            if(!copyFolderContents($source_file_folder, $new_folder, $exclude_file)){
                $result['error'] = "Failed to copy files from source";
            }

            //GENERATE PDF
            self::soaCollectionReminderLetterGeneration($intermediary_id, $folder_name, 'attachment');

            //ZIP
            $zip_file_path = $base_path . '/' . $folder_name . '.zip';
            if(!zipFolder($new_folder, $zip_file_path)){
                $result['error'] = "Zipping failed";
            }

            $result['base_path']          = $base_path;
            $result['folder_name']        = $folder_name;
            $result['source_file_folder'] = $source_file_folder;
            $result['exclude_file']       = $exclude_file;
        
            return $result;
        }
	}
?>