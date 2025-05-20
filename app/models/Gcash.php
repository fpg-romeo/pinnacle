<?php
class Gcash
{

    public function __construct() {}

    public static function getClaimEncodeById($id)
    {
        $result = mysql::select(
            'company com
                                     LEFT JOIN account_personal ape
                                     ON com.created_by = ape.account_id
                                    ',
            'com.*,
                                     com.name AS company_name,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS account_name,
                                    ',
            "com.id = '{$id}'"
        );
        return $result;
    }

    public static function getClaimEncodeByName($name)
    {
        $result = mysql::select(
            'gcash_claim gcl',
            'gcl.*',
            "gcl.name = '{$name}'"
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
                                gcl.name LIKE {$keyword}
                                OR
                                gcl.contact_no LIKE {$keyword}
                            )
                          ";
        } else {
            $filter = '';
        }

        $startLimit = (trim($start) != "" && trim($limit) != "") ? $start . ', ' . $limit : '';

        $result = mysql::select(
            'gcash_claim gcl
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = gcl.created_by',
            'gcl.*,
                                     CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, "")) AS uploader_name',
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
        $name   = $post['first_name'] . ' ' . $post['last_name'] . ' ' . $post['middle_name'];
        $record = self::getClaimEncodeByName($name);

        if (!is_array($record)) {
            $fields = mysql::buildFields($post, ", ");
            if (mysql::insert('gcash_claim', $fields)) {
                $result['status']  = 'success';
                $result['message'] = 'New Record Saved';
                $result['id']      = mysql::insertedId();
            } else {
                $result['status']  = 'failed';
                $result['message'] = 'Encounter technical error. Pls try again';
            }
        } else {
            $result['status']  = 'failed';
            $result['message'] = 'Record already exist';
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
}
