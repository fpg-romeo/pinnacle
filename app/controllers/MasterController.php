<?php
class MasterController
{

    public function __construct()
    {
    }

    public function accountRole()
    {
        $data = array();
        $CONFIGURATION = Configuration::general();

        $data['record'] = Master::getDynamic('master_account_role');

        views('master.account-role', $data);
    }

    public function accountRoleJson()
    {

        if (isset($_POST) && !empty($_POST)) {

            if (!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit') {

                $record = Master::getDynamicById('master_account_role', $_POST['id']);
                if (is_array($record)) {
                    foreach ($record as $row) {
                        $result['id']          = htmlDecode($row['id']);
                        $result['name']        = htmlDecode($row['name']);
                        $result['controller']  = htmlDecode($row['controller']);
                        $result['view']        = htmlDecode($row['view']);
                        $result['description'] = htmlDecode($row['description']);
                    }
                }
            } elseif (!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete') {

                $result = Master::deleteDynamic('master_account_role', $_POST['id']);
            } else {
                $id                   = htmlEncode($_POST['id']);
                $field['name']        = htmlEncode($_POST['name']);
                $field['controller']  = strtolower(htmlEncode($_POST['controller']));
                $field['view']        = strtolower(htmlEncode($_POST['view']));
                $field['description'] = htmlEncode($_POST['description']);

                $data = checkRequiredPost(array('name'));

                if (!array_key_exists('error', $data)) {
                    if (!empty($id)) {
                        $field['id']                = $id;
                        $field['updated_by']        = ACCOUNT_ID;
                        $field['updated_when']      = dateTimeStamp();

                        $result = Master::editDynamic('master_account_role', $field);
                    } else {
                        $field['created_by']        = ACCOUNT_ID;
                        $field['created_when']      = dateTimeStamp();

                        $result = Master::addDynamic('master_account_role', $field);
                    }
                }
            }
        } else {
            $result['status']  = 'forbidden';
            $result['message'] = 'Access to this resource on the server is denied';
        }

        echo json_encode($result);
    }

    public function accountStatus()
    {
        $data = array();
        $CONFIGURATION = Configuration::general();

        $data['record'] = Master::getDynamic('master_account_status');

        views('master.account-status', $data);
    }

    public function accountStatusJson()
    {

        if (isset($_POST) && !empty($_POST)) {

            if (!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit') {

                $record = Master::getDynamicById('master_account_status', $_POST['id']);
                if (is_array($record)) {
                    foreach ($record as $row) {
                        $result['id']               = htmlDecode($row['id']);
                        $result['name']             = htmlDecode($row['name']);
                    }
                }
            } elseif (!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete') {

                $result = Master::deleteDynamic('master_account_status', $_POST['id']);
            } else {
                $id               = htmlEncode($_POST['id']);
                $field['name']    = htmlEncode($_POST['name']);

                $data = checkRequiredPost(array('name'));

                if (!array_key_exists('error', $data)) {
                    if (!empty($id)) {
                        $field['id']                = $id;
                        $field['updated_by']        = ACCOUNT_ID;
                        $field['updated_when']      = dateTimeStamp();

                        $result = Master::editDynamic('master_account_status', $field);
                    } else {
                        $field['created_by']        = ACCOUNT_ID;
                        $field['created_when']      = dateTimeStamp();

                        $result = Master::addDynamic('master_account_status', $field);
                    }
                }
            }
        } else {
            $result['status']  = 'forbidden';
            $result['message'] = 'Access to this resource on the server is denied';
        }

        echo json_encode($result);
    }

    public function accountType()
    {
        $data = array();
        $CONFIGURATION = Configuration::general();

        $data['record'] = Master::getDynamic('master_account_type');

        views('master.account-type', $data);
    }

