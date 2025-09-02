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

    public static function getSoa(){
        set_time_limit(0);
        $result = SQLServer::select('soa_v3', '*','','','500');

        return $result; 
    }

    public static function addSoa($fields){
        set_time_limit(0);
        $insert = mysql::buildfields($fields, ", ");
        if(mysql::insert('soa_monthly_raw_data', $insert)){
            $result['status']  = 'success';
            $result['message'] = 'New Record Saved';
            $result['id']      = mysql::insertedId();
        }else{
            $result['status']  = 'failed';
            $result['message'] = 'Encounter technical error. Pls try again';
            $result['A_POLICYNO']      =  $insert['A_POLICYNO'];
        }

        return $result;
    }

    public static function deleteSoa($asofdate){
        set_time_limit(0);
        $delete = MySql::delete('soa_monthly_raw_data',"date_format(as_of_date, '%m') = {$asofdate}");
        if($delete){
            $result['status']  = 'success';
            $result['message'] = 'Records deleted'; 
        } 

        return $result;
    }

    public static function addJobQueue($fields){
        $insert = mysql::buildfields($fields, ", ");
        if (mysql::insert('soa_job', $insert)) {
            $result['status']  = 'success';
            $result['message'] = 'New Record Saved';
            $result['id']      = mysql::insertedId();
        }

        return $result;
    }
    
    public static function checkZeroEffect($policyno){
        $record = array();
        $record = SqlServer::query("SELECT A_POLICYNO,sum([GROSS PREMIUM]) AS GROSSPREMIUM FROM soa_v3 WHERE A_POLICYNO = '{$policyno}' GROUP BY A_POLICYNO", 'select');

        if($record[0]['GROSSPREMIUM'] == 0){
            return true;
        }
        return false;
    }

    public static function checkDistribution($asofdate){
        
        $result         = array();
        $batch_number   = "";
        $headers        = array(
                            'BOOKING_DATE', 'INCEPTION_DATE', 'EXPIRY_DATE', 'EFFECTIVE_DATE', 'VOUCHER_DEBIT_CREDIT_PREMIUM',
                            'VOUCHER_DEBIT_CREDIT_COMMISSION', 'OVERIDING_VOUCHER_DEBIT_CREDIT', 'REFNO', 'DOCNO', 'INSURED_NAME',
                            'POSTED_PAYMENT', 'ORIGINAL_BASIC_PREMIUM', 'PREMIUM', 'STAMPDUTY', 'LTO', 'LGT', 'FST', 'PREMIUMTAX', 'VAT', 'GROSS_PREMIUM',
                            'OVERRIDING_DISCOUNT', 'COMMISSION', 'INPUT_VAT', 'TAXRATE', 'TAX_AMOUNT', 'GROSS_COMMISSION', 'NET_DUE', 'AGING_DAYS', 'AGING_BUCKET'
                        );

        //get records in soa_monthly_raw_data table
        $query = mysql::query("SELECT A_POLICYNO,as_of_date,max(topro) as topro,sum(GROSS_PREMIUM) as GROSSPREMIUM,sum(ORIGINAL_BASIC_PREMIUM) as ORIGPREM,sum(stampduty) as DST FROM pinaccle.soa_monthly_raw_data where as_of_date = '{$asofdate}' group by A_POLICYNO,as_of_date");

        foreach($query as $key => $value){
            $searchRecord = mysql::query("SELECT 
                            BOOKING_DATE, 
                            INCEPTION_DATE, 
                            EXPIRY_DATE, 
                            EFFECTIVE_DATE, 
                            VOUCHER_DEBIT_CREDIT_PREMIUM,
                            VOUCHER_DEBIT_CREDIT_COMMISSION, 
                            OVERIDING_VOUCHER_DEBIT_CREDIT, 
                            REFNO, 
                            DOCNO, 
                            INSURED_NAME,
                            POSTED_PAYMENT, 
                            ORIGINAL_BASIC_PREMIUM, 
                            PREMIUM, 
                            STAMPDUTY, 
                            LTO, 
                            LGT, 
                            FST, 
                            PREMIUMTAX, 
                            VAT, 
                            GROSS_PREMIUM,
                            OVERRIDING_DISCOUNT, 
                            COMMISSION, 
                            INPUT_VAT, 
                            TAXRATE, 
                            TAX_AMOUNT, 
                            GROSS_COMMISSION, 
                            NET_DUE, 
                            AGING_DAYS, 
                            AGING_BUCKET,batch_number
                            FROM soa_monthly_raw_data where A_POLICYNO = '{$value['A_POLICYNO']}' AND as_of_date = '{$value['as_of_date']}'");

            if($value['ORIGPREM'] == 0 && ($value['GROSSPREMIUM'] == $value['DST'])){
                self::editDistribution('dst',$value['A_POLICYNO'],$value['as_of_date']);
                continue;
            }
            
            if($value['ORIGPREM'] > 0){
                if(round(($value['GROSSPREMIUM'] / $value['ORIGPREM']) * 100 === 2.0)){
                    self::editDistribution('cwt',$value['A_POLICYNO'],$value['as_of_date']);
                    continue;  
                }else{
                    $CODlist = array('CTPL-0201','CTPL-0202','CTPL-0203');   

                    if(in_array($value['topro'],$CODlist)){                    
                        self::editDistribution('cob',$value['A_POLICYNO'],$value['as_of_date']);
                        continue;                       
                    }else {
                        self::editDistribution('detailed',$value['A_POLICYNO'],$value['as_of_date']);
                        continue;
                    }
                }
            }                        
        }
            
        return $result;
    }

    public static function getDST($policyno='',$as_of_date,$fields='',$source_name=''){
        // For sheets
        $result = array();

        if($source_name != '' and $fields = ''){
            $fields = 'BOOKING_DATE, 
                            INCEPTION_DATE, 
                            EXPIRY_DATE, 
                            EFFECTIVE_DATE, 
                            VOUCHER_DEBIT_CREDIT_PREMIUM,
                            VOUCHER_DEBIT_CREDIT_COMMISSION, 
                            OVERIDING_VOUCHER_DEBIT_CREDIT, 
                            REFNO, 
                            DOCNO, 
                            INSURED_NAME,
                            POSTED_PAYMENT, 
                            ORIGINAL_BASIC_PREMIUM, 
                            PREMIUM, 
                            STAMPDUTY, 
                            LTO, 
                            LGT, 
                            FST, 
                            PREMIUMTAX, 
                            VAT, 
                            GROSS_PREMIUM,
                            OVERRIDING_DISCOUNT, 
                            COMMISSION, 
                            INPUT_VAT, 
                            TAXRATE, 
                            TAX_AMOUNT, 
                            GROSS_COMMISSION, 
                            NET_DUE, 
                            AGING_DAYS, 
                            AGING_BUCKET';
        }else  {
            $fields = '*';
        }

        $result = mysql::select('soa_monthly_raw_data', $fields, "as_of_date = '{$as_of_date}' and source_name = '{$source_name}' and is_dst = 1");
        return $result;
    }

    public static function getCWT($policyno='',$as_of_date,$fields='',$source_name=''){
            // For sheets
    
        $result = array();

        if($source_name != '' and $fields = ''){
        $fields = 'BOOKING_DATE, 
                        INCEPTION_DATE, 
                        EXPIRY_DATE, 
                        EFFECTIVE_DATE, 
                        VOUCHER_DEBIT_CREDIT_PREMIUM,
                        VOUCHER_DEBIT_CREDIT_COMMISSION, 
                        OVERIDING_VOUCHER_DEBIT_CREDIT, 
                        REFNO, 
                        DOCNO, 
                        INSURED_NAME,
                        POSTED_PAYMENT, 
                        ORIGINAL_BASIC_PREMIUM, 
                        PREMIUM, 
                        STAMPDUTY, 
                        LTO, 
                        LGT, 
                        FST, 
                        PREMIUMTAX, 
                        VAT, 
                        GROSS_PREMIUM,
                        OVERRIDING_DISCOUNT, 
                        COMMISSION, 
                        INPUT_VAT, 
                        TAXRATE, 
                        TAX_AMOUNT, 
                        GROSS_COMMISSION, 
                        NET_DUE, 
                        AGING_DAYS, 
                        AGING_BUCKET';
        }else{
            $fields = '*';
        }

        $result = mysql::select('soa_monthly_raw_data', $fields, "as_of_date = '{$as_of_date}' and A_policyno = '{$policyno}' and is_cwt = 1");
        return $result;
    }

    public static function getDetailed($policyno='',$as_of_date,$fields='',$source_name=''){
        $result = array();
        // For sheets
        if($source_name != '' and $fields = ''){
            $fields = 'BOOKING_DATE, 
                            INCEPTION_DATE, 
                            EXPIRY_DATE, 
                            EFFECTIVE_DATE, 
                            VOUCHER_DEBIT_CREDIT_PREMIUM,
                            VOUCHER_DEBIT_CREDIT_COMMISSION, 
                            OVERIDING_VOUCHER_DEBIT_CREDIT, 
                            REFNO, 
                            DOCNO, 
                            INSURED_NAME,
                            POSTED_PAYMENT, 
                            ORIGINAL_BASIC_PREMIUM, 
                            PREMIUM, 
                            STAMPDUTY, 
                            LTO, 
                            LGT, 
                            FST, 
                            PREMIUMTAX, 
                            VAT, 
                            GROSS_PREMIUM,
                            OVERRIDING_DISCOUNT, 
                            COMMISSION, 
                            INPUT_VAT, 
                            TAXRATE, 
                            TAX_AMOUNT, 
                            GROSS_COMMISSION, 
                            NET_DUE, 
                            AGING_DAYS, 
                            AGING_BUCKET';
        }else{    
            $fields = '*';
        }

        $result = mysql::select('soa_monthly_raw_data', $fields, "as_of_date = '{$as_of_date}' and source_name = '{$source_name}' and is_detailed = 1");
        return $result;
    }


    public static function getCOD($policyno='',$as_of_date,$fields='',$source_name=''){
        $result = array();
        // For sheets
        if($source_name != '' and $fields = ''){
            $fields = 'BOOKING_DATE, 
                            INCEPTION_DATE, 
                            EXPIRY_DATE, 
                            EFFECTIVE_DATE, 
                            VOUCHER_DEBIT_CREDIT_PREMIUM,
                            VOUCHER_DEBIT_CREDIT_COMMISSION, 
                            OVERIDING_VOUCHER_DEBIT_CREDIT, 
                            REFNO, 
                            DOCNO, 
                            INSURED_NAME,
                            POSTED_PAYMENT, 
                            ORIGINAL_BASIC_PREMIUM, 
                            PREMIUM, 
                            STAMPDUTY, 
                            LTO, 
                            LGT, 
                            FST, 
                            PREMIUMTAX, 
                            VAT, 
                            GROSS_PREMIUM,
                            OVERRIDING_DISCOUNT, 
                            COMMISSION, 
                            INPUT_VAT, 
                            TAXRATE, 
                            TAX_AMOUNT, 
                            GROSS_COMMISSION, 
                            NET_DUE, 
                            AGING_DAYS, 
                            AGING_BUCKET';
        }else{
            $fields = '*';
        }
    
        $result = mysql::select('soa_monthly_raw_data', $fields, "as_of_date = '{$as_of_date}' and source_name = '{$source_name}' and is_cod = 1");

        return $result;
    }

    public static function editDistribution($distribution,$policyno,$asofdate){
        if($distribution == 'detailed'){
            $updatefield = array(
                                'is_detailed' => '1',
                                'updated_when' => date('Y-m-d H:i:s'));
        }elseif($distribution == 'dst'){
            $updatefield = array(
                                'is_dst' => '1',
                                'updated_when' => date('Y-m-d H:i:s'));
        }elseif($distribution == 'cwt'){
                $updatefield = array(
                                'is_cwt' => '1',
                                'updated_when' => date('Y-m-d H:i:s'));
        }elseif($distribution === 'cob'){
            $updatefield = array(
                                'is_cod' => '1',
                                'updated_when' => date('Y-m-d H:i:s'));
        }else{
            $updatefield = [];
            exit;
        };

        $updateDB = mysql::buildFields($updatefield, ", ");

        if(mysql::update('soa_monthly_raw_data', $updateDB, "A_POLICYNO = '{$policyno}' AND as_of_date = '{$asofdate}'")){
            $result['status']  = 'success';
            $result['message'] = 'New Record Saved';
            $result['id']      = mysql::insertedId();
        }
    }

    public static function getOutstandingOverdue($policyno='',$as_of_date='',$fields='',$source_name=''){
        $result = array();
        // For sheets
        if($source_name != '' and $fields = ''){
            $fields = 'BOOKING_DATE, 
                            INCEPTION_DATE, 
                            EXPIRY_DATE, 
                            EFFECTIVE_DATE, 
                            VOUCHER_DEBIT_CREDIT_PREMIUM,
                            VOUCHER_DEBIT_CREDIT_COMMISSION, 
                            OVERIDING_VOUCHER_DEBIT_CREDIT, 
                            REFNO, 
                            DOCNO, 
                            INSURED_NAME,
                            POSTED_PAYMENT, 
                            ORIGINAL_BASIC_PREMIUM, 
                            PREMIUM, 
                            STAMPDUTY, 
                            LTO, 
                            LGT, 
                            FST, 
                            PREMIUMTAX, 
                            VAT, 
                            GROSS_PREMIUM,
                            OVERRIDING_DISCOUNT, 
                            COMMISSION, 
                            INPUT_VAT, 
                            TAXRATE, 
                            TAX_AMOUNT, 
                            GROSS_COMMISSION, 
                            NET_DUE, 
                            AGING_DAYS, 
                            AGING_BUCKET';
        }else{    
            $fields = '*';
        }

        $result = mysql::select('soa_monthly_raw_data', $fields, "as_of_date = '{$as_of_date}' and source_name = '{$source_name}' and AGING_DAYS > 90 AND is_detailed = 1");
        return $result;
    }
}