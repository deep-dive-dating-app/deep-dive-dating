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
		throw(new InvalidArgumentException("id cannot be empty or negative", 405));
	}

	if($method === "GET") {
		setXsrfCookie();

		if(empty($userId) === false) {
			$reply->data = Report::getReportByReportUserId($pdo, $userId)->toArray();
		} else if(empty($abuserId) === false) {
			$reply->data = Report::getReportByReportAbuserId($pdo, $abuserId)->toArray();
		} else if(empty($userId && $abuserId)) {
			$reply->data = Report::getReportByReportUserIdAndReportAbuserId($pdo, $userId, $abuserId);
		}

	} else if($method === "PUT" || $method == "POST") {
		verifyXsrf();

		if(empty($_SESSION["user"]) === true) {
			throw(new \InvalidArgumentException("you must be logged in to report a user", 401));
		}

		$requestContent = file_get_contents("php://input");
		// Retrieves the JSON package that the front end sent, and stores it in $requestContent. Here we are using file_get_contents("php://input") to get the request from the front end. file_get_contents() is a PHP function that reads a file into a string. The argument for the function, here, is "php://input". This is a read only stream that allows raw data to be read from the front end request which is, in this case, a JSON package.
		$requestObject = json_decode($requestContent);
		// This Line Then decodes the JSON package and stores that result in $requestObject

		if(empty($requestObject->reportAgent) === true) {
			throw(new \InvalidArgumentException("Report must have Agent info"));
		}

		if(empty($requestObject->reportContent) === true) {
			throw(new \InvalidArgumentException("Report must have content"));
		}

		if(empty($requestObject->reportDate) === true) {
			$requestObject->reportDate = null;
		}

		if(empty($requestObject->reportIp) === true) {
			throw(new \InvalidArgumentException("Report must have an IP"));
		}

		if($method === "PUT") {
			$report = Report::getReportByReportUserId($pdo, $userId);
			if($report === null) {
				throw(new RuntimeException("Report does not exist"));
			}

			//enforce the end user has a JWT token
			validateJwtHeader();

			//enforce the user is signed in and trying to edit their report
			if(empty($_SESSION["user"]) === true || $_SESSION["user"]->getUserId()->toString() !== $report->getReportUserId()->toString()) {
				throw(new \InvalidArgumentException("You are not allowed to edit this report", 403));
			}

			// update all attributes
			$report->setReportContent($requestObject->reportContent);
			$report->update($pdo);
			// update reply
			$reply->message = "Report has been updated";

		} else if($method === "POST") {

			// enforce the user is signed in
			if(empty($_SESSION["user"]) === true) {
				throw(new \InvalidArgumentException("you must be logged in to make a report", 403));
			}
			//enforce the end user has a JWT token
			validateJwtHeader();
			// create new tweet and insert into the database
			$report = new Report($_SESSION["user"]->getProfileId(), $requestObject->abuserId, $requestObject->reportAgent, $requestObject->reportContent, null, $requestObject->reportIp);
			$report->insert($pdo);
			// update reply
			$reply->message = "Report has been made";
		}
	}

} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}
// encode and return reply to front end caller
header("Content-type: application/json");
echo json_encode($reply);