    public function accountTypeJson()
    {

        if (isset($_POST) && !empty($_POST)) {

            if (!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit') {

                $record = Master::getDynamicById('master_account_type', $_POST['id']);
                if (is_array($record)) {
                    foreach ($record as $row) {
                        $result['id']               = htmlDecode($row['id']);
                        $result['name']             = htmlDecode($row['name']);
                    }
                }
            } elseif (!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete') {

                $result = Master::deleteDynamic('master_account_type', $_POST['id']);
            } else {
                $id               = htmlEncode($_POST['id']);
                $field['name']    = htmlEncode($_POST['name']);

                $data = checkRequiredPost(array('name'));

                if (!array_key_exists('error', $data)) {
                    if (!empty($id)) {
                        $field['id']                = $id;
                        $field['updated_by']        = ACCOUNT_ID;
                        $field['updated_when']      = dateTimeStamp();

                        $result = Master::editDynamic('master_account_type', $field);
                    } else {
                        $field['created_by']        = ACCOUNT_ID;
                        $field['created_when']      = dateTimeStamp();

                        $result = Master::addDynamic('master_account_type', $field);
                    }
                }
            }
        } else {
            $result['status']  = 'forbidden';
            $result['message'] = 'Access to this resource on the server is denied';
        }

        echo json_encode($result);
    }

    public function accountTeam()
    {
        $data = array();
        $CONFIGURATION = Configuration::general();

        $data['record'] = Master::getDynamic('master_account_team');

        views('master.account-team', $data);
    }

    public function accountTeamJson()
    {

        if (isset($_POST) && !empty($_POST)) {

            if (!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit') {

                $record = Master::getDynamicById('master_account_team', $_POST['id']);
                if (is_array($record)) {
                    foreach ($record as $row) {
                        $result['id']               = htmlDecode($row['id']);
                        $result['name']             = htmlDecode($row['name']);
                    }
                }
            } elseif (!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete') {

                $result = Master::deleteDynamic('master_account_team', $_POST['id']);
            } else {
                $id               = htmlEncode($_POST['id']);
                $field['name']    = htmlEncode($_POST['name']);

                $data = checkRequiredPost(array('name'));

                if (!array_key_exists('error', $data)) {
                    if (!empty($id)) {
                        $field['id']                = $id;
                        $field['updated_by']        = ACCOUNT_ID;
                        $field['updated_when']      = dateTimeStamp();

                        $result = Master::editDynamic('master_account_team', $field);
                    } else {
                        $field['created_by']        = ACCOUNT_ID;
                        $field['created_when']      = dateTimeStamp();

                        $result = Master::addDynamic('master_account_team', $field);
                    }
                }
            }
        } else {
            $result['status']  = 'forbidden';
            $result['message'] = 'Access to this resource on the server is denied';
        }

        echo json_encode($result);
    }

    public function accountLevel()
    {
        $data = array();
        $CONFIGURATION = Configuration::general();

        $data['record'] = Master::getDynamic('master_account_level');

        views('master.account-level', $data);
    }

    public function accountLevelJson()
    {

        if (isset($_POST) && !empty($_POST)) {

            if (!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit') {

                $record = Master::getDynamicById('master_account_level', $_POST['id']);
                if (is_array($record)) {
                    foreach ($record as $row) {
                        $result['id']               = htmlDecode($row['id']);
                        $result['name']             = htmlDecode($row['name']);
                    }
                }
            } elseif (!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete') {

                $result = Master::deleteDynamic('master_account_level', $_POST['id']);
            } else {
                $id               = htmlEncode($_POST['id']);
                $field['name']    = htmlEncode($_POST['name']);

                $data = checkRequiredPost(array('name'));

                if (!array_key_exists('error', $data)) {
                    if (!empty($id)) {
                        $field['id']                = $id;
                        $field['updated_by']        = ACCOUNT_ID;
                        $field['updated_when']      = dateTimeStamp();

                        $result = Master::editDynamic('master_account_level', $field);
                    } else {
                        $field['created_by']        = ACCOUNT_ID;
                        $field['created_when']      = dateTimeStamp();

                        $result = Master::addDynamic('master_account_level', $field);
                    }
                }
            }
        } else {
            $result['status']  = 'forbidden';
            $result['message'] = 'Access to this resource on the server is denied';
        }

        echo json_encode($result);
    }

