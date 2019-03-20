<?php

require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/Classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once dirname(__DIR__, 3) . "/php/lib/jwt.php";
require_once("/etc/apache2/capstone-mysql/Secrets.php");

use DeepDiveDatingApp\DeepDiveDating\{User};

/**
 * Cloudinary Api for Images
 *
 * @author Taylor Smith
 **/

if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
// prepare an empty reply
$reply = new StdClass();
$reply->status = 200;
$reply->data = null;

try {
			// Grab the MySQL connection
			$secrets = new \Secrets("/etc/apache2/capstone-mysql/cohort23/dateadan.ini");
			$pdo = $secrets->getPdoObject();
			$cloudinary = $secrets->getSecret("cloudinary");

			// determine the HTTP method used (we only allow the POST method to be used for image uploading
			$method = $_SERVER["HTTP_X_HTTP_METHOD"] ?? $_SERVER["REQUEST_METHOD"];

			// sanitize input
			$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
			$userId = filter_input(INPUT_GET, "userId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
			$userAvatarUrl = filter_input(INPUT_GET, "userAvatarUrl", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

			\Cloudinary::config(["cloud_name" => $cloudinary->cloudName, "api_key" => $cloudinary->apiKey, "api_secret" => $cloudinary->apiSecret]);

			// process Get requests
			if($method === "GET") {

				// set XSRF token
				setXsrfCookie();

			} elseif ($method === "POST") {

						// verify the SXRF-TOKEN is present
						verifyXsrf();

						//use $_POST super global to grab the needed Id
						

						//assigning variable to the user image/avatar, add image extension
						$tempUserFileName = $_FILES["image"]['tmp_name'];

						// upload image to cloudinary and get public id
						$cloudinaryResult = \Cloudinary\Uploader::upload($tempUserFileName, array("width" => 200, "crop" => "scale"));

						//after sending the image to cloudinary, create a new image
						//TODO find out which class needs to be called here and how to inject the cloudinary in;

						//update reply
						$reply->message = $cloudinaryResult["secure_url"];
				}


	//$config = readConfig("/etc/apache2/capstone-mysql/cohort23/dateadan.ini");


	//$cloudinary = json_decode($config["cloudinary"]);


} catch(Exception $exception) {
		$reply->status = $exception->getCode();
		$reply->message = $exception->getMessage();
}

//encode and return reply to the front-end caller
header("Content-Type: application/json");

// encode and return reply to front-end caller
echo json_encode($reply);
