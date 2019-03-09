<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/Classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once("/etc/apache2/capstone-mysql/Secrets.php");

use DeepDiveDatingApp\DeepDiveDating\{Answer, User};


/*
 * API for the answer class
 *
 * @author Kathleen Mattos
 */

//verifying the session, or start if not active
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare and empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	//get mysql connection
	$secrets =  new \Secrets("/etc/apache2/capstone-mysql/cohort23/dateadan.ini");
	$pdo = $secrets->getPdoObject();

	// which http method used
	$method = $_SERVER["HTTP_X_HTTP_METHOD"] ?? $_SERVER["REQUEST_METHOD"];

	//sanitize the input
	$answerUserId = filter_input(INPUT_GET, "answerUserId", FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
	$answerQuestionId = filter_input(INPUT_GET, "answerQusetionId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$answerResult = filter_input(INPUT_GET, "answerResult", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$answerScore = filter_input(INPUT_GET, "answerScore", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	//make sure the id is valid for methods that require it
	if (($method === "DELETE" || $method === "PUT") && (empty($answerUserId) === true)){
		throw(new InvalidArgumentException("id cannot be empty or negative", 405));
	}

if($method === "POST") {
	// enforce the user has a XSRF token
	verifyXsrf();

	//  Retrieves the JSON package that the front end sent, and stores it in $requestContent. Here we are using file_get_contents("php://input") to get the request from the front end. file_get_contents() is a PHP function that reads a file into a string. The argument for the function, here, is "php://input". This is a read only stream that allows raw data to be read from the front end request which is, in this case, a JSON package.
	$requestContent = file_get_contents("php://input");

	// This Line Then decodes the JSON package and stores that result in $requestObject
	$requestObject = json_decode($requestContent);

	//make sure answer result is available (required field)
	if(empty($requestObject->answerResult) === true) {
		throw(new \InvalidArgumentException ("There is no answer for this question.", 405));
	}
	//make sure answer result is available (required field)
	if(empty($requestObject->answerScore) === true) {
		throw(new \InvalidArgumentException ("There is no score for this answer.", 405));
	}

	//perform the actual put or post
	if($method === "PUT") {

		//retrieve the result to update
		$answerResult = Answer::getAnswerByAnswerUserId($pdo, $answerUserId);
		if($answer === null) {
			throw (new RuntimeException("Answer does not exist", 404));
		}

		//enforce the user is signed in and only trying to edit their own answer
		if(empty($_SESSION["user"]) === true || $_SESSION["user"]->getUserId()->toString() !== $answer->getAnserUserId()->toString()) {
			throw(new \InvalidArgumentException("You are not allows to edit this answer.", 403));
		}

		//update all attributes
		$answer->setAnswerResult($requestObject->answerResult);
		$answer->setAnswerScore($requestObject->answerScore);
		$answer->update($pdo);

		//update reply
		$reply->message = "Answer updated OK";
	}
}	else {
		throw(new \InvalidArgumentException("Inavlid HTTP request", 418));

	}
	//update the $reply->status $reply->message
}	catch(\TypeError | \Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}

//encode and return to the front end caller
header("Content-type: application/json");
echo json_encode($reply);