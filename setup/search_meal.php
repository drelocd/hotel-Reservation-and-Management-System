<?php
// search_meal.php
$conn = new mysqli("localhost", "root", "", "hotelms");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $sql = "SELECT * FROM meals1 WHERE name LIKE '%$query%'";
    $result = $conn->query($sql);

    $meals = [];
    while ($row = $result->fetch_assoc()) {
        $meals[] = $row;
    }

    echo json_encode($meals);
}

$conn->close();
?>
