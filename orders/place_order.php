<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
// Database connection parameters
include_once 'db.php';

// Read and decode the incoming JSON data
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

// Debugging: Log the incoming raw data
error_log("Raw incoming data: " . $rawData);

// Check if the 'orders' field exists and is an array
if (!isset($data['orders']) || !is_array($data['orders'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid order data received.']);
    exit;
}

$orders = $data['orders'];

if (empty($orders)) {
    echo json_encode(['success' => false, 'message' => 'No orders to process.']);
    exit;
}

// Combine all items from separate orders into one order
$allItems = [];
foreach ($orders as $order) {
    if (isset($order['items']) && is_array($order['items'])) {
        $allItems = array_merge($allItems, $order['items']);
    }
}

// Ensure we have items to process
if (empty($allItems)) {
    echo json_encode(['success' => false, 'message' => 'No items found in orders.']);
    exit;
}

try {
    // Concatenate item names into a single string
    $orderDetails = implode('+', array_map(function ($item) {
        return $item['name'];
    }, $allItems));

    // Calculate the total price for this combined order
    $totalPrice = 0;
    foreach ($allItems as $item) {
        if (!isset($item['price'], $item['quantity'])) {
            throw new Exception("Missing price or quantity for an item.");
        }
        $totalPrice += $item['quantity'] * $item['price'];
    }

    // Get the current date and time
    $orderDate = date('Y-m-d');
    $orderTime = date('H:i:s');

    // Insert the combined order into the database
    $query = "INSERT INTO active_orders (order_details, total_price, order_date, order_time, status) 
              VALUES (?, ?, ?, ?, 'pending')";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sdss", $orderDetails, $totalPrice, $orderDate, $orderTime);

    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $stmt->close();

    // Respond with a success message
    echo json_encode(['success' => true, 'message' => 'Order added to active orders successfully.']);
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error processing order: ' . $e->getMessage()]);
}

// Close the database connection
$conn->close();
?>
