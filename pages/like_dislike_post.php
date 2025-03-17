<?php
header("Content-Type: application/json");
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to perform this action.']);
    exit();
}
include('../includes/db.php');
$user_id = $_SESSION['user_id'];
$post_id = intval($_POST['post_id']);
$action = $_POST['action'];
if (!in_array($action, ['like', 'dislike'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
    exit();
}
$query = "SELECT * FROM post_likes WHERE user_id = ? AND post_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $user_id, $post_id);
$stmt->execute();
$result = $stmt->get_result(); 
$current_action = null;
if ($result->num_rows > 0){
    $row = $result->fetch_assoc();
    $current_action = is_null($row['deleted_at']) ? $row['action'] : null;
    if ($row['action'] === $action && is_null($row['deleted_at'])) {
        $soft_delete_query = "UPDATE post_likes SET deleted_at = NOW() WHERE user_id = ? AND post_id = ?";
        $soft_delete_stmt = $conn->prepare($soft_delete_query);
        $soft_delete_stmt->bind_param('ii', $user_id, $post_id);
        $soft_delete_stmt->execute();
        $current_action = null;
    } else {
        $reactivate_query = "UPDATE post_likes SET action = ?, deleted_at = NULL, updated_at = NOW() WHERE user_id = ? AND post_id = ?";
        $reactivate_stmt = $conn->prepare($reactivate_query);
        $reactivate_stmt->bind_param('sii', $action, $user_id, $post_id);
        $reactivate_stmt->execute();
        $current_action = $action;
    }
} else {
    $insert_query = "INSERT INTO post_likes (user_id, post_id, action, created_at, updated_at, deleted_at) VALUES (?, ?, ?, NOW(), NOW(), NULL)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param('iis', $user_id, $post_id, $action);
    $insert_stmt->execute();
    $current_action = $action;
}
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
$update_post_query = "UPDATE posts SET likes = ?, dislikes = ? WHERE id = ?";
$update_post_stmt = $conn->prepare($update_post_query);
$update_post_stmt->bind_param('iii', $count_data['likes'], $count_data['dislikes'], $post_id);
$update_post_stmt->execute();
echo json_encode([
    'status' => 'success',
    'likes' => $count_data['likes'],
    'dislikes' => $count_data['dislikes'],
    'user_action' => $current_action
]);
exit();
