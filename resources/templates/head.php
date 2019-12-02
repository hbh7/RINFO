<!-- Load universal scripts, styles, etc here -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

<!-- <link rel="stylesheet" type="text/css" href="/resources/styles/styles-newpost.css">
<link rel="stylesheet" type="text/css" href="/resources/styles/styles-newgroup.css">
<link rel="stylesheet" type="text/css" href="/resources/styles/styles.css">
<link rel="stylesheet" type="text/css" href="/resources/styles/styles-login.css">
<link rel="stylesheet" type="text/css" href="/resources/styles/search_styles.css">
<link rel="stylesheet" type="text/css" href="/resources/styles/styles-group.css">
<link rel="stylesheet" type="text/css" href="/resources/styles/styles-homepage.css">
<link rel="stylesheet" type="text/css" href="/resources/styles/styles-footer.css">
<link rel="stylesheet" type="text/css" href="/resources/styles/styles-header.css">
-->




<?php
    // of course I can't just read in a url and do stuff with it
    // even my inexperienced self realizes how freaking dangerous that is
    // I'm fairly certain that this covers most of my bases
    $executingScriptName = htmlspecialchars(basename($_SERVER["PHP_SELF"], ".php"), ENT_QUOTES);
    $hrefAttr = "/resources/styles/styles-" . $executingScriptName . ".css";
?>
<link rel="stylesheet" type="text/css" href="/resources/styles/styles.css">
<link rel="stylesheet" type="text/css" href="/resources/styles/styles-footer.css">
<link rel="stylesheet" type="text/css" href="/resources/styles/styles-header.css">
<link rel="stylesheet" type="text/css" href="<?php echo $hrefAttr;?>">


<?php

    include_once 'db.php';

    if (isset($_POST['toggle_attendance'])) {

        if (!checkValidLogin()) {
            header("Location: /login.php?redirectmsg=You must be logged in to do that!");
            die();
        }
        $attending = sizeof(dbGet(
                        "attendance_id",
                        "r_attendances",
                        "post_id='" . $_POST['p_id'] . "' AND user_id='" . getUserID() . "'"));
        if ($attending == 1) {
            dbDelete("r_attendances", "post_id='" . $_POST['p_id'] . "' AND user_id='" . getUserID() . "'");
        } else {
            dbPut("r_attendances", [$_POST['p_id'], getUserID()]);
        }
    }


  echo "<title> " . $title . " </title>";
?>
