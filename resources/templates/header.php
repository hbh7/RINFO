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
        <!-- END MAIN HEADER CONTENT (nothing but the popup should be below here) -->
        <?php
            // check for get request from a page (if the popup key is set, display popup with a message)
            // to get a popup with some message to appear, assign that message to the key 'displayPopup' in a GET request to any page with this header included
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
                            /* just an arbitrary value - should ideally be the largest z-index of any element on any page */
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
                        <!-- These 2 elements could be anything, BUT THEY MUST BE THE SAME (I chose h2 because it seems like a popup would have important info, but not h1 important if ya know what I mean) -->
                        <h2 id="popupChild">{$_GET['displayPopup']}</h2>
                        <!-- Needed to get the parent div to have the correct width (absolutely positioned elements are technically not in the document flow) -->
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
