<?php
require_once(dirname(__DIR__, 2) . '/php/vendor/autoload.php');
require_once(dirname(__DIR__, 1) . '/Classes/class-User.php');

use mos_curios\User\User;
use Ramsey\Uuid\Uuid;
use Pear\Validate\Validate;

session_start();

// Connect to database
$pgConnection = pg_connect("host=localhost dbname=mos_curios user=postgres password=1234");

// Test db connection
if (! User::isConnected($pgConnection)) {
    throw new Exception("Couldn't connect to DB from user login page.");
}

// Validate input
if (count($_POST) > 0) {
    $userName = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $userDOB = User::dateIsValid(trim($_POST['birthdate'])) ? $_POST['birthdate'] : false;
    $userEmail = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $userPassword = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    
    // Make sure a valid DOB was passed
    if (!$userDOB) {
        $reply->data = "Invalid birthdate was provided.";
        $reply->status = 422;
        throw new InvalidArgumentException("An invalid date was provided");
    }
}