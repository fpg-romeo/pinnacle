<?php
class Gcash
{

    public function __construct() {}

    public static function getClaimEncodeById($id)
    {
        $result = mysql::select(
            'gcash_claim gcl LEFT JOIN gcash_claim_batch_declaration gcb on gcb.batch_number = gcl.batch_number',
            'gcl.*, gcb.workflow_number, gcb.endorsement_number',
            "gcl.id = '{$id}'"
        );
        return $result;
    }

    public static function getClaimEncodeByPolicyId($policy_id)
    {
        $result = mysql::select(
            'gcash_claim gcl',
            'gcl.*',
            "gcl.policy_id = '{$policy_id}'"
        );
        return $result;
    }

    public static function getClaimEncodeByName($name)
    {
        $result = mysql::select(
            'gcash_claim gcl',
            'gcl.*',
            "CONCAT(gcl.first_name, ' ', gcl.last_name, ' ', gcl.middle_name) = '{$name}'"
        );
        return $result;
    }

    public static function getClaimEncode($keyword = '', $account_id, $status_id, $start = '', $limit = '')
    {

        if (is_array($account_id)) {
            $account_ids        = implode(',', $account_id);
            $filter_created_by     = " AND gcl.created_by IN ({$account_ids})";
        } else {
            if (!empty($account_id)) {
                $filter_created_by      = " AND gcl.created_by = '{$account_id}'";
            } else {
                $filter_created_by      = "";
            }
        }

        if (!empty($status_id)) {
            if ($status_id == 0) {
                $filter_status = " AND gcl.status_id = '0'";
            } else {
                $filter_status = " AND gcl.status_id = '{$status_id}'";
            }
        } else {
            if ($status_id == 0) {
                $filter_status = " AND gcl.status_id = '0'";
            } else {
                $filter_status = '';
            }
        }
        if (!empty(trim($keyword))) {

            $keyword = " '%{$keyword}%' ";
            $filter  = " AND 
                            (
                                gcl.first_name LIKE {$keyword}
                                OR
                                gcl.middle_name LIKE {$keyword}
                                OR
                                gcl.last_name LIKE {$keyword}
                                OR
                                gcl.date_of_birth LIKE {$keyword}
                                OR
                                gcl.mobile_number LIKE {$keyword}
                                OR
                                gcl.email_address LIKE {$keyword}
                                OR
                                gcl.date_of_transaction LIKE {$keyword}
                                OR
                                gcl.reference_number LIKE {$keyword}
                                OR
                                gcl.load_amount LIKE {$keyword}
                                OR
                                gcl.load_status LIKE {$keyword}
                                OR
                                gcl.consent_status LIKE {$keyword}
                                OR
                                gcl.policy_id LIKE {$keyword}
                                OR
                                gcl.policy_status LIKE {$keyword}
                                OR
                                gcl.protect_premium_taxes LIKE {$keyword}
                                OR
                                gcl.date_insurance_start LIKE {$keyword}
                                OR
                                gcl.date_insurance_end LIKE {$keyword}
                                OR
                                gcl.batch_number LIKE {$keyword}
                                OR
                                gcb.endorsement_number LIKE {$keyword}
                                OR
                                gcb.workflow_number LIKE {$keyword}
                            )
                          ";
        } else {
            $filter = '';
        }

        $startLimit = (trim($start) != "" && trim($limit) != "") ? $start . ', ' . $limit : '';

        $result = mysql::select(
            'gcash_claim gcl
                                 LEFT JOIN account_personal ape
                                 ON ape.account_id = gcl.created_by
                                 LEFT JOIN gcash_claim_batch_declaration gcb ON gcb.batch_number = gcl.batch_number',
            'gcl.*,
                                 CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, "")) AS uploader_name, gcb.workflow_number, gcb.endorsement_number',
            "gcl.id IS NOT NULL AND (gcl.duplicate IS NULL OR gcl.duplicate = 'No') " . $filter_created_by . $filter_status . $filter,
            "gcl.id DESC",
            $startLimit
        );

        return $result;
    }

    public static function countClaimEncode($keyword = '', $account_id = '', $status_id = '')
    {
        $result = self::getClaimEncode($keyword, $account_id, $status_id);
        if (is_array($result)) {
            return count($result);
        } else {
            return 0;
        }
    }

    public static function addClaimEncode($post)
    {
        $record = self::getClaimEncodeByPolicyId($post['policy_id']);
        if (is_array($record) && $post['policy_id'] != '') {
            $post['batch_id'] = $record[0]['batch_id'];
            $fields = mysql::buildFields($post, ", ");
            if (mysql::update('gcash_claim', $fields, "policy_id = '{$post['policy_id']}'")) {
                $result['status']  = 'success';
                $result['message'] = 'Record Successfully Updated';
            } else {
                $result['status']  = 'failed';
                $result['message'] = 'Encounter technical error. Pls try again';
            }
        } else {
            $fields = mysql::buildFields($post, ", ");
            if (mysql::insert('gcash_claim', $fields)) {
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

    public static function getClaimEncodeSummaryById($id)
    {
        $result = mysql::select(
            'gcash_claim_summary gcs
                                    ',
            'gcs.*
                                    ',
            "gcs.id = '{$id}'"
        );
        return $result;
    }

    public static function getClaimEncodeSummary($account_id = '', $start = '', $limit = '')
    {
        if (is_array($account_id)) {
            $account_ids        = implode(',', $account_id);
            $filter_created_by     = " gcs.created_by IN ({$account_ids})";
        } else {
            if (!empty($account_id)) {
                $filter_created_by      = " gcs.created_by = '{$account_id}'";
            } else {
                $filter_created_by      = "";
            }
        }

        $startLimit = (trim($start) != "" && trim($limit) != "") ? $start . ', ' . $limit : '';

        $result = mysql::select(
            'gcash_claim_summary gcs
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = gcs.created_by',
            'gcs.*,
                                     CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, "")) AS uploader_name',
            $filter_created_by,
            "gcs.id DESC",
            $startLimit
        );

        return $result;
    }

    public static function checkIfExistingFilename($file_name)
    {
        $result = mysql::select(
            'gcash_claim_summary gcs
                                    ',
            'count(gcs.file_name) as file_name_count',
            "gcs.file_name = '{$file_name}'"
        );


        return $result[0]['file_name_count'];
    }

    public static function countClaimEncodeSummary($account_id = '')
    {
        $result = self::getClaimEncodeSummary($account_id);
        if (is_array($result)) {
            return count($result);
        } else {
            return 0;
        }
    }

    public static function addClaimEncodeSummary($post)
    {
        $fields = mysql::buildFields($post, ", ");
        if (mysql::insert('gcash_claim_summary', $fields)) {
            $result['status']  = 'success';
            $result['message'] = 'New Record Saved';
            $result['id']      = mysql::insertedId();
        } else {
            $result['status']  = 'failed';
            $result['message'] = 'Encounter technical error. Pls try again';
        }

        return $result;
    }

    public static function editClaimEncodeSummary($post)
    {
        $id     = $post['id'];
        $record = self::getClaimEncodeSummaryById($id);

        if (is_array($record)) {
            $fields = mysql::buildFields($post, ", ");
            if (mysql::update('gcash_claim_summary', $fields, "id = '{$id}'")) {
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
                                gcb.batch_number LIKE {$keyword}
                                OR
                                gcb.workflow_number LIKE {$keyword}
                                OR
                                gcb.endorsement_number LIKE {$keyword}
                            )
                          ";
        } else {
            $filter = '';
        }

        $startLimit = (trim($start) != "" && trim($limit) != "") ? $start . ', ' . $limit : '';

        $result = mysql::select('gcash_claim_batch_declaration gcb
                                 LEFT JOIN account_personal ape
                                 ON gcb.created_by = ape.account_id',
                                'gcb.*,
                                 (CASE 
                                    WHEN ape.alias = "" OR ape.alias IS NULL
                                        THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                    ELSE 
                                        ape.alias
                                    END
                                 ) AS account_name',
                                "gcb.id IS NOT NULL " . $filter,
                                "gcb.batch_number ASC",
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
        $result = mysql::select("gcash_claim_batch_declaration", '*', "id = '{$id}'");       
        return $result;
    }

    public static function getDeclarationByBatchNumber($batch_number){
        $result = mysql::select("gcash_claim_batch_declaration", '*', "batch_number = '{$batch_number}'");
        return $result;
    }

    public static function validateDeclarationByBatchNumber($id, $batch_number){
        $result = mysql::select("gcash_claim_batch_declaration", '*', "id != '{$id}' AND batch_number = '{$batch_number}'");
        return $result;
    }

    public static function addDeclaration($post){
        $batch_number = $post['batch_number'];
        $record       = self::getDeclarationByBatchNumber($batch_number);

        if(!is_array($record)){  
            $fields = mysql::buildFields($post, ", ");
            if(mysql::insert("gcash_claim_batch_declaration", $fields)){
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
                if(mysql::update("gcash_claim_batch_declaration", $fields, "id = '{$id}'")){
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
            if(mysql::delete("gcash_claim_batch_declaration", "id = '{$id}'")){
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
}
