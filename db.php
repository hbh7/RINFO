<?php

// Get GET data
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if(isset($_GET['searchtext'])) {

        $output = [];
        $areas = [];
        if(isset($_GET['searchareas'])) {
            $areas = json_decode($_GET['searchareas']);
        }
        if(in_array("group_name", $areas)) {
            $output["group_name"] = dbGet("name, group_id", "r_groups", "visibility='public' AND name LIKE '%" . sanitizeInput($_GET['searchtext']) . "%'", true);
        }
        if(in_array("group_tagline", $areas)) {
            $output["group_tagline"] = dbGet("name, tagline, group_id", "r_groups", "visibility='public' AND tagline LIKE '%" . sanitizeInput($_GET['searchtext']) . "%'", true);
        }
        if(in_array("post_title", $areas)) {
            $output["post_title"] = dbGet("r_posts.title, r_posts.post_id, r_posts.group_id, r_posts.user_id, r_groups.visibility", "r_posts RIGHT JOIN r_groups on r_posts.group_id = r_groups.group_id", "r_groups.visibility='public' AND title LIKE '%" . sanitizeInput($_GET['searchtext']) . "%'", true);
        }
        if(in_array("post_body", $areas)) {
            $output["post_body"] = dbGet("r_posts.title, r_posts.body, r_posts.post_id, r_posts.group_id, r_posts.user_id, r_groups.visibility", "r_posts RIGHT JOIN r_groups on r_posts.group_id = r_groups.group_id", "r_groups.visibility='public' AND body LIKE '%" . sanitizeInput($_GET['searchtext']) . "%'", true);
        }
        if(in_array("user", $areas)) {
            $output["user"] = dbGet("user_id, firstname, lastname, username", "r_users", "firstname LIKE '%" . sanitizeInput($_GET['searchtext']) . "%' or lastname like '%" . sanitizeInput($_GET['searchtext']) . "%' or username like '%" . sanitizeInput($_GET['searchtext']) . "%'", true);
        }
        echo json_encode($output);

    }

}


// Accepts a tablename and an array of values to insert
// Run sanitizeInput() on ALL user data passed to this
function dbPut($tablename, $dbdata) {
    $conn = dbConnect();

    $dbdata_string = "'" . implode("', '", $dbdata) . "'" ;

    if($tablename == "r_users") {
        $sql = "INSERT INTO r_users (username, password, firstname, lastname, email) VALUES (" . $dbdata_string . ");";
    } elseif ($tablename == "r_groups") {
        $sql = "INSERT INTO r_groups (name, tagline, logo, visibility) VALUES ("  . $dbdata_string . ")";
    } elseif ($tablename == "r_permissions") {
        $sql = "INSERT INTO r_permissions (user_id, group_id, description) VALUES ("  . $dbdata_string . ")";
    } elseif ($tablename == "r_subscriptions") {
        $sql = "INSERT INTO r_subscriptions (user_id, group_id) VALUES ("  . $dbdata_string . ")";
    } elseif ($tablename == "r_posts") {
        $sql = "INSERT INTO r_posts (group_id, user_id, title, body, timestamp, attendance) VALUES ("  . $dbdata_string . ")";
    } elseif ($tablename == "r_comments") {
        $sql = "INSERT INTO r_comments (post_id, user_id, reply_id, body, timestamp) VALUES ("  . $dbdata_string . ")";
    } elseif ($tablename == "r_attendances") {
        $sql = "INSERT INTO r_attendances (post_id, user_id) VALUES ("  . $dbdata_string . ")";
    } elseif ($tablename == "r_alerts") {
        $sql = "INSERT INTO r_alerts (user_id, body, timestamp) VALUES ("  . $dbdata_string . ")";
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
// $search: only returns 5 items, can search for partial items
// Run sanitizeInput() on ALL user data passed to this
function dbGet($select, $from, $where=null, $search=null) {
    $conn = dbConnect();

    if($where != null) {
        $sql = "SELECT " . $select . " FROM " . $from . " WHERE " . $where;
    } else {
        $sql = "SELECT " . $select . " FROM " . $from;
    }

    if($search != null) {
        $sql .= " ORDER BY " . $search;
    }

    $sql  .= ";";

    //error_log($sql);

    $result = $conn->query($sql);

    $ret = [];

    if($conn->error) {
        error_log($conn->error);
        return($conn->error);
    } else {

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($ret, $row);
            }
        }
    }

    //error_log(print_r($ret,true));

    $conn->close();
    return $ret;

}

