<?php

include_once 'db.php';

// Get POST data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = sanitizeInput($_POST['password']);
    if (isset($_POST['rePassword']))
        $rePassword = sanitizeInput($_POST['rePassword']);
    $action = sanitizeInput($_POST['action']);

    if(isset($_POST['firstname']))
        $firstname = sanitizeInput($_POST['firstname']);
    if(isset($_POST['lastname']))
        $lastname = sanitizeInput($_POST['lastname']);
    if(isset($_POST['email']))
        $email = sanitizeInput($_POST['email']);
}

if(isset($action)) {

    $errors = [];

    $users = dbGet("user_id, username, password, firstname, lastname, enabled", "r_users", "username='" . $username . "'");

    if ($action == "register") {

        // Validate data:
        // First Name
        if(strlen($firstname) > 128) {
            $result = [false, "Failed to register: Some items require your attention"];
            $errors["firstname"] = "First name is too long";
        }
        if(!ctype_alpha($firstname)) {
            $result = [false, "Failed to register: Some items require your attention"];
            $errors["firstname"] = "First name contains non letters";
        }
        if(strlen($firstname) == 0) {
            $result = [false, "Failed to register: Some items require your attention"];
            $errors["firstname"] = "First name cannot be empty";
        }
        
        // Last Name
        if(strlen($lastname) > 128) {
            $result = [false, "Failed to register: Some items require your attention"];
            $errors["lastname"] = "Last name is too long";
        }
        if(!ctype_alpha($lastname)) {
            $result = [false, "Failed to register: Some items require your attention"];
            $errors["lastname"] = "Last name contains non letters";
        }
        if(strlen($lastname) == 0) {
            $result = [false, "Failed to register: Some items require your attention"];
            $errors["lastname"] = "Last name cannot be empty";
        }
        
        // Email
        if(strlen($email) > 256) {
            $result = [false, "Failed to register: Some items require your attention"];
            $errors["email"] = "eMail is too long";
        }
        if(strlen($email) < 5) {
            $result = [false, "Failed to register: Some items require your attention"];
            $errors["email"] = "eMail cannot be that short";
        }
        
        // Username
        if(strlen($username) > 32) {
            $result = [false, "Failed to register: Some items require your attention"];
            $errors["username"] = "Username is too long";
        }
        if(!ctype_alnum($username)) {
            $result = [false, "Failed to register: Some items require your attention"];
            $errors["username"] = "Username contains non alphanumeric characters";
        }
        if(strlen($username) == 0) {
            $result = [false, "Failed to register: Some items require your attention"];
            $errors["username"] = "Username cannot be empty";
        }
        
        // Password
        if(strlen($password) > 256) {
            $result = [false, "Failed to register: Some items require your attention"];
            $errors["password"] = "Password is too long";
        }
        if(strlen($password) < 8) {
            $result = [false, "Failed to register: Some items require your attention"];
            $errors["password"] = "Password cannot be bad (too short, minimum 8 characters)";
        }
        if($password != $rePassword) {
            $result = [false, "Failed to register: Some items require your attention"];
            $errors["password"] = "Passwords don't match";
        }

        // If everything validated:
        if(sizeof($errors) == 0) {

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
        } else {
            $action = "registerpage";
        }

    } elseif ($action == "login") {

        if(sizeof($users) == 0) {
            // Invalid user
            $result = [false, "Failed to log in: Invalid username and/or password!"];
        } else {

            // Make sure they're not disabled/banned
            if($users[0]["enabled"] == false) {
                $result = [false, "Failed to log in: Account is disabled!"];

            } else {

                $dbhash = $users[0]['password'];
                if (password_verify($password, $dbhash)) {
                    // Success!

                    // Setting cookie
                    $loginCookie = ["username" => $username, "passwordHash" => $dbhash, "firstname" => $users[0]['firstname'], "lastname" => $users[0]['lastname']];
                    // Cookie expires after 1 month
                    setcookie("login", json_encode($loginCookie), time() + (86400 * 30), "/");
                    // Redirect to homepage
                    header("Location: /index.php");
                    die();
                } else {
                    // Invalid credentials
                    $result = [false, "Failed to log in: Invalid username and/or password!"];
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
                        echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>" .$result[1]."<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
                    }
                    ?>
                </p>
                <form method="post" id="login_box" action="login.php">
                <?php
                if(isset($action) && $action == "registerpage") {
                    echo "<label for='firstname'>First Name </label><input type='text' name='firstname' id='firstname' value='";
                    if (isset($firstname)) echo $firstname;
                    echo "' placeholder='SIS'><br/>";
                    if(isset($errors["firstname"])) { echo "<span style='color: red'> " . $errors["firstname"] . "</span>"; }
                    echo "<br /><label for='lastname'>Last Name </label><input type='text' name='lastname' id='lastname' value='";
                    if (isset($lastname)) echo $lastname;
                    echo "' placeholder='Man'><br />";
                    if(isset($errors["lastname"])) { echo "<span style='color: red'> " . $errors["lastname"] . "</span>"; }
                    echo "<br /><label for='email'>eMail </label><br><input type='email' name='email' id='email' value='";
                    if (isset($email)) echo $email;
                    echo "' placeholder='sisman@rpi.edu'><br />";
                    if(isset($errors["email"])) { echo "<spanp style='color: red'> " . $errors["email"] . "</span>"; }
                    echo "<br /><label for='username'>Username </label><input type='text'  name='username' id='username' value='" . $username . "' placeholder='sisman'><br />";
                    if(isset($errors["username"])) { echo "<span style='color: red'> " . $errors["username"] . "</span>"; }
                    echo "<br /><label for='password'>Password (Don't forget it!)</label><input type='password' name='password' id='password' value='' placeholder='************'><br />";
                    echo "<br /><label for='password'>Re-Type Password </label><input type='password' name='rePassword' id='rePassword' value='' placeholder='************'><br />";
                    if(isset($errors["password"])) { echo "<span style='color: red'> " . $errors["password"] . "</span>"; }
                    echo "<br /><button type='submit' id='register_button' name='action' value='register'>Register</button>";

                } else {
                    echo "
                    <label for='username'>Username </label><input type='text' name='username' id='username' value='' placeholder='sisman'>
                    <br /><br />
                    <label for='password'>Password </label><input type='password' name='password' id='password' value='' placeholder='************'>
                    <br /><br />
                    <button type='submit' id='login_button' class='btn btn-secondary' name='action' value='login'>Log In</button>
                    <br /><br />
                    <button type='submit' id='forgot_password_button' class='btn btn-secondary' name='action' value='forgot'>Forgot Password?</button>
                    <br /><br />
                    <button type='submit' id='register_button' class='btn btn-secondary' name='action' value='registerpage'>Don't have an account? </br>Click here to join</button>
                    ";
                }
                ?>
                </form>
            </div>
        </div>

        <?php include('resources/templates/footer.php'); ?>
    </body>
</html>
