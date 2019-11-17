<?php

// Accepts a tablename and an array of values to insert
use function Sodium\add;

function dbPut($tablename, $dbdata) {
    $conn = dbConnect();

    $dbdata_string = "'" . implode("', '", $dbdata) . "'" ;

    if($tablename == "r_users") {
        $sql = "INSERT INTO r_users (username, password, firstname, lastname, email) VALUES (" . $dbdata_string . ");";
    } elseif ($tablename == "r_groups") {
        $sql = "INSERT INTO r_groups (name, tagline) VALUES ("  . $dbdata_string . ")";
    } elseif ($tablename == "r_permissions") {
        $sql = "INSERT INTO r_permissions (user_id, group_id, description) VALUES ("  . $dbdata_string . ")";
    } elseif ($tablename == "r_subscriptions") {
        $sql = "INSERT INTO r_subscriptions (user_id, group_id) VALUES ("  . $dbdata_string . ")";
    } elseif ($tablename == "r_posts") {
        $sql = "INSERT INTO r_posts (group_id, user_id, title, body, timestamp) VALUES ("  . $dbdata_string . ")";
    } else {
        return "invalid table name";
    }

    //error_log($sql);

    if ($conn->query($sql) === TRUE) {
        $conn->close();
        return "success";
    } else {
        return $conn->error;
    }

}

// Accepts strings:
// $select: what to select. Ex: "*"
// $from: tablename. Ex: "r_posts"
// $where: optional specifier. Ex: "user_id=3"
function dbGet($select, $from, $where=null) {
    $conn = dbConnect();

    if ($from != "r_users" && $from != "r_groups" && $from != "r_permissions" && $from != "r_subscriptions" && $from != "r_posts") {
        return null;
    }

    if($where != null) {
        $sql = "SELECT " . $select . " FROM " . $from . " WHERE " . $where . ";";
    } else {
        $sql = "SELECT " . $select . " FROM " . $from . ";";
    }

    //error_log($sql);

    $result = $conn->query($sql);

    //error_log(print_r($result,true));

    $ret = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            array_push($ret, $row);
        }
    }

    //error_log(print_r($ret,true));

    $conn->close();
    return $ret;
}

// Call this before running any code that relies on a user being logged in to ensure that they're actually logged in
// If their login is invalid, kill the cookie and redirect them to the login page and throw an error in $result.
function checkValidLogin() {
    if(!isset($_COOKIE["login"])) {
        header("Location: /login.php?redirectmsg=You must be logged in to do that!");
        die();
    } else {
        //print_r(json_decode($_COOKIE["login"], true));
        $users = dbGet("username, password", "r_users", "username='" . json_decode($_COOKIE["login"], true)["username"] . "'");
        if (sizeof($users) > 0) {
            // User found, check password
            if(json_decode($_COOKIE["login"], true)["passwordHash"] == $users[0]["password"]) {
                // Valid user, valid password
                return true;
            } else {
                // Valid user, invalid password
                setcookie("login", "", time() - 3600); // Nuke the cookie
                header("Location: /login.php?redirectmsg=Invalid session cookie, password error!");
                die();
            }
        } else {
            // Invalid user
            setcookie("login", "", time() - 3600); // Nuke the cookie
            header("Location: /login.php?redirectmsg=Invalid session cookie, username error!");
            die();
        }
    }

}

function dbConnect() {
    $servername = "localhost";
    $username = "rinfo";
    $password = "RINFOServerPassword10302019";
    $dbname = "RINFO";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}




?>