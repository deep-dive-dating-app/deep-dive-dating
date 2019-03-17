<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/Classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once("/etc/apache2/capstone-mysql/Secrets.php");

use DeepDiveDatingApp\DeepDiveDating\{User, UserDetail};

/**
 * API for signing up for Blame Dan Date Dan
 *
 * @author Taylor Smith
 **/
//verify session, if none start new session
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {
	//grab the MySQL connection
	$secrets = new \Secrets("/etc/apache2/capstone-mysql/cohort23/dateadan.ini");
	$pdo = $secrets->getPdoObject();
	//determine which of the HTTP methods was used
	$method = $_SERVER["HTTP_X_HTTP_METHOD"] ?? $_SERVER["REQUEST_METHOD"];

	if($method === "POST") {
		verifyXsrf();
		//get jSon package in string form then decode it
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);
		//assert that all sign in fields are valid
		if(empty($requestObject->userAvatarUrl) === true) {
			throw(new \InvalidArgumentException("User Avatar Picture is Empty", 405));
		}
		// possibly set url to accept null value and or use default icon
		if(empty($requestObject->userEmail) == true) {
			throw(new \InvalidArgumentException("User Email is Empty", 405));
		}
		if(empty($requestObject->userHandle) === true) {
			throw(new \InvalidArgumentException("User Handle is Empty", 405));
		}

		if(empty($requestObject->userPassword) === true) {
			throw(new \InvalidArgumentException("User Password is Empty", 405));
		}
		if(empty($requestObject->userPasswordConfirm) === true) {
			throw(new \InvalidArgumentException ("Passwords Do Not Match", 405));
		}
		//confirm that passwords match
		if($requestObject->userPassword !== $requestObject->userPasswordConfirm) {
			throw(new \InvalidArgumentException("Passwords Do Not Match", 405));
		}

		if(empty($requestObject->userDetailAboutMe) === true) {
			$requestObject->userDetailAboutMe = "This person does not have an about me, yet.";
		}
		if(empty($requestObject->userDetailAge) === true) {
			throw(new \InvalidArgumentException("Please, select your age."));
		}
		if(empty($requestObject->userDetailCareer) === true) {
			throw(new \InvalidArgumentException("Please enter your Career."));
		}
		if(empty($requestObject->userDetailDisplayEmail) === true) {
			$requestObject->userDetailDisplayEmail = $requestObject->userEmail;
		}
		var_dump($requestObject->userDetailDisplayEmail);
		if(empty($requestObject->userDetailEducation) === true) {
			throw(new \InvalidArgumentException("Please enter your education."));
		}
		if(empty($requestObject->userDetailGender) === true) {
			throw(new \InvalidArgumentException("Please enter your gender."));
		}
		//why do we have Interests? is it like the about me or do we have a specific question?
		if(empty($requestObject->userDetailInterests) === true) {
			$requestObject->userDetailInterests = null;
		}
		if(empty($requestObject->userDetailRace) === true) {
			throw(new \InvalidArgumentException("Please enter your race."));
		}
		if(empty($requestObject->userDetailReligion) === true) {
			throw(new \InvalidArgumentException("Please enter your religion."));
		}

		//do the values below  get assigned on sign up or after activation?
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		$userIpAddress =  $_SERVER['SERVER_ADDR'];
		$userDetailId = generateUuidV4();
		//user blocked? using 0 as default
		$userBlocked = 0;

		$userHash = password_hash($requestObject->userPassword, PASSWORD_ARGON2I, ["time_cost" => 384]);
		$userActivationToken = bin2hex(random_bytes(16));
		$userId = generateUuidV4();

		//create user object
		$user = new User($userId, $userActivationToken, $userAgent, $requestObject->userAvatarUrl, $userBlocked, $requestObject->userEmail, $requestObject->userHandle, $userHash, $userIpAddress);
		$userDetail = new UserDetail($userDetailId, $userId, $requestObject->userDetailAboutMe, $requestObject->userDetailAge, $requestObject->userDetailCareer, $requestObject->userDetailDisplayEmail, $requestObject->userDetailEducation, $requestObject->userDetailGender, $requestObject->userDetailInterests, $requestObject->userDetailRace, $requestObject->userDetailReligion);
		$user->insert($pdo);
		$userDetail->insert($pdo);

		//compose the email message to send with th activation token
		$messageSubject = "One step closer to Account Activation!";
		//building the activation link that can travel to another server and still work. This is the link that will be clicked to confirm the account.
		//make sure URL is /public_html/api/activation/$activation
		$basePath = dirname($_SERVER["SCRIPT_NAME"], 3);
		//create the path
		$urlglue = $basePath . "/api/activation/?activation=" . $userActivationToken;
		//create the redirect link
		$confirmLink = "https://" . $_SERVER["SERVER_NAME"] . $urlglue;

		//compose message to send with email
		$message = <<< EOF
		<h2>Welcome to Blame Dan Date Dan</h2>
		<p>In order to date Dan you must first confirm your account. </p>
		<p><a href="$confirmLink">$confirmLink</a></p>
		EOF;

		//create swift email
		$swiftMessage = new Swift_Message();
		// attach the sender to the message
		// this takes the form of an associative array where the email is the key to a real name
		$swiftMessage->setFrom(["taylorleesmith92@gmail.com" => "Taylor Smith"]);

		/**
		 * attach recipients to the message
		 * notice this is an array that can include or omit the recipient's name
		 * use the recipient's real name where possible;
		 * this reduces the probability of the email is marked as spam
		 */
		//define who the recipient is
		$recipients = [$requestObject->userEmail];
		//set the recipient to the swift message
		$swiftMessage->setTo($recipients);
		//attach the subject line to the email message
		$swiftMessage->setSubject($messageSubject);

		/**
		 * attach the message to the email
		 * set two versions of the message: a html formatted version and a filter_var()ed version of the message, plain text
		 * notice the tactic used is to display the entire $confirmLink to plain text
		 * this lets users who are not viewing the html content to still access the link
		 */
		//attach the html version fo the message
		$swiftMessage->setBody($message, "text/html");
		//attach the plain text version of the message
		$swiftMessage->addPart(html_entity_decode($message), "text/plain");

		/**
		 * send the Email via SMTP; the SMTP server here is configured to relay everything upstream via CNM
		 * this default may or may not be available on all web hosts; consult their documentation/support for details
		 * SwiftMailer supports many different transport methods; SMTP was chosen because it's the most compatible and has the best error handling
		 * @see http://swiftmailer.org/docs/sending.html Sending Messages - Documentation - SwitftMailer
		 **/
		//setup smtp
		$smtp = new Swift_SmtpTransport(
			"localhost", 25);
		$mailer = new Swift_Mailer($smtp);
		//send the message
		$numSent = $mailer->send($swiftMessage);
		/**
		 * the send method returns the number of recipients that accepted the Email
		 * so, if the number attempted is not the number accepted, this is an Exception
		 **/
		if($numSent !== count($recipients)) {
			// the $failedRecipients parameter passed in the send() method now contains contains an array of the Emails that failed
			throw(new RuntimeException("unable to send email", 400));
		}
		// update reply
		$reply->message = "Thank you for creating a Blame Dan Date Dan Profile";
	} else {
		throw (new InvalidArgumentException("invalid http request"));
	}
} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}

// encode and return reply to front end caller
header("Content-type: application/json");
echo json_encode($reply);