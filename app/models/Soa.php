<?php
class Soa
{

    public function __construct() {

    }

    public static function getCollectionReminder($date_start, $date_end)
    {
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
                                "smr.id ASC");
        return $result;
    }

    public static function getPolicyByPolicyId($policy_id)
    {
        $result = mysql::select('gcash_policy gpo',
                                'gpo.*',
                                "gpo.policy_id = '{$policy_id}'"
        );
        return $result;
    }

    public static function getPolicyByName($name)
    {
        $result = mysql::select('gcash_policy gpo',
                                'gpo.*',
                                "CONCAT(gpo.first_name, ' ', gpo.last_name, ' ', gpo.middle_name) = '{$name}'"
        );
        return $result;
    }

    public static function getPolicy($keyword = '', $start = '', $limit = '', $query_type = 'main')
    {

        if (!empty(trim($keyword))) {

            $keyword = " '%{$keyword}%' ";
            $filter_keyword  = " AND 
                            (
                                gpo.first_name LIKE {$keyword}
                                OR
                                gpo.middle_name LIKE {$keyword}
                                OR
                                gpo.last_name LIKE {$keyword}
                                OR
                                gpo.date_of_birth LIKE {$keyword}
                                OR
                                gpo.mobile_number LIKE {$keyword}
                                OR
                                gpo.email_address LIKE {$keyword}
                                OR
                                gpo.date_of_transaction LIKE {$keyword}
                                OR
                                gpo.reference_number LIKE {$keyword}
                                OR
                                gpo.load_amount LIKE {$keyword}
                                OR
                                gpo.load_status LIKE {$keyword}
                                OR
                                gpo.consent_status LIKE {$keyword}
                                OR
                                gpo.policy_id LIKE {$keyword}
                                OR
                                gpo.policy_status LIKE {$keyword}
                                OR
                                gpo.protect_premium_taxes LIKE {$keyword}
                                OR
                                gpo.date_insurance_start LIKE {$keyword}
                                OR
                                gpo.date_insurance_end LIKE {$keyword}
                                OR
                                gpo.batch_number LIKE {$keyword}
                                OR
                                gbd.endorsement_number LIKE {$keyword}
                                OR
                                gbd.workflow_number LIKE {$keyword}
                            )
                          ";
        } else {
            $filter_keyword = '';
        }

        if($query_type == 'main'){
            $select         = 'gpo.*,
                                 (CASE 
                                    WHEN ape.alias = "" OR ape.alias IS NULL
                                        THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                    ELSE 
                                        ape.alias
                                    END
                               ) AS account_name, 
                               gbd.workflow_number, 
                               gbd.endorsement_number
                              ';
            $startLimit     = (trim($start) != "" && trim($limit) != "") ? $start . ', ' . $limit : '';
        }else{
            $select         = 'COUNT(gpo.id) AS count';
            $startLimit     = '';
        }

        $result = mysql::select(
                                'gcash_policy gpo
                                 LEFT JOIN account_personal ape
                                 ON ape.account_id = gpo.created_by
                                 LEFT JOIN gcash_batch_declaration gbd ON gbd.batch_number = gpo.batch_number',
                                 $select,
                                "gpo.id IS NOT NULL AND (gpo.duplicate IS NULL OR gpo.duplicate = 'No') " . $filter_keyword,
                                "gpo.id DESC",
            $startLimit
        );

        return $result;
    }

    public static function countPolicy($keyword = '', $account_id = '', $status_id = '')
    {
        $result = self::getPolicy($keyword, $account_id, $status_id);
        if (is_array($result)) {
            return count($result);
        } else {
            return 0;
        }
    }

    public static function addPolicy($post){
        $record = self::getPolicyByPolicyId($post['policy_id']);

        if(!is_array($record)){  
            $fields = mysql::buildFields($post, ", ");
            if(mysql::insert("gcash_policy", $fields)){
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

    public static function editPolicy($post){
        $id           = $post['id'];
        $record       = self::getPolicyById($id);
        $validate     = self::validatePolicy($post);

        if(is_array($record)){   
            if(empty($validate)){
                $fields = mysql::buildFields($post, ", ");
                if(mysql::update("gcash_policy", $fields, "id = '{$id}'")){
                    $result['status']  = 'success';
                    $result['message'] = 'Record Successfully Updated';
                    $result['id']      = $id;
                }else{
                    $result['status']  = 'failed';
                    $result['message'] = 'Encounter technical error. Pls try again';                
                }
            }else{
                $result['status']  = 'failed';
                $result['message'] = 'Duplicate Record Found';
            }
        }else{
            $result['status']  = 'failed';
            $result['message'] = 'No Record Found';
        }
        return $result;        
    }

    public static function editPolicyByPolicyId($post){
        $policy_id = $post['policy_id'];
        $record    = self::getPolicyByPolicyId($policy_id);

        if(is_array($record)){   
            $fields = mysql::buildFields($post, ", ");
            if(mysql::update("gcash_policy", $fields, "policy_id = '{$policy_id}'")){
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

    public static function deletePolicy($id){
        $record = self::getPolicyById($id);

        if(is_array($record)){
            if(mysql::delete("gcash_policy", "id = '{$id}'")){
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

    public static function managePolicy($post){
        $record = self::getPolicyByPolicyId($post['policy_id']);

        if(is_array($record) && !empty($post['policy_id'])){  
            $post['updated_by']   = ACCOUNT_ID;
            $post['updated_when'] = dateTimeStamp();
            $result = self::editPolicyByPolicyId($post);
        }else{
            $post['created_by']   = ACCOUNT_ID;
            $post['created_when'] = dateTimeStamp();
            $result = self::addPolicy($post);
        }

        return $result;
    }

    /*
    public static function addPolicy($post)
    {
        $record = self::getPolicyByPolicyId($post['policy_id']);
        if (is_array($record) && $post['policy_id'] != '') {
            $post['batch_id'] = $record[0]['batch_id'];
            $fields = mysql::buildFields($post, ", ");
            if (mysql::update('gcash_policy', $fields, "policy_id = '{$post['policy_id']}'")) {
                $result['status']  = 'success';
                $result['message'] = 'Record Successfully Updated';
            } else {
                $result['status']  = 'failed';
                $result['message'] = 'Encounter technical error. Pls try again';
            }
        } else {
            $fields = mysql::buildFields($post, ", ");
            if (mysql::insert('gcash_policy', $fields)) {
                $result['status']  = 'success';
                $result['message'] = 'New Record Saved';
                $result['id']      = mysql::insertedId();
            } else {
                $result['status']  = 'failed';
                $result['message'] = 'Encounter technical error. Pls try again';
            }
        }

        return $result;
    }
    */

    public static function addPolicyBulk($post)
    {
        if (mysql::query(
                    "INSERT INTO gcash_policy (
                                            first_name, last_name, middle_name, date_of_birth, mobile_number,
                                            email_address, date_of_transaction, reference_number, load_amount,
                                            load_status, consent_status, policy_id, policy_status,
                                            protect_premium_taxes, date_insurance_start, date_insurance_end, 
                                            batch_number,created_by, created_when, batch_id
                                            ) VALUES " . implode(",\n", $post), "insert")) {
            $result['status']  = 'success';
            $result['message'] = 'New Record Saved';
        } else {
            $result['status']  = 'failed';
            $result['message'] = 'Encounter technical error. Pls try again';
        }

        return $result;
    }

    public static function getPolicySummaryById($id)
    {
        $result = mysql::select('gcash_policy_summary gcs',
                                'gcs.*',
                                "gcs.id = '{$id}'"
        );
        return $result;
    }

    public static function getPolicySummary($account_id = '', $start = '', $limit = '', $query_type = 'main')
    {
        if(is_array($account_id)){
            $account_ids           = implode(',', $account_id);
            $filter_created_by     = " gcs.created_by IN ({$account_ids})";
        }else{
            if(!empty($account_id)){
                $filter_created_by = " gcs.created_by = '{$account_id}'";
            }else{
                $filter_created_by = "";
            }
        }

        if($query_type == 'main'){
            $select         = 'gcs.*,
                                (CASE 
                                    WHEN ape.alias = "" OR ape.alias IS NULL
                                        THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                    ELSE 
                                        ape.alias
                                  END
                                ) AS account_name
                              ';
            $startLimit     = (trim($start) != "" && trim($limit) != "") ? $start . ', ' . $limit : '';
        }else{
            $select         = 'COUNT(gcs.id) AS count';
            $startLimit     = '';
        }
        
        $result = mysql::select('gcash_policy_summary gcs
                                 LEFT JOIN account_personal ape
                                 ON ape.account_id = gcs.created_by',
                                 $select,
                                 $filter_created_by,
                                "gcs.id DESC",
                                 $startLimit
        );

        return $result;
    }

    public static function countPolicySummary($account_id = '')
    {
        $result = self::getPolicySummary($account_id);
        if (is_array($result)) {
            return count($result);
        } else {
            return 0;
        }
    }

    public static function checkIfExistingFilename($file_name)
    {
        $result = mysql::select('gcash_policy_summary gcs',
                                'count(gcs.file_name) as file_name_count',
                                "gcs.file_name = '{$file_name}'"
        );


        return $result[0]['file_name_count'];
    }

    public static function addPolicySummary($post)
    {
        $fields = mysql::buildFields($post, ", ");
        if (mysql::insert('gcash_policy_summary', $fields)) {
            $result['status']  = 'success';
            $result['message'] = 'New Record Saved';
            $result['id']      = mysql::insertedId();
        } else {
            $result['status']  = 'failed';
            $result['message'] = 'Encounter technical error. Pls try again';
        }

        return $result;
    }

    public static function editPolicySummary($post)
    {
        $id     = $post['id'];
        $record = self::getPolicySummaryById($id);

        if (is_array($record)) {
            $fields = mysql::buildFields($post, ", ");
            if (mysql::update('gcash_policy_summary', $fields, "id = '{$id}'")) {
                $result['status']  = 'success';
                $result['message'] = 'Record Successfully Updated';
                $result['id']      = $id;
            } else {
                $result['status']  = 'failed';
                $result['message'] = 'Encounter technical error. Pls try again';
            }
        } else {
            $result['status']  = 'failed';
            $result['message'] = 'No Record Found';
        }

        return $result;
    }

    public static function getDeclaration($keyword = '', $start = '', $limit = '')
    {

        if (!empty(trim($keyword))) {

            $keyword = " '%{$keyword}%' ";
            $filter  = " AND 
                            (
                                gbd.batch_number LIKE {$keyword}
                                OR
                                gbd.workflow_number LIKE {$keyword}
                                OR
                                gbd.endorsement_number LIKE {$keyword}
                            )
                          ";
        } else {
            $filter = '';
        }

        $startLimit = (trim($start) != "" && trim($limit) != "") ? $start . ', ' . $limit : '';

        $result = mysql::select('gcash_batch_declaration gbd
                                 LEFT JOIN account_personal ape
                                 ON gbd.created_by = ape.account_id',
                                'gbd.*,
                                 (CASE 
                                    WHEN ape.alias = "" OR ape.alias IS NULL
                                        THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                    ELSE 
                                        ape.alias
                                    END
                                 ) AS account_name',
                                "gbd.id IS NOT NULL " . $filter,
                                "gbd.batch_number ASC",
                                $startLimit
        );

        return $result;
    }

    public static function countDeclaration($keyword = '')
    {
        $result = self::getDeclaration($keyword);
        if (is_array($result)) {
            return count($result);
        } else {
            return 0;
        }
    }

    public static function getDeclarationById($id){
        $result = mysql::select("gcash_batch_declaration", '*', "id = '{$id}'");       
        return $result;
    }

    public static function getDeclarationByBatchNumber($batch_number){
        $result = mysql::select("gcash_batch_declaration", '*', "batch_number = '{$batch_number}'");
        return $result;
    }

    public static function validateDeclarationByBatchNumber($id, $batch_number){
        $result = mysql::select("gcash_batch_declaration", '*', "id != '{$id}' AND batch_number = '{$batch_number}'");
        return $result;
    }

    public static function addDeclaration($post){
        $batch_number = $post['batch_number'];
        $record       = self::getDeclarationByBatchNumber($batch_number);

        if(!is_array($record)){  
            $fields = mysql::buildFields($post, ", ");
            if(mysql::insert("gcash_batch_declaration", $fields)){
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

    public static function editDeclaration($post){
        $id           = $post['id'];
        $batch_number = $post['batch_number'];
        $record       = self::getDeclarationById($id);
        $validate     = self::validateDeclarationByBatchNumber($id, $batch_number);

        if(is_array($record)){   
            if(empty($validate)){
                $fields = mysql::buildFields($post, ", ");
                if(mysql::update("gcash_batch_declaration", $fields, "id = '{$id}'")){
                    $result['status']  = 'success';
                    $result['message'] = 'Record Successfully Updated';
                    $result['id']      = $id;
                }else{
                    $result['status']  = 'failed';
                    $result['message'] = 'Encounter technical error. Pls try again';                
                }
            }else{
                $result['status']  = 'failed';
                $result['message'] = 'Duplicate Record Found';
            }
        }else{
            $result['status']  = 'failed';
            $result['message'] = 'No Record Found';
        }
        return $result;        
    }

    public static function deleteDeclaration($id){
        $record = self::getDeclarationById($id);

        if(is_array($record)){
            if(mysql::delete("gcash_batch_declaration", "id = '{$id}'")){
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

    public static function validatePolicy($post){
        $result = mysql::select("gcash_policy", 
                                '*', 
                                "id                    != '{$post['id']}' AND 
                                 first_name             = '{$post['first_name']}' AND
                                 last_name              = '{$post['last_name']}' AND
                                 middle_name            = '{$post['middle_name']}' AND
                                 date_of_birth          = '{$post['date_of_birth']}' AND
                                 mobile_number          = '{$post['mobile_number']}' AND
                                 email_address          = '{$post['email_address']}' AND
                                 date_of_transaction    = '{$post['date_of_transaction']}' AND
                                 reference_number       = '{$post['reference_number']}' AND
                                 load_amount            = '{$post['load_amount']}' AND
                                 load_status            = '{$post['load_status']}' AND
                                 consent_status         = '{$post['consent_status']}' AND
                                 policy_id              = '{$post['policy_id']}' AND
                                 policy_status          = '{$post['policy_status']}' AND
                                 protect_premium_taxes  = '{$post['protect_premium_taxes']}' AND
                                 date_insurance_start   = '{$post['date_insurance_start']}' AND
                                 date_insurance_end     = '{$post['date_insurance_end']}' AND
                                 batch_number           = '{$post['batch_number']}'");
        return $result;
    }
}
