<?php
    class UnderwritingController{

        public function __construct() {
            checkLoggedIn('true');
        }

        public function renewal(){
            views('underwriting.renewal'); 
        }
    }
?>