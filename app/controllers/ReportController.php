<?php
    require './app/library/spreadsheet/autoloader.php';

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Settings;
    use PhpOffice\PhpSpreadsheet\Style\Fill;
    use PhpOffice\PhpSpreadsheet\Style\Alignment;

    class ReportController{
    
        private static $excel_extension = 'xlsx'; 
        private static $content_type    = 'application/xlsx'; 
        private static $create_writer   = 'Excel2007';

        public static $prefix_name      = 'Pinnacle';

        public function __construct() {
            checkLoggedIn('true');

            // // Disable caching to avoid PSR cache errors
            // Settings::setCache(null);
        }

        public function test(){
            $spreadsheet = new Spreadsheet();

            // Get current active sheet
            $sheet = $spreadsheet->getActiveSheet();

            // Write some data
            $sheet->setCellValue('A1', 'Hello World!');

            // Write the file
            $writer = new Xlsx($spreadsheet);
            $writer->save('report-test.xlsx');

            echo "Spreadsheet created successfully.";
        }


        public function schedule(){
            $data = array();

            views('report.schedule', $data);  
        }

        public function exportJson(){

            if(isset($_POST) && !empty($_POST)){

                if(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'edit'){

                    $record = Report::getScheduleById($_POST['id']);
                    if(is_array($record)){    
                        foreach($record as $row){
                            $result['id']               = htmlDecode($row['id']);
                            $result['account_id']       = htmlDecode($row['account_id']);
                            $result['account_name']     = htmlDecode($row['account_name']);
                            $result['report_type']      = htmlDecode($row['report_type']);
                            $result['date_from']        = htmlDecode($row['date_from']);
                            $result['date_to']          = htmlDecode($row['date_to']);
                            $result['demand']           = htmlDecode($row['demand']);
                            $result['status']           = htmlDecode($row['status']);
                            $result['file']             = htmlDecode($row['file']);
                            $result['date_export']      = htmlDecode($row['date_export']);
                            $result['created_when']     = htmlDecode($row['created_when']);
                            $result['updated_when']     = htmlDecode($row['updated_when']);
                        }
                    }

                }elseif(!empty($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete'){

                    $result = Report::deleteSchedule($_POST['id']);
                    
                }else{
                    $field['id']                        = postVar('id', 0);
                    $field['account_id']                = ACCOUNT_ID;
                    $field['report_type']               = postVar('report_type');
                    $field['date_from']                 = date("Y-m-d", strtotime(htmlEncode($_POST['date_from']))); 
                    $field['date_to']                   = date("Y-m-d", strtotime(htmlEncode($_POST['date_to']))); 
                    $field['demand']                    = 'Normal';
                    $field['status']                    = 'Queue';

                    $data = checkRequiredPost(array('report_type', 'date_from', 'date_to'));

                    if(!array_key_exists('error', $data)){  
                        if(!empty($field['id'])){
                            $field['updated_by']        = ACCOUNT_ID;
                            $field['updated_when']      = dateTimeStamp();

                            $result = Report::editSchedule($field);
                        }else{
                            $field['created_by']        = ACCOUNT_ID;
                            $field['created_when']      = dateTimeStamp();

                            $result = Report::addSchedule($field);
                        }
                    }
                }
                
            }else{
                $result['status']  = 'forbidden';
                $result['message'] = 'Access to this resource on the server is denied';
            }
            
            header('Content-Type: application/json');
            echo json_encode($result);
        }

        public static function generator($sheet, $objPHPExcel, $tab_name, $post, $filename, $autosize = true , $multipleSheets = false , $save_only = false){

            if($multipleSheets == false){
                    $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray([
                        'fill'           => [
                            'fillType'   => Fill::FILL_SOLID,
                            'color'      => ['rgb' => '1d2939'],
                        ],
                        'font'           => [
                            'bold'       => true,
                            'color'      => ['rgb' => 'FFFFFF'],
                            'size'       => 11,
                            //'name' => 'Arial',
                        ],
                        'alignment'      => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical'   => Alignment::VERTICAL_TOP,
                        ],
                    ]);

                    //COLUMN WIDTH
                    // Auto size columns for each worksheet
                    foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {

                        $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($worksheet));

                        $sheet = $objPHPExcel->getActiveSheet();
                        $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
                        $cellIterator->setIterateOnlyExistingCells(true);
                        /** @var PHPExcel_Cell $cell */
                        foreach ($cellIterator as $cell) {
                            $autosize = $autosize == true ? true : false; 
                            $sheet->getColumnDimension($cell->getColumn())->setAutoSize($autosize);
                        }
                    }

                    // Rename worksheet
                    $objPHPExcel->getActiveSheet()->setTitle($tab_name);

                    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                    $objPHPExcel->setActiveSheetIndex(0);
                }

                ob_end_clean();
                
                if(empty($post)){
                    if($save_only == false){
                        // Redirect output to a client’s web browser (Excel5)
                        header('Content-Type: '.self::$content_type); // for .xls
                        //header('Content-Type: application/xlsx'); // for .xlsx
                        header('Content-Disposition: attachment;filename="'.$filename.'"');
                        header('Cache-Control: max-age=0');
                        // If you're serving to IE 9, then the following may be needed
                        header('Cache-Control: max-age=1');

                        // If you're serving to IE over SSL, then the following may be needed
                        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
                        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                        header ('Pragma: public'); // HTTP/1.0

                        $objWriter = new Xlsx($objPHPExcel);
                        //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, self::$create_writer); 
                        //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                        ob_end_clean();
                        $objWriter->save('php://output');    
                        exit;
                    }else{
                        // 'upload/temp/invoice-'.date('Ymd').ACCOUNT_ID.$invoice_id;
                        $file_path = getDocumentRoot().'/upload/report/';
                        $objWriter = new Xlsx($objPHPExcel);
                        //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, self::$create_writer); 
                        if (!file_exists($file_path)  && !is_dir($file_path)) {
                            mkdir(getDocumentRoot().'/upload/report/', 0755 , true);
                        }

                        $objWriter->save(str_replace(__FILE__,$file_path.$filename,__FILE__));
                    }
                    
                }

                /*
                if(!empty($post)){
                    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                    $objWriter->save(downloadFile('report', $filename)); 
                    
                    $report['id']           = htmlEncode((!empty($post['id']) ? $post['id'] : ''));       
                    $report['file']         = $filename; 
                    $report['email']        = htmlEncode((!empty($post['account_email']) ? $post['account_email'] : ''));
                    $report['report_type']  = htmlEncode((!empty($post['report_type']) ? $post['report_type'] : ''));
                    $report['date_from']    = htmlEncode((!empty($post['date_from']) ? $post['date_from'] : '')); 
                    $report['date_to']      = htmlEncode((!empty($post['date_to']) ? $post['date_to'] : ''));

                    $result = Shortcode::reportGenerated($report);

                    //return $result;
                }
                */
        }

        public static function account($post=''){
            includeModel(['Master']);
        
            $field['status']            = 'all'; //(!empty(postVar('status')) ? postVar('status') : getVar('status'));
            $field['joined_date_start'] = ''; //(!empty(postVar('joined_date_start')) ? postVar('joined_date_start') : getVar('joined_date_start'));
            $field['joined_date_end']   = ''; //(!empty(postVar('joined_date_end')) ? postVar('joined_date_end') : getVar('joined_date_end'));

            $record = Report::account($field['status'], $field['joined_date_start'], $field['joined_date_end']);
            if(is_array($record)){
                
                //$objPHPExcel = new PHPExcel();
                $objPHPExcel = new Spreadsheet();
                $objPHPExcel->setActiveSheetIndex(0);
                $sheet       = $objPHPExcel->getActiveSheet();
                $row         = '1';
                $col         = "A";
                $filename    = self::$prefix_name.'-Account_'.dateTimeAsId().'.'.self::$excel_extension;
                $tab_name    = 'Main';

                foreach($record as $key => $value){

                    $value['account_role_name'] = '';
                    if(!empty($value['account_role_id'])){

                        $role_list = explode('-', $value['account_role_id']);
                        $role_ctr = 1;
                        foreach($role_list as $role_item){
                            if(count($role_list) > $role_ctr){
                                $role_delimeter = ', ';
                            }else{
                                $role_delimeter = '';
                            }
                            $value['account_role_name'] .= recastArray(Master::getDynamicById('master_account_role', $role_item))['name'].$role_delimeter;
                            $role_ctr++;
                        }

                    }

                    $sheet->setCellValue('A'.$row, htmlDecode($value['account_id']));
                    $sheet->setCellValue('B'.$row, htmlDecode($value['active_directory']));
                    $sheet->setCellValue('C'.$row, htmlDecode($value['first_name']));
                    $sheet->setCellValue('D'.$row, htmlDecode($value['last_name']));
                    $sheet->setCellValue('E'.$row, htmlDecode($value['alias']));
                    $sheet->setCellValue('F'.$row, htmlDecode($value['full_name']));
                    $sheet->setCellValue('G'.$row, htmlDecode($value['email']));
                    $sheet->setCellValue('H'.$row, htmlDecode($value['account_department_name']));
                    $sheet->setCellValue('I'.$row, htmlDecode($value['account_level_name']));
                    $sheet->setCellValue('J'.$row, htmlDecode($value['account_designation_name']));
                    $sheet->setCellValue('K'.$row, htmlDecode($value['account_team_name']));
                    $sheet->setCellValue('L'.$row, htmlDecode($value['report_to_name']));
                    $sheet->setCellValue('M'.$row, htmlDecode($value['account_type_name']));
                    $sheet->setCellValue('N'.$row, htmlDecode($value['account_status_name']));

                    //FORMAT: FOR MONEY - ALIGN RIGHT
                    //$sheet->getStyle('M'.$row)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));

                    $row++;
                }

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'ACCOUNT ID')
                            ->setCellValue('B1', 'ACTIVE DIRECTORY')
                            ->setCellValue('C1', 'FIRST NAME')
                            ->setCellValue('D1', 'LAST NAME')
                            ->setCellValue('E1', 'NICKNAME')
                            ->setCellValue('F1', 'FULL NAME')
                            ->setCellValue('G1', 'EMAIL')
                            ->setCellValue('H1', 'DEPARTMENT')
                            ->setCellValue('I1', 'LEVEL')
                            ->setCellValue('J1', 'DESIGNATION')
                            ->setCellValue('K1', 'UNIT TEAM')
                            ->setCellValue('L1', 'REPORT  TO')
                            ->setCellValue('M1', 'ACCOUNT TYPE')
                            ->setCellValue('N1', 'STATUS');
                            
                self::generator($sheet, $objPHPExcel, $tab_name, $post, $filename);

            }else{
                alertAndRedirect('No record found', '/report/manual/');
            }
        }
    }
?>