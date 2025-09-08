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

    public static function getReminder(){ 

        $result = array();
        $result = mysql::query("select SOURCE_NAME, DATE_FORMAT(EFFECTIVE_DATE, '%M %Y') AS Month,aging_days, sum(net_due) as net_due  from soa_monthly_raw_data where is_processed is null and  
                                (AGING_DAYS > 90) and source_name = 'CCFM INSURANCE AGENCY CORP. DBA. ASSURANCE'
                                group by SOURCE_NAME, DATE_FORMAT(EFFECTIVE_DATE, '%M %Y'),aging_days ORDER BY SOURCE_NAME");
                                    
        if(is_array($result)){
            $result['message'] = 'success';
        }    

        return $result;
    }

    
}