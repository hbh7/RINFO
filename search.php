<html lang="en">

<head>
    <?php $title = "Search"; ?>
    <?php include('resources/templates/search_head.php'); ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>

<body>
    <?php include('resources/templates/header.php'); ?>
    <!--<div id="search_box_and_footer_wrapper">
        <div class="search-box">
            <input class="search-txt" type="text" name="search" id="searchtextbox" placeholder="Type to search" autocomplete="off">
            <img src="https://img.icons8.com/nolan/64/000000/search.png" id="search-icon">
        </div>
        <div id="results"></div>-->
    <div id="search_box_and_footer_wrapper">
        <!--https://codepen.io/AlbertFeynman/pen/BPvzWZ-->
        <div class="search-box">
            <div class="container">
                <input type="text" placeholder="Type to Search..." class="search-txt" name="search" id="searchtextbox" autocomplete="off">
                <div class="search"></div>
            </div>
        </div>
        <div id="results"></div>

        <?php include('resources/templates/footer.php'); ?>
    </div>

    <script type="text/javascript" src="resources/scripts/search.js"></script>
</body>

</html>


<!--<div id="search_box_and_footer_wrapper">
    <div class="search-box">
        <div class="container">
            <input type="text" placeholder="Type to Search..." class="search-txt" name="search" id="searchtextbox" autocomplete="off">
            <div class="search"></div>
        </div>
        <input class="search-txt" type="text" name="search" id="searchtextbox" placeholder="Type to search" autocomplete="off">
            <div id="animation-icon">
                <div id="icon" class="play">
                    <img src="https://img.icons8.com/nolan/64/000000/search.png" class="search-icon">
                    <img src="https://img.icons8.com/ultraviolet/40/000000/go.png" class="search-icon">
                </div>
            </div>
    </div>
    <div id="results"></div>-->