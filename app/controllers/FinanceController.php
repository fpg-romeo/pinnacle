<?php
    class FinanceController{
        
        public function __construct() {
            checkLoggedIn('true');
        }

        public function soaImmediate(){
            $data = array();

            views('finance.soa-immediate', $data);  
        } 

        public function soaScheduled(){
            $data = array();

            views('finance.soa-scheduled', $data);  
        } 

        public function soaSetting(){
            $data = array();

            views('finance.soa-setting', $data);  
        } 

        public function soaDownload(){
            $data = array();

            views('finance.soa-download', $data);  
        } 

        public function importSoaDownload(){
            $data = array();

            views('finance.import-soa-download', $data);  
        } 

        public function importSoaDownloadJson(){
            $result = array();

            echo json_encode($result); 
        } 

        public function soaEmailTemplate(){
            $data = array();

            views('finance.soa-email-template', $data);  
        } 

        public function soaEmailGeneric(){
            $data = array();

            views('finance.soa-email-generic', $data);  
        } 

        public function soaMaster(){
            $data = array();
            includeModel('Master');

            $keyword                    = urldecode(getVar('keyword'));
            $data['masterlists']        = Finance::getAllMasterlists($keyword, pagination('start'), pagination('limit'));
            $data['total_record']       = Finance::countAllMasterlist($keyword);
            $data['total_page']         = pagination('total', $data['total_record']); 

            $data['intermediaries']     = Master::getActiveIntermediary();
            $data['branches']           = Master::getActiveBranches();
            $data['segments']           = Master::getActiveSegments();
            $data['sales_channels']     = Master::getActiveSalesChannels();
            $data['topros']             = Master::getActiveTOPROs();
            $data['cobs']               = Master::getActiveCOBs();
            $data['handlers']           = Master::getActiveHandlers();
            $data['team_leaders']       = Master::getActiveTeamLeaders();
            
            if(isset($_POST['action'])){
                $field['master_list'] = array(
                    'intermediary_id'      => postVar('intermediary_id'),
                    'handler_id'           => postVar('handler_id'),
                    'team_leader_id'       => postVar('team_leader_id'),
                    'intermediary_code'    => postVar('intermediary_code'),
                    'categories'           => $_POST['categories'] != "" ? '["'.$_POST['categories'].'"]' : "",
                    'account_name'         => postVar('account_name'),
                    'created_at'           => date('Y-m-d H:i:s'),
                    'is_active'            => $_POST['is_active'],
                );

                $field['branch']               = isset($_POST['branch']) && $_POST['branch'][0] != "" ? $_POST['branch'] : "";
                $field['segment']              = isset($_POST['segment']) && $_POST['segment'][0] != "" ? $_POST['segment'] : "";
                $field['insured_name']         = postVar('insured_name');
                $field['sales_channel']        = isset($_POST['sales_channel']) && $_POST['sales_channel'][0] != "" ? $_POST['sales_channel'] : "";
                $field['topro']                = isset($_POST['topro']) && $_POST['topro'][0] != "" ? $_POST['topro'] : "";
                $field['class_of_business']    = isset($_POST['class_of_business']) && $_POST['class_of_business'][0] != "" ? $_POST['class_of_business'] : "";
                $field['or_recipients']        = isset($_POST['or_recipients']) && $_POST['or_recipients'][0] != "" ? $_POST['or_recipients'] : "";
                $field['soa_recipients']       = isset($_POST['soa_recipients']) && $_POST['soa_recipients'][0] != "" ? $_POST['soa_recipients'] : "";

                if($_POST['action'] == "add"){
                   $id = Finance::addMasterList($field);
                   
                }

                if($_POST['action'] == "edit"){
                   $id = Finance::editMasterList($_POST['id'], $field);
                }

                header('Location: /finance/soa-master/1');
            }

            views('finance.soa-master', $data);  
        } 

        public function soaLetter(){
            $data = array();
            $id = getVar('id');
            if($id > 0){
                $data['letter'] = recastArray(Finance::getLetterTemplateById($id));
               
                $categoriesDecode = json_decode($data['letter']['categories'], true);

                $categories = array_map(function($item) {
                    return json_encode([$item]);
                }, $categoriesDecode);

                $data['source_name'] = Finance::getSourceNameByCategories("'" . implode("', '", $categories) . "'");

                views('finance.soa-letter-setting', $data);
            }
            else{
                $data['letter'] = Finance::getLetterTemplates();
                views('finance.soa-letter', $data);
            }

        } 

        public function soaLetter_json(){
            $result = Finance::updateSoaLetter($_POST);
            echo json_encode($result);
        } 

        public function soaLetterManage(){
            $data = array();

            views('finance.soa-letter-manage', $data);  
        } 

        public function soaMaster_json(){
            $result = recastArray(Finance::getMasterlistById($_POST['id']));

            $result['branch']               = recastArray(Finance::getMasterlistPivot($_POST['id'], 'soa_branch_master_list_pivot')) ?: "";
            $result['segment']              = recastArray(Finance::getMasterlistPivot($_POST['id'], 'soa_segment_master_list_pivot')) ?: "";
            $result['class_of_business']    = recastArray(Finance::getMasterlistPivot($_POST['id'], 'soa_class_of_business_master_list_pivot')) ?: "";
            $result['sales_channel']        = recastArray(Finance::getMasterlistPivot($_POST['id'], 'soa_sales_channel_master_list_pivot')) ?: "";
            $result['topro']                = recastArray(Finance::getMasterlistPivot($_POST['id'], 'soa_topro_master_list_pivot')) ?: "";
            $result['soa_recipients']       = recastArray(Finance::getMasterlistPivot($_POST['id'], 'soa_recipient')) ?: "";
            $result['official_receipt']     = recastArray(Finance::getMasterlistPivot($_POST['id'], 'soa_official_receipt_recipient')) ?: "";

            echo json_encode($result);
        }

        public function soaLetterDownload_json(){
            includeModel('Master');
            includeDefault('shortcode');
            $premium_receivable = Master::getDetailed('', '202508290912', '*', 'CCFM INSURANCE AGENCY CORP. DBA. ASSURANCE');
            $tax_receivable_dst = Master::getDST('', '202508290912', '*', 'CCFM INSURANCE AGENCY CORP. DBA. ASSURANCE');
            $tax_receivable_cwt = Master::getCWT('', '202508290912', '*', 'CCFM INSURANCE AGENCY CORP. DBA. ASSURANCE');

        

            $current_accounts = [
                                    '0_30'   => array_sum(array_column($premium_receivable, '0_30_DAYS')),
                                    '31_60'  => array_sum(array_column($premium_receivable, '31_60_DAYS')),
                                    '61_90'  => array_sum(array_column($premium_receivable, '61_90_DAYS')),
                                ];
            $overdue_accounts = [
                                    '91_180'    => array_reduce(
                                                        $premium_receivable,
                                                        fn($total, $row) => $total + $row['91_120_DAYS'] + $row['121_150_DAYS'] + $row['151_180_DAYS'],
                                                        0
                                                    ),
                                    '180_ABOVE' => array_reduce(
                                                        $premium_receivable,
                                                        fn($total, $row) => $total + $row['181_210_DAYS'] + $row['211_360_DAYS'],
                                                        0
                                                    ),
                                ];

            $total_current = array_sum($current_accounts);
            $total_overdue = array_sum($overdue_accounts);

            $tax_current = [
                    '0_30'   => [
                                    'dst' => array_sum(array_column($tax_receivable_dst ?? [], '0_30_DAYS')),
                                    'cwt' => array_sum(array_column($tax_receivable_cwt ?? [], '0_30_DAYS')),
                                ],
                    '31_60'  => [
                                    'dst' => array_sum(array_column($tax_receivable_dst ?? [], '31_60_DAYS')),
                                    'cwt' => array_sum(array_column($tax_receivable_cwt ?? [], '31_60_DAYS')),
                                ],
                    '61_90'  => [
                                    'dst' => array_sum(array_column($tax_receivable_dst ?? [], '61_90_DAYS')),
                                    'cwt' => array_sum(array_column($tax_receivable_cwt ?? [], '61_90_DAYS')),
                                ]
            ];

            $tax_overdue = [
                '91_180'    => [
                                    'dst' => array_reduce(
                                        $tax_receivable_dst ?? [],
                                        fn($total, $row) => $total + $row['91_120_DAYS'] + $row['121_150_DAYS'] + $row['151_180_DAYS'],
                                        0
                                    ),
                                    'cwt' => array_reduce(
                                        $tax_receivable_cwt ?? [],
                                        fn($total, $row) => $total + $row['91_120_DAYS'] + $row['121_150_DAYS'] + $row['151_180_DAYS'],
                                        0
                                    ),
                                ],
                '180_ABOVE' => [
                                    'dst' => array_reduce(
                                                $tax_receivable_dst ?? [],
                                                fn($total, $row) => $total + $row['181_210_DAYS'] + $row['211_360_DAYS'],
                                                0
                                            ),
                                    'cwt' => array_reduce(
                                                $tax_receivable_cwt ?? [],
                                                fn($total, $row) => $total + $row['181_210_DAYS'] + $row['211_360_DAYS'],
                                                0
                                            ),
                                ]
            ];


            $message = '
                        <p>Dear Ma\'am/Sir,</p>
                        <br>
                        <p>Our records as of July 31, 2025 show that you have outstanding premiums of <b>PHP{{ PREMIUM }}</b>.</p>
                        <br>
                        <p>For your ready reference, we have provided you with the details, as per attached Statement of Account (SOA) which is password-protected.
                        Your default password is the last 7 digits of your Intermediary Code.</p>
                        <br>
                        <p>We wish to remind you of our agreed credit terms. In view thereof, we would appreciate receiving your payment on or before the specified <b>Due Dates below</b> to keep the policies in full force and effect and to avoid any legal complication in case of a claim. Please refer to the Aging Summary below based on effectivity of the policies.</p>
                        <br>
                        <table class="table">
                            <tr class="header">
                                <td colspan="2">COD POLICIES (DUE IMMEDIATELY)</td>
                            </tr>
                            <tr class="bold">
                                <td>AGING DAYS</td>
                                <td class="text-right">NET PREMIUM DUE</td>
                            </tr>
                            <tr>
                                <td>0 - 30 Days</td>
                                <td class="text-right">0.00</td>
                            </tr>
                            <tr>
                                <td>31 - 60 Days</td>
                                <td class="text-right">0.00</td>
                            </tr>
                            <tr>
                                <td>61 - 90 Days</td>
                                <td class="text-right">0.00</td>
                            </tr>
                            <tr>
                                <td>91 - 180 Days</td>
                                <td class="text-right">0.00</td>
                            </tr>
                            <tr>
                                <td>Above 180 Days</td>
                                <td class="text-right">0.00</td>
                            </tr>
                            <tr class="bold">
                                <td>TOTAL COD ACCOUNTS</td>
                                <td class="text-right">0.00</td>
                            </tr>
                        </table>
                        <br>
                        <table class="table">
                            <tr class="header">
                                <td colspan="3">PREMIUM RECEIVABLE</td>
                            </tr>
                            <tr class="bold">
                                <td>AGING DAYS</td>
                                <td class="text-right">NET PREMIUM DUE</td>
                                <td>PAYMENT DUE DATE</td>
                            </tr>';
                        
                        foreach($current_accounts as $key=>$current_account){
                            $message .= '<tr>
                                            <td>'.str_replace('_', ' - ', $key).' Days</td>
                                            <td class="text-right">'.$current_account.'</td>
                                            <td></td>
                                        </tr>';
                        }
                            
                $message.=  '<tr class="bold">
                                <td>Total Current Accounts</td>
                                <td class="text-right">'.$total_current.'</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>91 - 180 Days</td>
                                <td class="text-right">'.$overdue_accounts['91_180'].'</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Above 180 Days</td>
                                <td class="text-right">'.$overdue_accounts['180_ABOVE'].'</td>
                                <td></td>
                            </tr>
                            <tr class="bold">
                                <td>Total Overdue Accounts</td>
                                <td class="text-right">'.$total_overdue.'</td>
                                <td></td>
                            </tr>
                            <tr class="bold">
                                <td>TOTAL PREMIUM RECEIVABLE</td>
                                <td class="text-right">'.$total_current+$total_overdue.'</td>
                                <td></td>
                            </tr>
                        </table>
                        <br>
                        <table class="table">
                            <tr class="header">
                                <td colspan="3">TAXES RECEIVABLE</td>
                            </tr>
                            <tr class="bold">
                                <td>AGING DAYS</td>
                                <td class="text-right">OUTSTANDING DST</td>
                                <td class="text-right">OUTSTANDING CWT</td>
                            </tr>';

                            $total_tax_current = array();
                            foreach($tax_current as $key=>$current){
                                $message .= '<tr>
                                                <td>'.str_replace('_', ' - ', $key).' Days</td>';
                                foreach($current as $tax_key=>$tax){
                                    $message .= '<td class="text-right">'.$tax.'</td>';
                                    $total_tax_current[$tax_key] = ($total_tax_current[$tax_key] ?? 0) + $tax;
                                }
                                $message .= '</tr>';
                            }
                            $total_tax['dst'] = $tax_overdue['91_180']['dst'] + $tax_overdue['180_ABOVE']['dst'];
                            $total_tax['cwt'] = $tax_overdue['91_180']['cwt'] + $tax_overdue['180_ABOVE']['cwt'];
                            $grand_total = $total_current+$total_overdue+$total_tax['cwt']+$total_tax_current['cwt']+$total_tax['dst']+$total_tax_current['dst'];
                $message .= '<tr class="bold">
                                <td>Total Current Accounts</td>
                                <td class="text-right">'.$total_tax_current['dst'].'</td>
                                <td class="text-right">'.$total_tax_current['cwt'].'</td>
                            </tr>
                            <tr>
                                <td>91 - 180 Days</td>
                                <td class="text-right">'.$tax_overdue['91_180']['dst'].'</td>
                                <td class="text-right">'.$tax_overdue['91_180']['cwt'].'</td>
                            </tr>
                            <tr>
                                <td>Above 180 Days</td>
                                <td class="text-right">'.$tax_overdue['180_ABOVE']['dst'].'</td>
                                <td class="text-right">'.$tax_overdue['180_ABOVE']['cwt'].'</td>
                            </tr>
                            <tr class="bold">
                                <td>Total Overdue Accounts</td>
                                <td class="text-right">'.$total_tax['dst'].'</td>
                                <td class="text-right">'.$total_tax['cwt'].'</td>
                            </tr>
                            <tr class="bold">
                                <td>TOTAL TAXES RECEIVABLE</td>
                                <td class="text-right">'.$total_tax['dst']+$total_tax_current['dst'].'</td>
                                <td class="text-right">'.$total_tax['cwt']+$total_tax_current['cwt'].'</td>
                            </tr>
                            <tr class="bold">
                                <td colspan="2">GRAND TOTAL</td>
                                <td class="text-right">'.$grand_total.'</td>
                            </tr>
                        </table>
                        <br>
                        <p class="title-text">Overdue Accounts:</p>
                        <br>
                        <p> 
                            Our records as of May 31, 2025 show that you have outstanding premiums amounting to <b>Php '.$grand_total.'</b> which is due immediately. 
                            Failure to remit the payment will result in the cancellation of the policies by month end. 
                        </p>
                        <br>
                        <br>
                        <p class="title-text">Review SOA Details:</p>
                        <br>
                        <p>
                            Due to timing difference, there may be policies wherein payments have been remitted to us but are still included in your SOA. Please review the attached Statement of Account and 
                            advise us on or before the 15th of this month if there are discrepancies or concerns on your end. If we receive no response from you, we will assume that the outstanding balance 
                            reflected in our Statement of Account is aligned with your records. 
                        </p>
                        <br>
                        <br>
                        <p class="title-text">Payments:</p>
                        <br>
                        <p>We\'ve attached our preferred payment channel together with the payment guidelines.</p>
                        <br>
                        <p>Should you have concerns, kindly reply to this email for us to assist you. We trust that you will give this matter your utmost attention and we look forward to hearing from you soon.</p>
                        <br>
                        <br>
                        <p>Thank you.</p>                    
                    ';
            Shortcode::soaCollectionReminderLetterGeneration(1, '/upload/soa/', '', $message);

        }
    }
?>