<!-- Everything in this file is placed at the very top of the <body> tag in every page on the site -->
<header id="global_header">
    <div id="leftbox">
        <a href="/index.php"><img id="logo" src="/resources/images/logo.png" width=110px height=90px alt="Logo" /></a>
    </div>
    <div id="centerbox">
        <?php echo "<h1 id='page_title'>" . $title . "</h1>"; ?>
    </div>
    <div id="rightbox">
        <?php

        if (checkValidLogin()) {
            $firstname = json_decode($_COOKIE["login"], true)["firstname"];
            $lastname = json_decode($_COOKIE["login"], true)["lastname"];
            $id = getUserID();
            echo "<span id='logintext'> Logged in as " . $firstname . " " . $lastname . " </span>";

            $iconPath = dbGet("logo", "r_users", "user_id='" . getUserID() . "'")[0]["logo"];
            if ($iconPath != "" && file_exists($iconPath)) {
                $icon = "<img id = 'user_icon' src = '../../" . $iconPath . "' title = 'User Profile Icon' alt = 'User Picture' >";
            } else {
                $icon = "<img id = 'user_icon' src = 'resources/images/icon1.png' title = 'User Profile Icon' alt = 'User Picture' >";
            }

            echo <<<HTML
                <a id="search_link2" href="search.php" class="text-muted">
                    <button id="search" class="btn btn-light" onclick="window.location='search.php'">
                        <svg xmlns=" http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-3">
                            <circle cx="10.5" cy="10.5" r="7.5"></circle>
                            <line x1="21" y1="21" x2="15.8" y2="15.8"></line>
                        </svg>
                    </button>
                    </a>
                <a href="manage.php"><button id="manage" class="btn btn-light" onclick="window.location='manage.php';">Manage</button></a>
                <a href="logout.php"><button id="logout" class="btn btn-light" onclick="window.location='logout.php';">Log Out</button></a>
                <a id="user_icon_link" href="#" onclick="window.location='user.php?user_id={$id}';">
                    {$icon}
                </a>
    </div>
HTML;
        } else {
            echo <<<HTML
                    <a id="search_link" href="search.php" class="text-muted">
                    <button id="search" class="btn btn-light" onclick="window.location='search.php'">
                        <svg xmlns=" http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-3">
                            <circle cx="10.5" cy="10.5" r="7.5"></circle>
                            <line x1="21" y1="21" x2="15.8" y2="15.8"></line>
                        </svg>
                    </button>
                    </a>
                    <button id="login" class="btn btn-light" onclick="window.location='login.php';"><a href="login.php">Log In</a></button>
                    </div>
HTML;
        }
        ?>
    </div>
    <!-- END MAIN HEADER CONTENT (nothing but the popup should be below here) -->
    <?php
    // check for get request from a page (if the popup key is set, display popup with a message)
    // to get a popup with some message to appear, assign that message to
    //      the key 'displayPopup' in a GET request to any page with this header included
    if (isset($_GET['displayPopup']) && !is_null($_GET['displayPopup'])) {
        // displayPopup exists and it has some value (the message) - generate the popup
        $displayPopup = sanitizeInput($_GET['displayPopup']);
        echo <<< HTML
                    <div id="popupParent">
                        <!-- These 2 elements could be anything, BUT THEY MUST BE THE SAME (I chose h2
                            because it seems like a popup would have important info, but
                            not h1 important if ya know what I mean) -->
                        <h2 id="popupChild">{$displayPopup}</h2>
                        <!-- Needed to get the parent div to have the correct width (absolutely
                            positioned elements are technically not in the document flow) -->
                        <h2 id="hideMe">{$displayPopup}</h2>
                    </div>
                    <script>
                        // idk how long you want the popup to stay on the screen for
                        // change the delay time as needed
                        $("#popupParent").delay(5000).fadeOut();
                    </script>
HTML;
        unset($_GET['displayPopup']);
    }
    ?>
</header>