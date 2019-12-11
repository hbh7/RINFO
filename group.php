<?php

include_once 'db.php';

// Get GET data
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(isset( $_GET['group_id'])) {
        $group_id = sanitizeInput($_GET['group_id']);
    } else {
        echo "Error: You need to provide the correct parameter(s) for this page to work";
        die();
    }
} else {
    echo "Error: You need to provide the correct parameter(s) for this page to work";
    die();
}

$group = dbGet("*", "r_groups", "group_id='" . $group_id . "'");
if(sizeof($group) != 1) {
    echo "Error: Invalid group ID";
    die();
} else {
    $group = $group[0];
}

if (isset($_POST['action'])) {
    $action = sanitizeInput($_POST['action']);
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
    <?php $title = $group["name"]; ?>
    <?php include('resources/templates/head.php'); ?>
</head>

<body>
    <?php include('resources/templates/header.php'); ?>

    <div id="content">
        <div id="information">
            <div id="information_content" class="content">
                <h3 id="group_name"><?php echo $group["name"]; ?></h3>
                <?php
                $iconPath = $group["logo"];
                if($iconPath != "" && file_exists($iconPath)) {
                    echo "<img id = 'group_logo' src = '../../" . $iconPath . "' title = 'User Profile Icon' alt = 'Group Logo' >";
                } else {
                    echo "<img id = 'group_logo' src = 'resources/images/icon1.png' title = 'User Profile Icon' alt = 'Group Logo' >";
                }
                ?>
                <div id="users_info">
                    <img id="users_logo" src="resources/images/users.png" alt="Members Logo"/>
                    <p id="Nusers" class="Ndetails"><?php echo $numSubscriptions; ?> members</p>
                </div>
                <div id="posts_info">
                    <img id="posts_logo" src="resources/images/posts.png" alt="Posts Logo" />
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
            </div>
            <?php //check permissions
            if(checkValidLogin() && checkPermission($group["group_id"], "post")) {
                echo <<<HTML
                <a class="list-group-item list-group-item-action" id="new_post" href="newpost.php?destination={$group['name']}" role="tab" aria-controls="add_post">Add New Post</a>
HTML;
            }
            ?>
        </div>
        <div id="activity">
            <h2 id="group_posts">Posts</h2>
            <!--https://bootsnipp.com/snippets/xrKXW-->
            <div id="activity_content" class="content">
                <?php
                $posts = dbGet("*", "r_posts", "group_id='" . $group_id . "'");
                echo "<ul class='timeline'>";
                foreach ($posts as $post) {
                    $name = dbGet("firstname, lastname", "r_users", "user_id=" . $post["user_id"]);
                    $attendances = dbGet("*", "r_attendances", "post_id='" . $post["post_id"] . "'");
                    $comment_link = "./post.php?post_id=" . $post["post_id"];
                    $attend = count(dbGet("*", "r_attendances", "post_id='" . $post["post_id"] . "' AND user_id='" . getUserID() . "'"));

                    echo "<li><div class='feed_item'><div class='feed_info attendance_based'>" . //if attendance required, used class "attendance_based"
                        "<span class='title'><a href='" . $comment_link . "''>" . $post["title"] . "</a></span><br />" .
                        "<span class='smaller' class='body'>" . $post["body"] . "</span><br />" .
                        "<span class='smallest' class='postauthor'> Posted by " . $name[0]["firstname"] . " " . $name[0]["lastname"] . "</span>" .
                        "<span class='smallest' class='postdate'> on " . $post["timestamp"] . "</span>" .
                        "</div>";
                        //line below is only needed if attendance is part of this post
                    if ($post["attendance"]) {
                        echo "<div class='feed_attendance'><form method='post'><button type='submit' class='btn btn-light' name='toggle_attendance'";
                        if ($attend == 1) {
                            echo "style='color: rgb(233, 81, 81);'";
                        }
                        echo "><span class='num_attend'> " . count($attendances) . "</span><br /><span class='smaller'> attending </span></button><input type='hidden' name='p_id' value='" . $post["post_id"] . "''></form></div>";
                    } 
                    echo "</li>";
                }
                echo "</ul>";
                ?>

            </div>
        </div>
    </div>

    <?php include('resources/templates/footer.php'); ?>
</body>

</html>