<?php
header('Content-Type: application/json'); // Ensure the response is JSON
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CarolinkDB"; // Use your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

// Retrieve form data
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email2'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$birthdate = $_POST['birthdate'];
$gender = $_POST['gender'];

// Handle file upload
$targetDir = "uploads/";
$pdfFileName = basename($_FILES["pdfFile"]["name"]);
$targetFilePath = $targetDir . $pdfFileName;
$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

if (isset($_FILES["pdfFile"]) && $_FILES["pdfFile"]["error"] == 0) {
    // Allow certain file formats
    $allowedTypes = array('pdf');
    if (in_array($fileType, $allowedTypes)) {
        // Upload file to server
        if (move_uploaded_file($_FILES["pdfFile"]["tmp_name"], $targetFilePath)) {
            // Insert user data into the database
            $sql = "INSERT INTO Users (first_name, last_name, email, password, birthdate, gender, study_load_pdf)
                    VALUES ('$firstName', '$lastName', '$email', '$password', '$birthdate', '$gender', '$targetFilePath')";
            if ($conn->query($sql) === TRUE) {
                // Redirect to redirectToLogin.php after successful sign-up
                echo json_encode(['success' => true, 'redirect' => 'redirectToLogin.php']);
                exit();
            } else {
                echo json_encode(['success' => false, 'message' => 'Error inserting record: ' . $conn->error]);
                exit();
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'File upload failed. Unable to move the file.']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid file type. Only PDF files are allowed.']);
        exit();
    }
} else {
    $uploadError = $_FILES["pdfFile"]["error"];
    echo json_encode(['success' => false, 'message' => "File upload error: $uploadError"]);
    exit();
}

$conn->close();
?>
