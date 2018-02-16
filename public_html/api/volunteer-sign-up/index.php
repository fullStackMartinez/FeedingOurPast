<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\FeedPast\Volunteer;

/**
 * api for signing up as a Volunteer to Stop Senior Hunger
 *
 * @author Jolynn Pruitt <jpruitt5@cnm.edu>
 * @author Gkephart <GKephart@cnm.edu>
 **/

// verify the session, start if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	sessions_start();
}

// prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	// grab the mySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone/mysql/feedkitty.ini");

	// determine which HTTP method was used
	// shorthand: $method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];
	if(array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER)) {
		$method = $_SERVER["HTTP_X_HTTP_METHOD"];
	} else {
		$method = $_SERVER["REQUEST_METHOD"];
	}

	if($method === "POST") {
		// decode the json and turn it into a php object
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		// volunteer profile availability (can be null, if empty set it to null)
		if(empty($requestObject->volunteerAvailability) === true) {
			$requestObject->volunteerAvailability = null;
		}

		// volunteer profile email (required)
		if(empty($requestObject->volunteerEmail) === true) {
			throw(new \InvalidArgumentException("No volunteer email present", 405));
		}

		// volunteer profile name (required)
		if(empty($requestObject->volunteerName) === true) {
			throw(new \InvalidArgumentException("No volunteer name present", 405));
		}

		// volunteer profile password (required)
		if(empty($requestObject->volunteerPassword) === true) {
			throw(new\InvalidArgumentException("Must input valid password", 405));
		}

		// verify that the confirm password is present (required)
		if(empty($requestObject->volunteerPasswordConfirm) === true) {
			throw(new \InvalidArgumentException("Must input valid password", 405));
		}

		// volunteer profile phone (required)
		if(empty($requestObject->volunteerPhone) === true) {
			throw(new \InvalidArgumentException("No volunteer phone number present", 405));
		}

		// made sure the password and confirm password match
		if($requestObject->volunteerPassword !== $requestObject->volunteerPasswordConfirm) {
			throw(new \InvalidArgumentException("Passwords do not match"));
		}

		$salt = bin2hex(random_bytes(32));
		$hash = hash_pbkdf2("sha512", $requestObject->volunteerPassword, $salt, 262144);

		$volunteerActivationToken = bin2hex(random_bytes(16));

		// create the volunteer object and prepare to insert into the database
		$volunteer = new Volunteer(generateUuidV4(), $volunteerActivationToken, $requestObject->volunteerAvailability, $requestObject->volunteerEmail, $hash, $requestObject->volunteerName, $requestObject->volunteerPhone, $salt);

		// insert the volunteer profile into the database
		$volunteer->insert($pdo);

		// compose the email message to send with the activation token
		$messageSubject = "One step closer to Stop Senior Hunger -- Account Activation";

		// building the activation link that will be clicked to confirm the account
		// make sure URL is /public_html/api/activation/$activation
		$basePath = dirname($_SERVER["SCRIPT_NAME"], 3);

		// create the path
		$urlglue = $basePath . "/api/activation/?activation=" . $volunteerActivationToken;

		// create the redirect link
		$confirmLink = "https://" . $_SERVER["SERVER_NAME"] . $urlglue;

		// compose message to send with email
		$message = <<< EOF

EOF;




	}



}