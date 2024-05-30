<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "CarolinkDB";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
        exit();
    }

    // Retrieve form data
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Check if user exists
    $sql = "SELECT * FROM Users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . $conn->error]);
        exit();
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            echo json_encode(['success' => true, 'redirect' => 'user_final.php']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Incorrect password.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
