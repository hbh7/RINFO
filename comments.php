<?php

    // Get POST data
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        include_once 'db.php';

        if(checkValidLogin() && isset($_POST)) {
            $user_id = $_POST["user_id"];
            $post_id = $_POST["post_id"];
            $reply_id = $_POST["reply_id"];
            $body = $_POST["comment_body"];
            date_default_timezone_set('America/New_York');
            $date = date('Y-m-d H:i:s', time());

            if ($body != "Enter Comment Here...") {
                $result = dbPut("r_comments", [$post_id, $user_id, $reply_id, $body, $date]); 
            } 
        } 
    }
?>

<html lang="en">
    <head>
        <?php include('resources/templates/head.php'); ?>
        <?php
            if(isset($_GET["title"])) {
                $title = $_GET["title"];
                echo "<title>" . $title . "</title>";
            }
        ?>
        <script type="text/javascript" src="comments.js"></script>
    </head>

<body>
    <?php include('resources/templates/header.php'); ?>
    <?php include_once 'db.php'; ?>
    <div class="comment_content">
        <div class="post_info">
            <?php
                if (isset($title)) {
                    $posts = dbGet("*", "r_posts", "title='".$title."'");
                    $post = $posts[0];
                    $name = dbGet("firstname, lastname", "r_users", "user_id=" . $post["user_id"]);
                    $attendances = dbGet("*", "r_attendances", "post_id='" . $post["post_id"] . "'");

                    echo "<div class='activity'>" .
                        "<span class='body'>" . $post["body"] . "</span><br />" .
                        "<span class='postauthor'> Posted by " . $name[0]["firstname"] . " " . $name[0]["lastname"] . "</span>" .
                        "<span class='postdate'> on " . $post["timestamp"] . "</span>" .
                        "<span class='attendances'> " . count($attendances) . " people attending </span>" .
                        "</div>";
                }
            ?>
        </div>
        <div class="comment_form">
            <?php 
                if (checkValidLogin() && isset($post)) {
                    echo "<form method='post' action='./comments.php?title=" . $title . "'>";
                    echo "<textarea name='comment_body' rows='8'>";
                    echo "Enter Comment Here...</textarea><br>";
                    echo "<input type='submit' value='Comment'>";
                    echo "<input type='hidden' name='user_id' value='" . getUserID() . "'>";
                    echo "<input type='hidden' name='post_id' value='" . $post["post_id"] . "'>";
                    echo "<input type='hidden' name='reply_id' value='NULL'>";
                    echo "</form>";
                }
            ?>
        </div>
        <div class="comment_section">
            <?php
                if (isset($post)) {
                    echo "<h1>Comments:</h1>";
                    $comments = dbGet("*", "r_comments", "post_id=".$post["post_id"]);
                    foreach ($comments as $comment) {
                        $user = dbGet("firstname, lastname", "r_users", "user_id=" . $comment["user_id"]);

                        echo "<div class='comment'>" .
                            "<span class='body'>" . $comment["body"] . "</span><br />" .
                            "<span class='postauthor'> Posted by " . $user[0]["firstname"] . " " . $user[0]["lastname"] . "</span>" .
                            "<span class='postdate'> on " . $comment["timestamp"] . "</span>";
                        if (checkValidLogin())  {   
                            echo "<br><span class='replybutton'>Reply</span>";
                        }
                        echo "</div>";
                    }
                }
            ?>
        </div>
    </div>
    <?php include('resources/templates/footer.php'); ?>
</body>
</html>