<?php

// TODO: Handle when someone refuses to provide the required GET parameters

// Get GET data
//if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(isset( $_GET['post_id'])) {
        $post_id = $_GET['post_id'];
    }
//}

include_once 'db.php';

$post = dbGet("*", "r_posts", "post_id='" . $post_id . "'")[0];

if (isset( $_POST['action'])) {
    if($_POST['action'] == "comment") {
        if (checkValidLogin()) {
            // TODO: Comment code
            // something like dbPut( comments, [user id, body, etc] )
        } else {
            header("Location: /login.php?redirectmsg=You must be logged in to do that!");
            die();
        }
    } elseif($_POST['action'] == "attend") {
        if (checkValidLogin()) {
            // TODO: attendance code
            // something like dbPut( attendances, [user id, etc] )
        } else {
            header("Location: /login.php?redirectmsg=You must be logged in to do that!");
            die();
        }
    }
}


?>

<html lang="en">
    <head>
        <?php include('resources/templates/head.php'); ?>
        <title> <?php echo $post["title"]; ?> </title>
    </head>
    <body>
        <?php include('resources/templates/header.php'); ?>

        <div id="content">
            <div id="activity">
                <h2>Posts</h2>
                <div id="activity_content" class="content">
                    <?php
                    $comments = dbGet("*", "r_comments", "post_id='" . $post_id . "'");
                    foreach ($comments as $comment) {
                        $name = dbGet("firstname, lastname", "r_users", "user_id=" . $comment["user_id"]); // Could do with DB but too much work

                        echo "<div class='activity'>" .
                            "<span class='body'>" . $comment["body"] . "</span><br />" .
                            "<span class='postauthor'> Posted by " . $name[0]["firstname"] . " " . $name[0]["lastname"] . "</span>" .
                            "<span class='postdate'> on " . $comment["timestamp"] . "</span>" .
                            "</div>";
                    }
                    ?>
                </div>
                <?php
                $attendances = dbGet("*", "r_attendances", "post_id='" . $post_id . "'");
                echo "<p> " . sizeof($attendances) . " people attending </p>"
                ?>
            </div>
        </div>

        <?php include('resources/templates/footer.php'); ?>
    </body>
</html>