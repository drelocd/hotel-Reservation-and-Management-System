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
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

// Query to fetch laundry types
$query = "SELECT id, type_name, rate_per_kg FROM laundry_type";

try {
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $laundryTypes = [];

        // Fetch each row
        while ($row = $result->fetch_assoc()) {
            $laundryTypes[] = [
                'id' => $row['id'],
                'name' => $row['type_name'], // 'name' key for JavaScript compatibility
                'rate' => $row['rate_per_kg']
            ];
        }

        // Return JSON response
        echo json_encode(['laundryTypes' => $laundryTypes]);
    } else {
        echo json_encode(['laundryTypes' => []]); // Return empty array if no types exist
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
}

// Close connection
$conn->close();
?>
