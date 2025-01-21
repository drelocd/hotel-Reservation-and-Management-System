<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];
$field = $data['field'];
$value = $data['value'];

$sql = "UPDATE room_type SET $field = ? WHERE room_type_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $value, $id);

$response = ["success" => $stmt->execute()];
echo json_encode($response);
?>
