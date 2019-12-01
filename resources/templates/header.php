<!-- Everything in this file is placed at the very top of the <body> tag in every page on the site -->
<header id="global_header">
    <div id="leftbox">
        <a href="/index.php"><img id="logo" src="/resources/images/logo.png" width=70px height=70px alt="Logo" /></a>
    </div>
    <div id="centerbox">
        <?php
        libxml_use_internal_errors(true); // Silence the nonsense errors
        $doc = new DomDocument();
        // the following line assigns the absolute path of the currently executing script to $file
        $file = $_SERVER["SCRIPT_FILENAME"];
        $bool = $doc->loadHTMLFile($file);
        // this is a (functional) placeholder for now - I can modify this code to get
        //  the text from any element in the file and use said text as the page title
        $titles = $doc->getElementsByTagname("title");
        $out = array();
        foreach ($titles as $item) {
            $out[] = $item->nodeValue;
        }
        // $out[0]; contains the text contained in the <title> tag in the html file where this file is included
        // now I have to generate some html so that this text isn't just floating around on the page
        if (isset($out[0])) {
            echo "<h1 id='page_title'>" . $out[0] . "</h1>";
            // if ($out[0] = "Welcome to RINFO") {
            //     echo "<p id='home_tag'>RPI's Daily Information System</p>";
            // }
        }
        // For comments page, get title a different way
        if (isset($_GET["title"])) {
            $title = $_GET["title"];
            echo "<h1 id='page_title'>" . $title . "</h1>";
        }
        ?>
    </div>
    <div id="rightbox">
            <?php
            include_once 'db.php';
            if (checkValidLogin()) {
                $firstname = json_decode($_COOKIE["login"], true)["firstname"];
                $id = 1; //had to hardcode this, $_GET["user_id"]; not working
                echo "<span id='logintext'> Logged in as " . $firstname . " </span>";
                if (/* person is an admin - TODO: REPLACE THE 1 WITH AN ACTUAL CONDITION */1) {
                    echo <<<HTML
                    <button id="search" class="btn btn-light">
                        <a class="text-muted" onclick="window.location='search.php'">
                        <svg xmlns=" http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-3">
                                <circle cx="10.5" cy="10.5" r="7.5"></circle>
                        <line x1="21" y1="21" x2="15.8" y2="15.8"></line>
                        </svg>
                    </a>
                    <button id="admin" class="btn btn-light" onclick="window.location='admin.php';">Manage</button>
                    <button id="logout" class="btn btn-light" onclick="window.location='logout.php';">Log Out</button>
                    </button>
                    <div id="user_image">
                        <a href="#" onclick="window.location='user.php?user_id={$id}';">
                            <img src="resources/images/icon1.png" width="53" height="53" title="User Profile Icon" alt="User">
                        </a>
                    </div>
    </div>
HTML;
                }
            } else {
                echo <<<HTML
                    <button id="search" class="btn btn-light" onclick="window.location='search.php'">
                        <a class="text-muted">
                            <svg xmlns=" http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-3">
                                <circle cx="10.5" cy="10.5" r="7.5"></circle>
                                <line x1="21" y1="21" x2="15.8" y2="15.8"></line>
                            </svg>
                        </a>
                    </button>
                    <button id="login" class="btn btn-light" onclick="window.location='login.php';">Log In</button>
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
        echo <<< HTML
                    <style>
                        #popupParent {
                            position: relative;
                            display: inline-block;
                            padding: 2px;
                        }
                        #popupParent, #popupChild {
                            text-align: center;
                            /* just an arbitrary value - should ideally be the
                                largest z-index of any element on any page */
                            z-index: 987;

                        }
                        #popupChild {
                            position: absolute;
                            left: 60%;
                            right: -300%;
                            top: 65px;
                            border: 2px solid black;
                            border-radius: 5px;
                            background-color: rgba(76, 175, 80, 0.3);
                            padding: inherit;
                        }
                        #hideMe {
                            visibility: hidden;
                            height: 0;
                            float: left;
                        }
                    </style>
                    <div id="popupParent">
                        <!-- These 2 elements could be anything, BUT THEY MUST BE THE SAME (I chose h2
                            because it seems like a popup would have important info, but
                            not h1 important if ya know what I mean) -->
                        <h2 id="popupChild">{$_GET['displayPopup']}</h2>
                        <!-- Needed to get the parent div to have the correct width (absolutely
                            positioned elements are technically not in the document flow) -->
                        <h2 id="hideMe">{$_GET['displayPopup']}</h2>
                    </div>
                    <script>
                        // idk how long you want the popup to stay on the screen for
                        // change the delay time as needed
                        $("#popupParent").delay(2000).fadeOut();
                    </script>
HTML;
    }
    ?>
</header>
