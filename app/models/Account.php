<?php
class Account
{

    public function __construct() {}

    public static function logout()
    {
        mysql::disconnect();
    }

    public static function getDynamicById($table, $id)
    {
        $result = mysql::select($table, '*', "id = '{$id}'");
        return $result;
    }

    public static function getRowByEmail($email)
    {
        $result = mysql::select('account_employment', '*', "email = '{$email}'");
        return $result;
    }

    public static function getById($id)
    {
        $result = mysql::select('account', '*', "id = '{$id}'");
        return $result;
    }

    //NEW LOGIN FEATURE
    public static function ldap($username)
    {
        $record = mysql::select(
            'account',
            '*',
            "active_directory = '{$username}'"
        );

        if (is_array($record)) {
            $result['record']  = recastArray($record);

            $result['status']  = 'success';
            $result['message'] = 'Welcome';
        } else {
            $result['status']  = 'failed';
            $result['message'] = 'Invalid Username or Password. Please try again';
        }

        return $result;
    }

    public static function login($username, $password)
    {
        $result = mysql::select(
                                    'account acc
                                     LEFT JOIN account_personal ape
                                     ON acc.id = ape.account_id
                                     LEFT JOIN account_employment aem
                                     ON acc.id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id',
                                    'ape.*,
                                     aem.*, 
                                     acc.*,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name',
            "aem.email = '{$username}' AND acc.password = '{$password}' AND aem.account_status_id = 1 "
        ); //account_status_id = 1 : ACTIVE
        return $result;
    }

    public static function getRecordByIdAndPassword($id, $password)
    {
        $result = mysql::select(
                                    'account acc
                                     LEFT JOIN account_personal ape
                                     ON acc.id = ape.account_id
                                     LEFT JOIN account_employment aem
                                     ON acc.id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id',
                                    'ape.*, 
                                     aem.*, 
                                     acc.*,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name',
            "acc.id = '{$id}' AND acc.password = '{$password}'"
        );
        return $result;
    }

    public static function getRecordById($id)
    {
        $result = mysql::select(
                                    'account_employment aem USE INDEX(account_id)
                                     LEFT JOIN account_personal ape
                                     ON aem.account_id = ape.account_id
                                     LEFT JOIN master_account_department mad
                                     ON aem.account_department_id = mad.id
                                     LEFT JOIN master_account_designation mai
                                     ON aem.account_designation_id = mai.id 
                                     LEFT JOIN master_account_level mal
                                     ON aem.account_level_id = mal.id 
                                     LEFT JOIN master_account_team mae
                                     ON aem.account_team_id = mae.id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id
                                     LEFT JOIN account acc
                                     ON aem.account_id = acc.id
                                    ',
                                    'aem.*, 
                                     ape.*, 
                                     mad.name AS account_department_name,
                                     mai.name AS account_designation_name,
                                     mal.name AS account_level_name,
                                     mae.name AS account_team_name,
                                     aem.account_id as id,
                                     aem.email,
                                     acc.relogin,
                                     acc.active_directory,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name,
                                     (SELECT CONCAT(COALESCE(first_name, "")," ",COALESCE(last_name, "")) FROM account_personal USE INDEX(account_id) WHERE account_id = aem.report_to LIMIT 1) AS report_to_name,
                                     @report_to_email := (
                                                                CASE 
                                                                    WHEN aem.report_to > 0
                                                                        THEN (SELECT email FROM account_employment USE INDEX(account_id) WHERE account_id = aem.report_to AND account_status_id = 1)
                                                                    ELSE 
                                                                        ""
                                                                END
                                                                ) AS report_to_email,

                                     @team_leader_count := (SELECT COUNT(account_id) FROM account_employment USE INDEX(account_team_id) WHERE account_team_id = aem.account_team_id AND account_department_id = aem.account_department_id AND account_status_id = 1 AND account_level_id = 1) AS team_leader_count,
                                     @team_leader_account_id := (
                                                                CASE 
                                                                    WHEN @team_leader_count = 1
                                                                        THEN (SELECT account_id FROM account_employment USE INDEX(account_team_id) WHERE account_team_id = aem.account_team_id AND account_department_id = aem.account_department_id AND account_status_id = 1 AND account_level_id = 1)
                                                                    ELSE 
                                                                        0
                                                                END
                                                                ) AS team_leader_account_id

                                    ',
            "aem.account_id = '{$id}'"
        );
        return $result;
    }

    public static function getLoggedUser($id, $level_id = '')
    {
        // if(!empty($level_id)){
        //     $level_id = " AND account_level_id = '{$level_id}'";
        // }

        if (!empty($level_id)) {
            if (is_array($level_id)) {
                $level_id = implode("','", $level_id);
            }
            $filter_level = " AND account_level_id IN ('$level_id')";
        } else {
            $filter_level = '';
        }

        $result = mysql::select(
                                    'account_employment aem USE INDEX(account_id)
                                     LEFT JOIN account_personal ape
                                     ON aem.account_id = ape.account_id
                                     LEFT JOIN master_account_department mad
                                     ON aem.account_department_id = mad.id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id
                                     LEFT JOIN account acc
                                     ON aem.account_id = acc.id
                                    ',
                                    'aem.*,
                                     ape.*, 
                                     aem.account_id as id,
                                     aem.email,
                                     acc.relogin,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mad.name AS account_department_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name,

                                     @team_leader_count := (SELECT COUNT(account_id) FROM account_employment USE INDEX(account_team_id) WHERE account_team_id = aem.account_team_id AND account_department_id = aem.account_department_id AND account_status_id = 1 ' . $filter_level . ') AS team_leader_count,
                                     @team_leader_account_id := (
                                                                CASE 
                                                                    WHEN @team_leader_count = 1
                                                                        THEN (SELECT account_id FROM account_employment USE INDEX(account_team_id) WHERE account_team_id = aem.account_team_id AND account_department_id = aem.account_department_id AND account_status_id = 1 ' . $filter_level . ')
                                                                    ELSE 
                                                                        0
                                                                END
                                                                ) AS team_leader_account_id
                                    ',
            "aem.account_id = '{$id}'"
        );
        return $result;
    }

