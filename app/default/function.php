<?php
//START
ob_start();
session_start();

//SET TIMEZONE
date_default_timezone_set('Singapore');

function port()
{
	//return ':1001';
}

function getDocumentRoot()
{
	return $_SERVER['DOCUMENT_ROOT'];
}

function getCurrentUrl()
{
	return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . port();
}

function getSiteUrl()
{
	return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME'] . port();
}

function getFolderUrl()
{
	return "http://" . $_SERVER['SERVER_NAME'] . port();
}

function openWithChromeUrl()
{
	return "http://" . $_SERVER['SERVER_NAME'] . port();
}

function baseUrl()
{
	return '/';
}

function goBack()
{
	return '/';
}

function reportFileOutput()
{
	if ($_SERVER['SERVER_NAME'] == 'localhost') {
		$server['excel_extension'] 	= 'xls';
		$server['content_type'] 	= 'application/vnd.ms-excel';
		$server['create_writer'] 	= 'Excel5';
	} else {
		$server['excel_extension'] 	= 'xlsx';
		$server['content_type'] 	= 'application/xlsx';
		$server['create_writer'] 	= 'Excel2007';
	}

	// private static $excel_extension = ('localhost' == 'localhost' ? 'xls' : 'xlsx'); //Excel5 = xls : Excel2007 = xlsx
	// private static $content_type    = ('localhost' == 'localhost' ? 'application/vnd.ms-excel' : 'application/xlsx'); //Excel5 = xls = application/vnd.ms-excel : Excel2007 = xlsx = application/xlsx
	// private static $create_writer   = ('localhost' == 'localhost' ? 'Excel5' : 'Excel2007'); //Excel5 = xls = application/vnd.ms-excel : Excel2007 = xlsx = application/xlsx

	return $server;
}

function views($file, $data = '')
{
	$path = explode('.', $file);
	require_once('app/views/' . $path[0] . '/' . $path[1] . '.php');
}

function viewConvertUrl($url)
{
	$explode = explode('-', $url);
	$ctr 	 = 0;
	foreach ($explode as $key) {
		if ($ctr == 0) {
			$value = $key;
		} else {
			$value .= ucwords($key);
		}
		$ctr++;
	}
	return $value;
}

function viewDiplayUrl($url)
{
	$value = ucwords(preg_replace('/(?<!\ )[A-Z]/', ' $0', $url));
	return $value;
}

function includeController($files)
{
	if (is_array($files)) {
		foreach ($files as $file) {
			require_once(getDocumentRoot() . '/app/controllers/' . $file . '.php');
		}
	} else {
		require_once(getDocumentRoot() . '/app/controllers/' . $files . '.php');
	}
}

function includeModel($files)
{
	if (is_array($files)) {
		foreach ($files as $file) {
			require_once(getDocumentRoot() . '/app/models/' . $file . '.php');
		}
	} else {
		require_once(getDocumentRoot() . '/app/models/' . $files . '.php');
	}
}

function includeCommon($files)
{
	if (is_array($files)) {
		foreach ($files as $file) {
			require_once(getDocumentRoot() . '/app/views/common/' . $file . '.php');
		}
	} else {
		require_once(getDocumentRoot() . '/app/views/common/' . $files . '.php');
	}
}

function includeLibrary($files)
{
	if (is_array($files)) {
		foreach ($files as $file) {
			require_once(getDocumentRoot() . '/app/library/' . $file);
		}
	} else {
		require_once(getDocumentRoot() . '/app/library/' . $files);
	}
}

function includeDefault($files)
{
	if (is_array($files)) {
		foreach ($files as $file) {
			require_once(getDocumentRoot() . '/app/default/' . $file . '.php');
		}
	} else {
		require_once(getDocumentRoot() . '/app/default/' . $files . '.php');
	}
}

function uploadFile($folder, $file)
{
	return getDocumentRoot() . '/upload/' . $folder . '/' . $file;
}

function deleteFile($folder, $file)
{
	if (!empty($folder) && !empty($file) && file_exists(uploadFile($folder, $file))) {
		unlink(uploadFile($folder, $file));
	}
}

function moveFile($from, $to, $delete = '')
{
	if (!empty($from) && !empty($to)) {
		if (copy($from, $to)) {
			if (strtolower($delete) == 'delete') {
				unlink($from);
			}
		}
	}
}

//CHECK USER LOGGED IN
function checkLoggedIn($allow)
{
	switch ($allow) {

		//CAN USE IF USER LOGGED IN
		case 'true':
			if (!isset($_SESSION['login_id'])) {
				ob_clean();
				header('location: /login/' . safe_b64encode(getCurrentUrl()));
				die();
			}
			break;

		//CAN'T USE IF USER LOGGED IN						
		case 'false':
			if (isset($_SESSION['login_id']) && $_SESSION['login_id'] == true) {
				ob_clean();
				header('location: /');
				die();
			}
			break;

		//FOR ORDINARY PAGE | CONTENT PAGE ONLY						
		case 'none':
			//DO NOTHING
			break;
	}
}

//DESTROY ACTIVE SESSION | LOGIN SESSION
function sessionDestroy()
{
	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(
			session_name(),
			'',
			time() - 42000,
			$params["path"],
			$params["domain"],
			$params["secure"],
			$params["httponly"]
		);

		// Finally, destroy the session.
		session_unset();
		session_destroy();
	}
}

//ALLOW ACCESS BASED ON ACCOUNT_TYPE
function allowAccess($account, $users)
{
	if (is_array($users)) {
		if (!in_array($account, $users)) {
			header('location: /');
			die();
		}
	} else {
		if ($account <> $users) {
			header('location: /');
			die();
		}
	}
}

//RESTRICT MODULE/SECTION BASED ON ACCESS ROLE
function accessGranted($roles)
{
	$list = explode('-', ACCOUNT_ROLE_ID);

	$allow = false;

	if (is_array($roles)) {
		foreach ($roles as $role) {
			if (in_array($role, $list)) {
				$allow = true;
			}
		}
	} else {
		if (in_array($roles, $list)) {
			$allow = true;
		}
	}

	return $allow;
}

// ENCRYPT GET ID
function idEncrypt($value)
{
	if (empty($value)) {
		return '';
	} else {
		$string = rand(rand(100, 999), rand(1000, 9999)) . '-' . $value . '-' . dateTimeAsId();
		return safe_b64encode($string);
	}
}

// DECRYPT GET ID
function idDecrypt($value)
{
	if (empty($value)) {
		return '';
	} else {
		$string  = safe_b64decode($value);
		$explode = explode('-', $string);

		return $explode[1];
	}
}

//DATABASE SAVE DATE FORMAT
function dateStamp()
{
	return date('Y-m-d');
}

//DATABASE SAVE TIME FORMAT
function timeStamp()
{
	//return date('H:i:s');
	return date('Y-m-d H:i:s');
}

//DATABASE SAVE DATE and TIME FORMAT
function dateTimeStamp()
{
	return date('Y-m-d H:i:s');
}

//USE DATE TIME AS ID / UNIQUE 
function dateTimeAsId()
{
	return date('YmdHis');
}

//SYSTEM DATE DISPLAY
function dateDisplay()
{
	return date('d-M-Y');
}

//SYSTEM DATE DISPLAY
function dateDisplaySystem($date)
{
	$strtotime = strtotime($date);
	if (empty($strtotime)) {
		return '';
	} else {
		return date('d-M-Y', strtotime(htmlEncode($date)));
	}
}

//SYSTEM DATE DISPLAY
function dateMonthYear($date)
{
	$strtotime = strtotime($date);
	if (empty($strtotime)) {
		return '';
	} else {
		return date('M-Y', strtotime(htmlEncode($date)));
	}
}

//DISPLAY FULL DATE AND TIME 
function displayFullDateAndTime($date)
{
	$strtotime = strtotime($date);
	if (empty($strtotime)) {
		return '';
	} else {
		return date('F d, Y', strtotime(htmlEncode($date))) . ' ' . date('h:i:s A', strtotime(htmlEncode($date)));
	}
}

//DATE FORMAT : PROPER DISPLAY
function dateDisplayFull($date)
{
	$strtotime = strtotime($date);
	if (empty($strtotime)) {
		return '';
	} else {
		return date('F d, Y', strtotime(htmlEncode($date)));
	}
}

//DATE REFORMAT
function dateReformat($date, $format)
{
	if (!empty($date)) {
		return date($format, strtotime(htmlEncode($date)));
	}
}

//DATE CALENDAR
function dateCalendar($date)
{
	$strtotime = strtotime($date);
	if (empty($strtotime)) {
		return '';
	} else {
		return date('m/d/Y', strtotime(htmlEncode($date)));
	}
}

//TIME FORMAT : PROPER TIME DISPLAY
function timeDisplayFull($date)
{
	$strtotime = strtotime($date);
	if (empty($strtotime)) {
		return '';
	} else {
		return date('h:i A', strtotime(htmlEncode($date)));
	}
}

//COUNT DAY DIFFERENCE BETWEEN TWO DATES
function differenceDays($date_1, $date_2)
{
	$datediff = strtotime($date_2) - strtotime($date_1);
	return floor($datediff / (60 * 60 * 24));
}

//COUNT MONTHS DIFFERENCE BETWEEN TWO DATES
function differenceMonths($date_1, $date_2)
{
	$date_1   = strtotime($date_1);
	$date_2   = strtotime($date_2);
	$min_date = min($date_1, $date_2);
	$max_date = max($date_1, $date_2);
	$counter  = 0;

	while (($min_date = strtotime("+1 MONTH", $min_date)) <= $max_date) {
		$counter++;
	}

	return $counter;
}

