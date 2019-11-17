<?php

// Get GET data
//if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(isset( $_GET['group_id'])) {
        $group_id = $_GET['group_id'];
    }
//}

include_once 'db.php';

$group = dbGet("*", "r_groups", "group_id='" . $group_id . "'")[0];

if (isset( $_POST['action'])) {
    $action = $_POST['action'];
    if($action == "join") {
        if(checkValidLogin()) {
            dbPut("r_subscriptions", [getUserID(), $group_id]);
        } else {
            header("Location: /login.php?redirectmsg=You must be logged in to do that!");
            die();
        }
    } elseif ($action == "leave") {
        dbDelete("r_subscriptions", "user_id='" . getUserID() . "' AND group_id='" . $group_id . "'");
    }
}


$numSubscriptions = sizeof(dbGet("subscription_id", "r_subscriptions", "group_id='" . $group_id . "'"));
$numPosts = sizeof(dbGet("post_id", "r_posts", "group_id='" . $group_id . "'"));


?>

<html lang="en">
    <head>
        <?php include('resources/templates/head.php'); ?>
        <title> <?php echo $group["name"]; ?> </title>
    </head>
    <body>
        <?php include('resources/templates/header.php'); ?>

        <div id="content">
            <div id="information">
                <h2>Information</h2>
                <div id="information_content" class="content">
                    <img id="group_logo" src="<?php echo $group["logo"] ?>" alt="Group Logo">
                    <h3 id="group_name"><?php echo $group["name"]; ?></h3>
                    <p id="Nusers"><?php echo $numSubscriptions; ?> users</p>
                    <p id="Nposts"><?php echo $numPosts; ?> posts</p>
                    <form method="post">
                        <?php
                        if(checkValidLogin()) {
                            if (sizeof(dbGet("subscription_id", "r_subscriptions", "group_id='" . $group_id . "' AND user_id='" . getUserID() . "'")) > 0) {
                                echo "<button type=\"submit\" id=\"join\" name=\"action\" value=\"leave\">Leave</button>";
                            } else {
                                echo "<button type=\"submit\" id=\"join\" name=\"action\" value=\"join\">Join</button>";
                            }
                        } else {
                            echo "<button type=\"submit\" id=\"join\" name=\"action\" value=\"join\">Join</button>";
                        }
                        ?>
                    </form>
                    <p><?php echo $group["tagline"]; ?></p>
                    <!-- <p><a href="https://rpis.ec/">Website Link</a></p>
                    <p><a href="https://cs.sympa.rpi.edu/wws/subscribe/rpisec">Mailing List</a></p>
                    TODO: Move this into the database, and make it so it can be changed online-->
                </div>
            </div>
            <div id="activity">
                <h2>Activities</h2>
                <div id="activity_content" class="content">
                    <?php
                    $posts = dbGet("*", "r_posts", "group_id='" . $group_id . "'");
                    foreach ($posts as $post) {
                        $name = dbGet("firstname, lastname", "r_users", "user_id=" . $post["user_id"]);

                        echo "<div class='activity'>" .
                            "<span class='title'>" . $post["title"] . "</span><br />" .
                            "<span class='body'>" . $post["body"] . "</span><br />" .
                            "<span class='postauthor'> Posted by " . $name[0]["firstname"] . " " . $name[0]["lastname"] . "</span>" .
                            "<span class='postdate'> on " . $post["timestamp"] . "</span>" .
                            "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>

        <?php include('resources/templates/footer.php'); ?>
    </body>
</html>