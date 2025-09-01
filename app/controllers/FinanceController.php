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


            $message = self::generateCollectionReminderLettertogetherwithSOA(8);

            Shortcode::soaCollectionReminderLetterGeneration(1, '/upload/soa/', 'view', $message);

        }

        public function generateCollectionReminderLettertogetherwithSOA($master_list_id){
            includeDefault('shortcode');
            $master_list        = recastArray(Finance::getMasterlistById($master_list_id));
            $premium_receivable = Master::getDetailed('', '202508290912', '*', $master_list['source_name']);
            $tax_receivable_dst = Master::getDST('', '202508290912', '*', $master_list['source_name']);
            $tax_receivable_cwt = Master::getCWT('', '202508290912', '*', $master_list['source_name']);
            $current_accounts = [
                                    '0_30'   => array_sum(array_column($premium_receivable ?? [], '0_30_DAYS')),
                                    '31_60'  => array_sum(array_column($premium_receivable ?? [], '31_60_DAYS')),
                                    '61_90'  => array_sum(array_column($premium_receivable ?? [], '61_90_DAYS')),
                                ];
            $overdue_accounts = [
                                    '91_180'    => array_reduce(
                                                        $premium_receivable ?? [],
                                                        fn($total, $row) => $total + $row['91_120_DAYS'] + $row['121_150_DAYS'] + $row['151_180_DAYS'],
                                                        0
                                                    ),
                                    '180_ABOVE' => array_reduce(
                                                        $premium_receivable ?? [],
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

            $total_premium  = $total_current+$total_overdue;

            $total_tax_current = array();
            $tax_message = '';
            foreach($tax_current as $key=>$current){
                $tax_message .= '<tr>
                                <td>'.str_replace('_', ' - ', $key).' Days</td>';
                foreach($current as $tax_key=>$tax){
                    $tax_message .= '<td class="text-right">'.$tax.'</td>';
                    $total_tax_current[$tax_key] = ($total_tax_current[$tax_key] ?? 0) + $tax;
                }
                $tax_message .= '</tr>';
            }
            $total_tax['dst'] = $tax_overdue['91_180']['dst'] + $tax_overdue['180_ABOVE']['dst'];
            $total_tax['cwt'] = $tax_overdue['91_180']['cwt'] + $tax_overdue['180_ABOVE']['cwt'];
            $grand_total = $total_current+$total_overdue+$total_tax['cwt']+$total_tax_current['cwt']+$total_tax['dst']+$total_tax_current['dst'];

            $total_dst      = $total_tax['dst']+$total_tax_current['dst'];
            $total_cwt      = $total_tax['cwt']+$total_tax_current['cwt'];

            $message = '
                        <style>
                            table td{
                                border: 1px solid black;
                                text-align: center;
                            }
                            
                        </style>
                        <p>Dear Ma\'am/Sir,</p>
                        <p>Our records as of July 31, 2025 show that you have outstanding premiums of <b>PHP '.$grand_total.'</b>.</p>
                        
                        <p>For your ready reference, we have provided you with the details, as per attached Statement of Account (SOA) which is password-protected.
                        Your default password is the last 7 digits of your Intermediary Code.</p>
                        
                        <p>We wish to remind you of our agreed credit terms. In view thereof, we would appreciate receiving your payment on or before the specified <b>Due Dates below</b> to keep the policies in full force and effect and to avoid any legal complication in case of a claim. Please refer to the Aging Summary below based on effectivity of the policies.</p>
                        
                        <table class="table" cellpadding="5">
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
                        <table class="table" cellpadding="5">
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
                                <td class="text-right">'.$total_premium.'</td>
                                <td></td>
                            </tr>
                        </table>
                        <table class="table" cellpadding="5">
                            <tr class="header">
                                <td colspan="3">TAXES RECEIVABLE</td>
                            </tr>
                            <tr class="bold">
                                <td>AGING DAYS</td>
                                <td class="text-right">OUTSTANDING DST</td>
                                <td class="text-right">OUTSTANDING CWT</td>
                            </tr>';

                            $message .= $tax_message;
                            
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
                                <td class="text-right">'.$total_dst.'</td>
                                <td class="text-right">'.$total_cwt.'</td>
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
            return $message;
        }

        public function generate3160DPDCollectionReminder($master_list_id){
            $master_list = recastArray(Finance::getMasterlistById($master_list_id));

            $message = '<div style="font-size: 10px; line-height: 1.5;">
                            <div>
                                <span>'.$master_list['source_name'].'</span><br>
                                <span>'.$master_list['address'].'</span>
                            </div>
                            <div style="text-align: center;">
                                <p><strong>Subject: Reminder: Premium Payment Due for Accounts 31-60 Days</strong></p>
                            </div>
                            <p>Dear '.$master_list['source_name'].',</p>
                            <p> We trust this message finds you well. We are writing to follow up on the premium payment for the accounts that are currently 
                                31-60 days past due, you may refer to the previously submitted SOA for the list. As we near the end of the month, we would like to emphasize the importance of settling these outstanding 
                                premiums promptly to avoid any potential issues in the future. 
                            </p>

                            <p>Please note that Under Sec. 65 of the Insurance code (R.A. 10607), the Insurance Company can terminate the insurance coverage of 
                                the policy holder in the event that the premium will not be paid. Failure to settle these premiums within the agreed credit term 
                                may result in policy cancellation. Our intention is to prevent such circumstances and maintain a strong and mutually beneficial 
                                relationship going forward. 
                            </p>

                            <p>We appreciate your attention and your immediate action in remitting the outstanding payments for the mentioned accounts.
                                If you have any inquiries or concerns, please do not hesitate to contact me directly at '.$master_list['handler_contact_number'].'.
                            </p>

                            <p>Thank you for your cooperation.</p>
                            <p>Sincerely Yours,</p>
                            <div>
                                <span style="font-weight: bold;">'.$master_list['handler'].'</span><br>
                                <span>'.$master_list['handler_contact_number'].'</span><br>
                                <span>'.$master_list['handler_email'].'</span>
                            </div>

                        </div>';
            return $message;
        }

        public function generate6190DPDCollectionReminder($master_list_id){
            $master_list = recastArray(Finance::getMasterlistById($master_list_id));

            $message = '<div style="font-size: 10px; line-height: 1.5;">
                            <div>
                                <span>'.$master_list['source_name'].'</span><br>
                                <span>'.$master_list['address'].'</span>
                            </div>
                            <div style="text-align: center;">
                                <p><strong>Subject: Reminder: Premium Payment Due for Accounts 61-90 Days</strong></p>
                            </div>
                            <p>Dear '.$master_list['source_name'].',</p>
                            <p> We trust this message finds you well. We are writing to follow up on the premium payment for the accounts that are currently 
                                61-90 days past due, you may refer to the previously submitted SOA for the list. As we near the end of the month, we would like to emphasize the importance of settling these outstanding 
                                premiums promptly to avoid any potential issues in the future. 
                            </p>

                            <p>Please note that Under Sec. 65 of the Insurance code (R.A. 10607), the Insurance Company can terminate the insurance coverage of 
                                the policy holder in the event that the premium will not be paid. Failure to settle these premiums within the agreed credit term 
                                may result in policy cancellation. Our intention is to prevent such circumstances and maintain a strong and mutually beneficial 
                                relationship going forward. 
                            </p>

                            <p>We appreciate your attention and your immediate action in remitting the outstanding payments for the mentioned accounts.
                                If you have any inquiries or concerns, please do not hesitate to contact me directly at '.$master_list['handler_contact_number'].'.
                            </p>

                            <p>Thank you for your cooperation.</p>
                            <p>Sincerely Yours,</p>
                            <div>
                                <span style="font-weight: bold;">'.$master_list['handler'].'</span><br>
                                <span>'.$master_list['handler_contact_number'].'</span><br>
                                <span>'.$master_list['handler_email'].'</span>
                            </div>

                        </div>';
            return $message;
        }

        public function generateFirstReminderwithNoticeofCancellation($master_list_id){
            $master_list = recastArray(Finance::getMasterlistById($master_list_id));
            $message = '
                    <div style="font-size:10px; line-height:1.5; text-align:left;">
                        <div style="text-align:center; margin-bottom:20px;">
                            <p style="font-weight:bold; text-decoration:underline; margin:0;">
                                Above 90 Days Past Due Collection Reminder
                            </p>
                        </div>
                        <div style="margin-bottom:20px;">
                            <span>'.$master_list['source_name'].'</span><br>
                            <span>'.$master_list['address'].'</span>
                        </div>
                        <div style="text-align:center; margin-bottom:20px;">
                            <p style="margin:0;">
                                Subject: Reminder: Payment Due for Outstanding Premiums
                            </p>
                        </div>

                        <p>Dear '.$master_list['source_name'].',</p>

                        <p>
                            We are writing to follow up on the premium payment for the accounts that are overdue already
                            aging 91 days and above. Despite our previous follow-up attempts these accounts remain unpaid as of today.
                            We would like to emphasize the importance of settling these outstanding premiums promptly.
                            Below is the details of the said outstanding policies:
                        </p>
                        $outstandingOverdueHtml

                        <p>
                            We understand that unforeseen circumstances can sometimes affect payment timelines. However,
                            it is essential to address these outstanding balances to ensure the continuity of coverage.
                            Please note that Under Sec. 65 of the Insurance code (R.A. 10607), the Insurance Company can
                            terminate the insurance coverage of the policy holder in the event that the premium will not be paid.
                            Failure to do so will compel us to cancel these policies.
                        </p>

                        <p>
                            We appreciate your immediate action in remitting the outstanding payments for the mentioned accounts.
                        </p>

                        <p>
                            If you have any inquiries or concerns, please do not hesitate to contact me directly at
                            '.$master_list['handler_contact_number'].' & '.$master_list['handler_email'].'.
                        </p>

                        <p>
                            Thank you for your cooperation, and we eagerly anticipate your prompt response.
                        </p>

                        <p style="font-weight:bold;">Sincerely Yours,</p>

                        <div style="margin-top:12px;">
                            <span style="font-weight:bold;">'.$master_list['handler'].'</span><br>
                            <span>'.$master_list['handler_contact_number'].'</span><br>
                            <span>'.$master_list['handler_email'].'</span>
                        </div>

                    </div>
                    ';
            return $message;
        }
    }
?>