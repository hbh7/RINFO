<?php
include_once 'db.php';

// Get POST data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Check to make sure this isn't an edit redirect
    if(!(isset($_POST["edit"]) && $_POST["edit"] == "true")) {

        // Make sure all values are provided
        if (isset($_POST["name"]) && isset($_POST["tagline"]) && isset($_POST["visibility"])) {

            if (checkValidLogin()) {

                date_default_timezone_set('America/New_York');
                $date = date('Y-m-d H:i:s', time());

                if (sanitizeInput($_POST['destination']) == "Self") {
                    $where = 0;

                    if (!checkPermission(0, "post")) {
                        header("Location: /newpost.php?displayPopup=Error: You're not allowed to post to your page.");
                        die();
                    }
                } else {
                    $where = sanitizeInput($_POST['destination']);

                    if (sizeof(dbGet("name", "r_groups", "group_id='" . $where . "'")) != 1) {
                        header("Location: /newpost.php?displayPopup=Error: Invalid group specified.");
                        die();
                    }

                    if (!checkPermission($where, "post")) {
                        header("Location: /newpost.php?displayPopup=Error: You're not allowed to post to this group.");
                        die();
                    }
                }

                if (isset($_POST['attendance'])) {
                    $count_attendance = 1;
                } else {
                    $count_attendance = 0;
                }

                if(isset($_POST["post_id"])) {

                    dbUpdate("r_posts", "group_id='" . $where . "'", "post_id='" . sanitizeInput($_POST["post_id"]) . "'");
                    dbUpdate("r_posts", "title='" . sanitizeInput($_POST["title"]) . "'", "post_id='" . sanitizeInput($_POST["post_id"]) . "'");
                    dbUpdate("r_posts", "body='" . sanitizeInput($_POST["body"]) . "'", "post_id='" . sanitizeInput($_POST["post_id"]) . "'");
                    dbUpdate("r_posts", "date='" . $date . "'", "post_id='" . sanitizeInput($_POST["post_id"]) . "'");
                    dbUpdate("r_posts", "attendance='" . $count_attendance . "'", "post_id='" . sanitizeInput($_POST["post_id"]) . "'");

                    header("Location: /post.php?post_id=" . $_POST["post_id"] . "&displayPopup=Post Edited Successfully!");
                    die();

                } else {

                    if($result = dbPut("r_posts", [$where, getUserID(), sanitizeInput($_POST["title"]), sanitizeInput($_POST["body"]), $date, $count_attendance])) {
                        if ($where == 0) {
                            header("Location: /user.php?user_id=" . getUserID() . "&displayPopup=Post Added Successfully!"); // Not really a good way to get the ID of the post so lets just do to the page with it
                        } else {
                            header("Location: /group.php?group_id=" . $where . "&displayPopup=Post Added Successfully!"); // Not really a good way to get the ID of the post so lets just do to the page with it
                        }
                        die();
                    } else {
                        $_GET['displayPopup'] = $result;
                    }

                }


            } else {
                header("Location: /login.php?displayPopup=You must be logged in to do that!");
                die();
            }
        } else {
            $_GET['displayPopup'] = "Error, some required fields are missing";
        }
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
                <div class="messages"></div>
                <div class="controls">
                    <div class="form-group">
                        <label for="form_title">Post Title</label>
                        <input id="form_title" type="text" name="title" class="form-control" required="required" data-error="Post Title is required." value="<?php if (isset($_POST['title'])) echo htmlspecialchars($_POST['title']); ?>">
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <label for="form_body">Post Body</label>
                        <textarea id="form_body" name="body" class="form-control" rows="4" required data-error="Post Body is required"><?php if (isset($_POST['body'])) echo htmlspecialchars($_POST['body']); ?></textarea>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <label for="form_destination">Post Destination (Group Name or Self)</label>
                        <select id="form_destination" name="destination" class="form-control" required="required" data-error="Post Destination is required.">
                            <?php
                            $destinations = dbGetRaw("select r_groups.name, r_permissions.group_id from r_permissions inner join r_groups on r_permissions.group_id = r_groups.group_id where r_permissions.description='post' AND r_permissions.user_id='" . sanitizeInput(getUserID()) . "';");
                            if (isset($_GET['destination'])) {
                                $destination = sanitizeInput($_GET["destination"]);
                            }
                            if (isset($_POST['destination'])) {
                                $destination = sanitizeInput($_POST["destination"]);
                            }

                            foreach ($destinations as $d) {
                                echo "<option value='" . $d["group_id"] . "'";

                                if (isset($destination) && $d["name"] == $destination) {
                                    echo "selected";
                                }

                                echo ">" . $d["name"] . "</option>";

                            }

                            if($destination == "Self") {
                                echo "<option value='self' selected>Self</option>";
                            } else {
                                echo "<option value='self'>Self</option>";
                            }

                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="attendance_checkbox">Count Attendances?</label><br />
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