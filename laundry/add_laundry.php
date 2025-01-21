<?php
session_start();
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotelms";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);

// Validate data
if (empty($data['room_number']) || empty($data['weight']) || empty($data['laundry_type'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    exit;
}

// Get the room number, weight, and laundry type
$roomNumber = $data['room_number'];
$weight = $data['weight'];
$laundryTypeId = $data['laundry_type'];

// Fetch the cost per kg from the laundry_type table
$setupQuery = "SELECT rate_per_kg, type_name FROM laundry_type WHERE id = ?";
$stmt = $conn->prepare($setupQuery);
$stmt->bind_param('i', $laundryTypeId); // Bind the ID as an integer
$stmt->execute();
$stmt->bind_result($ratePerKg, $laundryTypeName);
$stmt->fetch();
$stmt->close();

// Check if the cost per kg and laundry type name were found
if (!$ratePerKg || !$laundryTypeName) {
    echo json_encode(['success' => false, 'message' => 'Laundry type not found.']);
    exit;
}

// Calculate the total cost
$totalCost = $ratePerKg * $weight;

// Fetch the room_id and customer_id based on the room_number
$roomQuery = "SELECT room_id, customer_id FROM booking WHERE room_number = ?";
$stmt = $conn->prepare($roomQuery);
$stmt->bind_param('s', $roomNumber); // Bind the room number as a string
$stmt->execute();
$stmt->bind_result($roomId, $customerId);
$stmt->fetch();
$stmt->close();

// Check if room_id and customer_id were found
if (!$roomId || !$customerId) {
    echo json_encode(['success' => false, 'message' => 'Room or customer not found for the provided room number.']);
    exit;
}

// Fetch the customer name
$customerQuery = "SELECT customer_name FROM customer WHERE customer_id = ?";
$stmt = $conn->prepare($customerQuery);
$stmt->bind_param('i', $customerId);
$stmt->execute();
$stmt->bind_result($customerName);
$stmt->fetch();
$stmt->close();

// Check if customer name was found
if (!$customerName) {
    echo json_encode(['success' => false, 'message' => 'Customer name not found.']);
    exit;
}

// Generate a random reference number
$referenceNumber = rand(100000, 999999);

// Insert the laundry data into the database
$query = "INSERT INTO laundry (room_id, customer_id, laundry_type, cost_per_kg, weight, total_cost, reference_number) 
          VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('iissdss', $roomId, $customerId, $laundryTypeId, $ratePerKg, $weight, $totalCost, $referenceNumber);
$stmt->execute();

// Check for success
if ($stmt->affected_rows > 0) {
    echo json_encode([
        'success' => true, 
        'reference_number' => $referenceNumber,
        'customer_name' => $customerName,
        'laundry_type_name' => $laundryTypeName,
        'cost_per_kg' => $ratePerKg,
        'total_cost' => $totalCost
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add laundry.']);
}

$stmt->close();
$conn->close();
?>
