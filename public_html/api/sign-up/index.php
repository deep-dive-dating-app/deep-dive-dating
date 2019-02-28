<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once("/etc/apache2/capstone-mysql/Secrets.php");

use DeepDiveDatingApp\DeepDiveDating\User;

/**
 * API for signing up for Blame Dan Date Dan
 *
 * @author Taylor Smith
 **/
//verify session, if none start new session
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {
	//grab the MySQL connection
	$secrets = new \Secrets("/etc/apache2/capstone-mysql/Secrets.php");
	$pdo = $secrets->getPdoObject();
	//determine which of the HTTP methods was used
	$method = $_SERVER["HTTP_X_HTTP_METHOD"] ?? $_SERVER["REQUEST_METHOD"];

	if($method === "POST") {
		verifyXsrf();
		//get jSon package in string form then decode it
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);
		//assert that all sign in fields are valid
		if(empty($requestObject->userAvatarUrl) === true) {
			throw(new \InvalidArgumentException("User Avatar Picture is Empty", 405));
		}
		if(empty($requestObject->userEmail) == true) {
			throw(new \InvalidArgumentException("User Email is Empty", 405));
		}
		if(empty($requestObject->userHandle) === true) {
			throw(new \InvalidArgumentException("User Handle is Empty", 405));
		}

		if(empty($requestObject->userPassword) === true) {
			throw(new \InvalidArgumentException("User Password is Empty", 405));
		}
		if(empty($requestObject->userPasswordConfirm) === true) {
			throw(new \InvalidArgumentException ("Passwords Do Not Match", 405));
		}
		//confirm that passwords match
		if ($requestObject->userPassword !== $requestObject->userPasswordConfirm) {
			throw(new \InvalidArgumentException("Passwords Do Not Match"));
		}

		$userHash = password_hash($requestObject->userPassword, PASSWORD_ARGON2I, ["time_cost" => 384]);
		$userActivationToken = bin2hex(random_bytes(16));
		$userId = generateUuidV4();

		//create user object
		$user = new User($userId, $userActivationToken, $requestObject->userAgent, $requestObject->userAvatarUrl, $requestObject->userBlocked, $requestObject->userEmail, $requestObject->userHandle, $userHash, $requestObject->userIpAddress);
		$user->insert($pdo);

	}
}