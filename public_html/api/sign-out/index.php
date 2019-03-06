<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/Classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once("/etc/apache2/capstone-mysql/Secrets.php");

use DeepDiveDatingApp\DeepDiveDating\User;

/*
 * api for signing out of deep dive dating account
 *
 * @author Kathleen Mattos <kmattos1@cnm.edu>
 */

//verify the xsrf challenge
if(session_status() !== PHP_SESSION_ACTIVE){
	session_start();
}

//prepare the default error message
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {
	//grab the mySQL connection
	$secrets = new \Secrets("");  // todo tell me secrets
	$pdo = $secrets->getPdoObject();
	//determine what method is used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["REQUEST_METHOD"] : $_SERVER["REQUEST_METHOD"];
	if($method === "GET") {
		$_SESSION =[];
		$reply->message = "You are Now signed out.";
	}
	else {
			throw (new \InvalidArgumentException("Invalid HTTP method request."));
	}
} catch(Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
} catch(TypeError $typeError) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}
header("Content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}

//encode and return reply to front end caller
echo json_encode($reply);
