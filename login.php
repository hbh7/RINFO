<html lang="en">
    <head>
        <?php include('resources/templates/head.php'); ?>
        <title> RINFO Login </title>
    </head>
    <body>
        <?php include('resources/templates/header.php'); ?>
        <div id="login_content">
            <div id="info_panel">
                <div class="tips">
                    <h2>Subscribe to groups</h2>
                    <h2>Browse personalized event feeds</h2>
                    <h2>Discover new interests</h2>
                    <h2>Get to know your campus better</h2>
                </div>
            </div>
            <div id="login_panel">
                <div id="login_box">
                    <p>Username <input type="text" id="username" value="Username"></p>
                    <br>
                    <p>Password   <input type="text" id="password" value="Password"></p>
                    <p class="small_link">Forgot Password</p>
                    <button type="button" id="login_button">Log In</button>
                </div>
                <div id="account_box">
                    <p>Don't have an account? Click here to join.</p>
                </div>
            </div>
        </div>

        <?php include('resources/templates/footer.php'); ?>
    </body>
</html>