    public function accountDepartment()
    {
        $data = array();
        $CONFIGURATION = Configuration::general();

        $data['departments']   = Master::getDynamic('master_account_department');
        $data['account_roles'] = Master::getDynamic('master_account_role');

        views('master.account-department', $data);
    }

    public function accountDepartmentJson()
    {

        if (isset($_POST) && !empty($_POST)) {

            if (!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit') {

                $record = Master::getDynamicById('master_account_department', $_POST['id']);
                if (is_array($record)) {
                    foreach ($record as $row) {
                        $result['id']               = htmlDecode($row['id']);
                        $result['name']             = htmlDecode($row['name']);
                        $result['account_role_ids'] = htmlDecode($row['account_role_ids']);
                    }
                }
            } elseif (!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete') {

                $result = Master::deleteDynamic('master_account_department', $_POST['id']);
            } else {
                $id                                     = htmlEncode($_POST['id']);
                $field['account_role_ids']              = isset($_POST['account_role_ids']) ? Shortcode::concatId($_POST['account_role_ids']) : 0;
                $field['name']                          = htmlEncode($_POST['name']);

                $data = checkRequiredPost(array('name'));

                if (!array_key_exists('error', $data)) {
                    if (!empty($id)) {
                        $field['id']                = $id;
                        $field['updated_by']        = ACCOUNT_ID;
                        $field['updated_when']      = dateTimeStamp();

                        $result = Master::editDynamic('master_account_department', $field);
                    } else {
                        $field['created_by']        = ACCOUNT_ID;
                        $field['created_when']      = dateTimeStamp();

                        $result = Master::addDynamic('master_account_department', $field);
                    }
                }
            }
        } else {
            $result['status']  = 'forbidden';
            $result['message'] = 'Access to this resource on the server is denied';
        }

        echo json_encode($result);
    }

    public function perDepartmentJson()
    {

        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $record = Master::getAccountDesignationByDepartmentId($_POST['id']);
            if (is_array($record)) {
                $result = $record;
            } else {
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
        } else {
            $result['status']  = 'forbidden';
            $result['message'] = 'Access to this resource on the server is denied';
        }

        echo json_encode($result);
    }

    public function accountDesignation()
    {
        $data = array();
        $CONFIGURATION = Configuration::general();

        $data['department']  = Master::getDynamic('master_account_department');
        $data['designation'] = Master::getAccountDesignation();

        views('master.account-designation', $data);
    }

    public function accountDesignationJson()
    {

        if (isset($_POST) && !empty($_POST)) {

            if (!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit') {

                $record = Master::getDynamicById('master_account_designation', $_POST['id']);
                if (is_array($record)) {
                    foreach ($record as $row) {
                        $result['id']               = htmlDecode($row['id']);
                        $result['department_id']    = htmlDecode($row['department_id']);
                        $result['name']             = htmlDecode($row['name']);
                    }
                }
            } elseif (!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete') {

                $result = Master::deleteDynamic('master_account_designation', $_POST['id']);
            } else {
                $id                     = htmlEncode($_POST['id']);
                $field['department_id'] = htmlEncode($_POST['department_id']);
                $field['name']          = htmlEncode($_POST['name']);

                $data = checkRequiredPost(array('name'));

                if (!array_key_exists('error', $data)) {
                    if (!empty($id)) {
                        $field['id']                = $id;
                        $field['updated_by']        = ACCOUNT_ID;
                        $field['updated_when']      = dateTimeStamp();

                        $result = Master::editAccountDesignation($field);
                    } else {
                        $field['created_by']        = ACCOUNT_ID;
                        $field['created_when']      = dateTimeStamp();

                        $result = Master::addAccountDesignation($field);
                    }
                }
            }
        } else {
            $result['status']  = 'forbidden';
            $result['message'] = 'Access to this resource on the server is denied';
        }

        echo json_encode($result);
    }

