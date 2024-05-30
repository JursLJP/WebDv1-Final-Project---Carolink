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
$liked = $_POST['liked'];

// Check if the post is already liked by the user
$sql = "SELECT * FROM Likes WHERE user_id = $user_id AND post_id = $post_id";
$result = $conn->query($sql);

if ($liked) {
    // Unlike the post
    if ($result->num_rows > 0) {
        $sql = "DELETE FROM Likes WHERE user_id = $user_id AND post_id = $post_id";
        if ($conn->query($sql) === TRUE) {
            echo "Unliked";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Error: Not liked yet";
    }
} else {
    // Like the post
    if ($result->num_rows == 0) {
        $sql = "INSERT INTO Likes (user_id, post_id, created_at) VALUES ($user_id, $post_id, NOW())";
        if ($conn->query($sql) === TRUE) {
            echo "Liked";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Error: Already liked";
    }
}

$conn->close();
?>
