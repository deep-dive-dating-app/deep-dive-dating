<?php

// we determine if we have a GET request. If so, we then process the request.
if ($method === "GET") {


// If it is not a GET request, we then proceed here to determine if we have a PUT or POST request.
} else if($method === "PUT") {

	//do setup that is needed for PUT request

	//perform the actual put
	if($method === "PUT") {
		// determines if we have a PUT request. If so we process the request.
		// process PUT requests here

}
else if($method === "PUT") {

	// enforce the user has a XSRF token
	verifyXsrf();

	//  Retrieves the JSON package that the front end sent, and stores it in $requestContent. Here we are using file_get_contents("php://input") to get the request from the front end. file_get_contents() is a PHP function that reads a file into a string. The argument for the function, here, is "php://input". This is a read only stream that allows raw data to be read from the front end request which is, in this case, a JSON package.
	$requestContent = file_get_contents("php://input");

	// This Line Then decodes the JSON package and stores that result in $requestObject
	$requestObject = json_decode($requestContent);

	//make sure user detail is available (required field)
	if(empty($requestObject->userDetail) === true) {
		throw(new \InvalidArgumentException ("No content for user detail", 405));
	}

	//perform the actual put
	if($method === "PUT") {

		// retrieve the user detail to update
		$userDetail = User::getUserDetailByUserId($pdo, $id);
		if($userDetail === null) {
			throw(new RuntimeException("user detail does not exist", 404));
		}

		//enforce the user is signed in and only trying to edit their own user detail
		if(empty($_SESSION["user"]) === true || $_SESSION["user"]->getUserId()->toString() !== $userDetail->getUserId()->toString()) {
			throw(new \InvalidArgumentException("You are not allowed to edit this user detail", 403));
		}

		// update all attributes
		$userDetail->setUserDetail($requestObject->userDetail);
		$userDetail->update($pdo);
	}

		// update reply
		$reply->message = "User detail updated OK";
	}
}