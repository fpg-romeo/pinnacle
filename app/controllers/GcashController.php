<?php
class GcashController
{

    public function __construct()
    {
        checkLoggedIn('true');
    }

    public function getColumns()
    {

        $column_name = [
            'A' => 'first_name',
            'B' => 'last_name',
            'C' => 'middle_name',
            'D' => 'date_of_birth',
            'E' => 'mobile_number',
            'F' => 'email_address',
            'G' => 'date_of_transaction',
            'H' => 'reference_number',
            'I' => 'load_amount',
            'J' => 'load_status',
            'K' => 'consent_status',
            'L' => 'policy_id',
            'M' => 'policy_status',
            'N' => 'protect_premium_taxes',
            'O' => 'date_insurance_start',
            'P' => 'date_insurance_end',
            'Q' => 'batch_number',
        ];
        return $column_name;
    }

    public function importPolicyView()
    {
        $data          = array();
        $id            = idDecrypt(getVar('account_id'));
        $data['claim'] = recastArray(Gcash::getPolicyById($id));

        views('gcash.import-policy-view', $data);
    }

    public function claim()
    {
        includeModel(['Account']);

        $data                   = array();
        $CONFIGURATION          = Configuration::general();
        $keyword                = urldecode(getVar('keyword'));
        
        $data['records']        = Gcash::getPolicy($keyword, pagination('start'), pagination('limit'));
        $data['total_record']   = recastArray(Gcash::getPolicy($keyword, '', '', 'count'))['count'] ?? 0;
        $data['total_page']     = pagination('total', $data['total_record']);
        $data['accounts']       = Account::getByStatusId($CONFIGURATION['ACCOUNT_STATUS_ACTIVE']);

        views('gcash.claim', $data);
    }

    public function claimSummary()
    {
        includeModel(['Account']);

        $data                   = array();
        $CONFIGURATION          = Configuration::general();
        $account_id             = urldecode(getVar('account_id'));
        $account_id             = $account_id == "all" ? '' : $account_id;

        $data['summary']        = Gcash::getPolicySummary($account_id, pagination('start'), pagination('limit'));
        $data['total_record']   = recastArray(Gcash::getPolicySummary($account_id, '', '', 'count'))['count'] ?? 0;
        $data['total_page']     = pagination('total', $data['total_record']);
        $data['accounts']       = Account::getByStatusId($CONFIGURATION['ACCOUNT_STATUS_ACTIVE']);

        views('gcash.claim-summary', $data);
    }

    public function importClaimUpload()
    {
        $data = array();

        views('gcash.import-claim-upload', $data);
    }

