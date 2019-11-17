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
  					<li id="group_tab" class="homepage" onclick="group()"><a href="#groups" >My Groups</a></li>
  					<li id="post_tab" class="homepage" onclick="post()"><a href="#posts">My Posts</a></li>
				</ul>
				<div class="tab_content">
					<div id="hp_activities">
						<p id="banner" class="activity">Example Admin Notification</p>
                        <?php
                        $posts = dbGet("*", "r_posts");
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
					<div id="my_groups">
                        <?php
                        if(checkValidLogin()) {
                            $subscriptions = dbGet("group_id", "r_subscriptions", "user_id='" . getUserID() . "'");
                            foreach ($subscriptions as $subscription) {
                                $name = dbGet("name", "r_groups", "group_id=" . $subscription["group_id"])[0]["name"];

                                echo "<div class='group'>" .
                                    "<span class='name'><a href=\"group.php?group_id=" . $subscription["group_id"] . "\">" . $name . "</a></span><br />" .
                                    "</div>";
                            }
                        } else {
                            $groups = dbGet("group_id, name", "r_groups");
                            foreach ($groups as $group) {
                                echo "<div class='group'>" .
                                    "<span class='name'><a href=\"group.php?group_id=" . $group["group_id"] . "\">" . $group["name"] . "</a></span><br />" .
                                    "</div>";
                            }
                        }

                        ?>
					</div>
					<div id="my_posts">
						<p class="post">Example Post 1</p>
						<p class="post">Example Post 2</p>
						<p class="post">Example Post 3</p>
					</div>
				</div>
			</div>
		</div>
		<?php include('resources/templates/footer.php'); ?>
	</body>
</html>
