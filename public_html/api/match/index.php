<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/Classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once dirname(__DIR__, 3) . "/php/lib/jwt.php";
require_once("/etc/apache2/capstone-mysql/Secrets.php");

use DeepDiveDatingApp\DeepDiveDating\{ Match, User };


/*
 * API for the match class
 *
 * @author Kathleen Mattos
 *
 * needs post, put, and get by's
 */

if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	//grab the mySQL connection
	// grab mySQL connection
	$secrets = new \Secrets("/etc/apache2/capstone-mysql/cohort23/dateadan.ini");
	$pdo = $secrets->getPdoObject();


	//determine which HTTP method was used
	$method = $_SERVER["HTTP_X_HTTP_METHOD"] ?? $_SERVER["REQUEST_METHOD"];

	//sanitize input
	$matchUserId = $id = filter_input(INPUT_GET, "matchUserId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$matchToUserId = $id = filter_input(INPUT_GET, "matchToUserId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$matchApproved = filter_input(INPUT_GET, "matchApproved", FILTER_SANITIZE_NUMBER_INT);


	if($method === "GET") {
		setXsrfCookie();

		if ($matchUserId !== null && $matchToUserId !== null) {
			$match = Match::getMatchByMatchUserIdAndMatchToUserId($pdo, $matchUserId, $matchToUserId)->toArray();
			if($match !== null) {
				$reply->data = $match;
			}
		} else if(empty($matchToUserId) === false) {
			$reply->data = Match::getMatchByMatchToUserId($pdo, $matchToUserId)->toArray();
		} else if(empty($matchUserId) === false) {
			$reply->data = Match::getMatchByMatchUserId($pdo, $matchUserId)->toArray();
		} else {
			throw(new \InvalidArgumentException("Incorrect Search Parameters", 405));
		}
	} else if($method === "POST" || $method === "PUT") {
		verifyXsrf();

		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		if(empty($requestObject->matchUserId) === true) {
			throw (new \InvalidArgumentException("No User linked to this Match", 405));
		}
		if(empty($requestObject->matchToUserId) === true) {
			throw (new \InvalidArgumentException("No Matched User linked to this Match", 405));
		}
		if(($requestObject->matchApproved) === null) {
			throw(new \InvalidArgumentException ("Match value is undefined", 405));
		}

		if($method === "POST") {
			verifyXsrf();
			validateJwtHeader();

			// enforce the user is signed in
			if(empty($_SESSION["user"]) === true) {
				throw(new \InvalidArgumentException("you must be logged in to make a match", 403));
			}

			// create new tweet and insert into the database
			$match = new Match($_SESSION["user"]->getUserId(), $requestObject->matchToUserId, 0);
			$match->insert($pdo);

			// update reply
			$reply->message = "Match has been created";

		} else if ($method === "PUT") {

			verifyXsrf();
			validateJwtHeader();

			$match = Match::getMatchByMatchUserIdAndMatchToUserId($pdo, $requestObject->matchUserId, $requestObject->matchToUserId);
			if($match === null) {
				throw(new RuntimeException("Match does not exist", 404));
			}

			//enforce the user is signed in and only trying to edit their own tweet
			if(empty($_SESSION["user"]) === true || $_SESSION["user"]->getUserId()->toString() !== $match->getMatchUserId()->toString()) {
				throw(new \InvalidArgumentException("You are not allowed to edit this Match", 403));
			}

			// update all attributes
			$match->setMatchApproved($requestObject->matchApproved);
			$match->update($pdo);

			// update reply
			$reply->message = "Match has been updated";

		}
	} else {
		throw (new InvalidArgumentException("Invalid HTTP method request"));
	}

} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}

// encode and return reply to front end caller
header("Content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}
echo json_encode($reply);