<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;    

    class EmailController{
    
        

        public function __construct() {
            checkLoggedIn('true');
        }

        public function generateAttachment(){
         
            require_once __DIR__ . '/../controllers/MasterController.php';
            require_once('app/library/spreadsheet/Spreadsheet.php');
            require_once('app/library/spreadsheet/IOFactory.php');
            require_once('app/library/spreadsheet/autoloader.php');
            require_once('app/models/Soa.php');
            require_once('app/default/database.php');
            

          
            $cutoffdate = strtoupper(date('F Y', strtotime('last month')));
            $getprocessingdate = date('YmdH');
            
            $folderpath = 'upload/attachments/'.$cutoffdate.'/'.$getprocessingdate;

            if(!is_dir($folderpath)){
                mkdir($folderpath, 0777, true);
            }
            
            $with_dst = false;
            $with_cwt = false;


            $premiumreceivable = array();
            $record = array();
            //get the record for processing using policynumber and batchnumber
            $record = Email::getRecord();
            
            if(!empty($record) && is_array($record) && $record['message'] == 'success'){
                
         
                foreach($record as $value){

                    if (!is_array($value)) {
                        error_log("Invalid entry at indexi: " . print_r($value, true));
                        continue;
                    }
                 
                    $workbook = IOFactory::load('upload/soa/default/soa.xlsx');
                    $worksheet = $workbook->getSheetByName('SUMMARY');
                    
                 
                    $zero = 0; 
                    $thirty = 0;
                    $sixty = 0;
                    $ninety = 0;
                    $over = 0;
                    $totalcod = 0;
                    $totalcurrent = 0;
                    $totaloverdue = 0;   
                    $source_name = '';
                    $grandtotal = 0;
                    $totalcwt = 0;
                    $totaldst = 0;
                    $over120 = 0;
                    $totalPremium = 0;
                    $grandtotal = 0;
                    $source_name = $value['SOURCE_NAME'];
                    $worksheet->setCellValue('B4', $source_name); //source name
                    $worksheet->setCellValue('B3', 'For the month of '.$cutoffdate); //cutoff date
                    
                    $premiumData = array();
                    $premiumreceivable = Soa::getDetailed('', $value['as_of_date'], '*', $value['SOURCE_NAME']);
                   
                    if(!empty($premiumreceivable) && is_array($premiumreceivable)){

                        //for the figures in summary
                        foreach ($premiumreceivable as $prem) {
                                $over120 = 0;
                                if (
                                    $prem['121_150_DAYS'] != 0 || 
                                    $prem['151_180_DAYS'] != 0 || 
                                    $prem['181_210_DAYS'] != 0 || 
                                    $prem['211_360_DAYS'] != 0 || 
                                    $prem['DAYS_OVER_361'] != 0
                                ) {
                                    $over120 = 
                                        (double)$prem['121_150_DAYS'] + 
                                        (double)$prem['151_180_DAYS'] + 
                                        (double)$prem['181_210_DAYS'] + 
                                        (double)$prem['211_360_DAYS'] + 
                                        (double)$prem['DAYS_OVER_361'];
                                }

                                $premData =  array(
                                    '0_30_DAYS' => (double)$prem['0_30_DAYS'],
                                    '31_60_DAYS' => (double)$prem['31_60_DAYS'],
                                    '61_90_DAYS' => (double)$prem['61_90_DAYS'],
                                    '91_120_DAYS' => (double)$prem['91_120_DAYS'],
                                    'OVER_180_DAYS' => $over120,
                                    'totalreceivable' => (double)$prem['0_30_DAYS'] + (double)$prem['31_60_DAYS'] + (double)$prem['61_90_DAYS'] + (double)$prem['91_120_DAYS'] + $over120,
                                );

                                        $zero += $premData['0_30_DAYS'] ? $premData['0_30_DAYS'] : 0;
                                        $thirty += $premData['31_60_DAYS'] ? $premData['31_60_DAYS'] : 0;
                                        $sixty += $premData['61_90_DAYS'] ? $premData['61_90_DAYS'] : 0;
                                        $ninety += $premData['91_120_DAYS'] ? $premData['91_120_DAYS'] : 0;
                                        $over += $premData['OVER_180_DAYS'] ? $premData['OVER_180_DAYS'] : 0;
                            }
                       

                            //initialize the worksheet fields
                                    $worksheet->setCellValue('C18', '0');
                                    $worksheet->setCellValue('C19', '0');
                                    $worksheet->setCellValue('C20', '0');
                                    $worksheet->setCellValue('C21', '0');
                                    $worksheet->setCellValue('C22', '0');
                                    $worksheet->setCellValue('C23', '0');
                                    $worksheet->setCellValue('C24', '0');
                                    $worksheet->setCellValue('C25', '0');

                                    
                                    $worksheet->setCellValue('C18', $zero); 
                                    $worksheet->setCellValue('C19', $thirty); 
                                    $worksheet->setCellValue('C20', $sixty); 

                                    $totalcurrent = $zero + $thirty + $sixty;
                                    $worksheet->setCellValue('C21', $totalcurrent);

                                    $worksheet->setCellValue('C22', $ninety);
                                    $worksheet->setCellValue('C23', $over);

                                    $totaloverdue = $ninety + $over ;
                                    $worksheet->setCellValue('C24', $totaloverdue);

                                    $totalPremium = $totalcurrent + $totaloverdue;
                                    $worksheet->setCellValue('C25', $totalPremium);

                                    $detailedList = $workbook->getSheetByName('DETAILED LIST');

                                    //initialize the worksheet fields
                                    $detailedList->setCellValue('A2', '');
                                    $detailedrecord = array();
                                    $startRow = 2;
                                    $endRow = 10000;
                                    $currentrow = $startRow;  

                                    foreach($premiumreceivable as $detail){
                                       
                                        //initialize the sheet
                                        $columns = range('A', 'AD');

                                        for ($row = $startRow; $row <= $endRow; $row++) {
                                            foreach ($columns as $col) {
                                                $detailedList->setCellValue($col.$row, '');
                                            }
                                        }
                                            
                                            $detailedList->setCellValue('A'.$currentrow, $detail['BOOKING_DATE']);
                                            $detailedList->setCellValue('B'.$currentrow, $detail['INCEPTION_DATE']);
                                            $detailedList->setCellValue('C'.$currentrow, $detail['EXPIRY_DATE']);
                                            $detailedList->setCellValue('D'.$currentrow, $detail['EFFECTIVE_DATE']);
                                            $detailedList->setCellValue('E'.$currentrow, $detail['VOUCHER_DEBIT_CREDIT_PREMIUM']);
                                            $detailedList->setCellValue('F'.$currentrow, $detail['VOUCHER_DEBIT_CREDIT_COMMISSION']);
                                            $detailedList->setCellValue('G'.$currentrow, $detail['OVERIDING_VOUCHER_DEBIT_CREDIT']);
                                            $detailedList->setCellValue('H'.$currentrow, $detail['REFNO']);
                                            $detailedList->setCellValue('I'.$currentrow, $detail['DOCNO']);
                                            $detailedList->setCellValue('J'.$currentrow, $detail['A_POLICYNO']);
                                            $detailedList->setCellValue('K'.$currentrow, $detail['INSURED_NAME']);
                                            $detailedList->setCellValue('L'.$currentrow, $detail['POSTED_PAYMENT']);
                                            $detailedList->setCellValue('M'.$currentrow, $detail['ORIGINAL_BASIC_PREMIUM']);
                                            $detailedList->setCellValue('N'.$currentrow, $detail['PREMIUM']);
                                            $detailedList->setCellValue('O'.$currentrow, $detail['STAMPDUTY']);
                                            $detailedList->setCellValue('P'.$currentrow, $detail['LTO']);
                                            $detailedList->setCellValue('Q'.$currentrow, $detail['LGT']);
                                            $detailedList->setCellValue('R'.$currentrow, $detail['FST']);
                                            $detailedList->setCellValue('S'.$currentrow, $detail['PREMIUMTAX']);
                                            $detailedList->setCellValue('T'.$currentrow, $detail['VAT']); 
                                            $detailedList->setCellValue('U'.$currentrow, $detail['GROSS_PREMIUM']);
                                            $detailedList->setCellValue('V'.$currentrow, $detail['OVERRIDING_DISCOUNT']);
                                            $detailedList->setCellValue('W'.$currentrow, $detail['COMMISSION']);
                                            $detailedList->setCellValue('X'.$currentrow, $detail['INPUT_VAT']);
                                            $detailedList->setCellValue('Y'.$currentrow, $detail['TAXRATE']);
                                            $detailedList->setCellValue('Z'.$currentrow, $detail['TAX_AMOUNT']);
                                            $detailedList->setCellValue('AA'.$currentrow, $detail['GROSS_COMMISSION']);
                                            $detailedList->setCellValue('AB'.$currentrow, $detail['NET_DUE']); 
                                            $detailedList->setCellValue('AC'.$currentrow, $detail['AGING_DAYS']);
                                            $detailedList->setCellValue('AD'.$currentrow, $detail['AGING_BUCKET']);
                                            
                                            $currentrow++;
                                    
                        }
                                    
                                    
         
                    }

                    //DST
                    $taxreceivable = array();
                    $taxreceivable = Soa::getDst('', $value['as_of_date'], '*', $value['SOURCE_NAME']); 
                
                    if (isset($taxreceivable) && is_array($taxreceivable)) {
                        
                                        $zero = 0;
                                        $thirty = 0;
                                        $sixty = 0;
                                        $ninety = 0;
                                        $over = 0;
                                        $totalcurrent = 0;
                                        $totaloverdue = 0;   
                                        $source_named = '';

                            //initialize the worksheet fields
                                        $worksheet->setCellValue('C30', '0');
                                        $worksheet->setCellValue('C31', '0');
                                        $worksheet->setCellValue('C32', '0');
                                        $worksheet->setCellValue('C33', '0');
                                        $worksheet->setCellValue('C34', '0');
                                        $worksheet->setCellValue('C35', '0');
                                        $worksheet->setCellValue('C36', '0');
                                        $worksheet->setCellValue('C37', '0');

                            foreach ($taxreceivable as $dst) {
                                $over120 = 0;
                                if (
                                    $dst['121_150_DAYS'] != 0 || 
                                    $dst['151_180_DAYS'] != 0 || 
                                    $dst['181_210_DAYS'] != 0 || 
                                    $dst['211_360_DAYS'] != 0 || 
                                    $dst['DAYS_OVER_361'] != 0
                                ) {
                                    $over120 = 
                                        (double)$dst['121_150_DAYS'] + 
                                        (double)$dst['151_180_DAYS'] + 
                                        (double)$dst['181_210_DAYS'] + 
                                        (double)$dst['211_360_DAYS'] + 
                                        (double)$dst['DAYS_OVER_361'];
                                }

                                $dstData =  array(
                                    '0_30_DAYS' => (double)$dst['0_30_DAYS'],
                                    '31_60_DAYS' => (double)$dst['31_60_DAYS'],
                                    '61_90_DAYS' => (double)$dst['61_90_DAYS'],
                                    '91_120_DAYS' => (double)$dst['91_120_DAYS'],
                                    'OVER_180_DAYS' => $over120,
                                    'totalreceivable' => (double)$dst['0_30_DAYS'] + (double)$dst['31_60_DAYS'] + (double)$dst['61_90_DAYS'] + (double)$dst['91_120_DAYS'] + $over120,
                                );

                                        $zero += $dstData['0_30_DAYS'] ? $dstData['0_30_DAYS'] : 0;
                                        $thirty += $dstData['31_60_DAYS'] ? $dstData['31_60_DAYS'] : 0;
                                        $sixty += $dstData['61_90_DAYS'] ? $dstData['61_90_DAYS'] : 0;
                                        $ninety += $dstData['91_120_DAYS'] ? $dstData['91_120_DAYS'] : 0;
                                        $over += $dstData['OVER_180_DAYS'] ? $dstData['OVER_180_DAYS'] : 0;
                            }

                                $worksheet->setCellValue('C30', $zero); 
                                $worksheet->setCellValue('C31', $thirty); 
                                $worksheet->setCellValue('C32', $sixty); 

                                $totalcurrentd = $zero + $thirty + $sixty;
                                $worksheet->setCellValue('C33', $totalcurrent ? $totalcurrent : 0);

                                $worksheet->setCellValue('C34', $ninety);
                                $worksheet->setCellValue('C35', $over);

                                $totaloverdue = $ninety + $over;
                                $worksheet->setCellValue('C36', $totaloverdue ? $totaloverdue : 0);

                                $totaldst = $totalcurrent + $totaloverdue;
                                $worksheet->setCellValue('C37', $totaldst ? $totaldst : 0);
                            
                                $dstlist = $workbook->getSheetByName('DST BALANCE');

                                $startRow = 2;
                                $endRow = 10000;
                                $currentrow = $startRow; 
                                        //initialize the sheet
                                $columns = range('A', 'AD');

                                for ($row = $startRow; $row <= $endRow; $row++) {
                                    foreach ($columns as $col) {
                                                $dstlist->setCellValue($col.$row, '');
                                        }
                                }

                                 foreach($taxreceivable as $detaildst){
                                        
                                        $dstlist->setCellValue('A'.$currentrow, $detaildst['BOOKING_DATE']);
                                        $dstlist->setCellValue('B'.$currentrow, $detaildst['INCEPTION_DATE']);
                                        $dstlist->setCellValue('C'.$currentrow, $detaildst['EXPIRY_DATE']);
                                        $dstlist->setCellValue('D'.$currentrow, $detaildst['EFFECTIVE_DATE']);
                                        $dstlist->setCellValue('E'.$currentrow, $detaildst['VOUCHER_DEBIT_CREDIT_PREMIUM']);
                                        $dstlist->setCellValue('F'.$currentrow, $detaildst['VOUCHER_DEBIT_CREDIT_COMMISSION']);
                                        $dstlist->setCellValue('G'.$currentrow, $detaildst['OVERIDING_VOUCHER_DEBIT_CREDIT']);
                                        $dstlist->setCellValue('H'.$currentrow, $detaildst['REFNO']);
                                        $dstlist->setCellValue('I'.$currentrow, $detaildst['DOCNO']);
                                        $dstlist->setCellValue('J'.$currentrow, $detaildst['A_POLICYNO']);
                                        $dstlist->setCellValue('K'.$currentrow, $detaildst['INSURED_NAME']);
                                        $dstlist->setCellValue('L'.$currentrow, $detaildst['POSTED_PAYMENT']);
                                        $dstlist->setCellValue('M'.$currentrow, $detaildst['ORIGINAL_BASIC_PREMIUM']);
                                        $dstlist->setCellValue('N'.$currentrow, $detaildst['PREMIUM']);
                                        $dstlist->setCellValue('O'.$currentrow, $detaildst['STAMPDUTY']);
                                        $dstlist->setCellValue('P'.$currentrow, $detaildst['LTO']);
                                        $dstlist->setCellValue('Q'.$currentrow, $detaildst['LGT']);
                                        $dstlist->setCellValue('R'.$currentrow, $detaildst['FST']);
                                        $dstlist->setCellValue('S'.$currentrow, $detaildst['PREMIUMTAX']);
                                        $dstlist->setCellValue('T'.$currentrow, $detaildst['VAT']); 
                                        $dstlist->setCellValue('U'.$currentrow, $detaildst['GROSS_PREMIUM']);
                                        $dstlist->setCellValue('V'.$currentrow, $detaildst['OVERRIDING_DISCOUNT']);
                                        $dstlist->setCellValue('W'.$currentrow, $detaildst['COMMISSION']);
                                        $dstlist->setCellValue('X'.$currentrow, $detaildst['INPUT_VAT']);
                                        $dstlist->setCellValue('Y'.$currentrow, $detaildst['TAXRATE']);
                                        $dstlist->setCellValue('Z'.$currentrow, $detaildst['TAX_AMOUNT']);
                                        $dstlist->setCellValue('AA'.$currentrow, $detaildst['GROSS_COMMISSION']);
                                        $dstlist->setCellValue('AB'.$currentrow, $detaildst['NET_DUE']); 
                                        $dstlist->setCellValue('AC'.$currentrow, $detaildst['AGING_DAYS']);
                                        $dstlist->setCellValue('AD'.$currentrow, $detaildst['AGING_BUCKET']);
                                        
                                        $currentrow++;
                                }

                    }

                    $taxreceivablecwt = array();
                    $taxreceivablecwt = Soa::getCWT('', $value['as_of_date'], '*', $value['SOURCE_NAME']);

                     if (isset($taxreceivablecwt)) {

                        $zero = 0;
                        $thirty = 0;
                        $sixty = 0;
                        $ninety = 0;
                        $over = 0;
                        $totalcurrent = 0;
                        $totaloverdue = 0;

                                    $worksheet->setCellValue('D30', '0');
                                    $worksheet->setCellValue('D31', '0');
                                    $worksheet->setCellValue('D32', '0');
                                    $worksheet->setCellValue('D33', '0');
                                    $worksheet->setCellValue('D34', '0');
                                    $worksheet->setCellValue('D35', '0');
                                    $worksheet->setCellValue('D36', '0');
                                    $worksheet->setCellValue('D37', '0');

                            foreach ($taxreceivablecwt as $cwt) {
                                $over120 = 0;
                                if (
                                    $cwt['121_150_DAYS'] != 0 || 
                                    $cwt['151_180_DAYS'] != 0 || 
                                    $cwt['181_210_DAYS'] != 0 || 
                                    $cwt['211_360_DAYS'] != 0 || 
                                    $cwt['DAYS_OVER_361'] != 0
                                ) {
                                    $over120 = 
                                        (double)$cwt['121_150_DAYS'] + 
                                        (double)$cwt['151_180_DAYS'] + 
                                        (double)$cwt['181_210_DAYS'] + 
                                        (double)$cwt['211_360_DAYS'] + 
                                        (double)$cwt['DAYS_OVER_361'];
                                }

                                $cwtData = array(
                                    '0_30_DAYS' => (double)$cwt['0_30_DAYS'],
                                    '31_60_DAYS' => (double)$cwt['31_60_DAYS'],
                                    '61_90_DAYS' => (double)$cwt['61_90_DAYS'],
                                    '91_120_DAYS' => (double)$cwt['91_120_DAYS'],
                                    'OVER_180_DAYS' => $over120,
                                    'totalreceivable' => (double)$cwt['0_30_DAYS'] + (double)$cwt['31_60_DAYS'] + (double)$cwt['61_90_DAYS'] + (double)$cwt['91_120_DAYS'] + $over120,
                                );
                            }
  
                            $worksheet->setCellValue('D30', $zero); 
                            $worksheet->setCellValue('D31', $thirty); 
                            $worksheet->setCellValue('D32', $sixty); 

                            $totalcurrent = $zero + $thirty + $sixty;
                            $worksheet->setCellValue('D33', $totalcurrent);

                            $worksheet->setCellValue('D34', $ninety);
                            $worksheet->setCellValue('D35', $over);

                            $totaloverdue = $ninety + $over;
                            $worksheet->setCellValue('D36', $totaloverdue ? $totaloverdue : 0);

                            $totalcwt = $totalcurrent + $totaloverdue;
                            $worksheet->setCellValue('D37', $totalcwt ? $totalcwt : 0);
                        
                            $cwtlist = $workbook->getSheetByName('CWT BALANCE');

                                $startRow = 2;
                                $endRow = 10000;
                                $currentrow = $startRow;
                                
                                $columns = range('A', 'AD');

                                for ($row = $startRow; $row <= $endRow; $row++) {
                                    foreach ($columns as $col) {
                                               $cwtlist->setCellValue($col.$row, '');
                                    }
                                }


                                foreach($taxreceivablecwt as $detailcwt){
                                    
                                    $cwtlist->setCellValue('A'.$currentrow, $detailcwt['BOOKING_DATE']);
                                    $cwtlist->setCellValue('B'.$currentrow, $detailcwt['INCEPTION_DATE']);
                                    $cwtlist->setCellValue('C'.$currentrow, $detailcwt['EXPIRY_DATE']);
                                    $cwtlist->setCellValue('D'.$currentrow, $detailcwt['EFFECTIVE_DATE']);
                                    $cwtlist->setCellValue('E'.$currentrow, $detailcwt['VOUCHER_DEBIT_CREDIT_PREMIUM']);
                                    $cwtlist->setCellValue('F'.$currentrow, $detailcwt['VOUCHER_DEBIT_CREDIT_COMMISSION']);
                                    $cwtlist->setCellValue('G'.$currentrow, $detailcwt['OVERIDING_VOUCHER_DEBIT_CREDIT']);
                                    $cwtlist->setCellValue('H'.$currentrow, $detailcwt['REFNO']);
                                    $cwtlist->setCellValue('I'.$currentrow, $detailcwt['DOCNO']);
                                    $cwtlist->setCellValue('J'.$currentrow, $detailcwt['A_POLICYNO']);
                                    $cwtlist->setCellValue('K'.$currentrow, $detailcwt['INSURED_NAME']);
                                    $cwtlist->setCellValue('L'.$currentrow, $detailcwt['POSTED_PAYMENT']);
                                    $cwtlist->setCellValue('M'.$currentrow, $detailcwt['ORIGINAL_BASIC_PREMIUM']);
                                    $cwtlist->setCellValue('N'.$currentrow, $detailcwt['PREMIUM']);
                                    $cwtlist->setCellValue('O'.$currentrow, $detailcwt['STAMPDUTY']);
                                    $cwtlist->setCellValue('P'.$currentrow, $detailcwt['LTO']);
                                    $cwtlist->setCellValue('Q'.$currentrow, $detailcwt['LGT']);
                                    $cwtlist->setCellValue('R'.$currentrow, $detailcwt['FST']);
                                    $cwtlist->setCellValue('S'.$currentrow, $detailcwt['PREMIUMTAX']);
                                    $cwtlist->setCellValue('T'.$currentrow, $detailcwt['VAT']); 
                                    $cwtlist->setCellValue('U'.$currentrow, $detailcwt['GROSS_PREMIUM']);
                                    $cwtlist->setCellValue('V'.$currentrow, $detailcwt['OVERRIDING_DISCOUNT']);
                                    $cwtlist->setCellValue('W'.$currentrow, $detailcwt['COMMISSION']);
                                    $cwtlist->setCellValue('X'.$currentrow, $detailcwt['INPUT_VAT']);
                                    $cwtlist->setCellValue('Y'.$currentrow, $detailcwt['TAXRATE']);
                                    $cwtlist->setCellValue('Z'.$currentrow, $detailcwt['TAX_AMOUNT']);
                                    $cwtlist->setCellValue('AA'.$currentrow, $detailcwt['GROSS_COMMISSION']);
                                    $cwtlist->setCellValue('AB'.$currentrow, $detailcwt['NET_DUE']); 
                                    $cwtlist->setCellValue('AC'.$currentrow, $detailcwt['AGING_DAYS']);
                                    $cwtlist->setCellValue('AD'.$currentrow, $detailcwt['AGING_BUCKET']);
                                    
                                    $currentrow++;
                                }
                          
                    }

                    $codreceivable = array();
                    $codreceivable =  Soa::getCOD('', $value['as_of_date'], '*', $value['SOURCE_NAME']);
                    
                    if (isset($codreceivable)) {
                      
                        $zero = 0;
                        $thirty = 0;
                        $sixty = 0;
                        $ninety = 0;
                        $over = 0;
                        $totalcurrent = 0;
                        $totaloverdue = 0;

                                    $worksheet->setCellValue('C8', '0');
                                    $worksheet->setCellValue('C9', '0');
                                    $worksheet->setCellValue('C10', '0');
                                    $worksheet->setCellValue('C11', '0');
                                    $worksheet->setCellValue('C12', '0');
                                    $worksheet->setCellValue('C13', '0');


                            foreach ($codreceivable as $cod) {
                                $over120 = 0;
                                if (
                                    $cod['121_150_DAYS'] != 0 || 
                                    $cod['151_180_DAYS'] != 0 || 
                                    $cod['181_210_DAYS'] != 0 || 
                                    $cod['211_360_DAYS'] != 0 || 
                                    $cod['DAYS_OVER_361'] != 0
                                ) {
                                    $over120 = 
                                        (double)$cod['121_150_DAYS'] + 
                                        (double)$cod['151_180_DAYS'] + 
                                        (double)$cod['181_210_DAYS'] + 
                                        (double)$cod['211_360_DAYS'] + 
                                        (double)$cod['DAYS_OVER_361'];
                                }

                                $cwtData = array(
                                    '0_30_DAYS' => (double)$cod['0_30_DAYS'],
                                    '31_60_DAYS' => (double)$cod['31_60_DAYS'],
                                    '61_90_DAYS' => (double)$cod['61_90_DAYS'],
                                    '91_120_DAYS' => (double)$cod['91_120_DAYS'],
                                    'OVER_180_DAYS' => $over120,
                                    'totalreceivable' => (double)$cod['0_30_DAYS'] + (double)$cod['31_60_DAYS'] + (double)$cod['61_90_DAYS'] + (double)$cod['91_120_DAYS'] + $over120,
                                );

                                        $zero += $cwtData['0_30_DAYS'] ? $cwtData['0_30_DAYS'] : 0;
                                        $thirty += $cwtData['31_60_DAYS'] ? $cwtData['31_60_DAYS'] : 0;
                                        $sixty += $cwtData['61_90_DAYS'] ? $cwtData['61_90_DAYS'] : 0;
                                        $ninety += $cwtData['91_120_DAYS'] ? $cwtData['91_120_DAYS'] : 0;
                                        $over += $cwtData['OVER_180_DAYS'] ? $cwtData['OVER_180_DAYS'] : 0;
                            }
  
                            $worksheet->setCellValue('C8', $zero); 
                            $worksheet->setCellValue('C9', $thirty); 
                            $worksheet->setCellValue('C10', $sixty); 
                            $worksheet->setCellValue('C11', $ninety);
                            $worksheet->setCellValue('C12', $over);

                            $totalcod = $ninety + $over + $zero + $thirty + $sixty;
                            $worksheet->setCellValue('C13', $totalcod ? $totalcod : 0);
                        
                            $codlist = $workbook->getSheetByName('COD POLICIES');

                                $startRow = 2;
                                $endRow = 10000;
                                $currentrow = $startRow;
                                
                                $columns = range('A', 'AD');

                                for ($row = $startRow; $row <= $endRow; $row++) {
                                    foreach ($columns as $col) {
                                               $codlist->setCellValue($col.$row, '');
                                    }
                                }


                                foreach($codreceivable as $detailcod){
                                    
                                    $codlist->setCellValue('A'.$currentrow, $detailcod['BOOKING_DATE']);
                                    $codlist->setCellValue('B'.$currentrow, $detailcod['INCEPTION_DATE']);
                                    $codlist->setCellValue('C'.$currentrow, $detailcod['EXPIRY_DATE']);
                                    $codlist->setCellValue('D'.$currentrow, $detailcod['EFFECTIVE_DATE']);
                                    $codlist->setCellValue('E'.$currentrow, $detailcod['VOUCHER_DEBIT_CREDIT_PREMIUM']);
                                    $codlist->setCellValue('F'.$currentrow, $detailcod['VOUCHER_DEBIT_CREDIT_COMMISSION']);
                                    $codlist->setCellValue('G'.$currentrow, $detailcod['OVERIDING_VOUCHER_DEBIT_CREDIT']);
                                    $codlist->setCellValue('H'.$currentrow, $detailcod['REFNO']);
                                    $codlist->setCellValue('I'.$currentrow, $detailcod['DOCNO']);
                                    $codlist->setCellValue('J'.$currentrow, $detailcod['A_POLICYNO']);
                                    $codlist->setCellValue('K'.$currentrow, $detailcod['INSURED_NAME']);
                                    $codlist->setCellValue('L'.$currentrow, $detailcod['POSTED_PAYMENT']);
                                    $codlist->setCellValue('M'.$currentrow, $detailcod['ORIGINAL_BASIC_PREMIUM']);
                                    $codlist->setCellValue('N'.$currentrow, $detailcod['PREMIUM']);
                                    $codlist->setCellValue('O'.$currentrow, $detailcod['STAMPDUTY']);
                                    $codlist->setCellValue('P'.$currentrow, $detailcod['LTO']);
                                    $codlist->setCellValue('Q'.$currentrow, $detailcod['LGT']);
                                    $codlist->setCellValue('R'.$currentrow, $detailcod['FST']);
                                    $codlist->setCellValue('S'.$currentrow, $detailcod['PREMIUMTAX']);
                                    $codlist->setCellValue('T'.$currentrow, $detailcod['VAT']); 
                                    $codlist->setCellValue('U'.$currentrow, $detailcod['GROSS_PREMIUM']);
                                    $codlist->setCellValue('V'.$currentrow, $detailcod['OVERRIDING_DISCOUNT']);
                                    $codlist->setCellValue('W'.$currentrow, $detailcod['COMMISSION']);
                                    $codlist->setCellValue('X'.$currentrow, $detailcod['INPUT_VAT']);
                                    $codlist->setCellValue('Y'.$currentrow, $detailcod['TAXRATE']);
                                    $codlist->setCellValue('Z'.$currentrow, $detailcod['TAX_AMOUNT']);
                                    $codlist->setCellValue('AA'.$currentrow, $detailcod['GROSS_COMMISSION']);
                                    $codlist->setCellValue('AB'.$currentrow, $detailcod['NET_DUE']); 
                                    $codlist->setCellValue('AC'.$currentrow, $detailcod['AGING_DAYS']);
                                    $codlist->setCellValue('AD'.$currentrow, $detailcod['AGING_BUCKET']);
                                    
                                    $currentrow++;
                                }
                          
                    }



                    $grandtotal = 0;
                    $grandtotal = $totalPremium + $totaldst + $totalcwt +  $totalcod;
                    $worksheet->setCellValue('D39',$grandtotal);

                    $filename = $value['SOURCE_NAME'].'- Statement of Account as of '.$cutoffdate.'.xlsx';
                    $writer = new Xlsx($workbook);
                    $writer->save($folderpath.'/'.$filename );
                }
            }
        }
    }
   
          