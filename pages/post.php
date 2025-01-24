<?php
session_start();
include '../includes/db.php';

// Redirect user to signin page if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and get form data
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $image = $_FILES['image']['name'];
    $uploadDir = "../images/";  // Directory where images will be uploaded
    $uploadFile = $uploadDir . basename($image);
    
    // Attempt to upload the image
    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
        // Get user ID from session
        $userId = $_SESSION['user_id'];
        
        // Insert post into the database (including created_at timestamp)
        $sql = "INSERT INTO posts (user_id, description, image, created_at) 
                VALUES ('$userId', '$description', '$image', NOW())";
        
        // Check if query is successful
        if ($conn->query($sql) === TRUE) {
            // Redirect to profile page if successful
            header("Location: profile.php");
            exit();
        } else {
            // Show error if query fails
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        // If image upload fails, show an error
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
    <!-- Post creation form -->
    <form method="POST" enctype="multipart/form-data" action="post.php">
        <label for="description">Description:</label><br>
        <textarea name="description" id="description" required></textarea><br><br>

        <label for="image">Image:</label><br>
        <input type="file" name="image" id="image" required><br><br>

        <input type="submit" value="Create Post">
    </form>
</body>
</html>
