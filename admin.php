<html lang="en">
    <head>
        <?php include('resources/templates/head.php'); ?>
        <link rel="stylesheet" type="text/css" href="/resources/styles/styles-admin.css">
        <title> Administration Homepage </title>
        <script src="/resources/scripts/admin.js" type="text/javascript" charset="utf-8" async defer></script>
    </head>
    <body>
        <?php include('resources/templates/header.php'); ?>
        <div id="content">
            <div id="information">
                <h2>Actions</h2>

                <div class="row">
                  <div class="col-12">
                    <div class="list-group" id="list-tab" role="tablist">
                      <a class="list-group-item list-group-item-action active" id="list-notifications-list" data-toggle="list" href="#list-notifications" role="tab" aria-controls="notifications">Notifications</a>
                      <a class="list-group-item list-group-item-action" id="list-admin_messages-list" data-toggle="list" href="#list-admin_messages" role="tab" aria-controls="admin_messages">Admin Messages</a>
                      <a class="list-group-item list-group-item-action" id="list-your_groups-list" data-toggle="list" href="#list-your_groups" role="tab" aria-controls="your_groups">Your Groups</a>
                      <a class="list-group-item list-group-item-action" id="list-user_permissions-list" data-toggle="list" href="#list-user_permissions" role="tab" aria-controls="user_permissions">User Permissions</a>
                      <a class="list-group-item list-group-item-action" id="list-foo-list" data-toggle="list" href="#list-foo" role="tab" aria-controls="foo">Foo</a>
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
                        <div class="tab-pane fade show active" id="list-notifications" role="tabpanel" aria-labelledby="list-notifications-list">
                            <h2>Notifications</h2>
                            <ul class="tab-content-ul">
                                <!--TODO: Someone do me a favor and generate this from the database-->
                                <li>There will be no classes on Jan. 17th due to a snow day! Nah jk this is RPI. You thought we would show mercy? By executive decree, today only, all 8am classes shall start at 8:06am.</li>
                                <li>RPI ALERT: A student on 15th street was approached by a 2'4" man wearing an Elmo costume. Witnesses say, "It may have been an infant on Halloween"</li>
                            </ul>
                        </div>


                        <div class="tab-pane fade" id="list-admin_messages" role="tabpanel" aria-labelledby="list-admin_messages-list">
                            <h2>Admin Messages</h2>
                            <!--TODO: send this form somewhere and create the php necessary to put a message in the database-->
                            <h4>Create a message</h4>
                            <form method="post" action="#" id="postAdminMessage">
                                <!--maxlength is arbitrary for now
                                    heck, all attribute names are subject to change, EXCEPT CLASSES AND IDs-->
                                <textarea name="message" maxlength="560" placeholder="Enter your message here..." required></textarea>
                                <br />
                                <input class="submitButton" type="submit" name="submit" value="Submit">
                            </form>
                            <ul class="tab-content-ul" id="admin_messages">
                                <?php
                                    //TODO: replace the for loop condition with one that reflects the number of messages in the database
                                    for ($i=0; $i < 4 ; $i++) {
                                        echo "<li>";
                                        echo "$i $i $i $i $i <br />messages generated from the database. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
                                        //TODO: make an if condition to check if a particular message was posted by the currently logged in user
                                        if (/*REPLACE THE 1 IN THIS IF CONDITION*/1) {
                                            echo <<<HTML
                                            <br />
                                            <button type="button" onclick="makeEditMessage(this);" class="submitButton" id="editMessage">Edit Message</button>
                                            <button type="button" onclick="deleteMessage(this);" class="submitButton" id="deleteMessage">Delete Message</button>
HTML;
                                        }
                                    }
                                ?>
                            </ul>
                        </div>


                        <div class="tab-pane fade" id="list-your_groups" role="tabpanel" aria-labelledby="list-your_groups-list">
                            <h2>Your Groups</h2>
                            <span>bleh here's some text</span>
                        </div>


                        <div class="tab-pane fade" id="list-user_permissions" role="tabpanel" aria-labelledby="list-user_permissions-list">
                            <h2>User Permissions</h2>
                            <span>bleh here's some text</span>
                        </div>


                        <div class="tab-pane fade" id="list-foo" role="tabpanel" aria-labelledby="list-foo-list">
                            <h2>Foo</h2>
                            <span>bleh here's some text</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include('resources/templates/footer.php'); ?>
    </body>
</html>
