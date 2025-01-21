<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Clear the selected meals in the session
unset($_SESSION['selectedMeals']);

echo json_encode(['success' => true, 'message' => 'Cart cleared.']);
?>
