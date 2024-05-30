<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CarolinkDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$post_id = $_POST['post_id'];

// Verify the post belongs to the user
$sql = "SELECT user_id FROM Posts WHERE post_id = $post_id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['user_id'] == $user_id) {
        // Delete the post
        $delete_sql = "DELETE FROM Posts WHERE post_id = $post_id";
        if ($conn->query($delete_sql) === TRUE) {
            echo "Success";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Error: Unauthorized";
    }
} else {
    echo "Error: Post not found";
}

$conn->close();
?>
