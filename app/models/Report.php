<?php
    class Report{

        public function __construct(){
        
        }

        public static function account($status_id='', $date_start='', $date_end=''){
            $result = array();

            if(!empty($status_id) && $status_id != 'all'){
                $filter_status = " AND aem.account_status_id = '".$status_id."' ";
            }else{
                $filter_status = '';
            }

            if(!empty($date_start) && !empty($date_end)){
                $filter_date_start_end = " AND aem.start_date >= '".reportDate($date_start)."' AND aem.start_date <= '".reportDate($date_end)."' ";
            }else{
                $filter_date_start_end = "";
            }

            $result = mysql::select('account acc
                                     LEFT JOIN account_personal ape
                                     ON acc.id = ape.account_id
                                     LEFT JOIN account_employment aem
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id
                                     LEFT JOIN master_account_team mae
                                     ON aem.account_team_id = mae.id
                                     LEFT JOIN master_account_department mad
                                     ON aem.account_department_id = mad.id
                                     LEFT JOIN master_account_designation mai
                                     ON aem.account_designation_id = mai.id
                                     LEFT JOIN master_account_level mal
                                     ON aem.account_level_id = mal.id ',
                                    '
                                     acc.active_directory,
                                     ape.*,
                                     CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, "")) AS full_name,
                                     mai.name as account_designation_name,
                                     mae.name as account_team_name,
                                     mat.name as account_type_name,
                                     mas.name as account_status_name,
                                     aem.account_role_id,
                                     aem.email as email,
                                     mad.name as account_department_name,
                                     mal.name as account_level_name,
                                     (SELECT CONCAT(COALESCE(first_name, "")," ",COALESCE(last_name, "")) FROM account_personal USE INDEX(account_id) WHERE account_id = aem.report_to LIMIT 1) AS report_to_name
                                    ',
                                    "acc.id IS NOT NULL {$filter_status} {$filter_date_start_end}",
                                    'full_name ASC');
            

            return $result;
        }
    }
?>