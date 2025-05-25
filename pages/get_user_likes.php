<?php
header("Content-Type: application/json");
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT post_id, action FROM post_likes WHERE user_id = ? AND deleted_at IS NULL";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$user_likes = [];
while ($row = $result->fetch_assoc()) {
    $user_likes[$row['post_id']] = $row['action'];
}
echo json_encode(['status' => 'success', 'user_likes' => $user_likes]);
exit();
?>