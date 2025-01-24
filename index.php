<?php
// Redirect to signin page if user is already logged in
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: pages/profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media Homepage</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome to the Social Media</h1>
        <p>Connect, Share, and Engage with friends and family.</p>

        <div class="actions">
            <a href="pages/signin.php" class="btn">Sign In</a>
            <a href="pages/signup.php" class="btn">Sign Up</a>
        </div>
    </div>
</body>
</html>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f1f1f1;
        text-align: center;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        margin: 100px auto;
        background-color: white;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h1 {
        color: #333;
    }

    p {
        color: #555;
        margin: 20px 0;
    }

    .actions {
        margin-top: 30px;
    }

    .btn {
        text-decoration: none;
        color: white;
        background-color: #007BFF;
        padding: 15px 30px;
        border-radius: 5px;
        font-size: 16px;
        margin: 10px;
        display: inline-block;
    }

    .btn:hover {
        background-color: #0056b3;
    }
</style>
