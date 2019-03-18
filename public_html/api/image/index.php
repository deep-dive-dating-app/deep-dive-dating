<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/Classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once dirname(__DIR__, 3) . "/php/lib/jwt.php";
require_once("/etc/apache2/capstone-mysql/Secrets.php");
use DeepDiveDatingApp\DeepDiveDating\{
	User, Image
};

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

	$secrets = new \Secrets("/etc/apache2/capstone-mysql/cohort23/dateadan.ini");
	$pdo = $secrets->getPdoObject();
	$cloudinary = json_decode($config["cloudinary"]);

	$method = $_SERVER["HTTP_X_HTTP_METHOD"] ?? $_SERVER["REQUEST_METHOD"];

	$userId = filter_input(INPUT_GET, "imageUserId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	//$config = readConfig("/etc/apache2/capstone-mysql/cohort23/dateadan.ini");
	\Cloudinary::config(["dt4vdvdap" => $cloudinary->cloudName, "586793469126844" => $cloudinary->apiKey, "pBInJOl4iRq6UQOeprKg8yW-yNw" => $cloudinary->apiSecret]);

	if($method === "GET") {
		setXsrfCookie();
		$reply->data = Image::getAllImages($pdo)->toArray();
		}

		//get a specific image by id and update reply
		/*if(empty($id) === false) {
			$image = Image::getImageByImageId($pdo, $id);
		} elseif(empty($tweetId) === false) {
			$reply->data = Image::getImageByImageUserId($pdo, $userId)->toArray();
		}*/
		else if($method === "POST") {
		//enforce that the end user has a XSRF token.
		verifyXsrf();

		//use $_Post super global to grab the needed Id
		$userId = filter_input(INPUT_POST, "userId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

		// assigning variable to the user profile, add image extension
		$tempUserFileName = $_FILES["image"]["tmp_name"];

		// upload image to cloudinary and get public id
		$cloudinaryResult = \Cloudinary\Uploader::upload($tempUserFileName, array("width" => 500, "crop" => "scale"));

		// after sending the image to Cloudinary, create a new image
		$image = new Image(generateUuidV4(), $userId, $cloudinaryResult["signature"], $cloudinaryResult["secure_url"]);
		$image->insert($pdo);
		// update reply
		$reply->message = "Image uploaded Ok";
	}

} catch(Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}
header("Content-Type: application/json");
echo json_encode($reply);
