<html lang="en">

<head>
    <?php include('resources/templates/head.php'); ?>
    <title> ~~DYNAMIC TITLE NAME~~ </title>
</head>

<body>
    <?php include('resources/templates/header.php'); ?>
    <?php include_once 'db.php'; ?>
        <div class="display">
            <p id="banner" class="activity">Example Admin Notification</p>
            <?php
            // Something like this should probably end up in its own function at some point, since its
            //      likely going to be the same post generator on each page.
            // I'm gonna tentatively veto this idea - they are not the same generators and creating php to
            //      dynamically generate php that dynamically generates html is one too many layers of abstraction for me
            // also why don't you use heredoc? it looks about 4.5 orders of magnitude nicer :)
            $posts = dbGet("*", "r_posts");
            foreach ($posts as $post) {
                $name = dbGet("firstname, lastname", "r_users", "user_id=" . $post["user_id"]);
                $attendances = dbGet("*", "r_attendances", "post_id='" . $post["post_id"] . "'");

                echo "<div class='activity'>" .
                    "<span class='title'>" . $post["title"] . "</span><br />" .
                    "<span class='body'>" . $post["body"] . "</span><br />" .
                    "<span class='postauthor'> Posted by " . $name[0]["firstname"] . " " . $name[0]["lastname"] . "</span>" .
                    "<span class='postdate'> on " . $post["timestamp"] . "</span>" .
                    "<span class='attendances'> " . count($attendances) . " people attending </span>" .
                    "</div>";
            }
            ?>   

    <?php include('resources/templates/footer.php'); ?>
</body>

</html>