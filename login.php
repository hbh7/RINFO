<?php

// Get POST data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $action = $_POST['action'];

    if(isset($_POST['firstname']))
        $firstname = $_POST['firstname'];
    if(isset($_POST['lastname']))
        $lastname = $_POST['lastname'];
    if(isset($_POST['email']))
        $email = $_POST['email'];
}

// Get GET data
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(isset( $_GET['redirectmsg'])) {
        $result = [False, $_GET['redirectmsg']];
    }
}

// Data validation
// TODO: Make sure username and password are strings of appropriate length for the db and that they have normal characters only
// TODO: Make sure capitalization is nice for firstname and lastname

if(isset($action)) {

    // DB Calls
    include_once 'db.php';
    $users = dbGet("user_id, username, password, firstname, lastname, enabled", "r_users", "username='" . $username . "'");

    if ($action == "register") {

        // Make sure they're not already registered
        if (sizeof($users) > 0) {
            // User already exists in the database
            $result = [false, "Already Registered"];
        } else {
            // Register them if not
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $dbres = dbPut("r_users", [$username, $hash, $firstname, $lastname, $email]);
            if ($dbres == "success") {
                // Generate default permissions
                $user_id = dbGet("user_id", "r_users", "username='" . $username . "'")[0]["user_id"];
                dbPut("r_permissions", [$user_id, 0, "post"]);
                dbPut("r_permissions", [$user_id, 0, "createGroup"]);

                $result = [true, "Registered Successfully, Please log in"];
            } else {
                $result = [false, "Failed to register. Error: " . $dbres];
            }

        }

    } elseif ($action == "login") {

        if(sizeof($users) == 0) {
            $result = [false, "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong>Failed to log in:</strong>  Invalid User and/or Password!<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span></button></div>"];
        } else {

            // Make sure they're not disabled/banned
            if($users[0]["enabled"] == false) {
                $result = [false, "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                <strong>Failed to log in:</strong>  Account is disabled!<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span></button></div>"];

            } else {

                $dbhash = $users[0]['password'];
                if (password_verify($password, $dbhash)) {
                    // Success!
                    $result = [true, "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                <strong>Successful Login!</strong><button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span></button></div>"];

                    // Setting cookie
                    $loginCookie = ["username" => $username, "passwordHash" => $dbhash, "firstname" => $users[0]['firstname'], "lastname" => $users[0]['lastname']];
                    // Cookie expires after 1 month
                    setcookie("login", json_encode($loginCookie), time() + (86400 * 30), "/");
                    // Redirect to homepage
                    header("Location: /index.php");
                    die();
                } else {
                    // Invalid credentials or unknown user
                    $result = [false, "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong>Failed to log in:</strong>  Invalid User and/or Password!<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span></button></div>"];
                }
            }
        }

    } elseif ($action == "forgot") {

        // Handle forgotten passwords by laughing at the user's misfortune
        $result = [False, "Sorry, you're out of luck lol. Should have used a password manager"];

    } else {
        // Invalid action
        $result = [False, "Error: Invalid action"];
    }
}

?>

<html lang="en">
    <head>
        <?php $title = "RINFO Login"; ?>
        <?php include('resources/templates/head.php'); ?>
    </head>
    <body>
        <?php include('resources/templates/header.php'); ?>
        <div id="login_content">
            <div id="info_panel">
                <div class="tips">
                    <h2>Subscribe to groups</h2>
                    <h2>Browse personalized event feeds</h2>
                    <h2>Discover new interests</h2>
                    <h2>Get to know your campus better</h2>
                </div>
            </div>
            <div id="login_panel">
                <p id="errors">
                    <?php
                    if(isset($result)) {
                        echo $result[1];
                    }
                    ?>
                </p>
                <form method="post" id="login_box">
                <?php
                if(isset($action) && $action == "registerpage") {
                    echo "
                    <label for=\"firstname\">First Name </label><input type=\"text\" name=\"firstname\" id=\"firstname\" value=\"\" placeholder=\"SIS\">
                    <br /><br />
                    <label for=\"lastname\">Last Name </label><input type=\"text\" name=\"lastname\" id=\"lastname\" value=\"\" placeholder=\"Man\">
                    <br /><br />
                    <label for=\"email\">Email </label><br><input type=\"text\" name=\"email\" id=\"email\" value=\"\" placeholder=\"sisman@rpi.edu\">
                    <br /><br />
                    <label for=\"username\">Username </label><input type=\"text\"  name=\"username\" id=\"username\" value=\"\" placeholder=\"sisman\">
                    <br /><br />
                    <label for=\"password\">Password (Don't forget it!)</label><input type=\"password\" name=\"password\" id=\"password\" value=\"\" placeholder=\"************\">
                    <br /><br />
                    <button type=\"submit\" id=\"register_button\" name=\"action\" value=\"register\">Register</button>
                    ";
                } else {
                    echo "
                    <label for=\"username\">Username </label><input type=\"text\" name=\"username\" id=\"username\" value=\"\" placeholder=\"sisman\">
                    <br /><br />
                    <label for=\"password\">Password </label><input type=\"password\" name=\"password\" id=\"password\" value=\"\" placeholder=\"************\">
                    <br /><br />
                    <button type=\"submit\" id=\"login_button\" class=\"btn btn-secondary\" name=\"action\" value=\"login\">Log In</button>
                    <br /><br />
                    <button type=\"submit\" id=\"forgot_password_button\" class=\"btn btn-secondary\" name=\"action\" value=\"forgot\">Forgot Password?</button>
                    <br /><br />
                    <button type=\"submit\" id=\"register_button\" class=\"btn btn-secondary\" name=\"action\" value=\"registerpage\">Don't have an account? </br>Click here to join</button>
                    ";
                }
                ?>
                </form>
            </div>
        </div>

        <?php include('resources/templates/footer.php'); ?>
    </body>
</html>