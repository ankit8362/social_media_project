<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}
include('../includes/db.php');
if (isset($_GET['post_id']) && is_numeric($_GET['post_id'])){
    $post_id = $_GET['post_id'];
    $user_id = $_SESSION['user_id'];
    $query = "DELETE FROM posts WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $post_id, $user_id);
    if($stmt->execute()){
        header("Location: profile.php");
        exit();
    } else {
        echo "Error deleting post: " . $stmt->error;
    }
} else {
    echo "Invalid post ID.";
}
?>
