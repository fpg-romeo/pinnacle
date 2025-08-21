<?php
class Master
{

    public function __construct() {}

    public static function getDynamic($table, $sort = ' name ASC ')
    {
        $result = mysql::select($table, '*', '', $sort);
        return $result;
    }

    public static function getDynamicById($table, $id, $sort = ' name ASC ')
    {
        $result = mysql::select($table, '*', "id = '{$id}'", $sort);
        return $result;
    }

    public static function getDynamicByName($table, $name)
    {
        $result = mysql::select($table, '*', "name = '{$name}'");
        return $result;
    }

    public static function getDynamicByCode($table, $code)
    {
        $result = mysql::select($table, '*', "code = '{$code}'");
        return $result;
    }

    public static function getDynamicByIds($table, $ids, $columns = '')
    {

        if (is_array($ids)) {
            $list = trim(implode("','", $ids));
        } else {
            $list = trim($ids);
        }

        if (is_array($columns) && !empty($columns)) {
            $columns = trim(implode("','", $columns));
        } else {
            $columns = '*';
        }

        $result = mysql::select($table, $columns, "id IN ('{$list}')");
        return $result;
    }

    public static function getDynamicIn($table, $column, $value, $sort = ' name ASC ')
    {

        if (is_array($value)) {
            $list = trim(implode("','", $value));
        } else {
            $list = trim($value);
        }

        $result = mysql::select($table, '*', "{$column} IN ('{$list}')", $sort);
        return $result;
    }

    public static function getDynamicNotIn($table, $column, $value, $sort = ' name ASC ')
    {

        if (is_array($value)) {
            $list = trim(implode("','", $value));
        } else {
            $list = trim($value);
        }

        $result = mysql::select($table, '*', "{$column} NOT IN ('{$list}')", $sort);
        return $result;
    }