//COUNT YEAR DIFFERENCE BETWEEN TWO YEARS
function differenceYears($date_1, $date_2)
{
	$date_1 = new DateTime($date_1);
	$date_2 = new DateTime($date_2);

	return $date_1->diff($date_2)->format("%y");
}

//DISPLAY PREVIOUS DATE
function previousDate($format, $date, $no_days)
{
	return date($format, strtotime($date . ' -' . $no_days . ' day'));
}

//DISPLAY PREVIOUS MONTH
function previousMonth($format, $date, $no_months)
{
	return date($format, strtotime($date . ' -' . $no_months . ' month'));
}

//DISPLAY NEXT DATE
function nextDate($format, $date, $no_days)
{
	return date($format, strtotime($date . ' +' . $no_days . ' day'));
}

//DISPLAY NEXT MONTH
function nextMonth($format, $date, $no_months)
{
	return date($format, strtotime($date . ' +' . $no_months . ' month'));
}

//GET BIRTHDAY
function birthday($birthday, $suffix = '')
{
	if (!empty($birthday)) {
		return date_diff(date_create($birthday), date_create('now'))->y . $suffix;
	}
}

//DATE FORMAT : DATABASE FORMAT
function dateSaveDB($date)
{
	if (empty($date)) {
		return '';
	} else {
		return date('Y-m-d', strtotime(htmlEncode($date)));
	}
}

//TIME FORMAT : 24 HOURS
function timeSaveDB($time)
{
	return date('H:i:s', strtotime(htmlEncode($time)));
}

//CONCAT STRING
function concatString($string, $delimeter)
{
	$string = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $string)));
	$string = str_replace(' ', $delimeter, $string);

	return $string;
}

//CLEAN MONEY VALUE
function moneyClean($value = '')
{
	if (!empty(str_replace(' ', '', $value))) {
		//return str_replace(',', '', $value);
		return str_replace(',', '', str_replace(' ', '', $value));
	} else {
		return '0.00';
	}
}

//DEFAULT MONEY VALUE
function moneyDefault($value = '')
{
	if (!empty($value)) {
		return moneyClean($value);
	} else {
		return '0.00';
	}
}

//CLEAN NUMBER VALUE :: ALLOWED NUMBER ONLY
function numberClean($value = '')
{
	if (!empty($value)) {
		$value = floor($value);
		if (is_numeric($value)) {
			return (int)($value);
		} else {
			return '0';
		}
	} else {
		return '0';
	}
}

//MONEY FORMAT
function formatMoney($amount)
{
	$amount = htmlDecode($amount);
	if ($amount == 0 || $amount == 0.00 || $amount == null || $amount == '') {
		$money = '0.00';
	} else {
		//$money = floor($amount * 100) / 100;
		//$money = bcdiv($amount, 1, 2);

		$money = number_format(moneyClean($amount), 2);

		// $amount = explode('.', moneyClean($amount));
		// $amount = $amount[0].'.'.(isset($amount[1]) ? substr($amount[1], 0, 2) : '00');
		// $money  = number_format(moneyClean($amount), 2);
	}
	return $money;
}

//GENERATE A GLOBALLY UNIQUE IDENTIFIER (GUID)
function getGUID()
{
	if (function_exists('com_create_guid')) {
		return com_create_guid();
	} else {
		mt_srand((float)microtime() * 10000); //optional for php 4.2.0 and up.
		$charid = strtoupper(md5(uniqid(rand(), true)));
		$hyphen = chr(45); // "-"
		$uuid = // "{"
			$hyphen . substr($charid, 0, 8);
		return $uuid;
	}
}

//CONVERT NUMBER TO ORDINAL FORMAT
function convertOrdinal($num)
{
	if (($num / 10) % 10 != 1) {
		switch ($num % 10) {
			case 1:
				return $num . 'st';
			case 2:
				return $num . 'nd';
			case 3:
				return $num . 'rd';
		}
	}

	return $num . 'th';
}

function displayVarWithDefault($var, $default)
{
	$value = $default;
	if (isset($var) && !empty($var)) {
		$value = htmlDecode($var);
	}
	return $value;
}
function displayVarWithReturn($var, $returnTrue, $returnFalse)
{
	$value = $returnFalse;
	if (isset($var) && !empty($var)) {
		$value = $returnTrue;
	}
	return $value;
}

//GET VARIABLE
function getVar($get, $default = '')
{
	if (isset($_GET[$get]) && !empty(trim($_GET[$get]))) {
		return htmlEncode($_GET[$get]);
	} else {
		return $default;
	}
}

//POST VARIABLE
function postVar($post, $default = '')
{
	if (isset($_POST[$post]) && !empty(trim($_POST[$post]))) {
		return htmlEncode($_POST[$post]);
	} else {
		return $default;
	}
}

//EMAIL VALIDATION
function validateEmail($field_name = '', $action = 'post')
{
	$result = '';
	if ($action == 'get') {
		$value = getVar($field_name, '');
	} else {
		$value = postVar($field_name, '');
	}

	$email = preg_replace('/\s+/', '', trim(strtolower($value))); //remove spaces; convert to lowercase
	$email = explode(",", $email); //get list if one or more entries
	if (is_array($email)) {
		$email = array_filter($email); //remove empty values in array
		$validated_email = array();
		foreach ($email as $key => $value) {
			$sanitized_email = filter_var($value, FILTER_SANITIZE_EMAIL); //remove special char
			$valid_email 	 = filter_var($sanitized_email, FILTER_VALIDATE_EMAIL); //check sanitized email if valid email format
			if (!empty($valid_email)) {
				$validated_email[] = $valid_email;
			}
		}
		$email = $validated_email;
	}
	$email = implode(",", $email);
	return htmlEncode($email);
}

//CHECK STRING VALUE IF VALID EMAIL ADDRESS
function validEmail($value)
{

	$clean_email     = preg_replace('/\s+/', '', trim(strtolower($value)));
	$sanitized_email = filter_var($clean_email, FILTER_SANITIZE_EMAIL); //remove special char
	$valid_email 	 = filter_var($sanitized_email, FILTER_VALIDATE_EMAIL); //check sanitized email if valid email format

	if (!empty($valid_email)) {
		$email = $valid_email;
	} else {
		$email = '';
	}

	return $email;
}

//CHECK IF POST VARIABLE IS NOT EMPTY
function checkRequiredPost($array)
{
	foreach ($array as $key) {
		if (empty(trim($_POST[$key]))) {
			$data['error'][$key] = requiredPrompt('Required field');
		} else {
			$data['post'][$key] = htmlEncode($_POST[$key]);
		}
	}

	return $data;
}

//CHECK IF API POST VARIABLE IS NOT EMPTY
function checkApiRequiredPost($array)
{
	$data = array();
	foreach ($array as $key) {
		if ((isset($_POST[$key]) && empty(trim($_POST[$key]))) || !isset($_POST[$key])) {
			$data['error'][$key] = 'Required field';
		} else {
			$data['post'][$key] = htmlEncode($_POST[$key]);
		}
	}
	return $data;
}

//API REQUEST VALIDATION
function validateRequestedMethod($method = '')
{
	$return = array();
	//valid methods POST, GET, PUT, DELETE,
	if (!empty($method)) {
		$valid_method = $method;
		if ($_SERVER['REQUEST_METHOD'] != $method) {
			$return['code']     = '401';
			$return['message']  = 'Request Method is not permitted.';
			header('Access-Control-Allow-Origin', '*');
			header('Content-Type: application/json');
			echo json_encode($return, JSON_PRETTY_PRINT);
			die();
		}
	} else {
		$valid_method = ['POST', 'GET', 'PUT', 'DELETE'];
		if (!in_array($_SERVER['REQUEST_METHOD'], $valid_method)) {
			$return['code']     = '401';
			$return['message']  = 'Request Method is not permitted.';
			header('Access-Control-Allow-Origin', '*');
			header('Content-Type: application/json');
			echo json_encode($return, JSON_PRETTY_PRINT);
			die();
		}
	}
	return $return;
}

//API LOGIN VALIDATION
function validateApiAuthorization($app = '')
{
	$CONFIGURATION = Configuration::general();
	if ((isset($_SERVER['PHP_AUTH_USER']) && safe_b64encode($_SERVER['PHP_AUTH_USER']) != $CONFIGURATION[$app]['USERNAME']) && (isset($_SERVER['PHP_AUTH_PW']) && $_SERVER['PHP_AUTH_PW'] != passwordEncode($CONFIGURATION[$app]['PASSWORD']))) {
		$return['code']     = '401';
		$return['message']  = 'Unauthorized';
		header('WWW-Authenticate: Basic realm="My Realm"');
		header('HTTP/1.0 401 Unauthorized');
		echo json_encode($return, JSON_PRETTY_PRINT);
		die();
	}
}

//CHANGE GET PARAMETER VALUE
function changeGetValue($variable, $value = '')
{
	$link = '';

	if (!empty(getVar($variable))) {
		$explode = explode($variable . '=' . getVar($variable), $_SERVER['REQUEST_URI']);
		if (count($explode) >= 2) {
			$link = $explode[0] . $variable . '=' . $value . $explode[1];
		}
	}

	return $link;
}

//PAGINATION QUERY PARAMETERS
function pagination($value, $total = '')
{
	$CONFIGURATION = Configuration::general();

	$parameter['limit'] = (getVar('limit') ? getVar('limit') : $CONFIGURATION['PAGINATION']);
	$parameter['start'] = ((getVar('page') ? getVar('page') - 1 : 0)) * $parameter['limit'];
	//$parameter['start'] = (!empty(getVar('page') ? getVar('page')-1 : 1))*$parameter['limit'];
	//$parameter['start'] = (getVar('page')-1)*$parameter['limit'];

	$parameter['total'] = 0;
	if (!empty($total)) {
		$parameter['total'] = ceil($total / $parameter['limit']);
	}

	return $parameter[$value];
}

