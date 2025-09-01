<?php

class Email{

    public function __construct() {}

    public static function getRecord(){

        $result = array();
        $result  = mysql::query("select as_of_date,source_name as SOURCE_NAME from soa_monthly_raw_data where is_processed is null  group by  as_of_date,source_name");
        
        if(is_array($result)){
            $result['message'] = 'success';
        }
       
        return $result;
    }
}