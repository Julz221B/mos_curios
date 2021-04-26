<?php
// Import our libs
require_once(dirname(__DIR__, 1) . '/vendor/autoload.php');

require_once(dirname(__DIR__, 1) . '/Classes/class-User.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create Account</title>
</head>
<body>
    <form id="new-user-form" action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
        <fieldset>
            <legend>Create Account</legend>
            <!-- User Personal Info -->
            <fieldset id="personal-info">
                <legend>Tell us about you</legend>
                <!-- First Name Form Field-->
                <label for="first-name">First Name:</label><br>
                <input type="text" id="first-name" name="firstName" required/><span>*</span>
                <br>
                <!-- Last Name Form Field -->
                <label for="last-name">Last Name:</label><br>
                <input type="text" id="last-name" name="lastName" required/><span>*</span>
                <br>
                <!-- Date Of Birth Form Field -->
                <label for="user-dob">Date of Birth:</label><br>
                <input type="date" id="user-dob" name="dateOfBirth" required/><span>*</span>
            </fieldset>

            <!-- User Site Credentials -->
            <fieldset id="user-site-info">
                <legend>Credentials</legend>
                <!-- Username Form Field -->
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username" required/><span>*</span>
                <br>
                <!-- User Email Form Field -->
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="eMail" requiredt/><span>*</span>
                <br>
                <!-- Password Form Field -->
                <label for="password">Password:</label><br>
                <input type="password" id="password" required/><span>*</span>
            </fieldset>
            <br>
            <button type="submit" name="submit" value="new-user-submit" style="margin-left: 75%;">Submit</button>
        </fieldset>
    </form>
</body>
</html>

<?php

?>