//PAGINATION COUNTER
function paginationCounter($page, $total_page, $total_record)
{
	$value = 'Showing <b>' . (!empty($page) ? $page : 1) . '</b> to <b>' . $total_page . '</b> of <b>' . $total_record . '</b> entries';

	return $value;
}

//SQL QUERY DATE FROM
function queryDateFormat($date)
{
	$strtotime = strtotime($date);
	if (empty($strtotime)) {
		return '';
	} else {
		return date('Y-m-d', strtotime(htmlEncode($date)));
	}
}

//REPORT DATE FROM
function reportDateFrom($date)
{
	$strtotime = strtotime($date);
	if (empty($strtotime)) {
		return '';
	} else {
		return date('Y-m-d', strtotime(htmlEncode($date)));
	}
}

//REPORT DATE TO
function reportDateTo($date)
{
	$strtotime = strtotime($date);
	if (empty($strtotime)) {
		return '';
	} else {
		return date('Y-m-d', strtotime("+1 day", strtotime(htmlEncode($date))));
	}
}

//REPORT DATE
function reportDate($date)
{
	$strtotime = strtotime($date);
	if (empty($strtotime)) {
		return '';
	} else {
		return date('Y-m-d', strtotime(htmlEncode($date)));
	}
}

//REPORT QUERY WHERE LIKE 
function reportWhereLikeIn($column, $array)
{
	$return = '';
	foreach ($array as $item) {
		$sql[] = $column . " LIKE '%$item%' ";
	}
	$return = implode(" OR ", $sql);

	return $return;
}

//COUNT ARRAY
function countArray($array)
{
	if (isset($array) && !empty($array)) {
		$value = count($array);
	} else {
		$value = 0;
	}

	return $value;
}

//COUNT TOTAL NUMBER WITH SAME VALUE IN MULTIDIMENSIONAL ARRAY
function countArrayValue($array, $row, $value)
{
	$count = 0;
	for ($i = 0; $i < count($array); $i++) {
		if ($array[$i][$row] == $value) {
			$count++;
		}
	}

	return $count;
}

//LIMIT WORDS DISPLAY
function wordTruncate($input, $maxWords, $maxChars)
{
	$words     = preg_split('/\s+/', $input);

	if ($maxWords > 0) {
		$words = array_slice($words, 0, $maxWords);
	}

	$words 	   = array_reverse($words);
	$chars 	   = 0;
	$truncated = array();

	if (count($words) == 1) {
		$result = substr($input, 0, $maxChars);
	} else {
		while (count($words) > 1) {
			$fragment = trim(array_pop($words));
			$chars += strlen($fragment);

			if ($chars > $maxChars) break;

			$truncated[] = $fragment;
		}

		//$result = implode($truncated, ' ');
		$result = implode(' ', $truncated);
	}

	return $result . ($input == $result ? '' : '...');
}

//REMOVE ALL SPACES
function spaceRemove($value)
{
	if (!empty($value)) {
		return preg_replace("/\s+/", "", $value);
	}
}

//CLEAN URL STRING
function urlClean($string)
{
	$string = str_replace('/', ' ', $string); // Replaces all spaces with hyphens.
	$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
	$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

	return preg_replace('/-+/', '-', strtolower($string)); // Replaces multiple hyphens with single one.
}

//CLEAN NAME STRING
function nameClean($string)
{
	$string = str_replace('/', ' ', $string); // Replaces all spaces with hyphens.
	$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
	$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

	return preg_replace('/-+/', '-', strtolower($string)); // Replaces multiple hyphens with single one.
}

//CLEAN AND PROPER FORMAT NAME
function formatName($value = '')
{
	if (!empty($value)) {
		return ucwords(preg_replace('/\s+/', ' ', trim($value)));
	} else {
		return $value;
	}
}

//CLEAN STRING - REMOVE ALL SPECIAL CHARACTERS
function removeSpecialCharacter($string)
{
	return preg_replace('/[^A-Za-z0-9]/', ' ', trim($string)); // Removes special chars.
}

//CLEAN CODE NUMNER
function numberCode($string)
{
	$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

	return preg_replace('/-+/', '', strtolower($string)); // Replaces multiple hyphens with single one.
}

//CLEAN ASSOCIATE ARRAY ONLY
function arrayClean($array)
{
	if (is_array($array) && !empty($array)) {
		$value = array_filter($array, function ($element) {
			return is_string($element) && '' !== trim($element);
		});

		return array_values($value);
	}
}

//REMOVE DATA FROM ARRAY
function removeFromArrayExcept($array, $keys_to_get)
{
	$array_to_filter = [];

	if (!empty($keys_to_get)) {
		if (is_array($keys_to_get)) {
			$array_to_filter = $keys_to_get;
		} else {
			$array_to_filter[] = $keys_to_get;
		}
	}

	if (is_array($array) && (is_array($array_to_filter))) {
		foreach ($array as $key => $value) {
			if (!in_array($key, $array_to_filter)) {
				unset($array[$key]);
			}
		}
	}

	return $array;
}

function properCase($string)
{
	return ucwords(strtolower(htmlDecode($string)));
}

//GENERATE UNIQUE IMAGE NAME
function uniqueImageName($filename)
{
	$name = strtolower(safe_b64encode(substr(urlClean($filename), 0, 5) . date('Ymd')));
	return $name;
}

//IMAGE NAME
function getImageName($file, $post_name)
{
	$split = explode('.', $file);
	return $split[0] . $post_name . '.' . $split[1];
}

//CONVERT FILENAME TO CAPITALIZE FIRST LETTER
function filenameConvert($string)
{
	if (!empty($string)) {
		$array = preg_split('/(?=[A-Z])/', $string);

		$name = '';
		foreach ($array as $value) {
			$name .= ucwords($value) . ' ';
		}
	}
	return trim($name);
}


/* REVISE THIS FUNCTION : IDEA SIMPLE CURL REST POST
	function cURLApiRestPOST($url,$fields){
    	$CONFIGURATION  = Configuration::general();
    	
		// pre($url ."?". http_build_query($fields));

        $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL				=> $url,
            	CURLOPT_RETURNTRANSFER 	=> true,
				CURLOPT_TIMEOUT 		=> 30,
				CURLOPT_HTTP_VERSION 	=> CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST 	=> "POST",
				CURLOPT_HTTPHEADER 		=> array(
					"cache-control: no-cache"
				),
				CURLOPT_ENCODING       	=> 'UTF-8',
				CURLOPT_POSTFIELDS    	=> $fields
        ));

        $response = curl_exec($curl);
        $err      = curl_error($curl);

        curl_close($curl);

        if($err){
            return "cURL API Error #:" . $err;
        }else{
            return $response;
        } 
	}
	*/

function cURLApiRestPOST($url, $username, $password, $parameter)
{
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL             => $url,
		CURLOPT_RETURNTRANSFER  => true,
		CURLOPT_SSL_VERIFYHOST  => false,
		CURLOPT_SSL_VERIFYPEER  => false,
		CURLOPT_ENCODING        => '',
		CURLOPT_MAXREDIRS       => 10,
		CURLOPT_TIMEOUT         => 120,
		CURLOPT_FOLLOWLOCATION  => true,
		CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST   => 'POST',
		CURLOPT_POSTFIELDS      => $parameter,
		CURLOPT_HTTPHEADER      => array(
			'Authorization: Basic ' . base64_encode($username . ':' . $password)
		)
	));
	$response = curl_exec($curl);
	$err      = curl_error($curl);
	curl_close($curl);
	if ($err) {
		return "cURL Claim Error #:" . $err;
	} else {
		return json_decode($response, true);
	}
}

//CHECK IF STRING IS EXIST
function requestUri($array, $parameter)
{
	$check = false;
	foreach ($array as $value) {
		if (strpos($parameter, $value)) {
			$check = true;
		}
	}

	return $check;
}

//REMOVE HTTPS OR HTTP FROM URL VALUE
function httpsCleanup($website_url)
{
	$return = '';

	if (!empty($website_url)) {
		$website_url = str_replace('https://', '', $website_url);
		$website_url = str_replace('http://', '', $website_url);
		$website_url = rtrim($website_url, '/');
		$return 	 = $website_url;
	}
	return $return;
}

//PROPER LINK
function websiteLink($link = '')
{
	if (!empty($link)) {
		$link = '<a href="https://' . httpsCleanup(htmlDecode($link)) . '" class="tx-default" target="_blank">' . httpsCleanup(wordTruncate(htmlDecode($link), 0, 45)) . '</a>';
	}
	return $link;
}

//LOGIN GREETINGS
function loginGreetings()
{
	$hour 	 = date('H');
	$message = "";

	if ($hour >= 22) {
		$message = "Get some rest";
	} elseif ($hour >= 18) {
		$message = "Good evening";
	} elseif ($hour >= 12) {
		$message = "Good afternoon";
	} elseif ($hour >=  9) {
		$message = "Good morning";
	} elseif ($hour >=  0) {
		$message = "Mornin' Sunshine";
	}

	return $message;
}

function removeFile($dir)
{
	if (is_dir($dir)) {
		$files = scandir($dir);
		foreach ($files as $file) {
			if ($file != "." && $file != "..") {
				removeFile("$dir/$file");
			}
		}
		rmdir($dir);
	} elseif (file_exists($dir)) {
		unlink($dir);
	}
}

