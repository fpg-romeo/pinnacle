<?php
class Finance
{

    public function __construct() {}

    public static function getAllMasterlists($keyword='',$start='', $limit='')
    {
        if(!empty(trim($keyword))){
                $keyword = " '%{$keyword}%' ";
                $filter  = "
                              
                             (
                                 mint.source_name LIKE {$keyword} OR
                                 mha.first_name LIKE {$keyword} OR
                                 mha.last_name LIKE {$keyword} OR
                                 mtl.first_name LIKE {$keyword} OR
                                 mtl.last_name LIKE {$keyword}
                             )
                          ";
            }else{
                $filter = '';
            }
            $startLimit = (trim($start) != "" && trim($limit) != "") ? $start.', '.$limit : ''; 
            $result = mysql::select('soa_masterlist sml 
                                    INNER JOIN master_intermediaries mint ON mint.id = sml.intermediary_id
                                    INNER JOIN master_handler mha ON mha.id = sml.handler_id
                                    INNER JOIN master_team_leader mtl ON mtl.id = sml.team_leader_id
                                    ',
                                    'sml.*, mint.source_name, CONCAT(mha.first_name, " ", mha.last_name) as handler, CONCAT(mtl.first_name, " ", mtl.last_name) as team_leader',
                                    $filter,
                                    'sml.id',$startLimit);
            return $result;
    }

    public static function countAllMasterlist($keyword='')
    {
        if(!empty(trim($keyword))){
            $keyword = " '%{$keyword}%' ";
            $filter  = "
                            
                            (
                                mint.source_name LIKE {$keyword} OR
                                mha.first_name LIKE {$keyword} OR
                                mha.last_name LIKE {$keyword} OR
                                mtl.first_name LIKE {$keyword} OR
                                mtl.last_name LIKE {$keyword}
                            )
                        ";
        }else{
            $filter = '';
        }

        $result = mysql::select('soa_masterlist sml 
                                 INNER JOIN master_intermediaries mint ON mint.id = sml.intermediary_id
                                 INNER JOIN master_handler mha ON mha.id = sml.handler_id
                                 INNER JOIN master_team_leader mtl ON mtl.id = sml.team_leader_id
                                ',
                                'COUNT(*) as count',
                                $filter,
                                'sml.id');
        if(is_array($result)){
            return recastArray($result)['count'];
        }else{
            return 0;
        }
    }

    public static function addMasterList($post){
        $fields = mysql::buildFields($post['master_list'], ", ");
        if(mysql::insert('soa_masterlist', $fields)){
            $result['status']  = 'success';
            $result['message'] = 'New Record Saved';

            $id    = mysql::insertedId();

            if($post['branch'] != ""){
                $branch['master_list_id']    = $id;
                $branch['created_at']        = date('Y-m-d H:i:s');
                $branch['branch_id'] = $post['branch'];
                $result['branch'] = self::addMasterlistPivot($branch, 'branch_master_list_pivot');
            }

            if($post['segment'] != ""){
                $segment['master_list_id']    = $id;
                $segment['created_at']        = date('Y-m-d H:i:s');
                $segment['segment_id'] = $post['segment'];
                $result['segment'] = self::addMasterlistPivot($segment, 'soa_segment_master_list_pivot');
            }

            if($post['class_of_business'] != ""){
                $class_ob_business['master_list_id']    = $id;
                $class_ob_business['created_at']        = date('Y-m-d H:i:s');
                $class_ob_business['class_of_business_id'] = $post['class_of_business'];
                $result['class_of_business'] = self::addMasterlistPivot($class_ob_business, 'cob_master_list_pivot');
            }

            if($post['sales_channel'] != ""){
                $sales_channel['master_list_id']    = $id;
                $sales_channel['created_at']        = date('Y-m-d H:i:s');
                $sales_channel['sales_channel_id'] = $post['sales_channel'];
                $result['sales_channel'] = self::addMasterlistPivot($sales_channel, 'soa_sales_channel_master_list_pivot');
            }

            if($post['topro'] != ""){
                $topro['master_list_id']    = $id;
                $topro['created_at']        = date('Y-m-d H:i:s');
                $topro['topro_id'] = $post['topro'];
                $result['topro'] = self::addMasterlistPivot($topro, 'soa_topro_master_list_pivot');
            }

            if($post['soa_recipients'] != ""){
                $soa_recipients['master_list_id']    = $id;
                $soa_recipients['created_at']        = date('Y-m-d H:i:s');
                $soa_recipients['email']             = $post['soa_recipients'];
                $result['soa_recipients']   = self::addMasterlistPivot($soa_recipients, 'soa_recipients');
            }

            if($post['or_recipients'] != ""){
                $official_receipt['master_list_id']    = $id;
                $official_receipt['created_at']        = date('Y-m-d H:i:s');
                $official_receipt['email']             = $post['or_recipients'];
                $result['official_receipt']   = self::addMasterlistPivot($official_receipt, 'official_receipt_recipients');
            }
            
        }else{
            $result['status']  = 'failed';
            $result['message'] = 'Encounter technical error. Pls try again';
        }
        return $result;
    }

