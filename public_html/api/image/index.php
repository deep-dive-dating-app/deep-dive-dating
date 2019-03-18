<?php

require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/Classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once dirname(__DIR__, 3) . "/php/lib/jwt.php";
require_once("/etc/apache2/capstone-mysql/Secrets.php");
use DeepDiveDatingApp\DeepDiveDating\User;

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
	// determine the HTTP method used (we only allow the POST method to be used for image uploading
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	if ($method !== "POST") {
		throw(new \Exception("This HTTP method is not supported for image upload.", 405));
	}
	
	// verify the SXRF-TOKEN is present
	verifyXsrf();

	// make sure user is logged in before uploading a picture
	if(empty($_SESSION["user"]) || empty($_SESSION["user"]->getUserId()->toString())) {
		throw(new \InvalidArgumentException("You must be logged in to upload your image.", 403));
	}

	// validate header
	validateJwtHeader();

	$config = readConfig("/etc/apache2/capstone-mysql/cohort23/dateadan.ini");
	$cloudinary = json_decode($config["cloudinary"]);
	\Cloudinary::config(["dt4vdvdap" => $cloudinary->cloudName, "586793469126844" => $cloudinary->apiKey, "pBInJOl4iRq6UQOeprKg8yW-yNw" => $cloudinary->apiSecret]);

	//assigning variable to the user image/avatar, add image extenstion
	$tempUserFileName = $_FILES["image"]["tmp_name"];
	// upload image to cloudinary and get public id
	$cloudinaryResult = \Cloudinary\Uploader::upload($tempUserFileName, array("width" => 500, "crop" => "scale"));
	$reply->data = $cloudinaryResult["secure_url"];

	// update reply
	$reply->message = "Image uploaded ok.";

} catch(Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}

//encode and return reply to the front-end caller
header("Content-Type: application/json");
if (!$reply->data) {
	unset($reply->data);
}

// encode and return reply to front-end caller
echo json_encode($reply);
