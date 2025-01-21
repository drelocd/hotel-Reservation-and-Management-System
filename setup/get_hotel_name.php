<?php
include 'db.php';

// Query to fetch the hotel name from the setup table
$sql = "SELECT hotel_name FROM setup WHERE id = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(["success" => true, "hotel_name" => $row['hotel_name']]);
} else {
    echo json_encode(["success" => false]);
}
?>
