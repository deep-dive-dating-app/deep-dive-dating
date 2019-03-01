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
			throw(new \InvalidArgumentException("User Avatar Picture is Empty", 400));
		}
		// possibly set url to accept null value and or use default icon
		if(empty($requestObject->userEmail) == true) {
			throw(new \InvalidArgumentException("User Email is Empty", 400));
		}
		if(empty($requestObject->userHandle) === true) {
			throw(new \InvalidArgumentException("User Handle is Empty", 400));
		}

		if(empty($requestObject->userPassword) === true) {
			throw(new \InvalidArgumentException("User Password is Empty", 400));
		}
		if(empty($requestObject->userPasswordConfirm) === true) {
			throw(new \InvalidArgumentException ("Passwords Do Not Match", 400));
		}
		//confirm that passwords match
		if($requestObject->userPassword !== $requestObject->userPasswordConfirm) {
			throw(new \InvalidArgumentException("Passwords Do Not Match", 400));
		}

		if(empty($requestObject->userDetailAboutMe) === true) {
			$requestObject->userDetailAboutMe = null;
		}
		if(empty($requestObject->userDetailAge) === true) {
			throw(new \InvalidArgumentException("Please, select your age."));
		}
		if(empty($requestObject->userDetailCareer) === true) {
			throw(new \InvalidArgumentException("Please enter your Career."));
		}
		if(empty($requestObject->userDeatilDisplayEmail) === true) {
			throw(new \InvalidArgumentException("Please select a display email."));
		}
		if(empty($requestObject->userDeatilEducation) === true) {
			throw(new \InvalidArgumentException("Please enter your education."));
		}
		if(empty($requestObject->userDeatilGender) === true) {
			throw(new \InvalidArgumentException("Please enter your gender."));
		}
		//why do we have Interests? is it like the about me or do we have a specific question?
		if(empty($requestObject->userDetailInterests) === true) {
			$requestObject->userDetailInterests = null;
		}
		if(empty($requestObject->userDetailRace) === true) {
			throw(new \InvalidArgumentException("Please enter your race."));
		}
		if(empty($requestObject->userDetailReligion) === true) {
			throw(new \InvalidArgumentException("Please enter your religion."));
		}

		//do the values below  get assigned on sign up or after activation?
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		$userIpAddress =  $_SERVER['SERVER_ADDR'];
		$userDetailId = generateUuidV4();
		//user detail id cant be set until user is made
		$userDetailUserId = null;
		//user blocked? using 0 as default
		$userBlocked = 0;

		$userHash = password_hash($requestObject->userPassword, PASSWORD_ARGON2I, ["time_cost" => 384]);
		$userActivationToken = bin2hex(random_bytes(16));
		$userId = generateUuidV4();

		//create user object
		$user = new User($userId, $userActivationToken, $requestObject->userAgent, $requestObject->userAvatarUrl, $userBlocked, $requestObject->userEmail, $requestObject->userHandle, $userHash, $requestObject->userIpAddress);
		$user->insert($pdo);

	}
} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}

// encode and return reply to front end caller
header("Content-type: application/json");
echo json_encode($reply);