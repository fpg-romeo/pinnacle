<?php
    class Notification{

        public function __construct(){
  
        }

        public static function getRowById($id){
            $result = mysql::select('notification_email', '*', "id = '{$id}'"); 
            return $result;
        }

        public static function getEmail(){
            $result = mysql::select('notification_email nem', 
                                    'nem.*,
                                     (CASE 
                                        WHEN nem.status_id = 1
                                            THEN "Sent"
                                        WHEN nem.status_id = 2
                                            THEN "Failed"
                                        ELSE 
                                            "Queue"
                                        END
                                     ) AS status_name,
                                     (SELECT CONCAT(COALESCE(first_name, "")," ",COALESCE(last_name, "")) FROM account_personal USE INDEX(account_id) WHERE account_id = nem.created_by) AS account_name
                                    ',
                                    "",
                                    'nem.id DESC');
            return $result;
        }

        public static function getEmailById($id){
            $result = mysql::select('notification_email nem',
                                    'nem.*,
                                     (CASE 
                                        WHEN nem.status_id = 1
                                            THEN "Sent"
                                        WHEN nem.status_id = 2
                                            THEN "Failed"
                                        ELSE 
                                            "Queue"
                                        END
                                     ) AS status_name,
                                     (SELECT CONCAT(COALESCE(first_name, "")," ",COALESCE(last_name, "")) FROM account_personal USE INDEX(account_id) WHERE account_id = nem.created_by) AS account_name
                                    ',
                                    "nem.id='{$id}'");
            return $result;
        }

        public static function getEmailByStatus($status_id){
            $result = mysql::select('notification_email nem USE INDEX(status_id)',
                                    'nem.*,
                                     (CASE 
                                        WHEN nem.status_id = 1
                                            THEN "Sent"
                                        WHEN nem.status_id = 2
                                            THEN "Failed"
                                        ELSE 
                                            "Queue"
                                        END
                                     ) AS status_name,
                                     (SELECT CONCAT(COALESCE(first_name, "")," ",COALESCE(last_name, "")) FROM account_personal USE INDEX(account_id) WHERE account_id = nem.created_by) AS account_name
                                    ',
                                    "nem.status_id='{$status_id}'",
                                    'nem.id ASC',
                                     10);
            return $result;
        }

        public static function addEmail($post){
            $fields = mysql::buildFields($post, ", ");
            if(mysql::insert('notification_email', $fields)){
                $result['status']  = 'success';
                $result['message'] = 'New Record Saved';
                $result['id']      = mysql::insertedId();
            }else{
                $result['status']  = 'failed';
                $result['message'] = 'Encounter technical error. Pls try again';
            }

            return $result;
        }

        public static function editEmail($post){
            $id     = $post['id'];
            $record = self::getRowById($id);

            if(is_array($record)){
                $fields = mysql::buildFields($post, ", ");
                if(mysql::update('notification_email', $fields, "id = '{$id}'")){
                    $result['status']  = 'success';
                    $result['message'] = 'Record Successfully Updated';
                }else{
                    $result['status']  = 'failed';
                    $result['message'] = 'Encounter technical error. Pls try again';                
                }
            }else{
                $result['status']  = 'failed';
                $result['message'] = 'No Record Found';
            }

            return $result;        
        }

        public static function getAllEmail($keyword='',$start='', $limit=''){
            if(!empty(trim($keyword))){
                $keyword = " '%{$keyword}%' ";
                $filter  = "
                              
                             (
                                 nem.name LIKE {$keyword}
                                 OR
                                 nem.subject LIKE {$keyword}
                                 OR
                                 nem.recipient_to LIKE {$keyword}
                                 OR
                                 nem.recipient_cc LIKE {$keyword}
                                 OR
                                 nem.recipient_bcc LIKE {$keyword}
                                 OR
                                 mne.name LIKE {$keyword}
                             )
                          ";
            }else{
                $filter = '';
            }
            $startLimit = (trim($start) != "" && trim($limit) != "") ? $start.', '.$limit : ''; 
            $result = mysql::select('notification_email nem 
                                     LEFT JOIN master_notification_email_status mne 
                                     ON nem.status_id = mne.id
                                    ', 
                                    'nem.*,
                                     mne.name AS status_name,
                                     (
                                        CASE 
                                            WHEN nem.created_by = 100
                                                THEN "CRON JOB" 
                                            WHEN nem.created_by = 200
                                                THEN "API ACTION" 
                                            WHEN nem.created_by = 300
                                                THEN "SCRIPT AUTO-RUN" 
                                            ELSE
                                                (SELECT CONCAT(COALESCE(first_name, "")," ",COALESCE(last_name, "")) FROM account_personal USE INDEX(account_id) WHERE account_id = nem.created_by)
                                        END
                                    ) AS account_name
                                    ',
                                    $filter,
                                    'nem.id DESC',$startLimit);
            return $result;
        }

        public static function countAllEmail($keyword=''){ 
            if(!empty(trim($keyword))){
                $keyword = " '%{$keyword}%' ";
                $filter  = "
                              
                             (
                                 nem.name LIKE {$keyword}
                                 OR
                                 nem.subject LIKE {$keyword}
                                 OR
                                 nem.recipient_to LIKE {$keyword}
                                 OR
                                 nem.recipient_cc LIKE {$keyword}
                                 OR
                                 nem.recipient_bcc LIKE {$keyword}
                                 OR
                                 mne.name LIKE {$keyword}
                             )
                          ";
            }else{
                $filter = '';
            }

            $result = mysql::select('notification_email nem 
                                     LEFT JOIN master_notification_email_status mne 
                                     ON nem.status_id = mne.id
                                    ', 
                                    'COUNT(*) as count',
                                    $filter);
            
            if(is_array($result)){
                return recastArray($result)['count'];
            }else{
                return 0;
            } 
        } 

        public static function getEmailBySubject($subject){
            
            $result = mysql::select('notification_email nem',
                                    'nem.recipient_to,
                                    nem.updated_when,
                                    nem.status_id',
                                    "nem.subject LIKE '%{$subject}%'"
                                    ,'',1);
            return $result;
        }

        public static function getEmailBySubjectAndTemplate($subject,$template){
            
            $result = mysql::select('notification_email nem',
                                    'nem.*,
                                    (CASE 
                                        WHEN nem.status_id = 1
                                            THEN "Sent"
                                        WHEN nem.status_id = 2
                                            THEN "Failed"
                                        ELSE 
                                            "Queue"
                                        END
                                     ) AS status_name,
                                     (SELECT CONCAT(COALESCE(first_name, "")," ",COALESCE(last_name, "")) FROM account_personal USE INDEX(account_id) WHERE account_id = nem.created_by) AS account_name
                                    ',
                                    "nem.subject = '{$subject}' AND nem.template = '{$template}'",
                                    'nem.id ASC',
                                    1);
            return $result;
        }
    }
?>