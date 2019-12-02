<html lang="en">

<head>
    <?php $title = "Welcome to RINFO"; ?>
    <?php include('resources/templates/head.php'); ?>
    <script type="text/javascript" src="resources/scripts/homepage.js"></script>
</head>

<body>
    <?php include('resources/templates/header.php'); ?>
    <div class="scroll-left">
        <p>EMERGENCY ALERT</p>
    </div>
    <div id="homepage_content">
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li class="indicator active" data-target="#carouselExampleIndicators" data-slide-to="0"></li>
                <li class="indicator" data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li class="indicator" data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                <li class="indicator" data-target="#carouselExampleIndicators" data-slide-to="3"></li>
                <li class="indicator" data-target="#carouselExampleIndicators" data-slide-to="4"></li>
                <li class="indicator" data-target="#carouselExampleIndicators" data-slide-to="5"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="d-block w-100" src="resources/images/example1.jpg" alt="First slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="resources/images/example2.jpg" alt="Second slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="resources/images/example3.png" alt="Third slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="resources/images/example1.jpg" alt="Fourth slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="resources/images/example2.jpg" alt="Fifth slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="resources/images/example3.png" alt="Sixth slide">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
        <div class="display">
            <div class='tab_menu'><ul>
                <li id="activity_tab" class="homepage" onclick="activity()"><a href="#activity_feed" id="activity_t">Activity Feed</a></li>
                <li id="group_tab" class="homepage" onclick="group()"><a href="#groups" id="group_t">Groups</a></li>
                <li id="post_tab" class="homepage" onclick="post()"><a href="#posts" id="post_t">My Posts</a></li>
            </ul></div>
            <div class="tab_content">
                <div id="hp_activities">
                    <?php
                    // Something like this should probably end up in its own function at some point, since its
                    //      likely going to be the same post generator on each page.
                    // I'm gonna tentatively veto this idea - they are not the same generators and creating php to
                    //      dynamically generate php that dynamically generates html is one too many layers of abstraction for me
                    // also why don't you use heredoc? it looks about 4.5 orders of magnitude nicer :)
                    $posts = dbGet("*", "r_posts");
                    foreach ($posts as $post) {
                        $name = dbGet("firstname, lastname", "r_users", "user_id=" . $post["user_id"]);
                        if ($post["attendance"]) {
                            $attendances = dbGet("*", "r_attendances", "post_id='" . $post["post_id"] . "'");
                            $attend = count(dbGet("*", "r_attendances", "post_id='" . $post["post_id"] . "' AND user_id='" . getUserID() . "'"));
                        }

                        echo "<div class='activity'><div class='inner_activity'>" .
                            "<span class='title'><a href=\"./comments.php?title=" . $post["title"] . "\">" . $post["title"] . "</a></span><br />" .
                            "<span class='body'>" . $post["body"] . "</span><br />" .
                            "<span class='postauthor'> Posted by " . $name[0]["firstname"] . " " . $name[0]["lastname"] . "</span>" .
                            "<span class='postdate'> on " . $post["timestamp"] . "</span>" .
                            "</div>";

                        if ($post["attendance"]) {
                            echo "<div class='attending'><form method='post'><button type='submit' class='btn btn-light' name='toggle_attendance'";
                            if ($attend == 1) {
                            	echo "style='color: rgb(233, 81, 81);'";
                            }
                             echo ">" .
                            "<span class='num_attend'>" . count($attendances) .
                            "</span><br><span class='smalltext'>attending</span>"
                            ."</button>" .
                            "<input type='hidden' name='p_id' value='" . $post["post_id"] . "''>" .
                            "</form></div>";
                        }
                        echo "</div>";
                    }
                    ?>
                </div>
                <div id="my_groups">
                    <?php

                    if (checkValidLogin()) {
                        echo "<div id='followed_groups'>";
                        echo "<h4> My groups </h4>";
                        $subscriptions = dbGet("group_id", "r_subscriptions", "user_id='" . getUserID() . "'");
                        foreach ($subscriptions as $subscription) {
                            $name = dbGet("name", "r_groups", "group_id=" . $subscription["group_id"])[0]["name"];

                            echo "<a href=\"group.php?group_id=" . $subscription["group_id"] . "\">" . 
                                "<div class='group'>" .
                                "<span class='name'>" . $name . "</span><br/>" .
                                "</div></a>";
                        }
                        echo "</div>";
                    }

                    echo "<div id='all_groups'>";
                    echo "<h4> All groups </h4>";

                    $groups = dbGet("group_id, name", "r_groups");
                    foreach ($groups as $group) {
                        echo "<a href=\"group.php?group_id=" . $group["group_id"] . "\">" .
                            "<div class='group'>" .
                            "<span class='name'>" . $group["name"] . "</span><br />" .
                            "</div></a>";
                    }
                    echo "</div>";


                    ?>
                </div>
                <div id="my_posts">
                    <?php

                    if (checkValidLogin()) {
                        $posts = dbGet("*", "r_posts", "user_id='" . getUserID() . "'");
                        if (sizeof($posts) == 0) {
                            echo "<h3>You haven't made any posts!</h3><br />";
                        } else {
                            $name = dbGet("firstname, lastname", "r_users", "user_id=" . $post["user_id"]);
                            if ($post["attendance"]) {
                                $attendances = dbGet("*", "r_attendances", "post_id='" . $post["post_id"] . "'");
                                $attend = count(dbGet("*", "r_attendances", "post_id='" . $post["post_id"] . "' AND user_id='" . getUserID() . "'"));
                            }

                            echo "<div class='activity'><div class='inner_activity'>" .
                                "<span class='title'><a href=\"./comments.php?title=" . $post["title"] . "\">" . $post["title"] . "</a></span><br />" .
                                "<span class='body'>" . $post["body"] . "</span><br />" .
                                "<span class='postauthor'> Posted by " . $name[0]["firstname"] . " " . $name[0]["lastname"] . "</span>" .
                                "<span class='postdate'> on " . $post["timestamp"] . "</span>" .
                                "</div>";

                            if ($post["attendance"]) {
                                echo "<div class='attending'><form method='post'><button type='submit' class='btn btn-light' name='toggle_attendance'";
                                if ($attend == 1) {
                                    echo "style='color: rgb(233, 81, 81);'";
                                }
                                 echo ">" .
                                "<span class='num_attend'>" . count($attendances) .
                                "</span><br><span class='smalltext'>attending</span>"
                                ."</button>" .
                                "<input type='hidden' name='p_id' value='" . $post["post_id"] . "''>" .
                                "</form></div>";
                            }
                            echo "</div>";
                        }
                    } else {
                        echo "<span class='name'>You'll need to be logged in to see your posts</span><br />";
                    }

                    ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        
    </script>
    <?php include('resources/templates/footer.php'); ?>
</body>

</html>
