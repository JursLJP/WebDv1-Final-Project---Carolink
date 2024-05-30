<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CarolinkDB";  // Use your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT organization_id, name, profile_picture FROM Organizations";
$result = $conn->query($sql);

$organizations = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $organizations[] = $row;
    }
}

$conn->close();
echo json_encode($organizations);
?>
