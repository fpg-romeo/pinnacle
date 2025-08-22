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

        public function soaCollectionReminderLetterGeneration(){
            Shortcode::soaCollectionReminderLetterGeneration(1, 'download');
        }
    }
?>