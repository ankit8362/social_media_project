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

    $sqlUpdate = "UPDATE users SET full_name='$fullName', dob='$dob' WHERE id='$userId'";
    if ($conn->query($sqlUpdate) === TRUE) {
        header("Location: profile.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<form method="POST">
    Full Name: <input type="text" name="fullName" value="<?php echo $user['full_name']; ?>" required><br>
    Date of Birth: <input type="date" name="dob" value="<?php echo $user['dob']; ?>" required><br>
    <input type="submit" value="Update">
</form>
