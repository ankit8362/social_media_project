<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

include('../includes/db.php');

// Check if the post_id is passed correctly
if (isset($_GET['post_id']) && is_numeric($_GET['post_id'])) {
    $post_id = $_GET['post_id'];
    $user_id = $_SESSION['user_id'];

    // Delete the post if it belongs to the logged-in user
    $query = "DELETE FROM posts WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $post_id, $user_id);

    if ($stmt->execute()) {
        // Redirect to the profile page after deletion
        header("Location: profile.php");
        exit();
    } else {
        echo "Error deleting post: " . $stmt->error;
    }
} else {
    echo "Invalid post ID.";
}
?>
