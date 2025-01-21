<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
header('Content-Type: application/json');  // Set Content-Type to JSON

include_once 'db.php';

// Get parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';

$itemsPerPage = 8;
$offset = ($page - 1) * $itemsPerPage;

// Base SQL query for fetching meals
$sql = "SELECT * FROM meals1 WHERE 1=1";
$params = [];
$paramTypes = '';

// Add search condition
if ($search) {
    $sql .= " AND (name LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $paramTypes .= 'ss';
}

// Add type condition
if ($type) {
    $sql .= " AND type = ?";
    $params[] = $type;
    $paramTypes .= 's';
}

// Add pagination
$sql .= " LIMIT ? OFFSET ?";
$params[] = $itemsPerPage;
$params[] = $offset;
$paramTypes .= 'ii';

// Prepare the SQL query
$stmt = $conn->prepare($sql);

// Bind parameters dynamically
$stmt->bind_param($paramTypes, ...$params);

// Execute query
$stmt->execute();
$result = $stmt->get_result();

/// Generate the meals HTML
$mealsHtml = '';
while ($row = $result->fetch_assoc()) {
    // Prepend the 'uploads/' directory to the stored filename
    $imageSrc = 'uploads/' . $row['image']; // Full path to the image

    // Check if the image file exists before outputting
    if (file_exists(__DIR__ . '/../' . $imageSrc)) {
        // Image exists, use the relative path stored in the database
        $imageUrl = $imageSrc;
    } else {
        // Fallback image if the file is not found
        $imageUrl = 'uploads/default-image.jpg'; // Path to a default image
    }

    $mealsHtml .= "
        <div class='meal-card'>
            <img src='{$imageUrl}' alt='{$row['name']}' width='300' height='150' />
            <h3>{$row['name']}</h3>
            <p>{$row['description']}</p>
            <p><strong>Ksh {$row['price']}</strong></p>
            <button class='selectMealBtn' data-id='{$row['id']}' data-name='{$row['name']}' data-price='{$row['price']}'>Select</button>
        </div>
    ";
}


// Query for total rows
$countSql = "SELECT COUNT(*) AS total FROM meals1 WHERE 1=1";
$countParams = [];
$countParamTypes = '';

// Add search condition for the count query
if ($search) {
    $countSql .= " AND (name LIKE ? OR description LIKE ?)";
    $countParams[] = "%$search%";
    $countParams[] = "%$search%";
    $countParamTypes .= 'ss';
}

// Add type condition for the count query
if ($type) {
    $countSql .= " AND type = ?";
    $countParams[] = $type;
    $countParamTypes .= 's';
}

// Prepare the count SQL query
$countStmt = $conn->prepare($countSql);

// Bind parameters dynamically for the count query
if (!empty($countParams)) {
    $countStmt->bind_param($countParamTypes, ...$countParams);
}

// Execute the count query
$countStmt->execute();
$countResult = $countStmt->get_result();
$totalRows = $countResult->fetch_assoc()['total'];

// Check if there are more meals for the next page
$hasNextPage = $totalRows > ($page * $itemsPerPage);

// Return JSON response
echo json_encode([
    'success' => true,
    'mealsHtml' => $mealsHtml,
    'hasNextPage' => $hasNextPage
]);

$conn->close();
?>