    public function teamLeader()
    {
        // $data['team_leaders']   = Master::getTeamLeader();
        $keyword                    = urldecode(getVar('keyword'));
        $status                     = getVar('status');
        $data['account_status']     = Master::getMaintenanceStatus();
        $data['team_leaders']       = Master::getallTeamLeader($status, $keyword, pagination('start'), pagination('limit')) ?: [];
        $data['total_record']       = Master::countAllTeamLeader($keyword);
        $data['total_page']         = pagination('total', $data['total_record']);


        if (isset($_POST['action'])) {
            $field['first_name']        = postVar('first_name');
            $field['middle_name']       = postVar('middle_name');
            $field['last_name']         = postVar('last_name');
            $field['suffix']            = postVar('suffix');
            $field['email']             = postVar('email');
            $field['contact_number']    = postVar('contact_number');
            $field['is_active']         = $_POST['is_active'];

            if ($_POST['action'] == 'add') {
                $insert = Master::addTeamLeader($field);
            } else if ($_POST['action'] == 'edit') {
                $id = $_POST['id'];
                $update = Master::updateTeamLeader($id, $field);
            }

            header('Location: /master/team-leader');
        }

        views('master.team-leader', $data);
    }

    public function teamLeader_json()
    {
        $result = recastArray(Master::getTeamLeaderById($_POST['id']));
        echo json_encode($result);
    }

    public function handler()
    {
        // $data['handlers']   = Master::getHandler();
        $keyword                    = urldecode(getVar('keyword'));
        $status                     = getVar('status');
        $data['account_status']     = Master::getMaintenanceStatus();
        $data['handlers']           = Master::getAllHandler($status, $keyword, pagination('start'), pagination('limit')) ?: [];
        $data['total_record']       = Master::countAllHandler($keyword);
        $data['total_page']         = pagination('total', $data['total_record']);

        if (isset($_POST['action'])) {
            $field['first_name']        = postVar('first_name');
            $field['middle_name']       = postVar('middle_name');
            $field['last_name']         = postVar('last_name');
            $field['suffix']            = postVar('suffix');
            $field['email']             = postVar('email');
            $field['contact_number']    = postVar('contact_number');
            $field['is_active']         = $_POST['is_active'];

            if ($_POST['action'] == 'add') {
                $insert = Master::addHandler($field);
            } else if ($_POST['action'] == 'edit') {
                $id = $_POST['id'];
                $update = Master::updateHandler($id, $field);
            }

            header('Location: /master/handler');
        }

        views('master.handler', $data);
    }

    public function handler_json()
    {
        $result = recastArray(Master::getHandlerById($_POST['id']));
        echo json_encode($result);
    }

    public function segment()
    {
        $keyword                    = urldecode(getVar('keyword'));
        $status                     = getVar('status');
        $data['account_status']     = Master::getMaintenanceStatus();
        $data['segments']           = Master::getAllSegment($status, $keyword, pagination('start'), pagination('limit')) ?: [];
        $data['total_record']       = Master::countAllSegment($keyword);
        $data['total_page']         = pagination('total', $data['total_record']);

        pre($status);



        if (isset($_POST['action'])) {
            $field['code']          = postVar('code');
            $field['name']          = postVar('name');
            $field['is_active']     = $_POST['is_active'];

            if ($_POST['action'] == 'add') {
                $insert = Master::addSegment($field);
            } else if ($_POST['action'] == 'edit') {
                $id = $_POST['id'];
                $update = Master::updateSegment($id, $field);
            }

            header('Location: /master/segment');
        }

        views('master.segment', $data);
    }

    public function segment_json()
    {
        $result = recastArray(Master::getSegmentById($_POST['id']));
        echo json_encode($result);
    }

    public function branch()
    {
        
        // $data['branches']   = Master::getBranch();
        $keyword                    = urldecode(getVar('keyword'));
        $status                     = getVar('status');
        $data['account_status']     = Master::getMaintenanceStatus();
        $data['branches']           = Master::getallBranch($status, $keyword, pagination('start'), pagination('limit')) ?: [];
        $data['total_record']       = Master::countAllBranch($keyword);
        $data['total_page']         = pagination('total', $data['total_record']);

        if (isset($_POST['action'])) {
            $field['code']          = postVar('code');
            $field['name']          = postVar('name');
            $field['is_active']     = $_POST['is_active'];

            if ($_POST['action'] == 'add') {
                $insert = Master::addBranch($field);
            } else if ($_POST['action'] == 'edit') {
                $id = $_POST['id'];
                $update = Master::updateBranch($id, $field);
            }
            $id = $_POST['id'];
        pre($id);
        die;

            header('Location: /master/branch');
        }

        views('master.branch', $data);
    }

