<?php
    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx; 
    use PhpOffice\PhpSpreadsheet\Shared\Date;
    use PhpOffice\PhpSpreadsheet\Shared\Csv;


    require_once('app/library/spreadsheet/Spreadsheet.php');
    require_once('app/library/spreadsheet/IOFactory.php');
    require_once('app/library/spreadsheet/autoloader.php');

    class UnderwritingController{   
        public function __construct() {
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

        
        public function parseExcelDate($value) {

            if (is_numeric($value) && $value > 25569 && $value < 60000) {
                // Convert Excel date serial to PHP timestamp
                $timestamp = Date::excelToTimestamp($value);
                return date('Y-m-d', $timestamp); // Or pass to dateSaveDB() if needed
            }
            return $value;

        }

        public function renewalSummary(){
            includeModel(['Account']);

            $data                   = array();
            $CONFIGURATION          = Configuration::general();
            $account_id             = urldecode(getVar('account_id'));
            $account_id             = $account_id == "all" ? '' : $account_id;

            $data['summary']        = Underwriting::getPolicySummary($account_id, pagination('start'), pagination('limit'));
            $data['total_record']   = recastArray(Underwriting::getPolicySummary($account_id, '', '', 'count'))['count'] ?? 0;
            $data['total_page']     = pagination('total', $data['total_record']);
            $data['accounts']       = Account::getByStatusId($CONFIGURATION['ACCOUNT_STATUS_ACTIVE']);

            views('underwriting.renewal-summary', $data); 
        }

         public function importRenewalUpload(){
            $data = array();

            views('underwriting.import-renewal-upload', $data);
        }

        public function importRenewalJson(){
            includeDefault(['email']);
            $result = array();

            $CONFIGURATION = Configuration::general();

            if (isset($_FILES['file']) && !empty($_FILES)) {
                $file_name     = $_FILES['file']['name'];
                $file_size     = $_FILES['file']['size'];
                $file_tmp      = $_FILES['file']['tmp_name'];
                $file_type     = $_FILES['file']['type'];
                $file_ext      = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $file_new_name = 'Policy-' . dateTimeAsId() . '.' . $file_ext;
                $extensions    = $CONFIGURATION['ALLOWED_EXCEL'];
                $file_upload   = '';

                $transaction_type = postVar('transaction_type');

                if (move_uploaded_file($file_tmp, uploadFile('renewal', $file_new_name))) {
                    // $list = explode(',', $field['row']);
                    $file_upload = getDocumentRoot() . '/upload/renewal/' . $file_new_name;
                    
                    try {
                        
                        $inputFileType = IOFactory::identify($file_upload);

                        // Use CSV reader if CSV file (faster)
                        if (strtolower(pathinfo($file_upload, PATHINFO_EXTENSION)) === 'csv') {
                            $reader = new Csv();
                            $reader->setDelimiter(',');
                            $reader->setEnclosure('"');
                            $reader->setLineEnding("\r\n");
                            $reader->setSheetIndex(0);
                        } else {
                            $reader = IOFactory::createReader($inputFileType);
                            if (method_exists($reader, 'setReadDataOnly')) {
                                $reader->setReadDataOnly(true);
                            }
                        }

                        // Load spreadsheet with only necessary data
                        $spreadsheet   = $reader->load($file_upload);
                        $sheet         = $spreadsheet->getActiveSheet();
                        $highestRow    = $sheet->getHighestRow();
                        $highestColumn = $sheet->getHighestColumn();
                        $worksheet     = $sheet->toArray();

                        
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
                            $result = Underwriting::addPolicySummary($encode_summary);
                            $batch_id  = $result['id'];

                            $column_name = $this->getColumns();
                            $batch_declaration = [];

                            $required_column = [11]; //[0, 4, 11];

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

                                    $file['policy_summary_id']    = $batch_id;
                                    $file['policy_no']            = htmlEncode($worksheet_value[0]);
                                    $file['remarks']              = htmlEncode($worksheet_value[1]);
                                    $file['occupancy']            = htmlEncode($worksheet_value[2]);
                                    $file['tariff_code']          = htmlEncode($worksheet_value[3]);
                                    $file['expiring_rate']        = htmlEncode($worksheet_value[4]);
                                    $file['renewal_rate']         = htmlEncode($worksheet_value[5]);
                                    $file['endorsement_no']       = htmlEncode($worksheet_value[6]);
                                    $file['renewal_no']           = htmlEncode($worksheet_value[7]);
                                    $file['ci_no']                = htmlEncode($worksheet_value[8]);
                                    $file['reference_no']         = htmlEncode($worksheet_value[9]);
                                    $file['co_insurance']         = htmlEncode($worksheet_value[10]);
                                    $file['insured_name']         = htmlEncode($worksheet_value[11]);
                                    $file['contact_numbers']      = htmlEncode($worksheet_value[12]);
                                    $file['inception_date']       = dateSaveDB(self::parseExcelDate($worksheet_value[13]));
                                    $file['expiry_date']          = dateSaveDB(self::parseExcelDate($worksheet_value[14]));
                                    $file['booking_date']         = dateSaveDB(self::parseExcelDate($worksheet_value[15]));
                                    $file['branch']               = htmlEncode($worksheet_value[16]);
                                    $file['branch_name']          = htmlEncode($worksheet_value[17]);
                                    $file['channel']              = htmlEncode($worksheet_value[18]);
                                    $file['channel_2']            = htmlEncode($worksheet_value[19]);
                                    $file['channel_3']            = htmlEncode($worksheet_value[20]);
                                    $file['toc']                  = htmlEncode($worksheet_value[21]);
                                    $file['cob']                  = htmlEncode($worksheet_value[22]);
                                    $file['fob']                  = htmlEncode($worksheet_value[23]);
                                    $file['policy_type']          = htmlEncode($worksheet_value[24]);
                                    $file['mo']                   = htmlEncode($worksheet_value[25]);
                                    $file['segment']              = htmlEncode($worksheet_value[26]);
                                    $file['segment_desc']         = htmlEncode($worksheet_value[27]);
                                    $file['ourshare']             = htmlEncode($worksheet_value[28]);
                                    $file['gross']                = htmlEncode($worksheet_value[29]);
                                    $file['pctshare']             = htmlEncode($worksheet_value[30]);
                                    $file['facultative']          = htmlEncode($worksheet_value[31]);
                                    $file['fshare']               = htmlEncode($worksheet_value[32]);
                                    $file['total_sum_insured']    = $worksheet_value[33] ?? 0;
                                    $file['basic_premium']        = $worksheet_value[34] ?? 0;
                                    $file['dst']                  = htmlEncode($worksheet_value[35]);
                                    $file['vat']                  = $worksheet_value[36] ?? 0;
                                    $file['fst']                  = $worksheet_value[37] ?? 0;
                                    $file['lgt']                  = $worksheet_value[38] ?? 0;
                                    $file['total_premium']        = $worksheet_value[39] ?? 0;
                                    $file['coverage']             = htmlEncode($worksheet_value[40]);
                                    $file['bscode']               = htmlEncode($worksheet_value[41]);
                                    $file['bsname']               = htmlEncode($worksheet_value[42]);
                                    $file['fee']                  = htmlEncode($worksheet_value[43]);
                                    $file['discount']             = htmlEncode($worksheet_value[44]);
                                    $file['nofclaim']             = $worksheet_value[45] ?? 0;
                                    $file['os_claim']             = $worksheet_value[46] ?? 0;
                                    $file['settled_claim']        = $worksheet_value[47] ?? 0;
                                    $file['premiumpaid']          = $worksheet_value[48] ?? 0;
                                    $file['loss_ratio']           = $worksheet_value[49] ?? '';
                                    $file['location_of_risk']     = htmlEncode($worksheet_value[50]);
                                    $file['vehicle_unit']         = htmlEncode($worksheet_value[51]);
                                    $file['type_of_body']         = htmlEncode($worksheet_value[52]);
                                    $file['plate_no']             = htmlEncode($worksheet_value[53]);
                                    $file['engine_no']            = htmlEncode($worksheet_value[54]);
                                    $file['chassis_no']           = htmlEncode($worksheet_value[55]);
                                    $file['renewal_premium']      = htmlEncode($worksheet_value[56]);
                                    $file['renewal_tsi']          = htmlEncode($worksheet_value[57]);
                                    $file['renewal_status']       = htmlEncode($worksheet_value[58]);
                                    $file['location_of_risk_2']   = htmlEncode($worksheet_value[59]);
                                    $file['mailing_address']      = htmlEncode($worksheet_value[60]);
                                    $file['lgt_rate_per_branch']  = htmlEncode($worksheet_value[61]);

                                    $file['updated_by']           = ACCOUNT_ID;
                                    $file['updated_when']         = dateTimeStamp();

                                    Underwriting::managePolicy($file);
                                    $total_success++;
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

                                    $file['policy_summary_id']    = $batch_id;
                                    $file['policy_no']            = htmlEncode($worksheet_value[0]);
                                    $file['remarks']              = htmlEncode($worksheet_value[1]);
                                    $file['occupancy']            = htmlEncode($worksheet_value[2]);
                                    $file['tariff_code']          = htmlEncode($worksheet_value[3]);
                                    $file['expiring_rate']        = htmlEncode($worksheet_value[4]);
                                    $file['renewal_rate']         = htmlEncode($worksheet_value[5]);
                                    $file['endorsement_no']       = htmlEncode($worksheet_value[6]);
                                    $file['renewal_no']           = htmlEncode($worksheet_value[7]);
                                    $file['ci_no']                = htmlEncode($worksheet_value[8]);
                                    $file['reference_no']         = htmlEncode($worksheet_value[9]);
                                    $file['co_insurance']         = htmlEncode($worksheet_value[10]);
                                    $file['insured_name']         = htmlEncode($worksheet_value[11]);
                                    $file['contact_numbers']      = htmlEncode($worksheet_value[12]);
                                    $file['inception_date']       = dateSaveDB(self::parseExcelDate($worksheet_value[13]));
                                    $file['expiry_date']          = dateSaveDB(self::parseExcelDate($worksheet_value[14]));
                                    $file['booking_date']         = dateSaveDB(self::parseExcelDate($worksheet_value[15]));
                                    $file['branch']               = htmlEncode($worksheet_value[16]);
                                    $file['branch_name']          = htmlEncode($worksheet_value[17]);
                                    $file['channel']              = htmlEncode($worksheet_value[18]);
                                    $file['channel_2']            = htmlEncode($worksheet_value[19]);
                                    $file['channel_3']            = htmlEncode($worksheet_value[20]);
                                    $file['toc']                  = htmlEncode($worksheet_value[21]);
                                    $file['cob']                  = htmlEncode($worksheet_value[22]);
                                    $file['fob']                  = htmlEncode($worksheet_value[23]);
                                    $file['policy_type']          = htmlEncode($worksheet_value[24]);
                                    $file['mo']                   = htmlEncode($worksheet_value[25]);
                                    $file['segment']              = htmlEncode($worksheet_value[26]);
                                    $file['segment_desc']         = htmlEncode($worksheet_value[27]);
                                    $file['ourshare']             = htmlEncode($worksheet_value[28]);
                                    $file['gross']                = htmlEncode($worksheet_value[29]);
                                    $file['pctshare']             = htmlEncode($worksheet_value[30]);
                                    $file['facultative']          = htmlEncode($worksheet_value[31]);
                                    $file['fshare']               = htmlEncode($worksheet_value[32]);
                                    $file['total_sum_insured']    = $worksheet_value[33] ?? 0;
                                    $file['basic_premium']        = $worksheet_value[34] ?? 0;
                                    $file['dst']                  = htmlEncode($worksheet_value[35]);
                                    $file['vat']                  = $worksheet_value[36] ?? 0;
                                    $file['fst']                  = $worksheet_value[37] ?? 0;
                                    $file['lgt']                  = $worksheet_value[38] ?? 0;
                                    $file['total_premium']        = $worksheet_value[39] ?? 0;
                                    $file['coverage']             = htmlEncode($worksheet_value[40]);
                                    $file['bscode']               = htmlEncode($worksheet_value[41]);
                                    $file['bsname']               = htmlEncode($worksheet_value[42]);
                                    $file['fee']                  = htmlEncode($worksheet_value[43]);
                                    $file['discount']             = htmlEncode($worksheet_value[44]);
                                    $file['nofclaim']             = $worksheet_value[45] ?? 0;
                                    $file['os_claim']             = $worksheet_value[46] ?? 0;
                                    $file['settled_claim']        = $worksheet_value[47] ?? 0;
                                    $file['premiumpaid']          = $worksheet_value[48] ?? 0;
                                    $file['loss_ratio']           = $worksheet_value[49] ?? '';
                                    $file['location_of_risk']     = htmlEncode($worksheet_value[50]);
                                    $file['vehicle_unit']         = htmlEncode($worksheet_value[51]);
                                    $file['type_of_body']         = htmlEncode($worksheet_value[52]);
                                    $file['plate_no']             = htmlEncode($worksheet_value[53]);
                                    $file['engine_no']            = htmlEncode($worksheet_value[54]);
                                    $file['chassis_no']           = htmlEncode($worksheet_value[55]);
                                    $file['renewal_premium']      = htmlEncode($worksheet_value[56]);
                                    $file['renewal_tsi']          = htmlEncode($worksheet_value[57]);
                                    $file['renewal_status']       = htmlEncode($worksheet_value[58]);
                                    $file['location_of_risk_2']   = htmlEncode($worksheet_value[59]);
                                    $file['mailing_address']      = htmlEncode($worksheet_value[60]);
                                    $file['lgt_rate_per_branch']  = htmlEncode($worksheet_value[61]);

                                    $file['created_by']           = ACCOUNT_ID;
                                    $file['created_when']         = dateTimeStamp();
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

                                $encode_result = Underwriting::addPolicyBulk($multiple_data);
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
                            Underwriting::editPolicySummary($update_encode);
                            
                            $result['alert'] = 'Total Saved = ' . $total_uploaded_rows . ' / Total Failed = ' . $ctr_failed . ' / Total Duplicate = ' . $ctr_duplicate;
                            $batch           = (isset($batch_number) && !empty($batch_number)) ? $batch_number : "N/A";
                            
                            // $email_body      = Email::gcashPolicyUpload($total_uploaded_rows, $ctr_duplicate, $ctr_failed, $batch, $result['id']);
                            // $email_body      = Email::templateDefault($email_body);

                            // Email::sendEmail('', 'GCash Policy', $email_body, '', '', '');
                        }
                        else if($transaction_type == 'update' && $highestRow > 501){
                            $result['status']   = 'failed';
                            $result['message']  = 'Maximum limit of 500 rows';
                            $result['alert']    = 'Maximum limit of 500 rows';
                        }
                    } catch (Exception $e) {
                        $result['status']  = 'forbidden';
                        $result['message'] = 'Error loading file "' . pathinfo($file_upload, PATHINFO_BASENAME) . '": ' . $e->getMessage();
                    }
                }

                $result['redirect'] = '/underwriting/renewal-summary/1';
            } else {
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }

            echo json_encode($result);
        }

        public function renewal(){
            $data                   = array();
            $CONFIGURATION          = Configuration::general();
            $keyword                = urldecode(getVar('keyword'));
            
            $data['records']        = Underwriting::getPolicy($keyword, pagination('start'), pagination('limit'));

            $data['total_record']   = recastArray(Underwriting::getPolicy($keyword, '', '', 'count'))['count'] ?? 0;
            $data['total_page']     = pagination('total', $data['total_record']);
            $data['accounts']       = Account::getByStatusId($CONFIGURATION['ACCOUNT_STATUS_ACTIVE']);
            
            views('underwriting.renewal', $data);
        }

        public function renewal_json(){
            $id = idDecrypt(getVar('id'));
            $result = recastArray(Underwriting::getPolicyById($id));

            echo json_encode($result);
        }
    }
?>