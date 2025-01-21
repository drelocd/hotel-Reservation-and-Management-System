<?php
require 'db.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ref_num'])) {
    $ref_num = $_POST['ref_num'];

    try {
        // SQL query to delete the job from the laundry table based on the reference number
        $query = "DELETE FROM laundry WHERE reference_number = :ref_num";

        $stmt = $pdo->prepare($query);
        $stmt->execute([':ref_num' => $ref_num]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(["status" => "success", "message" => "Job deleted successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "No active job found with the given reference number."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>
