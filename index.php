<html lang="en">
	<head>
        <?php include('resources/templates/head.php'); ?>
        <title> RINFO Home Page </title>
        <script type="text/javascript" src="homepage.js"></script>
    </head>

    <body>
        <?php include('resources/templates/header.php'); ?>
        <?php include_once 'db.php'; ?>

		<div id="content">
			<div class="display">
				<ul>
                    <li id="activity_tab" class="homepage" onclick="activity()"><a class="active" href="#activity_feed">Activity Feed</a></li>
  					<li id="group_tab" class="homepage" onclick="group()"><a href="#groups" >Groups</a></li>
  					<li id="post_tab" class="homepage" onclick="post()"><a href="#posts">My Posts</a></li>
				</ul>
				<div class="tab_content">
					<div id="hp_activities">
						<p id="banner" class="activity">Example Admin Notification</p>
                        <?php
                        // TODO: Something like this should probably end up in its own function at some point, since its
                        // likely going to be the same post generator on each page.
                        $posts = dbGet("*", "r_posts");
                        foreach ($posts as $post) {
                            $name = dbGet("firstname, lastname", "r_users", "user_id=" . $post["user_id"]);
                            $attendances = dbGet("*", "r_attendances", "post_id='" . $post["post_id"] . "'");

                            echo "<div class='activity'>" .
                                 "<span class='title'>" . $post["title"] . "</span><br />" .
                                 "<span class='body'>" . $post["body"] . "</span><br />" .
                                 "<span class='postauthor'> Posted by " . $name[0]["firstname"] . " " . $name[0]["lastname"] . "</span>" .
                                 "<span class='postdate'> on " . $post["timestamp"] . "</span>" .
                                 "<span class='attendances'> " . $attendances . " people attending </span>" .
                                 "</div>";
                        }
                        ?>
					</div>
					<div id="my_groups">
                        <?php

                        if(checkValidLogin()) {
                            echo "<span> My groups </span>";
                            $subscriptions = dbGet("group_id", "r_subscriptions", "user_id='" . getUserID() . "'");
                            foreach ($subscriptions as $subscription) {
                                $name = dbGet("name", "r_groups", "group_id=" . $subscription["group_id"])[0]["name"];

                                echo "<div class='group'>" .
                                    "<span class='name'><a href=\"group.php?group_id=" . $subscription["group_id"] . "\">" . $name . "</a></span><br />" .
                                    "</div>";
                            }
                        }

                        echo "<span> All groups </span>";

                        $groups = dbGet("group_id, name", "r_groups");
                        foreach ($groups as $group) {
                            echo "<div class='group'>" .
                                "<span class='name'><a href=\"group.php?group_id=" . $group["group_id"] . "\">" . $group["name"] . "</a></span><br />" .
                                "</div>";
                        }


                        ?>
					</div>
					<div id="my_posts">
                        <?php

                        if(checkValidLogin()) {
                            $posts = dbGet("*", "r_posts", "user_id='" . getUserID() . "'");
                            if(sizeof($posts) == 0) {
                                echo "<span class='name'>You haven't made any posts</span><br />";
                            } else {
                                foreach ($posts as $post) {
                                    $attendances = dbGet("*", "r_attendances", "post_id='" . $post["post_id"] . "'");
                                    echo "<div class='activity'>" .
                                        "<span class='title'>" . $post["title"] . "</span><br />" .
                                        "<span class='body'>" . $post["body"] . "</span><br />" .
                                        "<span class='postdate'> on " . $post["timestamp"] . "</span>" .
                                        "<span class='attendances'> " . $attendances . " people attending </span>" .
                                        "</div>";
                                }
                            }
                        } else {
                            echo "<span class='name'>You'll need to be logged in to see your posts lol</span><br />";
                        }

                        ?>
					</div>
				</div>
			</div>
		</div>
		<?php include('resources/templates/footer.php'); ?>
	</body>
</html>
