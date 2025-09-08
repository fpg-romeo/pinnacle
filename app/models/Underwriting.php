<?php
class Underwriting{

    public function __construct() {

    }

    public static function getPolicyById($id)
    {
        $result = mysql::select('underwriting_policy upo',
                                'upo.*',
                                "upo.id = '{$id}'");
        return $result;
    }

    public static function getPolicyByPolicyId($policy_id)
    {
        $result = mysql::select('underwriting_policy upo',
                                'upo.*',
                                "upo.policy_no = '{$policy_id}'"
        );
        return $result;
    }

    public static function getPolicyByName($name)
    {
        $result = mysql::select('underwriting_policy upo',
                                'upo.*',
                                "CONCAT(upo.first_name, ' ', upo.last_name, ' ', upo.middle_name) = '{$name}'"
        );
        return $result;
    }

    public static function getPolicyByBatchNo($batch_no = '', $keyword = '', $start = '', $limit = '', $query_type = 'main')
    {

        if(!empty($batch_no)){
            $filter_batch_number = " AND upo.batch_number = '{$batch_no}'";
        }else{
            $filter_batch_number = " AND upo.batch_number = (SELECT MAX(batch_number) FROM underwriting_batch_declaration) ";
        }

        if (!empty(trim($keyword))) {

            $keyword = " '%{$keyword}%' ";
            $filter_keyword  = " AND 
                            (
                                upo.first_name LIKE {$keyword}
                                OR
                                upo.middle_name LIKE {$keyword}
                                OR
                                upo.last_name LIKE {$keyword}
                                OR
                                upo.date_of_birth LIKE {$keyword}
                                OR
                                upo.mobile_number LIKE {$keyword}
                                OR
                                upo.email_address LIKE {$keyword}
                                OR
                                upo.date_of_transaction LIKE {$keyword}
                                OR
                                upo.reference_number LIKE {$keyword}
                                OR
                                upo.load_amount LIKE {$keyword}
                                OR
                                upo.load_status LIKE {$keyword}
                                OR
                                upo.consent_status LIKE {$keyword}
                                OR
                                upo.policy_id LIKE {$keyword}
                                OR
                                upo.policy_status LIKE {$keyword}
                                OR
                                upo.protect_premium_taxes LIKE {$keyword}
                                OR
                                upo.date_insurance_start LIKE {$keyword}
                                OR
                                upo.date_insurance_end LIKE {$keyword}
                                OR
                                upo.batch_number LIKE {$keyword}
                            )
                          ";
        } else {
            $filter_keyword = '';
        }

        if($query_type == 'main'){
            $select         = 'upo.*,
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
            $select         = 'COUNT(upo.id) AS count';
            $startLimit     = '';
        }

        $result = mysql::select(
                                'underwriting_policy upo USE INDEX(batch_number)
                                 LEFT JOIN account_personal ape
                                 ON ape.account_id = upo.created_by',
                                 $select,
                                "upo.id IS NOT NULL AND (upo.duplicate IS NULL OR upo.duplicate = 'No') " . $filter_batch_number . $filter_keyword,
                                "upo.id DESC",
            $startLimit
        );

        return $result;
    }

