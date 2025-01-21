<?php
$host = "localhost";  // Change to your database host if necessary
$user = "root";       // Change to your database username
$password = "";       // Change to your database password
$dbname = "hotelms"; // Change to your actual database name

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
