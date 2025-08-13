<?php
    class MiscellaneousController{

        public function __construct() {

        }

        public function cronJob(){
            $data = array();

            views('miscellaneous.cron-job', $data);  
        }
    }
?>
