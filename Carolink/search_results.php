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

// Get search query from URL
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

// Fetch user details of logged-in user
$user_id = $_SESSION['user_id'];
$user_sql = "SELECT first_name, profile_picture FROM Users WHERE user_id = $user_id";
$user_result = $conn->query($user_sql);
$user_data = $user_result->fetch_assoc();
$user_first_name = $user_data['first_name'];
$user_profile_picture = $user_data['profile_picture'] ? $user_data['profile_picture'] : 'assets/USER ICON.png';

$conn->close();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Search Results • Carolink</title><link rel="icon" type="image/x-icon" href="images/favicon.ico">
<link rel="stylesheet" href="user_final.css"> <!-- Ensure this path is correct -->
</head>
<body class="user-final">
<div class="nav-bar">
    <div class="logo">
        <a href="user_final.php" class="carolink-1"></a>
        <div class="usc-log"></div>
    </div>
    <div class="nav-items">
        <a href="user_final.php" class="home-button">Home</a>
        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Search..." value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit">Search</button>
            <div id="search-results" class="search-results"></div>
        </div>
        <div class="profile">
            <img src="<?php echo htmlspecialchars($user_profile_picture); ?>" alt="Profile Icon">
            <span class="profile-name"><?php echo htmlspecialchars($user_first_name); ?></span>
            <a href="logout.php" class="logout-button">Logout</a>
        </div>
    </div>
</div>
<div class="side-bar">
    <div class="icons">
        <a href="org_page.php?org_id=1"><img src="assets/USC.png"> University of San Carlos</a>
        <a href="org_page.php?org_id=2"><img src="assets/SSC.png"> USC Supreme Student Council</a>
        <a href="org_page.php?org_id=3"><img src="assets/SAFAD.png"> USC School of Architecture, Fine Arts and Design Council</a>
        <a href="org_page.php?org_id=4"><img src="assets/SAS.png"> USC School of Arts and Sciences Student Council</a>
        <a href="org_page.php?org_id=5"><img src="assets/SBE.png"> USC School of Business and Economics Council</a>
        <a href="org_page.php?org_id=6"><img src="assets/SED.png"> USC School of Education Council</a>
        <a href="org_page.php?org_id=7"><img src="assets/SOE.png"> USC Collegiate Engineering Council</a>
        <a href="org_page.php?org_id=8"><img src="assets/SHCP.png"> USC School of Healthcare Professions Council</a>
        <a href="org_page.php?org_id=9"><img src="assets/SLG.png"> USC Carolinian Political Science Society</a>
    </div>
</div>
<div class="main-content">
    <h1>Search Results for "<?php echo htmlspecialchars($search_query); ?>"</h1>
    <div class="search-results-container">
        <?php if (count($results) > 0): ?>
            <?php foreach ($results as $item): ?>
                <div class="search-result-item" onclick="window.location.href='<?php echo $item['type'] === 'organization' ? 'org_page.php?org_id=' . $item['id'] : 'user_page.php?user_id=' . $item['id']; ?>'">
                    <img src="<?php echo htmlspecialchars($item['profile_picture']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>'s profile picture">
                    <span><?php echo htmlspecialchars($item['name']); ?></span>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No results found</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
