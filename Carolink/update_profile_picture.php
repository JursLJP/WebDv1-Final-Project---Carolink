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

// Handle file upload
$targetDir = "assets/";
$profilePictureName = basename($_FILES["profile_picture"]["name"]);
$targetFilePath = $targetDir . $profilePictureName;
$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

if (isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["error"] == 0) {
    // Allow certain file formats
    $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
    if (in_array(strtolower($fileType), $allowedTypes)) {
        // Upload file to server
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFilePath)) {
            // Update user data in the database
            $sql = "UPDATE Users SET profile_picture = '$targetFilePath' WHERE user_id = $user_id";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(['success' => true, 'file_path' => $targetFilePath]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error updating record: ' . $conn->error]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'File upload failed.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Only JPG, JPEG, PNG, and GIF files are allowed.']);
    }
} else {
    $uploadError = $_FILES["profile_picture"]["error"];
    echo json_encode(['success' => false, 'message' => "File upload error: $uploadError"]);
}

$conn->close();
?>
