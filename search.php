<html lang="en">
    <head>
        <?php include('resources/templates/search_head.php'); ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <title> Searching... </title>
    </head>
    <body>
		<?php include('resources/templates/header.php'); ?>
        <div id="search_box_and_footer_wrapper">
            <div class="search-box">
              <input class="search-txt" type="text" name="search" id="searchtextbox" placeholder="Type to search" autocomplete="off">
    		  <img src="https://img.icons8.com/nolan/64/000000/search.png" id="search-icon">
            </div>
            <div id="results"></div>

            <?php include('resources/templates/footer.php'); ?>
        </div>

        <script type="text/javascript" src="resources/scripts/search.js"></script>
    </body>
</html>