    public static function getPolicy($keyword = '', $start = '', $limit = '', $query_type = 'main')
    {

        if (!empty(trim($keyword))) {

            $keyword = " '%{$keyword}%' ";
            $filter_keyword  = " AND 
                            (
                                upo.first_name LIKE {$keyword}
                                OR
                                upo.middle_name LIKE {$keyword}
                                OR
                                upo.last_name LIKE {$keyword}
                                OR
                                upo.date_of_birth LIKE {$keyword}
                                OR
                                upo.mobile_number LIKE {$keyword}
                                OR
                                upo.email_address LIKE {$keyword}
                                OR
                                upo.date_of_transaction LIKE {$keyword}
                                OR
                                upo.reference_number LIKE {$keyword}
                                OR
                                upo.load_amount LIKE {$keyword}
                                OR
                                upo.load_status LIKE {$keyword}
                                OR
                                upo.consent_status LIKE {$keyword}
                                OR
                                upo.policy_id LIKE {$keyword}
                                OR
                                upo.policy_status LIKE {$keyword}
                                OR
                                upo.protect_premium_taxes LIKE {$keyword}
                                OR
                                upo.date_insurance_start LIKE {$keyword}
                                OR
                                upo.date_insurance_end LIKE {$keyword}
                                OR
                                upo.batch_number LIKE {$keyword}
                            )
                          ";
        } else {
            $filter_keyword = '';
        }

        if($query_type == 'main'){
            $select         = 'upo.*,
                                 (CASE 
                                    WHEN ape.alias = "" OR ape.alias IS NULL
                                        THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                    ELSE 
                                        ape.alias
                                    END
                               ) AS account_name, ups.id as batch_id
                              ';
            $startLimit     = (trim($start) != "" && trim($limit) != "") ? $start . ', ' . $limit : '';
        }else{
            $select         = 'COUNT(upo.id) AS count';
            $startLimit     = '';
        }

        $result = mysql::select(
                                'underwriting_policy upo
                                 LEFT JOIN account_personal ape
                                 ON ape.account_id = upo.created_by
                                 LEFT JOIN underwriting_policy_summary ups ON ups.id = upo.policy_summary_id',
                                 $select,
                                "upo.id IS NOT NULL AND (ups.duplicate IS NULL OR ups.duplicate = 'No') " . $filter_keyword,
                                "upo.id DESC",
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
            if(mysql::insert("underwriting_policy", $fields)){
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
                if(mysql::update("underwriting_policy", $fields, "id = '{$id}'")){
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
        $policy_id = $post['policy_no'];
        $record    = self::getPolicyByPolicyId($policy_id);

        if(is_array($record)){   
            $fields = mysql::buildFields($post, ", ");
            if(mysql::update("underwriting_policy", $fields, "policy_no = '{$policy_id}'")){
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
            if(mysql::delete("underwriting_policy", "id = '{$id}'")){
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
        $record = self::getPolicyByPolicyId($post['policy_no']);

        if(is_array($record) && !empty($post['policy_no'])){  
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

    public static function addPolicyBulk($post)
    {
        if (mysql::query(
                    "INSERT INTO underwriting_policy (`policy_summary_id`,`policy_no`,`remarks`,`occupancy`,`tariff_code`,`expiring_rate`,`renewal_rate`,`endorsement_no`,`renewal_no`,`ci_no`,`reference_no`,`co_insurance`,`insured_name`,`contact_numbers`,`inception_date`,`expiry_date`,`booking_date`,`branch`,`branch_name`,`channel`,`channel_2`,`channel_3`,`toc`,`cob`,`fob`,`policy_type`,`mo`,`segment`,`segment_desc`,`ourshare`,`gross`,`pctshare`,`facultative`,`fshare`,`total_sum_insured`,`basic_premium`,`dst`,`vat`,`fst`,`lgt`,`total_premium`,`coverage`,`bscode`,`bsname`,`fee`,`discount`,`nofclaim`,`os_claim`,`settled_claim`,`premiumpaid`,`loss_ratio`,`location_of_risk`,`vehicle_unit`,`type_of_body`,`plate_no`,`engine_no`,`chassis_no`,`renewal_premium`,`renewal_tsi`,`renewal_status`,`location_of_risk_2`,`mailing_address`,`lgt_rate_per_branch`,`created_by`,`created_when`) VALUES " . implode(",\n", $post), "insert")) {
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
        $result = mysql::select('underwriting_policy_summary gcs',
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
        
        $result = mysql::select('underwriting_policy_summary gcs
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
        $result = mysql::select('underwriting_policy_summary gcs',
                                'count(gcs.file_name) as file_name_count',
                                "gcs.file_name = '{$file_name}'"
        );


        return $result[0]['file_name_count'];
    }

    public static function addPolicySummary($post)
    {
        $fields = mysql::buildFields($post, ", ");
        if (mysql::insert('underwriting_policy_summary', $fields)) {
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
            if (mysql::update('underwriting_policy_summary', $fields, "id = '{$id}'")) {
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

    public static function validatePolicy($post){
        $result = mysql::select("underwriting_policy", 
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