    public static function editMasterList($id, $post){
        $fields = mysql::buildFields($post['master_list'], ", ");
        if(mysql::update('soa_masterlist', $fields, 'id='.$id)){
            $result['status']  = 'success';
            $result['message'] = 'New Record Saved';

            if($post['branch'] != ""){
                $branch['master_list_id']       = $id;
                $branch['created_at']           = date('Y-m-d H:i:s');
                $branch['branch_id']            = $post['branch'];
                $result['branch']               = self::editMasterlistPivot($branch, 'branch_master_list_pivot');
            }

            if($post['segment'] != ""){
                $segment['master_list_id']      = $id;
                $segment['created_at']          = date('Y-m-d H:i:s');
                $segment['segment_id']          = $post['segment'];
                $result['segment']              = self::editMasterlistPivot($segment, 'soa_segment_master_list_pivot');
            }

            if($post['class_of_business'] != ""){
                $class_ob_business['master_list_id']    = $id;
                $class_ob_business['created_at']        = date('Y-m-d H:i:s');
                $class_ob_business['class_of_business_id'] = $post['class_of_business'];
                $result['class_of_business'] = self::editMasterlistPivot($class_ob_business, 'cob_master_list_pivot');
            }

            if($post['sales_channel'] != ""){
                $sales_channel['master_list_id']    = $id;
                $sales_channel['created_at']        = date('Y-m-d H:i:s');
                $sales_channel['sales_channel_id'] = $post['sales_channel'];
                $result['sales_channel'] = self::editMasterlistPivot($sales_channel, 'soa_sales_channel_master_list_pivot');
            }

            if($post['topro'] != ""){
                $topro['master_list_id']    = $id;
                $topro['created_at']        = date('Y-m-d H:i:s');
                $topro['topro_id'] = $post['topro'];
                $result['topro'] = self::editMasterlistPivot($topro, 'soa_topro_master_list_pivot');
            }

            if($post['soa_recipients'] != ""){
                $soa_recipients['master_list_id']    = $id;
                $soa_recipients['created_at']        = date('Y-m-d H:i:s');
                $soa_recipients['email']             = $post['soa_recipients'];
                $result['soa_recipients']   = self::editMasterlistPivot($soa_recipients, 'soa_recipients');
            }

            if($post['or_recipients'] != ""){
                $official_receipt['master_list_id']    = $id;
                $official_receipt['created_at']        = date('Y-m-d H:i:s');
                $official_receipt['email']             = $post['or_recipients'];
                $result['official_receipt']   = self::editMasterlistPivot($official_receipt, 'official_receipt_recipients');
            }
            
        }else{
            $result['status']  = 'failed';
            $result['message'] = 'Encounter technical error. Pls try again';
        }
        return $result;
    }

    
    public static function getMasterlistById($id)
    {
        $result = mysql::select('soa_masterlist sml 
                                 INNER JOIN master_intermediaries mint ON mint.id = sml.intermediary_id
                                 INNER JOIN master_handler mha ON mha.id = sml.handler_id
                                 INNER JOIN master_team_leader mtl ON mtl.id = sml.team_leader_id
                                ',
                                'sml.*',
                                'sml.id = '.$id,
                                '');
        return $result;
    }

    public static function addMasterlistPivot($post, $table){
        $fields = mysql::buildFields($post, ", ");
        if(mysql::insert($table, $fields)){
            $result['status']  = 'success';
            $result['message'] = 'New Record Saved';
        }else{
            $result['status']  = 'failed';
            $result['message'] = 'Encounter technical error. Pls try again';
        }
        return $result;
    }

    public static function editMasterlistPivot($post, $table){

        $reset = mysql::delete($table, 'master_list_id = '.$post['master_list_id']);
        $fields = mysql::buildFields($post, ", ");
        if(mysql::insert($table, $fields, '')){
            $result['status']  = 'success';
            $result['message'] = 'Reord Successfully Updated';
        }else{
            $result['status']  = 'failed';
            $result['message'] = 'Encounter technical error. Pls try again';
        }
        return $result;
    }

    public static function getMasterlistPivot($id, $table)
    {
        $result = mysql::select($table,
                                '*',
                                'master_list_id = '.$id,
                                '');
        return $result;
    }
}
?>