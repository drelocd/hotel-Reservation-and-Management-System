<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Database connection
$host = "localhost"; // Change as needed
$user = "root";      // Change as needed
$password = "";      // Change as needed
$dbname = "hotelms"; // Change as needed

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get ref_num from request
if (isset($_GET['ref_num'])) {
    $refNum = $conn->real_escape_string($_GET['ref_num']);

    // Query to fetch weight, laundry_type_id, and room_id from the laundry table
    $laundryQuery = "
        SELECT 
            weight, 
            laundry_type, 
            room_id 
        FROM 
            laundry 
        WHERE 
            reference_number = '$refNum'
    ";
    $laundryResult = $conn->query($laundryQuery);

    if ($laundryResult->num_rows > 0) {
        $laundryData = $laundryResult->fetch_assoc();
        $weight = $laundryData['weight'];
        $laundryTypeId = $laundryData['laundry_type'];
        $roomId = $laundryData['room_id'];

        // Query to fetch laundry type name from the laundry_type table
        $laundryTypeQuery = "
            SELECT 
                type_name 
            FROM 
                laundry_type 
            WHERE 
                id = '$laundryTypeId'
        ";
        $laundryTypeResult = $conn->query($laundryTypeQuery);
        $laundryTypeName = $laundryTypeResult->num_rows > 0 
            ? $laundryTypeResult->fetch_assoc()['type_name'] 
            : null;

        // Query to fetch room number from the room table
        $roomQuery = "
            SELECT 
                room_no 
            FROM 
                room 
            WHERE 
                room_id = '$roomId'
        ";
        $roomResult = $conn->query($roomQuery);
        $roomNo = $roomResult->num_rows > 0 
            ? $roomResult->fetch_assoc()['room_no'] 
            : null;

        // Combine all details into the response
        if ($laundryTypeName && $roomNo) {
            $response = [
                'status' => 'success',
                'data' => [
                    'weight' => $weight,
                    'laundry_name' => $laundryTypeName,
                    'room_no' => $roomNo
                ]
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Failed to retrieve laundry or room details.'
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // No matching laundry found
        echo json_encode(['status' => 'error', 'message' => 'No laundry job found with this reference number.']);
    }
} else {
    // Missing ref_num
    echo json_encode(['status' => 'error', 'message' => 'Reference number is required.']);
}

// Close connection
$conn->close();
?>
