<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$userId = $data['userId'];

$query = "DELETE FROM user WHERE id = '$userId'";

if (mysqli_query($conn, $query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
