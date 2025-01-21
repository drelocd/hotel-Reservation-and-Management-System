<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];

$sql = "DELETE FROM room_type WHERE room_type_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

$response = ["success" => $stmt->execute()];
echo json_encode($response);
?>
