<html lang="en">
    <head>
        <?php include('resources/templates/head.php'); ?>
        <title> RINFO Home </title>
         <link rel="stylesheet" type="text/css" href="resources/styles/styles-newpost.css" />
    </head>
    <body>
        <?php include('resources/templates/header.php'); ?>
        <?php include_once('db.php'); ?>

        <form>
            Event Name:
            <input type="text" name="eventName"><br>
            Date:
            <input type="date" name="date"><br>
            Start Time:
            <input type="time" name="startTime"><br>
            End Time:
            <input type="time" name="endTime"><br>
            Location:
            <input type="text" name="location"><br>
            Room Number:
            <input type="text" name="roomNum"><br>
            Description:
            <input type="text" name="description"><br>
        </form>

        <?php

        // Idk, when the button is pushed, call the insertDB(); function or something

        ?>

        <?php include('resources/templates/footer.php'); ?>
    </body>
</html>