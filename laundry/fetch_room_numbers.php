<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Database connection (adjust your credentials)
$host = 'localhost';
$dbname = 'hotelms';
$username = 'root';
$password = '';

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if there is a search term
    if (isset($_GET['searchTerm'])) {
        $searchTerm = $_GET['searchTerm'];
        // Prepare SQL query to fetch room numbers that match the search term and have deleteStatus = 0
        $stmt = $pdo->prepare("SELECT room_no FROM room WHERE room_no LIKE :searchTerm AND deleteStatus = 0");
        $stmt->execute(['searchTerm' => "%$searchTerm%"]);
    } else {
        // Default query to fetch all room numbers where deleteStatus = 0
        $stmt = $pdo->query("SELECT room_no FROM room WHERE deleteStatus = 0");
    }

    // Fetch the results
    $roomNumbers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the results as JSON
    echo json_encode(['roomNumbers' => $roomNumbers]);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
