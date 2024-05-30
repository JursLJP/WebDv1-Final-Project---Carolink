<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CarolinkDB"; // Use your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get organization ID from URL
$organization_id = isset($_GET['org_id']) ? $_GET['org_id'] : 1;

// Fetch organization details
$org_sql = "SELECT * FROM Organizations WHERE organization_id = $organization_id";
$org_result = $conn->query($org_sql);
$organization = $org_result->fetch_assoc();

// Fetch posts for the organization
$post_sql = "
SELECT p.post_id, p.title, p.content, p.created_at, p.user_id, p.post_type,
o.name AS organization_name, o.profile_picture AS organization_profile_picture,
u.first_name, u.last_name, u.profile_picture AS user_profile_picture
FROM Posts p
LEFT JOIN Organizations o ON p.organization_id = o.organization_id
LEFT JOIN Users u ON p.user_id = u.user_id
WHERE p.organization_id = $organization_id OR (p.user_id IS NOT NULL AND p.organization_id = $organization_id)
ORDER BY p.created_at DESC
";
$post_result = $conn->query($post_sql);

// Fetch user details
$user_id = $_SESSION['user_id'];
$user_sql = "SELECT first_name, profile_picture FROM Users WHERE user_id = $user_id";
$user_result = $conn->query($user_sql);
$user_data = $user_result->fetch_assoc();
$user_first_name = $user_data['first_name'];
$user_profile_picture = $user_data['profile_picture'] ? $user_data['profile_picture'] : 'assets/USER ICON.png';

// Fetch all organizations
$all_org_sql = "SELECT organization_id, name, profile_picture FROM Organizations";
$all_org_result = $conn->query($all_org_sql);
$organizations = [];
while ($row = $all_org_result->fetch_assoc()) {
    $organizations[] = $row;
}

$conn->close();
?>
<!doctype html>
<html lang="en">
<head>
<title>Organization • Carolink</title>
<link rel="icon" type="image/x-icon" href="images/favicon.ico">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $organization['name']; ?></title>
<link rel="stylesheet" href="user_final.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const currentUrl = window.location.href;
    const icons = document.querySelectorAll('.icons a');

    icons.forEach(icon => {
        if (currentUrl.includes(icon.getAttribute('href'))) {
            icon.classList.add('selected');
        }
    });

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

    searchInput.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            window.location.href = `search_results.php?query=${searchInput.value}`;
        }
    });
});
</script>
<script>
$(document).ready(function() {
    $('.btn-like').on('click', function() {
        var $btn = $(this);
        var postId = $btn.data('post-id');
        var liked = $btn.data('liked');

        $.ajax({
            url: 'like_post.php',
            type: 'POST',
            data: {
                post_id: postId,
                liked: !liked
            },
            success: function(response) {
                if (!liked) {
                    $btn.find('img').attr('src', 'assets/like-gesture.png');
                } else {
                    $btn.find('img').attr('src', 'assets/like (1).png');
                }
                $btn.data('liked', !liked);
            }
        });
    });

    $('.btn-delete').on('click', function() {
        var $btn = $(this);
        var postId = $btn.data('post-id');

        if (confirm("Are you sure you want to delete this post?")) {
            $.ajax({
                url: 'delete_post.php',
                type: 'POST',
                data: {
                    post_id: postId
                },
                success: function(response) {
                    if (response === 'Success') {
                        $btn.closest('.user-post-card').remove();
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        }
    });

    $('.btn-edit').on('click', function() {
        var postId = $(this).data('post-id');
        window.location.href = 'edit_post.php?post_id=' + postId;
    });
});
</script>
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
        <?php foreach ($organizations as $org): ?>
            <a href="org_page.php?org_id=<?php echo $org['organization_id']; ?>">
                <img src="<?php echo htmlspecialchars($org['profile_picture']); ?>" alt="<?php echo htmlspecialchars($org['name']); ?>">
                <?php echo htmlspecialchars($org['name']); ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>
<div class="main-content">
    <div class="organization-header">
        <img src="<?php echo htmlspecialchars($organization['profile_picture']); ?>" alt="Organization Logo" class="organization-logo-large">
        <div class="organization-details">
            <h1 class="organization-name"><?php echo htmlspecialchars($organization['name']); ?></h1>
            <p class="organization-description"><?php echo htmlspecialchars($organization['description']); ?></p>
        </div>
    </div>
    <div class="posts-header">
        <h2 style="padding-right: 40px;">Posts</h2>
        <a href="create_post.php" class="create-post-button">Create Post</a>
    </div>
    <div class="posts-container">
        <?php while($post = $post_result->fetch_assoc()): ?>
            <?php if (is_null($post['post_type'])): // Check if the post is made by a user ?>
                <div class="post">
                    <div class="user-post-header">
                        <img src="<?php echo htmlspecialchars($post['user_profile_picture']); ?>" alt="Profile Picture" class="user-profile-picture">
                        <div>
                            <strong><?php echo htmlspecialchars($post['first_name'] . ' ' . $post['last_name']); ?></strong> <br>
                            <small class="text-muted"><?php echo date('F j, Y', strtotime($post['created_at'])); ?></small>
                        </div>
                        <?php if ($post['user_id'] == $user_id): ?>
                            <div class="post-actions">
                                <button class="btn-edit" data-post-id="<?php echo $post['post_id']; ?>">
                                    <img src="assets/pen.png" alt="Edit" style="width: 16px; height: 16px;">
                                </button>
                                <button class="btn-delete" data-post-id="<?php echo $post['post_id']; ?>">
                                    <img src="assets/trash-can.png" alt="Delete" style="width: 16px; height: 16px;">
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="user-post-content">
                        <div class="user-post-title"><?php echo htmlspecialchars($post['title']); ?></div>
                        <p><?php echo htmlspecialchars($post['content']); ?></p>
                    </div>
                    <div class="user-post-actions">
                        <!--
                        <button class="user-btn-like" data-post-id="<?php echo $post['post_id']; ?>" data-liked="false">
                            <img src="assets/like (1).png" alt="Like">
                        </button>
                        <button class="user-btn-comment">Comment</button>
                        -->
                    </div>
                </div>
            <?php else: ?>
                <div class="post">
                    <div class="post-header">
                        <img src="<?php echo htmlspecialchars($post['organization_profile_picture']); ?>" alt="Organization Logo" class="organization-logo">
                        <div class="organization-name"><?php echo htmlspecialchars($post['organization_name']); ?></div>
                        <div class="post-date"><?php echo date('F j, Y, g:i a', strtotime($post['created_at'])); ?></div>
                    </div>
                    <h2 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h2>
                    <p class="post-content"><?php echo htmlspecialchars($post['content']); ?></p>
                </div>
            <?php endif; ?>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>

