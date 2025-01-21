<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "hotelms");

$query = "SELECT room_id, room_no FROM room WHERE deleteStatus = 0";
$result = $conn->query($query);

$rooms = [];
while ($row = $result->fetch_assoc()) {
    $rooms[] = $row;
}

header('Content-Type: application/json');
echo json_encode($rooms);
$conn->close();
?>
