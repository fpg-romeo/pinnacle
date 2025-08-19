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
                                    INNER JOIN master_handler mha ON mha.id = sml.analyst_id
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
                                 INNER JOIN master_handler mha ON mha.id = sml.analyst_id
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

}
?>