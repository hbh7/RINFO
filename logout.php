<?php
setcookie("login", "", time() - 3600); // Nuke the cookie
header("Location: /index.php");
?>