    public function importClaimJson()
    {
        includeDefault(['email']);
        $result = array();

        $CONFIGURATION = Configuration::general();

        if (isset($_FILES['file']) && !empty($_FILES)) {
            $file_name     = $_FILES['file']['name'];
            $file_size     = $_FILES['file']['size'];
            $file_tmp      = $_FILES['file']['tmp_name'];
            $file_type     = $_FILES['file']['type'];
            $file_ext      = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $file_new_name = 'Claim-' . dateTimeAsId() . '.' . $file_ext;
            $extensions    = $CONFIGURATION['ALLOWED_EXCEL'];
            $file_upload   = '';

            $transaction_type = postVar('transaction_type');

            if (move_uploaded_file($file_tmp, uploadFile('gcash', $file_new_name))) {
                // $list = explode(',', $field['row']);
                $file_upload = getDocumentRoot() . '/upload/gcash/' . $file_new_name;
                
                try {

                    includeLibrary(['excel/PHPExcel.php']);

                    $objPHPExcel   = new PHPExcel();
                    
                    $inputFileType = PHPExcel_IOFactory::identify($file_upload);

                    // Use CSV reader if CSV file (faster)
                    if (strtolower(pathinfo($file_upload, PATHINFO_EXTENSION)) === 'csv') {
                        $objReader = new PHPExcel_Reader_CSV();
                        $objReader->setDelimiter(',');
                        $objReader->setEnclosure('"');
                        $objReader->setLineEnding("\r\n");
                        $objReader->setSheetIndex(0);
                    } else {
                        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                        if (method_exists($objReader, 'setReadDataOnly')) {
                            $objReader->setReadDataOnly(true);
                        }
                    }

                    // Load with only necessary data
                    $objPHPExcel    = $objReader->load($file_upload);
                    $sheet          = $objPHPExcel->getActiveSheet();
                    $highestRow     = $sheet->getHighestRow();
                    $highestColumn  = $sheet->getHighestColumn();
                    $worksheet      = $sheet->toArray();

                    
                    $ctr_success    = 0;
                    $ctr_failed     = 0;
                    $ctr_duplicate  = postVar('duplicate', 0);

                    if($transaction_type == 'new' || ($transaction_type == 'update' && $highestRow <= 501)){
                        // Summary insert
                        $encode_summary = [
                            'success'              => $ctr_success,
                            'duplicate'            => $ctr_duplicate,
                            'failed'               => $ctr_failed,
                            'file'                 => $file_new_name,
                            'file_name'            => $file_name,
                            'transaction_type'     => postVar('transaction_type'),
                            'created_by'           => ACCOUNT_ID,
                            'created_when'         => dateTimeStamp(),
                        ];
                        $result = Gcash::addPolicySummary($encode_summary);

                        $column_name = $this->getColumns();
                        $batch_declaration = [];

                        $required_column = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];

                        unset($worksheet[0]);
                        $total_success = 0;
                        if($transaction_type == 'update'){
                            foreach($worksheet as $worksheet_key => $worksheet_value){
                                $valid = true;
                                foreach($required_column as $column){
                                    if(empty(trim($worksheet_value[$column] ?? ''))){
                                        $valid = false;
                                        break;
                                    }
                                }

                                if(!$valid){
                                    continue;
                                }

                                $file['first_name']             = htmlEncode($worksheet_value[0]);
                                $file['last_name']              = htmlEncode($worksheet_value[1]);
                                $file['middle_name']            = htmlEncode($worksheet_value[2]);
                                $file['date_of_birth']          = dateSaveDB(self::parseExcelDate($worksheet_value[3]));
                                $file['mobile_number']          = htmlEncode($worksheet_value[4]);
                                $file['email_address']          = htmlEncode($worksheet_value[5]);
                                $file['date_of_transaction']    = dateSaveDB(self::parseExcelDate($worksheet_value[6]));
                                $file['reference_number']       = htmlEncode($worksheet_value[7]);
                                $file['load_amount']            = htmlEncode($worksheet_value[8]);
                                $file['load_status']            = htmlEncode($worksheet_value[9]);
                                $file['consent_status']         = htmlEncode($worksheet_value[10]);
                                $file['policy_id']              = htmlEncode($worksheet_value[11]);
                                $file['policy_status']          = htmlEncode($worksheet_value[12]);
                                $file['protect_premium_taxes']  = htmlEncode($worksheet_value[13]);
                                $file['date_insurance_start']   = dateSaveDB(self::parseExcelDate($worksheet_value[14]));
                                $file['date_insurance_end']     = dateSaveDB(self::parseExcelDate($worksheet_value[15]));
                                $file['batch_number']           = htmlEncode($worksheet_value[16]);
                                $file['updated_by']             = ACCOUNT_ID;
                                $file['updated_when']           = dateTimeStamp();

                                Gcash::managePolicy($file);
                                $total_success++;
                                $batch_number = $file['batch_number'];
                            }
                        }else{
                            foreach($worksheet as $worksheet_key => $worksheet_value){
                                $valid = true;
                                foreach($required_column as $column){
                                    if(empty(trim($worksheet_value[$column] ?? ''))){
                                        $valid = false;
                                        break;
                                    }
                                }

                                if(!$valid){
                                    continue;
                                }

                                $file['first_name']             = htmlEncode($worksheet_value[0]);
                                $file['last_name']              = htmlEncode($worksheet_value[1]);
                                $file['middle_name']            = htmlEncode($worksheet_value[2]);
                                $file['date_of_birth']          = dateSaveDB(self::parseExcelDate($worksheet_value[3]));
                                $file['mobile_number']          = htmlEncode($worksheet_value[4]);
                                $file['email_address']          = htmlEncode($worksheet_value[5]);
                                $file['date_of_transaction']    = dateSaveDB(self::parseExcelDate($worksheet_value[6]));
                                $file['reference_number']       = htmlEncode($worksheet_value[7]);
                                $file['load_amount']            = htmlEncode($worksheet_value[8]);
                                $file['load_status']            = htmlEncode($worksheet_value[9]);
                                $file['consent_status']         = htmlEncode($worksheet_value[10]);
                                $file['policy_id']              = htmlEncode($worksheet_value[11]);
                                $file['policy_status']          = htmlEncode($worksheet_value[12]);
                                $file['protect_premium_taxes']  = htmlEncode($worksheet_value[13]);
                                $file['date_insurance_start']   = dateSaveDB(self::parseExcelDate($worksheet_value[14]));
                                $file['date_insurance_end']     = dateSaveDB(self::parseExcelDate($worksheet_value[15]));
                                $file['batch_number']           = htmlEncode($worksheet_value[16]);
                                $file['created_by']             = ACCOUNT_ID;
                                $file['created_when']           = dateTimeStamp();
                                $file['batch_id']               = dateTimeAsId();

                                $ctr_file = 1;
                                $insert_column = '(';
                                foreach($file as $column){
                                    $insert_column .= "'".$column."'";

                                    if($ctr_file < count(array_keys($file))){
                                        $insert_column .= ',';
                                    }
                                    $ctr_file++;
                                }

                                $insert_column .= ')';
                                $multiple_data[] = $insert_column;
                            }

                            $batch_number  = $file['batch_number'];
                            $encode_result = Gcash::addPolicyBulk($multiple_data);
                            $total_success = count($multiple_data);
                        }

                        // update register encode
                        $update_encode['id']           = $result['id'];
                        $total_uploaded_rows           = $total_success;
                        $total_processed_rows          = $ctr_duplicate + $ctr_success + $ctr_failed;
                        // calculate the first validation count for duplicates
                        $first_stage_duplicate_count   = $total_uploaded_rows - $total_processed_rows;
                        $total_duplicate               = $ctr_duplicate + $first_stage_duplicate_count;
                        $update_encode['duplicate']    = $ctr_duplicate;
                        $update_encode['success']      = $total_uploaded_rows;
                        $update_encode['failed']       = $ctr_failed;
                        Gcash::editPolicySummary($update_encode);
                        
                        $result['alert'] = 'Total Saved = ' . $total_uploaded_rows . ' / Total Failed = ' . $ctr_failed . ' / Total Duplicate = ' . $ctr_duplicate;
                        $batch           = (isset($batch_number) && !empty($batch_number)) ? $batch_number : "N/A";
                        $email_body      = Email::gcashPolicyUpload($total_uploaded_rows, $ctr_duplicate, $ctr_failed, $batch, $file_upload);
                        $email_body      = Email::templateDefault($email_body);

                        Email::sendEmail('', 'Claims Upload', $email_body, '', '', '');
                    }
                    else if($transaction_type == 'update' && $highestRow > 501){
                        $result['status']   = 'failed';
                        $result['message']  = 'Maximum limit of 500 rows';
                        $result['alert']    = 'Maximum limit of 500 rows';
                    }
                } catch (Exception $e) {
                    $result['status']  = 'forbidden';
                    $result['message'] = 'Error loading file "' . pathinfo($file, PATHINFO_BASENAME) . '": ' . $e->getMessage();
                }
            }

            $result['redirect'] = '/gcash/claim-summary/1';
        } else {
            $result['status']  = 'forbidden';
            $result['message'] = 'Access to this resource on the server is denied';
        }

