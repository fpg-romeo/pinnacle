<?php
    class CollectionController{
        
        public function __construct() {
            checkLoggedIn('true');
        }

        public function all(){
            $data = array();


            // $data                   = array();
            // $CONFIGURATION          = Configuration::general();
            // $keyword                = urldecode(getVar('keyword'));
            
            // $data['records']        = Gcash::getPolicy($keyword, pagination('start'), pagination('limit'));
            // $data['total_record']   = recastArray(Gcash::getPolicy($keyword, '', '', 'count'))['count'] ?? 0;
            // $data['total_page']     = pagination('total', $data['total_record']);
            // $data['accounts']       = Account::getByStatusId($CONFIGURATION['ACCOUNT_STATUS_ACTIVE']);

            views('collection.all', $data);  
        } 

    }
?>