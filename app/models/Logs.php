<?php
    class Logs{

        public function __construct(){
  
        }
        
        public static function addDynamic($table,$post){      
            $fields = mysql::buildFields($post, ", ");
            if(mysql::insert($table, $fields)){
                $return['status']  = 'success';
                $return['message'] = 'New Record Saved';
                $return['id']      = mysql::insertedId();
            }else{
                $return['status']  = 'failed';
                $return['message'] = 'Encounter technical error. Pls try again';
                $return['id']      = '';
            }

            return $return;
        }

    }
?>