<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "hotelms");

$query = "SELECT id, type_name, rate_per_kg FROM laundry_type";
$result = $conn->query($query);

$types = [];
while ($row = $result->fetch_assoc()) {
    $types[] = $row;
}

header('Content-Type: application/json');
echo json_encode($types);
$conn->close();
?>
