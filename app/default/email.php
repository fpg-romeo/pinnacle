<?php
require_once('app/library/mail/PHPMailer.php');
require_once('app/library/mail/SMTP.php');
require_once('app/library/mail/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email{

    public function __construct() {

    }

    public static function sendEmail($to, $subject, $message, $cc = '', $bcc = '', $attachment = '', $reply_to = ''){

        includeDefault(['configuration']);

        $CONFIGURATION = Configuration::general();

        //TEST MODE : STAGING & DEVELOPMENT
        if (SYSTEM_ENVIRONMENT != PRODUCTION) {
            $to       = ACCOUNT_EMAIL;
            $cc       = '';
            $bcc      = $CONFIGURATION['IT_TEAM_EMAIL'];
            $reply_to = '';
            $subject  = 'PLEASE IGNORE - ' . strtoupper(SYSTEM_SUBDOMAIN) . ' : ' . strtoupper(SYSTEM_ENVIRONMENT) . ' SERVER TEST | ' . $subject;
        }

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->SetLanguage('en', 'phpmailer/language/');
            $mail->Host = $CONFIGURATION['MAIL_HOST'];
            $mail->Port = $CONFIGURATION['MAIL_PORT'];
            $mail->SMTPAuth = false;

            if (is_array($to)) {
                foreach ($to as $to_email) {
                    if (!empty($to_email) && Shortcode::checkIfValidEmail($to_email)) {
                        $mail->addAddress($to_email);
                    }
                }
            } else {
                if (!empty($to) && Shortcode::checkIfValidEmail($to)) {
                    $mail->addAddress($to);
                }
            }

            if (!empty($cc)) {
                if (is_array($cc)) {
                    foreach ($cc as $cc_email) {
                        if (!empty($cc_email) && Shortcode::checkIfValidEmail($cc_email)) {
                            $mail->AddCC($cc_email);
                        }
                    }
                } else {
                    if (!empty($cc) && Shortcode::checkIfValidEmail($cc)) {
                        $mail->AddCC($cc);
                    }
                }
            }

            if (!empty($bcc)) {
                $bcc = $bcc;
            } else {
                $bcc = $CONFIGURATION['IT_TEAM_EMAIL'];
            }

            if (is_array($bcc)) {
                foreach ($bcc as $bcc_email) {
                    if (!empty($bcc_email) && Shortcode::checkIfValidEmail($bcc_email)) {
                        $mail->AddBCC($bcc_email);
                    }
                }
            } else {
                if (!empty($bcc) && Shortcode::checkIfValidEmail($bcc)) {
                    $mail->AddBCC($bcc);
                }
            }

            if (!empty($attachment)) {
                if (is_array($attachment)) {
                    foreach ($attachment as $k_attachment) {
                        $mail->AddAttachment($k_attachment);
                    }
                } else {
                    $mail->AddAttachment($attachment);
                }
            }

            if (!empty($reply_to)) {
                $reply_to = $reply_to;
            } else {
                $reply_to = $CONFIGURATION['MAIL_REPLYTO'];
            }

            if (is_array($reply_to)) {
                foreach ($reply_to as $reply_to_email) {
                    if (!empty($reply_to_email) && Shortcode::checkIfValidEmail($reply_to_email)) {
                        $mail->addReplyTo($reply_to_email);
                    }
                }
            } else {
                if (!empty($reply_to) && Shortcode::checkIfValidEmail($reply_to)) {
                    $mail->addReplyTo($reply_to);
                }
            }

            $mail->Subject = htmlDecode($subject);
            $mail->SetFrom($CONFIGURATION['MAIL_SENDER'], $CONFIGURATION['MAIL_FROM_NAME']);
            $mail->msgHTML($message);

            if (!$mail->Send()) {
                $return['status']  = 'failed';
                $return['message'] = 'Encounter sending email error. Mailer Error: ' . $mail->ErrorInfo;
            } else {
                $return['status']  = 'success';
                $return['message'] = 'Email sent';
            }

            $mail->clearAddresses();
            $mail->clearAttachments();

        } catch (Exception $e) {
            $return['status']  = "error";
            $return['message'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        return $return;
    }

    public static function templateDefault($content, $size = '')
    {

        includeDefault(['configuration']);
        $CONFIGURATION = Configuration::general();

        if ($size == 'fullsize') {
            $width = '90%';
        } else {
            $width = '690px';
        }

        $template = '	
                            <html>
                            <head>
                            <meta http-equiv="X-UA-Compatible" content="IE=edge">
                            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                            <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
                            <meta charset="utf-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1.0, user-scalable=no">
                            <title>' . $CONFIGURATION['SYSTEM_NAME'] . '</title>
                            <style>
                            html, body, div, span, applet, object, iframe,
                            h1, h2, h3, h4, h5, h6, p, blockquote, pre,
                            a, abbr, acronym, address, big, cite, code,
                            del, dfn, em, img, ins, kbd, q, s, samp,
                            small, strike, strong, sub, sup, tt, var,
                            b, u, i, center,
                            dl, dt, dd, ol, ul, li,
                            fieldset, form, label, legend,
                            table, caption, tbody, tfoot, thead, tr, th, td,
                            article, aside, canvas, details, embed, 
                            figure, figcaption, footer, header, hgroup, 
                            menu, nav, output, ruby, section, summary,
                            time, mark, audio, video {
                                margin: 0;
                                padding: 0;
                                border: 0;
                                font-size: 100%;
                                font: inherit;
                                vertical-align: baseline;
                            }
                            article, aside, details, figcaption, figure, 
                            footer, header, hgroup, menu, nav, section {
                                display: block;
                            }
                            body {
                                margin: 0; 
                                padding: 0; 
                                line-height: 1;
                                /*background: #f7f7f7;*/
                                font-family: arial;
                                font-size: 12px;
                            }
                            ol, ul {
                                list-style: none;
                            }
                            blockquote, q {
                                quotes: none;
                            }
                            blockquote:before, blockquote:after,
                            q:before, q:after {
                                content: "";
                                content: none;
                            }
                            table {
                                border-collapse: collapse;
                                border-spacing: 0;
                                mso-table-lspace:0pt; 
                                mso-table-rspace:0pt;
                            } 
                            th, td {
                                padding: 10px;
                            }

                            p                   { margin: 0; padding: 0; }
                            small               { color: #a9a9a9; font-size: 10px; line-height:12px; }
                            a                   { color:rgb(20, 7, 255) !important; text-decoration: none !important; }
                            .link_file          { color:#1155cc !important; text-decoration: underline !important; }

                            .block              { margin: 0 auto; width:100%; padding: 30px 0; font-family: arial; font-size: 11px; line-height: 16px; color: #828282; box-sizing: border-box; }
                            .box                { margin: 0 auto; width:90%; border: #dbdbdb solid 1px; border-radius:10px; box-sizing: border-box; }
                            
                            .title-text         { color: #80173d; font-weight: bold; }
                            .title-bar          { background: #80173d; color: #ffffff; font-weight: bold; border: 1px solid #80173d !important; }
                            .border td          { border: 1px solid #DEE2E6; }
                            .logo               { display: inline-block; width: 160px; height: auto; margin-top: 25px; margin-bottom: 25px; border: 0; }
                            .footer             { margin: 40px auto 50px auto; width:100%; text-align:center; color: #969696; }
                            .footer a           { color: #969696 !important; }
                            .bold               { font-weight: bold; }
        
                            .width-10           { width: 10%; }
                            .width-20           { width: 20%; }
                            .width-30           { width: 30%; }
                            .width-40           { width: 40%; }
                            .width-50           { width: 50%; }
                            .width-60           { width: 60%; }
                            .width-70           { width: 70%; }
                            .width-80           { width: 80%; }
                            .width-90           { width: 90%; }
                            .width-100          { width: 100%; }

                            .odd                { background: #fcfcfc; }
                            .even               { background: #f3f3f3; }

                            .text-center        { text-align:center !important; }
                            .text-left          { text-align:left !important; }
                            .text-right         { text-align:right !important; }

                            .button-href        { padding: 10px 20px; background:#4284f4; text-decoration:none; color:#ffffff !important; font-weight: bold; font-size: 11px; border-radius:4px; }
                            .link-href          { color:#222 !important; text-decoration:none; }

                            .margin-top-10      { margin-top: 10px !important; }
                            .margin-top-20      { margin-top: 20px !important; }
                            .margin-top-30      { margin-top: 30px !important; }
                            .margin-top-40      { margin-top: 40px !important; }
                            .margin-top-50      { margin-top: 50px !important; }

                            .margin-bottom-10   { margin-bottom: 10px !important; }
                            .margin-bottom-20   { margin-bottom: 20px !important; }
                            .margin-bottom-30   { margin-bottom: 30px !important; }
                            .margin-bottom-40   { margin-bottom: 40px !important; }
                            .margin-bottom-50   { margin-bottom: 50px !important; }

                            @media screen and (min-width: 768px) { .box { width: ' . $width . '; box-sizing: border-box; } }
                            @media screen and (max-width: 767px) { .box { width: 90%; padding: 15px; box-sizing: border-box; } }
                            @media screen and (max-width: 600px) { 
                                .block          { padding: 0; }
                                .box            { width: 100%; padding: 0; border: 0; border-radius:0; border-bottom: #e0e0e0 solid 1px; box-sizing: border-box; } 
                                .hidden-600     { display:none; }
                                .footer         { width: 94%; margin: 30px 10px; }
                            }
                            </style>
                            </head>
							<body>
                            <div class="block">
                                <div class="box">
                                    <table width="100%">
										' . $content . '
									</table>
								</div>
								<div class="footer">
									If you require further assistance, email us at <a href="mailto:' . $CONFIGURATION['SYSTEM_EMAIL'] . '" target="_blank">' . $CONFIGURATION['SYSTEM_EMAIL'] . '</a>
                                    <br>
                                    <a href="' . $CONFIGURATION['SYSTEM_COMPANY_URL'] . '" target="_blank" class="link-href">' . $CONFIGURATION['SYSTEM_COMPANY'] . '</a>
                                    <br>
                                    '.$CONFIGURATION['SYSTEM_SLOGAN'].'
                                    <br>
                                    <br>
                                    All Rights Reserved. Copyright &copy; ' . date("Y") . '. ' . $CONFIGURATION['SYSTEM_ALIAS'] . '.<small>' . $CONFIGURATION['SYSTEM_VERSION'] . '</small>
								</div>
							</div>
							</body>
							</html>
					    ';

        return $template;
    }

    public static function templateSoa($content, $size = '')
    {

        includeDefault(['configuration']);
        $CONFIGURATION = Configuration::general();

        if ($size == 'fullsize') {
            $width = '90%';
        } else {
            $width = '690px';
        }

        $template = '	
                            <html>
                            <head>
                            <meta http-equiv="X-UA-Compatible" content="IE=edge">
                            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                            <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
                            <meta charset="utf-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1.0, user-scalable=no">
                            <title>' . $CONFIGURATION['SYSTEM_NAME'] . '</title>
                            <style>
                            html, body, div, span, applet, object, iframe,
                            h1, h2, h3, h4, h5, h6, p, blockquote, pre,
                            a, abbr, acronym, address, big, cite, code,
                            del, dfn, em, img, ins, kbd, q, s, samp,
                            small, strike, strong, sub, sup, tt, var,
                            b, u, i, center,
                            dl, dt, dd, ol, ul, li,
                            fieldset, form, label, legend,
                            table, caption, tbody, tfoot, thead, tr, th, td,
                            article, aside, canvas, details, embed, 
                            figure, figcaption, footer, header, hgroup, 
                            menu, nav, output, ruby, section, summary,
                            time, mark, audio, video {
                                margin: 0;
                                padding: 0;
                                border: 0;
                                font-size: 100%;
                                font: inherit;
                                vertical-align: baseline;
                            }
                            article, aside, details, figcaption, figure, 
                            footer, header, hgroup, menu, nav, section {
                                display: block;
                            }
                            body {
                                margin: 0; 
                                padding: 0; 
                                line-height: 1;
                                /*background: #f7f7f7;*/
                                font-family: arial;
                                font-size: 12px;
                            }
                            ol, ul {
                                list-style: none;
                            }
                            blockquote, q {
                                quotes: none;
                            }
                            blockquote:before, blockquote:after,
                            q:before, q:after {
                                content: "";
                                content: none;
                            }
                            table {
                                border-collapse: collapse;
                                border-spacing: 0;
                                mso-table-lspace:0pt; 
                                mso-table-rspace:0pt;
                            } 
                            th, td {
                                padding: 10px;
                            }

                            p                   { margin: 0; padding: 0; }
                            small               { color: #a9a9a9; font-size: 10px; line-height:12px; }
                            a                   { color:rgb(20, 7, 255) !important; text-decoration: none !important; }
                            .link_file          { color:#1155cc !important; text-decoration: underline !important; }

                            .block              { margin: 0 auto; width:100%; padding: 30px 0; font-family: arial; font-size: 11px; line-height: 16px; color: #828282; box-sizing: border-box; }
                            .box                { margin: 0 auto; width:90%; }
                            
                            .title-text         { color: #ff6b00; font-weight: bold; text-decoration: underline; }
                            .title-bar          { background: #ff6b00; color: #ffffff; font-weight: bold; border: 1px solid #dd5c00 !important; }
                            .border td          { border: 1px solid #DEE2E6; }
                            .logo               { display: inline-block; width: 160px; height: auto; margin-top: 25px; margin-bottom: 25px; border: 0; }
                            .footer             { margin: 40px auto 50px auto; width:100%; text-align:center; color: #969696; }
                            .footer a           { color: #969696 !important; }
                            .footer .note       { font-weight: bold; color: red; font-size: 9px; }
                            .bold               { font-weight: bold; }
                            .center             { margin: 0 auto; width:100%; }
        
                            .table              { border-collapse: separate; border-spacing: 5px; width: 480px; font-size: 10px; }
                            .table td           { padding: 5px; border: 1px solid #ccc; } 
                            .table .header      { background: #ff6b00; color: #ffffff; font-weight: bold; }
                            
                            .width-10           { width: 10%; }
                            .width-20           { width: 20%; }
                            .width-30           { width: 30%; }
                            .width-40           { width: 40%; }
                            .width-50           { width: 50%; }
                            .width-60           { width: 60%; }
                            .width-70           { width: 70%; }
                            .width-80           { width: 80%; }
                            .width-90           { width: 90%; }
                            .width-100          { width: 100%; }

                            .odd                { background: #fcfcfc; }
                            .even               { background: #f3f3f3; }

                            .text-center        { text-align:center !important; }
                            .text-left          { text-align:left !important; }
                            .text-right         { text-align:right !important; }

                            .button-href        { padding: 10px 20px; background:#4284f4; text-decoration:none; color:#ffffff !important; font-weight: bold; font-size: 11px; border-radius:4px; }
                            .link-href          { color:#222 !important; text-decoration:none; }

                            .margin-top-10      { margin-top: 10px !important; }
                            .margin-top-20      { margin-top: 20px !important; }
                            .margin-top-30      { margin-top: 30px !important; }
                            .margin-top-40      { margin-top: 40px !important; }
                            .margin-top-50      { margin-top: 50px !important; }

                            .margin-bottom-10   { margin-bottom: 10px !important; }
                            .margin-bottom-20   { margin-bottom: 20px !important; }
                            .margin-bottom-30   { margin-bottom: 30px !important; }
                            .margin-bottom-40   { margin-bottom: 40px !important; }
                            .margin-bottom-50   { margin-bottom: 50px !important; }

                            @media screen and (min-width: 768px) { .box { width: ' . $width . '; box-sizing: border-box; } }
                            @media screen and (max-width: 767px) { .box { width: 90%; padding: 15px; box-sizing: border-box; } }
                            @media screen and (max-width: 600px) { 
                                .block          { padding: 0; }
                                .box            { width: 100%; padding: 0; border: 0; border-radius:0; border-bottom: #e0e0e0 solid 1px; box-sizing: border-box; } 
                                .hidden-600     { display:none; }
                                .footer         { width: 94%; margin: 30px 10px; }
                            }
                            </style>
                            </head>
							<body>
                            <div class="block">
                                <div class="box">
                                    <table width="100%">
										' . $content . '
									</table>
								</div>
								<div class="footer">
                                    <p class="note">***THIS IS A SYSTEM GENERATED EMAIL. NO SIGNATURE IS REQUIRED.***</p>
                                    <br>
                                    <br>
                                    <a href="' . $CONFIGURATION['SYSTEM_COMPANY_URL'] . '" target="_blank" class="link-href">' . $CONFIGURATION['SYSTEM_COMPANY'] . '</a>
                                    <br>
                                    All Rights Reserved. Copyright &copy; ' . date("Y") . '.
								</div>
							</div>
							</body>
							</html>
					    ';

        return $template;
    }

    public static function testEmail($message)
    {
        return self::templateDefault($message);
    }

    public static function resetPassword($post = '')
    {
        $CONFIGURATION = Configuration::general();

        $message = '
						<p>Hi ' . $post['name'] . ',</p>
						<br>
						<p>
							A request has been received to reset your password.
							<br>
							If you made this request, click the link below to reset your password. If you didn\'t make this request you can just ignore this e-mail or you may contact as at ' . $CONFIGURATION['SYSTEM_EMAIL'] . '
							<br>
							<br>
                            <a href="' . $CONFIGURATION['SYSTEM_URL'] . '/reset-password/' . $post['email'] . "/" . $post['code'] . '" target="_blank" class="link-href">' . $CONFIGURATION['SYSTEM_URL'] . '/reset-password/' . $post['email'] . "/" . $post['code'] . '</a>
                            <br>
                            <br>
                            or 
                            <br>
                            <br>
                            <a href="' . $CONFIGURATION['SYSTEM_URL'] . '/reset-password/' . $post['email'] . "/" . $post['code'] . '" target="_blank" class="button-href">CLICK HERE to reset your password</a>
						</p>
					   ';

        return self::templateDefault($message);
    }

    public static function remarks($post)
    {

        $message = htmlDecode($post['content']);

        return self::templateDefault($message);
    }

    public static function gcashPolicyUpload($ctr_success, $ctr_duplicate, $ctr_failed, $batch, $upload_id)
    {
        $email_body = '
                        <div class="container">
                            <h4>GCash Microsite Excel Data import is completed</h4>
                            <p class="success">The data from your Excel file has been successfully imported into the system.</p>

                            <br><br>
                            <div class="summary">
                                <p><strong>Import Summary:</strong></p>
                                <table style="padding: 0 !important">
                                    <tr>
                                        <td>
                                            <strong>Upload ID:</strong><br>
                                            <strong>Total Saved:</strong><br>
                                            <strong>Total Duplicate:</strong><br>
                                            <strong>Total Failed:</strong><br>
                                            <strong>Batch No:</strong><br>
                                            <strong>Date Uploaded:</strong><br>
                                            <strong>Uploaded By:</strong>
                                        </td>
                                        <td>
                                            ' . $upload_id . '<br>
                                            ' . $ctr_success . '<br>
                                            ' . $ctr_duplicate . '<br>
                                            ' . $ctr_failed . '<br>
                                            ' . $batch . '<br>
                                            ' . date('d-M-Y') . '<br>
                                            ' . ACCOUNT_NAME . '
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>';
        return $email_body;
    }

    public static function soa($post){

        //$message = htmlDecode($post['content']);

        $message = '
                    <p>Dear Ma\'am/Sir,</p>
                    <br>
                    <p>We are pleased to share your Statement of Account (SOA) as of <b>May 31, 2025</b>.</p>
                    <br>
                    <p>For your security, the file is password protected. You may open it by entering the last 7 digits of your Intermediary Code as your default password.</p>
                    <br>
                    <p>We would appreciate reminding our clients to pay their dues on or before the agreed term to keep the policies in full force and avoid any legal complications in case of a claim.</p>
                    <br>
                    <p>Please refer to the Summary below based on effectivity of the policies and number of days past due.</p>
                    <br>
                    <div class="center">
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
                    </div>
                    <br>
                    <div class="center">
                        <table class="table">
                            <tr class="header">
                                <td colspan="3">PREMIUM RECEIVABLE</td>
                            </tr>
                            <tr class="bold">
                                <td>AGING DAYS</td>
                                <td class="text-right">NET PREMIUM DUE</td>
                                <td>PAYMENT DUE DATE</td>
                            </tr>
                            <tr>
                                <td>0 - 30 Days</td>
                                <td class="text-right">0.00</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>31 - 60 Days</td>
                                <td class="text-right">0.00</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>61 - 90 Days</td>
                                <td class="text-right">0.00</td>
                                <td></td>
                            </tr>
                            <tr class="bold">
                                <td>Total Current Accounts</td>
                                <td class="text-right">0.00</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>91 - 180 Days</td>
                                <td class="text-right">0.00</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Above 180 Days</td>
                                <td class="text-right">0.00</td>
                                <td></td>
                            </tr>
                            <tr class="bold">
                                <td>Total Overdue Accounts</td>
                                <td class="text-right">0.00</td>
                                <td></td>
                            </tr>
                            <tr class="bold">
                                <td>TOTAL PREMIUM RECEIVABLE</td>
                                <td class="text-right">0.00</td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <br>
                    <div class="center">
                        <table class="table">
                            <tr class="header">
                                <td colspan="3">TAXES RECEIVABLE</td>
                            </tr>
                            <tr class="bold">
                                <td>AGING DAYS</td>
                                <td class="text-right">OUTSTANDING DST</td>
                                <td class="text-right">OUTSTANDING CWT</td>
                            </tr>
                            <tr>
                                <td>0 - 30 Days</td>
                                <td class="text-right">0.00</td>
                                <td class="text-right">0.00</td>
                            </tr>
                            <tr>
                                <td>31 - 60 Days</td>
                                <td class="text-right">0.00</td>
                                <td class="text-right">0.00</td>
                            </tr>
                            <tr>
                                <td>61 - 90 Days</td>
                                <td class="text-right">0.00</td>
                                <td class="text-right">0.00</td>
                            </tr>
                            <tr class="bold">
                                <td>Total Current Accounts</td>
                                <td class="text-right">0.00</td>
                                <td class="text-right">0.00</td>
                            </tr>
                            <tr>
                                <td>91 - 180 Days</td>
                                <td class="text-right">0.00</td>
                                <td class="text-right">0.00</td>
                            </tr>
                            <tr>
                                <td>Above 180 Days</td>
                                <td class="text-right">0.00</td>
                                <td class="text-right">0.00</td>
                            </tr>
                            <tr class="bold">
                                <td>Total Overdue Accounts</td>
                                <td class="text-right">0.00</td>
                                <td class="text-right">0.00</td>
                            </tr>
                            <tr class="bold">
                                <td>TOTAL TAXES RECEIVABLE</td>
                                <td class="text-right">0.00</td>
                                <td class="text-right">0.00</td>
                            </tr>
                            <tr class="bold">
                                <td colspan="2">GRAND TOTAL</td>
                                <td class="text-right">0.00</td>
                            </tr>
                        </table>
                    </div>
                    <br>
                    <p class="title-text">Overdue Accounts:</p>
                    <br>
                    <p> 
                        Our records as of May 31, 2025 show that you have outstanding premiums amounting to <b>Php303,325.69</b> which is due immediately. 
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

        return self::templateSoa($message);
    }
}
