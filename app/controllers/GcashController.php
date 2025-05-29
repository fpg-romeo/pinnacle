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

    public function importView()
    {
        $data = array();

        includeDefault(['email']);

        $id              = idDecrypt(getVar('account_id'));
        $data['claim'] = recastArray(Gcash::getClaimEncodeById($id));

        views('gcash.import-claim-view', $data);
    }

    public function claim()
    {
        includeModel(['Account']);

        $data          = array();
        $CONFIGURATION = Configuration::general();

        $account_id           = urldecode(getVar('account_id'));
        $account_ids          = ACCOUNT_ID;
        $keyword              = urldecode(getVar('keyword'));
        $data['records']      = Gcash::getClaimEncode($keyword, $account_ids, '', pagination('start'), pagination('limit'));
        $data['total_record'] = Gcash::countClaimEncode($keyword, $account_ids, '');
        $data['total_page']   = pagination('total', $data['total_record']);

        $data['accounts']     = Account::getByStatusId($CONFIGURATION['ACCOUNT_STATUS_ACTIVE']);

        views('gcash.claim', $data);
    }

    public function claimSummary()
    {
        includeModel(['Account']);

        $data                 = array();
        $CONFIGURATION        = Configuration::general();

        $account_id           = urldecode(getVar('account_id'));
        $account_ids          = ACCOUNT_ID;

        $account_id           = $account_id == "all" ? '' : $account_id;

        $data['summary']      = Gcash::getClaimEncodeSummary($account_id, pagination('start'), pagination('limit'));
        $data['total_record'] = Gcash::countClaimEncodeSummary($account_id);
        $data['total_page']   = pagination('total', $data['total_record']);

        $data['accounts']     = Account::getByStatusId($CONFIGURATION['ACCOUNT_STATUS_ACTIVE']);

        views('gcash.claim-summary', $data);
    }

    public function importClaim()
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

            // $checkIfExistingFile = Gcash::checkIfExistingFilename($file_name);

            if (!in_array($file_ext, $extensions)) {
                $result['status']  = 'Error';
                $result['message'] = 'File format is not allow';
            }
            //  else if ($checkIfExistingFile > 0) {
            //     $result['status']  = 'Error';
            //     $result['message'] = '<b>Error: Duplicate File Name Detected</b></br>
            //                             The file you are trying to upload has the same name as an existing file.</br>
            //                             Please rename your file and try uploading again.';
            // } 
            else {
                if (move_uploaded_file($file_tmp, uploadFile('temp', $file_new_name))) {
                    if (!array_key_exists('error', $result)) {

                        $file = getDocumentRoot() . '/upload/temp/' . $file_new_name;
                        //$file = $_FILES['file']['tmp_name'];
                        try {
                            includeLibrary(['excel/PHPExcel.php']);

                            $inputFileType = PHPExcel_IOFactory::identify($file);

                            // Use CSV reader explicitly if it's a CSV file
                            if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'csv') {
                                $objReader = new PHPExcel_Reader_CSV();
                                $objReader->setDelimiter(','); // Optional: set delimiter if needed
                                $objReader->setEnclosure('"');
                                $objReader->setLineEnding("\r\n");
                                $objReader->setSheetIndex(0);
                            } else {
                                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                                if (method_exists($objReader, 'setReadDataOnly')) {
                                    $objReader->setReadDataOnly(true);
                                }
                            }

                            $objPHPExcel = $objReader->load($file);
                            $sheet = $objPHPExcel->getActiveSheet();

                            $highestRow    = $sheet->getHighestRow();
                            $highestColumn = $sheet->getHighestColumn();

                            $duplicate_contact_no   = [];
                            $duplicate_company_name = [];
                            $duplicate_rows         = [];
                            $duplicate              = 0;

                            $column_name = $this->getColumns();

                            for ($row = 1; $row <= $highestRow; $row++) {
                                $columnarray = [];
                                $column = 'A';
                                foreach ($column_name as $key => $value) {

                                    $cell = $sheet->getCell($column . $row);
                                    $cellValue = $cell->getValue();

                                    // Check if it's a date AND numeric (i.e., Excel serial format)
                                    $date = array('date_of_birth', 'date_of_transaction', 'date_insurance_start', 'date_insurance_end');
                                    if (in_array($value, $date) && !empty($value) && is_numeric($cellValue)) {
                                        $timestamp = '' . PHPExcel_Shared_Date::ExcelToPHP($cellValue) . '';
                                        ${$value}  = date('Y-m-d', $timestamp);
                                    } elseif ($cellValue instanceof PHPExcel_RichText) {
                                        ${$value} = $cellValue->getPlainText();
                                    } else {
                                        ${$value} = $cellValue;
                                    }

                                    $columnarray['row'][$value] = ${$value};
                                    $column++;
                                }

                                if ('A' . $row != 'A1' && !empty($first_name)) {
                                    $result['row'][] = array(
                                        'row'                   => $row,
                                        'similar_company_name'  => '',
                                        'is_similar_only'       => '',
                                        'status'                => 'New'
                                    );
                                    $lastIndex = count($result['row']) - 1;

                                    $result['row'][$lastIndex] = array_merge(
                                        $result['row'][$lastIndex],
                                        $columnarray['row']
                                    );
                                }
                            }

                            $result['file_temporary'] = $file_new_name;
                            $result['file_name']      = $file_name;
                            $result['duplicate']      = count($duplicate_rows);
                        } catch (Exception $e) {
                            $result['status']  = 'Error';
                            $result['message'] = 'Error loading file "' . pathinfo($file, PATHINFO_BASENAME) . '": ' . $e->getMessage();
                        }
                    }
                } else {
                    $result['status']  = 'Error';
                    $result['message'] = 'Encounter technical error. Pls try again';
                }
            }
        } elseif (isset($_POST['submit-import'])) {
            $field['file_temporary'] = postVar('file_temporary');
            $field['row']            = postVar('row');

            $data = checkRequiredPost(array('file_temporary', 'row'));

            if (!array_key_exists('error', $data)) {

                $list = explode(',', $field['row']);
                $file = getDocumentRoot() . '/upload/temp/' . $field['file_temporary'];
                try {

                    includeLibrary(['excel/PHPExcel.php']);

                    $objPHPExcel   = new PHPExcel();
                    $inputFileType = PHPExcel_IOFactory::identify($file);

                    // Use CSV reader explicitly if it's a CSV file
                    if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'csv') {
                        $objReader = new PHPExcel_Reader_CSV();
                        $objReader->setDelimiter(','); // Optional: set delimiter if needed
                        $objReader->setEnclosure('"');
                        $objReader->setLineEnding("\r\n");
                        $objReader->setSheetIndex(0);
                    } else {
                        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                        if (method_exists($objReader, 'setReadDataOnly')) {
                            $objReader->setReadDataOnly(true);
                        }
                    }

                    $objPHPExcel = $objReader->load($file);
                    $sheet = $objPHPExcel->getActiveSheet();
                    $highestRow    = $sheet->getHighestRow();
                    $highestColumn = $sheet->getHighestColumn();

                    $ctr_success   = 0;
                    $ctr_failed    = 0;
                    $ctr_duplicate = postVar('duplicate', 0);

                    //save dataentry summary
                    $encode_summary['success']          = $ctr_success;
                    $encode_summary['duplicate']        = $ctr_duplicate;
                    $encode_summary['failed']           = $ctr_failed;
                    $encode_summary['file']             = postVar('file_temporary');
                    $encode_summary['file_name']        = postVar('file_name');
                    $encode_summary['created_by']       = ACCOUNT_ID;
                    $encode_summary['created_when']     = dateTimeStamp();
                    $result                             = Gcash::addClaimEncodeSummary($encode_summary);
                    $batch_declaration                  = array();

                    $column_name = $this->getColumns();

                    foreach ($list as $row) {
                        if (empty($duplicate)) {

                            $encode['duplicate']    = 'No';
                            $encode['batch_id']     = $result['id'];

                            $column = 'A';
                            foreach ($column_name as $key => $value) {

                                $cell = $sheet->getCell($column . $row);
                                $cellValue = $cell->getValue();

                                // Check if it's a date AND numeric (i.e., Excel serial format)
                                if (($value == 'date_of_birth' || $value == 'date_of_transaction' || $value == 'date_insurance_start' || $value == 'date_insurance_end') && is_numeric($cellValue)) {
                                    $timestamp = '' . PHPExcel_Shared_Date::ExcelToPHP($cellValue) . '';
                                    $encode[$value] = date('Y-m-d', $timestamp);
                                } elseif ($cellValue instanceof PHPExcel_RichText) {
                                    $encode[$value] = $cellValue->getPlainText();
                                } else {
                                    $encode[$value] = $cellValue;
                                }

                                $column++;
                            }

                            $encode['created_by']   = ACCOUNT_ID;
                            $encode['created_when'] = dateTimeStamp();

                            $batch_declaration['batch_number'] = $encode['batch_number'];

                            $encode_result = Gcash::addClaimEncode($encode);

                            if ($encode_result['status'] == 'success') {
                                $ctr_success++;
                            } else {
                                $ctr_failed++;
                            }
                        } else {
                            $ctr_duplicate++;
                        }
                    }

                    $batch_declaration['created_by'] = ACCOUNT_ID;
                    $batch_declaration['created_when'] = dateTimeStamp();

                    ($batch_declaration['batch_number'] != "") ? Gcash::addBatchDeclaration($batch_declaration) : "";

                    //update register encode
                    $update_encode['id']           = $result['id'];
                    $total_uploaded_rows           = postVar('total_rows');
                    $total_processed_rows          = $ctr_duplicate + $ctr_success + $ctr_failed;
                    // calculate the first validation count for duplicates
                    $first_stage_duplicate_count   = $total_uploaded_rows - $total_processed_rows;
                    $total_duplicate               = $ctr_duplicate + $first_stage_duplicate_count;
                    $update_encode['duplicate']    = $total_duplicate;
                    $update_encode['success']      = $ctr_success;
                    $update_encode['failed']       = $ctr_failed;
                    Gcash::editClaimEncodeSummary($update_encode);

                    $result['alert'] = 'Total Saved = ' . $ctr_success . ' / Total Failed = ' . $ctr_failed . ' / Total Duplicate = ' . $total_duplicate;


                    $batch = ($batch_declaration['batch_number'] != "") ? $batch_declaration['batch_number'] : "N/A";
                    $email_body = Email::emailBodyForClaimUpload($ctr_success, $ctr_duplicate, $ctr_failed, $batch);
                    $email_body = Email::templateDefault($email_body);

                    Email::sendEmail('', 'Claims Upload', $email_body, '', '', $file);
                    moveFile($file, getDocumentRoot() . '/upload/gcash/' . $field['file_temporary'], 'delete');
                } catch (Exception $e) {
                    $result['status']  = 'forbidden';
                    $result['message'] = 'Error loading file "' . pathinfo($file, PATHINFO_BASENAME) . '": ' . $e->getMessage();
                }
            }

            $result['redirect'] = htmlDecode(postVar('redirect'));
        } else {
            $result['status']  = 'forbidden';
            $result['message'] = 'Access to this resource on the server is denied';
        }

        echo json_encode($result);
    }

    public function declaration()
    {
        includeModel(['Account']);

        $data          = array();
        $CONFIGURATION = Configuration::general();

        $account_id           = urldecode(getVar('account_id'));
        $account_ids          = ACCOUNT_ID;

        $account_id           = $account_id == "all" ? '' : $account_id;

        $keyword              = urldecode(getVar('keyword'));
        $data['records']      = Gcash::getClaimDeclarationSummary($keyword, $account_id, pagination('start'), pagination('limit'));

        $data['total_record'] = Gcash::countClaimDeclarationSummary($keyword, $account_id, '');
        $data['total_page']   = pagination('total', $data['total_record']);

        $data['accounts']     = Account::getByStatusId($CONFIGURATION['ACCOUNT_STATUS_ACTIVE']);

        views('gcash.declaration', $data);
    }

    public function importDeclaration()
    {
        $data = array();

        $id                     = idDecrypt(getVar('id'));
        $data['declaration']    = recastArray(Gcash::getClaimDeclarationSummaryById($id));
        $data['action']         = getVar('action');

        views('gcash.import-declaration', $data);
    }

    public function submitDeclarationJson()
    {
        $data = array();
        
        $field['batch_number']       = postVar('batch_number');
        $field['workflow_number']    = postVar('workflow_number');
        $field['endorsement_number'] = postVar('endorsement_number');
        

        if(postVar('action') == 'add') {
            $field['created_by']         = ACCOUNT_ID;
            $field['created_when']       = dateTimeStamp();
            $data['alert'] = Gcash::addClaimDeclarationSummary($field)['message'];
        } else {
            $field['id']                 = postVar('declaration_id');
            $field['updated_by']         = ACCOUNT_ID;
            $field['updated_when']       = dateTimeStamp();
            $data['alert'] = Gcash::updateClaimDeclarationSummary($field)['message'];
        }

        echo json_encode($data);
    }
}
