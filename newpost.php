<?php

// Get POST data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once 'db.php';

    if(checkValidLogin()) {

        date_default_timezone_set('America/New_York');
        $date = date('Y-m-d H:i:s', time());
        if($_POST['where'] == "self") {
            $where = 0;

            if(!checkPermission(getUserID(), "post")) {
                header("Location: /newpost.php?redirectmsg=Error: You're not allowed to post to this group.");
                die();
            }

        } else {
            $where = dbGet("group_id", "r_groups", "name='" . $_POST['where'] . "'")[0]["group_id"];

            /*if(!checkPermission($where, "post")) {
                header("Location: /newpost.php?redirectmsg=Error: You're not allowed to post to this group.");
                die();
            }*/

        }

        $result = dbPut("r_posts", [$where, getUserID(), $_POST["title"], $_POST["body"], $date]);

        if($result == "success") {
            // TODO: Implement a popup system so we can display "Group added successfully!" or something
            if ($where == 0) {
                header("Location: /user.php?user_id=" . getUserID());
            } else {
                header("Location: /group.php?group_id=" . $where);
            }
            die();
        } else {
            echo $result;
        }

    } else {
        header("Location: /login.php?redirectmsg=You must be logged in to do that!");
        die();
    }


}
?>

<html lang="en">
    <head>
        <?php include('resources/templates/head.php'); ?>
        <title> RINFO Home </title>
         <link rel="stylesheet" type="text/css" href="resources/styles/styles-newpost.css" />
    </head>
    <body>
        <?php include('resources/templates/header.php'); ?>

        <form method="post" action="newpost.php">
            <!-- TODO: These need label tags -->
            <!-- TODO: Validate form data -->
            <!-- TODO: PHP backfill the values if validation fails, see newgroup.php -->
            Title:
            <input type="text" name="title"><br>
            Body:
            <input type="text" name="body"><br>
            Where to post to (group name or "self"):
            <!-- TODO: Probably add a radio button for group vs self, perhaps a group search function -->
            <input type="text" name="where"><br>

            <input type="submit" />
        </form>

        <?php include('resources/templates/footer.php'); ?>
    </body>
</html>