<html>
    <head>
        <?php include('resources/templates/head.php'); ?>
        <title> RINFO Home </title>
    </head>
    <body>
        <?php include('resources/templates/header.php'); ?>

        <form>
            Event Name:
            <input type="text" name="eventName"><br>
            Start Time:
            <input type="time" name="startTime"><br>
            End Time:
            <input type="time" name="endTime"><br>
            Street Address:
            <input type="text" name="location"><br>
        </form>

        <?php include('resources/templates/footer.php'); ?>
    </body>
</html>