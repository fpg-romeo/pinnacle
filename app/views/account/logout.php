<?php
	/* Initialize the session. */
	/* If you are using session_name("something"), don't forget it now! */
	/* session_start(); */

	/* Unset all of the session variables. */

	if(!isset($_SESSION)){ 
		session_start();
		$_SESSION = array();
	} 

	/* If it's desired to kill the session, also delete the session cookie. */
	/* Note: This will destroy the session, and not just the session data! */

	// if (ini_get("session.use_cookies")) {
	//     $params = session_get_cookie_params();
	//     setcookie(session_name(), '', time() - 42000,
	//         $params["path"], $params["domain"],
	//         $params["secure"], $params["httponly"]
	//     );

	// 	// Finally, destroy the session.
	// 	session_unset();
	// 	session_destroy();
	// }

	sessionDestroy();

	header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");

	header('Location: /login');  
	die();
?>