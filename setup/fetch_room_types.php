<?php
include 'db.php'; // Your database connection file

$sql = "SELECT * FROM room_type";
$result = $conn->query($sql);

$roomTypes = [];
while ($row = $result->fetch_assoc()) {
    $roomTypes[] = $row;
}
echo json_encode($roomTypes);
?>
