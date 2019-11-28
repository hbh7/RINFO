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
            <div id="icon" class="play">
				<img src="https://img.icons8.com/nolan/64/000000/search.png" class="search-icon">
			  	<img src="https://img.icons8.com/ultraviolet/40/000000/go.png" class="search-icon">
			</div>
        </div>
        <div id="results"></div>


    </div>
    <?php include('resources/templates/footer.php'); ?>
    <script type="text/javascript" src="resources/scripts/search.js"></script>
</body>

</html>
