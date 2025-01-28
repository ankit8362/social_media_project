<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to perform this action.']);
    exit();
}

include('../includes/db.php');

$user_id = $_SESSION['user_id'];
$post_id = intval($_POST['post_id']);
$action = $_POST['action']; // 'like' or 'dislike'

// Validate action
if (!in_array($action, ['like', 'dislike'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
    exit();
}

// Check if the user has already liked/disliked the post (including soft-deleted entries)
$query = "SELECT * FROM post_likes WHERE user_id = ? AND post_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $user_id, $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User already liked/disliked this post
    $row = $result->fetch_assoc();

    if ($row['action'] === $action && is_null($row['deleted_at'])) {
        // If the same action is clicked again, soft-delete the record
        $soft_delete_query = "UPDATE post_likes SET deleted_at = NOW() WHERE user_id = ? AND post_id = ?";
        $soft_delete_stmt = $conn->prepare($soft_delete_query);
        $soft_delete_stmt->bind_param('ii', $user_id, $post_id);
        $soft_delete_stmt->execute();
    } else {
        // Reactivate or update the action (in case it was soft-deleted or a different action is selected)
        $reactivate_query = "UPDATE post_likes SET action = ?, deleted_at = NULL, updated_at = NOW() WHERE user_id = ? AND post_id = ?";
        $reactivate_stmt = $conn->prepare($reactivate_query);
        $reactivate_stmt->bind_param('sii', $action, $user_id, $post_id);
        $reactivate_stmt->execute();
    }
} else {
    // User has not liked/disliked this post yet, insert a new record
    $insert_query = "INSERT INTO post_likes (user_id, post_id, action, created_at, updated_at, deleted_at) VALUES (?, ?, ?, NOW(), NOW(), NULL)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param('iis', $user_id, $post_id, $action);
    $insert_stmt->execute();
}

// Count active likes and dislikes for the post
$count_query = "SELECT 
    SUM(action = 'like' AND deleted_at IS NULL) AS likes, 
    SUM(action = 'dislike' AND deleted_at IS NULL) AS dislikes 
FROM post_likes 
WHERE post_id = ?";
$count_stmt = $conn->prepare($count_query);
$count_stmt->bind_param('i', $post_id);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$count_data = $count_result->fetch_assoc();

// Update the `posts` table for likes and dislikes
$update_post_query = "UPDATE posts SET likes = ?, dislikes = ? WHERE id = ?";
$update_post_stmt = $conn->prepare($update_post_query);
$update_post_stmt->bind_param('iii', $count_data['likes'], $count_data['dislikes'], $post_id);
$update_post_stmt->execute();

// Return the updated counts
echo json_encode([
    'status' => 'success',
    'likes' => $count_data['likes'],
    'dislikes' => $count_data['dislikes']
]);
exit();
?>
