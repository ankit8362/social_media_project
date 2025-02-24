<?php
include '../includes/db.php';

if (isset($_GET['query'])) {
    $search = trim($_GET['query']);
    $search = $conn->real_escape_string($search);

    // Search query updated with the correct column names
    $sql = "SELECT * FROM users WHERE full_name LIKE '%$search%' OR email LIKE '%$search%'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Search Results:</h2>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li><a href='profile.php?id=" . $row['id'] . "'><strong>" . htmlspecialchars($row['full_name']) . "</strong> (" . htmlspecialchars($row['email']) . ")</a></li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No users found.</p>";
    }
} else {
    echo "<p>Please enter a search query.</p>";
}
?>
