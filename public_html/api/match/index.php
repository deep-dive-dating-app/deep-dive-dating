<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/Classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once("/etc/apache2/capstone-mysql/Secrets.php");

use DeepDiveDatingApp\DeepDiveDating\{ Match, User };


/*
 * API for the match class
 *
 * @author Kathleen Mattos
 *
 * needs post, put, get by detail id, and get by user id
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
	$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
	$matchUserId = filter_input(INPUT_GET, "matchUserId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$matchToUserId = filter_input(INPUT_GET, "matchToUserId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$matchApproved = filter_input(INPUT_GET, "matchApproved", FILTER_SANITIZE_NUMBER_INT);

	//make sure the id is valid for methods that require it
	if(($method === "PUT") && (empty($id) === true)) {
		throw(new InvalidArgumentException("id cannot be empty or negative", 405));
	}

	if($method === "GET") {
		setXsrfCookie();

		if((empty($matchUserId) === false) && (empty($matchToUserId) === false)) {
			$reply->data = Match::getMatchByMatchUserIdAndMatchToUserId($pdo, $matchUserId, $matchToUserId)->toArray();
		} else if(empty($matchToUserId) === false) {
			$reply->data = Match::getMatchByMatchToUserId($pdo, $matchToUserId)->toArray();
		} else if(empty($matchUserId) === false) {
			$reply->data = Match::getMatchByMatchUserId($pdo, $matchUserId)->toArray();
		} else {
			throw(new \InvalidArgumentException("Id cannot be empty or negative", 405));
		}
	} else if($method === "PUT" || $method === "POST") {
		verifyXsrf();

		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		//make sure tweet content is available (required field)
		if(empty($requestObject->matchApproved) === true) {
			throw(new \InvalidArgumentException ("Match value is undefined", 405));
		}

		if($method === "PUT") {

			// retrieve the tweet to update
			$match = Match::getMatchByMatchUserIdAndMatchToUserId($pdo, $matchUserId, $matchToUserId);
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
		} else if($method === "POST") {

			// enforce the user is signed in
			if(empty($_SESSION["user"]) === true) {
				throw(new \InvalidArgumentException("you must be logged in to make a match", 403));
			}

			// create new tweet and insert into the database
			$match = new Match($_SESSION["user"]->getUserId(), $requestObject->matchToUserId, 0);
			$match->insert($pdo);

			// update reply
			$reply->message = "Match has been created";
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
echo json_encode($reply);