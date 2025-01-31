<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Include the database connection
include('../includes/db.php');

// Fetch user details from the database
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Check if the user exists
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
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script>
    function handleLikeDislike(post_id, action) {
        console.log("Button clicked. Post ID:", post_id, "Action:", action);

        fetch('like_dislike_post.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `post_id=${post_id}&action=${action}`,
        })
        .then(response => response.json())
        .then(data => {
            console.log("Response from server:", data);
            if (data.status === 'success') {
                document.getElementById(`likes-count-${post_id}`).innerText = data.likes;
                document.getElementById(`dislikes-count-${post_id}`).innerText = data.dislikes;

                // Update button styles
                document.getElementById(`like-btn-${post_id}`).classList.toggle('active', action === 'like');
                document.getElementById(`dislike-btn-${post_id}`).classList.toggle('active', action === 'dislike');
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function confirmDelete(post_id) {
        if (confirm("Are you sure you want to delete this post?")) {
            window.location.href = "delete_post.php?post_id=" + post_id;
        }
    }
    </script>
</head>
<body>
    <div class="container">
        <h1>Your Profile</h1>

        <div class="profile">
            <img src="../images/<?php echo htmlspecialchars($user['profile_pic']) ? htmlspecialchars($user['profile_pic']) : 'default-profile.png'; ?>" alt="Profile Picture" class="profile-pic">
        
            <div class="profile-info">
                <p><a href="editProfile.php" class="edit-icon">
                   <i class="fas fa-pencil-alt"></i>
                </a></p>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($user['full_name']); ?></p>
                <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($user['dob']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            </div>
        </div>

        <div class="create-post">
            <h2>Create a New Post</h2>
            <form action="create_post.php" method="POST" enctype="multipart/form-data">
                <textarea name="description" placeholder="Write your post..." required></textarea><br>
                <input type="file" name="post_image" required><br>
                <button type="submit">Post</button>
            </form>
        </div>

        <div class="user-posts">
            <h2>Your Posts</h2>
            <?php
            // Fetch user posts
            $post_query = "SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC";
            $post_stmt = $conn->prepare($post_query);
            $post_stmt->bind_param('i', $user_id);
            $post_stmt->execute();
            $post_result = $post_stmt->get_result();
            
            while ($post = $post_result->fetch_assoc()) {
                echo '<div class="post">';
                echo '<a href="javascript:void(0);" onclick="confirmDelete(' . $post['id'] . ');" class="delete-btn">X</a>';
                echo '<p>' . htmlspecialchars($post['description']) . '</p>';
                if ($post['image']) {
                    echo '<img src="../images/' . htmlspecialchars($post['image']) . '" alt="Post Image">';
                }
                echo '<p><strong>Likes:</strong> <span id="likes-count-' . $post['id'] . '">' . $post['likes'] . '</span> <strong>Dislikes:</strong> <span id="dislikes-count-' . $post['id'] . '">' . $post['dislikes'] . '</span></p>';
                echo '<button id="like-btn-' . $post['id'] . '" class="like-btn" onclick="handleLikeDislike(' . $post['id'] . ', \'like\')">👍 Like</button>';
                echo '<button id="dislike-btn-' . $post['id'] . '" class="dislike-btn" onclick="handleLikeDislike(' . $post['id'] . ', \'dislike\')">👎 Dislike</button>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</body>
</html>