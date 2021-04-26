<?php

namespace mos_curios\User;

// Requrie our libs
require_once(dirname(__DIR__, 1) . '/vendor/autoload.php');

use Ramsey\Uuid\Uuid;
use Datetime;
use Exception;
use InvalidArgumentException;
use Ramsey\Uuid\Codec\StringCodec;

class User
{
    private string $userId;
    private string $userName;
    private DateTime $userDOB;
    private string $userEmail;
    private string $userPassword;

    public function __construct(string $userId, string $userName, DateTime $DOB, string $userEmail, string $userPassword)
    {
        $this->userId = $userId;
        $this->userName = $userName;
        $this->userDOB = $DOB;
        $this->userEmail = $userEmail;
        $this->userPassword = $userPassword;
    }

    public function insertUser($db)
    {

        if (!User::isConnected($db)) {
            throw new Exception("Unable to connect to DB");
        }

        // Create query
        $query = "INSERT INTO users VALUES('$this->userId', '$this->userName', '$this->userEmail', crypt('$this->password', gen_salt('bf')));";

        pg_send_query($db, $query) or die("Couldn't insert user with id of $this->userId");
    }

    /**
     * A function that returns true if a connection is detected or false if not
     * 
     * @param $db posgres db connection
     */
    public static function isConnected($db): bool
    {
        // Check db connection
        if (pg_connection_status($db) !== PGSQL_CONNECTION_OK) {
            return false;
        }

        // Return false if connection failed
        return true;
    }

    /**
     * Validates a given date
     */
    public static function dateIsValid(string $date): bool
    {
        $sd = filter_var($date, FILTER_SANITIZE_STRING);
        // parse date
        $dArr = explode('/', $sd);

        // Make sure date is in correct format
        if (sizeof($dArr) < 3) {
            throw new InvalidArgumentException("Date provided is in an invalid format.");
        }

        // Validate date
        if (checkdate($dArr[1], $dArr[0], $dArr[2])) {
            return true;
        }

        return false;
    }
}
