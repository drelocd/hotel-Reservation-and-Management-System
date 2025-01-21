<?php
include 'db.php';

$date = isset($_GET['date']) ? $_GET['date'] : '';
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = 5;  // Number of transactions per page
$offset = ($page - 1) * $limit;

// Build the SQL query to fetch transactions for the selected date
$query = "SELECT * FROM transactions WHERE DATE(date) = ? LIMIT $limit OFFSET $offset";

// Prepare the statement
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $date);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the transactions
$transactions = [];
while ($row = $result->fetch_assoc()) {
    $transactions[] = $row;
}

// Fetch total number of transactions for pagination purposes
$totalQuery = "SELECT COUNT(*) AS total FROM transactions WHERE DATE(date) = ?";
$totalStmt = $conn->prepare($totalQuery);
$totalStmt->bind_param('s', $date);
$totalStmt->execute();
$totalResult = $totalStmt->get_result();
$totalRow = $totalResult->fetch_assoc();
$totalTransactions = $totalRow['total'];

// Calculate pagination
$totalPages = ceil($totalTransactions / $limit);
$prevPage = $page > 1;
$nextPage = $page < $totalPages;

// Return the results as JSON
echo json_encode([
    'success' => true,
    'transactions' => $transactions,
    'prevPage' => $prevPage,
    'nextPage' => $nextPage
]);
?>