function copyFile($src, $dst)
{
	if (file_exists($dst)) {
		removeFile($dst);
	}
	if (is_dir($src)) {
		mkdir($dst);
		$files = scandir($src);
		foreach ($files as $file) {
			if ($file != "." && $file != "..") {
				copyFile("$src/$file", "$dst/$file");
			}
		}
	} elseif (file_exists($src)) {
		copy($src, $dst);
	}
}

//EXCEL DATE FORMAT
function excelDateFormat($excel_date = '')
{

	if (!empty($excel_date) && is_numeric($excel_date)) {
		//$excel_date = 43010; //here is that value 41621 or 41631
		$unix_date  = ($excel_date - 25569) * 86400;
		$excel_date = 25569 + ($unix_date / 86400);
		$unix_date  = ($excel_date - 25569) * 86400;

		$excel_date = gmdate("Y-m-d", $unix_date);
	}

	return $excel_date;
}

//PAGINATION SORT COLUMN
function sortby($column_list = array())
{

	$column 	  = (getVar('column') ? (getVar('column')) : '');
	$order 		  = (getVar('order') ? (getVar('order')) : 'ASC');
	$column_value = array_search($column, array_column($column_list, 'id'));
	$column 	  = (!empty($column_value) || $column_value === 0) && isset($column_list[$column_value]['id']) ? safe_b64decode($column_list[$column_value]['id']) : '';
	$order 		  = $order != 'ASC' && $order != 'DESC' ? 'ASC' : $order;
	$sort  		  = !empty($column) && !empty($order) ? $column . ' ' . $order : '';

	return $sort;
}

//CURL API FOR GOOGLE CALENDAR 
function cURLHolidayApiRestGet($url)
{
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL             => $url,
		CURLOPT_RETURNTRANSFER  => true,
		CURLOPT_SSL_VERIFYHOST  => false,
		CURLOPT_SSL_VERIFYPEER  => false,
		CURLOPT_ENCODING        => '',
		CURLOPT_MAXREDIRS       => 10,
		CURLOPT_TIMEOUT         => 120,
		CURLOPT_FOLLOWLOCATION  => true,
		CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST   => 'GET',
		CURLOPT_POSTFIELDS      => ''
	));
	$response = curl_exec($curl);
	$err      = curl_error($curl);
	curl_close($curl);
	if ($err) {
		return "cURL Claim Error #:" . $err;
	} else {
		return json_decode($response, true);
	}
}

//CHECK IF EMPTY THEN REDIRECT
function emptyRedirectPage($value, $url = '/page-not-found')
{
	if (empty($value)) {
		header('Location: ' . $url);
		die();
	}
}

//RESTRICTED PAGE : WITH ROLE ACCESS HAS ALLOWED
function accessRole($role)
{
	//ROLE MUST BE ARRAY
	//ACCOUNT_TYPE_ID != 1 : ADMINISTRATOR
	if (empty($value)) {
		if (!accessGranted($role) && ACCOUNT_TYPE_ID != 1 && strpos($_SERVER['REQUEST_URI'], 'json') === false) {
			header('location: /page-not-found');
			die();
		}
	}
}

//ALLOWED EMAIL BASED ON STRING LENGTH
function emailFilterLength($email, $string_count = 100)
{
	$email_clean = '';
	if (!empty($email)) {
		$email 					= str_replace(' ', '', $email);
		$email_list  			= explode(",", $email);
		$total_character_length	= 0;
		foreach ($email_list as $key => $value) {
			$character_length = strlen($value);
			$total_character_length += $character_length;
			if ($total_character_length <= $string_count) {
				$valid_email[] = $value;
				$countWithComma = $total_character_length + 1;
				if (isset($email_list[$key + 1]) && $countWithComma <= $string_count) {
					$total_character_length++;
				} else {
					break;
				}
			} else {
				break;
			}
		}
		//$email_clean = implode($valid_email,",");
		$email_clean = implode(",", $valid_email);
	}

	return $email_clean;
}

//GET VALUE FROM STRING PARAMETERS
function valueFromParameter($string, $key)
{
	$value = NULL;

	if (!empty($string) && !empty($key)) {
		parse_str(parse_url($string, PHP_URL_QUERY), $result);

		$value = isset($result[$key]) ? $result[$key] : $value;
	}

	return $value;
}

//COMPARE THE TWO DATA IF THERE ARE CHANGES
function hasChanges($originalData, $newData, $except)
{
	$return = false;
	array_multisort($originalData);
	array_multisort($newData);
	if (is_array($except)) {
		foreach ($except as $item) {
			unset($originalData[$item]);
			unset($newData[$item]);
		}
	} else {
		unset($originalData[$except]);
		unset($newData[$except]);
	}

	if (serialize($originalData) === serialize($newData)) {
		$return = false;
	} else {
		$return = true;
	}
	return $return;
}

//HIGHLIGHT ACTIVE CONTROLLER
function activeController($controller = '', $class = '')
{
	if (isset($_GET['controller']) && in_array($_GET['controller'], $controller)) {
		echo $class;
	}
}

//HIGHLIGHT ACTIVE VIEW
function activeView($controller = '', $view = '', $class = '', $sub = '', $value = '')
{
	if (!empty($sub) && !empty($value)) {
		if (isset($_GET['controller']) && in_array($_GET['controller'], $controller) && isset($_GET['view']) && in_array($_GET['view'], $view) && $_GET[$sub] == $value) {
			echo $class;
		}
	} else {
		if (isset($_GET['controller']) && in_array($_GET['controller'], $controller) && isset($_GET['view']) && in_array($_GET['view'], $view)) {
			echo $class;
		}
	}
}

//HIGHLIGHT DASHBOARD
function activeDashboard($class = '')
{
	if (!isset($_GET['controller']) && !isset($_GET['view']) || $_GET['controller'] == 'page' && $_GET['view'] == 'dashboard') {
		echo $class;
	}
}

//MENU HIGHLIGHT ACTIVE
function menuActive($class, $page, $variable, $value)
{
	$link = substr(strtolower(basename($_SERVER['PHP_SELF'])), 0, strlen(basename($_SERVER['PHP_SELF'])) - 4);
	$list = explode(',', preg_replace('/\s+/', '', $page));
	if (!empty($link) && !empty($page)) {
		if (in_array($link, $list)) {
			echo 'class="' . $class . '"';
		}
	} else {
		if (isset($_GET[$variable]) && $_GET[$variable] == $value) {
			echo 'class="' . $class . '"';
		}
	}
}

//ACCOUNT MENU HIGHLIGHT ACTIVE
function accountMenuActive($controller, $page)
{
	if (isset($_GET['controller']) && $_GET['controller'] == $controller && isset($_GET['view']) && $_GET['view'] == $page) {
		echo 'class="current"';
	} elseif (!isset($_GET['view']) && $page == 'home') {
		echo 'class="current"';
	}
}

//CURRENT PAGE
function currentPage($controller = '', $page = '')
{
	if (!isset($_GET['view']) && $page == 'home') {
		return true;
	} elseif (isset($_GET['controller']) && $_GET['controller'] == $controller && isset($_GET['view']) && $_GET['view'] == $page) {
		return true;
	} else {
		return false;
	}
}

//TAB HIGHTLIGHT
function tabMenu($param, $page)
{
	if (isset($_GET[$param]) && $_GET[$param] == $page) {
		echo 'class="current"';
	}
}

//FIELD ENCODER
function htmlEncode($field = '')
{
	//$trim  = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $field)));
	//$value = htmlentities(addslashes($trim));	

	if (!empty($field)) {
		$field = htmlentities(addslashes(trim($field)), ENT_QUOTES, "UTF-8");
	}

	return $field;
}

//FIELD DECODER
function htmlDecode($field = '', $type = '')
{
	if (!empty($field)) {
		$field = stripslashes(html_entity_decode(trim($field), ENT_QUOTES, "UTF-8"));

		if ($type == 'textarea') {
			$field = nl2br($field);
		}
		//return nl2br($value);
	}
	return $field;
}

//CLEAN ~ SPECIAL CHARACTER
function htmlEncodeSpecialCharacter($string)
{
	//return htmlEncode(str_replace("'", "\'", htmlentities($string, ENT_COMPAT, 'ISO-8859-1', true)));
	return str_replace("'", "\'", htmlentities($string, ENT_COMPAT, 'ISO-8859-1', true));
}

//FIELD ENCODER ADD SLASH
function htmlEncodeSlash($field = '')
{
	if (!empty($field)) {
		$trim  = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $field)));
		$field = htmlentities(addslashes($trim));
	}

	return $field;
}

//TEXTAREA DECODER
function textareaDecode($msg = "")
{
	return str_replace(array("\r\n", "\r", "\n", "<br>", "</br>"), "\\n", $msg);
}

//PASSWORD ENCODE SPECIAL CHARACTER
function passwordEncode($str)
{
	for ($i = 0; $i < 5; $i++) {
		$str = strrev(base64_encode($str)); //apply base64 first and then reverse the string
	}
	return $str;
}

//PASSWORD ENCODE SPECIAL CHARACTER
function passwordDecode($str)
{
	for ($i = 0; $i < 5; $i++) {
		$str = base64_decode(strrev($str)); //apply base64 first and then reverse the string
	}
	return $str;
}

// STRING ENCRYPTION
function safe_b64encode($string)
{
	$data = base64_encode($string);
	$data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
	return $data;
}

// STRING DECRYPTION
function safe_b64decode($string)
{
	$data = str_replace(array('-', '_'), array('+', '/'), $string);
	$mod4 = strlen($data) % 4;
	if ($mod4) {
		$data .= substr('====', $mod4);
	}
	return base64_decode($data);
}

