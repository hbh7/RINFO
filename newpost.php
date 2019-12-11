<?php
include_once 'db.php';
if(!checkValidLogin()) {
    header("Location: /login.php?redirectmsg=You must be logged in to do that!");
    die();
}

// Get POST data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once 'db.php';

    if (checkValidLogin()) {

        date_default_timezone_set('America/New_York');
        $date = date('Y-m-d H:i:s', time());
        if (sanitizeInput($_POST['where']) == "self") {
            $where = 0;

            if (!checkPermission(0, "post")) {
                header("Location: /newpost.php?displayPopup=Error: You're not allowed to post to this group.");
                die();
            }
        } else {
            $where = dbGet("group_id", "r_groups", "name='" . sanitizeInput($_POST['where']) . "'")[0]["group_id"];

            if(!checkPermission($where, "post")) {
                header("Location: /newpost.php?displayPopup=Error: You're not allowed to post to this group.");
                die();
            }
        }

        if (isset($_POST['attendance'])) {
            $count_attendance = 1;
        } else {
            $count_attendance = 0;
        }

        $result = dbPut("r_posts", [$where, getUserID(), sanitizeInput($_POST["title"]), sanitizeInput($_POST["body"]), $date, $count_attendance]);

        if ($result == "success") {
            if ($where == 0) {
                header("Location: /user.php?user_id=" . getUserID() . "&displayPopup=Post Added Successfully!");
            } else {
                header("Location: /group.php?group_id=" . $where . "&displayPopup=Post Added Successfully!");
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
    <?php include('resources/templates/head.php'); ?>
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
                        <input id="form_title" type="text" name="title" class="form-control" required="required" data-error="Post Title is required." value="<?php if (isset($_POST['name'])) echo sanitizeInput($_POST['name']); ?>">
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <label for="form_body">Post Body</label>
                        <textarea id="form_body" name="body" class="form-control" rows="4" required data-error="Post Body is required"></textarea> <!-- TODO: backfill the body -->
                        <div class="help-block with-errors"></div>
                    </div>
                    <!-- TODO: Probably add a radio button for group vs self, perhaps a group search function -->
                    <!-- TODO: Add an attendance yes or no radio button -->
                    <div class="form-group">
                        <label for="form_name">Post Destination (Group Name or Self)</label>
                        <select id="form_name" name="where" class="form-control" required="required" data-error="Post Destination is required." value="<?php if (isset($_POST['name'])) echo sanitizeInput($_POST['name']); else if (isset($_GET['destination'])) echo sanitizeInput($_GET['destination']); ?>">
                            <?php
                            include_once('db.php');
                            $results = dbGet("name", "r_groups");
                            if (isset($_GET['destination'])) {
                                $destination = sanitizeInput($_GET["destination"]);
                                foreach ($results as $result) {
                                    if ($result["name"] == $destination) {
                                        echo "<option value='" . $result["name"] . "''>";
                                        echo $result["name"] . "</option>";
                                    }
                                }
                            }
                            foreach ($results as $result) {
                                if ($result["name"] == $destination)
                                    continue;
                                echo "<option value='" . $result["name"] . "''>";
                                echo $result["name"] . "</option>";
                            }
                            ?>
                            <option value="self">Self</option>
                        </select>
                        <label for="attendance_checkbox">Count Attendances?</label>
                        <input id="attendance_checkbox" type="checkbox" name="attendance">
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