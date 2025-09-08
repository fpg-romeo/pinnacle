<?php


        require_once('app/library/spreadsheet/autoloader.php');
        require_once('app/models/Soa.php');
        require_once('app/default/database.php');
                        

        use PhpOffice\PhpSpreadsheet\IOFactory;
        use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
        use PhpOffice\PhpSpreadsheet\Writer\Spreadsheet;
        use PhpOffice\PhpSpreadsheet\Style\Alignment;


	class Shortcode{

        private static $aging = ['0 - 30 DPD', '31 - 60 DPD', '61 - 90 DPD'];
        private static $aging_overdue = ['91 - 180 DPD', 'Above 180'];

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

            $document  = $id;
            $watermark = True;
            //$folder    = './upload/soa';

            if (ob_get_contents()) ob_end_clean();
            if($action == "attachment"){
                $filename = $document.'.pdf';
                pdf::generate($filename, $body, 'invoice', 'attachment', $file_path, '');
                $file = array(
                            'location'  => $file_path, 
                            'file_name' => $filename
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
            includeModel(['Soa', 'Finance']);

            $master_list        = recastArray(Finance::getMasterlistById($master_list_id));
            $premium_receivable = Soa::getDetailed('', '2025-08-31', '*', $master_list['source_name']);
            $tax_receivable_dst = Soa::getDST('', '2025-08-31', '*', $master_list['source_name']);
            $tax_receivable_cwt = Soa::getCWT('', '2025-08-31', '*', $master_list['source_name']);
            $cod                = Soa::getCOD('', '2025-08-31', '*', $master_list['source_name']);
            $category           = ($master_list['categories'] == '["Direct"]') ? "GROSS_PREMIUM" : "NET_DUE";
            
            foreach(self::$aging as $age){
                $current_accounts[$age] = array_sum(array_column(array_filter($premium_receivable ?? [], fn($row) => $row['AGING_BUCKET'] == $age), $category));
                $cod_policies[$age] = array_sum(array_column(array_filter($cod ?? [], fn($row) => $row['AGING_BUCKET'] == $age), $category));
                $tax_current[$age]['dst'] = array_sum(array_column(array_filter($tax_receivable_dst ?? [], fn($row) => $row['AGING_BUCKET'] == $age), $category));
                $tax_current[$age]['cwt'] = array_sum(array_column(array_filter($tax_receivable_cwt ?? [], fn($row) => $row['AGING_BUCKET'] == $age), $category));
            }

            foreach(self::$aging_overdue as $age){
                $overdue_accounts[$age] = array_sum(array_column(array_filter($premium_receivable ?? [], fn($row) => $row['AGING_BUCKET'] == $age), $category));
                $cod_policies[$age] = array_sum(array_column(array_filter($cod ?? [], fn($row) => $row['AGING_BUCKET'] == $age), $category));
                $tax_overdue[$age]['dst'] = array_sum(array_column(array_filter($tax_receivable_dst ?? [], fn($row) => $row['AGING_BUCKET'] == $age), $category));
                $tax_overdue[$age]['cwt'] = array_sum(array_column(array_filter($tax_receivable_cwt ?? [], fn($row) => $row['AGING_BUCKET'] == $age), $category));
            }

            $total_current = array_sum($current_accounts);
            $total_overdue = array_sum($overdue_accounts);
            $total_cod = array_sum($cod_policies);

            $total_premium  = $total_current+$total_overdue;


            $total_tax_current = array();
            $tax_message = '';
            foreach($tax_current as $key=>$current){
                $tax_message .= '<tr>
                                <td>'.str_replace('DPD', 'Days', $key).'</td>';
                foreach($current as $tax_key=>$tax){
                    $tax_message .= '<td class="text-right">'.formatMoney($tax).'</td>';
                    $total_tax_current[$tax_key] = ($total_tax_current[$tax_key] ?? 0) + $tax;
                }
                $tax_message .= '</tr>';
            }
            
            $total_tax['dst'] = $tax_overdue['91 - 180 DPD']['dst'] + $tax_overdue['Above 180']['dst'];
            $total_tax['cwt'] = $tax_overdue['91 - 180 DPD']['cwt'] + $tax_overdue['Above 180']['cwt'];
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
                            .title-text{
                                text-decoration: underline;
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
                                    <td class="text-right">'.($master_list['categories'] == '["Direct"]' ? "GROSS PREMIUM DUE" : "NET PREMIUM DUE").'</td>
                                </tr>';

                                foreach($cod_policies as $key=>$cod_policy){
                                    $message .= '<tr>
                                                    <td>'.str_replace('DPD', ' Days ', $key).'</td>
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
                                    <td class="text-right">'.($master_list['categories'] == '["Direct"]' ? "GROSS PREMIUM DUE" : "NET PREMIUM DUE").'</td>
                                    <td>PAYMENT DUE DATE</td>
                                </tr>';
                                $counter = 3;
                                $as_of_date = new DateTime($as_of_date);

                                foreach ($current_accounts as $key => $current_account) {
                                    $month = self::addMonthNoOverflow($as_of_date, $counter);

                                    $lastDay = (clone $month)->modify('last day of this month');
                                    $duedate = ($current_account > 0) ? $lastDay->format("F d, Y") : "";

                                    $message .= '<tr>
                                                    <td>' . str_replace('DPD', 'Days', $key) . '</td>
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
                                </tr>';
                                
                                foreach($tax_overdue as $key=>$tax){
                                    $message .= '<tr>
                                                    <td>'.str_replace('DPD','Days', $key).'</td>
                                                    <td class="text-right">'.formatMoney($tax['dst']).'</td>
                                                    <td class="text-right">'.formatMoney($tax['cwt']).'</td>
                                                </tr>';
                                }

                     $message .= '<tr class="bold">
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
                        <p class="title-text">Overdue Accounts:</p>
                        <p>We have already given sufficient time to settle the policies by giving you a credit term.
                            Thus, immediate payments are requested for all
                            <span class="font-bold">overdue accounts amounting to <strong>Php '.$grand_total.'</strong></span>.
                            Failure to comply will compel us to <strong>CANCEL these policies within the month.</strong>
                        </p>
                        <br>
                        <p class="title-text">Review SOA Details:</p>
                        <p>Due to timing difference, there may be policies wherein payments have been remitted to us but we have not posted yet and therefore not considered in our month end SOA extraction.
                        Kindly disregard if payment for these policies has been settled. Please refer to the Payments section below for additional reminders.
                        </p>
                        <br>
                        <p class="title-text">Payments:</p>
                        <p>We\'ve attached our preferred payment channel together with the payment guidelines.</p>
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
                            <p>We trust this message finds you well. We are writing to follow up on the premium payment for the accounts that are currently 
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

                            <p>Thank you for your cooperation.</p><br>
                            <div>
                                Sincerely Yours,<br>
                                <b>'.$master_list['handler'].'</b><br>
                               '.$master_list['handler_contact_number'].'<br>
                                '.$master_list['handler_email'].'
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
                            <div>
                                Sincerely Yours,<br>
                                <b>'.$master_list['handler'].'</b><br>
                               '.$master_list['handler_contact_number'].'<br>
                                '.$master_list['handler_email'].'
                            </div>

                        </div>';
            return $message;
        }

        public static function soaFirstReminderwithNoticeofCancellation($master_list_id, $as_of_date){
            includeModel(['Finance', 'Soa']);
            $master_list            = recastArray(Finance::getMasterlistById($master_list_id));
            $get_outstanding        = Soa::getOutstandingOverdue('', $as_of_date, '', $master_list['source_name']);
            $column                 = ($master_list['categories'] == '["Direct"]') ? "GROSS_PREMIUM" : "NET_DUE";
            $outstanding_overdue    = array();

            $aging = ['91_120_DAYS', '121_150_DAYS', '151_180_DAYS', '181_210_DAYS', '211_360_DAYS', 'DAYS_OVER_361']; 
            foreach ($aging as $age) {
                foreach ($get_outstanding as $row) {
                    $month = date("F Y", strtotime($row['EFFECTIVE_DATE']));
                    $value = $row[$age] ?? 0;

                    if ($value != 0) {
                        $outstanding_overdue[$age][$month] = 
                            ($outstanding_overdue[$age][$month] ?? 0) + $value;
                    }
                }
            }

            // pre($outstanding_overdue);

            // foreach($outstanding_overdue as $key=>$outstanding){
            //     pre(count($outstanding));
            //     foreach($outstanding as $month_key=>$permonth){
            //     }
            // }
            // die;

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
                            <p style="margin:0;">Subject: Reminder: Payment Due for Outstanding Premiums</p>
                        </div>

                        <p>Dear '.$master_list['source_name'].',</p>

                        <p>We are writing to follow up on the premium payment for the accounts that are overdue already
                            aging 91 days and above. Despite our previous follow-up attempts these accounts remain unpaid as of today.
                            We would like to emphasize the importance of settling these outstanding premiums promptly.
                            Below is the details of the said outstanding policies:</p>
                        
                        <table class="table" cellpadding="4">
                            <tr class="header">
                                <td>No. of OUTSTANDING</td>
                                <td>MONTH</td>
                                <td>TOTAL '.str_replace('_', ' ', $column).'</td>
                                <td>PAYMENT DUE DATE</td>
                            </tr>';
                        $counter = 1;
                        $days = '';
                        $rowspan = 0;
                        foreach ($outstanding_overdue as $months) {
                            $rowspan += count($months);
                        }
                        
                        foreach($outstanding_overdue as $key=>$outstanding){
                            foreach($outstanding as $month_key=>$permonth){
                                $message .= '<tr>';
                                                if($days != $key){
                                                    $message .= '<td rowspan="'.count($outstanding).'">'.str_replace('_', ' ', $key).'</td>';
                                                    $days = $key;
                                                }
                                $message .=    '<td>'.$month_key.'</td>
                                                <td>'.formatMoney($permonth).'</td>';
                                                if($counter == 1){
                                                    $message .= '<td style="vertical-align: middle" rowspan="'.$rowspan.'">FOR CANCELLATION / FOR IMMEDIATE PAYMENT</td>';
                                                    
                                                    $counter++;
                                                }
                                $message .= '</tr>';
                                
                            }
                        }
                        
            $message .= '
                            <tr class="bold">
                                <td></td>
                                <td>Total Overdue</td>
                                <td>'.formatMoney(array_sum(
                                            array_map('array_sum', $outstanding_overdue)
                                        )).'</td>
                                <td></td>
                            </tr>
                        </table>
                        <p>We understand that unforeseen circumstances can sometimes affect payment timelines. However,
                            it is essential to address these outstanding balances to ensure the continuity of coverage.
                            Please note that Under Sec. 65 of the Insurance code (R.A. 10607), the Insurance Company can
                            terminate the insurance coverage of the policy holder in the event that the premium will not be paid.
                            Failure to do so will compel us to cancel these policies.</p>

                        <p>We appreciate your immediate action in remitting the outstanding payments for the mentioned accounts.</p>

                        <p>If you have any inquiries or concerns, please do not hesitate to contact me directly at
                            '.$master_list['handler_contact_number'].' & '.$master_list['handler_email'].'.</p>

                        <p>Thank you for your cooperation, and we eagerly anticipate your prompt response.</p>

                        <div>
                            Sincerely Yours,<br>
                            <b>'.$master_list['handler'].'</b><br>
                            '.$master_list['handler_contact_number'].'<br>
                            '.$master_list['handler_email'].'
                        </div>

                    </div>
                    ';
            return $message;
        }

        public static function soaFinalReminderwithNoticeofCancellation($master_list_id, $as_of_date){
            includeModel(['Finance', 'Soa']);
            $master_list            = recastArray(Finance::getMasterlistById($master_list_id));
            $get_outstanding        = Soa::getOutstandingOverdue('', $as_of_date, '', $master_list['source_name']);
            $column                 = ($master_list['categories'] == '["Direct"]') ? "GROSS_PREMIUM" : "NET_DUE";
            $outstanding_overdue    = array();

            $aging = ['91_120_DAYS', '121_150_DAYS', '151_180_DAYS', '181_210_DAYS', '211_360_DAYS', 'DAYS_OVER_361']; 
            foreach ($aging as $age) {
                foreach ($get_outstanding as $row) {
                    $month = date("F Y", strtotime($row['EFFECTIVE_DATE']));
                    $value = $row[$age] ?? 0;

                    if ($value != 0) {
                        $outstanding_overdue[$age][$month] = 
                            ($outstanding_overdue[$age][$month] ?? 0) + $value;
                    }
                }
            }

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
                    <div style="font-size:10px; line-height:1.5; text-align:left;">
                        <div style="margin-bottom:20px;">
                            <span>'.$master_list['source_name'].'</span><br>
                            <span>'.$master_list['address'].'</span>
                        </div>
                        <div style="text-align:center; margin-bottom:20px;">
                            <p style="margin:0;">
                                Final Collection Reminder with notice of cancellation
                            </p>
                        </div>

                        <p>Dear '.$master_list['source_name'].',</p>

                        <p>This is to remind you of the unpaid premiums with FPG that are beyond the approved credit term. Below is the summary based on number of days past due:</p>
                        
                        <table class="table" cellpadding="4">
                            <tr class="header">
                                <td>No. of OUTSTANDING</td>
                                <td>MONTH</td>
                                <td>TOTAL '.str_replace('_', ' ', $column).'</td>
                                <td>PAYMENT DUE DATE</td>
                            </tr>';
                        $counter = 1;
                        $days = '';
                        $rowspan = 0;
                        foreach ($outstanding_overdue as $months) {
                            $rowspan += count($months);
                        }
                        
                        foreach($outstanding_overdue as $key=>$outstanding){
                            foreach($outstanding as $month_key=>$permonth){
                                $message .= '<tr>';
                                                if($days != $key){
                                                    $message .= '<td rowspan="'.count($outstanding).'">'.str_replace('_', ' ', $key).'</td>';
                                                    $days = $key;
                                                }
                                $message .=    '<td>'.$month_key.'</td>
                                                <td>'.formatMoney($permonth).'</td>';
                                                if($counter == 1){
                                                    $message .= '<td style="vertical-align: middle" rowspan="'.$rowspan.'">FOR CANCELLATION / FOR IMMEDIATE PAYMENT</td>';
                                                    
                                                    $counter++;
                                                }
                                $message .= '</tr>';
                                
                            }
                        }
                        
            $message .= '
                            <tr class="bold">
                                <td></td>
                                <td>Total Overdue</td>
                                <td>'.formatMoney(array_sum(
                                            array_map('array_sum', $outstanding_overdue)
                                        )).'</td>
                                <td></td>
                            </tr>
                        </table>
                        <p>We\'ve attached the complete list in this email for your reference</p>

                        <p>Please note that Under Sec. 65 of the Insurance code (R.A. 10607), the Insurance Company can terminate the insurance coverage of the policy holder in the event that the premium will not be paid. Since these policies are still outstanding, we are compelled to cancel these policies within the month.</p>

                        <p>We trust that you will give this matter your utmost attention.</p>
                        <div>
                            <b>Sincerely Yours,<br>
                            '.$master_list['handler'].'</b><br>
                            '.$master_list['handler_contact_number'].'<br>
                            '.$master_list['handler_email'].'
                        </div>

                    </div>
                    ';
            return $message;
        }

        public static function soaCollectionReminderGeneric($master_list_id, $as_of_date){
            includeModel('Finance');
            $master_list = recastArray(Finance::getMasterlistById($master_list_id));
            
            $message = '
                        <style>
                            *{
                                font-size: 10px;
                            }
                        </style>
                        <div class="text-xs space-y-5 flex flex-col">
                            <p>'.date('F d, Y', strtotime('now')).'</p>
                            <p>Subject: <span class="underline">Urgent: Unpaid Premiums - Immediate Action Required to Prevent Policy Cancellation.</span></p>
                            <p>Dear Valued Partner,</p>
                            <p>We are writing to follow up on the premium payment for the accounts that are overdue. Despite our previous follow-up attempts these accounts remain unpaid.</p>
                            <p>Please note that Under Sec. 65 of the Insurance code (R.A. 10607), the Insurance Company can terminate the insurance coverage of the policy holder in the event that the premium will not be paid. Failure to do so will compel us to cancel these policies by end of this month.</p>
                            <p>If you have any questions or require further assistance, please do not hesitate to contact me at the details below.</p>
                            <p>We trust that you will give this matter your immediate action.</p>
                            <p></p>
                            <p>Warm Regards,</p>
                            <div style="margin-top:12px;">
                                <span style="font-weight:bold;">'.$master_list['handler'].'</span><br>
                                <span>'.$master_list['handler_contact_number'].'</span><br>
                                <span>'.$master_list['handler_email'].'</span>
                            </div>
                        </div>
                        ';

            return $message;
        }

        public static function soaReminderExcel($master_list_id, $as_of_date){



            includeModel(['Finance']);
            includeDefault('excel');
            $previous = "";
            $current  = "";
            $initialval = False;
            $record = array();
            $recorditem = array();
            $cutoffdate = strtoupper(date('F Y', strtotime('last month')));
            $getprocessingdate = date('YmdH');
            $folderpath = 'upload/attachments/'.$cutoffdate.'/'.$getprocessingdate;
            $workbook = IOFactory::load('upload/soa/default/reminder.xlsx');
            $worksheet = $workbook->getSheetByName('SUMMARY');
            $ageinglist = [
                "90 - 120",
                "121 - 150",
                "151 - 180",
                "181 - 210",
                "211 - 240",
                "241 - 270",
                "271 - 300",
                "301 - 330",
                "331 - 360",
                "361 and above"
            ];
            
            $master_list = recastArray(Finance::getMasterlistById($master_list_id));
            $source_name = "CCFM INSURANCE AGENCY CORP. DBA. ASSURANCE";
            (float)$totaldue = 0;
            $startRow = 7;


                if(!is_dir($folderpath)){
                    mkdir($folderpath, 0777, true);
                }

                    foreach($ageinglist as $ageing){

                        $worksheet->setCellValue('B3', 'For the month of '.$cutoffdate); //cutoff date
                        $worksheet->setCellValue('B4', $source_name); //source name

                        $recorditem = Finance::getReminderByPeriod($ageing,$source_name);
                        $currentrow = $startRow;
                        
                            if(is_array($recorditem)){
                               
                                foreach($recorditem as $record){
                                    $worksheet->setCellValue('C'.$currentrow, $record['Month']);
                                    excel::styleCell($worksheet,'C'.$currentrow,Alignment::HORIZONTAL_LEFT);
                                    $worksheet->setCellValue('D'.$currentrow, $record['net_due']);
                                    excel::styleCell($worksheet,'D'.$currentrow,Alignment::HORIZONTAL_RIGHT);
                                    $currentrow ++;
                                    $totaldue = $totaldue + $record['net_due'];
                                }
                                
                                //merge the cells 
                                $worksheet->mergeCells('B'.$startRow.':B'.($currentrow - 1));
                                $worksheet->setCellValue('B'.$startRow, $ageing);
                                excel::styleCell($worksheet,'B'.$startRow.':B'.($currentrow - 1),Alignment::HORIZONTAL_CENTER,Alignment::VERTICAL_CENTER);
                                
                                $startRow = $currentrow;
                            
                            }  
                    }
               
                    $worksheet->mergeCells('E7:E'.($startRow - 1));
                    $worksheet->setCellValue('E7', 'FOR CANCELLATION / FOR IMMEDIATE PAYMENT');
                    $worksheet->getStyle('E7')->getFont()->setBold(true);
                    excel::styleCell($worksheet,'E7:E'.($startRow - 1),Alignment::HORIZONTAL_CENTER,Alignment::VERTICAL_CENTER);
                    $worksheet->setCellValue('C'.$startRow, 'Total Due');
                    $worksheet->getStyle('C'.$startRow)->getFont()->setBold(true);
                    excel::styleCell($worksheet,'C'.$startRow,Alignment::HORIZONTAL_RIGHT);
                    $worksheet->setCellValue('D'.$startRow, $totaldue);
                    $worksheet->getStyle('D'.$startRow)->getFont()->setBold(true);
                    excel::styleCell($worksheet,'D'.$startRow,Alignment::HORIZONTAL_RIGHT);


        // populate detailed list
                    $premiumData = array();
                    $zero = 0;
                    $thirty = 0;
                    $sixty = 0;
                    $ninety = 0;
                    $detailedList = $workbook->getSheetByName('DETAILED LIST');
                    
                    $startRow = 2;
                    $endRow = 10000;
                    $currentrow = $startRow;
                    $premiumreceivable = Soa::getDetailed('', $as_of_date, '*', $source_name,'ageing');
                   
                    if(!empty($premiumreceivable) && is_array($premiumreceivable)){
                                    foreach($premiumreceivable as $detail){
                                       
                                        //initialize the sheet
                                        $columns = range('A', 'AD');

                                        for ($row = $startRow; $row <= $endRow; $row++) {
                                            foreach ($columns as $col) {
                                                $detailedList->setCellValue($col.$row, '');
                                            }
                                        }
                                            
                                            $detailedList->setCellValue('A'.$currentrow, $detail['BOOKING_DATE']);
                                            $detailedList->setCellValue('B'.$currentrow, $detail['INCEPTION_DATE']);
                                            $detailedList->setCellValue('C'.$currentrow, $detail['EXPIRY_DATE']);
                                            $detailedList->setCellValue('D'.$currentrow, $detail['EFFECTIVE_DATE']);
                                            $detailedList->setCellValue('E'.$currentrow, $detail['VOUCHER_DEBIT_CREDIT_PREMIUM']);
                                            $detailedList->setCellValue('F'.$currentrow, $detail['VOUCHER_DEBIT_CREDIT_COMMISSION']);
                                            $detailedList->setCellValue('G'.$currentrow, $detail['OVERIDING_VOUCHER_DEBIT_CREDIT']);
                                            $detailedList->setCellValue('H'.$currentrow, $detail['REFNO']);
                                            $detailedList->setCellValue('I'.$currentrow, $detail['DOCNO']);
                                            $detailedList->setCellValue('J'.$currentrow, $detail['A_POLICYNO']);
                                            $detailedList->setCellValue('K'.$currentrow, $detail['INSURED_NAME']);
                                            $detailedList->setCellValue('L'.$currentrow, $detail['POSTED_PAYMENT']);
                                            $detailedList->setCellValue('M'.$currentrow, $detail['ORIGINAL_BASIC_PREMIUM']);
                                            $detailedList->setCellValue('N'.$currentrow, $detail['PREMIUM']);
                                            $detailedList->setCellValue('O'.$currentrow, $detail['STAMPDUTY']);
                                            $detailedList->setCellValue('P'.$currentrow, $detail['LTO']);
                                            $detailedList->setCellValue('Q'.$currentrow, $detail['LGT']);
                                            $detailedList->setCellValue('R'.$currentrow, $detail['FST']);
                                            $detailedList->setCellValue('S'.$currentrow, $detail['PREMIUMTAX']);
                                            $detailedList->setCellValue('T'.$currentrow, $detail['VAT']); 
                                            $detailedList->setCellValue('U'.$currentrow, $detail['GROSS_PREMIUM']);
                                            $detailedList->setCellValue('V'.$currentrow, $detail['OVERRIDING_DISCOUNT']);
                                            $detailedList->setCellValue('W'.$currentrow, $detail['COMMISSION']);
                                            $detailedList->setCellValue('X'.$currentrow, $detail['INPUT_VAT']);
                                            $detailedList->setCellValue('Y'.$currentrow, $detail['TAXRATE']);
                                            $detailedList->setCellValue('Z'.$currentrow, $detail['TAX_AMOUNT']);
                                            $detailedList->setCellValue('AA'.$currentrow, $detail['GROSS_COMMISSION']);
                                            $detailedList->setCellValue('AB'.$currentrow, $detail['NET_DUE']); 
                                            $detailedList->setCellValue('AC'.$currentrow, $detail['AGING_DAYS']);
                                            $detailedList->setCellValue('AD'.$currentrow, $detail['AGING_BUCKET']);
                                            
                                            $currentrow++;
                                    
                        }
                                    
                                    
         
                    }
                    $filename = $source_name.'- Outstanding Overdue Premiums.xlsx';
                    $writer = new Xlsx($workbook);
                    $writer->save($folderpath.'/'.$filename );
        }

        public static function soaExcel($master_list_id, $as_of_date){
         
            $cutoffdate = strtoupper(date('F Y', strtotime('last month')));
            $getprocessingdate = date('YmdH');
            $folderpath = 'upload/attachments/'.$cutoffdate.'/'.$getprocessingdate;
            $as_of_date = "2025-08-31";

            if(!is_dir($folderpath)){
                mkdir($folderpath, 0777, true);
            }
            
            $with_dst = false;
            $with_cwt = false;


            $premiumreceivable = array();

                    $workbook = IOFactory::load('upload/soa/default/soa.xlsx');
                    $worksheet = $workbook->getSheetByName('SUMMARY');
                    
                 
                    $zero = 0; 
                    $thirty = 0;
                    $sixty = 0;
                    $ninety = 0;
                    $over = 0;
                    $totalcod = 0;
                    $totalcurrent = 0;
                    $totaloverdue = 0;   
                    $source_name = 'CCFM INSURANCE AGENCY CORP. DBA. ASSURANCE';
                    $grandtotal = 0;
                    $totalcwt = 0;
                    $totaldst = 0;
                    $over120 = 0;
                    $totalPremium = 0;
                    $grandtotal = 0;
                    $source_name = $source_name;
                    $worksheet->setCellValue('B4', $source_name); //source name
                    $worksheet->setCellValue('B3', 'For the month of '.$cutoffdate); //cutoff date
                    
                    $premiumData = array();
                
                    $premiumreceivable = Soa::getDetailed('', $as_of_date, '*', $source_name);
                   
                    if(!empty($premiumreceivable) && is_array($premiumreceivable)){

                        //for the figures in summary
                        foreach ($premiumreceivable as $prem) {
                                $over120 = 0;
                                if (
                                    $prem['121_150_DAYS'] != 0 || 
                                    $prem['151_180_DAYS'] != 0 || 
                                    $prem['181_210_DAYS'] != 0 || 
                                    $prem['211_360_DAYS'] != 0 || 
                                    $prem['DAYS_OVER_361'] != 0
                                ) {
                                    $over120 = 
                                        (double)$prem['121_150_DAYS'] + 
                                        (double)$prem['151_180_DAYS'] + 
                                        (double)$prem['181_210_DAYS'] + 
                                        (double)$prem['211_360_DAYS'] + 
                                        (double)$prem['DAYS_OVER_361'];
                                }

                                $premData =  array(
                                    '0_30_DAYS' => (double)$prem['0_30_DAYS'],
                                    '31_60_DAYS' => (double)$prem['31_60_DAYS'],
                                    '61_90_DAYS' => (double)$prem['61_90_DAYS'],
                                    '91_120_DAYS' => (double)$prem['91_120_DAYS'],
                                    'OVER_180_DAYS' => $over120,
                                    'totalreceivable' => (double)$prem['0_30_DAYS'] + (double)$prem['31_60_DAYS'] + (double)$prem['61_90_DAYS'] + (double)$prem['91_120_DAYS'] + $over120,
                                );

                                        $zero += $premData['0_30_DAYS'] ? $premData['0_30_DAYS'] : 0;
                                        $thirty += $premData['31_60_DAYS'] ? $premData['31_60_DAYS'] : 0;
                                        $sixty += $premData['61_90_DAYS'] ? $premData['61_90_DAYS'] : 0;
                                        $ninety += $premData['91_120_DAYS'] ? $premData['91_120_DAYS'] : 0;
                                        $over += $premData['OVER_180_DAYS'] ? $premData['OVER_180_DAYS'] : 0;
                            }
                       

                            //initialize the worksheet fields
                                    $worksheet->setCellValue('C18', '0');
                                    $worksheet->setCellValue('C19', '0');
                                    $worksheet->setCellValue('C20', '0');
                                    $worksheet->setCellValue('C21', '0');
                                    $worksheet->setCellValue('C22', '0');
                                    $worksheet->setCellValue('C23', '0');
                                    $worksheet->setCellValue('C24', '0');
                                    $worksheet->setCellValue('C25', '0');

                                    
                                    $worksheet->setCellValue('C18', $zero); 
                                    $worksheet->setCellValue('C19', $thirty); 
                                    $worksheet->setCellValue('C20', $sixty); 

                                    $totalcurrent = $zero + $thirty + $sixty;
                                    $worksheet->setCellValue('C21', $totalcurrent);

                                    $worksheet->setCellValue('C22', $ninety);
                                    $worksheet->setCellValue('C23', $over);

                                    $totaloverdue = $ninety + $over ;
                                    $worksheet->setCellValue('C24', $totaloverdue);

                                    $totalPremium = $totalcurrent + $totaloverdue;
                                    $worksheet->setCellValue('C25', $totalPremium);

                                    $detailedList = $workbook->getSheetByName('DETAILED LIST');

                                    //initialize the worksheet fields
                                    $detailedList->setCellValue('A2', '');
                                    $detailedrecord = array();
                                    $startRow = 2;
                                    $endRow = 10000;
                                    $currentrow = $startRow;  

                                    foreach($premiumreceivable as $detail){
                                       
                                        //initialize the sheet
                                        $columns = range('A', 'AD');

                                        for ($row = $startRow; $row <= $endRow; $row++) {
                                            foreach ($columns as $col) {
                                                $detailedList->setCellValue($col.$row, '');
                                            }
                                        }
                                            
                                            $detailedList->setCellValue('A'.$currentrow, $detail['BOOKING_DATE']);
                                            $detailedList->setCellValue('B'.$currentrow, $detail['INCEPTION_DATE']);
                                            $detailedList->setCellValue('C'.$currentrow, $detail['EXPIRY_DATE']);
                                            $detailedList->setCellValue('D'.$currentrow, $detail['EFFECTIVE_DATE']);
                                            $detailedList->setCellValue('E'.$currentrow, $detail['VOUCHER_DEBIT_CREDIT_PREMIUM']);
                                            $detailedList->setCellValue('F'.$currentrow, $detail['VOUCHER_DEBIT_CREDIT_COMMISSION']);
                                            $detailedList->setCellValue('G'.$currentrow, $detail['OVERIDING_VOUCHER_DEBIT_CREDIT']);
                                            $detailedList->setCellValue('H'.$currentrow, $detail['REFNO']);
                                            $detailedList->setCellValue('I'.$currentrow, $detail['DOCNO']);
                                            $detailedList->setCellValue('J'.$currentrow, $detail['A_POLICYNO']);
                                            $detailedList->setCellValue('K'.$currentrow, $detail['INSURED_NAME']);
                                            $detailedList->setCellValue('L'.$currentrow, $detail['POSTED_PAYMENT']);
                                            $detailedList->setCellValue('M'.$currentrow, $detail['ORIGINAL_BASIC_PREMIUM']);
                                            $detailedList->setCellValue('N'.$currentrow, $detail['PREMIUM']);
                                            $detailedList->setCellValue('O'.$currentrow, $detail['STAMPDUTY']);
                                            $detailedList->setCellValue('P'.$currentrow, $detail['LTO']);
                                            $detailedList->setCellValue('Q'.$currentrow, $detail['LGT']);
                                            $detailedList->setCellValue('R'.$currentrow, $detail['FST']);
                                            $detailedList->setCellValue('S'.$currentrow, $detail['PREMIUMTAX']);
                                            $detailedList->setCellValue('T'.$currentrow, $detail['VAT']); 
                                            $detailedList->setCellValue('U'.$currentrow, $detail['GROSS_PREMIUM']);
                                            $detailedList->setCellValue('V'.$currentrow, $detail['OVERRIDING_DISCOUNT']);
                                            $detailedList->setCellValue('W'.$currentrow, $detail['COMMISSION']);
                                            $detailedList->setCellValue('X'.$currentrow, $detail['INPUT_VAT']);
                                            $detailedList->setCellValue('Y'.$currentrow, $detail['TAXRATE']);
                                            $detailedList->setCellValue('Z'.$currentrow, $detail['TAX_AMOUNT']);
                                            $detailedList->setCellValue('AA'.$currentrow, $detail['GROSS_COMMISSION']);
                                            $detailedList->setCellValue('AB'.$currentrow, $detail['NET_DUE']); 
                                            $detailedList->setCellValue('AC'.$currentrow, $detail['AGING_DAYS']);
                                            $detailedList->setCellValue('AD'.$currentrow, $detail['AGING_BUCKET']);
                                            
                                            $currentrow++;
                                    
                        }
                                    
                                    
         
                    }

                    //DST
                    $taxreceivable = array();
                    $taxreceivable = Soa::getDst('', $as_of_date, '*', $source_name); 
                
                    if (isset($taxreceivable) && is_array($taxreceivable)) {
                        
                                        $zero = 0;
                                        $thirty = 0;
                                        $sixty = 0;
                                        $ninety = 0;
                                        $over = 0;
                                        $totalcurrent = 0;
                                        $totaloverdue = 0;   
                                        $source_named = '';

                            //initialize the worksheet fields
                                        $worksheet->setCellValue('C30', '0');
                                        $worksheet->setCellValue('C31', '0');
                                        $worksheet->setCellValue('C32', '0');
                                        $worksheet->setCellValue('C33', '0');
                                        $worksheet->setCellValue('C34', '0');
                                        $worksheet->setCellValue('C35', '0');
                                        $worksheet->setCellValue('C36', '0');
                                        $worksheet->setCellValue('C37', '0');

                            foreach ($taxreceivable as $dst) {
                                $over120 = 0;
                                if (
                                    $dst['121_150_DAYS'] != 0 || 
                                    $dst['151_180_DAYS'] != 0 || 
                                    $dst['181_210_DAYS'] != 0 || 
                                    $dst['211_360_DAYS'] != 0 || 
                                    $dst['DAYS_OVER_361'] != 0
                                ) {
                                    $over120 = 
                                        (double)$dst['121_150_DAYS'] + 
                                        (double)$dst['151_180_DAYS'] + 
                                        (double)$dst['181_210_DAYS'] + 
                                        (double)$dst['211_360_DAYS'] + 
                                        (double)$dst['DAYS_OVER_361'];
                                }

                                $dstData =  array(
                                    '0_30_DAYS' => (double)$dst['0_30_DAYS'],
                                    '31_60_DAYS' => (double)$dst['31_60_DAYS'],
                                    '61_90_DAYS' => (double)$dst['61_90_DAYS'],
                                    '91_120_DAYS' => (double)$dst['91_120_DAYS'],
                                    'OVER_180_DAYS' => $over120,
                                    'totalreceivable' => (double)$dst['0_30_DAYS'] + (double)$dst['31_60_DAYS'] + (double)$dst['61_90_DAYS'] + (double)$dst['91_120_DAYS'] + $over120,
                                );

                                        $zero += $dstData['0_30_DAYS'] ? $dstData['0_30_DAYS'] : 0;
                                        $thirty += $dstData['31_60_DAYS'] ? $dstData['31_60_DAYS'] : 0;
                                        $sixty += $dstData['61_90_DAYS'] ? $dstData['61_90_DAYS'] : 0;
                                        $ninety += $dstData['91_120_DAYS'] ? $dstData['91_120_DAYS'] : 0;
                                        $over += $dstData['OVER_180_DAYS'] ? $dstData['OVER_180_DAYS'] : 0;
                            }

                                $worksheet->setCellValue('C30', $zero); 
                                $worksheet->setCellValue('C31', $thirty); 
                                $worksheet->setCellValue('C32', $sixty); 

                                $totalcurrentd = $zero + $thirty + $sixty;
                                $worksheet->setCellValue('C33', $totalcurrent ? $totalcurrent : 0);

                                $worksheet->setCellValue('C34', $ninety);
                                $worksheet->setCellValue('C35', $over);

                                $totaloverdue = $ninety + $over;
                                $worksheet->setCellValue('C36', $totaloverdue ? $totaloverdue : 0);

                                $totaldst = $totalcurrent + $totaloverdue;
                                $worksheet->setCellValue('C37', $totaldst ? $totaldst : 0);
                            
                                $dstlist = $workbook->getSheetByName('DST BALANCE');

                                $startRow = 2;
                                $endRow = 10000;
                                $currentrow = $startRow; 
                                        //initialize the sheet
                                $columns = range('A', 'AD');

                                for ($row = $startRow; $row <= $endRow; $row++) {
                                    foreach ($columns as $col) {
                                                $dstlist->setCellValue($col.$row, '');
                                        }
                                }

                                 foreach($taxreceivable as $detaildst){
                                        
                                        $dstlist->setCellValue('A'.$currentrow, $detaildst['BOOKING_DATE']);
                                        $dstlist->setCellValue('B'.$currentrow, $detaildst['INCEPTION_DATE']);
                                        $dstlist->setCellValue('C'.$currentrow, $detaildst['EXPIRY_DATE']);
                                        $dstlist->setCellValue('D'.$currentrow, $detaildst['EFFECTIVE_DATE']);
                                        $dstlist->setCellValue('E'.$currentrow, $detaildst['VOUCHER_DEBIT_CREDIT_PREMIUM']);
                                        $dstlist->setCellValue('F'.$currentrow, $detaildst['VOUCHER_DEBIT_CREDIT_COMMISSION']);
                                        $dstlist->setCellValue('G'.$currentrow, $detaildst['OVERIDING_VOUCHER_DEBIT_CREDIT']);
                                        $dstlist->setCellValue('H'.$currentrow, $detaildst['REFNO']);
                                        $dstlist->setCellValue('I'.$currentrow, $detaildst['DOCNO']);
                                        $dstlist->setCellValue('J'.$currentrow, $detaildst['A_POLICYNO']);
                                        $dstlist->setCellValue('K'.$currentrow, $detaildst['INSURED_NAME']);
                                        $dstlist->setCellValue('L'.$currentrow, $detaildst['POSTED_PAYMENT']);
                                        $dstlist->setCellValue('M'.$currentrow, $detaildst['ORIGINAL_BASIC_PREMIUM']);
                                        $dstlist->setCellValue('N'.$currentrow, $detaildst['PREMIUM']);
                                        $dstlist->setCellValue('O'.$currentrow, $detaildst['STAMPDUTY']);
                                        $dstlist->setCellValue('P'.$currentrow, $detaildst['LTO']);
                                        $dstlist->setCellValue('Q'.$currentrow, $detaildst['LGT']);
                                        $dstlist->setCellValue('R'.$currentrow, $detaildst['FST']);
                                        $dstlist->setCellValue('S'.$currentrow, $detaildst['PREMIUMTAX']);
                                        $dstlist->setCellValue('T'.$currentrow, $detaildst['VAT']); 
                                        $dstlist->setCellValue('U'.$currentrow, $detaildst['GROSS_PREMIUM']);
                                        $dstlist->setCellValue('V'.$currentrow, $detaildst['OVERRIDING_DISCOUNT']);
                                        $dstlist->setCellValue('W'.$currentrow, $detaildst['COMMISSION']);
                                        $dstlist->setCellValue('X'.$currentrow, $detaildst['INPUT_VAT']);
                                        $dstlist->setCellValue('Y'.$currentrow, $detaildst['TAXRATE']);
                                        $dstlist->setCellValue('Z'.$currentrow, $detaildst['TAX_AMOUNT']);
                                        $dstlist->setCellValue('AA'.$currentrow, $detaildst['GROSS_COMMISSION']);
                                        $dstlist->setCellValue('AB'.$currentrow, $detaildst['NET_DUE']); 
                                        $dstlist->setCellValue('AC'.$currentrow, $detaildst['AGING_DAYS']);
                                        $dstlist->setCellValue('AD'.$currentrow, $detaildst['AGING_BUCKET']);
                                        
                                        $currentrow++;
                                }

                    }

                    $taxreceivablecwt = array();
                    $taxreceivablecwt = Soa::getCWT('',$as_of_date, '*', $source_name);

                     if (isset($taxreceivablecwt)) {

                        $zero = 0;
                        $thirty = 0;
                        $sixty = 0;
                        $ninety = 0;
                        $over = 0;
                        $totalcurrent = 0;
                        $totaloverdue = 0;

                                    $worksheet->setCellValue('D30', '0');
                                    $worksheet->setCellValue('D31', '0');
                                    $worksheet->setCellValue('D32', '0');
                                    $worksheet->setCellValue('D33', '0');
                                    $worksheet->setCellValue('D34', '0');
                                    $worksheet->setCellValue('D35', '0');
                                    $worksheet->setCellValue('D36', '0');
                                    $worksheet->setCellValue('D37', '0');

                            foreach ($taxreceivablecwt as $cwt) {
                                $over120 = 0;
                                if (
                                    $cwt['121_150_DAYS'] != 0 || 
                                    $cwt['151_180_DAYS'] != 0 || 
                                    $cwt['181_210_DAYS'] != 0 || 
                                    $cwt['211_360_DAYS'] != 0 || 
                                    $cwt['DAYS_OVER_361'] != 0
                                ) {
                                    $over120 = 
                                        (double)$cwt['121_150_DAYS'] + 
                                        (double)$cwt['151_180_DAYS'] + 
                                        (double)$cwt['181_210_DAYS'] + 
                                        (double)$cwt['211_360_DAYS'] + 
                                        (double)$cwt['DAYS_OVER_361'];
                                }

                                $cwtData = array(
                                    '0_30_DAYS' => (double)$cwt['0_30_DAYS'],
                                    '31_60_DAYS' => (double)$cwt['31_60_DAYS'],
                                    '61_90_DAYS' => (double)$cwt['61_90_DAYS'],
                                    '91_120_DAYS' => (double)$cwt['91_120_DAYS'],
                                    'OVER_180_DAYS' => $over120,
                                    'totalreceivable' => (double)$cwt['0_30_DAYS'] + (double)$cwt['31_60_DAYS'] + (double)$cwt['61_90_DAYS'] + (double)$cwt['91_120_DAYS'] + $over120,
                                );
                            }
  
                            $worksheet->setCellValue('D30', $zero); 
                            $worksheet->setCellValue('D31', $thirty); 
                            $worksheet->setCellValue('D32', $sixty); 

                            $totalcurrent = $zero + $thirty + $sixty;
                            $worksheet->setCellValue('D33', $totalcurrent);

                            $worksheet->setCellValue('D34', $ninety);
                            $worksheet->setCellValue('D35', $over);

                            $totaloverdue = $ninety + $over;
                            $worksheet->setCellValue('D36', $totaloverdue ? $totaloverdue : 0);

                            $totalcwt = $totalcurrent + $totaloverdue;
                            $worksheet->setCellValue('D37', $totalcwt ? $totalcwt : 0);
                        
                            $cwtlist = $workbook->getSheetByName('CWT BALANCE');

                                $startRow = 2;
                                $endRow = 10000;
                                $currentrow = $startRow;
                                
                                $columns = range('A', 'AD');

                                for ($row = $startRow; $row <= $endRow; $row++) {
                                    foreach ($columns as $col) {
                                               $cwtlist->setCellValue($col.$row, '');
                                    }
                                }


                                foreach($taxreceivablecwt as $detailcwt){
                                    
                                    $cwtlist->setCellValue('A'.$currentrow, $detailcwt['BOOKING_DATE']);
                                    $cwtlist->setCellValue('B'.$currentrow, $detailcwt['INCEPTION_DATE']);
                                    $cwtlist->setCellValue('C'.$currentrow, $detailcwt['EXPIRY_DATE']);
                                    $cwtlist->setCellValue('D'.$currentrow, $detailcwt['EFFECTIVE_DATE']);
                                    $cwtlist->setCellValue('E'.$currentrow, $detailcwt['VOUCHER_DEBIT_CREDIT_PREMIUM']);
                                    $cwtlist->setCellValue('F'.$currentrow, $detailcwt['VOUCHER_DEBIT_CREDIT_COMMISSION']);
                                    $cwtlist->setCellValue('G'.$currentrow, $detailcwt['OVERIDING_VOUCHER_DEBIT_CREDIT']);
                                    $cwtlist->setCellValue('H'.$currentrow, $detailcwt['REFNO']);
                                    $cwtlist->setCellValue('I'.$currentrow, $detailcwt['DOCNO']);
                                    $cwtlist->setCellValue('J'.$currentrow, $detailcwt['A_POLICYNO']);
                                    $cwtlist->setCellValue('K'.$currentrow, $detailcwt['INSURED_NAME']);
                                    $cwtlist->setCellValue('L'.$currentrow, $detailcwt['POSTED_PAYMENT']);
                                    $cwtlist->setCellValue('M'.$currentrow, $detailcwt['ORIGINAL_BASIC_PREMIUM']);
                                    $cwtlist->setCellValue('N'.$currentrow, $detailcwt['PREMIUM']);
                                    $cwtlist->setCellValue('O'.$currentrow, $detailcwt['STAMPDUTY']);
                                    $cwtlist->setCellValue('P'.$currentrow, $detailcwt['LTO']);
                                    $cwtlist->setCellValue('Q'.$currentrow, $detailcwt['LGT']);
                                    $cwtlist->setCellValue('R'.$currentrow, $detailcwt['FST']);
                                    $cwtlist->setCellValue('S'.$currentrow, $detailcwt['PREMIUMTAX']);
                                    $cwtlist->setCellValue('T'.$currentrow, $detailcwt['VAT']); 
                                    $cwtlist->setCellValue('U'.$currentrow, $detailcwt['GROSS_PREMIUM']);
                                    $cwtlist->setCellValue('V'.$currentrow, $detailcwt['OVERRIDING_DISCOUNT']);
                                    $cwtlist->setCellValue('W'.$currentrow, $detailcwt['COMMISSION']);
                                    $cwtlist->setCellValue('X'.$currentrow, $detailcwt['INPUT_VAT']);
                                    $cwtlist->setCellValue('Y'.$currentrow, $detailcwt['TAXRATE']);
                                    $cwtlist->setCellValue('Z'.$currentrow, $detailcwt['TAX_AMOUNT']);
                                    $cwtlist->setCellValue('AA'.$currentrow, $detailcwt['GROSS_COMMISSION']);
                                    $cwtlist->setCellValue('AB'.$currentrow, $detailcwt['NET_DUE']); 
                                    $cwtlist->setCellValue('AC'.$currentrow, $detailcwt['AGING_DAYS']);
                                    $cwtlist->setCellValue('AD'.$currentrow, $detailcwt['AGING_BUCKET']);
                                    
                                    $currentrow++;
                                }
                          
                    }

                    $codreceivable = array();
                    $codreceivable =  Soa::getCOD('',$as_of_date, '*',$source_name);
                    
                    if (isset($codreceivable)) {
                      
                        $zero = 0;
                        $thirty = 0;
                        $sixty = 0;
                        $ninety = 0;
                        $over = 0;
                        $totalcurrent = 0;
                        $totaloverdue = 0;

                                    $worksheet->setCellValue('C8', '0');
                                    $worksheet->setCellValue('C9', '0');
                                    $worksheet->setCellValue('C10', '0');
                                    $worksheet->setCellValue('C11', '0');
                                    $worksheet->setCellValue('C12', '0');
                                    $worksheet->setCellValue('C13', '0');


                            foreach ($codreceivable as $cod) {
                                $over120 = 0;
                                if (
                                    $cod['121_150_DAYS'] != 0 || 
                                    $cod['151_180_DAYS'] != 0 || 
                                    $cod['181_210_DAYS'] != 0 || 
                                    $cod['211_360_DAYS'] != 0 || 
                                    $cod['DAYS_OVER_361'] != 0
                                ) {
                                    $over120 = 
                                        (double)$cod['121_150_DAYS'] + 
                                        (double)$cod['151_180_DAYS'] + 
                                        (double)$cod['181_210_DAYS'] + 
                                        (double)$cod['211_360_DAYS'] + 
                                        (double)$cod['DAYS_OVER_361'];
                                }

                                $cwtData = array(
                                    '0_30_DAYS' => (double)$cod['0_30_DAYS'],
                                    '31_60_DAYS' => (double)$cod['31_60_DAYS'],
                                    '61_90_DAYS' => (double)$cod['61_90_DAYS'],
                                    '91_120_DAYS' => (double)$cod['91_120_DAYS'],
                                    'OVER_180_DAYS' => $over120,
                                    'totalreceivable' => (double)$cod['0_30_DAYS'] + (double)$cod['31_60_DAYS'] + (double)$cod['61_90_DAYS'] + (double)$cod['91_120_DAYS'] + $over120,
                                );

                                        $zero += $cwtData['0_30_DAYS'] ? $cwtData['0_30_DAYS'] : 0;
                                        $thirty += $cwtData['31_60_DAYS'] ? $cwtData['31_60_DAYS'] : 0;
                                        $sixty += $cwtData['61_90_DAYS'] ? $cwtData['61_90_DAYS'] : 0;
                                        $ninety += $cwtData['91_120_DAYS'] ? $cwtData['91_120_DAYS'] : 0;
                                        $over += $cwtData['OVER_180_DAYS'] ? $cwtData['OVER_180_DAYS'] : 0;
                            }
  
                            $worksheet->setCellValue('C8', $zero); 
                            $worksheet->setCellValue('C9', $thirty); 
                            $worksheet->setCellValue('C10', $sixty); 
                            $worksheet->setCellValue('C11', $ninety);
                            $worksheet->setCellValue('C12', $over);

                            $totalcod = $ninety + $over + $zero + $thirty + $sixty;
                            $worksheet->setCellValue('C13', $totalcod ? $totalcod : 0);
                        
                            $codlist = $workbook->getSheetByName('COD POLICIES');

                                $startRow = 2;
                                $endRow = 10000;
                                $currentrow = $startRow;
                                
                                $columns = range('A', 'AD');

                                for ($row = $startRow; $row <= $endRow; $row++) {
                                    foreach ($columns as $col) {
                                               $codlist->setCellValue($col.$row, '');
                                    }
                                }


                                foreach($codreceivable as $detailcod){
                                    
                                    $codlist->setCellValue('A'.$currentrow, $detailcod['BOOKING_DATE']);
                                    $codlist->setCellValue('B'.$currentrow, $detailcod['INCEPTION_DATE']);
                                    $codlist->setCellValue('C'.$currentrow, $detailcod['EXPIRY_DATE']);
                                    $codlist->setCellValue('D'.$currentrow, $detailcod['EFFECTIVE_DATE']);
                                    $codlist->setCellValue('E'.$currentrow, $detailcod['VOUCHER_DEBIT_CREDIT_PREMIUM']);
                                    $codlist->setCellValue('F'.$currentrow, $detailcod['VOUCHER_DEBIT_CREDIT_COMMISSION']);
                                    $codlist->setCellValue('G'.$currentrow, $detailcod['OVERIDING_VOUCHER_DEBIT_CREDIT']);
                                    $codlist->setCellValue('H'.$currentrow, $detailcod['REFNO']);
                                    $codlist->setCellValue('I'.$currentrow, $detailcod['DOCNO']);
                                    $codlist->setCellValue('J'.$currentrow, $detailcod['A_POLICYNO']);
                                    $codlist->setCellValue('K'.$currentrow, $detailcod['INSURED_NAME']);
                                    $codlist->setCellValue('L'.$currentrow, $detailcod['POSTED_PAYMENT']);
                                    $codlist->setCellValue('M'.$currentrow, $detailcod['ORIGINAL_BASIC_PREMIUM']);
                                    $codlist->setCellValue('N'.$currentrow, $detailcod['PREMIUM']);
                                    $codlist->setCellValue('O'.$currentrow, $detailcod['STAMPDUTY']);
                                    $codlist->setCellValue('P'.$currentrow, $detailcod['LTO']);
                                    $codlist->setCellValue('Q'.$currentrow, $detailcod['LGT']);
                                    $codlist->setCellValue('R'.$currentrow, $detailcod['FST']);
                                    $codlist->setCellValue('S'.$currentrow, $detailcod['PREMIUMTAX']);
                                    $codlist->setCellValue('T'.$currentrow, $detailcod['VAT']); 
                                    $codlist->setCellValue('U'.$currentrow, $detailcod['GROSS_PREMIUM']);
                                    $codlist->setCellValue('V'.$currentrow, $detailcod['OVERRIDING_DISCOUNT']);
                                    $codlist->setCellValue('W'.$currentrow, $detailcod['COMMISSION']);
                                    $codlist->setCellValue('X'.$currentrow, $detailcod['INPUT_VAT']);
                                    $codlist->setCellValue('Y'.$currentrow, $detailcod['TAXRATE']);
                                    $codlist->setCellValue('Z'.$currentrow, $detailcod['TAX_AMOUNT']);
                                    $codlist->setCellValue('AA'.$currentrow, $detailcod['GROSS_COMMISSION']);
                                    $codlist->setCellValue('AB'.$currentrow, $detailcod['NET_DUE']); 
                                    $codlist->setCellValue('AC'.$currentrow, $detailcod['AGING_DAYS']);
                                    $codlist->setCellValue('AD'.$currentrow, $detailcod['AGING_BUCKET']);
                                    
                                    $currentrow++;
                                }
                          
                    }

                    $grandtotal = 0;
                    $grandtotal = $totalPremium + $totaldst + $totalcwt +  $totalcod;
                    $worksheet->setCellValue('D39',$grandtotal);

                    $filename = $source_name.'- Statement of Account as of '.$cutoffdate.'.xlsx';
                    $writer = new Xlsx($workbook);
                    $writer->save($folderpath.'/'.$filename );
                //}
           // }
        }

        
	}
?>