//ENCRYPT
function encrypt($value)
{
	if (!$value) {
		return false;
	}

	$skey 		= "a%rQ~2qWne%F!}_D;e#A%~>{";
	$text 		= $value;
	$iv_size 	= mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	$iv 		= mcrypt_create_iv($iv_size, MCRYPT_RAND);
	$encrypted 	= mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $skey, $text, MCRYPT_MODE_ECB, $iv);

	return trim(safe_b64encode($encrypted));
}

//DECRYPT
function decrypt($value)
{
	if (!$value) {
		return false;
	}

	$skey 		= "a%rQ~2qWne%F!}_D;e#A%~>{";
	$crypttext 	= safe_b64decode($value);
	$iv_size 	= mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	$iv 		= mcrypt_create_iv($iv_size, MCRYPT_RAND);
	$decrypted	= mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $skey, $crypttext, MCRYPT_MODE_ECB, $iv);

	return trim($decrypted);
}

// META TAGS | SEO TITLE
function metaSeoTitle($field)
{
	$value = ucwords(strtolower($field));
	return ($value);
}

// META TAGS | SEO DESCRIPTION
function metaSeoDescription($field)
{
	$value = function_truncate(strtolower(trim(strip_tags($field))), 150, 160);
	return ($value);
}

// META TAGS | SEO KEYWORDS
function metaSeoKeyword($field)
{
	$value = ucwords(strtolower($field));
	return ($value);
}

//IF ARRAY KEY & VALUE IF NOT EMPTY
function arrayKeyValueExist($array, $key, $type = '')
{
	if (array_key_exists($key, $array)) {
		if (!empty($array[$key]) || $array[$key] != '') {
			return htmlDecode($array[$key], $type);
		}
	}
}

//IF ARRAY KEY IS EXIST
function arrayKeyExist($array, $key, $type = '')
{
	if (is_array($array) && array_key_exists($key, $array)) {
		return htmlDecode($array[$key], $type);
	}
	return NULL;
}

//IF MULTIDIMENSIONAL ARRAY KEY IS EXIST RETURN VALUE
function multiArrayKeyExist($array, $key1, $key2, $type = '')
{
	$result = NULL;
	if (is_array($array) && array_key_exists($key1, $array) && !is_null($array[$key1]) && array_key_exists($key2, $array[$key1])) {
		foreach ($array[$key1] as $element => $value) {
			if ($key2 == $element) {
				$result = $value;
			}
		}
	}

	return htmlDecode($result, $type);
}

//CHECK IF KEY EXIST IN MULTIDIMENSIONAL ARRAY
function multiKeyExists($array, $key)
{
	if (is_array($array) && array_key_exists($key, $array)) {
		// is in base array?
		if (array_key_exists($key, $array)) {
			return $array[$key];
		}
		// check arrays contained in this array
		foreach ($array as $element) {
			if (is_array($element)) {
				if (multiKeyExists($element, $key)) {
					//return true;
					return htmlDecode($element[$key]);
				}
			}
		}
	}
	return NULL;
}

//MERGE ALL VALUES FROM MULTI-DIMENSIONAL ARRAY
function mergeMultiArrayValue($array, $column_name)
{
	$list = array();

	if (is_array($array) && !empty($array)) {
		foreach ($array as $key => $value) {
			$list[] = $value[$column_name];
		}
	}
	return $list;
}

//BUILD GET ARRAY VARIABLES AND ENCODE
function getVariablesEncode($array)
{
	if (!empty($array)) {
		$result 	= '';;
		$separator 	= '';
		foreach ($array as $key => $value) {
			$result .= $separator . $key . '=' . $value;
			$separator = '&';
		}

		return safe_b64encode($result);
	}
}

//BUILD GET ARRAY VARIABLES AND DECODE
function getVariablesDecode($get)
{

	/*
		$str = "first=value&arr[]=foo+bar&arr[]=baz";
		parse_str($str, $output);
		echo $output['first'];  // value
		echo $output['arr'][0]; // foo bar
		echo $output['arr'][1]; // baz
		*/

	$str = safe_b64decode($get);
	//$str = "first=value&arr[]=foo+bar&arr[]=baz";
	parse_str($str, $output);

	return $output;

	//$_get = getVariablesDecode($_GET['var']);
	//getVariablesEncode(array('action'=>'add','id'=>''));
}

//CHECK IF DEFINE VARIABLE IS NOT EMPTY, IF EMPTY RETURN NULL
function defineValue($define)
{
	$value = '';
	if (isset($define) && !empty($define)) {
		$value = $define;
	}
	return $value;
}

//MARK 'checked' THE CHECKBOX
function loopCheckbox($array, $key, $value)
{
	if (array_key_exists($key, $array)) {
		$data = explode('-', $array[$key]);
		foreach ($data as $row) {
			if ($row == $value) {
				echo ' checked ';
			}
		}
	}
}

//CHECK IF MULTIDIMENSIONAL ARRAY ARE EMPTY
function checkIfArrayIsNotEmpty($array)
{
	$return = FALSE;
	if (is_array($array)) {
		foreach ($array as $value) {
			if (!empty($value)) {
				$return = TRUE;
			}
		}
	} elseif (!empty($array)) {
		$return = TRUE;
	}

	return $return;
}

//DATABASE SAVE DATE FORMAT
function saveDate()
{
	return date('Y-m-d');
}

//CONVERT ARRAY KEY
function convertArrayKey($array, $newKey)
{
	if (empty($array)) {
		return $array[$newKey];
	} else {
		foreach ($array as $key => $value) {
			$array[$newKey] = $array[$key];
			unset($array[$key]);
		}
		return $array;
	}
}

function recastArray($array = '')
{
	return (is_array($array) && count($array) == 1 ? $array[0] : $array);
}

//PRINT ARRAY NICELY
function pre($array)
{
	print "<pre>";
	print_r($array);
	print "</pre>";
}

//CHECK IF IMAGE VARIABLE IS NOT EMPTY AND IMAGE EXISTS
function displayImage($file, $folder)
{

	$extension = pathinfo(getFolderUrl() . '/' . $folder . '/' . $file, PATHINFO_EXTENSION);
	$authorize = array('jpg', 'jpeg', 'png');

	if (in_array($extension, $authorize) || empty($extension)) {
		if (file_exists(getDocumentRoot() . '/upload/' . $folder . '/' . $file) && !empty($file)) {
			$image = '/file/' . $folder . '/' . $file;
		} else {
			$image = '/public/img/no-photo.jpg';
		}
	} else {
		$image = '/public/img/no-photo-' . strtolower($extension) . '.jpg';
	}

	return $image;
}

//USER PHOTO : THUMBNAIL OR PROFILE PHOTO
function userPhoto($file, $folder, $gender)
{

	$extension = pathinfo(getFolderUrl() . '/' . $folder . '/' . $file, PATHINFO_EXTENSION);
	$authorize = array('jpg', 'jpeg', 'png');

	if (in_array($extension, $authorize) || empty($extension)) {
		if (file_exists(getDocumentRoot() . '/upload/' . $folder . '/' . thumbnailName($file)) && !empty($file)) {
			$image = '/file/' . $folder . '/' . thumbnailName($file);
		} elseif (!empty($gender)) {
			$image = '/public/img/default-profile-' . strtolower($gender) . '.jpeg';
		} else {
			$image = '/public/img/no-photo.jpg';
		}
	} else {
		$image = '/public/img/no-photo-' . strtolower($extension) . '.jpg';
	}

	return $image;
}

function thumbnailName($photo)
{
	if (!empty(htmlDecode($photo))) {
		$filename  = pathinfo($photo, PATHINFO_FILENAME);
		$extension = pathinfo($photo, PATHINFO_EXTENSION);
		$name 	   = htmlDecode($filename . '-thumb.' . $extension);
	} else {
		$name 	   = '';
	}
	return $name;
}

function thumbnailGenerate($photo, $destination, $resize_width)
{
	$type  = getimagesize($photo);
	$limit = 'no-photo';

	if (!strpos($photo, $limit)) {
		switch ($type['mime']) {
			case "image/png":
				$source_image = imagecreatefrompng($photo);
				break;

			case "image/jpg":
				$source_image = imagecreatefromjpeg($photo);
				break;
			case "image/jpeg":
				$source_image = imagecreatefromjpeg($photo);
				break;

			case "image/gif":
				$source_image = imagecreatefromgif($photo);
				break;
		}

		$width 			 = imagesx($source_image);
		$height 		 = imagesy($source_image);
		$resize_height 	 = floor($height * ($resize_width / $width));
		$virtual_image 	 = imagecreatetruecolor($resize_width, $resize_height);
		$whiteBackground = imagecolorallocate($virtual_image, 255, 255, 255);
		imagefill($virtual_image, 0, 0, $whiteBackground); // fill the background with white

		imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $resize_width, $resize_height, $width, $height);
		imagejpeg($virtual_image, $destination);
	}
}

//CHECK IF FILE IS EXISTS VIA URL
function fileUrl($photo, $path)
{
	if (!empty($photo) && @getimagesize(getSiteUrl() . $path . $photo)) {
		$image = getSiteUrl() . $path . $photo;
	} else {
		$image = getSiteUrl() . '/public/img/no-photo.jpg';
	}
	return $image;
}

//CHECK IF FILE IS EXISTS FROM DIRECTORY
function fileExist($file, $folder, $parameter = '')
{
	$path = '/public/img/file-not-found.jpg';

	if (!empty($file) && !empty($folder)) {
		if (file_exists(getDocumentRoot() . '/upload/' . $folder . '/' . $file) && !empty($file)) {
			$path = '/file/' . $folder . '/' . $file . $parameter;
		}
	}

	return $path;
}

