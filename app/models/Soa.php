<?php
class Soa{

    public function __construct() {

    }

    public static function getCollectionReminder($date_start, $date_end, $limit=''){

        $filter_limit = '';
        if(!empty($limit)){
            $filter_limit = $limit;
        }
        
        $result = mysql::select('soa_monthly_raw_data AS smr USE INDEX(created_when)
                                ',
                                'smr.* 
                                ',
                                "smr.email_status_id NOT IN (3, 4, 5) AND
                                 smr.email_send_attempt < 3 AND
                                 smr.file_pdf != '' AND
                                 smr.file_pdf IS NOT NULL AND
                                 smr.file_pdf_timestamp IS NOT NULL AND
                                 smr.file_excel != '' AND 
                                 smr.file_excel IS NOT NULL AND
                                 smr.file_excel_timestamp IS NOT NULL AND
                                 smr.created_when >= '".$date_start."' AND smr.created_when <= '".$date_end."'
                                ",
                                "smr.id ASC",
                                $filter_limit);
        return $result;
    }

    public static function getCollectionReminderFilePdf($date_start, $date_end, $limit=''){

        $filter_limit = '';
        if(!empty($limit)){
            $filter_limit = $limit;
        }

        $result = mysql::select('soa_monthly_raw_data AS smr USE INDEX(created_when)
                                ',
                                'smr.* 
                                ',
                                "smr.email_status_id NOT IN (3, 4, 5) AND
                                 smr.file_pdf_attempt < 3 AND
                                 (smr.file_pdf = '' OR smr.file_pdf IS NULL) AND
                                 smr.created_when >= '".$date_start."' AND smr.created_when <= '".$date_end."'
                                ",
                                "smr.id ASC",
                                $filter_limit);
        return $result;
    }

    public static function getCollectionReminderFileExcel($date_start, $date_end, $limit=''){

        $filter_limit = '';
        if(!empty($limit)){
            $filter_limit = $limit;
        }

        $result = mysql::select('soa_monthly_raw_data AS smr USE INDEX(created_when)
                                ',
                                'smr.* 
                                ',
                                "smr.email_status_id NOT IN (3, 4, 5) AND
                                 smr.file_excel_attempt < 3 AND
                                 (smr.file_excel = '' OR smr.file_excel IS NULL) AND
                                 smr.created_when >= '".$date_start."' AND smr.created_when <= '".$date_end."'
                                ",
                                "smr.id ASC",
                                $filter_limit);
        return $result;
    }

    public static function getRecordById($id){
        $result = mysql::select('soa_monthly_raw_data',
                                '*',
                                "id = '{$id}'"
        );

        return $result;
    }

    public static function getRecordByBatchNumber($batch_number){
        $result = mysql::select('soa_monthly_raw_data',
                                '*',
                                "batch_number = '{$batch_number}'"
        );

        return $result;
    }

    public static function addRecord($post){
        $record = self::getRecordById($post['id']); //CHANGE BASED ON A UNIQUE IDENTIFIER

        if(!is_array($record)){  
            $fields = mysql::buildFields($post, ", ");
            if(mysql::insert("soa_monthly_raw_data", $fields)){
                $result['status']  = 'success';
                $result['message'] = 'New Record Saved';
                $result['id']      = mysql::insertedId();
            }else{
                $result['status']  = 'failed';
                $result['message'] = 'Encounter technical error. Pls try again';
            }
        }else{
            $result['status']  = 'failed';
            $result['message'] = 'Record already exist';
        }
        return $result;
    }

    public static function editRecord($post){
        $id     = $post['id'];
        $record = self::getRecordById($id);

        if(is_array($record)){   
            $fields = mysql::buildFields($post, ", ");
            if(mysql::update("soa_monthly_raw_data", $fields, "id = '{$id}'")){
                $result['status']  = 'success';
                $result['message'] = 'Record Successfully Updated';
                $result['id']      = $id;
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

    public static function deleteRecord($id){
        $record = self::getRecordById($id);

        if(is_array($record)){
            if(mysql::delete("soa_monthly_raw_data", "id = '{$id}'")){
                $result['status']   = 'success';
                $result['message']  = 'Record Successfully Deleted';
                $result['record']   = recastArray($record);
            }else{
                $result['status']   = 'failed';
                $result['message']  = 'Encounter technical error. Pls try again';
            }
        }else{
            $result['status']  = 'failed';
            $result['message'] = 'No Record Found';
        }

        return $result;
    }  

    public static function manageRecord($post){
        $record = self::getRecordById($post['id']);

        if(is_array($record) && !empty($post['id'])){  
            $post['updated_by']     = ACCOUNT_ID;
            $post['updated_when']   = dateTimeStamp();
            $result                 = self::editRecord($post);
        }else{
            $post['created_by']     = ACCOUNT_ID;
            $post['created_when']   = dateTimeStamp();
            $result                 = self::addRecord($post);
        }

        return $result;
    }
}