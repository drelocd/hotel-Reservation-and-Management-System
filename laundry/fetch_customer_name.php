<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
// Hardcoded database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$database = "hotelms"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['room_number'])) {
    $roomNumber = $_GET['room_number'];

    try {
        // Step 1: Get the customer_id from the booking table
        $query1 = "SELECT customer_id FROM booking WHERE room_number = ?";
        $stmt1 = $conn->prepare($query1);
        $stmt1->bind_param('s', $roomNumber);
        $stmt1->execute();
        $stmt1->bind_result($customerId);

        if ($stmt1->fetch()) {
            $stmt1->close();

            // Step 2: Use the customer_id to fetch the customer's name from the customer table
            $query2 = "SELECT customer_name FROM customer WHERE customer_id = ?";
            $stmt2 = $conn->prepare($query2);
            $stmt2->bind_param('i', $customerId);
            $stmt2->execute();
            $stmt2->bind_result($customerName);

            if ($stmt2->fetch()) {
                echo json_encode(['customer_name' => $customerName]);
            } else {
                echo json_encode(['error' => 'Customer name not found']);
            }

            $stmt2->close();
        } else {
            echo json_encode(['error' => 'Customer ID not found']);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Room number not provided']);
}

// Close the connection
$conn->close();
?>