//CHECK IF IMAGE VARIABLE IS NOT EMPTY AND IMAGE EXISTS IN DIRECTORY
function openImageInNewTab($photo, $path)
{
	if (!empty($photo) && getimagesize(getFolderUrl() . $path . $photo)) {
		$image = 'href="' . $path . $photo . '" target="_blank"';
	} else {
		$image = '';
	}
	return $image;
}

//CHECK IF FILE VARIABLE IS NOT EMPTY AND FILE EXISTS IN DIRECTORY
function openFileInNewTab($file, $path)
{
	if (!empty($file) && file_exists(getDocumentRoot() . '\upload\/' . $path . '\/' . $file)) {
		$link = 'href="/file/' . $path . '/' . $file . '" target="_blank"';
	} else {
		$link = '';
	}
	return $link;
}

//FLASH MESSAGE PROMPT
function promptMessage($name = '', $message = '', $class = 'success')
{

	if (!empty($name)) {

		if (!empty($message) && empty($_SESSION[$name])) {
			if (!empty($_SESSION[$name])) {
				unset($_SESSION[$name]);
			}
			if (!empty($_SESSION[$name . '_class'])) {
				unset($_SESSION[$name . '_class']);
			}

			$_SESSION[$name] = $message;
			$_SESSION[$name . '_class'] = $class;
		} elseif (!empty($_SESSION[$name]) && empty($message)) {
			$class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : 'success';

			if ($class == 'success') {
				$icon = '<i class="icon ion-ios-checkmark alert-icon tx-28 mg-t-5 mg-xs-t-0"></i>';
			} else {
				$icon = '<i class="icon ion-ios-close alert-icon tx-28"></i>';
			}

			echo '
						<div class="alert alert-' . $class . ' pd-10 flash alert-solid" role="alert">
							<div class="d-flex align-items-center justify-content-start">
								' . $icon . '
								<span>' . $_SESSION[$name] . '</span>
							</div>
						</div>
					 ';
		}
	}

	return $name;
}

//UNSET FLASH MESSAGE PROMPT AFTER DISPLAY
function flash($name)
{
	if (isset($_SESSION[$name])) {
		unset($_SESSION[$name]);
		unset($_SESSION[$name . '_class']);
	}
}

//DISPLAY FLASH MESSAGE PROMPT
function flashMessage()
{
	$message = '
					<div class="col-lg-12">
						' . flash(promptMessage('message')) . '
					</div>
				   ';

	return $message;
}

//DISPLAY UPDATE OR NEW PAGE TITLE
function managePageTitle($id = 'id')
{
	echo '<b class="tx-primary">[ ' . (!empty(getVar($id)) ? 'Update' : 'Create') . ' ]</b>';
}

//CONVERT NEGATIVE VALUE TO POSITIVE ZERO
function convertNegativeToZero($value)
{
	return ((htmlEncode($value) != 0 && htmlEncode($value) != NULL) ? max(htmlEncode($value), 0) : 0);
}

//DATE FORMAT : BOX STYLE DISPLAY
function dateDisplayBox($date)
{
	if (!empty($date)) {
		return date('M d Y', strtotime(htmlEncode($date)));
	} else {
		return 'No date available';
	}
}

//DATE FORMAT : DATABASE RETURN
function formatDateReturn($date)
{

	$strtotime = strtotime($date);
	if (empty($strtotime)) {
		return '';
	} else {
		return date("m/d/Y", strtotime(htmlEncode($date)));
	}
}

//YEAR & MONTH FORMAT FOR TEMPORARY CODE
function dateYearMonthCode($date)
{
	return date("ym", strtotime(htmlEncode($date)));
}

//YEAR & MONTH FORMAT
function dateYearMonth($date)
{
	return date("Y-m", strtotime(htmlEncode($date)));
}

//DATE & TIME FORMAT : DATABASE
function formatDateTimeDB($date)
{
	return date("Y-m-d H:i:s", strtotime(htmlEncode($date)));
}

//DATE FORMAT : NUMERIC DISPLAY
function dateNumericDisplay($date)
{
	return date('Y/m/d', strtotime(htmlEncode($date)));
}

//DATE FORMAT : PROPER NUMERIC DISPLAY
function dateProperNumeric($date)
{
	if (!empty($date)) {
		return date('m/d/Y', strtotime(htmlEncode($date)));
	}
}

//DATE FORMAT : IN DETAILS DISPLAY
function dateDetail($date)
{
	return date('D, d <br> M.Y', strtotime(htmlEncode($date)));
}

//DATE FORMAT : AS OF 
function dateAsOf($date)
{
	return date('d F Y', strtotime(htmlEncode($date)));
}

//DATE & TIME FORMAT : REPORT
function reportDateTime($date)
{
	return date("m-d-Y H:i", strtotime(htmlEncode($date)));
}

//ADD ZERO
function formatNumber($value, $number = 4)
{
	return str_pad($value, $number, "0", STR_PAD_LEFT);
}

//CHECK MINIMUM AND MAXIMUM
function minMax($minimum, $maximum)
{
	if ($maximum < $minimum) {
		return "Maximum is less than to minimum ($minimum minimum)";
	}
	return TRUE;
}

function alertAndRedirect($message, $location)
{
	$alert = "<script>
	    		  	alert('" . $message . "'); 
	    		  	window.location.href='" . $location . "';
	    		  </script>";
	echo $alert;
}

function forbiddenRedirect()
{
	$alert = "<script>
	    		  	alert('Access to this resource on the server is denied'); 
	    		  	window.location.href='/';
	    		  </script>";
	echo $alert;
}

function refreshPage()
{
	return '<meta http-equiv="refresh" content="0">';
}

function redirectPage($location)
{
	return '<meta http-equiv="refresh" content="0" url="' . $location . '">';
}

function checkboxCheck($field)
{
	$checked = (!empty($field) ? ' checked ' : '');
	echo $checked;
}

function checkboxSet($field, $value = '')
{
	$result = '';
	/*
		if(isset(htmlEncode($field)) && !empty(htmlEncode($field))){
			if(empty($value)){
				$result = htmlEncode($field);
			}else{
				$result = $value;
			}
		}else{
			$result = '';
		}
		*/
	echo $result;
}

function ifTheSame($field, $value)
{
	$same = (($field == $value) ? ' checked ' : '');
	echo $same;
}

function optionSelected($field, $value)
{
	$same = (($field == $value) ? ' selected ' : '');
	echo $same;
}

function blockMessage($message, $error = '')
{
	$display = '<div class="message-' . $error . '"><p>' . $message . '</p></div>';
	return $display;
}

function successMessage($message)
{
	$display = '<div class="alert alert-success">' . $message . '</div>';
	return $display;
}

function errorMessage($message)
{
	$display = '<div class="alert alert-danger">' . $message . '</div>';
	return $display;
}

function requiredPrompt($message)
{
	$display = '<p class="required-prompt">*' . $message . '</p>';
	return $display;
}

function successPrompt($message)
{
	$display = '<p class="success-prompt">' . $message . '</p>';
	return $display;
}

//GENERATE RANDOM CODE
function generateRandomCode($length = 6)
{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$string 	= '';
	for ($p = 0; $p < $length; $p++) {
		$string .= $characters[mt_rand(0, strlen($characters) - 1)];
	}

	return $string;
}

function generateButton($name, $icon, $link, $class = '')
{
	$button = '
	                <a href="' . $link . '" class="btn btn-primary btn-list ' . $class . '">
                        <i class="fa ' . $icon . ' fa-lg"></i>
                  ';
	if (!empty($name)) {
		$button .= '
                        &nbsp; <small>' . $name . '</small>
                  ';
	}
	$button .= '
	                </a>
				  ';

	echo $button;
}

function buttonModal($name, $icon, $modal, $class = '')
{
	$button = '
                    <button class="btn btn-primary btn-list ' . $class . '" data-toggle="modal" data-target="#' . $modal . '">
                        <i class="fa ' . $icon . ' fa-lg"></i>
                  ';
	if (!empty($name)) {
		$button .= '
                        &nbsp; <small>' . $name . '</small>
                  ';
	}
	$button .= '
                    </button>
				  ';

	echo $button;
}

function cURLApi($url)
{
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL             => $url,
		CURLOPT_RETURNTRANSFER  => true,
		CURLOPT_ENCODING        => "",
		CURLOPT_MAXREDIRS       => 10,
		CURLOPT_TIMEOUT         => 0,
		CURLOPT_FOLLOWLOCATION  => false,
		CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST   => "GET"
	));

	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

	$response = curl_exec($curl);
	$err      = curl_error($curl);

	curl_close($curl);

	if ($err) {
		return "cURL Claim Error #:" . $err;
	} else {
		return json_decode($response, true);
	}
}

function percentAmount($amount, $percent)
{
	if ($amount != '' || $amount != 0 && $percent != '' || $percent != 0) {
		$result = ($percent / 100) * $amount;
	} else {
		$result = '0.00';
	}
	return $result;
}

function percentTotal($total, $portion, $set = '')
{
	if ($portion != '' || $portion != 0 && $total != '' || $total != 0) {
		if ($set == 'Full') {
			$result = ($portion / $total) * 100;
		} else {
			$result = ($portion >= $total ? 100 : ($portion / $total) * 100);
		}

		$result = (is_float($result) ? bcdiv(str_replace(',', '', $result), 1, 2) : $result);
	} else {
		$result = NULL;
	}

	return $result;
}

function percentageToDecimal($value)
{
	return moneyClean($value) / 100;
}

function percentageToNumber($value)
{
	return ($value / 100) * 100;
}