    public static function addDynamic($table, $post)
    {
        // If $post is just the ID, wrap it in an array
        if (!is_array($post)) {
            $post = ['id' => $post];
        }

        if (!isset($post['id'])) {
            return [
                'status'  => 'failed',
                'message' => 'No ID provided'
            ];
        }
        $name   = $post['name'];
        $record = self::getDynamicByName($table, $name);

        if (!is_array($record)) {
            $fields = mysql::buildFields($post, ", ");
            if (mysql::insert($table, $fields)) {
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

    public static function editDynamic($table, $post)
    {
        $id     = $post['id'];
        $record = self::getDynamicById($table, $id);

        if (is_array($record)) {
            $fields = mysql::buildFields($post, ", ");
            if (mysql::update($table, $fields, "id = '{$id}'")) {
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

    public static function deleteDynamic($table, $id)
    {
        $record = self::getDynamicById($table, $id);

        if (is_array($record)) {
            if (mysql::delete($table, "id = '{$id}'")) {
                $result['status']   = 'success';
                $result['message']  = 'Record Successfully Deleted';
                $result['record']   = recastArray($record);
            } else {
                $result['status']   = 'failed';
                $result['message']  = 'Encounter technical error. Pls try again';
            }
        } else {
            $result['status']  = 'failed';
            $result['message'] = 'No Record Found';
        }


        return $result;
    }

    public static function getAccountDepartment()
    {
        $result = mysql::select(
            'master_account_department mad',
            'mad.*',
            "",
            'mad.name ASC'
        );
        return $result;
    }

    public static function getAccountType()
    {
        $result = mysql::select(
            'master_account_type mat',
            'mat.*',
            "",
            'mat.name ASC'
        );
        return $result;
    }

    public static function getAccountTeam()
    {
        $result = mysql::select(
            'master_account_team mat',
            'mat.*',
            "",
            'mat.name ASC'
        );
        return $result;
    }

    public static function getAccountLevel()
    {
        $result = mysql::select(
            'master_account_level mal',
            'mal.*',
            "",
            'mal.name ASC'
        );
        return $result;
    }

    public static function getAccountRoleMaster()
    {
        $result = mysql::select(
            'master_account_role mar',
            'mar.*',
            "",
            'mar.name ASC'
        );
        return $result;
    }

    public static function getAccountStatus()
    {
        $result = mysql::select(
            'master_account_status mas',
            'mas.*',
            "",
            'mas.name ASC'
        );
        return $result;
    }

    public static function getAccountDesignation()
    {
        $result = mysql::select(
            'master_account_designation mad
                                     LEFT JOIN master_account_department mae
                                     ON mad.department_id = mae.id',
            'mad.*,
                                     mae.name AS department_name',
            "",
            'mae.name ASC, mad.name ASC'
        );
        return $result;
    }

    public static function getAccountDesignationByDepartmentId($department_id)
    {
        $result = mysql::select(
            'master_account_designation mad  
                                     LEFT JOIN master_account_department mae
                                     ON mad.department_id = mae.id',
            'mad.*,
                                     mae.name AS department_name',
            "mad.department_id = '{$department_id}'",
            'mae.name ASC, mad.name ASC'
        );
        return $result;
    }

    public static function getAccountDesignationById($id)
    {
        $result = mysql::select('master_account_designation', '*', "id = '{$id}'");
        return $result;
    }

    public static function getAccountDesignationByName($name)
    {
        $result = mysql::select('master_account_designation', '*', "name = '{$name}'");
        return $result;
    }

    public static function getAccountDesignationByDepartmentIdAndName($department_id, $name)
    {
        $result = mysql::select('master_account_designation', '*', "department_id = '{$department_id}' AND name = '{$name}'");
        return $result;
    }

    public static function addAccountDesignation($post)
    {
        $department_id = $post['department_id'];
        $name          = $post['name'];
        $record        = self::getAccountDesignationByDepartmentIdAndName($department_id, $name);

        if (!is_array($record)) {
            $fields = mysql::buildFields($post, ", ");
            if (mysql::insert('master_account_designation', $fields)) {
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

    public static function editAccountDesignation($post)
    {
        $id     = $post['id'];
        $record = self::getAccountDesignationById($id);

        if (is_array($record)) {
            $department_id = $post['department_id'];
            $name          = $post['name'];
            $data          = self::getAccountDesignationByDepartmentIdAndName($department_id, $name);
            if (!is_array($data)) {
                $fields = mysql::buildFields($post, ", ");
                if (mysql::update('master_account_designation', $fields, "id = '{$id}'")) {
                    $result['status']  = 'success';
                    $result['message'] = 'Record Successfully Updated';
                    $result['id']      = $id;
                } else {
                    $result['status']  = 'failed';
                    $result['message'] = 'Encounter technical error. Pls try again';
                }
            } else {
                $result['status']  = 'failed';
                $result['message'] = 'Name already used';
            }
            return $result;        
        }
    }

        public static function getAccountRole($controller, $view){     
            $result = mysql::select('master_account_role', '*', 
                                    "controller = '{$controller}' AND FIND_IN_SET ('{$view}', view)", 
                                    'name ASC');        
            return $result;
        }

        public static function getTeamLeader(){
            $result = mysql::select('master_team_leader', '*', 
                                    "", 
                                    '');        
            return $result;
        }

        public static function getTeamLeaderById($id){
            $result = mysql::select('master_team_leader', '*', 
                                    'id='.$id, 
                                    '');        
            return $result;
        }

        public static function getAllTeamLeader($keyword='',$start='', $limit=''){
            if(!empty(trim($keyword))){
                $keyword = " '%{$keyword}%' ";
                $filter  = "
                              
                             (
                                 first_name LIKE {$keyword}
                                 OR
                                 last_name LIKE {$keyword}
                                 OR
                                 email LIKE {$keyword}
                                 OR
                                 contact_number LIKE {$keyword}
                             )
                          ";
            }else{
                $filter = '';
            }
            $startLimit = (trim($start) != "" && trim($limit) != "") ? $start.', '.$limit : ''; 
            $result = mysql::select('master_team_leader', 
                                    '*',
                                    $filter,
                                    'id DESC',$startLimit);
            return $result;
        }

         public static function countAllTeamLeader($keyword=''){
            if(!empty(trim($keyword))){
                $keyword = " '%{$keyword}%' ";
                $filter  = "
                              
                             (
                                first_name LIKE {$keyword}
                                 OR
                                 last_name LIKE {$keyword}
                                 OR
                                 email LIKE {$keyword}
                                 OR
                                 contact_number LIKE {$keyword}
                             )
                          ";
            }else{
                $filter = '';
            }
            $result = mysql::select('master_team_leader', 
                                    'COUNT(*) as count',
                                    $filter);
            if(is_array($result)){
                return recastArray($result)['count'];
            }else{
                return 0;
            } 
        } 

        public static function addTeamLeader($field){

            $fields = mysql::buildFields($field, ", ");
            if(mysql::insert('master_team_leader', $fields)){
                $result['status']  = 'success';
                $result['message'] = 'New Record Saved';
                $result['id']      = mysql::insertedId();
            }else{
                $result['status']  = 'failed';
                $result['message'] = 'Encounter technical error. Pls try again';
            }
            return $result;
        }

        public static function updateTeamLeader($id, $post){
            $record = self::getTeamLeaderById($id);
            if(is_array($record)){  
                $fields = mysql::buildFields($post, ", ");
                if(mysql::update('master_team_leader', $fields, 'id='.$id)){
                    $result['status']  = 'success';
                    $result['message'] = 'Record Successfully Updated';
                }else{
                    $result['status']  = 'failed';
                    $result['message'] = 'Encounter technical error. Pls try again';
                }
            }else{
                $result['status']  = 'failed';
                $result['message'] = 'Record does not exist';
            }
            return $result;
        }

        public static function getHandler(){
            $result = mysql::select('master_handler', '*', 
                                    "", 
                                    '');        
            return $result;
        }

        public static function getHandlerById($id){
            $result = mysql::select('master_handler', '*', 
                                    'id='.$id, 
                                    '');        
            return $result;
        }

          public static function getAllHandler($keyword='',$start='', $limit=''){
            if(!empty(trim($keyword))){
                $keyword = " '%{$keyword}%' ";
                $filter  = "
                              
                             (
                                 first_name LIKE {$keyword}
                                 OR
                                 last_name LIKE {$keyword}
                                 OR
                                 email LIKE {$keyword}
                                 OR
                                 contact_number LIKE {$keyword}
                             )
                          ";
            }else{
                $filter = '';
            }
            $startLimit = (trim($start) != "" && trim($limit) != "") ? $start.', '.$limit : ''; 
            $result = mysql::select('master_handler', 
                                    '*',
                                    $filter,
                                    'id DESC',$startLimit);
            return $result;
        }

         public static function countAllHandler($keyword=''){
            if(!empty(trim($keyword))){
                $keyword = " '%{$keyword}%' ";
                $filter  = "
                              
                             (
                                first_name LIKE {$keyword}
                                 OR
                                 last_name LIKE {$keyword}
                                 OR
                                 email LIKE {$keyword}
                                 OR
                                 contact_number LIKE {$keyword}
                             )
                          ";
            }else{
                $filter = '';
            }
            $result = mysql::select('master_handler', 
                                    'COUNT(*) as count',
                                    $filter);
            if(is_array($result)){
                return recastArray($result)['count'];
            }else{
                return 0;
            } 
        } 

        public static function addHandler($field){

            $fields = mysql::buildFields($field, ", ");
            if(mysql::insert('master_handler', $fields)){
                $result['status']  = 'success';
                $result['message'] = 'New Record Saved';
                $result['id']      = mysql::insertedId();
            }else{
                $result['status']  = 'failed';
                $result['message'] = 'Encounter technical error. Pls try again';
            }
            return $result;
        }

        public static function updateHandler($id, $post){
            $record = self::getHandlerById($id);
            if(is_array($record)){  
                $fields = mysql::buildFields($post, ", ");
                if(mysql::update('master_handler', $fields, 'id='.$id)){
                    $result['status']  = 'success';
                    $result['message'] = 'Record Successfully Updated';
                }else{
                    $result['status']  = 'failed';
                    $result['message'] = 'Encounter technical error. Pls try again';
                }
            }else{
                $result['status']  = 'failed';
                $result['message'] = 'Record does not exist';
            }
            return $result;
        }

        public static function getSegment(){
            $result = mysql::select('master_segment', '*', 
                                    "", 
                                    '');        
            return $result;
        }

        public static function getSegmentById($id){
            $result = mysql::select('master_segment', '*', 
                                    'id='.$id, 
                                    '');        
            return $result;
        }

         public static function getAllSegment($keyword='',$start='', $limit=''){
            if(!empty(trim($keyword))){
                $keyword = " '%{$keyword}%' ";
                $filter  = "
                              
                             (
                                 code LIKE {$keyword}
                                 OR
                                 name LIKE {$keyword}
                                 OR
                                 is_active LIKE {$keyword}
                             )
                          ";
            }else{
                $filter = '';
            }
            $startLimit = (trim($start) != "" && trim($limit) != "") ? $start.', '.$limit : ''; 
            $result = mysql::select('master_segment', 
                                    '*',
                                    $filter,
                                    'id DESC',$startLimit);
            return $result;
        }

         public static function countAllSegment($keyword=''){
            if(!empty(trim($keyword))){
                $keyword = " '%{$keyword}%' ";
                $filter  = "
                              
                             (
                                 code LIKE {$keyword}
                                 OR
                                 name LIKE {$keyword}
                                 OR
                                 is_active LIKE {$keyword}
                             )
                          ";
            }else{
                $filter = '';
            }
            $result = mysql::select('master_segment', 
                                    'COUNT(*) as count',
                                    $filter);
            if(is_array($result)){
                return recastArray($result)['count'];
            }else{
                return 0;
            } 
        } 

        public static function addSegment($field){

            $fields = mysql::buildFields($field, ", ");
            if(mysql::insert('master_segment', $fields)){
                $result['status']  = 'success';
                $result['message'] = 'New Record Saved';
                $result['id']      = mysql::insertedId();
            }else{
                $result['status']  = 'failed';
                $result['message'] = 'Encounter technical error. Pls try again';
            }
            return $result;
        }

        public static function updateSegment($id, $post){
            $record = self::getSegmentById($id);
            if(is_array($record)){  
                $fields = mysql::buildFields($post, ", ");
                if(mysql::update('master_segment', $fields, 'id='.$id)){
                    $result['status']  = 'success';
                    $result['message'] = 'Record Successfully Updated';
                }else{
                    $result['status']  = 'failed';
                    $result['message'] = 'Encounter technical error. Pls try again';
                }
            }else{
                $result['status']  = 'failed';
                $result['message'] = 'Record does not exist';
            }
            return $result;
        }

        public static function getBranch(){
            $result = mysql::select('master_branch', '*', 
                                    "", 
                                    '');        
            return $result;
        }

        public static function getBranchById($id){
            $result = mysql::select('master_branch', '*', 
                                    'id='.$id, 
                                    '');        
            return $result;
        }

        public static function getAllBranch($keyword='',$start='', $limit=''){
            if(!empty(trim($keyword))){
                $keyword = " '%{$keyword}%' ";
                $filter  = "
                              
                             (
                                 code LIKE {$keyword}
                                 OR
                                 name LIKE {$keyword}
                                 OR
                                 is_active LIKE {$keyword}
                             )
                          ";
            }else{
                $filter = '';
            }
             $startLimit = (trim($start) != "" && trim($limit) != "") ? $start.', '.$limit : ''; 
            $result = mysql::select('master_branch', 
                                    '*',
                                    $filter,
                                    'id DESC',$startLimit);
            return $result;
        }

        public static function countAllBranch($keyword=''){
            if(!empty(trim($keyword))){
                $keyword = " '%{$keyword}%' ";
                $filter  = "
                              
                             (
                                 code LIKE {$keyword}
                                 OR
                                 name LIKE {$keyword}
                                 OR
                                 is_active LIKE {$keyword}
                             )
                          ";
            }else{
                $filter = '';
            }
            $result = mysql::select('master_branch', 
                                    'COUNT(*) as count',
                                    $filter);
            if(is_array($result)){
                return recastArray($result)['count'];
            }else{
                return 0;
            } 
        } 

        public static function addBranch($field){

            $fields = mysql::buildFields($field, ", ");
            if(mysql::insert('master_branch', $fields)){
                $result['status']  = 'success';
                $result['message'] = 'New Record Saved';
                $result['id']      = mysql::insertedId();
            }else{
                $result['status']  = 'failed';
                $result['message'] = 'Encounter technical error. Pls try again';
            }
            return $result;
        }

        public static function updateBranch($id, $post){
            $record = self::getBranchById($id);
            if(is_array($record)){  
                $fields = mysql::buildFields($post, ", ");
                if(mysql::update('master_branch', $fields, 'id='.$id)){
                    $result['status']  = 'success';
                    $result['message'] = 'Record Successfully Updated';
                }else{
                    $result['status']  = 'failed';
                    $result['message'] = 'Encounter technical error. Pls try again';
                }
            }else{
                $result['status']  = 'failed';
                $result['message'] = 'Record does not exist';
            }
            return $result;
        }
        public static function getSalesChannel(){
            $result = mysql::select('master_sales_channel', '*', 
                                    "", 
                                    '');        
            return $result;
        }

        public static function getSalesChannelById($id){
            $result = mysql::select('master_sales_channel', '*', 
                                    'id='.$id, 
                                    '');        
            return $result;
        }

        public static function getAllSalesChannel($keyword='',$start='', $limit=''){
            if(!empty(trim($keyword))){
                $keyword = " '%{$keyword}%' ";
                $filter  = "
                              
                             (
                                 name LIKE {$keyword}
                             )
                          ";
            }else{
                $filter = '';
            }
            $startLimit = (trim($start) != "" && trim($limit) != "") ? $start.', '.$limit : ''; 
            $result = mysql::select('master_sales_channel', 
                                    '*',
                                    $filter,
                                    'id DESC',$startLimit);
            return $result;
        }

         public static function countAllSalesChannel($keyword=''){
            if(!empty(trim($keyword))){
                $keyword = " '%{$keyword}%' ";
                $filter  = "
                              
                             (
                                name LIKE {$keyword}
                             )
                          ";
            }else{
                $filter = '';
            }
            $result = mysql::select('master_sales_channel', 
                                    'COUNT(*) as count',
                                    $filter);
            if(is_array($result)){
                return recastArray($result)['count'];
            }else{
                return 0;
            } 
        } 

        public static function addSalesChannel($field){

            $fields = mysql::buildFields($field, ", ");
            if(mysql::insert('master_sales_channel', $fields)){
                $result['status']  = 'success';
                $result['message'] = 'New Record Saved';
                $result['id']      = mysql::insertedId();
            }else{
                $result['status']  = 'failed';
                $result['message'] = 'Encounter technical error. Pls try again';
            }
            return $result;
        }

        public static function updateSalesChannel($id, $post){
            $record = self::getSalesChannelById($id);
            if(is_array($record)){  
                $fields = mysql::buildFields($post, ", ");
                if(mysql::update('master_sales_channel', $fields, 'id='.$id)){
                    $result['status']  = 'success';
                    $result['message'] = 'Record Successfully Updated';
                }else{
                    $result['status']  = 'failed';
                    $result['message'] = 'Encounter technical error. Pls try again';
                }
            }else{
                $result['status']  = 'failed';
                $result['message'] = 'Record does not exist';
            }
            return $result;
        }

        public static function syncTopro(){
            $result = SqlServer::select('topro', 'topro as code,description,AllowedF as is_active', "AllowedF = 1", 'description ASC');
            return $result;
        }

        public static function getTopro(){
            $result = mysql::select('master_topro', '*', "", 'description ASC');
            return $result;
        }

         public static function getAllTopro($keyword='',$start='', $limit=''){
            if(!empty(trim($keyword))){
                $keyword = " '%{$keyword}%' ";
                $filter  = "
                              
                             (
                                 code LIKE {$keyword}
                                 OR
                                 description LIKE {$keyword}
                             )
                          ";
            }else{
                $filter = '';
            }
             $startLimit = (trim($start) != "" && trim($limit) != "") ? $start.', '.$limit : ''; 
            $result = mysql::select('master_topro', 
                                    '*',
                                    $filter,
                                    'id DESC',$startLimit);
            return $result;
        }

        public static function countAllTopro($keyword=''){
            if(!empty(trim($keyword))){
                $keyword = " '%{$keyword}%' ";
                $filter  = "
                              
                             (
                                 code LIKE {$keyword}
                                 OR
                                 description LIKE {$keyword}
                             )
                          ";
            }else{
                $filter = '';
            }
            $result = mysql::select('master_topro', 
                                    'COUNT(*) as count',
                                    $filter);
            if(is_array($result)){
                return recastArray($result)['count'];
            }else{
                return 0;
            } 
        } 

        public static function addTopro($fields){

            $fields = MySql::buildFields($fields, ", ");
                if (MySql::insert('master_topro', $fields)) {
                    $result['status']  = 'success';
                    $result['message'] = 'New Record Saved';
                    $result['id']      = MySql::insertedId();
                } else {
                    $result['status']  = 'failed';
                    $result['message'] = 'Encounter technical error. Pls try again';
                }
                return $result;
        }

        public static function deleteTopro(){

                if (MySql::delete('master_topro')) {
                    $result['status']  = 'success';
                    $result['message'] = 'New Record Saved';
                } else {
                    $result['status']  = 'failed';
                    $result['message'] = 'Encounter technical error. Pls try again';
                }
                return $result;
        }

        public static function syncCob(){
            $result = SqlServer::select('cob', 'cob as code,description', "", 'description ASC');
            return $result;
        }

        public static function getCob(){
            $result = mysql::select('master_class_of_business', '*', "", 'description ASC');
            return $result;
        }

        public static function getAllCob($keyword='',$start='', $limit=''){
            if(!empty(trim($keyword))){
                $keyword = " '%{$keyword}%' ";
                $filter  = "
                              
                             (
                                 code LIKE {$keyword}
                                 OR
                                 description LIKE {$keyword}
                             )
                          ";
            }else{
                $filter = '';
            }
             $startLimit = (trim($start) != "" && trim($limit) != "") ? $start.', '.$limit : ''; 
            $result = mysql::select('master_class_of_business', 
                                    '*',
                                    $filter,
                                    'id DESC',$startLimit);
            return $result;
        }

        public static function countAllCob($keyword=''){
            if(!empty(trim($keyword))){
                $keyword = " '%{$keyword}%' ";
                $filter  = "
                              
                             (
                                 code LIKE {$keyword}
                                 OR
                                 description LIKE {$keyword}
                             )
                          ";
            }else{
                $filter = '';
            }
            $result = mysql::select('master_class_of_business', 
                                    'COUNT(*) as count',
                                    $filter);
            if(is_array($result)){
                return recastArray($result)['count'];
            }else{
                return 0;
            } 
        } 

        public static function addcob($fields){

            $fields = MySql::buildFields($fields, ", ");
                if (MySql::insert('master_class_of_business', $fields)) {
                    $result['status']  = 'success';
                    $result['message'] = 'New Record Saved';
                    $result['id']      = MySql::insertedId();
                } else {
                    $result['status']  = 'failed';
                    $result['message'] = 'Encounter technical error. Pls try again';
                }
                return $result;
        }

        public static function deletecob(){

                if (MySql::delete('master_class_of_business')) {
                    $result['status']  = 'success';
                    $result['message'] = 'New Record Saved';
                } else {
                    $result['status']  = 'failed';
                    $result['message'] = 'Encounter technical error. Pls try again';
                }
                return $result;
        }

        public static function getIntermediary(){
            $result = mysql::select('master_intermediaries', '*', "", 'source_name ASC');
            return $result;
        }
        
        public static function getIntermediaryById($id){
            $result = mysql::select('master_intermediaries', '*', 'id='.$id, '');
            return $result;
        }

        public static function getAllIntermediary($keyword='',$start='', $limit=''){
            if(!empty(trim($keyword))){
                $keyword = " '%{$keyword}%' ";
                $filter  = "
                              
                             (
                                 source_name LIKE {$keyword}
                                 OR
                                 created_at LIKE {$keyword}
                             )
                          ";
            }else{
                $filter = '';
            }
            $startLimit = (trim($start) != "" && trim($limit) != "") ? $start.', '.$limit : ''; 
            $result = mysql::select('master_intermediaries', 
                                    '*',
                                    $filter,
                                    'id DESC',$startLimit);
            return $result;
        }

         public static function countAllIntermediary($keyword=''){
            if(!empty(trim($keyword))){
                $keyword = " '%{$keyword}%' ";
                $filter  = "
                              
                             (
                                 source_name LIKE {$keyword}
                                 OR
                                 created_at LIKE {$keyword}
                             )
                          ";
            }else{
                $filter = '';
            }
            $result = mysql::select('master_intermediaries', 
                                    'COUNT(*) as count',
                                    $filter);
            if(is_array($result)){
                return recastArray($result)['count'];
            }else{
                return 0;
            } 
        } 

        public static function addIntermediary($fields){

            $fields = MySql::buildFields($fields, ", ");
                if (MySql::insert('master_intermediaries', $fields)) {
                    $result['status']  = 'success';
                    $result['message'] = 'New Record Saved';
                    $result['id']      = MySql::insertedId();
                } else {
                    $result['status']  = 'failed';
                    $result['message'] = 'Encounter technical error. Pls try again';
                }
                return $result;
        }

        public static function deleteIntermediary(){

                if (MySql::delete('master_intermediaries')) {
                    $result['status']  = 'success';
                    $result['message'] = 'New Record Saved';
                } else {
                    $result['status']  = 'failed';
                    $result['message'] = 'Encounter technical error. Pls try again';
                }
                return $result;
        }

        public static function syncIntermediary(){
            $result = SqlServer::select('profile', "top 10  name as source_name,address_1 + ' ' + address_2 + ' ' + address_3 as ADDRESS", "ptype = 'M' and restrictedf = 0", 'name ASC');
            return $result;
        }

        public static function updateIntermediary($id,$fields){
            $record = self::getIntermediaryById($id);
            if(is_array($record)){  
                $fields = mysql::buildFields($fields, ", ");
                if(mysql::update('master_intermediaries', $fields, 'id='.$id)){
                    $result['status']  = 'success';
                    $result['message'] = 'Record Successfully Updated';
                }else{
                    $result['status']  = 'failed';
                    $result['message'] = 'Encounter technical error. Pls try again';
                }
            }else{
                $result['status']  = 'failed';
                $result['message'] = 'Record does not exist';
            }
            return $result;
        }
        
        public static function getActiveIntermediary(){
            $result = mysql::select('master_intermediaries', '*', "is_active = 1 AND categories != ''", 'source_name ASC');
            return $result;
        }
        public static function getActiveBranches(){
            $result = mysql::select('master_branch', '*', "is_active = 1", 'name ASC');
            return $result;
        }
        public static function getActiveSegments(){
            $result = mysql::select('master_segment', '*', "is_active = 1", '');
            return $result;
        }
        public static function getActiveSalesChannels(){
            $result = mysql::select('master_sales_channel', '*', "is_active = 1", '');
            return $result;
        }
        public static function getActiveTOPROs(){
            $result = mysql::select('master_topro', '*', "is_active = 1", '');
            return $result;
        }
        public static function getActiveCOBs(){
            $result = mysql::select('master_class_of_business', '*', "is_active = 1", '');
            return $result;
        }
        public static function getActiveHandlers(){
            $result = mysql::select('master_handler', '*', "is_active = 1", '');
            return $result;
        }
        public static function getActiveTeamLeaders(){
            $result = mysql::select('master_team_leader', '*', "is_active = 1", '');
            return $result;
        }

        public static function syncSoa(){

            set_time_limit(0);
            $result = SQLServer::select('soa_v3', 'top 2 *',);
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

        public static function addJobQueue($fields){
            
            $insert = mysql::buildfields($fields, ", ");
            if(mysql::insert('jobs', $insert)){
                $result['status']  = 'success';
                $result['message'] = 'New Record Saved';
                $result['id']      = mysql::insertedId();
            }
            return $result;
        
        }
}

    
      