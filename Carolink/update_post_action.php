<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "CarolinkDB"; // Use your actual database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $post_id = $_POST['post_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $sql = "UPDATE Posts SET title = '$title', content = '$content' WHERE post_id = $post_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: user_final.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $conn->close();
}
?>
