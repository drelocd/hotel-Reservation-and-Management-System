<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$userId = $data['userId'];
$newPassword = $data['password'];

// Hash the new password using bcrypt
$hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

$query = "UPDATE user SET password = '$hashedPassword' WHERE id = '$userId'";

if (mysqli_query($conn, $query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
