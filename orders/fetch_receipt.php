<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

header('Content-Type: application/json');

include_once 'db.php';
$orderId = $_GET['order_id'];

// Fetch hotel name from setup table
$setupQuery = "SELECT hotel_name FROM setup LIMIT 1";
$setupResult = $conn->query($setupQuery);
$hotelName = $setupResult->fetch_assoc()['hotel_name'];

// Fetch order details
$orderQuery = "SELECT * FROM active_orders WHERE id = ?";
$stmt = $conn->prepare($orderQuery);
$stmt->bind_param('i', $orderId);
$stmt->execute();
$orderResult = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch current user's name
$userQuery = "SELECT name FROM user WHERE id = ?";
$userStmt = $conn->prepare($userQuery);
$userStmt->bind_param('i', $_SESSION['user_id']);
$userStmt->execute();
$userResult = $userStmt->get_result()->fetch_assoc();
$userStmt->close();

// Decode items JSON or split by '+' if not JSON
$orderDetails = $orderResult['order_details'];

// Check if orderDetails contains a '+' indicating multiple items
if (strpos($orderDetails, '+') !== false) {
    // Split the string into individual items
    $orderItems = array_map('trim', explode('+', $orderDetails));

    // Create a simple array without item prices (just the names and quantities)
    $structuredItems = [];
    foreach ($orderItems as $item) {
        // Extract the item name and quantity from the format "Name (xQuantity)"
        if (preg_match('/^(.*?) \(x(\d+)\)$/', $item, $matches)) {
            $structuredItems[] = [
                'name' => $matches[1],
                'quantity' => (int)$matches[2],
            ];
        } else {
            // Handle malformed item strings gracefully
            $structuredItems[] = ['name' => $item, 'quantity' => 1];
        }
    }

    $orderItems = $structuredItems; // Use the structured array
} else {
    // Handle single item order
    if (!empty($orderDetails)) {
        $orderItems = [['name' => $orderDetails, 'quantity' => 1]];  // Treat it as a single item order
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid order details format.']);
        exit;
    }
}

// Build response
$response = [
    'hotel_name' => $hotelName,
    'order_date' => $orderResult['order_date'],
    'order_time' => $orderResult['order_time'],
    'items' => $orderItems,
    'total_price' => $orderResult['total_price'], // Total price only
    'served_by' => $userResult['name']
];

echo json_encode($response);

$conn->close();
?>
