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
        
        public static function soaCollectionReminderLetterGeneration($id, $folder = '', $action="", $body = ''){
            includeDefault('pdf');

            $directory = realpath(__DIR__ . '/../../../'); // goes up 3 levels to pinnacle root
            
            $file_path = $directory . DIRECTORY_SEPARATOR . 'pinnacle' . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . 'soa' . DIRECTORY_SEPARATOR . $folder; //pinnacle - update with correct project folder name

            $document  = $file_name;
            $watermark = True;
            //$folder    = './upload/soa';

            if (ob_get_contents()) ob_end_clean();
            if($action == "attachment"){
                $filename = $document.'.pdf';
                pdf::generate($filename, $body, 'invoice', 'attachment', $file_path, '');
                $file = array(
                            'location' => $folder, 
                            'file'     => $filename
                            ); 
                return $file;
            }
            else{
                pdf::generate($document, $body, 'invoice', $action, $folder, '');
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

        public static function addMonthNoOverflow(DateTime $date, int $months): DateTime {
            $new = clone $date;

            // Target month and year
            $month = (int)$new->format('n') + $months;
            $year = (int)$new->format('Y');

            // Normalize year and month
            $year += intdiv($month - 1, 12);
            $month = ($month - 1) % 12 + 1;

            // Last day of target month
            $lastDay = (int)cal_days_in_month(CAL_GREGORIAN, $month, $year);

            // If current day > last day, snap to end of month
            $day = min((int)$new->format('j'), $lastDay);

            return new DateTime(sprintf('%04d-%02d-%02d', $year, $month, $day));
        }

        public static function soaCollectionReminderLettertogetherwithSOA($master_list_id, $as_of_date){
            includeModel(['Master', 'Finance']);

            $master_list        = recastArray(Finance::getMasterlistById($master_list_id));
            $premium_receivable = Master::getDetailed('', '2025-08-31', '*', $master_list['source_name']);
            $tax_receivable_dst = Master::getDST('', '2025-08-31', '*', $master_list['source_name']);
            $tax_receivable_cwt = Master::getCWT('', '2025-08-31', '*', $master_list['source_name']);
            $cod                = Master::getCOD('', '2025-08-31', '*', $master_list['source_name']);

            $cod_policies     = [
                                    '0_30'      => array_sum(array_column($cod ?? [], '0_30_DAYS')),
                                    '31_60'     => array_sum(array_column($cod ?? [], '31_60_DAYS')),
                                    '61_90'     => array_sum(array_column($cod ?? [], '61_90_DAYS')),
                                    '91_180'    => array_reduce(
                                                        $cod ?? [],
                                                        fn($total, $row) => $total + $row['91_120_DAYS'] + $row['121_150_DAYS'] + $row['151_180_DAYS'],
                                                        0
                                                    ),
                                    '180_ABOVE' => array_reduce(
                                                        $cod ?? [],
                                                        fn($total, $row) => $total + $row['181_210_DAYS'] + $row['211_360_DAYS'],
                                                        0
                                                    ),
                                ];

            $current_accounts = [
                                    '0_30'   => array_sum(array_column($premium_receivable ?? [], '0_30_DAYS')),
                                    '31_60'  => array_sum(array_column($premium_receivable ?? [], '31_60_DAYS')),
                                    '61_90'  => array_sum(array_column($premium_receivable ?? [], '61_90_DAYS')),
                                ];
            $overdue_accounts = [
                                    '91_180'    => array_reduce(
                                                        $premium_receivable ?? [],
                                                        fn($total, $row) => $total + $row['91_120_DAYS'] + $row['121_150_DAYS'] + $row['151_180_DAYS'],
                                                        0
                                                    ),
                                    '180_ABOVE' => array_reduce(
                                                        $premium_receivable ?? [],
                                                        fn($total, $row) => $total + $row['181_210_DAYS'] + $row['211_360_DAYS'],
                                                        0
                                                    ),
                                ];

            $total_current = array_sum($current_accounts);
            $total_overdue = array_sum($overdue_accounts);
            $total_cod = array_sum($cod_policies);

            $tax_current = [
                    '0_30'   => [
                                    'dst' => array_sum(array_column($tax_receivable_dst ?? [], '0_30_DAYS')),
                                    'cwt' => array_sum(array_column($tax_receivable_cwt ?? [], '0_30_DAYS')),
                                ],
                    '31_60'  => [
                                    'dst' => array_sum(array_column($tax_receivable_dst ?? [], '31_60_DAYS')),
                                    'cwt' => array_sum(array_column($tax_receivable_cwt ?? [], '31_60_DAYS')),
                                ],
                    '61_90'  => [
                                    'dst' => array_sum(array_column($tax_receivable_dst ?? [], '61_90_DAYS')),
                                    'cwt' => array_sum(array_column($tax_receivable_cwt ?? [], '61_90_DAYS')),
                                ]
            ];

            $tax_overdue = [
                '91_180'    => [
                                    'dst' => array_reduce(
                                        $tax_receivable_dst ?? [],
                                        fn($total, $row) => $total + $row['91_120_DAYS'] + $row['121_150_DAYS'] + $row['151_180_DAYS'],
                                        0
                                    ),
                                    'cwt' => array_reduce(
                                        $tax_receivable_cwt ?? [],
                                        fn($total, $row) => $total + $row['91_120_DAYS'] + $row['121_150_DAYS'] + $row['151_180_DAYS'],
                                        0
                                    ),
                                ],
                '180_ABOVE' => [
                                    'dst' => array_reduce(
                                                $tax_receivable_dst ?? [],
                                                fn($total, $row) => $total + $row['181_210_DAYS'] + $row['211_360_DAYS'],
                                                0
                                            ),
                                    'cwt' => array_reduce(
                                                $tax_receivable_cwt ?? [],
                                                fn($total, $row) => $total + $row['181_210_DAYS'] + $row['211_360_DAYS'],
                                                0
                                            ),
                                ]
            ];

            $total_premium  = $total_current+$total_overdue;

            $total_tax_current = array();
            $tax_message = '';
            foreach($tax_current as $key=>$current){
                $tax_message .= '<tr>
                                <td>'.str_replace('_', ' - ', $key).' Days</td>';
                foreach($current as $tax_key=>$tax){
                    $tax_message .= '<td class="text-right">'.formatMoney($tax).'</td>';
                    $total_tax_current[$tax_key] = ($total_tax_current[$tax_key] ?? 0) + $tax;
                }
                $tax_message .= '</tr>';
            }
            $total_tax['dst'] = $tax_overdue['91_180']['dst'] + $tax_overdue['180_ABOVE']['dst'];
            $total_tax['cwt'] = $tax_overdue['91_180']['cwt'] + $tax_overdue['180_ABOVE']['cwt'];
            $grand_total = $total_current+$total_overdue+$total_tax['cwt']+$total_tax_current['cwt']+$total_tax['dst']+$total_tax_current['dst']+$total_cod;
            $grand_total = formatMoney($grand_total);

            $total_dst      = $total_tax['dst']+$total_tax_current['dst'];
            $total_cwt      = $total_tax['cwt']+$total_tax_current['cwt'];

            $message = '
                        <style>
                            table td{
                                border: 1px solid black;
                                text-align: center;
                            }

                            .header, .bold{
                                font-weight: bold;
                            }
                        </style>
                        <p>Dear Ma\'am/Sir,</p>
                        <p>Our records as of July 31, 2025 show that you have outstanding premiums of <b>PHP '.$grand_total.'</b>.</p>
                        
                        <p>For your ready reference, we have provided you with the details, as per attached Statement of Account (SOA) which is password-protected.
                        Your default password is the last 7 digits of your Intermediary Code.</p>
                        
                        <p>We wish to remind you of our agreed credit terms. In view thereof, we would appreciate receiving your payment on or before the specified <b>Due Dates below</b> to keep the policies in full force and effect and to avoid any legal complication in case of a claim. Please refer to the Aging Summary below based on effectivity of the policies.</p>
                        <div>
                            <table class="table" cellpadding="3">
                                <tr class="header">
                                    <td colspan="2">COD POLICIES (DUE IMMEDIATELY)</td>
                                </tr>
                                <tr class="bold">
                                    <td>AGING DAYS</td>
                                    <td class="text-right">NET PREMIUM DUE</td>
                                </tr>';

                                foreach($cod_policies as $key=>$cod_policy){
                                    $message .= '<tr>
                                                    <td>'.str_replace('_', ' - ', $key).'</td>
                                                    <td>'.formatMoney($cod_policy).'</td>
                                                </tr>';
                                }
                                
                    $message .= '<tr class="bold">
                                    <td>TOTAL COD ACCOUNTS</td>
                                    <td class="text-right">'.$total_cod.'</td>
                                </tr>
                            </table>
                        </div>
                        <div>
                            <table class="table" cellpadding="3">
                                <tr class="header">
                                    <td colspan="3">PREMIUM RECEIVABLE</td>
                                </tr>
                                <tr class="bold">
                                    <td>AGING DAYS</td>
                                    <td class="text-right">NET PREMIUM DUE</td>
                                    <td>PAYMENT DUE DATE</td>
                                </tr>';
                                $counter = 3;
                                $as_of_date = new DateTime($as_of_date);

                                foreach ($current_accounts as $key => $current_account) {
                                    $month = self::addMonthNoOverflow($as_of_date, $counter);

                                    $lastDay = (clone $month)->modify('last day of this month');
                                    $duedate = ($current_account > 0) ? $lastDay->format("F d, Y") : "";

                                    $message .= '<tr>
                                                    <td>' . str_replace('_', ' - ', $key) . ' Days</td>
                                                    <td class="text-right">' . formatMoney($current_account) . '</td>
                                                    <td>' . $duedate . '</td>
                                                </tr>';

                                    $counter--;
                                }


                    $message.=  '<tr class="bold">
                                    <td>Total Current Accounts</td>
                                    <td class="text-right">'.formatMoney($total_current).'</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>91 - 180 Days</td>
                                    <td class="text-right">'.formatMoney($overdue_accounts['91_180']).'</td>
                                    <td>'.(($overdue_accounts['91_180'] > 0) ? "Due Immediately" : "").'</td>
                                </tr>
                                <tr>
                                    <td>Above 180 Days</td>
                                    <td class="text-right">'.formatMoney($overdue_accounts['180_ABOVE']).'</td>
                                    <td>'.(($overdue_accounts['180_ABOVE'] > 0) ? "Due Immediately" : "").'</td>
                                </tr>
                                <tr class="bold">
                                    <td>Total Overdue Accounts</td>
                                    <td class="text-right">'.formatMoney($total_overdue).'</td>
                                    <td></td>
                                </tr>
                                <tr class="bold">
                                    <td>TOTAL PREMIUM RECEIVABLE</td>
                                    <td class="text-right">'.formatMoney($total_premium).'</td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                        <div>
                            <table class="table" cellpadding="3">
                                <tr class="header">
                                    <td colspan="3">TAXES RECEIVABLE</td>
                                </tr>
                                <tr class="bold">
                                    <td>AGING DAYS</td>
                                    <td class="text-right">OUTSTANDING DST</td>
                                    <td class="text-right">OUTSTANDING CWT</td>
                                </tr>';

                                $message .= $tax_message;
                                
                                $message .= '<tr class="bold">
                                    <td>Total Current Accounts</td>
                                    <td class="text-right">'.formatMoney($total_tax_current['dst']).'</td>
                                    <td class="text-right">'.formatMoney($total_tax_current['cwt']).'</td>
                                </tr>
                                <tr>
                                    <td>91 - 180 Days</td>
                                    <td class="text-right">'.formatMoney($tax_overdue['91_180']['dst']).'</td>
                                    <td class="text-right">'.formatMoney($tax_overdue['91_180']['cwt']).'</td>
                                </tr>
                                <tr>
                                    <td>Above 180 Days</td>
                                    <td class="text-right">'.formatMoney($tax_overdue['180_ABOVE']['dst']).'</td>
                                    <td class="text-right">'.formatMoney($tax_overdue['180_ABOVE']['cwt']).'</td>
                                </tr>
                                <tr class="bold">
                                    <td>Total Overdue Accounts</td>
                                    <td class="text-right">'.formatMoney($total_tax['dst']).'</td>
                                    <td class="text-right">'.formatMoney($total_tax['cwt']).'</td>
                                </tr>
                                <tr class="bold">
                                    <td>TOTAL TAXES RECEIVABLE</td>
                                    <td class="text-right">'.formatMoney($total_dst).'</td>
                                    <td class="text-right">'.formatMoney($total_cwt).'</td>
                                </tr>
                                <tr class="bold">
                                    <td colspan="2">GRAND TOTAL</td>
                                    <td class="text-right">'.$grand_total.'</td>
                                </tr>
                            </table>
                        </div>
                        <br>
                        <p class="title-text">Overdue Accounts:</p>
                        <br>
                        <p>We have already given sufficient time to settle the policies by giving you a credit term.
                            Thus, immediate payments are requested for all
                            <span class="font-bold">overdue accounts amounting to <strong>Php '.$grand_total.'</strong></span>.
                            Failure to comply will compel us to <strong>CANCEL these policies within the month.</strong>
                        </p>
                        <br>
                        <br>
                        <p class="title-text">Review SOA Details:</p>
                        <br>
                        <p>Due to timing difference, there may be policies wherein payments have been remitted to us but we have not posted yet and therefore not considered in our month end SOA extraction.
                        Kindly disregard if payment for these policies has been settled. Please refer to the Payments section below for additional reminders.
                        </p>
                        <br>
                        <br>
                        <p class="title-text">Payments:</p>
                        <br>
                        <p>We\'ve attached our preferred payment channel together with the payment guidelines.</p>
                        <br>
                        <p>Should you have further concerns, kindly reply to this email for us to assist you better.</p>
                        <p>For further inquiries, please reply to this email for us to assist you better.</p>
                        <p>We trust that you will give this matter your utmost attention and we look forward to hearing from you soon.</p>
                        <br>
                        <br>
                        <p>Thank you.</p>                    
                    ';
            return $message;
        }

        public static function soa3160DPDCollectionReminder($master_list_id, $as_of_date){
            includeModel('Finance');
            $master_list = recastArray(Finance::getMasterlistById($master_list_id));

            $message = '<div style="font-size: 10px; line-height: 1.5;">
                            <div>
                                <span>'.$master_list['source_name'].'</span><br>
                                <span>'.$master_list['address'].'</span>
                            </div>
                            <div style="text-align: center;">
                                <p><strong>Subject: Reminder: Premium Payment Due for Accounts 31-60 Days</strong></p>
                            </div>
                            <p>Dear '.$master_list['source_name'].',</p>
                            <p> We trust this message finds you well. We are writing to follow up on the premium payment for the accounts that are currently 
                                31-60 days past due, you may refer to the previously submitted SOA for the list. As we near the end of the month, we would like to emphasize the importance of settling these outstanding 
                                premiums promptly to avoid any potential issues in the future. 
                            </p>

                            <p>Please note that Under Sec. 65 of the Insurance code (R.A. 10607), the Insurance Company can terminate the insurance coverage of 
                                the policy holder in the event that the premium will not be paid. Failure to settle these premiums within the agreed credit term 
                                may result in policy cancellation. Our intention is to prevent such circumstances and maintain a strong and mutually beneficial 
                                relationship going forward. 
                            </p>

                            <p>We appreciate your attention and your immediate action in remitting the outstanding payments for the mentioned accounts.
                                If you have any inquiries or concerns, please do not hesitate to contact me directly at '.$master_list['handler_contact_number'].'.
                            </p>

                            <p>Thank you for your cooperation.</p>
                            <p>Sincerely Yours,</p>
                            <div>
                                <span style="font-weight: bold;">'.$master_list['handler'].'</span><br>
                                <span>'.$master_list['handler_contact_number'].'</span><br>
                                <span>'.$master_list['handler_email'].'</span>
                            </div>

                        </div>';
            return $message;
        }

        public static function soa6190DPDCollectionReminder($master_list_id, $as_of_date){
            includeModel('Finance');
            $master_list = recastArray(Finance::getMasterlistById($master_list_id));

            $message = '<div style="font-size: 10px; line-height: 1.5;">
                            <div>
                                <span>'.$master_list['source_name'].'</span><br>
                                <span>'.$master_list['address'].'</span>
                            </div>
                            <div style="text-align: center;">
                                <p><strong>Subject: Reminder: Premium Payment Due for Accounts 61-90 Days</strong></p>
                            </div>
                            <p>Dear '.$master_list['source_name'].',</p>
                            <p> We trust this message finds you well. We are writing to follow up on the premium payment for the accounts that are currently 
                                61-90 days past due, you may refer to the previously submitted SOA for the list. As we near the end of the month, we would like to emphasize the importance of settling these outstanding 
                                premiums promptly to avoid any potential issues in the future. 
                            </p>

                            <p>Please note that Under Sec. 65 of the Insurance code (R.A. 10607), the Insurance Company can terminate the insurance coverage of 
                                the policy holder in the event that the premium will not be paid. Failure to settle these premiums within the agreed credit term 
                                may result in policy cancellation. Our intention is to prevent such circumstances and maintain a strong and mutually beneficial 
                                relationship going forward. 
                            </p>

                            <p>We appreciate your attention and your immediate action in remitting the outstanding payments for the mentioned accounts.
                                If you have any inquiries or concerns, please do not hesitate to contact me directly at '.$master_list['handler_contact_number'].'.
                            </p>

                            <p>Thank you for your cooperation.</p>
                            <p>Sincerely Yours,</p>
                            <div>
                                <span style="font-weight: bold;">'.$master_list['handler'].'</span><br>
                                <span>'.$master_list['handler_contact_number'].'</span><br>
                                <span>'.$master_list['handler_email'].'</span>
                            </div>

                        </div>';
            return $message;
        }

        public static function soaFirstReminderwithNoticeofCancellation($master_list_id, $as_of_date){
            includeModel('Finance');
            $master_list = recastArray(Finance::getMasterlistById($master_list_id));
            $message = '
                    <div style="font-size:10px; line-height:1.5; text-align:left;">
                        <div style="text-align:center; margin-bottom:20px;">
                            <p style="font-weight:bold; text-decoration:underline; margin:0;">
                                Above 90 Days Past Due Collection Reminder
                            </p>
                        </div>
                        <div style="margin-bottom:20px;">
                            <span>'.$master_list['source_name'].'</span><br>
                            <span>'.$master_list['address'].'</span>
                        </div>
                        <div style="text-align:center; margin-bottom:20px;">
                            <p style="margin:0;">
                                Subject: Reminder: Payment Due for Outstanding Premiums
                            </p>
                        </div>

                        <p>Dear '.$master_list['source_name'].',</p>

                        <p>
                            We are writing to follow up on the premium payment for the accounts that are overdue already
                            aging 91 days and above. Despite our previous follow-up attempts these accounts remain unpaid as of today.
                            We would like to emphasize the importance of settling these outstanding premiums promptly.
                            Below is the details of the said outstanding policies:
                        </p>
                        $outstandingOverdueHtml

                        <p>
                            We understand that unforeseen circumstances can sometimes affect payment timelines. However,
                            it is essential to address these outstanding balances to ensure the continuity of coverage.
                            Please note that Under Sec. 65 of the Insurance code (R.A. 10607), the Insurance Company can
                            terminate the insurance coverage of the policy holder in the event that the premium will not be paid.
                            Failure to do so will compel us to cancel these policies.
                        </p>

                        <p>
                            We appreciate your immediate action in remitting the outstanding payments for the mentioned accounts.
                        </p>

                        <p>
                            If you have any inquiries or concerns, please do not hesitate to contact me directly at
                            '.$master_list['handler_contact_number'].' & '.$master_list['handler_email'].'.
                        </p>

                        <p>
                            Thank you for your cooperation, and we eagerly anticipate your prompt response.
                        </p>

                        <p style="font-weight:bold;">Sincerely Yours,</p>

                        <div style="margin-top:12px;">
                            <span style="font-weight:bold;">'.$master_list['handler'].'</span><br>
                            <span>'.$master_list['handler_contact_number'].'</span><br>
                            <span>'.$master_list['handler_email'].'</span>
                        </div>

                    </div>
                    ';
            return $message;
        }

	}
?>