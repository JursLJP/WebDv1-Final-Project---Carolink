<?php
session_start();
include('db_connection.php'); // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Ensure the user owns the post
    $post = fetchPostById($post_id); // Replace with your actual function
    if ($post && $_SESSION['user_id'] === $post['user_id']) {
        // Update the post
        $conn = new mysqli($servername, $username, $password, $dbname);
        $stmt = $conn->prepare("UPDATE Posts SET title = ?, content = ? WHERE post_id = ?");
        $stmt->bind_param("ssi", $title, $content, $post_id);
        if ($stmt->execute()) {
            header('Location: user_final.php');
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "You do not have permission to edit this post.";
    }
} else {
    echo "Invalid request method.";
}
?>
