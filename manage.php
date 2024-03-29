<?php
include_once "db.php";

if (!checkValidLogin()) {
    header("Location: /login.php?displayPopup=You must be logged in to do that!");
    die();
}

if(isset($_POST["adminMessageCreate"]) || isset($_POST["adminMessageEdit"]) || isset($_POST["adminMessageDelete"])) {
    if (checkPermission(0, "admin")) {

        if (isset($_POST["adminMessageCreate"])) {

            date_default_timezone_set('America/New_York');
            $date = date('Y-m-d H:i:s', time());
            if(dbPut("r_alerts", [getUserID(), sanitizeInput($_POST["adminMessage"]), $date])) {
                $_GET["displayPopup"] = "Successfully saved new admin message!";
            } else {
                $_GET["displayPopup"] = "Error: Something went wrong while saving the message.";
            }

        } elseif (isset($_POST["adminMessageEdit"])) {

            date_default_timezone_set('America/New_York');
            $date = date('Y-m-d H:i:s', time());
            if(dbUpdate("r_alerts", "body='" . sanitizeInput($_POST['adminMessageBody']) . "'", "alert_id='" . sanitizeInput($_POST['adminMessageID']) . "'") &&
               dbUpdate("r_alerts", "date='" . sanitizeInput($date) . "'", "alert_id='" . sanitizeInput($_POST['adminMessageID']) . "'")) {
                $_GET["displayPopup"] = "Successfully saved admin message!";
            } else {
                $_GET["displayPopup"] = "Error: Something went wrong while saving the message.";
            }

        } elseif (isset($_POST["adminMessageDelete"])) {

            if(dbDelete("r_alerts", "alert_id='" . sanitizeInput($_POST["adminMessageID"]) . "'")) {
                $_GET["displayPopup"] = "Successfully deleted admin message!";
            } else {
                $_GET["displayPopup"] = "Error: Something went wrong while deleting the message.";
            }

        }

    } else {
        $_GET["displayPopup"] = "Error: You are not authorized to use admin messages!";
    }
} else if(isset($_POST["addPermission"]) || isset($_POST["removePermission"])) {
    if (checkPermission(0, "admin")) {

        if (isset($_POST["addPermission"])) {

            if(dbPut("r_permissions", [sanitizeInput($_POST["user_id"]), sanitizeInput($_POST["group_id"]), sanitizeInput($_POST["permission"])])) {
                $_GET["displayPopup"] = "Successfully added permission!";
            } else {
                $_GET["displayPopup"] = "Error: Something went wrong while adding the permission.";
            }

        } elseif (isset($_POST["removePermission"])) {

            if(dbDelete("r_permissions", "user_id=" . sanitizeInput($_POST["user_id"]) . " AND group_id=" . sanitizeInput($_POST["group_id"]) . " AND description='" . sanitizeInput($_POST["permission"]) . "'")) {
                $_GET["displayPopup"] = "Successfully deleted permission!";
            } else {
                $_GET["displayPopup"] = "Error: Something went wrong while deleting the permission.";
            }

        }

    } else {
        $_GET["displayPopup"] = "Error: You are not authorized to change permissions!";
    }
} else if(isset($_POST["submitName"])) {
    $changedFirst = false;
    $changedLast = false;
    $user = dbGet("*", "r_users", "user_id='" . getUserID() . "'")[0];
    if (isset($_POST["firstName"]) && $_POST["firstName"] != $user["firstname"]) {
        $firstName = $_POST["firstName"];
        // Validate input
        if(strlen($firstName) > 128) {
            $_GET["displayPopup"] = "First name is too long.";
            $valid = false;
        }
        if(!ctype_alpha($firstName)) {
            $_GET["displayPopup"] = "First name contains non letters";
            $valid = false;
        }
        if(strlen($firstName) == 0) {
            $_GET["displayPopup"] = "First name cannot be empty";
            $valid = false;
        }
        if (isset($valid) && $valid == false) {
            header("Location: /manage.php?displayPopup=" . $_GET["displayPopup"]);
            die();
        }
        // Update table
        if(dbUpdate("r_users", "firstname='" . $firstName . "'", "firstname='" . $user["firstname"] . "'")) {
            $_GET["displayPopup"] = "Successfully changed first name.";
            $changedFirst = true;    
        } else {
            $_GET["displayPopup"] = "Error: Something went wrong.";
        }
    }
    if (isset($_POST["lastName"]) &&  $_POST["lastName"] != $user["lastname"]) {
        $lastName = $_POST["lastName"];
        // Validate input
        if(strlen($lastName) > 128) {
            $_GET["displayPopup"] = "Last name is too long.";
            $valid = false;
        }
        if(!ctype_alpha($lastName)) {
            $_GET["displayPopup"] = "Last name contains non letters";
            $valid = false;
        }
        if(strlen($lastName) == 0) {
            $_GET["displayPopup"] = "Last name cannot be empty";
            $valid = false;
        }
        if (isset($valid) && $valid == false) {
            header("Location: /manage.php?displayPopup=" . $_GET["displayPopup"]);
            die();
        }
        $oldLastName = $lastName;
        // Update table
        if(dbUpdate("r_users", "lastname='" . $lastName . "'", "lastname='" . $user["lastname"] . "'")) {
            $_GET["displayPopup"] = "Successfully changed last name.";
            $changedLast = true;    
        } else {
            $_GET["displayPopup"] = "Error: Something went wrong.";
        }
    }
    if (isset($changedFirst) && isset($changedLast) && $changedFirst && $changedLast) {
        $_GET["displayPopup"] = "Successfully changed your entire name for some reason.";
    }
} else if(isset($_POST["submitEmail"])) {
    $user = dbGet("*", "r_users", "user_id='" . getUserID() . "'")[0];
    if (isset($_POST["email"]) &&  $_POST["email"] != $user["email"]) {
        $email = $_POST["email"];
        // Validate input
        if(strlen($email) > 256) {
            $_GET["displayPopup"] = "Email is too long.";
            $valid = false;
        }
        if(strlen($email) < 5) {
            $_GET["displayPopup"] = "Invalid Email";
            $valid = false;
        }
        if (isset($valid) && $valid == false) {
            header("Location: /manage.php?displayPopup=" . $_GET["displayPopup"]);
            die();
        }
        // Update table
        if(dbUpdate("r_users", "email='" . $email . "'", "email='" . $user["email"] . "'")) {
            $_GET["displayPopup"] = "Successfully changed email.";
        } else {
            $_GET["displayPopup"] = "Error: Something went wrong.";
        }
    }
} else if(isset($_POST["submitPassword"])) {
    $user = dbGet("*", "r_users", "user_id='" . getUserID() . "'")[0];
    $currentHash = $user["password"];
    if (isset($_POST["oldPass"]) && isset($_POST["newPass"]) && isset($_POST["reNewPass"])) {
        $newHash = password_hash($_POST["newPass"], PASSWORD_DEFAULT);
        // Validate input
        if (!password_verify($_POST["oldPass"], $user["password"])) {
            $_GET["displayPopup"] = "Invalid current password.";
            $valid = false;
        } else if(strlen($_POST["newPass"]) > 256) {
            $_GET["displayPopup"] = "Password is too long.";
            $valid = false;
        } else if(strlen($_POST["newPass"]) < 8) {
            $_GET["displayPopup"] = "Password is too short. Must be at least 8 characters in length.";
            $valid = false;
        }
        if(!password_verify($_POST["reNewPass"], $newHash)) {
            $_GET["displayPopup"] = "Passwords do not match.";
            $valid = false;
        }
        if (password_verify($_POST["oldPass"], $newHash)) {
            $_GET["displayPopup"] = "Please enter a NEW password.";     
            $valid = false;           
        }
        if (isset($valid) && $valid == false) {
            header("Location: /manage.php?displayPopup=" . $_GET["displayPopup"]);
            die();
        }
        // Update table
        if(dbUpdate("r_users", "password='" . $newHash . "'", "password='" . $user["password"] . "'")) {
            $_GET["displayPopup"] = "Successfully changed password.";
            // Update cookie
            $loginCookie = ["username" => $user["username"], "passwordHash" => $newHash, "firstname" => $user["firstName"], "lastname" => $user['lastName']];
            setcookie("login", json_encode($loginCookie), time() + (86400 * 30), "/");
            header("Location: /manage.php?displayPopup=");
            die();
        } else {
            $_GET["displayPopup"] = "Error: Something went wrong.";
        }
    }
}
?>

