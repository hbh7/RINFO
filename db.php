<?php

// Accepts a tablename and an array of values to insert
function dbInsert($tablename, $dbdata) {
    $conn = dbConnect();

    $dbdata_string = implode(", ", $dbdata);

    if($tablename == "r_users") {
        $sql = "INSERT INTO r_users (username, password, firstname, lastname, email) VALUES (" . $dbdata_string . ")";
    } elseif ($tablename == "r_groups") {
        $sql = "INSERT INTO r_groups (name, tagline) VALUES ("  . $dbdata_string . ")";
    } elseif ($tablename == "r_permissions") {
        $sql = "INSERT INTO r_permissions (user_id, group_id, description) VALUES ("  . $dbdata_string . ")";
    } elseif ($tablename == "r_subscriptions") {
        $sql = "INSERT INTO r_subscriptions (user_id, group_id) VALUES ("  . $dbdata_string . ")";
    } elseif ($tablename == "r_posts") {
        $sql = "INSERT INTO r_posts (group_id, user_id, title, body, date) VALUES ("  . $dbdata_string . ")";
    }

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

// Accepts a tablename and an array of values to insert
function dbGet($tablename, $dbdata) {
    $conn = dbConnect();

    $dbdata_string = implode(", ", $dbdata);

    echo "Table " . $tablename . ": <br />";
    $sql = "SELECT " . $dbdata_string . " FROM " . $tablename;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo $row . "<br />"; // or something else, undecided yet how this is going to get used.
        }
    } else {
        echo "0 results <br />";
    }

    $conn->close();
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