<html>
	<head>
        <?php include('resources/templates/head.php'); ?>
        <title> RINFO Home Page </title>
    </head>

    <body>
        <?php include('resources/templates/header.php'); ?>


		<div id="content">
			<div class="display">
				<ul>
                    <li class="homepage"><a class="active" href="#activity_feed">Activity Feed</a></li>
  					<li class="homepage"><a href="#groups">My Groups</a></li>
  					<li class="homepage"><a href="#posts">My Posts</a></li>
				</ul>
				<div class="tab_content">
					<div id="hp_activities">
						<p id="banner" class="activity">Example Admin Notification</p>
						<p class="activity">Example Activity 1</p>
						<p class="activity">Example Activity 2</p>
						<p class="activity">Example Activity 3</p>
						<p class="activity">Example Activity 4</p>
					</div>
				</div>
			</div>
		</div>

		<?php include('resources/templates/footer.php'); ?>
	</body>
</html>