// Accepts raw SQL string, returns in the same way as dbGet()
function dbGetRaw($sql) {
    $conn = dbConnect();

    //error_log($sql);

    $result = $conn->query($sql);

    $ret = [];

    if($conn->error) {
        error_log($conn->error);
        return($conn->error);
    } else {

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($ret, $row);
            }
        }
    }

    //error_log(print_r($ret,true));

    $conn->close();
    return $ret;

}


// Accepts strings:
// $table: tablename. Ex: "r_posts"
// $column: what value to update. Ex: "firstname='Joe'"
// $where: Important! Restricts what values to act on. Ex: "username='joe'"
function dbUpdate($table, $column, $where) {
    $conn = dbConnect();

    if ($table != "r_users" && $table != "r_groups" && $table != "r_permissions" && $table != "r_subscriptions" && $table != "r_posts" && $table != "r_comments" && $table != "r_attendances" && $table != "r_alerts") {
        return null;
    }

    $sql = "UPDATE " . $table . " SET " . $column . " WHERE " . $where . ";";

    //error_log($sql);

    if ($conn->query($sql) === TRUE) {
        $conn->close();
        return true;
    } else {
        return $conn->error;
    }

}

// Accepts a tablename and a string specifying what to delete
// Ex: "WHERE user_id='4'"
// Run sanitizeInput() on ALL user data passed to this
function dbDelete($table, $where) {
    $conn = dbConnect();

    if ($table != "r_users" && $table != "r_groups" && $table != "r_permissions" && $table != "r_subscriptions" && $table != "r_posts" && $table != "r_attendances" && $table != "r_alerts") {
        return null;
    }

    $sql = "DELETE FROM " . $table . " WHERE " . $where . ";";

    //error_log($sql);

    if ($conn->query($sql) === TRUE) {
        $conn->close();
        return true;
    } else {
        return $conn->error;
    }

}

// Call this before running any code that relies on a user being logged in to ensure that they're actually logged in
// If their login is invalid, kill the cookie and redirect them to the login page and throw an error in $result.
// If valid login, return true. If not logged in, return false.
function checkValidLogin() {
    if(!isset($_COOKIE["login"])) {
        return false;
    } else {
        $users = dbGet("username, password", "r_users", "username='" . json_decode($_COOKIE["login"], true)["username"] . "'");
        if (sizeof($users) > 0) {
            // User found, check password
            if(json_decode($_COOKIE["login"], true)["passwordHash"] == $users[0]["password"]) {
                // Valid user, valid password
                return true;
            } else {
                // Valid user, invalid password
                setcookie("login", "", time() - 3600); // Nuke the cookie
                header("Location: /login.php?displayPopup=Invalid session cookie, password error!");
                die();
            }
        } else {
            // Invalid user
            setcookie("login", "", time() - 3600); // Nuke the cookie
            header("Location: /login.php?displayPopup=Invalid session cookie, username error!");
            die();
        }
    }
}

// Call this before running any code that relies on a user having permission to do something.
// Supply the ID of group and desired action (ex: post)
// If valid login, return true. If not logged in, return false.
// It is assumed that the checkValidLogin() function is called before this.
// Run sanitizeInput() on ALL user data passed to this
function checkPermission($id, $action) {
    $arr = dbGet("description", "r_permissions", "group_id='" . $id ."' AND user_id='" . getUserID() . "' AND description='" . $action . "'");
    if(sizeof($arr) == 1) {
        return true;
    } else {
        return false;
    }
}

// Return the DB ID associated with the current user. Run checkValidLogin() first.
function getUserID() {
    if (!isset($_COOKIE["login"]))
        return null;
    $users = dbGet("user_id", "r_users", "username='" . json_decode($_COOKIE["login"], true)["username"] . "'");
    return $users[0]["user_id"];
}

// Use to clean up any data from the user
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $conn = dbConnect();
    $data = $conn->real_escape_string($data); // This may patch SQL injection almost everywhere probably
    $conn->close();
    return $data;
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