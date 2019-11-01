<html>
    <head>
        <?php include('resources/templates/head.php'); ?>
        <title> Administration Homepage </title>
    </head>
    <body>
        <?php include('resources/templates/header.php'); ?>
        Welcome!

        <div id="content">
            <div id="information">
                <h2>Information</h2>
                <div id="information_content" class="content">
                    <img id="group_logo" src="/resources/images/logo.png" alt="Group Logo">
                    <h3 class="group_name">RPI Admins</h3>
                    <p id="Nusers">13 users</p>
                    <p id="Nposts">12 posts</p>
                    <p id="Join">Edit Front Page Banner</p>
                    <p>This is the official group for the RPI administration.</p>
                    <p><a href="https://rpi.edu/">Website Link</a></p>
                    <p><a href="https://lists.sympa.rpi.edu/wws/info/morningmail">Mailing List</a></p>
                </div>
            </div>
            <div id="activity">
                <h2>Notifications</h2>
                <div id="activity_content" class="content">
                    <ul>
                        <li>There will be no classes on Jan. 17th due to a snow day!</li>
                        <li>RPI ALERT: A student on 15th street was apprached by a man in an elmo costume. Witnesses say, "It may have been Halloween"</li>
                    </ul>
                </div>
            </div>
        </div>

        <?php include('resources/templates/footer.php'); ?>
    </body>
</html>