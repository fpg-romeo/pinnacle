<?php
    class MiscellaneousController{

        public function __construct() {

        }

        public function database(){
            $data   = array();

            $folder = getDocumentRoot().'/backup/sql/';

            if(!file_exists($folder)){
                mkdir($folder);
            }

            $files = array_diff(scandir($folder), array('.', '..'));
            $data  = $files;

            views('miscellaneous.database', $data);  
        }

        public function cronJob(){
            $data = array();

            views('miscellaneous.cron-job', $data);  
        }
    }
?>
