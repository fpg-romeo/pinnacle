<?php 
includeLibrary(['tcpdf/tcpdf.php']);

class soaFile extends TCPDF{
    protected $last_page_flag = false;

    public function Header(){

        $CONFIGURATION = Configuration::general();

        $image = getSiteUrl().'/template/image/logo.png';

        $this->Rect(11.5, 8, 188.5, 25,'F',array(),array(247, 245, 244));
        $this->Image($image, 179, 11, 18, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        $this->SetY(7);
        $this->SetX(13);
        $this->SetFont('helvetica', 'B',12);
        $this->SetTextColor(255,107,0);
        $this->Cell(0, 10, $CONFIGURATION['SYSTEM_COMPANY'], 0, false, 'L', 0, '', 0, false, 'T', 'M');

        $this->SetY(7);
        $this->SetX(13);
        $this->SetFont('helvetica', '',8);
        $this->SetTextColor(0,0,0);
        $this->Cell(0, 22, $CONFIGURATION['SYSTEM_COMPANY_ADDRESS'], 0, false, 'L', 0, '', 0, false, 'T', 'M');

        $this->SetY(7);
        $this->SetX(13);
        $this->Cell(0, 30, 'Tel: '.$CONFIGURATION['SYSTEM_COMPANY_CONTACT_NO'], 0, false, 'L', 0, '', 0, false, 'T', 'M');

        $this->SetY(7);
        $this->SetX(13);
        $this->Cell(0, 38, 'Email: '.$CONFIGURATION['SYSTEM_EMAIL'], 0, false, 'L', 0, '', 0, false, 'T', 'M');

        $this->SetY(7);
        $this->SetX(13);
        $this->SetTextColor(255,107,0);
        $this->Write(46, $CONFIGURATION['SYSTEM_COMPANY_URL'], $CONFIGURATION['SYSTEM_COMPANY_URL'], false, 'L', true);

        $this->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(128, 24, 61)));
        $this->Line(11.5, 33, 200, 33, '');
    }

    public function Close() {
        $this->last_page_flag = true;
        parent::Close();
    }
    
    public function Footer(){
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

        if( !$this->last_page_flag ){
            $this->SetY(-5);
            $this->SetFont('helvetica', 'I', 8);
            $this->Cell(0, 10, 'Please refer to page '.$this->getAliasNbPages().' for Terms & Conditions', 0, false, 'L', 0, '', 0, false, 'B', 'M');
        }
    }
    
}

class pdf{
    public static function generate($filename="", $body="", $template="", $option="", $filepath="", $watermark="",$other_header_details=""){
        
        $pdf = new soaFile(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetAutoPageBreak(TRUE, 15);
        $margin_top = 20;

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
        //$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        // $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetMargins(10, 35, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        // $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        // $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        // $pdf->SetAutoPageBreak(TRUE, 15);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('Helvetica','',7.5);
        $pdf->AddPage();

        $content = '';
        
        if(is_array($body)){

            foreach($body as $key => $value){

                $pdf->setPrintHeader(false);

                if((count($body) - 1) == $key){
                    $pdf->SetMargins(10, $margin_top, 10);
                }else{
                    $pdf->SetMargins(10, 10, 10);
                }

                if($key>0){
                    $pdf->SetMargins(10, 10, 10);
                    $pdf->AddPage();
                }
                $pdf->writeHTML($content.$value, true, false, true, false, '');
            }
        }else{
            // $pdf->SetAutoPageBreak(false, 0);
            $pdf->setHeaderTemplateAutoreset(false);
            $pdf->writeHTML($content.$body, true, false, true, false, '');
        }

        if($watermark == True){
            $pageNo = 1;
            if(is_array($body)){
                foreach($body as $page) {
                    $pdf->StartTransform();
                    $pdf->Rotate(45, 115, 115);
                    $pdf->SetDrawColor(255,107,0);
                    $pdf->SetFont("helvetica", "", 45);
                    $pdf->setTextRenderingMode($stroke=0.2, $fill=false, $clip=false);
                    $pdf->SetY(120);
                    $pdf->SetX(10);
                    $pdf->Write(0, 'FPG Insurance Co. Inc.', '', 0, '', true, 0, false, false, 0);
                    $pdf->StopTransform();
                    $pdf->setPage($pageNo);
                    $pageNo++;
                }
            }else{
                $pdf->StartTransform();
                $pdf->Rotate(45, 115, 115);
                $pdf->SetDrawColor(255,107,0);
                $pdf->SetFont("helvetica", "", 45);
                $pdf->setTextRenderingMode($stroke=0.2, $fill=false, $clip=false);
                $pdf->SetY(120);
                $pdf->SetX(10);
                $pdf->Write(0, 'FPG Insurance Co. Inc.', '', 0, '', true, 0, false, false, 0);
                $pdf->StopTransform();
                $pdf->setPage($pageNo);
            }
        }

        $pdf->lastPage();
        if(ob_get_level() > 0) {
            ob_end_clean();
        }

        if($option == "view"){
            $pdf->Output($filename.'.pdf', "I"); //view pdf

        }elseif($option == "download"){
            $pdf->Output($filename.'.pdf', "D"); //download pdf

        }elseif($option == "attachment"){

            if(!file_exists($filepath)){
                mkdir($filepath, 0777, true);
            }
            $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $filename = !empty($file_ext) && $file_ext == "pdf" ? $filename : $filename.'.pdf';
            $pdf->Output($filepath."/".$filename, "F"); //save pdf to folder as attachment
        }else{
            $pdf->Output($filename.'.pdf', "I"); //view pdf
        }
    }
}
?>