    public static function getRecordByIds($ids)
    {
        if (!empty($ids)) {
            if (is_array($ids)) {
                $ids = implode("','", $ids);
            }
            $filter_ids = " AND aem.account_id IN ('$ids')";
        } else {
            $filter_ids = '';
        }
        $result = mysql::select(
                                    'account_employment aem USE INDEX(account_id)
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id',
                                    'ape.*, 
                                     aem.*, 
                                     aem.account_id as id,
                                     aem.email,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name',
            "aem.account_id IS NOT NULL " . $filter_ids,
            'full_name ASC'
        );
        return $result;
    }

    public static function getRecordByFirstName($first_name)
    {
        $result = mysql::select(
                                    'account_employment aem
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id',
                                    'ape.*, 
                                     aem.*, 
                                     aem.account_id as id,
                                     aem.email,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name',
            "ape.first_name = '{$first_name}'"
        );
        return $result;
    }

    public static function getEmailById($id)
    {
        $result = mysql::select('account_employment', 'email',  "account_id = '{$id}'");
        return $result;
    }

    public static function getRecordByEmail($email)
    {
        $result = mysql::select(
            'account_employment aem
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id
                                     LEFT JOIN account acc 
                                     ON acc.id = aem.account_id',
            'ape.*, 
                                     aem.*, 
                                     aem.account_id as id,
                                     aem.email,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name,
                                     acc.remember_me,
                                     acc.verification_code',
            "aem.email = '{$email}'"
        );
        return $result;
    }

    public static function getRecordByDesignationId($account_designation_id)
    {
        $result = mysql::select(
            'account_employment aem USE INDEX(account_designation_id)
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id',
            'ape.*, 
                                     aem.*, 
                                     aem.account_id as id,
                                     aem.email,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name',
            "aem.account_designation_id = '{$account_designation_id}'"
        );
        return $result;
    }

    public static function getRecordByDepartmentId($account_department_id)
    {
        $result = mysql::select(
            'account_employment aem USE INDEX(account_department_id)
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id',
            'ape.*, 
                                     aem.*, 
                                     aem.account_id as id,
                                     aem.email,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name',
            "aem.account_department_id = '{$account_department_id}'"
        );
        return $result;
    }

    public static function getRecordByDepartmentIds($account_department_ids = "", $status_ids = '')
    {

        if (!empty($account_department_ids)) {
            if (is_array($account_department_ids)) {
                $account_department_ids = implode("','", $account_department_ids);
            }
            $filter_department = " AND aem.account_department_id IN ('{$account_department_ids}') ";
        } else {
            $filter_department = '';
        }

        if (!empty($status_ids)) {
            if (is_array($status_ids)) {
                $status_ids = implode("','", $status_ids);
            }
            $filter_status = " AND aem.account_status_id IN ('{$status_ids}') ";
        } else {
            $filter_status = '';
        }

        $result = mysql::select(
            'account_employment aem USE INDEX(account_department_id)
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id',
            'ape.*, 
                                     aem.*, 
                                     aem.account_id as id,
                                     aem.email,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name',
            "aem.account_id IS NOT NULL " . $filter_department . $filter_status,
            'full_name ASC'
        );
        return $result;
    }

    public static function getRecordByDepartmentIdAndTeamId($account_department_id = "", $account_team_id)
    {
        if (!empty($account_department_id)) {
            if (is_array($account_department_id)) {
                $account_team_ids = implode("','", $account_department_id);
            } else {
                $account_team_ids = $account_department_id;
            }
            $filter_department = " AND aem.account_department_id IN ('$account_team_ids')";
        } else {
            $filter_department = "";
        }

        if (!empty($account_team_id)) {
            if (is_array($account_team_id)) {
                $account_team_ids = implode("','", $account_team_id);
            } else {
                $account_team_ids = $account_team_id;
            }
            $filter_team = " AND aem.account_team_id IN ('$account_team_ids')";
        } else {
            $filter_team = "";
        }

        $result = mysql::select(
            'account_employment aem
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id',
            'ape.*, 
                                     aem.*, 
                                     aem.account_id as id,
                                     aem.email,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name',
            "aem.account_status_id = 1" . $filter_department . $filter_team
        );
        return $result;
    }

