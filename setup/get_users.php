<?php
include 'db.php';

$sql = "SELECT id, name, username, email, password, created_at, role FROM user LIMIT 5";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $users = [];
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode(["success" => true, "users" => $users]);
} else {
    echo json_encode(["success" => false]);
}
?>
