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

// Fetch user data
$user_id = $_SESSION['user_id'];
$user_sql = "SELECT first_name, last_name, email, profile_picture FROM Users WHERE user_id = $user_id";
$user_result = $conn->query($user_sql);
$user_data = $user_result->fetch_assoc();
$user_first_name = $user_data['first_name'];
$user_last_name = $user_data['last_name'];
$user_full_name = $user_first_name . ' ' . $user_last_name;
$user_email = $user_data['email'];
$user_profile_picture = $user_data['profile_picture'] ? $user_data['profile_picture'] : 'assets/USER ICON.png';

$conn->close();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Profile</title>
<link rel="stylesheet" href="profile.css">
</head>
<body>
<div class="nav-bar">
<div class="logo">
<a href="user_final.php" class="carolink-1"></a>
<div class="usc-log"></div>
</div>
<div class="nav-items">
<div class="search-bar">
<input type="text" placeholder="Search...">
<button type="submit">Search</button>
</div>
</div>
</div>
<div class="main-content">
<div class="profile-container">
<div class="profile-header">
<img src="<?php echo $user_profile_picture; ?>" alt="Profile Picture" class="profile-picture" id="profile-picture">
<input type="file" id="profile-picture-input" class="hidden">
<button id="change-picture-button" class="profile-info-button">Change Picture</button>
<h1 id="user-name"><?php echo $user_full_name; ?></h1>
<div id="name-inputs" class="hidden">
<input type="text" id="first-name-input" value="<?php echo $user_first_name; ?>" placeholder="First Name" class="text-input">
<input type="text" id="last-name-input" value="<?php echo $user_last_name; ?>" placeholder="Last Name" class="text-input">
</div>
</div>
<div class="profile-info">
<div class="info-item">
<label for="email">Email:</label>
<span id="email-display"><?php echo $user_email; ?></span>
</div>
<div class="info-item">
<label for="password">Password:</label>
<input type="password" id="password-input" placeholder="New Password" class="text-input">
</div>
<button id="edit-button" class="profile-info-button">Edit Profile</button>
<button id="save-button" class="profile-info-button hidden">Save Changes</button>
<button id="logout-button" class="profile-info-button">Logout</button>
</div>
</div>
</div>
<script src="profile.js"></script>
</body>
</html>
