<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/Classes/autoload.php";
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
	$secrets = new \Secrets("/etc/apache2/capstone-mysql/cohort23/dateadan.ini");
	$pdo = $secrets->getPdoObject();
	//determine which HTTP method was used
	$method = $_SERVER["HTTP_X_HTTP_METHOD"] ?? $_SERVER["REQUEST_METHOD"];
	//sanitize input
	$reportId = null;
	$reportAbuserId = $id = filter_input(INPUT_GET, "reportAbuserId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$reportUserId = $id =  filter_input(INPUT_GET, "reportUserId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$reportAgent = $_SERVER['HTTP_USER_AGENT'];
	$reportContent = filter_input(INPUT_GET, "reportContent", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$reportDate = null;
	$reportIp =  $_SERVER['SERVER_ADDR'];

	if($method === "GET") {
		setXsrfCookie();

		if (($reportUserId !== null) && ($reportAbuserId !== null)) {
			$report = Report::getReportByReportUserIdAndReportAbuserId($pdo, $reportUserId, $reportAbuserId)->toArray();
			if($report !== null) {
				$reply->data = $report;
			}
		} else if(empty($reportUserId) === false) {
			$reply->data = Report::getReportByReportUserId($pdo, $reportUserId)->toArray();
		} else if(empty($reportAbuserId) === false) {
			$reply->data = Report::getReportByReportAbuserId($pdo, $reportAbuserId)->toArray();
		} else {
			throw(new \InvalidArgumentException("Incorrect Search Parameters", 405));
		}

	} else if($method == "POST") {
		verifyXsrf();
		validateJwtHeader();

		if(empty($_SESSION["user"]) === true) {
			throw(new \InvalidArgumentException("you must be logged in to report a user", 401));
		}
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		if(empty($reportAgent) === true) {
			throw(new \InvalidArgumentException("Report must have Agent info"));
		}
		if(empty($requestObject->reportContent) === true) {
			throw(new \InvalidArgumentException("Report must have content"));
		}
		if(empty($requestObject->reportDate) === true) {
			$requestObject->reportDate = null;
		}
		if(empty($reportIp) === true) {
			throw(new \InvalidArgumentException("Report must have an IP"));
		}


		// create new tweet and insert into the database
		$reportId = generateUuidV4();
		$report = new Report($reportId, $_SESSION["user"]->getUserId(), $requestObject->reportAbuserId, $reportAgent, $requestObject->reportContent, $requestObject->reportDate, $reportIp);
		$report->insert($pdo);
		// update reply
		$reply->message = "Report has been made";
		//}
	}

} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}
// encode and return reply to front end caller
header("Content-type: application/json");
echo json_encode($reply);