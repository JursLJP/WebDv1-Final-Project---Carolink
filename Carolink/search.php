<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
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

// Get search query
$search_query = isset($_GET['query']) ? $_GET['query'] : '';

// Search organizations and users
$sql = "
    SELECT 'organization' AS type, organization_id AS id, name, profile_picture FROM Organizations WHERE name LIKE '%$search_query%'
    UNION
    SELECT 'user' AS type, user_id AS id, CONCAT(first_name, ' ', last_name) AS name, profile_picture FROM Users WHERE first_name LIKE '%$search_query%' OR last_name LIKE '%$search_query%'
";
$result = $conn->query($sql);
if (!$result) {
    die("Error: " . $conn->error);
}

// Fetch results
$results = [];
while($row = $result->fetch_assoc()) {
    $results[] = $row;
}

// Return results as JSON
header('Content-Type: application/json');
echo json_encode($results);

$conn->close();
?>
