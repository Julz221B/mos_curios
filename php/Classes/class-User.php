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
    private string $firstName;
    private string $lastName;
    private DateTime $userDOB;

    private string $userName;
    private string $userEmail;
    private string $userPassword;

    public function __construct(string $userId, string $firstName, string $lastName, DateTime $userDOB, string $userName, string $userEmail, string $userPassword)
    {
        $this->userId = $userId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->userDOB = $userDOB;
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->userPassword = $userPassword;
    }

    public function insert($db)
    {
        // Check db connection
        if (!User::isConnected($db)) {
            throw new Exception("There was a problem connecting to the database.");
        }

        // Change DateTime to string
        $dob = $this->userDOB->format('m-d-Y');

        $query = "INSERT INTO users VALUES";
        $values = "('$this->userId', '$this->firstName', '$this->lastName', '$this->userDOB', '$this->userName', '$this->userEmail', '$this->userPassword');";

        var_dump($values);
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
}
