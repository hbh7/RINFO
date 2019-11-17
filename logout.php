<?php

setcookie("login", "", time() - 3600); // Nuke the cookie
header('Refresh: 2; URL=index.php');

?>

<html lang="en">
    <head>
        <?php include('resources/templates/head.php'); ?>
        <title> RINFO - Log out </title>
    </head>
    <body>
        <?php include('resources/templates/header.php'); ?>

        <h1 style="text-align: center; color: white; margin-top: 15%; margin-bottom: 15%">
            Logging you out...
        </h1>

        <?php include('resources/templates/footer.php'); ?>
    </body>
</html>