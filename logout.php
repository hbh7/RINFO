<?php
setcookie("login", "", time() - 3600); // Nuke the cookie
//have an alert pop up that says you have logged out
header("Location: /index.php");
?>
