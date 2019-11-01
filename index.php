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
					<ul id="hp_activities">
						<li class="activity">Activity 1</li>
					</ul>
				</div>
			</div>
		</div>

		<?php include('resources/templates/footer.php'); ?>
	</body>
</html>
