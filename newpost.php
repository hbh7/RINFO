<?php

include_once 'db.php';

// Get POST data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (checkValidLogin()) {

        date_default_timezone_set('America/New_York');
        $date = date('Y-m-d H:i:s', time());
        if ($_POST['where'] == "self") {
            $where = 0;

            if (!checkPermission(getUserID(), "post")) {
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

        // TODO: Change true to read the radio button for attendance tracking yes or no
        $result = dbPut("r_posts", [$where, getUserID(), $_POST["title"], $_POST["body"], $date, true]);

        if ($result == "success") {
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
    <?php $title = "Add Post "; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="resources/styles/styles-newpost.css" />
</head>

<body>
    <?php include('resources/templates/header.php'); ?>
    <div class="container">
        <div id="form_container">
            <form id="form" method="post" action="" role="form">
                <!-- TODO: These need label tags -->
                <!-- TODO: Validate form data -->
                <!-- TODO: PHP backfill the values if validation fails, see newgroup.php -->
                <div class="messages"></div>
                <div class="controls">
                    <div class="form-group">
                        <label for="form_title">Post Title</label>
                        <input id="form_title" type="text" name="name" class="form-control" required="required" data-error="Post Title is required." value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>">
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <label for="form_body">Post Body</label>
                        <textarea id="form_body" name="example" class="form-control" rows="4" required data-error="Post Body is required"></textarea>
                        <div class="help-block with-errors"></div>
                    </div>
                    <!-- TODO: Probably add a radio button for group vs self, perhaps a group search function -->
                    <!-- TODO: Add an attendance yes or no radio button -->
                    <div class="form-group">
                        <label for="form_name">Post Destination (Group Name or Self)</label>
                        <input id="form_name" type="text" name="name" class="form-control" required="required" data-error="Post Destination is required." value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>">
                        <div class="help-block with-errors"></div>
                    </div>
                    <input id="submit_group" type="submit" class="btn btn-secondary btn-send" value="Submit">
                </div>
            </form>
        </div>
    </div>
    <?php include('resources/templates/footer.php'); ?>
</body>

</html>