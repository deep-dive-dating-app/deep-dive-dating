<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/Classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once("/etc/apache2/capstone-mysql/Secrets.php");

use DeepDiveDatingApp\DeepDiveDating\User;

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

			// Grab the mySQL connection
			$secrets = new \Secrets("/etc/apache2/capstone-mysql/cohort23/dateadan.ini");
			$pdo = $secrets->getPdoObject();

			// Determine which HTTP method was used.
			$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ?? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

			// If the method is POST, handle the sign -in logic.
			if($method == "POST") {

				// Make sure the XSRF Token is valid.
				verifyXsrf();

				// Process the request content and decode the json object into a PHP object.
				$requestContent = file_get_contents("php://input");
				$requestObject = json_decode($requestContent);

				// Check for the email (required field)
			}

}