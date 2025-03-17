<?php
include('../includes/db.php');
session_start();
echo "<br><br><hr><h1>This is posts table</h1><br><br>";
$query = "SELECT * FROM posts";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
echo "<table border=1>";
echo "<tr>";
while($user = $result->fetch_assoc()){
echo "<prev>";
echo "<td>";
print_r($user);
echo "</td>";
echo "</prev>"; 
}
echo "</td>";
echo "</table>";

echo "<br><br><hr><h1>This is users table</h1><br><br>";

$query2 = "SELECT * FROM users";
$stmt2 = $conn->prepare($query2);
$stmt2->execute();
$result2 = $stmt2->get_result();
echo "<table border=1>";
echo "<tr>";
while($user2 = $result2->fetch_assoc()){
// echo "<prev>";
echo "<td>";
print_r($user2);
echo "</td>";
// echo "</prev>"; 
}
echo "</td>";
echo "</table>";

echo "<br><br><hr><h1>This is likes_table</h1><br><br>";

$query3 = "SELECT * FROM post_likes";
$stmt3 = $conn->prepare($query3);
$stmt3->execute();
$result3 = $stmt3->get_result();
echo "<table border=1>";
echo "<tr>";
while($user3 = $result3->fetch_assoc()){
// echo "<prev>";
echo "<td>";
print_r($user3);
echo "</td>";
// echo "</prev>"; 
}
echo "</td>";
echo "</table>";
?>