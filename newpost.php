<?php

// Get POST data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once 'db.php';

    if(checkValidLogin()) {

        // Check if any post exist with the same name
        // Commented out as I don't think is actually necessary. Leaving in case it is
        //$arr = dbGet("name", "r_posts");
        $unique = true;
        //foreach($arr as $a) {
        //    if($a["name"] == $_POST["title"]) {
        //        $unique = false;
        //        break;
        //    }
        //}
        if($unique) {

            date_default_timezone_set('America/New_York');
            $date = date('m/d/Y h:i:s a', time());
            if($_POST['where'] == "self") {
                $where = 0;
            } else {
                $where = $_POST['where'];
            }

            dbPut("r_posts", [$where, getUserID(), $_POST["title"], $_POST["body"], $date]);

            // TODO: Implement a popup system so we can display "Group added successfully!" or something
            if($where == 0) {
                header("Location: /user.php?user_id=" . getUserID());
            } else {
                header("Location: /group.php?group_id=" . $where);
            }
            die();

        } else {
            // Throw an error that the group name isn't unique. TODO: Improve this, probably just remove it though
            echo "Error, your post title isn't unique";
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

        <form>
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