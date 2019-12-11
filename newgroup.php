<?php

// Get POST data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if(!(isset($_POST["edit"]) && $_POST["edit"] == "true")) {

        // Make sure all values are provided
        if (isset($_POST["name"]) && isset($_POST["tagline"]) && isset($_POST["visibility"])) {

            include_once 'db.php';

            if (checkValidLogin()) {

                if (!checkPermission(0, "createGroup")) {
                    header("Location: /newgroup.php?displayPopup=Error: You are not allowed to create a group.");
                    die();
                }
                if (!isset($_POST["editGroup"])) {
                    // Check if any groups exist with the same name
                    $arr = dbGet("name", "r_groups");
                    $unique = true;
                    if(!isset($_POST["group_id"])) {
                        foreach ($arr as $a) {
                            if ($a["name"] == sanitizeInput($_POST["name"])) {
                                $unique = false;
                                break;
                            }
                        }
                    }
                    if ($unique) {

                        if ($_FILES["fileUpload"]["error"] != 4) {
                            include 'upload.php';
                            $imagePath = processUpload($_FILES["fileUpload"], "groups", sanitizeInput($_POST["name"]));
                        } else {
                            $imagePath = null;
                        }

                        if(isset($_POST["group_id"])) {
                            // Edit group
                            // sanitizeInput($_POST["tagline"]), $imagePath, sanitizeInput($_POST["visibility"])]
                            dbUpdate("r_groups", "name='" . sanitizeInput($_POST["name"]) . "'", "group_id='" . sanitizeInput($_POST["group_id"]) . "'");
                            dbUpdate("r_groups", "tagline='" . sanitizeInput($_POST["tagline"]) . "'", "group_id='" . sanitizeInput($_POST["group_id"]) . "'");
                            dbUpdate("r_groups", "visibility='" . sanitizeInput($_POST["visibility"]) . "'", "group_id='" . sanitizeInput($_POST["group_id"]) . "'");
                            if($imagePath != null) {
                                dbUpdate("r_groups", "logo='" . $imagePath . "'", "group_id='" . sanitizeInput($_POST["group_id"]) . "'");
                            }

                            header("Location: /group.php?group_id=" . sanitizeInput($_POST["group_id"]) . "&displayPopup=Group Edited Successfully!");
                            die();

                        } else {
                            // New group
                            dbPut("r_groups", [sanitizeInput($_POST["name"]), sanitizeInput($_POST["tagline"]), $imagePath, sanitizeInput($_POST["visibility"])]);
                            $groupID = dbGet("*", "r_groups", "name='" . sanitizeInput($_POST["name"]) . "'")[0]["group_id"];
                            dbPut("r_subscriptions", [getUserID(), $groupID]);

                            header("Location: /group.php?group_id=" . $groupID . "&displayPopup=Group Added Successfully!");
                            die();
                        }

                    } else {
                        // Throw an error that the group name isn't unique.
                        $_GET['displayPopup'] = "Error, your group name isn't unique";
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
    <?php
    if(isset($_POST["edit"]) && $_POST["edit"] == "true") {
        $title = "Edit Group";
    } else {
        $title = "Add Group";
    }

    ?>
    <?php include('resources/templates/head.php'); ?>
    <link rel="stylesheet" type="text/css" href="resources/styles/styles-newgroup.css" />
</head>

<body>
    <?php include('resources/templates/header.php'); ?>

    <div class="container">
        <div id="form_container">
            <form id="form" method="post" action="" role="form" enctype="multipart/form-data">
                <div class="messages"></div>
                <div class="controls">
                    <div class="form-group">
                        <label for="group_name">Group Name</label>
                        <input id="group_name" type="text" name="name" class="form-control" required="required" data-error="Group Name is required." value="<?php if (isset($_POST['name'])) echo htmlspecialchars($_POST['name']); ?>">
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <label for="tagline">Tagline</label>
                        <textarea id="tagline" name="tagline" class="form-control" required="required" data-error="Tagline is required."><?php if (isset($_POST['tagline'])) echo htmlspecialchars($_POST['tagline']); ?></textarea>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <label for="fileUpload">Image</label> <br />
                        <input type="file" name="fileUpload" id="fileUpload">
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <label for="visibility">Visibility</label><br />
                        <input type="radio" id="visibility" name="visibility" value="public" checked> Public <br />
                        <input type="radio" id="visibility" name="visibility" value="private"> Private <br />
                        <div class="help-block with-errors"></div>
                    </div>

                    <?php if(isset($_POST["group_id"])) { echo "<input type='hidden' name='group_id' value='" . sanitizeInput($_POST["group_id"]) . "'>"; } ?>

                    <input id="submit_group" type="submit" class="btn btn-secondary btn-send" value="Submit">
                </div>
            </form>
        </div>
    </div>
    <?php include('resources/templates/footer.php'); ?>
</body>

</html>
