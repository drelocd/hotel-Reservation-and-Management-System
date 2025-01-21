<?php
require 'db.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['job_id'])) {
    $job_id = $_POST['job_id'];

    try {
        // SQL query to update laundry status and add cost to booking
        $query = "UPDATE booking b
                  JOIN laundry l ON b.customer_id = l.customer_id
                  SET b.laundry_cost = b.laundry_cost + l.total_cost,
                      l.status = 'Complete'
                  WHERE l.id = :job_id AND l.status = 'Active'";

        $stmt = $pdo->prepare($query);
        $stmt->execute([':job_id' => $job_id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(["status" => "success", "message" => "Job Closed successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "No job updated. Check if it's active."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>