<html lang="en">
    <head>
        <?php $title = "Management Page"; ?>
        <?php include('resources/templates/head.php'); ?>
        <script src="/resources/scripts/manage.js" type="text/javascript" charset="utf-8" async defer></script>
    </head>
    <body>
        <?php
            include('resources/templates/header.php');
            $user = dbGet("*", "r_users", "user_id='" . getUserID() . "'")[0];
        ?>
        <!--A scroll to top button-->
        <button type="button" name="toTop" id="toTop" onclick="toTop();">Top</button>

        <div id="content">

            <!-- Left Navigation Sidebar -->
            <div id="information">
                <h2>Actions</h2>
                <div class="row">
                  <div class="col-12">
                    <div class="list-group" id="list-tab" role="tablist">
                      <a class="list-group-item list-group-item-action active" id="list-admin_messages-list" data-toggle="list" href="#list-admin_messages" role="tab" aria-controls="admin_messages">Admin Messages</a>
                      <a class="list-group-item list-group-item-action" id="list-your_groups-list" data-toggle="list" href="#list-your_groups" role="tab" aria-controls="your_groups">Groups You're Admin Of</a>
                      <a class="list-group-item list-group-item-action" id="list-user_permissions-list" data-toggle="list" href="#list-user_permissions" role="tab" aria-controls="user_permissions">User Permissions</a>
                      <a class="list-group-item list-group-item-action" id="list-your_account-list" data-toggle="list" href="#list-your_account" role="tab" aria-controls="your_account">Your Account</a>
                    </div>
                  </div>
                </div>
            </div>

            <!-- Right content box -->
            <div id="activity">
                <div id="activity_content" class="col-17">
                    <div class="tab-content" id="nav-tabContent">

                        <!-- Admin Messages right-side box -->
                        <div class="tab-pane fade show active" id="list-admin_messages" role="tabpanel" aria-labelledby="list-admin_messages-list">
                            <h2>Admin Messages</h2>
                            <!--TODO: editing and then clearing a message destroys formatting - fix this-->
                            <!--TODO: send this form somewhere and create the php necessary to put a message in the database-->
                            <form id="postAdminMessage" method="post">
                                <label style="width: 100%">
                                    <h4> Create a message </h4> <!-- TODO: Make this an allowed tag -->
                                    <textarea id="adminMessage" name="adminMessage" maxlength="1000" placeholder="Enter your message here..." required></textarea>
                                </label>
                                <br />
                                <input type='submit' name='adminMessageCreate' class='submitButton' value='Submit Message' />
                            </form>
                            <ul class="tab-content-ul" id="admin_messages">
                                <?php

                                $posts = dbGet("*", "r_alerts");

                                foreach ($posts as $post) {

                                    $name = dbGet("firstname, lastname", "r_users", "user_id=" . $post["user_id"]);

                                    echo "<li><div class='feed_info'>" .
                                        "<form id='postAdminMessage' method='post'>" .
                                        "<textarea class='body' name='adminMessageBody'>" . $post["body"] . "</textarea><br />" .
                                        "<span class='smallest' class='postauthor'> Posted by " . $name[0]["firstname"] . " " . $name[0]["lastname"] . "</span>" .
                                        "<span class='smallest' class='postdate'> on " . $post["timestamp"] . "</span><br />" .
                                        "<input type='hidden' name='adminMessageID' value='" . $post["alert_id"] . "'/>" .
                                        "<input type='submit' name='adminMessageEdit' class='submitButton' value='Edit Message' />" .
                                        "<input type='submit' name='adminMessageDelete' class='submitButton' value='Delete Message' />" .
                                        "</form></div></li>";
                                }

                                ?>
                            </ul>
                        </div>

                        <!-- Groups you're admin of right-side box -->
                        <div class="tab-pane fade" id="list-your_groups" role="tabpanel" aria-labelledby="list-your_groups-list">
                            <h2>Groups You're Admin Of</h2>
                            <ul class="tab-content-ul" id="your_groups-ul">
                                <a href="newgroup.php"> Create New Group </a>
                                <?php
                                    $permissions = dbGet("group_id", "r_permissions", "user_id='" . getUserID() . "' AND description='admin'");
                                    foreach ($permissions as $permission) {

                                        if($permission["group_id"] == 0) {
                                            continue;
                                        }

                                        $groupName = dbGet("name", "r_groups", "group_id='" . $permission["group_id"] . "'")[0]["name"];
                                        $tagline = dbGet("tagline", "r_groups", "group_id='" . $permission["group_id"] . "'")[0]["tagline"];
                                        $visibility = dbGet("visibility", "r_groups", "group_id='" . $permission["group_id"] . "'")[0]["visibility"];
                                        echo "<li class='admin_group_name'>";
                                        echo <<<HTML
                                        <form action="/newgroup.php" method="post">
                                            <h4>{$groupName}</h4>
                                            <input type="hidden" name="name" value="{$groupName}">
                                            <input type="hidden" name="tagline" value="{$tagline}">
                                            <input type="hidden" name="visibility" value="{$visibility}">
                                            <input type="hidden" name="edit" value="true">
                                            <input type="hidden" name="group_id" value="{$permission['group_id']}">
                                            <input type="submit" name="editGroup" value="Edit Group">
                                        </form>
HTML;
                                    }
                                ?>
                            </ul>
                        </div>

                        <!-- User Permissions right-side box -->
                        <div class="tab-pane fade" id="list-user_permissions" role="tabpanel" aria-labelledby="list-user_permissions-list">
                            <h2>User Permissions</h2>
                            <form action="" method="post">
                                <label> User ID
                                    <input type="text" name="user_id">
                                </label>
                                <label> Group ID
                                    <input type="text" name="group_id">
                                </label>
                                <label> Permission
                                    <input type="text" name="permission">
                                </label>
                                <input type='submit' name='addPermission' class='submitButton' value='Add Permission' />
                                <input type='submit' name='removePermission' class='submitButton' value='Remove Permission' />
                            </form>
                            <!-- <input id="searchtextbox" type="text" name="userIdentifier" placeholder="Look up user..." size="35px" />
                            <div id="results"></div> -->
                        </div>

                        <!-- Your account right-side box -->
                        <div class="tab-pane fade" id="list-your_account" role="tabpanel" aria-labelledby="list-your_account-list">
                            <h2>Your Account</h2>
                            <form action="" method="post" class="yourAccount">
                                <h4>Edit Profile Picture</h4>
                                <input type="file" name="pic" accept="image/*">
                                <br />
                                <input type="submit" name="submitPicture" value="Change Picture">
                                <input type="submit" name="deletePicture" value="Delete Current Picture">
                            </form>
                            <form action="" method="post" class="yourAccount">
                                <h4>Change Name</h4>
                                <input type="text" name="firstName" value="<?php echo $user["firstname"];?>">
                                <br />
                                <input type="text" name="lastName" value="<?php echo $user["lastname"];?>">
                                <br />
                                <input type="submit" name="submitName" value="Change Name">
                            </form>
                            <form action="" method="post" class="yourAccount">
                                <h4>Change Email</h4>
                                <input type="email" name="email" value="<?php echo $user["email"];?>">
                                <br />
                                <input type="submit" name="submitEmail" value="Change Email">
                            </form>
                            <form action="" method="post" class="yourAccount">
                                <h4>Change Password</h4>
                                <input type="password" name="oldPass" placeholder="Old Password">
                                <br />
                                <input type="password" name="newPass" placeholder="New Password">
                                <br />
                                <input type="password" name="reNewPass" placeholder="Re-enter new password">
                                <br />
                                <input type="submit" name="submitPassword" value="Change Password">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include('resources/templates/footer.php'); ?>
    </body>
</html>
