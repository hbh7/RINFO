<html lang="en">
    <head>
        <?php include('resources/templates/head.php'); ?>
        <link rel="stylesheet" type="text/css" href="/resources/styles/styles-admin.css">
        <title> Administration Homepage </title>
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
                            <span>bleh here's some text</span>
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
