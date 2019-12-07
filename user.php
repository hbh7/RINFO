<?php

include_once 'db.php';

// Get GET data
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['user_id'])) {
        $user_id = sanitizeInput($_GET['user_id']);
    } else {
        echo "Error: You need to provide the correct parameter(s) for this page to work";
        die();
    }
} else {
    echo "Error: You need to provide the correct parameter(s) for this page to work";
    die();
}

$user = dbGet("*", "r_users", "user_id='" . $user_id . "'")[0];
// TODO: Verify valid user ID

if (isset($_POST['action'])) {
    $action = sanitizeInput($_POST['action']);
    if (checkValidLogin()) {
        if ($action == "join") {
            dbPut("r_subscriptions", [getUserID(), $user_id]);
        } elseif ($action == "leave") {
            dbDelete("r_subscriptions", "user_id='" . getUserID() . "' AND user_id='" . $user_id . "'");
        }
    } else {
        header("Location: /login.php?redirectmsg=You must be logged in to do that!");
        die();
    }
}

$numSubscriptions = sizeof(dbGet("subscription_id", "r_subscriptions", "user_id='" . $user_id . "'"));
$numPosts = sizeof(dbGet("post_id", "r_posts", "user_id='" . $user_id . "' AND group_id='0'"));
// Currently just looking for posts directly to their profile, not for posts they make to other groups


?>

<html lang="en">

<head>
    <?php $title = $user["firstname"] . " " . $user["lastname"] . "'s Profile"; ?>
    <?php include('resources/templates/head.php'); ?>
    <link rel="stylesheet" type="text/css" href="/resources/styles/styles-user.css">
</head>

<body>
    <?php include('resources/templates/header.php'); ?>

    <div id="content">
        <div id="information">
            <h2>Information</h2>
            <div id="information_content" class="content">
                <h3 id="name"><?php echo $user["firstname"] . " " . $user["lastname"]; ?></h3>
                <h3 id="username"><?php echo $user["username"]; ?></h3>
                <h3 id="email"><?php echo $user["email"]; ?></h3>
                    <!-- <p id="Nusers"><?php echo $numSubscriptions; ?> users</p>
                <p id="Nposts"><?php echo $numPosts; ?> posts</p> -->
                <!-- TODO: Find out what this is about, and delete the stuff below)
                    <form method="post">
                        <?php
                        if (getUserID() != $user_id) {
                            if (checkValidLogin()) {
                                if (sizeof(dbGet("subscription_id", "r_subscriptions", "user_id='" . $user_id . "' AND user_id='" . getUserID() . "'")) > 0) {
                                    echo "<button type=\"submit\" id=\"join\" name=\"action\" value=\"leave\">Unfollow</button>";
                                } else {
                                    echo "<button type=\"submit\" id=\"join\" name=\"action\" value=\"join\">Follow</button>";
                                }
                            } else {
                                echo "<button type=\"submit\" id=\"join\" name=\"action\" value=\"join\">Follow</button>";
                            }
                        }
                        ?>
                    </form>
                    <div class="row">
                        <div class="col-12">
                            <div class="list-group" id="list-tab" role="tablist">
                                <a class="list-group-item list-group-item-action active" id="list-your_groups-list" data-toggle="list" href="#list-your_groups" role="tab" aria-controls="your_groups">Posts</a>
                                <a class="list-group-item list-group-item-action" id="list-your_groups-list" href="newpost.php?destination=self" role="tab" aria-controls="add_post">Add Post</a>
                            </div>
                        </div>
                    </div>
                    <!-- <p><a href="https://rpis.ec/">Website Link</a></p>
                    <p><a href="https://cs.sympa.rpi.edu/wws/subscribe/rpisec">Mailing List</a></p>
                    TODO: Move this into the database (generation script), into the body section and without tags-->
            </div>
        </div>
        <!-- <div id="activity">
            <h2>Posts</h2>
            <div id="activity_content" class="content">
                <?php
                $posts = dbGet("*", "r_posts", "user_id='" . $user_id . "' AND group_id='0'");
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
        </div> -->
        <div id="activity">
            <div id="activity_content" class="col-17">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="list-your_groups" role="tabpanel" aria-labelledby="list-your_groups-list">
                        <h2>Posts</h2>
                        <ul class="tab-content-ul">
                            <?php
                            $posts = dbGet("*", "r_posts", "user_id='" . $user_id . "' AND group_id='0'");
                            foreach ($posts as $post) {
                                $name = dbGet("firstname, lastname", "r_users", "user_id=" . $post["user_id"]);

                                echo "<li class='my_post'>" .
                                    "<span class='title'>" . $post["title"] . "</span><br />" .
                                    "<span class='body'>" . $post["body"] . "</span><br />" .
                                    "<span class='postauthor'> Posted by " . $name[0]["firstname"] . " " . $name[0]["lastname"] . "</span>" .
                                    "<span class='postdate'> on " . $post["timestamp"] . "</span>" .
                                    "</li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('resources/templates/footer.php'); ?>
</body>

</html>