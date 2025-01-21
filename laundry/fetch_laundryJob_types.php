<?php
// Database connection
$host = 'localhost';  // Change if necessary
$dbname = 'hotelms';  // Your database name
$username = 'root';    // Database username
$password = '';        // Database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch laundry types
    $stmt = $pdo->query("SELECT id, type_name FROM laundry_type");
    $types = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON response
    echo json_encode($types);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
