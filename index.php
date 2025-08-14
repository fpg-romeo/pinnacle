<?php
define('SYSTEM_SUBDOMAIN', 'pinnacle');
define('SYSTEM_SERVER_PRODUCTION', 'fpgins.com');
define('SYSTEM_SERVER_STAGING', 'fpgins.staging');
define('DOMAIN_LIVE', SYSTEM_SUBDOMAIN . '.' . SYSTEM_SERVER_PRODUCTION);
define('DOMAIN_UAT', SYSTEM_SUBDOMAIN . '.' . SYSTEM_SERVER_STAGING);
define('DOMAIN_LOCAL', 'localhost');
define('PRODUCTION', 'Production');
define('STAGING', 'Staging');
define('DEVELOPMENT', 'Development');
define('SYSTEM_ENVIRONMENT', ($_SERVER['SERVER_NAME'] == DOMAIN_LIVE ? PRODUCTION : ($_SERVER['SERVER_NAME'] == DOMAIN_LOCAL ? DEVELOPMENT : STAGING)));

require_once('app/default/function.php');

includeDefault(['database', 'segment', 'configuration', 'shortcode', 'tools', 'value']);

if (!empty(getVar('controller')) && !empty(getVar('view'))) {
	$controller = getVar('controller');
	$view     	= viewConvertUrl(getVar('view'));
} else {
	$controller = 'page';
	$view     	= 'dashboard';
}

$account = array('login', 'logout', 'forgotPassword', 'resetPassword', 'googleSigninCallback');
$blank   = array('cron', 'source', 'pdf');
$request = array('import', 'pdf');
$json 	 = array('quickbooks', 'api');
$error 	 = array('error400', 'error401', 'error403', 'error404', 'error500', 'comingSoon', 'underMaintenance');

if ($controller == 'page' && $view == 'errorModal') {
	$template = 'error';
} elseif ($view == 'tv' && $controller != 'notification') {
	$template = 'tv';
} elseif (strpos($_SERVER['REQUEST_URI'], 'json') !== false || in_array($controller, $json)) {
	$template = 'json';
} elseif (requestUri($request, $_SERVER['REQUEST_URI']) || in_array($controller, $blank)) {
	$template = 'blank';
} elseif (in_array($view, $account)) {
	$template = 'account';
} else {
	$template = 'main';
}

$CONFIGURATION = Configuration::general();

//$_SESSION['login_id'] = idEncrypt(1000);

if (isset($_SESSION['login_id'])) {

	includeModel(['Account']);
	$record = Account::getLoggedUser(idDecrypt($_SESSION['login_id']));

	if (is_array($record) && !empty($record)) {
		$record = recastArray($record);

		//FOR MANDATORY RELOGIN
		if ($record['relogin'] == 'Yes') {
			header('location: /logout');
		}

		define('ACCOUNT_TYPE_ID', htmlDecode($record['account_type_id']));
		define('ACCOUNT_TYPE_NAME', htmlDecode($record['account_type_name']));
		define('ACCOUNT_ROLE_ID', htmlDecode($record['account_role_id']));
		define('ACCOUNT_DEPARTMENT_ID', htmlDecode($record['account_department_id']));
		define('ACCOUNT_DESIGNATION_ID', htmlDecode($record['account_designation_id']));
		define('ACCOUNT_DEPARTMENT_NAME', htmlDecode($record['account_department_name']));
		define('ACCOUNT_TEAM_ID', htmlDecode($record['account_team_id']));
		define('ACCOUNT_LEVEL_ID', htmlDecode($record['account_level_id']));
		define('ACCOUNT_ID', htmlDecode($record['id']));
		define('ACCOUNT_ALIAS', (!empty(htmlDecode($record['alias'])) ? htmlDecode($record['alias']) : htmlDecode($record['first_name'])));
		define('ACCOUNT_NAME', htmlDecode(fullName($record['first_name'], $record['middle_name'], $record['last_name'])));
		define('ACCOUNT_EMAIL', htmlDecode($record['email']));
		define('ACCOUNT_PHOTO', userPhoto(htmlDecode($record['photo']), 'account', htmlDecode($record['gender'])));
		define('ACCOUNT_TEAM_LEADER_ID', htmlDecode($record['team_leader_account_id']));

		if (!empty(date('m-d', strtotime($record['birthday']))) && date('m-d', strtotime($record['birthday'])) == date('m-d')) {
			define('IS_BIRTHDAY', true);
		} else {
			define('IS_BIRTHDAY', false);
		}

		Shortcode::accessGranted(ACCOUNT_ID, $controller, $view);
		Shortcode::onlineMember(ACCOUNT_ID);
	} else {
		sessionDestroy();
	}
} else {
	define('ACCOUNT_TYPE_ID', '');
	define('ACCOUNT_TYPE_NAME', '');
	define('ACCOUNT_ROLE_ID', '');
	define('ACCOUNT_DEPARTMENT_ID', '');
	define('ACCOUNT_DESIGNATION_ID', '');
	define('ACCOUNT_DEPARTMENT_NAME', '');
	define('ACCOUNT_TEAM_ID', '');
	define('ACCOUNT_LEVEL_ID', '');
	define('ACCOUNT_ID', 0);
	define('ACCOUNT_ALIAS', '');
	define('ACCOUNT_EMAIL', '');
	define('ACCOUNT_PHOTO', '');
	define('ACCOUNT_TEAM_LEADER_ID', 0); //NOT ALLOWED EMPTY 
	define('IS_BIRTHDAY', false);

	checkRememberMeCookieToken();
}

define('SYSTEM_DOMAIN', $CONFIGURATION['SYSTEM_DOMAIN']);
define('CONFIGURATION_SYSTEM_NAME', $CONFIGURATION['SYSTEM_NAME']);
define('CONFIGURATION_SYSTEM_ALIAS', $CONFIGURATION['SYSTEM_ALIAS']);
define('CONFIGURATION_SYSTEM_VERSION', $CONFIGURATION['SYSTEM_VERSION']);

require_once('app/views/layout/' . $template . '.php');