    public function branch_json()
    {
        $result = recastArray(Master::getBranchById($_POST['id']));
        echo json_encode($result);
    }

    public function salesChannel()
    {
        // $data['salesChannels']   = Master::getsalesChannel();
        $keyword                    = urldecode(getVar('keyword'));
        $status                     = getVar('status');
        $data['account_status']     = Master::getMaintenanceStatus();
        $data['sales_channel']      = Master::getallSalesChannel($status, $keyword, pagination('start'), pagination('limit')) ?: [];
        $data['total_record']       = Master::countAllSalesChannel($keyword);
        $data['total_page']         = pagination('total', $data['total_record']);


        if (isset($_POST['action'])) {
            $field['name']          = postVar('name');
            $field['is_active']     = $_POST['is_active'];

            if ($_POST['action'] == 'add') {
                $insert = Master::addsalesChannel($field);
            } else if ($_POST['action'] == 'edit') {
                $id = $_POST['id'];
                $update = Master::updatesalesChannel($id, $field);
            }

            header('Location: /master/sales-channel');
        }

        views('master.sales-channel', $data);
    }

    public function salesChannel_json()
    {
        $result = recastArray(Master::getsalesChannelById($_POST['id']));
        echo json_encode($result);
    }



    public function topro()
    {
        $data = array();
        $CONFIGURATION = Configuration::general();

        // $data['record'] = Master::getTopro();

        // $data['record'] = is_array($data['record']) ? $data['record'] : array();

        $keyword                    = urldecode(getVar('keyword'));
        $status                     = getVar('status');
        $data['account_status']     = Master::getMaintenanceStatus();
        $data['record']             = Master::getallTopro($status, $keyword, pagination('start'), pagination('limit')) ?: [];
        $data['total_record']       = Master::countAllTopro($keyword);
        $data['total_page']         = pagination('total', $data['total_record']);


        views('master.topro', $data);
    }

    public function topro_json()
    {
        $data = array();

        $data['record'] = Master::syncTopro();

        if (!empty($data['record'])) {

            $delete = Master::deleteTopro();

            if ($delete['status'] == 'success') {
                $allInserted = true;
                foreach ($data['record'] as $record) {

                    $field = array();
                    $field['code'] = $record['code'];
                    $field['description'] = $record['description'];
                    $field['sync_date'] = date('Y-m-d H:i:s');
                    $field['is_active'] = $record['is_active'];

                    $insert = Master::addTopro($field);
                    if (!isset($insert['status']) || $insert['status'] !== 'success') {
                        $allInserted = false;
                    }
                }
                if ($allInserted) {
                    $result['status']  = 'success';
                    $result['message'] = 'Sync completed successfully.';
                } else {
                    $result['status']  = 'error';
                    $result['message'] = 'One or more records failed to insert.';
                }
            } else {
                $result['status']  = 'error';
                $result['message'] = 'Failed to sync data.';
            }
        } else {
            $result['status']  = 'forbidden';
            $result['message'] = 'Access to this resource on the server is denied';
        }

        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }

    public static function classBusiness()
    {
        $data = array();

        // $data['record'] = Master::getcob();

        // $data['record'] = is_array($data['record']) ? $data['record'] : array();

        $keyword                    = urldecode(getVar('keyword'));
        $status                     = getVar('status');
        $data['account_status']     = Master::getMaintenanceStatus();
        $data['record']             = Master::getAllCob($status, $keyword, pagination('start'), pagination('limit')) ?: [];
        $data['total_record']       = Master::countAllCob($keyword);
        $data['total_page']         = pagination('total', $data['total_record']);

        views('master.class-business', $data);
    }

