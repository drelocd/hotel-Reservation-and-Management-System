<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$newHotelName = $data['hotel_name'];

// Protect against SQL injection
$newHotelName = $conn->real_escape_string($newHotelName);

// Update hotel name in the setup table
$sql = "UPDATE setup SET hotel_name = ? WHERE id = 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $newHotelName);

$response = ["success" => $stmt->execute()];
echo json_encode($response);
?>
