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
$err = Array();

// Data validation
// TODO: Make sure username and password are strings of appropriate length for the db and that they have normal characters only
// TODO: Make sure action is "login" or "register"

if(isset($action)) {

// DB Calls
    include 'db.php';
    $users = dbGet("user_id, username, password", "r_users", "username='" . $username . "'");

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
                $result = [true, "Registered Successfully"];
            } else {
                $result = [false, "Failed to register. Error: " . $dbres];
            }

        }

    } elseif ($action == "login") {

        if(sizeof($users) == 0) {
            $result = [false, "Failed to log in: Invalid user!"];
        } else {
            $dbhash = $users[0]['password'];
            if (password_verify($password, $dbhash)) {
                // Success!
                $result = [true, "Logged in Successfully"];

                // Setting cookie
                // TODO: Consider some sort of cookie validation

            } else {
                // Invalid credentials or unknown user
                $result = [false, "Failed to log in: Invalid password!"];
            }
        }

    } elseif ($action == "forgot") {

        // Handle forgotten passwords by laughing at the user's misfortune
        $result = [False, "Sorry, you're out of luck lol. Should have used a password manager"];

    }

}

?>

<html lang="en">
    <head>
        <?php include('resources/templates/head.php'); ?>
        <title> RINFO Login </title>
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
                    <label for=\"firstname\">First Name </label><input type=\"text\" name=\"firstname\" id=\"firstname\" value=\"\" placeholder=\"Shirley\">
                    <br /><br />
                    <label for=\"lastname\">Last Name </label><input type=\"text\" name=\"lastname\" id=\"lastname\" value=\"\" placeholder=\"Jackson\">
                    <br /><br />
                    <label for=\"email\">eMail </label><input type=\"text\" name=\"email\" id=\"email\" value=\"\" placeholder=\"jackson@rpi.edu\">
                    <br /><br />
                    <label for=\"username\">Username </label><input type=\"text\"  name=\"username\" id=\"username\" value=\"\" placeholder=\"shirleyannjackson\">
                    <br /><br />
                    <label for=\"password\">Password (Don't forget it!)</label><input type=\"password\" name=\"password\" id=\"password\" value=\"\" placeholder=\"************\">
                    <br /><br />
                    <button type=\"submit\" id=\"register_button\" name=\"action\" value=\"register\">Register</button>
                    ";
                } else {
                    echo "
                    <label for=\"username\">Username </label><input type=\"text\" name=\"username\" id=\"username\" value=\"\" placeholder=\"shirleyannjackson\">
                    <br /><br />
                    <label for=\"password\">Password </label><input type=\"password\" name=\"password\" id=\"password\" value=\"\" placeholder=\"************\">
                    <br /><br />
                    <button type=\"submit\" id=\"login_button\" name=\"action\" value=\"login\">Log In</button>
                    <br /><br />
                    <button type=\"submit\" id=\"forgot_password_button\" name=\"action\" value=\"forgot\">Forgot Password?</button>
                    <br /><br />
                    <button type=\"submit\" id=\"register_button\" name=\"action\" value=\"registerpage\">Don't have an account? Click here to join</button>
                    ";
                }
                ?>
                </form>
            </div>
        </div>

        <?php include('resources/templates/footer.php'); ?>
    </body>
</html>