    public function classBusiness_json()
    {
        $data = array();

        $data['record'] = Master::synccob();

        if (!empty($data['record'])) {

            $delete = Master::deletecob();

            if ($delete['status'] == 'success') {
                $allInserted = true;
                foreach ($data['record'] as $record) {

                    $field = array();
                    $field['code'] = $record['code'];
                    $field['description'] = $record['description'];
                    $field['sync_date'] = date('Y-m-d H:i:s');
                    $field['is_active'] = 1;

                    $insert = Master::addcob($field);
                    if (!isset($insert['status']) || $insert['status'] !== 'success') {
                        $allInserted = false;
                    }
                }
                if ($allInserted) {
                    $result['status']  = 'success';
                    $result['message'] = 'Sync completed successfully.';
                } else {
                    $result['status']  = 'error';
                    $result['message'] = 'One or more records failed to insert.';
                }
            } else {
                $result['status']  = 'error';
                $result['message'] = 'Failed to sync data.';
            }
        } else {
            $result['status']  = 'forbidden';
            $result['message'] = 'Access to this resource on the server is denied';
        }

        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }

    public function intermediary()
    {
        $data = array();
        $CONFIGURATION = Configuration::general();

        // $data['record'] = Master::getIntermediary();
        $keyword                    = urldecode(getVar('keyword'));
        $status                     = getVar('status');
        $data['account_status']     = Master::getMaintenanceStatus();
        $data['record']             = Master::getallIntermediary($status, $keyword, pagination('start'), pagination('limit')) ?: [];
        $data['total_record']       = Master::countAllIntermediary($keyword);
        $data['total_page']         = pagination('total', $data['total_record']);

        views('master.intermediary', $data);
    }

    public function intermediary_json()
    {
        if (isset($_POST) && $_POST['action'] === 'sync') {
            $data = array();

            $data['record'] = Master::syncIntermediary();

            if (!empty($data['record'])) {

                $delete = Master::deleteIntermediary();

                if ($delete['status'] == 'success') {
                    $allInserted = true;
                    foreach ($data['record'] as $record) {

                        $field = array();
                        $field['source_name'] = $record['source_name'];
                        $field['address'] = $record['ADDRESS'];
                        $field['created_at'] = date('Y-m-d H:i:s');
                        $field['is_active'] = 1;

                        $insert = Master::addIntermediary($field);
                        if (!isset($insert['status']) || $insert['status'] !== 'success') {
                            $allInserted = false;
                        }
                    }
                    if ($allInserted) {
                        $result['status']  = 'success';
                        $result['message'] = 'Sync completed successfully.';
                    } else {
                        $result['status']  = 'error';
                        $result['message'] = 'One or more records failed to insert.';
                    }
                } else {
                    $result['status']  = 'error';
                    $result['message'] = 'Failed to sync data.';
                }
            } else {
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
        } elseif (isset($_POST) && $_POST['action'] === 'edit') {

            $data = array();
            $id = htmlEncode($_POST['id']);

            $data['record'] = Master::getIntermediaryById($id);


            if (is_array($data['record']) && !empty($data['record'])) {
                $result['id'] = $data['record'][0]['id'];
                $result['sourcename'] = $data['record'][0]['source_name'];
                $result['address'] = $data['record'][0]['address'];
                $result['is_active'] = $data['record'][0]['is_active'];
                $result['category'] = $data['record'][0]['categories'];
            }
        } elseif (isset($_POST) && $_POST['action'] === 'save') {
            $data = array();

            $data['id'] = htmlEncode($_POST['id']);
            $data['source_name'] = htmlEncode($_POST['sourcename']);
            $data['address'] = htmlEncode($_POST['address']);
            $data['categories'] = isset($_POST['category']) && $_POST['category'] !== '' ? json_encode([htmlEncode($_POST['category'])])  : '[]';
            $data['is_active'] = ($_POST['is_active'] === '1') ? 1 : 0;
            $data['updated_at'] = date('Y-m-d H:i:s');

            $result = Master::updateIntermediary($data['id'], $data);
            if ($result['status'] == 'success') {
                $result['message'] = 'Intermediary updated successfully.';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result);

        exit;
    }

    
}
