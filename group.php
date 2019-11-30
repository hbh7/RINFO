<?php

// TODO: Handle when someone refuses to provide the required GET parameters

// Get GET data
//if ($_SERVER['REQUEST_METHOD'] == 'GET') {
if (isset($_GET['group_id'])) {
    $group_id = $_GET['group_id'];
}
//}

include_once 'db.php';

$group = dbGet("*", "r_groups", "group_id='" . $group_id . "'")[0];

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if (checkValidLogin()) {
        if ($action == "join") {
            dbPut("r_subscriptions", [getUserID(), $group_id]);
        } elseif ($action == "leave") {
            dbDelete("r_subscriptions", "user_id='" . getUserID() . "' AND group_id='" . $group_id . "'");
        }
    } else {
        header("Location: /login.php?redirectmsg=You must be logged in to do that!");
        die();
    }
}


$numSubscriptions = sizeof(dbGet("subscription_id", "r_subscriptions", "group_id='" . $group_id . "'"));
$numPosts = sizeof(dbGet("post_id", "r_posts", "group_id='" . $group_id . "'"));


?>

<html lang="en">

<head>
    <title> <?php echo $group["name"]; ?> </title>
    <?php include('resources/templates/head.php'); ?>
</head>

<body>
    <?php include('resources/templates/header.php'); ?>

    <div id="content">
        <div id="information">
            <div id="information_content" class="content">
                <h3 id="group_name"><?php echo $group["name"]; ?></h3>
                <img id="group_logo" src="<?php echo $group["logo"] ?>" alt="Group Logo">
                <div id="users_info">
                    <img id="users_logo" src="resources/images/users.png"></img>
                    <p id="Nusers" class="Ndetails"><?php echo $numSubscriptions; ?> users</p>
                </div>
                <div id="posts_info">
                    <img id="users_logo" src="resources/images/posts.png"></img>
                    <p id="Nposts" class="Ndetails"><?php echo $numPosts; ?> posts</p>
                </div>
                <form method="post">
                    <?php
                    if (checkValidLogin()) {
                        if (sizeof(dbGet("subscription_id", "r_subscriptions", "group_id='" . $group_id . "' AND user_id='" . getUserID() . "'")) > 0) {
                            echo "<button type=\"submit\" id=\"join\" name=\"action\" value=\"leave\" class=\"btn btn-light\">Leave</button>";
                        } else {
                            echo "<button type=\"submit\" id=\"join\" name=\"action\" value=\"join\" class=\"btn btn-light\">Join</button>";
                        }
                    } else {
                        echo "<button type=\"submit\" id=\"join\" name=\"action\" value=\"join\" class=\"btn btn-light\">Join</button>";
                    }
                    ?>
                </form>
                <p id="tagline"><?php echo $group["tagline"]; ?></p>
                <!-- <p><a href="https://rpis.ec/">Website Link</a></p>
                    <p><a href="https://cs.sympa.rpi.edu/wws/subscribe/rpisec">Mailing List</a></p>
                    TODO: Move this into the database, and make it so it can be changed online-->
            </div>
        </div>
        <div id="activity">
            <h2>Posts</h2>
            <!--https://bootsnipp.com/snippets/xrKXW-->
            <div id="activity_content" class="content">
                <?php
                $posts = dbGet("*", "r_posts", "group_id='" . $group_id . "'");
                echo "<ul class='timeline'>";
                foreach ($posts as $post) {
                    $name = dbGet("firstname, lastname", "r_users", "user_id=" . $post["user_id"]);
                    $attendances = dbGet("*", "r_attendances", "post_id='" . $post["post_id"] . "'");

                    echo "<li><div class='feed_item'>" .
                        "<span class='title'>" . $post["title"] . "</span><br />" .
                        "<span class='smaller' class='body'>" . $post["body"] . "</span><br />" .
                        "<span class='smaller' class='postauthor'> Posted by " . $name[0]["firstname"] . " " . $name[0]["lastname"] . "</span>" .
                        "<span class='smaller' class='postdate'> on " . $post["timestamp"] . "</span>" .
                        "<span class='smaller' class='attendances'> " . count($attendances) . " people attending </span>" .
                        "</div></li>";
                }
                echo "</ul>";
                ?>

            </div>
        </div>
    </div>

    <?php include('resources/templates/footer.php'); ?>
</body>

</html>