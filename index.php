<html>
	<head>
        <?php include('resources/templates/head.php'); ?>
        <title> RINFO Home Page </title>
        <script type="text/javascript" src="homepage.js"></script>
    </head>

    <body>
        <?php include('resources/templates/header.php'); ?>

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
						<p class="activity">Example Activity 1</p>
						<p class="activity">Example Activity 2</p>
						<p class="activity">Example Activity 3</p>
						<p class="activity">Example Activity 4</p>
					</div>
					<div id="my_groups">
						<p class="group"> <a href="group.php">RPISEC</a></p>
						<p class="group">Example Club 2</p>
						<p class="group">Example Club 3</p>
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
