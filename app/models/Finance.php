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
            $result = mysql::select('soa_master_list sml 
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

        $result = mysql::select('soa_master_list sml 
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
        if(mysql::insert('soa_master_list', $fields)){
            $result['status']  = 'success';
            $result['message'] = 'New Record Saved';

            $id    = mysql::insertedId();

            if(!empty($post['branch'])){
                $insert_branch = array();
                foreach($post['branch'] as $branch){
                    $insert_branch[] = "(" . $id . ", '".$branch."', '".date('Y-m-d H:i:s')."')";
                }
                
                $result['branch']  = self::addMasterlistPivot($id, $insert_branch, 'soa_branch_master_list_pivot', '(master_list_id, branch_id, created_at)');
            }

            if(!empty($post['segment'])){
                $insert_segment = array();
                foreach($post['segment'] as $segment){
                    $insert_segment[] = "(" . $id . ", '".$segment."', '".date('Y-m-d H:i:s')."')";
                }
                
                $result['segment']  = self::addMasterlistPivot($id, $insert_segment, 'soa_segment_master_list_pivot', '(master_list_id, segment_id, created_at)');
            }

            if(!empty($post['class_of_business'])){
                $insert_class_of_business = array();
                foreach($post['class_of_business'] as $class_of_business){
                    $insert_class_of_business[] = "(" . $id . ", '".$class_of_business."', '".date('Y-m-d H:i:s')."')";
                }
                
                $result['class_of_business']  = self::addMasterlistPivot($id, $insert_class_of_business, 'soa_class_of_business_master_list_pivot', '(master_list_id, class_of_business_id, created_at)');
            }

            if(!empty($post['sales_channel'])){
                $insert_sales_channel = array();
                foreach($post['sales_channel'] as $sales_channel){
                    $insert_sales_channel[] = "(" . $id . ", '".$sales_channel."', '".date('Y-m-d H:i:s')."')";
                }
                
                $result['sales_channel']  = self::addMasterlistPivot($id, $insert_sales_channel, 'soa_sales_channel_master_list_pivot', '(master_list_id, sales_channel_id, created_at)');
            }

            if(!empty($post['topro'])){
                $insert_topro = array();
                foreach($post['topro'] as $topro){
                    $insert_topro[] = "(" . $id . ", '".$topro."', '".date('Y-m-d H:i:s')."')";
                }
                
                $result['topro']  = self::addMasterlistPivot($id, $insert_topro, 'soa_topro_master_list_pivot', '(master_list_id, topro_id, created_at)');
            }

            if(!empty($post['soa_recipients'])){
                $insert_soa_recipients = array();
                foreach($post['soa_recipients'] as $soa_recipients){
                    $insert_soa_recipients[] = "(" . $id . ", '".$soa_recipients."', '".date('Y-m-d H:i:s')."')";
                }
                
                $result['soa_recipients']  = self::addMasterlistPivot($id, $insert_soa_recipients, 'soa_recipient', '(master_list_id, email, created_at)');
            }
            
            if(!empty($post['or_recipients'])){
                $insert_or_recipients = array();
                foreach($post['or_recipients'] as $or_recipients){
                    $insert_or_recipients[] = "(" . $id . ", '".$or_recipients."', '".date('Y-m-d H:i:s')."')";
                }
                
                $result['or_recipients']  = self::addMasterlistPivot($id, $insert_or_recipients, 'soa_official_receipt_recipient', '(master_list_id, email, created_at)');
            }
            
        }else{
            $result['status']  = 'failed';
            $result['message'] = 'Encounter technical error. Pls try again';
        }
        return $result;
    }

    public static function editMasterList($id, $post){
        $fields = mysql::buildFields($post['master_list'], ", ");
        if(mysql::update('soa_master_list', $fields, 'id='.$id)){
            $result['status']  = 'success';
            $result['message'] = 'New Record Saved';

            if(!empty($post['branch'])){
                $insert_branch = array();
                foreach($post['branch'] as $branch){
                    $insert_branch[] = "(" . $id . ", '".$branch."', '".date('Y-m-d H:i:s')."')";
                }
                
                $result['branch']  = self::addMasterlistPivot($id, $insert_branch, 'soa_branch_master_list_pivot', '(master_list_id, branch_id, created_at)');
            }

            if(!empty($post['segment'])){
                pre($post['segment']);
                $insert_segment = array();
                foreach($post['segment'] as $segment){
                    $insert_segment[] = "(" . $id . ", '".$segment."', '".date('Y-m-d H:i:s')."')";
                }
                
                $result['segment']  = self::addMasterlistPivot($id, $insert_segment, 'soa_segment_master_list_pivot', '(master_list_id, segment_id, created_at)');
            }

            if(!empty($post['class_of_business'])){
                $insert_class_of_business = array();
                foreach($post['class_of_business'] as $class_of_business){
                    $insert_class_of_business[] = "(" . $id . ", '".$class_of_business."', '".date('Y-m-d H:i:s')."')";
                }
                
                $result['class_of_business']  = self::addMasterlistPivot($id, $insert_class_of_business, 'soa_class_of_business_master_list_pivot', '(master_list_id, class_of_business_id, created_at)');
            }

            if(!empty($post['sales_channel'])){
                $insert_sales_channel = array();
                foreach($post['sales_channel'] as $sales_channel){
                    $insert_sales_channel[] = "(" . $id . ", '".$sales_channel."', '".date('Y-m-d H:i:s')."')";
                }
                
                $result['sales_channel']  = self::addMasterlistPivot($id, $insert_sales_channel, 'soa_sales_channel_master_list_pivot', '(master_list_id, sales_channel_id, created_at)');
            }

            if(!empty($post['topro'])){
                $insert_topro = array();
                foreach($post['topro'] as $topro){
                    $insert_topro[] = "(" . $id . ", '".$topro."', '".date('Y-m-d H:i:s')."')";
                }
                
                $result['topro']  = self::addMasterlistPivot($id, $insert_topro, 'soa_topro_master_list_pivot', '(master_list_id, topro_id, created_at)');
            }

            if(!empty($post['soa_recipients'])){
                $insert_soa_recipients = array();
                foreach($post['soa_recipients'] as $soa_recipients){
                    $insert_soa_recipients[] = "(" . $id . ", '".$soa_recipients."', '".date('Y-m-d H:i:s')."')";
                }
                
                $result['soa_recipients']  = self::addMasterlistPivot($id, $insert_soa_recipients, 'soa_recipient', '(master_list_id, email, created_at)');
            }
            
            if(!empty($post['or_recipients'])){
                $insert_or_recipients = array();
                foreach($post['or_recipients'] as $or_recipients){
                    $insert_or_recipients[] = "(" . $id . ", '".$or_recipients."', '".date('Y-m-d H:i:s')."')";
                }
                
                $result['or_recipients']  = self::addMasterlistPivot($id, $insert_or_recipients, 'soa_official_receipt_recipient', '(master_list_id, email, created_at)');
            }
            
        }else{
            $result['status']  = 'failed';
            $result['message'] = 'Encounter technical error. Pls try again';
        }
        return $result;
    }

    
    public static function getMasterlistById($id)
    {
        $result = mysql::select('soa_master_list sml 
                                 INNER JOIN master_intermediaries mint ON mint.id = sml.intermediary_id
                                 INNER JOIN master_handler mha ON mha.id = sml.handler_id
                                 INNER JOIN master_team_leader mtl ON mtl.id = sml.team_leader_id
                                ',
                                'sml.*',
                                'sml.id = '.$id,
                                '');
        return $result;
    }

    public static function addMasterlistPivot($master_list_id, $post, $table, $column){

        $record = self::getMasterlistPivot($master_list_id, $table);

        if(is_array($record)){
            $reset = mysql::delete($table, 'master_list_id = '.$master_list_id);
        }

        if (mysql::query("INSERT INTO ".$table.$column." VALUES " . implode(",\n", $post), "insert")) {
            $result['status']  = 'success';
            $result['message'] = 'New Record Saved';
        } else {
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