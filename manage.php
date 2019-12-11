<?php

include_once "db.php";

if(isset($_POST["adminMessageCreate"]) || isset($_POST["adminMessageEdit"]) || isset($_POST["adminMessageDelete"])) {
    if(checkValidLogin()) {
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
    } else {
        header("Location: /login.php?displayPopup=You must be logged in to do that!");
        die();
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
                            <!--TODO: implement a (watered-down) user search - nothing fancy required
                                    because there is no way im listing out every single user and forcing an admin to spend an hour scrolling or forcing them to use ctrl+f (first option is torture, second option is tacky)
                                it's passable with the admin messages because there shouldnt be that many of them anyway and theoretically they are all important, but there's no cap on the potential number of users-->
                            <!--TODO: implement a way to search for partial names as well (right now only usernames are supported)-->
                            <input id="searchtextbox" type="text" name="userIdentifier" placeholder="Enter username" size="35px"></input>
                            <div id="results"></div>
                        </div>

                        <!-- Your account right-side box -->
                        <div class="tab-pane fade" id="list-your_account" role="tabpanel" aria-labelledby="list-your_account-list">
                            <h2>Your Account</h2>
                            <!--TODO: implement change profile pic, change password, username-->
                            <form action="" method="post" class="yourAccount">
                                <h4>Edit Profile Picture</h4>
                                <input type="file" name="pic" accept="image/*">
                                <br />
                                <input type="submit" name="submitPicture" value="Change Picture">
                                <input type="submit" name="deletePicture" value="Delete Current Picture">
                            </form>
                            <form action="" method="post" class="yourAccount">
                                <h4>Change Name</h4>
                                <input type="text" name="firstName" value="<?php echo $firstname;?>">
                                <br />
                                <input type="text" name="lastName" value="<?php echo $lastname;?>">
                                <br />
                                <input type="submit" name="submitName" value="Change Name">
                            </form>
                            <form action="" method="post" class="yourAccount">
                                <h4>Change Email</h4>
                                <input type="text" name="email" value="<?php echo $user["email"];?>">
                                <br />
                                <input type="submit" name="submitEmail" value="Change Email">
                            </form>
                            <form action="" method="post" class="yourAccount">
                                <h4>Change Password</h4>
                                <input type="text" name="oldPass" placeholder="Old Password">
                                <br />
                                <input type="text" name="newPass" placeholder="New Password">
                                <br />
                                <input type="text" name="newPass" placeholder="Re-enter new password">
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
