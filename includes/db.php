<?php
// db.php - Database connection

$host = "localhost";  // Your MySQL host (usually localhost)
$username = "root";   // Your MySQL username (usually root)
$password = "";       // Your MySQL password (usually empty by default)
$dbname = "social_media"; // Your database name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