function percentageToAmount($amount, $percent)
{
	$result = array();
	$value  = '0.00';

	if ($percent == null || $percent == 'null' || $percent == '' || $percent == '0' || $percent == '0.00' || $amount == null || $amount == 'null' || $amount == '' || $amount == '0' || $amount == '0.00') {
		$result['percent'] = $value;
		$result['total']   = $value;
	} else {
		$percentage    		= percentageToDecimal($percent);
		$price         		= moneyClean($amount);
		$total_gst     		= moneyClean($price * $percentage);
		$total_amount  		= moneyClean($price + $total_gst);

		$result['percent'] 	= formatMoney(is_nan($total_gst) || $total_gst == 0 ? $value : $total_gst);
		$result['total']   	= formatMoney(is_nan($total_amount) || $total_amount == 0 ? $value : $total_amount);
	}

	return $result;
}

function percentageToUnitPrice($amount, $percent)
{
	$result = array();
	$value  = '0.00';

	if ($percent == null || $percent == 'null' || $percent == '' || $percent == '0' || $percent == '0.00' || $amount == null || $amount == 'null' || $amount == '' || $amount == '0' || $amount == '0.00') {
		$result['percent']  = $value;
		$result['total']    = $value;
	} else {
		$percentage    		= moneyClean(percentageToDecimal($percent) + 1);
		$total_gst     		= moneyClean($amount / $percentage);
		$total_amount  		= moneyClean($amount - $total_gst);

		$result['percent'] 	= formatMoney(is_nan($total_amount) || $total_amount == 0 ? $value : $total_amount);
		$result['total']   	= formatMoney(is_nan($total_gst) || $total_gst == 0 ? $value : $total_gst);
	}

	return $result;
}

//GENERATE RANDOM CODE
function generateRandomNumber($length = 6)
{
	$characters = '0123456789';
	$string 	= '';
	for ($p = 0; $p < $length; $p++) {
		$string .= $characters[mt_rand(0, strlen($characters) - 1)];
	}

	return $string;
}

function fieldChecker($value, $check)
{
	$iserror = false;
	switch ($check) {
		case 'not_empty':
			if (strlen(trim($value)) <= 0) $iserror = true;
			break;
		case 'string':
			if (!ctype_alnum($value)) $errmsg = true;
			break;
		case 'digit':
			if (!ctype_digit($value)) $iserror = true;
			break;
		case 'email':
			if (strlen(trim($value)) <= 0 || !eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $value)) $iserror = true;
			break;
		case 'file-image':
			$filetype = array('image/jpeg', 'image/pjpeg', 'image/jpg', 'image/gif', 'image/png');
			if (!in_array($_FILES[$value]['type'], $filetype)) $iserror = true;
			break;
		case 'file-alltype':
			$element = $value;
			if (!empty($_FILES[$element]['name'])) {
				if ($_FILES[$element]['size'] < $GLOBALS[file_max_limit]) {
					if ($_FILES[$element]['error'] == 0) {
						$pinfo = pathinfo(basename($_FILES[$element]['name']));
						if (in_array($pinfo[extension], $GLOBALS[file_valid_extensions])) {
							$errmsg = 0;
						} else {
							$errmsg = 'File type not supported. Types: [*.flv | *.mpg]';
						}
					} else {
						$errmsg = 'Error uploading file';
					}
				} else {
					$errmsg = 'File too large. Max Limit[' . $GLOBALS[file_max_limit] . ']';
				}
			} else {
				$errmsg = 'File not set';
			}
			break;
		case 'date':
			list($yr, $mo, $dy) = explode('-', $value);
			$this_month = getDate(mktime(0, 0, 0, $mo, 1, $yr));
			$next_month = getDate(mktime(0, 0, 0, $mo + 1, 1, $yr));
			$days_in_this_month = round(($next_month[0] - $this_month[0]) / (60 * 60 * 24));
			if ($dy > $days_in_this_month) $errmsg = "<span class='err'>* Inputted Date is Invalid</span>";
			else $errmsg = 0;
			break;
		case 'password':
			if (strlen(trim($value)) <= 0) {
				$iserror = true;
			} elseif (strlen($value) < 6) {
				$iserror = true;
			} else {
				$iserror = false;
			}

			break;
		case 'pass_confirm':
			list($password_one, $password_two) = $value;
			if ($password_one !== $password_two) $iserror = true;
			break;
		case 'do_nothing':
			$errmsg = 0;
			break;
		default:
			$errmsg = 0;
			break;
	}
	return ($iserror);
}

function imageResize($width, $height, $target)
{
	if ($width > $height) {
		$percentage = ($target / $width);
	} else {
		$percentage = ($target / $height);
	}

	$width  = round($width * $percentage);
	$height = round($height * $percentage);

	return array($width, $height);
}

function passwordValidation($field1, $field2)
{

	if (empty($field1)) {
		$error = "You didn\'t enter a password";
	} elseif (strlen($field1) < 7 || strlen($field1) > 15) {
		$error = "Incorrect Length for Password";
	} elseif (ctype_alnum($field1) != true) {
		$error = "Password contains illegal characters";
	} elseif (!preg_match("/^.*(?=.*[0-9])(?=.*[a-zA-Z]).*$/", $field1)) {
		$error = "Password must be alpha numeric";
	} elseif ($field1 != $field2) {
		$error = "Password Re-Type mis-Match";
	} else {
		return true;
	}

	if ($error != "") {
		echo "<script type='text/javascript'> 
					alert('ERROR: " . $error . "'); 							
				  </script>";
		return false;
	}
}

function validateDate($date, $format = 'YYYY-MM-DD')
{
	switch ($format) {
		case 'YYYY/MM/DD':
		case 'YYYY-MM-DD':
			list($y, $m, $d) = preg_split('/[-\.\/ ]/', $date);
			break;

		case 'YYYY/DD/MM':
		case 'YYYY-DD-MM':
			list($y, $d, $m) = preg_split('/[-\.\/ ]/', $date);
			break;

		case 'DD-MM-YYYY':
		case 'DD/MM/YYYY':
			list($d, $m, $y) = preg_split('/[-\.\/ ]/', $date);
			break;

		case 'MM-DD-YYYY':
		case 'MM/DD/YYYY':
			list($m, $d, $y) = preg_split('/[-\.\/ ]/', $date);
			break;

		case 'YYYYMMDD':
			$y = substr($date, 0, 4);
			$m = substr($date, 4, 2);
			$d = substr($date, 6, 2);
			break;

		case 'YYYYDDMM':
			$y = substr($date, 0, 4);
			$d = substr($date, 4, 2);
			$m = substr($date, 6, 2);
			break;

		default:
			throw new Exception("Invalid Date Format");
	}
	return checkdate($m, $d, $y);
}

function incrementLetter($value, $increment)
{
	for ($i = 1; $i <= $increment; $i++) {
		$value++;
	}

	return $value;
}

function decrementLetter($value, $decrement)
{
	for ($i = 1; $i <= $decrement; $i++) {
		$value--;
	}

	return $value;
}

function cURLApiRestGET($url, $username, $password)
{
	$curl = curl_init();
	curl_setopt_array(
		$curl,
		array(
			CURLOPT_URL             => $url,
			CURLOPT_RETURNTRANSFER  => true,
			CURLOPT_SSL_VERIFYHOST  => false,
			CURLOPT_SSL_VERIFYPEER  => false,
			CURLOPT_ENCODING        => '',
			CURLOPT_MAXREDIRS       => 10,
			CURLOPT_TIMEOUT         => 120,
			CURLOPT_FOLLOWLOCATION  => true,
			CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST   => 'GET',
			CURLOPT_HTTPHEADER      => array(
				'Authorization: Basic ' . base64_encode($username . ':' . $password)
			),
		)
	);

	$response = curl_exec($curl);
	$err      = curl_error($curl);

	curl_close($curl);

	if ($err) {
		return "cURL API Error #:" . $err;
	} else {
		return json_decode($response, true);
	}
}

function cURLApiRestPostFile($url, $fields = array())
{
	$CONFIGURATION  = Configuration::general();
	$API_URL        = $CONFIGURATION['API_URL'];

	$fields = array_merge($fields, array("PRIVATE_USERNAME" => $CONFIGURATION['PRIVATE_USERNAME'], "PRIVATE_PASSWORD" => $CONFIGURATION['PRIVATE_PASSWORD']));

	// MOVE THE UPLOADED FILE TO WEBSITE DIRECTORY
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($process, CURLOPT_SSL_VERIFYPEER, false);

	$response = curl_exec($curl);
	$err      = curl_error($curl);

	curl_close($curl);

	if ($err) {
		return "cURL API Error #:" . $err;
	} else {
		return json_decode($response, true);
	}
}

// REMOVE ALL FILES FROM DIR AND THE FOLDER ITSELF
function rrmdir($dir)
{
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($dir . "/" . $object) == "dir")
					rrmdir($dir . "/" . $object);
				else unlink($dir . "/" . $object);
			}
		}
		reset($objects);
		rmdir($dir);
	}
}

function modifyStringSpecialCharacter($string)
{
	$string = str_ireplace("'",  "&apos;", $string);
	$string = str_ireplace("\\", "&bsol;", $string);
	$string = str_ireplace('"',  "&quot;", $string);

	return $string;
}

//LOGIN REMEMBER ME COOKIES
function checkRememberMeCookieToken()
{
	includeModel('Account');
	if (isset($_COOKIE["member"]) && !empty($_COOKIE["member"])) {
		$cookie = unserialize(safe_b64decode($_COOKIE["member"]));

		$expiration = $cookie['expiration'];
		$username   = $cookie['username'];
		if ($expiration >= time()) {
			$record = recastArray(Account::getRecordByEmail($cookie['username']));
			if (!empty($record) && isset($record['remember_me']) && !empty($record['remember_me']) && $username == $record['email'] && $expiration == $record['remember_me']) {
				$_SESSION['login_id'] = idEncrypt($record['id']);
				header('location: ' . (!empty(getVar('redirect')) ? safe_b64decode(getVar('redirect')) : '/'));
			} else {
				setcookie("member", "", 1);
			}
		} else {
			setcookie("member", "", 1);
		}
	}
}

