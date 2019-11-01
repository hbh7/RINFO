<!-- Everything in this file is placed at the very top of the <body> tag in every page on the site -->
    <header id="global_header">
        <a href="/index.php"><img id="logo" src="/resources/images/logo.png" width=50px height=50px alt="Logo" /></a>
        <?php
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
            // $out[0]; contains the text contained in the <title> tag in the html file where this file is include'd
            // now I have to generate some html so that this text isn't just floating around on the page
            echo "<h1 id='page_title'>".$out[0]."</h1>";
        ?>
        <button id="search" onclick="window.location='search.php';"> Search </button>
        <button id="login" onclick="window.location='login.php';">Log in</button>
    </header>
