<?php
session_start();
include('../includes/db.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $description = htmlspecialchars($_POST['description']);

    // Handle image upload
    $image_name = null;
    if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] === 0) {
        $target_dir = "../images/";
        $image_name = time() . "_" . basename($_FILES['post_image']['name']);
        $target_file = $target_dir . $image_name;

        // Move uploaded file to the target directory
        if (!move_uploaded_file($_FILES['post_image']['tmp_name'], $target_file)) {
            die("Error uploading image.");
        }
    }

    // Insert post into the database
    $query = "INSERT INTO posts (user_id, description, image, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iss', $user_id, $description, $image_name);

    if ($stmt->execute()) {
        header("Location: profile.php");
        exit();
    } else {
        echo "Error creating post.";
    }
}
?>
<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body> 
</body>
</html> -->
