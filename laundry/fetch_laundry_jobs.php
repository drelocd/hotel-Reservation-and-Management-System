<?php
require 'db.php'; // Your database connection file

// Get search term, filter type, date, and pagination details
$search = isset($_GET['search']) ? $_GET['search'] : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');  // Default to current date if no date is provided
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 5;
$offset = ($page - 1) * $limit;

try {
    $query = "SELECT l.id, r.room_no, l.weight, 
    DATE_FORMAT(l.date_created, '%H:%i:%s') AS time_created, 
    l.total_cost, l.status, l.reference_number
    FROM laundry l
    JOIN room r ON l.room_id = r.room_id
    WHERE DATE(l.date_created) = :date 
    AND r.deletestatus = 0";

    // Array to hold parameters for prepared statements
    $params = [':date' => $date];

    // Check if there is a search term
    if (!empty($search)) {
        $query .= " AND (l.reference_number LIKE :search OR r.room_no LIKE :search)";
        $params[':search'] = "%$search%";
    }

    // Fix: Compare directly if the filter is an ID
    if (!empty($filter)) {
        $query .= " AND l.laundry_type = :filter";
        $params[':filter'] = $filter;
    }

    // Add pagination
    $query .= " ORDER BY l.date_created DESC LIMIT $limit OFFSET $offset";

    // Prepare and execute query
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get total pages
    $countQuery = "SELECT COUNT(*) 
                   FROM laundry l 
                   JOIN room r ON l.room_id = r.room_id 
                   WHERE DATE(l.date_created) = :date 
                   AND r.deletestatus = 0";

    $countStmt = $pdo->prepare($countQuery);
    $countStmt->execute([':date' => $date]);
    $totalJobs = $countStmt->fetchColumn();
    $totalPages = ceil($totalJobs / $limit);

    // Return JSON response
    echo json_encode([
        "status" => "success",
        "jobs" => $jobs,
        "totalPages" => $totalPages
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}

?>
