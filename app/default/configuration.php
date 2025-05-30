<?php
class Configuration{

    public function __construct(){

    }

	public static function general(){
        includeDefault(['key']);
        $KEY   = Key::credential();
        $value = array();

		$value['DOMAIN_LIVE']  												= DOMAIN_LIVE;
		$value['DOMAIN_UAT']  												= DOMAIN_UAT;
		$value['DOMAIN_LOCAL']  											= DOMAIN_LOCAL;

		$value['SYSTEM_NAME'] 												= 'Intuit - FPG Insurance Internal Application';
		$value['SYSTEM_ALIAS'] 												= 'Intuit';
		$value['SYSTEM_VERSION'] 											= 'v.1.0';
		$value['SYSTEM_COMPANY']  											= 'FPG Insurance Co., Inc.';
		$value['SYSTEM_COMPANY_URL']  										= 'https://ph.fpgins.com';
		$value['SYSTEM_COMPANY_CONTACT_NO']  								= '(02) 8859 1200';
		$value['SYSTEM_COMPANY_ADDRESS']									= '6/F Zuellig Building, Makati Avenue corner Paseo de Roxas, Makati City, Philippines 1225';
		$value['SYSTEM_SLOGAN'] 											= 'FPG Insurance is one of the top leading independent Non-Life Insurance Companies in the Philippines.';
		$value['SYSTEM_EMAIL'] 												= 'servicedesk@fpgins.com';
		$value['SYSTEM_VERSION_DATE_START'] 								= '2025-06-01';
		$value['SYSTEM_LOGIN_DOMAIN'] 										= 'fpgins.com';
	
		$value['SYSTEM_FOLDER']  											= SYSTEM_SUBDOMAIN;
		$value['SYSTEM_SUBDOMAIN']  										= SYSTEM_SUBDOMAIN;
		$value['SYSTEM_SERVER_PRODUCTION']  								= SYSTEM_SERVER_PRODUCTION;
		$value['SYSTEM_SERVER_STAGING']  									= SYSTEM_SERVER_STAGING;
		$value['SYSTEM_DOMAIN'] 											= str_replace($value['SYSTEM_SUBDOMAIN'].'.','', $_SERVER['SERVER_NAME']); 
		$value['SYSTEM_SERVER']  											= $value['SYSTEM_SUBDOMAIN'].'.'.$value['SYSTEM_DOMAIN'];
		$value['SYSTEM_URL'] 												= 'https://'.($_SERVER['SERVER_NAME'] == $value['DOMAIN_LOCAL'] ? $value['DOMAIN_LOCAL'] : $value['SYSTEM_SERVER']);  

		$value['MAIL_SENDER'] 												= $value['SYSTEM_SUBDOMAIN'].'@'.($_SERVER['SERVER_NAME'] == $value['DOMAIN_LIVE'] ? $value['SYSTEM_SERVER_PRODUCTION'] : $value['SYSTEM_SERVER_STAGING']); 
		$value['MAIL_EMAIL'] 												= $KEY['MAIL_EMAIL']; 
		$value['MAIL_PASSWORD'] 											= $KEY['MAIL_PASSWORD']; 
		$value['MAIL_HOST'] 												= $KEY['MAIL_HOST']; 
		$value['MAIL_PORT'] 												= 25;
		$value['MAIL_REPLYTO'] 												= array($value['MAIL_SENDER']);
		$value['MAIL_FROM_NAME'] 											= $value['SYSTEM_COMPANY'];
		$value['MAIL_MAX_ATTEMPT'] 											= 10;

		$value['PAGINATION'] 												= 10;
		$value['ALLOWED_DOCUMENT'] 											= array('pdf', 'xls', 'xlsx', 'csv', 'doc', 'docx');
		$value['ALLOWED_PHOTO'] 											= array('jpg', 'jpeg', 'png');
		$value['ALLOWED_FILE'] 												= array('pdf', 'xls', 'xlsx', 'csv', 'doc', 'docx', 'jpg', 'jpeg', 'png');
		$value['ALLOWED_EXCEL'] 											= array('xls', 'xlsx', 'csv');
		$value['ALLOWED_FILE_SIZE'] 										= 5 * 1024 * 1024; // 5242880 KB = 5 MB
		$value['ALLOWED_FILE_SIZE_20'] 										= 20 * 1024 * 1024; // 5242880 KB = 20 MB

		$value['COOKIES_EXPIRATION'] 										= (30 * 24 * 60 * 60); //day * hour * minute * seconds | 30days
	
		//GOOGLE API (LOGIN)
		$value['GOOGLE_SIGNIN_APPLICATION_NAME']							= 'FPG Insurance Google Sign In';
		$value['GOOGLE_SIGNIN_CLIENT_ID']									= $KEY['GOOGLE_SIGNIN_CLIENT_ID'];
		$value['GOOGLE_SIGNIN_CLIENT_SECRET']								= $KEY['GOOGLE_SIGNIN_CLIENT_SECRET'];
		$value['GOOGLE_SIGNIN_REDIRECT_URI']								= getSiteUrl().'/account/google-signin-callback/';

		//GOOGLE CAPTCHA
		$value['GOOGLE_RECAPTCHA_SITE_KEY']									= $KEY['GOOGLE_RECAPTCHA_SITE_KEY'];
		$value['GOOGLE_RECAPTCHA_SECRET_KEY']								= $KEY['GOOGLE_RECAPTCHA_SECRET_KEY'];

		//HOLIDAY CALENDAR DATES
		$value['GOOGLE_CALENDAR_PH_HOLIDAY_API_URL']    					= "https://www.googleapis.com/calendar/v3/calendars/en.philippines%23holiday%40group.v.calendar.google.com/events?key=AIzaSyBXT99B4JgexJ5A7G7YprjBUZwSZee541o";

		$value['ACCOUNT_STATUS_ACTIVE'] 									= '1'; //ACTIVE

		$value['ACCOUNT_TYPE_ADMINISTRATOR'] 								= '1';

		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// FIXED POSITION : DONT REMOVE ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$value['BLACKLISTED_EMAIL'] 										= array();
        $value['IT_TEAM_EMAIL'] 											= array('jeffreydimla@fpgins.com');
		
        if(in_array($_SERVER['SERVER_NAME'], array($value['DOMAIN_LIVE'], $value['DOMAIN_UAT']))){
			//EMAIL DEFAULT RECIPIENT
			$value['EMAIL_TO_'] 											= array();
			$value['EMAIL_BCC_'] 			   								= $value['IT_TEAM_EMAIL'];
		}else{
			//EMAIL DEFAULT RECIPIENT
			$value['EMAIL_TO_'] 											= $value['IT_TEAM_EMAIL'];
			$value['EMAIL_BCC_'] 											= $value['IT_TEAM_EMAIL'];
		}

		return $value;
	}
}
?>