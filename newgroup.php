<?php

// Get POST data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Make sure all values are provided
    if(isset($_POST["name"]) && isset($_POST["tagline"]) && isset($_POST["publicity"])) {

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
                foreach ($arr as $a) {
                    if ($a["name"] == sanitizeInput($_POST["name"])) {
                        $unique = false;
                        break;
                    }
                }
                if ($unique) {

                    if (isset($_FILES["fileUpload"])) {
                        include 'upload.php';
                        $imagePath = processUpload($_FILES["fileUpload"], "groups", sanitizeInput($_POST["name"]));
                    } else {
                        $imagePath = null;
                    }

                    dbPut("r_groups", [sanitizeInput($_POST["name"]), sanitizeInput($_POST["tagline"]), $imagePath, sanitizeInput($_POST["publicity"])]);
                    $groupID = dbGet("*", "r_groups", "name='" . sanitizeInput($_POST["name"]) . "'")[0]["group_id"];
                    dbPut("r_subscriptions", [getUserID(), $groupID]);

                    header("Location: /group.php?group_id=" . $groupID . "&displayPopup=Group Added Successfully!");
                    die();
                } else {
                    // Throw an error that the group name isn't unique.
                    // TODO: Consider making this like the login page one is, or make the login page one like this, idk.
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
?>


<html lang="en">

<head>
    <?php $title = "Add Group"; ?>
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
                        <input id="group_name" type="text" name="name" class="form-control" required="required" data-error="Group Name is required." value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>">
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <label for="tagline">Tagline</label>
                        <textarea id="tagline" name="tagline" class="form-control" required="required" data-error="Tagline is required."><?php if (isset($_POST['tagline'])) echo $_POST['tagline']; ?></textarea>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <label for="fileUpload">Image</label> <br />
                        <input type="file" name="fileUpload" id="fileUpload">
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <label for="publicity">Publicity</label><br />
                        <input type="radio" id="publicity" name="publicity" value="public" checked> Public <br />
                        <input type="radio" id="publicity" name="publicity" value="private"> Private <br />
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