    public static function getRecordByDepartmentIdAndTeamIdsAndLevelIds($account_department_id, $account_team_ids, $account_level_ids, $account_status_id)
    {
        if (is_array($account_level_ids)) {
            $ids = implode("','", $account_level_ids);
        } else {
            $ids = $account_level_ids;
        }

        if (is_array($account_team_ids)) {
            $team_ids = implode("','", $account_team_ids);
        } else {
            $team_ids = $account_team_ids;
        }

        if (!empty($account_status_id)) {
            if (is_array($account_status_id)) {
                $account_status_id = implode("','", $account_status_id);
            }
            $filter_accounts_status_id = " AND aem.account_status_id IN ('{$account_status_id}')";
        } else {
            $filter_accounts_status_id = "";
        }
        $result = mysql::select(
            'account_employment aem
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id
                                     LEFT JOIN master_account_team mae
                                     ON aem.account_team_id = mae.id
                                    ',
            'ape.*, 
                                     aem.*, 
                                     aem.account_id as id,
                                     aem.email,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name,
                                     mae.name AS account_team_name
                                    ',
            "aem.account_department_id = '{$account_department_id}' AND aem.account_team_id IN ('{$team_ids}') AND aem.account_level_id IN ('{$ids}')" . $filter_accounts_status_id
        );
        return $result;
    }

    public static function getRecordByDepartmentIdsAndLevelIds($account_department_ids, $account_level_ids, $account_status_id)
    {
        if (is_array($account_department_ids)) {
            $department = implode("','", $account_department_ids);
        } else {
            $department = $account_department_ids;
        }

        if (is_array($account_level_ids)) {
            $level = implode("','", $account_level_ids);
        } else {
            $level = $account_level_ids;
        }

        $result = mysql::select(
            'account_employment aem
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id',
            'ape.*, 
                                     aem.*, 
                                     aem.account_id as id,
                                     aem.email,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name',
            "aem.account_department_id IN ('{$department}') AND aem.account_level_id IN ('{$level}') AND aem.account_status_id = '{$account_status_id}'",
            "full_name ASC"
        );
        return $result;
    }

    public static function getRecordByDepartmentIdsAndLevelIdsAndTeamIds($account_department_ids, $account_level_ids, $account_team_ids, $account_status_id)
    {
        if (is_array($account_department_ids)) {
            $department = implode("','", $account_department_ids);
        } else {
            $department = $account_department_ids;
        }

        if (is_array($account_level_ids)) {
            $level = implode("','", $account_level_ids);
        } else {
            $level = $account_level_ids;
        }

        if (is_array($account_team_ids)) {
            $team = implode("','", $account_team_ids);
        } else {
            $team = $account_team_ids;
        }

        $result = mysql::select(
            'account_employment aem
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id',
            'ape.*, 
                                     aem.*, 
                                     aem.account_id as id,
                                     aem.email,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name',
            "aem.account_department_id IN ('{$department}') AND aem.account_level_id IN ('{$level}') AND aem.account_team_id IN ('{$team}') AND aem.account_status_id = '{$account_status_id}'",
            "full_name ASC"
        );
        return $result;
    }

    public static function getRecordByTeamIdAndDepartmentIds($account_team_id, $account_department_ids, $account_status_id)
    {
        if (is_array($account_department_ids)) {
            $ids = implode("','", $account_department_ids);
        } else {
            $ids = $account_department_ids;
        }

        $result = mysql::select(
            'account_employment aem
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id',
            'ape.*, 
                                     aem.*, 
                                     aem.account_id as id,
                                     aem.email,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name',
            "aem.account_team_id = '{$account_team_id}' AND aem.account_department_id IN ('{$ids}') AND aem.account_status_id = '{$account_status_id}'",
            "full_name ASC"
        );
        return $result;
    }

    public static function getByReportTo($account_id)
    {
        $result = mysql::select(
            'account_employment aem USE INDEX(report_to)
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id',
            'ape.*, 
                                     aem.*, 
                                     aem.account_id as id,
                                     aem.email,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name',
            "aem.report_to = '{$account_id}'",
            "full_name ASC"
        );
        return $result;
    }

    public static function getPersonalByAccountId($account_id)
    {
        $result = mysql::select(
            'account_personal USE INDEX(account_id)
                                    ',
            '*, 
                                     CONCAT(COALESCE(first_name, "")," ",COALESCE(last_name, "")) AS full_name
                                    ',
            "account_id = '{$account_id}'"
        );
        return $result;
    }

    public static function getAll()
    {
        $result = mysql::select(
            'account_employment aem
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id 
                                     LEFT JOIN master_account_department mad
                                     ON aem.account_department_id = mad.id 
                                     LEFT JOIN master_account_team mae
                                     ON aem.account_team_id = mae.id 
                                     LEFT JOIN master_account_level mal
                                     ON aem.account_level_id = mal.id 
                                     LEFT JOIN master_account_designation mai
                                     ON aem.account_designation_id = mai.id 
                                     ',
            'ape.*, 
                                     aem.*, 
                                     aem.account_id as id,
                                     aem.email,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name,
                                     mad.name AS account_department_name,
                                     mae.name AS account_team_name,
                                     mal.name AS account_level_name,
                                     mai.name AS account_designation_name
                                    ',
            "",
            "full_name ASC"
        );
        return $result;
    }

    public static function getByStatusId($status_id)
    {
        $result = mysql::select(
            'account_employment aem
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id 
                                     LEFT JOIN master_account_department mad
                                     ON aem.account_department_id = mad.id 
                                     LEFT JOIN master_account_team mae
                                     ON aem.account_team_id = mae.id 
                                     LEFT JOIN master_account_level mal
                                     ON aem.account_level_id = mal.id 
                                     LEFT JOIN master_account_designation mai
                                     ON aem.account_designation_id = mai.id 
                                     ',
            'ape.*, 
                                     aem.*, 
                                     aem.account_id as id,
                                     aem.email,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name,
                                     mad.name AS account_department_name,
                                     mae.name AS account_team_name,
                                     mal.name AS account_level_name,
                                     mai.name AS account_designation_name
                                    ',
            "aem.account_status_id = '{$status_id}'", //account_status_id = 1 : ACTIVE,
            "full_name ASC"
        );
        return $result;
    }