        echo json_encode($result);
    }

    public function parseExcelDate($value) {
        if (is_numeric($value) && $value > 25569 && $value < 60000) {
            // Convert Excel date serial to PHP timestamp
            $timestamp = \PHPExcel_Shared_Date::ExcelToPHP($value);
            return date('Y-m-d', $timestamp); // Or pass to dateSaveDB() if needed
        }
        return $value;
    }

    public function declaration()
    {
        $keyword              = urldecode(getVar('keyword'));
        $data['records']      = Gcash::getDeclaration($keyword, pagination('start'), pagination('limit'));
        $data['total_record'] = Gcash::countDeclaration($keyword);
        $data['total_page']   = pagination('total', $data['total_record']);

        views('gcash.declaration', $data);
    }

    public function importDeclarationView()
    {
        $data                = array();
        $id                  = idDecrypt(getVar('id'));
        $data['declaration'] = recastArray(Gcash::getDeclarationById($id));

        views('gcash.import-declaration-view', $data);
    }

    public function importDeclarationManage()
    {
        $data                = array();
        $id                  = idDecrypt(getVar('id'));
        $data['declaration'] = recastArray(Gcash::getDeclarationById($id));

        views('gcash.import-declaration-manage', $data);
    }

    public function declarationJson()
    {

        if(isset($_POST) && !empty($_POST)){

            if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){
                $result = Gcash::deleteDeclaration(idDecrypt(postVar('id')));
                
            }else{
                $id                           = idDecrypt(postVar('id'));
                $field['batch_number']        = postVar('batch_number');
                $field['workflow_number']     = postVar('workflow_number');
                $field['endorsement_number']  = postVar('endorsement_number');

                $data = checkRequiredPost(array('batch_number'));

                if(!array_key_exists('error', $data)){  
                    if(!empty($id)){
                        $field['id']                = $id;
                        $field['updated_by']        = ACCOUNT_ID;
                        $field['updated_when']      = dateTimeStamp();

                        $result = Gcash::editDeclaration($field);
                    }else{
                        $field['created_by']        = ACCOUNT_ID;
                        $field['created_when']      = dateTimeStamp();

                        $result = Gcash::addDeclaration($field);
                    }
                }
            }
        }else{
            $result['status']  = 'forbidden';
            $result['message'] = 'Access to this resource on the server is denied';
        }
        
        echo json_encode($result);
    }

    public function importPolicyManage()
    {
        $data           = array();
        $id             = idDecrypt(getVar('id'));
        $data['policy'] = recastArray(Gcash::getPolicyById($id));

        views('gcash.import-policy-manage', $data);
    }

    public function policyJson()
    {

        if(isset($_POST) && !empty($_POST)){

            if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){
                $result = Gcash::deletePolicy(idDecrypt(postVar('id')));
                
            }else{
                $id                             = idDecrypt(postVar('id'));
                $field['first_name']            = postVar('first_name');
                $field['last_name']             = postVar('last_name');
                $field['middle_name']           = postVar('middle_name');
                $field['date_of_birth']         = dateSaveDB(postVar('date_of_birth'));
                $field['mobile_number']         = postVar('mobile_number');
                $field['email_address']         = postVar('email_address');
                $field['date_of_transaction']   = dateSaveDB(postVar('date_of_transaction'));
                $field['reference_number']      = postVar('reference_number');
                $field['load_amount']           = postVar('load_amount');
                $field['load_status']           = postVar('load_status');
                $field['consent_status']        = postVar('consent_status');
                $field['policy_id']             = postVar('policy_id');
                $field['policy_status']         = postVar('policy_status');
                $field['protect_premium_taxes'] = postVar('protect_premium_taxes');
                $field['date_insurance_start']  = dateSaveDB(postVar('date_insurance_start'));
                $field['date_insurance_end']    = dateSaveDB(postVar('date_insurance_end'));
                $field['batch_number']          = postVar('batch_number');

                $data = checkRequiredPost(array('first_name','last_name','middle_name','date_of_birth','mobile_number','email_address',
                                                'date_of_transaction','reference_number','load_amount','load_status','consent_status',
                                                'policy_id','policy_status','protect_premium_taxes','date_insurance_start','date_insurance_end',
                                                'batch_number'));

                if(!array_key_exists('error', $data)){  
                    if(!empty($id)){
                        $field['id']                = $id;
                        $field['updated_by']        = ACCOUNT_ID;
                        $field['updated_when']      = dateTimeStamp();

                        $result = Gcash::editPolicy($field);
                    }else{
                        $field['created_by']        = ACCOUNT_ID;
                        $field['created_when']      = dateTimeStamp();

                        $result = Gcash::addPolicy($field);
                    }
                }
            }
        }else{
            $result['status']  = 'forbidden';
            $result['message'] = 'Access to this resource on the server is denied';
        }
        
        echo json_encode($result);
    }
}