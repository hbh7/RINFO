<!--Search Page-->
<html lang="en">

<head>
    <?php $title = "Search"; ?>
    <?php include('resources/templates/head.php'); ?>
    <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
</head>

<body>
    <!--Add the header of the search page. ex: Logo, title, and buttons-->
    <?php include('resources/templates/header.php'); ?>
    <div id="search_box_and_footer_wrapper">
        <div class="search-box">
            <div class="container">
                <input type="text" placeholder="Type to Search..." class="search-txt" name="search" id="searchtextbox" autocomplete="off">
                <div class="search"></div>
            </div>
        </div>
        <!--result shows in this div-->
        <div id="results"></div>
        <!--Add the footer for the search page-->
        <?php include('resources/templates/footer.php'); ?>
    </div>
    <!--Put the javascript at the bottom to avid it slow down the whole page.-->
    <script type="text/javascript" src="resources/scripts/search.js"></script>
</body>

</html>

