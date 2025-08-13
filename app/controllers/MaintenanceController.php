<?php
    class MaintenanceController{

        public function __construct() {
            checkLoggedIn('true');
        }

        public function teamLeader(){
            views('maintenance.team-leader.php');
        }
    }
?>