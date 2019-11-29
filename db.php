<?php

// Get GET data
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if(isset($_GET['searchtext'])) {

        $output = [];
        $output["group_name"] = dbGet("name, group_id", "r_groups", "visibility='public' AND name LIKE '%" . $_GET['searchtext'] . "%'", true);
        $output["group_tagline"] = dbGet("name, tagline, group_id", "r_groups", "visibility='public' AND tagline LIKE '%" . $_GET['searchtext'] . "%'", true);
        $output["post_title"] = dbGet("r_posts.title, r_posts.post_id, r_posts.group_id, r_posts.user_id, r_groups.visibility", "r_posts RIGHT JOIN r_groups on r_posts.group_id = r_groups.group_id", "r_groups.visibility='public' AND title LIKE '%" . $_GET['searchtext'] . "%'", true);
        $output["post_body"] = dbGet("r_posts.title, r_posts.body, r_posts.post_id, r_posts.group_id, r_posts.user_id, r_groups.visibility", "r_posts RIGHT JOIN r_groups on r_posts.group_id = r_groups.group_id", "r_groups.visibility='public' AND body LIKE '%" . $_GET['searchtext'] . "%'", true);
        $output["user"] = dbGet("user_id, firstname, lastname, username", "r_users", "firstname LIKE '%" . $_GET['searchtext'] . "%' or lastname like '%" . $_GET['searchtext'] . "%' or username like '%" . $_GET['searchtext'] . "%'", true);
        echo json_encode($output);

    }


}


// Accepts a tablename and an array of values to insert
function dbPut($tablename, $dbdata) {
    $conn = dbConnect();

    $dbdata_string = "'" . implode("', '", $dbdata) . "'" ;

    if($tablename == "r_users") {
        $sql = "INSERT INTO r_users (username, password, firstname, lastname, email) VALUES (" . $dbdata_string . ");";
    } elseif ($tablename == "r_groups") {
        $sql = "INSERT INTO r_groups (name, tagline, visibility) VALUES ("  . $dbdata_string . ")";
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
function dbGet($select, $from, $where=null, $search=false) {
    $conn = dbConnect();

    if($where != null) {
        $sql = "SELECT " . $select . " FROM " . $from . " WHERE " . $where;
    } else {
        $sql = "SELECT " . $select . " FROM " . $from;
    }

    if($search) {
        if (strpos($sql, 'r_groups') !== false) {
            // If search is for groups
            $sql .= " ORDER BY name asc LIMIT 10";
        } elseif (strpos($sql, 'r_users') !== false) {
            // If search is for users
            $sql .= " ORDER BY firstname asc LIMIT 10";
        } else {
            // If search is for posts
            $sql .= " ORDER BY title asc LIMIT 10";
        }
    }

    $sql  .= ";";

    error_log($sql);

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

    if ($table != "r_users" && $table != "r_groups" && $table != "r_permissions" && $table != "r_subscriptions" && $table != "r_posts" && $table != "r_comments" && $table != "r_attendances") {
        return null;
    }

    $sql = "UPDATE " . $table . " SET " . $column . " WHERE " . $where . ";";

    error_log($sql);

    if ($conn->query($sql) === TRUE) {
        $conn->close();
        return "success";
    } else {
        return $conn->error;
    }

}

// Accepts a tablename and a string specifying what to delete
// Ex: "WHERE user_id='4'"
function dbDelete($tablename, $where) {
    $conn = dbConnect();

    if ($tablename != "r_users" && $tablename != "r_groups" && $tablename != "r_permissions" && $tablename != "r_subscriptions" && $tablename != "r_posts") {
        return null;
    }

    $sql = "DELETE FROM " . $tablename . " WHERE " . $where . ";";

    error_log($sql);

    if ($conn->query($sql) === TRUE) {
        $conn->close();
        return "success";
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

// Call this before running any code that relies on a user having permission to do something.
// Supply the ID of group and desired action (ex: post)
// If valid login, return true. If not logged in, return false.
// It is assumed that the checkValidLogin() function is called before this.
function checkPermission($id, $action) {

    $arr = dbGet("description", "r_permissions", "group_id='" . $id ."' AND user_id='" . getUserID() . "'");
    foreach($arr as $a) {
        if($a["description"] == $action) {
            error_log("Permission check passed");
            return true;
        }
    }
    error_log("Permission check failed");
    return false;
}

// Return the DB ID associated with the current user. Run checkValidLogin() first.
function getUserID() {
    $users = dbGet("user_id", "r_users", "username='" . json_decode($_COOKIE["login"], true)["username"] . "'");
    return $users[0]["user_id"];
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