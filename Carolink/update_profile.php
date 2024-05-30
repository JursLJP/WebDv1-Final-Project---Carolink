<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CarolinkDB";  // Use your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

// Retrieve user ID from session
$user_id = $_SESSION['user_id'];

// Retrieve form data
$first_name = $_POST['first_name'];
$password = $_POST['password'];

// Update user data in the database
$sql = "UPDATE Users SET first_name = '$first_name'";

if (!empty($password)) {
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $sql .= ", password = '$hashed_password'";
}

$sql .= " WHERE user_id = $user_id";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error updating record: ' . $conn->error]);
}

$conn->close();
?>
