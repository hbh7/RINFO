<!-- Everything in this file is placed at the very top of the <body> tag in every page on the site -->
    <header id="global_header">
        <div id="leftbox">
            <a href="/index.php"><img id="logo" src="/resources/images/logo.png" width=75px height=75px alt="Logo" /></a>
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
                foreach($titles as $item) {
                    $out[] = $item->nodeValue;
                }
                // $out[0]; contains the text contained in the <title> tag in the html file where this file is included
                // now I have to generate some html so that this text isn't just floating around on the page
                echo "<h1 id='page_title'>".$out[0]."</h1>";
            ?>
        </div>
        <div id="rightbox">
            <?php
            include_once 'db.php';
            if(checkValidLogin()) {
                echo "<span id='logintext'> Logged in as " . json_decode($_COOKIE["login"], true)["firstname"] . " </span>";
                echo "<button id=\"logout\" onclick=\"window.location='logout.php';\">Log out</button>";
            } else {
                echo "<button id=\"login\" onclick=\"window.location='login.php';\">Log in</button>";
            }
            ?>
            <button id="search" onclick="window.location='search.php';"> Search </button>
        </div>
        <?php
            // check for get request from a page (if the popup key is set, display popup with a message)
            if (isset($_GET['displayPopup']) && !is_null($_GET['displayPopup'])) {
                // displayPopup exists and it has some value (the message) - generate the popup
                echo <<< HTML
                    <style>
                        #popup {
                            /*border: 2px solid black;
                            border-radius: 5px;
                            box-shadow: 5px 10px 8px #888888;
                            z-index: 987;
                            display: table;
                            margin: 0 auto;*/
                            position: relative;
                        }
                        #popupText {
                             positon: absolute;
                        }
                    </style>
                    <div id="popup">
                        <span id="popupText">{$_GET['displayPopup']}</span>
                    </div>
                    <script>
                        $("#popup").delay(2000).fadeOut();
                    </script>
HTML;
            }
        ?>
    </header>
