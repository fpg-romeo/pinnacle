<?php

require_once('app/library/spreadsheet/autoloader.php');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;



class Excel{

    public static function _construct(){

    }

    public static function styleCell($worksheet,$cell,$horizontal = '',$vertical = ''){

        
        if($vertical != ''){
        
            $worksheet->getStyle($cell)->applyFromArray([
                'alignment' => [
                    'horizontal' => $horizontal,
                    'vertical'   => $vertical,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ]);
        } else {
            $worksheet->getStyle($cell)->applyFromArray([
                'alignment' => [
                    'horizontal' => $horizontal,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ]);

        }

    }
}
?>