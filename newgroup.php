<?php

// Get POST data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once 'db.php';

    if(checkValidLogin()) {

        // Check if any groups exist with the same name
        $arr = dbGet("name", "r_groups");
        $unique = true;
        foreach($arr as $a) {
            if($a["name"] == $_POST["name"]) {
                $unique = false;
                break;
            }
        }
        if($unique) {

            dbPut("r_groups", [$_POST["name"], $_POST["name"]]);
            $groupID = dbGet("*", "r_groups", "name='" . $_POST["name"] . "'")[0]["group_id"];

            header("Location: /group.php?group_id=" . $groupID);
            die();

        } else {
            // Throw an error that the group name isn't unique. TODO: Improve this
            echo "Error, your group name isn't unique";
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
        <title> Add Group </title>
         <link rel="stylesheet" type="text/css" href="resources/styles/styles-newpost.css" />
    </head>
    <body>
        <?php include('resources/templates/header.php'); ?>
        <?php include_once('db.php'); ?>

        <form method="post" action="newgroup.php">
            <!-- TODO: These need label tags -->
            <!-- TODO: Validate form data -->
            Group Name:
            <input type="text" name="name" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>"><br>
            Tagline:
            <input type="text" name="tagline" value="<?php if (isset($_POST['tagline'])) echo $_POST['tagline']; ?>"><br>


            <label>Example: <input type="text" name="example"></label><br />

            <input type="submit" />

        </form>


        <?php include('resources/templates/footer.php'); ?>
    </body>
</html>