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
    <script>
    function handleLikeDislike(post_id, action) {
        console.log("Button clicked. Post ID:", post_id, "Action:", action); // Debugging line

        fetch('like_dislike_post.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `post_id=${post_id}&action=${action}`,
        })
        .then(response => response.json())
        .then(data => {
            console.log("Response from server:", data); // Debugging line
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
    </script>
</head>
<body>
    <div class="container">
        <h1>Your Profile</h1>

        <div class="profile">
            <img src="../images/<?php echo htmlspecialchars($user['profile_pic']) ? htmlspecialchars($user['profile_pic']) : 'default-profile.png'; ?>" alt="Profile Picture" class="profile-pic">
            
            <div class="profile-info">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($user['full_name']); ?> <a href="editProfile.php">Edit</a></p>
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

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
    }

    .container {
        max-width: 800px;
        margin: 20px auto;
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
        color: #333;
    }

    .profile {
        text-align: center;
        margin-bottom: 20px;
    }

    .profile-pic {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
    }

    .profile-info {
        margin-top: 10px;
    }

    .profile-info p {
        font-size: 16px;
        color: #555;
    }

    .profile-info a {
        font-size: 14px;
        color: #007BFF;
        text-decoration: none;
    }

    .profile-info a:hover {
        text-decoration: underline;
    }

    .create-post {
        margin-top: 30px;
    }

    .create-post form {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .create-post textarea {
        width: 80%;
        height: 100px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .create-post input {
        margin: 10px 0;
    }

    .create-post button {
        background-color: #007BFF;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .create-post button:hover {
        background-color: #0056b3;
    }

    .user-posts {
        margin-top: 40px;
    }

    .user-posts .post {
        margin-bottom: 20px;
        background-color: #f1f1f1;
        padding: 15px;
        border-radius: 8px;
    }

    .user-posts .post img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin-top: 10px;
    }

    .user-posts button {
        padding: 5px 15px;
        margin: 0 5px;
        cursor: pointer;
    }

    .user-posts button.active {
        color: white;
        background-color: #007BFF;
    }

    .user-posts button:hover {
        background-color: #d3d3d3;
    }
</style>
