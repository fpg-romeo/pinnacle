<?php
    class CronController{

        public function __construct() {
            //checkLoggedIn('true');
        }

        public function testEmail(){
            includeDefault('email');

            $CONFIGURATION = Configuration::general();

            $subject       = $CONFIGURATION['SYSTEM_ALIAS'].' Mail Server '.$CONFIGURATION['MAIL_HOST'].' Connection Test - Random Id: '.dateTimeAsId();
            $recipient_to  = (!empty(ACCOUNT_EMAIL) ? ACCOUNT_EMAIL : $CONFIGURATION['IT_TEAM_EMAIL']); 
            $recipient_cc  = $CONFIGURATION['IT_TEAM_EMAIL']; 
            $recipient_bcc = $CONFIGURATION['IT_TEAM_EMAIL'];
            $attachment    = '';
            $body          = 'This is a test email to check if the '.strtoupper($CONFIGURATION['SYSTEM_ALIAS']).' Application and '.$CONFIGURATION['MAIL_HOST'].' mail server service connection is working properly';
            $reply_to      = '';
            $email         = Email::sendEmail($recipient_to, $subject, Email::testEmail($body), $recipient_cc, $recipient_bcc, $attachment, $reply_to);

            pre($email);
        }

        public function notificationEmail(){
          
            // STATUS: master_notification_email_status (database)
            // 1 - Unprocessed
            // 2 - Queue
            // 3 - Sent
            // 4 - Failed
            // 5 - Error

            //ENGINE : CONFIGURATION
            // 100 - CRON JOB
            // 200 - API ACTION
            // 300 - SCRIPT AUTO-RUN
           
            includeModel(['Notification']);
            includeDefault('email');

            $CONFIGURATION     = Configuration::general();
            $result['success'] = array();
            $result['failed']  = array();
            $queued_list       = Notification::getEmailByStatus(1);

            // pre($queued_list);
            // die();

            if(is_array($queued_list) && !empty($queued_list)){
                // REMOVE IF THIS CAUSES AN ISSUE
               // AND UPDATE ALL NOTIFICATION ROWS THAT HAS 2 STATUS_ID TO 1 
                foreach($queued_list as $key => $value){
                    $value['attempt'] ++ ;
                    if($value['attempt'] <= $CONFIGURATION['MAIL_MAX_ATTEMPT']){
                        $queued_field['id']           = $value['id'];
                        $queued_field['status_id']    = 2;
                        $queued_field['response']     = 'Queue';
                        $queued_field['updated_when'] = dateTimeStamp();
                        
                        Notification::editEmail($queued_field);
                    }else{
                        continue;
                    }
                }

               // REMOVE IF THIS CAUSES AN ISSUE
               // AND UPDATE ALL NOTIFICATION ROWS THAT HAS 2 STATUS_ID TO 1 
                foreach($queued_list as $key => $value){    
                    $value['attempt'] ++ ;
                    if($value['attempt'] <= $CONFIGURATION['MAIL_MAX_ATTEMPT']){
                        $subject                    = htmlDecode($value['subject']);
                        $recipient_to               = explode(',', $value['recipient_to']);
                        $recipient_cc               = explode(',', $value['recipient_cc']);
                        $recipient_bcc              = explode(',', $value['recipient_bcc']);
                        $attachment                 = (!empty($value['attachment']) ? unserialize(safe_b64decode($value['attachment'])) : '');
                        $body                       = unserialize(safe_b64decode($value['body']));
                        $template                   = $value['template'];
                        $reply_to_value             = (!empty($value['reply_to']) ? explode(',', $value['reply_to']) : '');
                        $reply_to                   = $CONFIGURATION['MAIL_REPLYTO'];
                        $email                      = Email::sendEmail($recipient_to, $subject, Email::$template($body), $recipient_cc, $recipient_bcc, $attachment, $reply_to);
                        
                        if($email['status'] == 'success'){
                            $result['success'][]    = $recipient_to;
                            $field['status_id']     = 3;
                        }else{
                            $result['failed'][]     = $recipient_to;
                            $field['status_id']     = ($value['attempt'] == $CONFIGURATION['MAIL_MAX_ATTEMPT'] ? 5 : 1); //ERROR SMTP
                        }
                        $field['attempt']           = $value['attempt'];
                        $field['id']                = $value['id'];
                        $field['response']          = $email['message'];
                        $field['updated_when']      = dateTimeStamp();
                        
                        Notification::editEmail($field);
                        $field = [];

                        sleep(3);
                    }else{
                        continue;
                    }
                        
                }

            }

            if(isset($result['success']) && !empty($result['success'])){
                echo 'Email sent to:';
                echo json_encode($result['success']);
            }
            
            if(isset($result['failed']) && !empty($result['failed'])){
                echo '<br>';
                echo 'Failed sent to:';
                echo json_encode($result['failed']);
            }

            exit();
        }

        // public function soaCollectionReminderLetterGeneration(){
        //     Shortcode::soaCollectionReminderLetterGeneration(1);
        // }

        //RUN THIS EVERY 7AM DAILY
        public function soaCollectionReminder(){
            #get the list from monthly raw na blank pa ung mga pdf and excel, then make sure ung attemps is not more than 3 times
            #1unang checking kung nasend na ung email via status
            #sencod checking ung attempt ng sending not exceed to 3 times
            #third kung may mga files na ung pdf and excel
            #before ilagay sa for sending

            #then magstore n sa notification email table ng record n iprocess for sending

            includeModel(['Soa', 'Notification']);
            $CONFIGURATION  = Configuration::general();

            $date_start     = '2025-08-01';
            $date_end       = '2025-08-31';
            $data           = Soa::getCollectionReminder($date_start, $date_end);
            if(!empty($data)){
                foreach($data as $key => $value){

                    $recipient_to                       = $CONFIGURATION['IT_TEAM_EMAIL'];
                    $recipient_cc                       = $CONFIGURATION['IT_TEAM_EMAIL'];
                    $recipient_bcc                      = $CONFIGURATION['IT_TEAM_EMAIL'];
                    $reply_to                           = $CONFIGURATION['IT_TEAM_EMAIL'];

                    $field['name']                      = $CONFIGURATION['NOTIFICATION_NAME_SOA_COLLECTION_REMINDER']; 
                    $field['subject']                   = $CONFIGURATION['NOTIFICATION_SUBJECT_SOA_COLLECTION_REMINDER']; 
                    $field['recipient_to']              = is_array($recipient_to) ? implode(',', $recipient_to) : $recipient_to;  
                    $field['recipient_cc']              = is_array($recipient_cc) ? implode(',', $recipient_cc) : $recipient_cc;
                    $field['recipient_bcc']             = is_array($recipient_bcc) ? implode(',', $recipient_bcc) : $recipient_bcc;
                    $field['reply_to']                  = is_array($reply_to) ? implode(',', $reply_to) : $reply_to;
                    $field['template']                  = 'soa'; 
                    $field['status_id']                 = $CONFIGURATION['NOTIFICATION_STATUS_UNPROCESSED']; 
                    $field['attempt']                   = 0; //DEFAULT  
                    $field['batch_number']              = $value['batch_number'];  
                    $field['soa_monthly_raw_data_id']   = $value['id'];  
                    $field['created_by']                = (defined(ACCOUNT_ID) && !empty(ACCOUNT_ID) ? ACCOUNT_ID : $CONFIGURATION['ENGINE_CRON_JOB']);  
                    $field['created_when']              = dateTimeStamp();   
                    
                    $attachment[$key]['file_pdf']       = getDocumentRoot().'/upload/soa/'.htmlDecode($value['batch_number']).'-'.htmlDecode($value['id']).'/'.htmlDecode($value['file_pdf']);
                    $attachment[$key]['file_excel']     = getDocumentRoot().'/upload/soa/'.htmlDecode($value['batch_number']).'-'.htmlDecode($value['id']).'/'.htmlDecode($value['file_excel']);
                    
                    $soa_default_file_counter = 1;
                    foreach($CONFIGURATION['SOA_EMAIL_ATTACHMENT_DEFAULT'] as $soa_default_file){
                        $attachment[$key]['file_default_'.$soa_default_file_counter] = getDocumentRoot().'/upload/soa/default/'.$soa_default_file;
                        $soa_default_file_counter++;
                    }
                    $field['attachment']                = safe_b64encode(serialize($attachment)); 
                    $field['body']                      = safe_b64encode(serialize($value)); 

                    Notification::addEmail($field);
                }
            }
        }


        //RUN THIS EVERY 5AM DAILY
        public function soaCollectionReminderGenerationPdf(){
            includeModel(['Soa', 'Notification']);
            $CONFIGURATION  = Configuration::general();

            $date_start     = '2025-08-01';
            $date_end       = '2025-08-31';
            $data           = Soa::getCollectionReminder($date_start, $date_end);
            if(!empty($data)){
                foreach($data as $key => $value){

                    $recipient_to                       = $CONFIGURATION['IT_TEAM_EMAIL'];
                    $recipient_cc                       = $CONFIGURATION['IT_TEAM_EMAIL'];
                    $recipient_bcc                      = $CONFIGURATION['IT_TEAM_EMAIL'];
                    $reply_to                           = $CONFIGURATION['IT_TEAM_EMAIL'];

                    $field['name']                      = $CONFIGURATION['NOTIFICATION_NAME_SOA_COLLECTION_REMINDER']; 
                    $field['subject']                   = $CONFIGURATION['NOTIFICATION_SUBJECT_SOA_COLLECTION_REMINDER']; 
                    $field['recipient_to']              = is_array($recipient_to) ? implode(',', $recipient_to) : $recipient_to;  
                    $field['recipient_cc']              = is_array($recipient_cc) ? implode(',', $recipient_cc) : $recipient_cc;
                    $field['recipient_bcc']             = is_array($recipient_bcc) ? implode(',', $recipient_bcc) : $recipient_bcc;
                    $field['reply_to']                  = is_array($reply_to) ? implode(',', $reply_to) : $reply_to;
                    $field['template']                  = 'soa'; 
                    $field['status_id']                 = $CONFIGURATION['NOTIFICATION_STATUS_UNPROCESSED']; 
                    $field['attempt']                   = 0; //DEFAULT  
                    $field['batch_number']              = $value['batch_number'];  
                    $field['soa_monthly_raw_data_id']   = $value['id'];  
                    $field['created_by']                = (defined(ACCOUNT_ID) && !empty(ACCOUNT_ID) ? ACCOUNT_ID : $CONFIGURATION['ENGINE_CRON_JOB']);  
                    $field['created_when']              = dateTimeStamp();   
                    
                    $attachment[$key]['file_pdf']       = getDocumentRoot().'/upload/soa/'.htmlDecode($value['batch_number']).'-'.htmlDecode($value['id']).'/'.htmlDecode($value['file_pdf']);
                    $attachment[$key]['file_excel']     = getDocumentRoot().'/upload/soa/'.htmlDecode($value['batch_number']).'-'.htmlDecode($value['id']).'/'.htmlDecode($value['file_excel']);
                    
                    $soa_default_file_counter = 1;
                    foreach($CONFIGURATION['SOA_EMAIL_ATTACHMENT_DEFAULT'] as $soa_default_file){
                        $attachment[$key]['file_default_'.$soa_default_file_counter] = getDocumentRoot().'/upload/soa/default/'.$soa_default_file;
                        $soa_default_file_counter++;
                    }
                    $field['attachment']                = safe_b64encode(serialize($attachment)); 
                    $field['body']                      = safe_b64encode(serialize($value)); 

                    Notification::addEmail($field);
                }
            }
        public function syncSOA_json()
        {
      
        $cutoffmonth = date('m', strtotime('first day of last month'));
        $processingmonth = date('m');
        $cutoffdate  = strtoupper(date('Y-m-d', strtotime('last day of previous month'))); 
        $data = Master::syncSOA();

        $datacount = count($data) > 0 ? $data : [];


            

                    if (!empty($data)) {

                            // get the cutoff date, if processing date is within the current month , replenish the previous month's data 
                            
                            if(date('m') == $processingmonth){

                                $deleteDB = Master::deleteSoa($cutoffmonth);
                                
                                if($deleteDB['status'] != 'success'){

                                    $errmessage = array();

                                        $errmessage['status'] = "Insertion of new cutoff date: " . $cutoffmonth;
                                        $errmessage['job_type'] = 'syncSOA';
                                        $errmessage['created_at'] = date('Y-m-d H:i:s');
                                        $errmessage['payload'] = '';
                                        Master::addJobQueue($errmessage);
                                        $batchnumber = ''; // Reset batch number on error
                                    
                                } else {
                                    $errmessage = array();

                                        $errmessage['status'] = "replenish of cutoff date: " . $cutoffmonth;
                                        $errmessage['job_type'] = 'syncSOA';
                                        $errmessage['created_at'] = date('Y-m-d H:i:s');
                                        $errmessage['payload'] = '';
                                        Master::addJobQueue($errmessage);
                                        $batchnumber = ''; // Reset batch number on error
                                    
                                }

                            }


                        foreach ($data as $record) {
                            $cleanRecord = [];

                            foreach ($record as $key => $value) {
                            
                                $updatedKey = strtoupper($key);
                                $updatedKey = str_replace([' ', '(', ')', '/', '>', '-'], ['_', '', '', '_', 'OVER_', '_'], $updatedKey);
                                $updatedKey = preg_replace('/_{2,}/', '_', $updatedKey);
                                $updatedKey = trim($updatedKey, '_');

                                // Format DateTime values
                                if ($value instanceof DateTime) {
                                    $value = $value->format('Y-m-d');
                                }

                            
                            $cleanRecord[$updatedKey] = $value;
                            }
                            //$cleanRecord['batch_number'] = date('YmdHis');
                            $cleanRecord['as_of_date']   = strtoupper(date('Y-m-d', strtotime('last day of previous month')));

                            //perform the zero effect already. those policies with zero gross premium in total will not be included in the insert
                            $zeroeffect = Master::checkZeroEffect($cleanRecord['A_POLICYNO']);
                            if ($zeroeffect) {
                                continue; // Skip this record and move to the next one
                            }
                            

                    
                                $insertDB = Master::addSoa($cleanRecord);
                                if ($insertDB['status'] != 'success') {
                                    
                                    $errmessage = array();

                                    $errmessage['status'] = "Error inserting record: " . json_encode($insertDB);
                                    $errmessage['job_type'] = 'syncSOA';
                                    $errmessage['created_at'] = date('Y-m-d H:i:s');
                                    $errmessage['payload'] = json_encode($cleanRecord);

                                    $result = Master::addJobQueue($errmessage);
                                    $batchnumber = ''; // Reset batch number on error
                                    break;
                                } 
                        }

                                $successmessage = array();

                                $successmessage['status'] = "Successfully insert Care " . count($datacount) . " records.";
                                $successmessage['job_type'] = 'syncSOA';
                                $successmessage['created_at'] = date('Y-m-d H:i:s');
                                $successmessage['payload'] = '';

                                $result = Master::addJobQueue($successmessage);

                                //after getting the raw data, scan the fetched records then do the distribution
                                $record = Master::checkDistribution($cutoffdate);
                                

                    } else {
                                $errmessage = array();

                                $errmessage['status'] = "No data found";
                                $errmessage['job_type'] = 'syncSOA';
                                $errmessage['created_at'] = date('Y-m-d H:i:s');
                                $errmessage['payload'] = json_encode($cleanRecord) ? json_encode($cleanRecord) : '';

                                $result = Master::addJobQueue($errmessage);
                    }

        }
    }
?>