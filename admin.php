<html lang="en">
    <head>
        <?php $title = "Administration Homepage"; ?>
        <?php include('resources/templates/head.php'); ?>
        <script src="/resources/scripts/admin.js" type="text/javascript" charset="utf-8" async defer></script>
    </head>
    <body>
        <?php include('resources/templates/header.php'); ?>
        <!--A scroll to top button-->
        <button type="button" name="toTop" id="toTop" onclick="toTop();">Top</button>
        <div id="content">
            <div id="information">
                <h2>Actions</h2>

                <div class="row">
                  <div class="col-12">
                    <div class="list-group" id="list-tab" role="tablist">
                      <a class="list-group-item list-group-item-action active" id="list-admin_messages-list" data-toggle="list" href="#list-admin_messages" role="tab" aria-controls="admin_messages">Admin Messages</a>
                      <a class="list-group-item list-group-item-action" id="list-your_groups-list" data-toggle="list" href="#list-your_groups" role="tab" aria-controls="your_groups">Your Groups</a>
                      <a class="list-group-item list-group-item-action" id="list-user_permissions-list" data-toggle="list" href="#list-user_permissions" role="tab" aria-controls="user_permissions">User Permissions</a>
                      <a class="list-group-item list-group-item-action" id="list-your_account-list" data-toggle="list" href="#list-your_account" role="tab" aria-controls="your_account">Your Account</a>
                    </div>
                  </div>
                </div>

                <!--
                <div id="information_content" class="content">
                    <form method="get" action="#">
                    <ul id="actions">
                        <li class="action"><input class="actionInp" type="submit" name="notifications" value="Notifications"></li>
                        <li class="action"><input class="actionInp" type="submit" name="admin_messages" value="Admin Messages"></li>
                        <li class="action"><input class="actionInp" type="submit" name="your_groups" value="Your groups"></li>
                        <li class="action"><input class="actionInp" type="submit" name="user_permissions" value="User Permissions"></li>
                        <li class="action"><input class="actionInp" type="submit" name="homepage_carousel" value="Homepage Carousel"></li>
                    </ul>
                    <img id="group_logo" src="/resources/images/logo.png" alt="Group Logo">
                    <h3 class="group_name">RPI Admins</h3>
                    <p id="Nusers">13 users</p>
                    <p id="Nposts">12 posts</p>
                    <p id="Join">Edit Front Page Banner</p>
                    <p>This is the official group for the RPI administration.</p>
                    <p><a href="https://rpi.edu/">Website Link</a></p>
                    <p><a href="https://lists.sympa.rpi.edu/wws/info/morningmail">Mailing List</a></p>
                </div>
                -->
            </div>
            <div id="activity">
                <div id="activity_content" class="col-17">
                    <div class="tab-content" id="nav-tabContent">

                        <div class="tab-pane fade" id="list-admin_messages" role="tabpanel" aria-labelledby="list-admin_messages-list">
                            <h2>Admin Messages</h2>
                            <!--TODO: send this form somewhere and create the php necessary to put a message in the database-->
                            <h4>Create a message</h4>
                            <form id="postAdminMessage">
                                <!--maxlength is arbitrary for now
                                    heck, all attribute names are subject to change, EXCEPT CLASSES AND IDs-->
                                <textarea name="message" maxlength="560" placeholder="Enter your message here..." required></textarea>
                                <br />
                                <button class="submitButton" id="createMessage" type="button" onclick="submitMessage(this);">Submit Message</button>
                            </form>
                            <ul class="tab-content-ul" id="admin_messages">
                                <?php

                                $posts = dbGet("*", "r_alerts");

                                echo "<ul class='timeline'>";
                                foreach ($posts as $post) {

                                    $name = dbGet("firstname, lastname", "r_users", "user_id=" . $post["user_id"]);

                                    echo "<li><div class='feed_item'><div class='feed_info'>" .
                                        "<span class='smaller' class='body'>" . $post["body"] . "</span><br />" .
                                        "<span class='smallest' class='postauthor'> Posted by " . $name[0]["firstname"] . " " . $name[0]["lastname"] . "</span>" .
                                        "<span class='smallest' class='postdate'> on " . $post["timestamp"] . "</span><br />" .
                                        "<button type='button' onclick='makeEditMessage(this);' class='submitButton' id='editMessage'>Edit Message</button>" .
                                        "<button type='button' onclick='deleteMessage(this);' class='submitButton' id='deleteMessage''>Delete Message</button>" .
                                        "</div>";

                                    echo "</li>";
                                }
                                echo "</ul>";

                                ?>
                            </ul>
                        </div>


                        <div class="tab-pane fade" id="list-your_groups" role="tabpanel" aria-labelledby="list-your_groups-list">
                            <h2>Your Groups</h2>
                            <ul class="tab-content-ul" id="your_groups-ul">
                                <form method="get" action="/newgroup.php" id="createNewGroup">
                                    <input type="submit" name="newGroup" value="Create New Group">
                                </form>
                                <?php
                                    //TODO: replace 4 \/ with the number of groups that the current user is an admin of
                                    for ($i = 0; $i < 7 ; $i++) {
                                        echo "<li class='admin_group_name'>";
                                        echo <<<HTML
                                        <form action="/newgroup.php" method="get">
                                            <!--TODO: load group names from the database-->
                                            <h4>Group {$i}</h4>
                                            <!--TODO: name and value attributes subject to...a better name (php variables can be used in heredoc when enclosed by curly braces!)-->
                                            <input type="submit" name="editGroup" value="Edit Group {$i}">
                                        </form>
HTML;
                                    }
                                ?>
                            </ul>
                        </div>


                        <div class="tab-pane fade" id="list-user_permissions" role="tabpanel" aria-labelledby="list-user_permissions-list">
                            <h2>User Permissions</h2>
                            <!--TODO: implement a (watered-down) user search - nothing fancy required
                                    because there is no way im listing out every single user and forcing an admin to spend an hour scrolling or forcing them to use ctrl+f (first option is torture, second option is tacky)
                                it's passable with the admin messages because there shouldnt be that many of them anyway and theoretically they are all important, but there's no cap on the potential number of users-->
                            <input type="text" name="userIdentifier" placeholder="Enter username or email address" size="35px"></input>
                        </div>


                        <div class="tab-pane fade" id="list-your_account" role="tabpanel" aria-labelledby="list-your_account-list">
                            <h2>Your Account</h2>
                            <!--TODO: implement change profile pic, change password, username-->
                            <span>bleh here's some text</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include('resources/templates/footer.php'); ?>
    </body>
</html>
