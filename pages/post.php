<?php
session_start();
include '../includes/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $image = $_FILES['image']['name'];
    $uploadDir = "../images/";
    $uploadFile = $uploadDir . basename($image);
    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)){

        $userId = $_SESSION['user_id'];
        $sql = "INSERT INTO posts (user_id, description, image, created_at) 
                VALUES ('$userId', '$description', '$image', NOW())";
        if ($conn->query($sql) === TRUE) {
            header("Location: profile.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else{
        echo "Error uploading image. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
</head>
<body>
    <h2>Create a Post</h2>
    <form method="POST" enctype="multipart/form-data" action="post.php">
        <label for="description">Description:</label><br>
        <textarea name="description" id="description" required></textarea><br><br>
        <label for="image">Image:</label><br>
        <input type="file" name="image" id="image" required><br><br>
        <input type="submit" value="Create Post">
    </form>
</body>
</html>
