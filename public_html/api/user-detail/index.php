<?php
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/Classes/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/lib/jwt.php");
require_once(dirname(__DIR__, 3) . "/php/lib/xsrf.php");
require_once(dirname(__DIR__, 3) . "/php/lib/uuid.php");
require_once("/etc/apache2/capstone-mysql/Secrets.php");
use DeepDiveDatingApp\DeepDiveDating\{User, UserDetail};

	/**
	 * API for UserDetail
	 *
	 * @author Nwoodard1
	 * @version 1.0
	 **/


//verify the session, start if not active
			if(session_status() !== PHP_SESSION_ACTIVE) {
				session_start();
			//}

//prepare an empty reply
			$reply = new stdClass();
			$reply->status = 200;
			$reply->data = null;

			try {
				//grab the mySQL connection
				$secrets = new \Secrets("/etc/apache2/capstone-mysql/dateadan.ini");
				$pdo = $secrets->getPdoObject();

				//determine which HTTP method was used
				$method = $_SERVER["HTTP_X_HTTP_METHOD"] ?? $_SERVER["REQUEST_METHOD"];

				//sanitize input
				$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
				$userDetailId = filter_input(INPUT_GET, "userDetailId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
				$userDetailUserId = filter_input(INPUT_GET, "userDetailUserId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
				$userDetailAboutMe = filter_input(INPUT_GET, "userDetailAboutMe", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
				$userDetailAge = filter_input(INPUT_GET, "userDetailAge", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
				$userDetailCareer = filter_input(INPUT_GET, "userDetailCareer", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
				$userDetailDisplayEmail = filter_input(INPUT_GET, "userDetailDisplayEmail", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
				$userDetailEducation = filter_input(INPUT_GET, "userDetailEducation", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
				$userDetailGender = filter_input(INPUT_GET, "userDetailGender", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
				$userDetailInterests = filter_input(INPUT_GET, "userDetailInterests", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
				$userDetailRace = filter_input(INPUT_GET, "userDetailRace", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
				$userDetailReligion = filter_input(INPUT_GET, "userDetailReligion", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);



				//make sure the id is valid for methods that require it
				if(($method === "PUT") && (empty($id) === true)) {
					throw(new InvalidArgumentException("id cannot be empty or negative", 405));
				} else if($method === "PUT" || $method === "POST") {

					// enforce the user has a XSRF token
					verifyXsrf();

					//  Retrieves the JSON package that the front end sent, and stores it in $requestContent. Here we are using file_get_contents("php://input") to get the request from the front end. file_get_contents() is a PHP function that reads a file into a string. The argument for the function, here, is "php://input". This is a read only stream that allows raw data to be read from the front end request which is, in this case, a JSON package.
					$requestContent = file_get_contents("php://input");

					// This Line Then decodes the JSON package and stores that result in $requestObject
					$requestObject = json_decode($requestContent);

					//make sure tweet content is available (required field)
					if(empty($requestObject->userDetail) === true) {
						throw(new \InvalidArgumentException ("No content for user detail.", 405));
					}


					//perform the actual put or post
					if($method === "PUT") {

						// retrieve the tweet to update
						$user = UserDetail::getUserDetailByUserDetailId($pdo, $id);
						if($user === null) {
							throw(new RuntimeException("user detail does not exist", 404));
						}

						//enforce the user is signed in and only trying to edit their own tweet
						if(empty($_SESSION["userDetail"]) === true || $_SESSION["userDetail"]->getUserDetailId()->toString() !== $user->getUserDetailId()->toString()) {
							throw(new \InvalidArgumentException("You are not allowed to edit this user detail", 403));
						}

						// update all attributes
						$user->setUserDetail($requestObject->userDetail);
						$user->update($pdo);

						// update reply
						$reply->message = "user detail updated OK";

					}

				}
// update the $reply->status $reply->message
			} catch(\Exception | \TypeError $exception) {
				$reply->status = $exception->getCode();
				$reply->message = $exception->getMessage();
			}

// encode and return reply to front end caller
			header("Content-type: application/json");
			echo json_encode($reply);

// finally - JSON encodes the $reply object and sends it back to the front end.
}