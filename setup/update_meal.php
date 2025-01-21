<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $id = $data['id'];
    $name = $data['name'];
    $description = $data['description'];
    $price = $data['price'];
    $type = $data['type'];

    $query = "UPDATE meals1 SET name=?, description=?, price=?, type=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssdsi", $name, $description, $price, $type, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
