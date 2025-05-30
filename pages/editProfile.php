<?php
session_start();
include '../includes/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$userId = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id='$userId'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullName = $_POST['fullName'];
    $dob = $_POST['dob'];
//  if (!empty($_FILES['profilePic']['name'])){
//     $profilePic = $_FILES['profilePic']['name'];
//     $target_dir = "../images/";
//     $target_file = $target_dir . basename($profilePic); 
//     if(move_uploaded_file($_FILES['profilePic']['tmp_name'], $target_file)){
//         $sqlUpdate = "UPDATE users SET full_name='$fullName', dob='$dob', profile_pic='$profilePic' WHERE id='$userId'";      
//     }else{
//         echo "Failed to upload profile picture.";
//         exit;
//     }
// }else{
//     $sqlUpdate = "UPDATE users SET full_name='$fullName', dob='$dob' WHERE id='$userId'";
// }

    $sqlUpdate = "UPDATE users SET full_name='$fullName', dob='$dob' WHERE id='$userId'";
    if ($conn->query($sqlUpdate)=== TRUE){
        header("Location: profile.php");
        exit();
    }else{
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Profile</h1>
        <form method="POST" class="signup-form" enctype="multipart/form-data">
            <div class="input-group">
                <label for="fullName">Full Name:</label>
                <input type="text" id="fullName" name="fullName" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
            </div>
            <div class="input-group">
                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($user['dob']); ?>" required>
            </div>
            <!-- <label for=profilePic>Upload New Profile Picture:</label>
                <input type="file" name="profilePic" id="ProfilePic"> -->
            <button type="submit" class="submit-btn">Update</button>
        </form>
    </div>
</body>
</html>
