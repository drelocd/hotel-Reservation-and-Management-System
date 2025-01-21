<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $mealId = $_POST['mealId'];
    $mealName = $_POST['mealName'];
    $mealPrice = $_POST['mealPrice'];
    $quantity = $_POST['quantity'];

    if (!isset($_SESSION['activeOrder'])) {
        $_SESSION['activeOrder'] = [];
    }

    $_SESSION['activeOrder'][$mealId] = [
        'name' => $mealName,
        'price' => $mealPrice,
        'quantity' => $quantity
    ];

    echo json_encode(['success' => true]);
    exit;
}
?>
