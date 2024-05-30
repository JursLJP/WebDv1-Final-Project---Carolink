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
    die("Connection failed: " . $conn->connect_error);
}

// Get user ID from URL
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 1;

// Fetch user details
$user_sql = "SELECT first_name, last_name, email, profile_picture FROM Users WHERE user_id = $user_id";
$user_result = $conn->query($user_sql);
$user = $user_result->fetch_assoc();
$user_name = $user['first_name'] . ' ' . $user['last_name'];
$user_email = $user['email'];
$user_profile_picture = $user['profile_picture'] ? $user['profile_picture'] : 'assets/USER ICON.png';

// Fetch user details of logged-in user
$logged_in_user_id = $_SESSION['user_id'];
$logged_in_user_sql = "SELECT first_name, profile_picture FROM Users WHERE user_id = $logged_in_user_id";
$logged_in_user_result = $conn->query($logged_in_user_sql);
$logged_in_user_data = $logged_in_user_result->fetch_assoc();
$logged_in_user_first_name = $logged_in_user_data['first_name'];
$logged_in_user_profile_picture = $logged_in_user_data['profile_picture'] ? $logged_in_user_data['profile_picture'] : 'assets/USER ICON.png';

$conn->close();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $user_name; ?></title>
<link rel="stylesheet" href="user_final.css"> <!-- Ensure this path is correct -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');

    searchInput.addEventListener('input', function() {
        const query = searchInput.value;

        if (query.length > 0) {
            fetch(`search.php?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    searchResults.innerHTML = '';
                    data.forEach(item => {
                        const resultItem = document.createElement('div');
                        resultItem.classList.add('search-result-item');

                        const resultImage = document.createElement('img');
                        resultImage.src = item.profile_picture;
                        resultImage.alt = `${item.name}'s profile picture`;

                        const resultText = document.createElement('span');
                        resultText.textContent = item.name;

                        resultItem.appendChild(resultText);
                        resultItem.appendChild(resultImage);
                        resultItem.addEventListener('click', () => {
                            if (item.type === 'organization') {
                                window.location.href = `org_page.php?org_id=${item.id}`;
                            } else if (item.type === 'user') {
                                window.location.href = `user_page.php?user_id=${item.id}`;
                            }
                        });

                        searchResults.appendChild(resultItem);
                    });
                    searchResults.style.display = 'block';
                });
        } else {
            searchResults.innerHTML = '';
            searchResults.style.display = 'none';
        }
    });
});
</script>
</head>
<title>User • Carolink</title><link rel="icon" type="image/x-icon" href="images/favicon.ico">
<body class="user-final">
<div class="nav-bar">
    <div class="logo">
        <a href="user_final.php" class="carolink-1"></a>
        <div class="usc-log"></div>
    </div>
    <div class="nav-items">
        <a href="user_final.php" class="home-button">Home</a>
        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Search...">
            <button type="submit">Search</button>
            <div id="search-results" class="search-results"></div>
        </div>
        <div class="profile">
            <a href="profile.php"><img src="<?php echo htmlspecialchars($user_profile_picture); ?>" alt="Profile Icon"></a>
            <a href="profile.php" class="profile-name"><?php echo htmlspecialchars($user_first_name); ?></a>
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
    <div class="organization-header">
        <img src="<?php echo $user_profile_picture; ?>" alt="User Profile Picture" class="organization-logo-large">
        <div class="organization-details">
            <h1 class="organization-name"><?php echo $user_name; ?></h1>
            <p class="organization-description"><?php echo $user_email; ?></p>
        </div>
    </div>
</div>
</body>
</html>
