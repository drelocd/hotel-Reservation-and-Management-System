<?php
include 'db.php'; // Include the database connection

// Prepare the SQL query to get meal types
$query = "SELECT DISTINCT type FROM meals1";

// Execute the query
$result = $conn->query($query);

// Check if any results are returned
if ($result->num_rows > 0) {
    // Fetch results as an associative array
    $mealTypes = [];
    while ($row = $result->fetch_assoc()) {
        $mealTypes[] = $row['type'];
    }
    echo json_encode($mealTypes);
} else {
    echo json_encode([]);
}
?>
