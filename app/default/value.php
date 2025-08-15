<?php
    function value_gender(){
        $value = array('Female', 
                       'Male'
                       );
        return $value;
    }

    function value_yes_no(){
        $value = array('Yes', 
                       'No'
                       );
        return $value;
    }

    function value_age_group(){
        $value = array();
        for ($x = 1; $x <= 65; $x++) {
            array_push($value, $x);
        }
        return $value;
    }

    function value_marital_status(){
        $value = array('Single', 
                       'Married'
                       );
        return $value;
    }

    function value_pagination_limit(){
        $value = array('5', 
                       '10', 
                       '25',
                       '50',
                       '100'
                       );
        return $value;
    }

    function value_month(){
        $value = array('January',
                       'February',
                       'March',
                       'April',
                       'May',
                       'June',
                       'July',
                       'August',
                       'September',
                       'October',
                       'November',
                       'December'
                      );  

        return $value;
    }
    
    function value_quarter(){
        $value = array('Q1',
                       'Q2',
                       'Q3',
                       'Q4',
                      );

        return $value;
    }

    function value_computer_type(){
        $value = array('Acer',
                       'Asus',
                       'Dell',
                       'HP',
                       'Huawei',
                       'iMac',
                       'Lenovo',
                       'LG',
                       'MacBook Air',
                       'MacBook Pro',
                       'Mac mini',
                       'Microsoft',
                       'MSI',
                       'Razer'
                      );

        return $value;
    }

    function value_status_email(){
        $value = array(1 => 'Unprocessed', 
                       3 => 'Sent'
                      );
        return $value; 
    }
?>