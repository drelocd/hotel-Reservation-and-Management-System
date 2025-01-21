<?php
include 'db.php';

$sql = "SELECT id, name, email, role FROM user";
$result = $conn->query($sql);

$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode(['success' => true, 'users' => $users]);
} else {
    echo json_encode(['success' => false]);
}
?>
