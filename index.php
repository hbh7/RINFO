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
                    <li id="activity_tab" class="homepage"><a class="active" href="#activity_feed" onclick="activity()">Activity Feed</a></li>
  					<li id="group_tab" class="homepage"><a href="#groups" onclick="group()">My Groups</a></li>
  					<li id="post_tab" class="homepage"><a href="#posts" onclick="post()">My Posts</a></li>
				</ul>
				<div class="tab_content">
					<div id="hp_activities">
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
		<script src="homepage.js">
			$(document).ready(function () {
    			document.getElementById("my_groups").style.display = "none";
    			document.getElementById("my_posts").style.display = "none";
			});
		</script>
		<?php include('resources/templates/footer.php'); ?>
	</body>
</html>