    public static function addRecord($post)
    {
        $email  = $post['email'];
        $record = self::getRowByEmail($email);

        if (!is_array($record)) {
            unset($post['email']);

            $fields = mysql::buildFields($post, ", ");
            if (mysql::insert('account', $fields)) {
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

    public static function editRecord($post)
    {
        $id     = $post['id'];
        $record = self::getDynamicById('account', $id);

        if (is_array($record)) {
            //$fields = mysql::buildFields($post, ", "); //This is passing a comma-prefixed string as the $fields to your update() function, which leads to invalid SQL
            $fields = ltrim(mysql::buildFields($post, ", "), ", "); //IDK why but this fixes my issue
            if (mysql::update('account', $fields, "id = '{$id}'")) {
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

    public static function deleteRecord($id)
    {
        $record = self::getDynamicById('account', $id);

        if (is_array($record)) {
            if (mysql::delete('account', "id = '{$id}'")) {
                $result['status']   = 'success';
                $result['message']  = 'Record Successfully Deleted';
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

    public static function changePasswordByAccountId($post)
    {

        $id     = $post['id'];
        $record = self::getDynamicById('account', $id);

        if (is_array($record)) {
            $fields = mysql::buildFields($post, ", ");
            if (mysql::update('account', $fields, "id = '{$id}'")) {
                $result['status']  = 'success';
                $result['message'] = 'Password Successfully Updated';
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

    public static function getAllRecordByStatus($status = '1,2')
    {
        $result = mysql::select(
            'account_employment aem
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id
                                     LEFT JOIN account acc
                                     ON acc.id = aem.account_id',
            'ape.*,
                                     aem.*,
                                     aem.account_id as id,
                                     aem.email,
                                     acc.last_time_in,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name',
            " aem.account_status_id IN (" . $status . ") ",
            'full_name ASC'
        );
        return $result;
    }

    public static function getAccountInformationsRecordById($id)
    {
        $result = mysql::select(
            'account_employment aem USE INDEX(account_id)
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id
                                     LEFT JOIN master_account_department  mad
                                     ON aem.account_department_id = mad.id
                                     LEFT JOIN master_account_designation mades
                                     ON aem.account_designation_id = mades.id',
            'ape.*, 
                                     aem.*, 
                                     aem.account_id as id,
                                     aem.email,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name,
                                     mad.name AS account_department_name,
                                     mades.name AS account_designation_name',
            "aem.account_id = '{$id}'"
        );
        return $result;
    }

    public static function getDynamicByAccountId($table, $account_id)
    {
        $result = mysql::select($table, '*', "account_id = '{$account_id}'");
        return $result;
    }

    public static function addDynamic($table, $post)
    {
        $account_id = $post['account_id'];
        $record     = self::getDynamicByAccountId($table, $account_id);

        if (!is_array($record)) {
            $fields = mysql::buildFields($post, ", ");
            if (mysql::insert($table, $fields)) {
                $result['status']  = 'success';
                $result['message'] = 'New Record Saved';
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

    public static function editDynamic($table, $post)
    {
        $account_id = $post['account_id'];
        $record     = self::getDynamicByAccountId($table, $account_id);

        if (is_array($record)) {
            $fields = mysql::buildFields($post, ", ");
            if (mysql::update($table, $fields, "account_id = '{$account_id}'")) {
                $result['status']  = 'success';
                $result['message'] = 'Record Successfully Updated';
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

    public static function manageDynamic($table, $post, $is_from_api = false)
    {
        $account_id = $post['account_id'];
        $record     = self::getDynamicByAccountId($table, $account_id);


        if ($is_from_api == false) {
            if (is_array($record)) {
                $post['updated_by']   = (!empty(ACCOUNT_ID) ? ACCOUNT_ID : (isset($post['updated_by']) && !empty($post['updated_by']) ? $post['updated_by'] : 0));
                $post['updated_when'] = dateTimeStamp();
                $result = self::editDynamic($table, $post);
            } else {
                $post['created_by']   = (!empty(ACCOUNT_ID) ? ACCOUNT_ID : (isset($post['created_by']) && !empty($post['created_by']) ? $post['created_by'] : 0));
                $post['created_when'] = dateTimeStamp();
                $result = self::addDynamic($table, $post);
            }
        } else {
            if (is_array($record)) {
                $post['updated_by']   = (isset($post['updated_by']) && !empty($post['updated_by'])) ? $post['updated_by'] : 0;
                $post['updated_when'] = dateTimeStamp();
                $result = self::editDynamic($table, $post);
            } else {
                $post['created_by']   = (isset($post['created_by']) && !empty($post['created_by'])) ? $post['created_by'] : 0;
                $post['created_when'] = dateTimeStamp();
                $result = self::addDynamic($table, $post);
            }
        }

        return $result;
    }

    public static function deleteAccountPersonalById($id)
    {
        $record     = self::getDynamicByAccountId('account_personal', $id);

        if (is_array($record)) {
            if (mysql::delete('account_personal', "account_id = '{$id}'")) {
                $result['status']   = 'success';
                $result['message']  = 'Record Successfully Deleted';
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

    public static function deleteAccountBankById($id)
    {
        $record     = self::getDynamicByAccountId('account_bank', $id);

        if (is_array($record)) {
            if (mysql::delete('account_bank', "account_id = '{$id}'")) {
                $result['status']   = 'success';
                $result['message']  = 'Record Successfully Deleted';
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

    public static function deleteAccountEmergencyContactById($id)
    {
        $record     = self::getDynamicByAccountId('account_emergency_contact', $id);

        if (is_array($record)) {
            if (mysql::delete('account_emergency_contact', "account_id = '{$id}'")) {
                $result['status']   = 'success';
                $result['message']  = 'Record Successfully Deleted';
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

    public static function deleteAccountEmploymentById($id)
    {
        $record     = self::getDynamicByAccountId('account_employment', $id);

        if (is_array($record)) {
            if (mysql::delete('account_employment', "account_id = '{$id}'")) {
                $result['status']   = 'success';
                $result['message']  = 'Record Successfully Deleted';
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

    public static function deleteAccountEquipmentById($id)
    {
        $record     = self::getDynamicByAccountId('account_equipment', $id);

        if (is_array($record)) {
            if (mysql::delete('account_equipment', "account_id = '{$id}'")) {
                $result['status']   = 'success';
                $result['message']  = 'Record Successfully Deleted';
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

    public static function getBusinessUnit()
    {
        $result = mysql::select(
            'account_employment aem
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_team mat
                                     ON mat.id = aem.account_team_id
                                     LEFT JOIN master_account_department mad
                                     ON mad.id = aem.account_department_id
                                     LEFT JOIN master_account_level mal
                                     ON mal.id = aem.account_level_id
                                     LEFT JOIN master_account_designation mde
                                     ON mde.id = aem.account_designation_id',
            'aem.account_id as account_id,
                                     aem.account_team_id,
                                     mat.name as team_name,
                                     aem.account_department_id,
                                     mad.name as department_name,
                                     mde.name as designation_name,
                                     aem.account_level_id,
                                     mal.name as account_level_name,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     aem.monthly_sales_target
                                    ',
            "aem.account_team_id > 0 and ape.account_id > 0 and aem.account_status_id = 1",
            "team_name,department_name, aem.account_level_id ASC"
        );
        return $result;
    }

    public static function getAccountBlacklist()
    {

        $result = mysql::select(
            "account_blacklist abl",
            'abl.*,
                                    CONCAT(COALESCE(abl.first_name, "")," ",COALESCE(abl.last_name, "")) AS full_name',
            "",
            "abl.created_when ASC",
        );

        return $result;
    }

    public static function getAccountBlacklistRecordById($id)
    {
        $result = mysql::select(
            "account_blacklist abl",
            'abl.*',
            "abl.id = '{$id}'"
        );
        return $result;
    }

    public static function addAccountBlacklist($post)
    {
        $id         = $post['id'];
        $record     = self::getAccountBlacklistRecordById($id);

        if (!is_array($record)) {
            $fields = mysql::buildFields($post, ", ");
            if (mysql::insert('account_blacklist', $fields)) {
                $result['status']  = 'success';
                $result['message'] = 'New Record Saved';
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

    public static function editAccountBlacklist($post)
    {
        $id         = $post['id'];
        $record     = self::getAccountBlacklistRecordById($id);

        if (is_array($record)) {
            $fields = mysql::buildFields($post, ", ");
            if (mysql::update('account_blacklist', $fields, "id = '{$id}'")) {
                $result['status']  = 'success';
                $result['message'] = 'Record Successfully Updated';
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

    public static function deleteAccountBlacklist($id)
    {
        $record     = self::getAccountBlacklistRecordById($id);

        if (is_array($record)) {
            if (mysql::delete('account_blacklist', "id = '{$id}'")) {
                $result['status']   = 'success';
                $result['message']  = 'Record Successfully Deleted';
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

    public static function getAccount($account_department_ids, $account_status_id, $keyword = '', $start = '', $limit = '', $appointment = '', $auto_allocate = '')
    {

        if (!empty($account_department_ids)) {
            if (is_array($account_department_ids)) {
                $account_department_ids = implode("','", $account_department_ids);
            }
            $filter_department = " AND aem.account_department_id IN ('$account_department_ids')";
        } else {
            $filter_department = "";
        }

        if (!empty($account_status_id)) {
            if (is_array($account_status_id)) {
                $account_status_id = implode("','", $account_status_id);
            }
            $account_status = " aem.account_status_id IN ('{$account_status_id}') ";
        } else {
            $account_status = " aem.account_status_id = 1 "; // 1 = ACTIVE
        }

        if (!empty(trim($keyword))) {
            $keyword = " '%{$keyword}%' ";
            $filter  = "
                    AND
                    (
                        CONCAT(ape.first_name, ' ', ape.last_name) LIKE {$keyword}
                        OR
                        ape.alias LIKE {$keyword}
                        OR
                        ape.contact_no LIKE {$keyword}
                        OR
                        mat.name LIKE {$keyword}
                        OR
                        mas.name LIKE {$keyword}
                        OR
                        mae.name LIKE {$keyword}
                        OR
                        mad.name LIKE {$keyword}
                        OR
                        mai.name LIKE {$keyword}
                        OR
                        mal.name LIKE {$keyword}
                        OR
                        aem.email LIKE {$keyword}
                        OR
                        aem.account_id LIKE {$keyword}
                        OR
                        acc.active_directory LIKE {$keyword}
                    )
                ";
        } else {
            $filter = '';
        }

        $startLimit = (trim($start) != "" && trim($limit) != "") ? $start . ', ' . $limit : '';

        $result = mysql::select(
                                    'account_employment aem
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id 
                                     LEFT JOIN master_account_department mad
                                     ON aem.account_department_id = mad.id 
                                     LEFT JOIN master_account_team mae
                                     ON aem.account_team_id = mae.id 
                                     LEFT JOIN master_account_level mal
                                     ON aem.account_level_id = mal.id 
                                     LEFT JOIN master_account_designation mai
                                     ON aem.account_designation_id = mai.id 
                                     LEFT JOIN account acc 
                                     ON aem.account_id = acc.id
                                     ',
                                    'ape.*, 
                                     aem.*, 
                                     aem.account_id as id,
                                     acc.active_directory,
                                     ape.photo AS user_photo,
                                     aem.email,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name,
                                     mad.name AS account_department_name,
                                     mae.name AS account_team_name,
                                     mal.name AS account_level_name,
                                     mai.name AS account_designation_name
                                    ',
            $account_status . $filter_department . $filter,
            'full_name ASC',
            $startLimit
        );
        return $result;
    }

    public static function countAccount($account_department_ids = '', $account_status_id = '', $keyword = '', $appointment = '', $auto_allocate = '')
    {
        $result = self::getAccount($account_department_ids, $account_status_id, $keyword, '', '', $appointment, $auto_allocate);

        if (is_array($result)) {
            return count($result);
        } else {
            return 0;
        }
    }

    public static function getAccountTeamLeaderByAccountId($account_id)
    {
        $result = mysql::select(
            'account_employment aem USE INDEX(account_id)',
            'aem.account_id,
                                     @team_leader_account_id := (
                                                                CASE 
                                                                    WHEN aem.account_level_id != 4 AND (SELECT COUNT(account_id) FROM account_employment USE INDEX(account_team_id) WHERE account_team_id = aem.account_team_id AND account_department_id = aem.account_department_id AND account_status_id = 1 AND account_level_id = 4 LIMIT 1) 
                                                                        THEN (SELECT account_id FROM account_employment USE INDEX(account_team_id) WHERE account_team_id = aem.account_team_id AND account_department_id = aem.account_department_id AND account_status_id = 1 AND account_level_id = 4 LIMIT 1)
                                                                    ELSE 
                                                                        0
                                                                END
                                                                ) AS team_leader_account_id
                                    ',
            "aem.account_id = '{$account_id}'"
        );
        return $result;
    }

    public static function getActive($exclude_account_ids = "")
    {

        if (is_array($exclude_account_ids)) {
            $exclude_ids = " AND aem.account_id NOT IN (" . implode("','", $exclude_account_ids) . ") ";
        } else {
            $exclude_ids = "";
        }

        $result = mysql::select(
            'account_employment aem
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id
                                     LEFT JOIN account acc
                                     ON acc.id = aem.account_id',
            'ape.*,
                                     aem.*,
                                     aem.account_id as id,
                                     aem.email,
                                     acc.last_time_in,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name',
            " aem.account_status_id = 1 " . $exclude_ids,
            'full_name ASC'
        );
        return $result;
    }

    public static function getByFullname($full_name)
    {
        $keyword = " '%{$full_name}%' ";

        $result = mysql::select(
            'account_employment aem
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id',
            'ape.*,
                                     aem.*,
                                     aem.account_id as id,
                                     aem.email,
                                     CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, "")) AS full_name',
            "
                                    (CONCAT(ape.first_name ,' ', ape.last_name) LIKE {$keyword} OR (ape.first_name LIKE {$keyword} OR ape.last_name LIKE {$keyword} OR ape.alias LIKE {$keyword}))
                                    ",
            'full_name ASC',
            '1'
        );
        return $result;
    }

    public static function getRecordByAccountTypeId($type_id, $level_ids)
    {

        if (is_array($level_ids) && !empty($level_ids)) {
            $level_ids = " OR aem.account_level_id IN ('" . implode("','", $level_ids) . "') ";
        } else {
            $level_ids = "";
        }

        $result = mysql::select(
            'account_employment aem USE INDEX(account_id)
                                     LEFT JOIN account_personal ape
                                     ON aem.account_id = ape.account_id
                                     LEFT JOIN master_account_department mad
                                     ON aem.account_department_id = mad.id
                                     LEFT JOIN master_account_designation mai
                                     ON aem.account_designation_id = mai.id 
                                     LEFT JOIN master_account_level mal
                                     ON aem.account_level_id = mal.id 
                                     LEFT JOIN master_account_team mae
                                     ON aem.account_team_id = mae.id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id
                                     LEFT JOIN account acc
                                     ON aem.account_id = acc.id
                                    ',
            'ape.*, 
                                     aem.*, 
                                     mad.name AS account_department_name,
                                     mai.name AS account_designation_name,
                                     mal.name AS account_level_name,
                                     mae.name AS account_team_name,
                                     aem.account_id as id,
                                     aem.email,
                                     acc.transfer_leads,
                                     acc.relogin,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name,
                                     (SELECT CONCAT(COALESCE(first_name, "")," ",COALESCE(last_name, "")) FROM account_personal USE INDEX(account_id) WHERE account_id = aem.report_to LIMIT 1) AS report_to_name
                                    ',
            "aem.account_type_id = '{$type_id}' " . $level_ids
        );
        return $result;
    }

    public static function getRecordByLevelIdsAndStatusId($account_level_ids = '', $account_status_id = '')
    {

        if (!empty($account_level_ids)) {
            if (is_array($account_level_ids)) {
                $filter_level_ids = " AND aem.account_level_id IN ('" . implode("','", $account_level_ids) . "')";
            } else {
                $filter_level_ids = $account_level_ids;
            }
        } else {
            $filter_level_ids = '';
        }
        if (!empty($account_status_id)) {
            $filter_status = " AND aem.account_status_id = '{$account_status_id}'";
        } else {
            $filter_status = "";
        }

        $result = mysql::select(
            'account_employment aem
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id
                                     LEFT JOIN master_account_team mae
                                     ON aem.account_team_id = mae.id
                                    ',
            'ape.*, 
                                     aem.*, 
                                     aem.account_id as id,
                                     aem.email,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name,
                                     mae.name AS account_team_name
                                    ',
            "aem.account_id IS NOT NULL" . $filter_level_ids . $filter_status,
            'full_name ASC'
        );
        return $result;
    }

    public static function getUsersUnderReportToByAccountId($account_id)
    {
        $result = mysql::select(
            'account_employment aem
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id
                                     LEFT JOIN master_account_team mae
                                     ON aem.account_team_id = mae.id
                                    ',
            'ape.*, 
                                     aem.*, 
                                     aem.account_id as id,
                                     aem.email,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name,
                                     mae.name AS account_team_name
                                    ',
            "aem.account_status_id = 1 AND (aem.account_id = '{$account_id}' OR aem.report_to  = '{$account_id}')"
        );
        return $result;
    }

    public static function getByAccountIds($account_ids)
    {

        if (is_array($account_ids)) {
            $filter_account_id = " AND aem.account_id IN (" . implode(',', $account_ids) . ") ";;
        } else {
            $filter_account_id = " AND aem.account_id = '{$account_ids}'";
        }

        $result = mysql::select(
            'account_employment aem
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id 
                                     LEFT JOIN master_account_department mad
                                     ON aem.account_department_id = mad.id 
                                     LEFT JOIN master_account_team mae
                                     ON aem.account_team_id = mae.id 
                                     LEFT JOIN master_account_level mal
                                     ON aem.account_level_id = mal.id 
                                     LEFT JOIN master_account_designation mai
                                     ON aem.account_designation_id = mai.id 
                                     ',
            'ape.*, 
                                     aem.*, 
                                     aem.account_id as id,
                                     aem.email,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name,
                                     mad.name AS account_department_name,
                                     mae.name AS account_team_name,
                                     mal.name AS account_level_name,
                                     mai.name AS account_designation_name
                                    ',
            "aem.account_status_id = 1 " . $filter_account_id, //account_status_id = 1 : ACTIVE,
            'full_name ASC'
        );
        return $result;
    }

    public static function getEmploymentByAccountIdAndRoleId($account_id, $role_id, $status_id = '1')
    {
        $result = mysql::select(
            'account_employment aem
                                     LEFT JOIN account_personal ape
                                     ON aem.account_id = ape.account_id',
            'aem.*, 
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name
                                    ',
            "aem.account_id = '{$account_id}' AND FIND_IN_SET ('{$role_id}', REPLACE(aem.account_role_id, '-', ',')) AND aem.account_status_id = '{$status_id}'"
        ); //account_status_id = 1 : ACTIVE
        return $result;
    }

    public static function getBySalesAndEnterprise()
    {
        $result = mysql::select(
            'account_employment aem
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id 
                                     LEFT JOIN master_account_department mad
                                     ON aem.account_department_id = mad.id 
                                     LEFT JOIN master_account_team mae
                                     ON aem.account_team_id = mae.id 
                                     LEFT JOIN master_account_level mal
                                     ON aem.account_level_id = mal.id 
                                     LEFT JOIN master_account_designation mai
                                     ON aem.account_designation_id = mai.id 
                                     ',
            'ape.*, 
                                     aem.*, 
                                     aem.account_id as id,
                                     aem.email,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name,
                                     mad.name AS account_department_name,
                                     mae.name AS account_team_name,
                                     mal.name AS account_level_name,
                                     mai.name AS account_designation_name
                                    ',
            "aem.account_status_id = '1' AND (aem.account_department_id = 5 OR aem.account_team_id = 9)", //account_status_id = 1 : ACTIVE,
            "full_name ASC"
        );
        return $result;
    }

    public static function getByAccountRegionIdAndByStatusId($account_region_id, $account_status_id)
    {


        if (!empty($account_region_id) && $account_region_id != 'all') {
            $filter_region = " AND aem.account_region_id = '{$account_region_id}'";
        } else {
            $filter_region = "";
        }

        if (!empty($account_status_id) && $account_status_id != 'all') {
            $filter_status = " AND aem.account_status_id = '{$account_status_id}'";
        } else {
            $filter_status = "";
        }

        $result = mysql::select(
            'account_employment aem
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id 
                                     LEFT JOIN master_account_department mad
                                     ON aem.account_department_id = mad.id 
                                     LEFT JOIN master_account_team mae
                                     ON aem.account_team_id = mae.id 
                                     LEFT JOIN master_account_level mal
                                     ON aem.account_level_id = mal.id 
                                     LEFT JOIN master_account_designation mai
                                     ON aem.account_designation_id = mai.id 
                                     ',
            'ape.*, 
                                     aem.*, 
                                     aem.account_id as id,
                                     aem.email,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name,
                                     mad.name AS account_department_name,
                                     mae.name AS account_team_name,
                                     mal.name AS account_level_name,
                                     mai.name AS account_designation_name
                                    ',
            "aem.account_id IS NOT NULL" . $filter_region . $filter_status,
            "full_name ASC"
        );
        return $result;
    }

    public static function getRecordByDepartmentIdsAndDesignationIds($account_department_ids = '', $account_designation_ids = '', $client_appointment = '', $auto_allocate_leads = '')
    {

        if (!empty($account_department_ids)) {
            if (is_array($account_department_ids)) {
                $account_department_ids = implode("','", $account_department_ids);
            }
            $filter_department = " AND aem.account_department_id IN ('$account_department_ids')";
        } else {
            $filter_department = "";
        }

        if (!empty($account_designation_ids)) {
            if (is_array($account_designation_ids)) {
                $account_designation_ids = implode("','", $account_designation_ids);
            }
            $filter_designation = " AND aem.account_designation_id IN ('$account_designation_ids')";
        } else {
            $filter_designation = "";
        }

        $result = mysql::select(
            'account_employment aem
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id
                                     LEFT JOIN master_account_team mae
                                     ON aem.account_team_id = mae.id
                                    ',
            'ape.*, 
                                     aem.*, 
                                     aem.account_id as id,
                                     aem.email,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name,
                                     mae.name AS account_team_name
                                    ',
            "aem.account_status_id = 1 AND aem.account_region_id = 1" . $filter_department . $filter_designation . $filter_client_appointment
        );
        return $result;
    }

    public static function getSeoTvUsersByDepartmentIdsAndDesignationIds($account_department_ids, $account_designation_ids)
    {

        if (!empty($account_department_ids)) {
            if (is_array($account_department_ids)) {
                $account_department_ids = implode("','", $account_department_ids);
            }
            $filter_department = " AND aem.account_department_id IN ('$account_department_ids')";
        } else {
            $filter_department = "";
        }

        if (!empty($account_designation_ids)) {
            if (is_array($account_designation_ids)) {
                $account_designation_ids = implode("','", $account_designation_ids);
            }
            $filter_designation = " AND aem.account_designation_id IN ('$account_designation_ids')";
        } else {
            $filter_designation = "";
        }

        $result = mysql::select(
            'account_employment aem
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id
                                     LEFT JOIN master_account_team mae
                                     ON aem.account_team_id = mae.id
                                    ',
            'ape.*, 
                                     aem.*, 
                                     aem.account_id as id,
                                     aem.email,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name,
                                     mae.name AS account_team_name
                                    ',
            "aem.account_status_id = 1 AND aem.account_region_id = 1 AND FIND_IN_SET('85',REPLACE(aem.account_role_id,'-',',')) " . $filter_department . $filter_designation
        );
        return $result;
    }

    public static function getRecordByTeamId($account_team_id, $account_status_id = '')
    {

        if (!empty($account_status_id)) {
            $filter_status = " AND aem.account_status_id = '{$account_status_id}'";
        } else {
            $filter_status = "";
        }
        $result = mysql::select(
            'account_employment aem
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id',
            'ape.*, 
                                     aem.*, 
                                     aem.account_id as id,
                                     aem.email,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name',
            "aem.account_team_id = '{$account_team_id}'" . $filter_status,
            "full_name ASC"
        );
        return $result;
    }

    public static function getRecordByDesignationIdSAndTeamIds($account_designation_ids, $team_ids)
    {

        if (!empty($account_designation_ids)) {
            if (is_array($account_designation_ids)) {
                $account_designation_ids = implode("','", $account_designation_ids);
            }
            $filter_designation = " AND aem.account_designation_id IN ('{$account_designation_ids}')";
        } else {
            $filter_designation = "";
        }
        if (!empty($team_ids)) {
            if (is_array($team_ids)) {
                $team_ids = implode("','", $team_ids);
            }
            $filter_team = " AND aem.account_team_id IN ('{$team_ids}')";
        } else {
            $filter_team = "";
        }
        $result = mysql::select(
            'account_employment aem USE INDEX(account_designation_id)
                                     LEFT JOIN account_personal ape
                                     ON ape.account_id = aem.account_id
                                     LEFT JOIN master_account_type mat
                                     ON aem.account_type_id = mat.id
                                     LEFT JOIN master_account_status mas
                                     ON aem.account_status_id = mas.id',
            'ape.*, 
                                     aem.*, 
                                     aem.account_id as id,
                                     aem.email,
                                     (CASE 
                                        WHEN ape.alias = "" OR ape.alias IS NULL
                                            THEN CONCAT(COALESCE(ape.first_name, "")," ",COALESCE(ape.last_name, ""))
                                        ELSE 
                                            ape.alias
                                        END
                                     ) AS full_name,
                                     mat.name AS account_type_name,
                                     mas.name AS account_status_name',
            "aem.account_id IS NOT NULL AND aem.account_status_id = 1 " . $filter_team . $filter_designation
        );
        return $result;
    }

    public static function getActiveStatusByEmail($email)
    {
        $result = mysql::select('account_employment', '*', "email = '{$email}' AND account_status_id = 1");
        return $result;
    }
}
