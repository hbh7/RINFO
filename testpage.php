<html lang="en">
	<head>
        <?php include('resources/templates/head.php'); ?>
        <title> RINFO Home Page </title>
        <script type="text/javascript" src="homepage.js"></script>
    </head>

    <body>
        <?php include('resources/templates/header.php'); ?>

		<div id="content">
			<div class="display">
                <?php
                include 'db.php';
                echo "Valid login cookie: " . checkValidLogin();
                ?>
			</div>
		</div>
		<?php include('resources/templates/footer.php'); ?>
	</body>
</html>
