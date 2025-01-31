<?php
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullName = $_POST['fullName'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $profilePic = $_FILES['profilePic']['name'];

    $uploadDir = "../images/";
    $uploadFile = $uploadDir . basename($profilePic);
    move_uploaded_file($_FILES['profilePic']['tmp_name'], $uploadFile);

    $sql = "INSERT INTO users (full_name, dob, email, password, profile_pic) 
            VALUES ('$fullName', '$dob', '$email', '$password', '$profilePic')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
        header("Location: signin.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Sign Up</h1>

        <form method="POST" enctype="multipart/form-data" class="signup-form">
            <div class="input-group">
                <label for="fullName">Full Name:</label>
                <input type="text" name="fullName" id="fullName" required>
            </div>

            <div class="input-group">
                <label for="dob">Date of Birth:</label>
                <input type="date" name="dob" id="dob" required>
            </div>

            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div class="input-group">
                <label for="repassword">Confirm Password:</label>
                <input type="password" name="repassword" id="repassword" required>
            </div>

            <div class="input-group">
                <label for="profilePic">Profile Picture:</label>
                <input type="file" name="profilePic" id="profilePic" required>
            </div>

            <input type="submit" value="Sign Up" class="submit-btn">
        </form>

        <div class="signin-link">
            <a href="signin.php">Already have an account? Sign In</a>
        </div>
    </div>
    <script src="../assets/js/password.js"></script>

</body>
</html>
