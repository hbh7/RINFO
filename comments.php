<html lang="en">
    <head>
        <?php include('resources/templates/head.php'); ?>
        <?php
            if(isset($_GET["title"])) {
                $title = $_GET["title"];
                echo "<title>" . $title . "</title>";
            }
        ?>
        <script type="text/javascript" src="homepage.js"></script>
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
                if (checkValidLogin()) {
                    echo "<form method=\"post\" action=\"./comments.php?title=" . $title . "\">";
                    echo "<textarea name=\"comment_body\" rows=\"8\">";
                    echo "Enter Comment Here...</textarea><br>";
                    echo "<input type=\"submit\" value=\"Comment\">";
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

                        echo "<div class='activity'>" .
                            "<span class='body'>" . $comment["body"] . "</span><br />" .
                            "<span class='postauthor'> Posted by " . $user[0]["firstname"] . " " . $user[0]["lastname"] . "</span>" .
                            "<span class='postdate'> on " . $comment["timestamp"] . "</span>" .
                            "</div>";
                    }
                }
            ?>
        </div>
    </div>
    <?php include('resources/templates/footer.php'); ?>
</body>
</html>