<?php
    class NotificationController{

        public function __construct() {

        }

        public function email(){
            $data = array();

            $keyword                    = urldecode(getVar('keyword'));
            $data['email']              = Notification::getAllEmail($keyword, pagination('start'), pagination('limit'));
            $data['total_record']       = Notification::countAllEmail($keyword);
            $data['total_page']         = pagination('total', $data['total_record']); 

            if(is_array($data['email']) && !empty($data['email'])){
                foreach($data['email'] as $key => $value){
                    if(!empty($data['email'][$key]['recipient_to'])){
                        $data['email'][$key]['recipient_to'] = Shortcode::concatView($data['email'][$key]['recipient_to']);
                    }
                    if(!empty($data['email'][$key]['recipient_cc'])){
                        $data['email'][$key]['recipient_cc'] = Shortcode::concatView($data['email'][$key]['recipient_cc']);
                    }
                    if(!empty($data['email'][$key]['recipient_bcc'])){
                        $data['email'][$key]['recipient_bcc'] = Shortcode::concatView($data['email'][$key]['recipient_bcc']);
                    }
                }
            }

            views('notification.email', $data);  
        }

        public function emailJson(){
            if(isset($_POST) && !empty($_POST)){
                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'view'){
                   
                    $record = Notification::getEmailById(idDecrypt(postvar('id')));
                    if(is_array($record)){    
                        $result                 = recastArray($record);
                        $result['created_when'] = dateReformat(htmlDecode($result['created_when']), 'd-M-Y').' | '.dateReformat(htmlDecode($result['created_when']), 'h:i A');
                        $result['updated_when'] = dateReformat(htmlDecode($result['updated_when']), 'd-M-Y').' | '.dateReformat(htmlDecode($result['updated_when']), 'h:i A');

                        if(!empty($result['recipient_to'])){
                            $result['recipient_to'] = Shortcode::concatView($result['recipient_to'], ', ');
                        }
                        if(!empty($result['recipient_cc'])){
                            $result['recipient_cc'] = Shortcode::concatView($result['recipient_cc'], ', ');
                        }
                        if(!empty($result['recipient_bcc'])){
                            $result['recipient_bcc'] = Shortcode::concatView($result['recipient_bcc'], ', ');
                        }
                    }
                }else{
                    $data = checkRequiredPost(array('id'));
                    if(!array_key_exists('error', $data)){  
                        $field['id']           = postVar('id');
                        $field['status_id']    = postVar('status_id', '0');
                        $field['response']     = '';
                        if($field['status_id'] == 0 ){
                            $field['attempt']  = 0; 
                        }
                        $field['updated_by']   = ACCOUNT_ID;
                        $field['updated_when'] = dateTimeStamp();

                        $result = Notification::editEmail($field);
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
