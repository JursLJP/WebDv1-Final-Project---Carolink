<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CarolinkDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$user_sql = "SELECT first_name, profile_picture FROM Users WHERE user_id = $user_id";
$user_result = $conn->query($user_sql);
$user_data = $user_result->fetch_assoc();
$user_first_name = $user_data['first_name'];
$user_profile_picture = $user_data['profile_picture'] ? $user_data['profile_picture'] : 'assets/USER ICON.png';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['postTitle'];
    $content = $_POST['postContent'];
    $organization_id = $_POST['organizationId']; // Assuming organization ID is provided in the form

    $stmt = $conn->prepare("INSERT INTO Posts (user_id, title, content, organization_id, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("issi", $user_id, $title, $content, $organization_id);

    if ($stmt->execute()) {
        header("Location: user_final.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post • Carolink</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .create-post-card {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-top: 120px; /* Lower the container to avoid being covered by the nav bar */
        }
        .form-control {
            border-radius: 20px;
        }
        .btn-post {
            border-radius: 20px;
            background-color: #28a745;
            color: white;
        }
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .profile-picture {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .nav-bar {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
            background: linear-gradient(90deg, rgba(119, 203, 49, 0.19), #77CB31);
            position: fixed; 
            top: 0; 
            left: 0; 
            padding: 14px 18px; 
            width: 100%; 
            height: 106px;
            box-sizing: border-box; 
            z-index: 1000; 
            display: flex;
            justify-content: space-between; 
            align-items: center; 
        }
        .logo {
            position: relative;
            display: flex;
            padding-top: 38.8px;
            width: 321px;
            height: 92px;
            box-sizing: border-box;
        }
        .carolink-1 {
            background: url('assets/carolink_1.png') 50% / cover no-repeat;
            position: absolute;
            top: 0;
            right: -5px;
            width: 250px;
            height: 70px;
            display: block;
        }
        .usc-log {
            background: url('assets/usc_log.png') 50% / cover no-repeat;
            position: absolute;
            left: 0;
            top: 2.7px;
            width: 81px;
            height: 73.1px;
        }
        .nav-items {
            display: flex;
            align-items: center;
        }
        .search-bar {
            display: flex;
            align-items: center;
            margin-right: 20px; 
            background-color: white;
            border-radius: 25px;
            padding: 5px 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        }
        .search-bar input {
            border: none;
            outline: none;
            padding: 10px;
            border-radius: 20px;
            flex: 1;
            margin-right: 10px;
        }
        .search-bar input::placeholder {
            color: #888;
        }
        .search-bar button {
            padding: 10px 20px;
            background-color: #4CAF50;
            border: none;
            border-radius: 20px;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font: Arial;
            font-size: 16px;
        }
        .search-bar button:hover {
            background-color: #45a049;
        }
        .profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
        .profile-name {
            color: white;
            text-decoration: underline;
            font-size: 16px;
            font: Arial;
            margin-right: 20px;
            cursor: pointer;
        }
        .logout-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            border: none;
            border-radius: 20px;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font: Arial;
            font-size: 16px;
            text-decoration: none;
        }
        .logout-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="nav-bar">
        <div class="logo">
            <a href="user_final.php" class="carolink-1"></a>
            <div class="usc-log"></div>
        </div>
        <div class="nav-items">
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
    <div class="container mt-5">
        <div class="create-post-card shadow-sm">
            <div class="profile-header">
                <img src="<?php echo htmlspecialchars($user_profile_picture); ?>" alt="Profile Picture" class="profile-picture">
                <div>
                    <strong><?php echo htmlspecialchars($user_first_name); ?></strong> <br>
                    <small class="text-muted"><?php echo date("F j, Y"); ?></small>
                </div>
            </div>
            <h5 class="mb-4">Create Post</h5>
            <form method="POST" action="create_post.php">
                <div class="form-group">
                    <input type="text" class="form-control" name="postTitle" placeholder="Title (optional)">
                </div>
                <div class="form-group">
                    <textarea class="form-control" name="postContent" rows="4" placeholder="What's happening?"></textarea>
                </div>
                <div class="form-group" style="margin-left: -10px;">
                    <label for="organizationId">Post to</label>
                    <select class="form-control" name="organizationId">
                        <option value="1">University of San Carlos</option>
                        <option value="2">USC Supreme Student Council</option>
                        <option value="3">USC School of Architecture, Fine Arts and Design Council</option>
                        <option value="4">USC School of Arts and Sciences Student Council</option>
                        <option value="5">USC School of Business and Economics Council</option>
                        <option value="6">USC School of Education Council</option>
                        <option value="7">USC Collegiate Engineering Council</option>
                        <option value="8">USC School of Healthcare Professions Council</option>
                        <option value="9">USC Carolinian Political Science Society</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-post btn-block">Post</button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>