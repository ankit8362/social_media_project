<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$post_id = $_POST['post_id'];
$action = $_POST['action'];
$user_id = $_SESSION['user_id'];

// Validate input
if (!in_array($action, ['like', 'dislike'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    exit();
}

// Update the post's like or dislike count
if ($action === 'like') {
    $query = "UPDATE posts SET likes = likes + 1 WHERE id = ?";
} else {
    $query = "UPDATE posts SET dislikes = dislikes + 1 WHERE id = ?";
}

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $post_id);
$stmt->execute();

// Get the updated counts
$query = "SELECT likes, dislikes FROM posts WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

echo json_encode([
    'status' => 'success',
    'likes' => $post['likes'],
    'dislikes' => $post['dislikes'],
]);
?>
