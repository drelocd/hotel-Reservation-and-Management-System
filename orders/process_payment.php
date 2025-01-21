<?php
session_start();

// Include the database connection
include 'db.php'; // Ensure the path is correct

// Check if the connection was established

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Check if required POST data exists
if (isset($_POST['order_id']) && isset($_POST['payment_method'])) {
    $order_id = (int) $_POST['order_id'];
    $payment_method = $_POST['payment_method'];

    // Validate payment method
    if (!in_array($payment_method, ['cash', 'mpesa'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid payment method.']);
        exit;
    }

    // Fetch total_price from active_orders before updating
    if ($conn) {
        $stmt = $conn->prepare("SELECT total_price FROM active_orders WHERE id = ? AND status = 'pending'");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->bind_result($total_price);
        $stmt->fetch();
        $stmt->close();

        if ($total_price !== null) {
            // Update order status to 'paid' and record the payment method
            $update_stmt = $conn->prepare("UPDATE active_orders SET status = 'paid', payment_method = ? WHERE id = ? AND status = 'pending'");
            $update_stmt->bind_param("si", $payment_method, $order_id);

            if ($update_stmt->execute()) {
                $update_stmt->close();

                // Insert transaction record (mapping total_price to total_cost in transactions)
                $transaction_stmt = $conn->prepare("INSERT INTO transactions (transaction_name, transaction_type, total_cost, date) VALUES (?, 'meal', ?, NOW())");
                $transaction_name = "Meal Payment - Order #$order_id";
                $transaction_stmt->bind_param("sd", $transaction_name, $total_price);

                if ($transaction_stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Order paid and transaction recorded successfully!']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Order updated but failed to record transaction.']);
                }

                $transaction_stmt->close();
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update order status.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Order not found or already paid.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Required parameters missing.']);
}

// Close the database connection
$conn->close();
?>
