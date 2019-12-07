<?php

// Get POST data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once 'db.php';

    if (checkValidLogin()) {

        /*
        if(!checkPermission(getUserID(), "createGroup")) {
            header("Location: /newpost.php?redirectmsg=Error: You're not allowed to create a group.");
            die();
        }
        // TODO: Enable this permission check
        */
        if (!isset($_POST["editGroup"])) {
            // Check if any groups exist with the same name
            $arr = dbGet("name", "r_groups");
            $unique = true;
            foreach ($arr as $a) {
                if ($a["name"] == sanitizeInput($_POST["name"])) {
                    $unique = false;
                    break;
                }
            }
            if ($unique) {

                include 'upload.php';
                $imagePath = processUpload($_FILES["fileUpload"], "groups", sanitizeInput($_POST["name"]));

                // TODO: Change "public" to read in a public/private value from form
                dbPut("r_groups", [sanitizeInput($_POST["name"]), sanitizeInput($_POST["name"]), $imagePath, "public"]);
                // TODO: Add the new group to the user's subscriptions

                $groupID = dbGet("*", "r_groups", "name='" . sanitizeInput($_POST["name"]) . "'")[0]["group_id"];

                // TODO: Implement a popup system so we can display "Group added successfully!" or something
                header("Location: /group.php?group_id=" . $groupID);
                die();
            } else {
                // Throw an error that the group name isn't unique. TODO: Improve this
                echo "Error, your group name isn't unique";
            }
        }
    } else {
        header("Location: /login.php?redirectmsg=You must be logged in to do that!");
        die();
    }
}
?>


<html lang="en">

<head>
    <?php $title = "Add Group"; ?>
    <?php include('resources/templates/head.php'); ?>
    <link rel="stylesheet" type="text/css" href="resources/styles/styles-newgroup.css" />
</head>

<body>
    <?php include('resources/templates/header.php'); ?>

    <!-- <form method="post" action="newgroup.php">
        TODO: These need label tags
        TODO: Validate form data
        Group Name:
        <input type="text" name="name" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>"><br>
        Tagline:
        <input type="text" name="tagline" value="<?php if (isset($_POST['tagline'])) echo $_POST['tagline']; ?>"><br>
        TODO: Add a radio button to pick between public and private

        <label>Example: <input type="text" name="example"></label><br />

        <input type="submit" />

        // TODO: Clean up this legacy code, migrate/solve the todos

    </form> -->
    <div class="container">
        <div id="form_container">
            <form id="form" method="post" action="" role="form" enctype="multipart/form-data">
                <div class="messages"></div>
                <div class="controls">
                    <div class="form-group">
                        <label for="group_name">Group Name</label>
                        <input id="group_name" type="text" name="name" class="form-control" required="required" data-error="Group Name is required." value="<?php if (isset($_POST['name'])) echo sanitizeInput($_POST['name']); ?>">
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <label for="tagline">Tagline</label>
                        <input id="tagline" type="text" name="text" class="form-control" required="required" data-error="Tagline is required." value="<?php if (isset($_POST['tagline'])) echo sanitizeInput($_POST['tagline']); ?>">
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <label for="form_info">Group Information</label>
                        <textarea id="form_info" name="example" class="form-control" rows="4" required data-error="Group Information is required"></textarea>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <label for="form_name">Image</label>
                        <input type="file" name="fileUpload" id="fileUpload">
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
