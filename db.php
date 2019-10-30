<?php
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

echo "Table r_users: <br />";
$sql = "SELECT user_id, username, password, firstname, lastname, email FROM r_users";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "user_id: " . $row["user_id"] . ", username: " . $row["username"] . ", password: " . $row["password"] . ", firstname: " . $row["firstname"] . ", lastname: " . $row["lastname"] . ", email: " . $row["email"] . "<br>";
    }
} else {
    echo "0 results <br />";
}

echo "<br />Table r_groups: <br />";
$sql = "SELECT group_id, name, tagline, logo FROM r_groups";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "group_id: " . $row["group_id"] . ", name: " . $row["name"] . ", tagline: " . $row["tagline"] . ", logo: " . $row["logo"] . "<br>";
    }
} else {
    echo "0 results <br />";
}

echo "<br />Table permissions: <br />";
$sql = "SELECT permission_id, user_id, group_id, description FROM r_permissions";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "permission_id: " . $row["permission_id"] . ", user_id: " . $row["user_id"] . ", group_id: " . $row["group_id"] . ", description: " . $row["description"] . "<br>";
    }
} else {
    echo "0 results <br />";
}

echo "<br />Table r_subscriptions: <br />";
$sql = "SELECT subscription_id, user_id, group_id FROM r_subscriptions";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "subscription_id: " . $row["subscription_id"] . ", user_id: " . $row["user_id"] . ", group_id: " . $row["group_id"] . "<br>";
    }
} else {
    echo "0 results <br />";
}

echo "<br />Table r_posts: <br />";
$sql = "SELECT post_id, group_id, user_id, title, body, date FROM r_posts";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "post_id: " . $row["post_id"] . ", group_id: " . $row["group_id"] . ", user_id: " . $row["user_id"] . ", title: " . $row["title"] . ", body: " . $row["body"] . ", date: " . $row["date"] . "<br>";
    }
} else {
    echo "0 results <br />";
}


$conn->close();
?>