<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once("/etc/apache2/capstone-mysql/Secrets.php");
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once dirname(__DIR__, 3) . "/php/lib/jwt.php";

use DeepDiveDatingApp\DeepDiveDating\{
	Report,
	User
};

/**
 * API for the Report Class
 *
 * @author Taylor Smith
 */

if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	$secrets = new \Secrets("/etc/apache2/capstone-mysql/ddctwitter.ini");
	$pdo = $secrets->getPdoObject();
	//determine which HTTP method was used
	$method = $_SERVER["HTTP_X_HTTP_METHOD"] ?? $_SERVER["REQUEST_METHOD"];
	//sanitize input
	$userId = filter_input(INPUT_GET, "userId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$abuserId = filter_input(INPUT_GET, "abuserId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$reportContent = filter_input(INPUT_GET, "reportContent", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	//make sure the ids are valid
	if(($method === "PUT") && ((empty($userId) === true) || (empty($abuserId) === true))) {
		throw(new InvalidArgumentException("id cannot be empty or negative", 402));
	}




} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}
// encode and return reply to front end caller
header("Content-type: application/json");
echo json_encode($reply);