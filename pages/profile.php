<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}
$user_id = $_SESSION['user_id'];
include('../includes/db.php');
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
if ($user === null) {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.2.0/remixicon.css">
    <!-- Font Awesome CDN for Icons --> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="../assets/js/profile.js"> -->
</head>
<body>
    <div class="container">
        <div class="search-container">
            <form action="search.php" method="GET">
                <input type="text" name="query" placeholder="Search users..." required>
                <button type="submit"><i class="ri-search-line"></i></button>
            </form>
        </div>
        <h1>Your Profile</h1>
        <div class="profile">
    <div class="profile-pic-container">
        <img src="../images/<?php echo htmlspecialchars($user['profile_pic']) ? htmlspecialchars($user['profile_pic']) : 'default-profile.png'; ?>" 
             alt="Profile Picture" class="profile-pic">
        <a href="editProfile.php" class="edit-icon">
            <i class="fas fa-pencil-alt"></i>
        </a>
    </div>
    <div class="profile-info">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['full_name']); ?></p>
        <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($user['dob']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    </div>
</div>

        <div class="create-post">
            <h2>Create a New Post</h2>
            <form action="create_post.php" method="POST" enctype="multipart/form-data">
                <textarea name="description" placeholder="Write your post..." required></textarea><br>
                <input type="file" name="post_image"><br>
                <button type="submit">Post</button>                
            </form>
        </div>

        <div class="user-posts">
            <h2>Your Posts</h2>
            <?php
            $post_query = "SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC";
            $post_stmt = $conn->prepare($post_query);
            $post_stmt->bind_param('i', $user_id);
            $post_stmt->execute();
            $post_result = $post_stmt->get_result();
            
            while ($post = $post_result->fetch_assoc()) {
                echo '<div class="post">';
                echo '<a href="javascript:void(0);" onclick="confirmDelete(' . $post['id'] . ');" class="delete-btn"><i class="ri-close-large-line"></i></a>';
                echo '<p class="description">' . htmlspecialchars($post['description']) . '</p>';
                if ($post['image']) {
                    echo '<img src="../images/' . htmlspecialchars($post['image']) . '" alt="Post Image">';
                }
                echo '<p>
                <button id="like-btn-' . $post['id'] . '" class="like-btn" onclick="handleLikeDislike(' . $post['id'] . ', \'like\')">
                     <i class="ri-thumb-up-line"></i> <span id="likes-count-' . $post['id'] . '">' . $post['likes'] . '</span>
                </button>
                <button id="dislike-btn-' . $post['id'] . '" class="dislike-btn" onclick="handleLikeDislike(' . $post['id'] . ', \'dislike\')">
                    <i class="ri-thumb-down-line"></i> <span id="dislikes-count-' . $post['id'] . '">' . $post['dislikes'] . '</span>
                </button>
              </p>';
        
                echo '</div>';
            }
            ?>
        </div>
        <button class="logout" style="color:white;">
               <a href="logout.php" class="logout">Logout</a>
               </button>
    </div>

    <script src="../assets/js/profile_like_dislike.js"></script>
</body>
</html>

<!-- <button  class="like-btn">
                    <i class="ri-chat-3-line"></i><span > 2 </span>
                </button> -->