//LOG IP ADDRESS
function getUserIpAddress()
{
	$ipAddress = '';

	if (!empty($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'] != '::1') {
		//ip from share internet
		$ipAddress = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		//ip pass from proxy
		$ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ipAddress = $_SERVER['REMOTE_ADDR'];
	}

	return $ipAddress;
}

function logs($value, $name = 'log')
{
	$logs = '';
	if (!empty($value)) {
		if (is_array($value)) {
			$logs = print_r($value, TRUE);
		} else {
			$logs = $value;
		}
	}

	return file_put_contents('./logs/' . $name . '-' . dateTimeAsId() . '.txt', $logs . PHP_EOL, FILE_APPEND | LOCK_EX);
}

function fullName($first_name, $last_name, $middle_name = '')
{
	$full_name = $first_name . ' ' . $middle_name . ' ' . $last_name;

	return $full_name;
}

function reIndexMultiArray($array, $array_key)
{
	if (is_array($array)) {
		foreach ($array as $key => $val) {

			$column = array_column($array[$key], $array_key);
			array_multisort($column, SORT_ASC, $array[$key]);
		}

		return $array;
	}
}

function getAllDatesInDateRange($date_start, $date_end)
{
	$dates              = [];
	$currentDate        = new DateTime($date_start);
	$endDate            = new DateTime($date_end);

	while ($currentDate <= $endDate) {
		$dates[] = $currentDate->format('Y-m-d');
		$currentDate->modify('+1 day');
	}

	return $dates;
}

function serverCurrent()
{
	$CONFIGURATION  = Configuration::general();

	if (SYSTEM_ENVIRONMENT == PRODUCTION) {
		$value = '';
	} elseif (SYSTEM_ENVIRONMENT == STAGING) {
		$value = strtoupper(STAGING) . ' SERVER';
	} else {
		$value = strtoupper(DEVELOPMENT) . ' SERVER';
	}

	return $value;
}

function restrictedViewPerDayAndTime($day = '', $time = '')
{
	//$day  = date('w'); // 1,2,3,4,5 Weekdays Only
	//$time = date('Hi'); // 2pm to 4pm Only

	if (!empty($day) && !empty($time) && (in_array($day, array(1, 2, 3, 4, 5))) && ($time >= 1400 && $time <= 1600)) {
		$value = 'Yes'; //Allow
	} else {
		$value = 'No'; //Restricted
	}

	return $value;
}

function getUniqueValuesBasedOnMultidimensionalArrayKey($array, $key)
{
	$uniqueKey = [];
	$uniqueArray = [];

	foreach ($array as $item) {
		$unique_value = $item[$key];
		if (!in_array($unique_value, $uniqueKey)) {
			$uniqueKey[] = $unique_value;
			$uniqueArray[] = $item;
		}
	}
	return $uniqueArray;
}

function explodeByDate($inputString)
{
	// Define an array to hold the results
	$resultArray = [];

	// Define the regex patterns for various date formats
	$patterns = [
		'\d{2}\/\d{2}\/\d{4}',            // DD/MM/YYYY
		'\d{4}-\d{2}-\d{2}',              // YYYY-MM-DD
		'\d{2}-\d{2}-\d{4}',              // MM-DD-YYYY
		'[A-Za-z]+ \d{1,2}, \d{4}'        // Month DD, YYYY
	];

	// Combine patterns into a single pattern with '|' (OR) operator
	$combinedPattern = '/(' . implode('|', $patterns) . '):/';

	// Split the string based on the dates using the combined pattern
	$splitStrings = preg_split($combinedPattern, $inputString, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

	// Iterate through the split strings to build the result array
	for ($i = 0; $i < count($splitStrings); $i += 2) {
		$date = $splitStrings[$i];
		$remark = isset($splitStrings[$i + 1]) ? $splitStrings[$i + 1] : '';
		$resultArray[] = ['date' => $date, 'remark' => trim($remark)];
	}

	return $resultArray;
}

//DATE FORMAT : DATABASE FORMAT
function dateTimeSaveDB($date)
{
	if (empty($date)) {
		return '';
	} else {
		return date('Y-m-d H:i:s', strtotime(htmlEncode($date)));
	}
}

function convertToDateFormat($dateString)
{
	// Array of date formats to try
	$formats = [
		'd/m/Y',
		'd-m-Y',
		'm/d/Y',
		'm-d-Y',
		'Y/m/d',
		'Y-m-d',
		'd.m.Y',
		'm.d.Y',
		'Y.m.d',
		'd M Y',
		'M d, Y',
		'Y M d',
	];

	// Try each format
	foreach ($formats as $format) {
		$date = DateTime::createFromFormat($format, $dateString);

		// If valid date is found, return it in 'Y-m-d' format
		if ($date !== false && $date->format($format) === $dateString) {
			return $date->format('Y-m-d');
		}
	}

	// If no valid date format is found, return an error message
	return "Invalid";
}

function getIdsInMultidimentionalArray($array, $key = 'id')
{
	$return = [];

	foreach ($array as $value) {
		if (isset($value[$key]) && !empty($value[$key])) {
			$return[] = $value[$key];
		}
	}

	return $return;
}

function findRecordByKeyAndValue($array, $key, $value)
{
	foreach ($array as $record) {
		if (isset($record[$key]) && $record[$key] == $value) {
			return $record;
		}
	}
	return null; // Return null if no matching record is found
}

function pickRandomizedAccountId($account_ids)
{
	$return = '';
	if (is_array($account_ids)) {
		shuffle($account_ids);
		$random_keys = array_rand($account_ids, 2);
		$return = $account_ids[$random_keys[array_rand($random_keys)]];
	}
	return $return;
}

function addWorkingDays($startDate, $daysToAdd)
{
	$currentDate = date('Y-m-d', strtotime($startDate));
	$workingDaysAdded = 0;

	while ($workingDaysAdded < $daysToAdd) {
		// Add one day to the current date
		$currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));

		// Check if the new date is a weekday (Monday to Friday)
		$dayOfWeek = date('N', strtotime($currentDate)); // N gives the day number (1 = Monday, 7 = Sunday)

		if ($dayOfWeek < 6) { // If it's a weekday (Monday to Friday)
			$workingDaysAdded++;
		}
	}

	return $currentDate;
}

function removeValueFromArray($array, $value_to_remove)
{
	$return = '';
	if (is_array($array) && count($array) > 1) {
		$new_array = [];
		foreach ($array as $key => $value) {
			if ($value_to_remove != $value) {
				$new_array[] = $value;
			}
		}
		$return = $new_array;
	} else {
		$return = $array;
	}
	return $return;
}

function getUrlBeforeQuery($url)
{
	// Parse the URL and retrieve its components
	$parsed_url = parse_url($url);

	// If the URL contains a query part (i.e., has a '?'), return an empty string
	if (isset($parsed_url['query'])) {
		return ''; // Return empty string if there's a query string
	}

	// Initialize the result URL
	$url_before_query = '';

	// Check if the scheme exists (e.g., http or https)
	if (isset($parsed_url['scheme'])) {
		$url_before_query .= $parsed_url['scheme'] . '://';
	}

	// Check if the host exists (e.g., www.example.com)
	if (isset($parsed_url['host'])) {
		$url_before_query .= $parsed_url['host'];
	}

	// Check if a port exists and append it if necessary
	if (isset($parsed_url['port'])) {
		$url_before_query .= ':' . $parsed_url['port'];
	}

	// Check if the path exists and append it
	if (isset($parsed_url['path'])) {
		$url_before_query .= $parsed_url['path'];
	}

	return $url_before_query; // Return the full URL without the query string if no query is present
}

function getQueryString($url)
{
	// Parse the URL and retrieve the query string
	$parsed_url = parse_url($url);

	// Check if the query part exists in the URL
	if (isset($parsed_url['query'])) {
		// Return the query string after the '?'
		return $parsed_url['query'];
	} else {
		return null; // No query string found
	}
}

function parsePrefixString($prefix)
{
	// Check if the prefix contains a hyphen (-), indicating a range
	if (strpos($prefix, '-') !== false) {
		// Split the string into two parts using the hyphen as the delimiter
		[$start, $end] = explode('-', $prefix);

		// Extract year and month for start and end values
		$startYear = (int)substr($start, 0, 2);
		$startMonth = (int)substr($start, 2, 2);
		$endYear = (int)substr($end, 0, 2);
		$endMonth = (int)substr($end, 2, 2);

		$result = [];

		// Loop through years and months
		for ($year = $startYear; $year <= $endYear; $year++) {
			$monthStart = ($year === $startYear) ? $startMonth : 1;
			$monthEnd = ($year === $endYear) ? $endMonth : 12;

			for ($month = $monthStart; $month <= $monthEnd; $month++) {
				$result[] = (int)(sprintf('%02d%02d', $year, $month));
			}
		}

		return $result;
	}

	// If no hyphen, treat it as a single value and return an array with that value
	return [(int)$prefix];
}

function extractUrlParameters($url)
{
	// Parse the URL
	$parsedUrl = parse_url($url);

	// Check if the URL has a query string
	if (isset($parsedUrl['query']) && !empty($parsedUrl['query'])) {
		return htmlDecode($parsedUrl['query']);
	}

	// If no query string, return the original URL
	return '';
}
