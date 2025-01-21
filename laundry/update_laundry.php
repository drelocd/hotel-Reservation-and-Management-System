<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "hotelms");

$data = json_decode(file_get_contents('php://input'), true);

$refNum = $conn->real_escape_string($data['ref_num']);
$weight = $conn->real_escape_string($data['weight']);
$laundryTypeId = $conn->real_escape_string($data['laundry_type']);
$roomId = $conn->real_escape_string($data['room_id']);

// Fetch the rate_per_kg for the selected laundry type
$typeQuery = "SELECT rate_per_kg FROM laundry_type WHERE id = '$laundryTypeId'";
$typeResult = $conn->query($typeQuery);

if ($typeResult->num_rows > 0) {
    $ratePerKg = $typeResult->fetch_assoc()['rate_per_kg'];

    // Calculate total cost
    $totalCost = $weight * $ratePerKg;

    // Update the laundry table
    $query = "
        UPDATE laundry
        SET 
            weight = '$weight',
            laundry_type = '$laundryTypeId',
            room_id = '$roomId',
            cost_per_kg = '$ratePerKg',
            total_cost = '$totalCost'
        WHERE reference_number = '$refNum'
    ";

    if ($conn->query($query)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Laundry type not found.']);
}

$conn->close();
?>
