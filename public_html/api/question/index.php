<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/Classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once("/etc/apache2/capstone-mysql/Secrets.php");

use DeepDiveDatingApp\DeepDiveDating\Question;

/**
 *  API for app sign in, User class
 *
 * POST requests are supported.
 *
 * @author Nehomah Mora <nmora9@cnm.edu>
 **/

//Verify the session. If it's not active, start it.
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

// Prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {

	$questionInputs = [
		"08570473-b224-4b4e-bb69-d537be2b96de" => "value",
		"1630ed4d-9d0e-43b5-8a2d-310471dba230" => "value",
		"21545d7c-5142-4452-a284-6624cb1b741e" => "value",
		"2186bedc-3241-4a1a-938d-ebc91af7e785" => "value",
		"28479627-4218-4898-b1d4-ffb2aad72eba" => "value",
		"4664f9c0-eac3-41ac-aa75-4f64009a4b85" => "value",
		"5bd43186-5ad9-4db6-bf53-c4d14e87ed53" => "value",
		"79d4d031-1ad6-4e6e-8c0d-712e88164c32" => "value",
		"c9aaa062-9c23-4bd6-a0a6-ac623d5839df" => "value",
		"e0678ea3-c320-415e-983e-09c4caf26390" => "value"
	];
	// Grab the mySQL connection
	$secrets = new \Secrets("/etc/apache2/capstone-mysql/cohort23/dateadan.ini");
	$pdo = $secrets->getPdoObject();

	// Determine which HTTP method was used.
	$method = $_SERVER["HTTP_X_HTTP_METHOD"] ?? $_SERVER["REQUEST_METHOD"];


	// Sanitize input
	$questionId = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$questionContent = filter_input(INPUT_GET, "questionContent", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$questionValue = filter_input(INPUT_GET, "questionValue", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	// Ensure id is valid for methods that need it
	if(($method === "DELETE" || $method === "PUT") && (empty($questionId) === true)) {
		throw(new \InvalidArgumentException("id cannot be empty or negative", 405));
	}

	

	// Handle GET request - if id is present, that question is returned, otherwise all questions are returned
	if($method === "GET") {
		// Set xsrf token
		setXsrfCookie();

		//get a specific question or all questions and update reply
		if(empty($questionId) === false) {
			$reply->data = Question::getQuestionByQuestionId($pdo, $questionId);
		} else if(empty($questionContent) === false) {
			$reply->data = Question::getQuestionbyQuestionContent($pdo, $questionContent);
		} else {
			$reply->data = Question::getAllQuestions($pdo)->toArray();
		}
	} else if($method === "PUT" || $method === "POST") {
		// Enforce the user has xsrf token
		verifyXsrf();

		// Enforce user is signed in
		if(empty($_SESSION["user"]) === true) {
			throw(new \InvalidArgumentException("you must be signed in to change questions", 401));
		}
		// Retrieves JSON package that the front end sent, and stores it in $requestContent
		$requestContent = file_get_contents("php://input");
		// This line decodes the json package and stores result in $requestObject
		$requestObject = json_decode($requestContent);
		// Ensure question content is available (required)
		if(empty($requestObject->questionContent) === true) {
			throw(new \InvalidArgumentException("No content for question.", 405));
		}

		if($method === "PUT") {
			// Retrieve the question to update
			$question = Question::getQuestionByQuestionId($pdo, $questionId);
			if($event === null) {
				throw (new \RuntimeException("question does not exist, 400"));
			}

			// Enforce the end user has a JWT token
			validateJwtHeader();

			// Update question
			$question->setQuestionContent($requestObject->questionContent);
			$question->update($pdo);

			// Update reply
			$reply->message = "Question updated OK";
		} else if($method === "POST") {

			// Enforce user is signed in
			if(empty($_SESSION["user"]) === true) {
				throw(new \InvalidArgumentException("you must be logged in to create questions.", 403));
			}

			// Enforce end user has JWT token
			validateJwtHeader();

			// Create a new question and insert into database
			$question = new Qustion(generateUuidV4(), $requestObject->questionContent, $requestObject->questionValue);
			$question->insert($pdo);

			// Update reply
			$reply->message = "Question created OK";
		}
	}
}	catch (\TypeError | \Exception $exception) {
				$reply->status = $exception->getCode();
				$reply->message = $exception->getMessage();
	}

	// Encode and return to front end caller
	header("Content-type: application/json");
	echo json_encode($reply);
