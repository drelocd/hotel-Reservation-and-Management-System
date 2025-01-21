<?php
// Start the session only if none is active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not authenticated
    exit; // Ensure no further code is executed
}




// Hardcoded database connection details
$servername = "localhost"; // Your database server
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "hotelms"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define how many orders to display per page
$orders_per_page = 6;

// Get the current page number from the URL (defaults to 1 if not set)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $orders_per_page;

// Get the selected date and order type from the URL (defaults to today and pending)
$selected_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$order_type = isset($_GET['type']) ? $_GET['type'] : 'pending';

// Ensure order_type is a valid value ('pending' or 'paid')
$order_type = ($order_type === 'paid' || $order_type === 'pending') ? $order_type : 'pending';

// Use prepared statements to fetch orders based on the selected type and date (limited to the current page)
$query = "SELECT * FROM active_orders WHERE status = ? AND order_date = ? LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);

// Bind parameters: "ssii" means two strings (order_type, selected_date) and two integers (orders_per_page, offset)
$stmt->bind_param("ssii", $order_type, $selected_date, $orders_per_page, $offset);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Initialize an array to store orders
$orders = [];

if ($result && $result->num_rows > 0) {
    // Fetch all orders from the result set
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

// Query to get the total number of orders for pagination (using prepared statements)
$total_query = "SELECT COUNT(*) AS total FROM active_orders WHERE status = ? AND order_date = ?";
$total_stmt = $conn->prepare($total_query);
$total_stmt->bind_param("ss", $order_type, $selected_date);
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$total_orders = $total_result->fetch_assoc()['total'];

// Calculate the total number of pages
$total_pages = ceil($total_orders / $orders_per_page);

// Close the database connection
$conn->close();
?>

<style>
/* Basic styling for the modal */
/* Basic styling for the modal */
.modal {
    display: none; /* Hidden by default */
    position: fixed;
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
    padding-top: 60px;
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 400px;
    text-align: center;
}

.hidden {
    display: none;
}

/* Button styling */
button {
    margin: 5px;
}

</style>
<!-- Payment Modal -->
<div id="paymentModal" class="modal hidden">
    <div class="modal-content">
        <h2>Payment Options</h2>
        <p>Select the payment method:</p>
        
        <!-- Payment method selection -->
        <button class="btn btn-success" onclick="processPayment('cash')">Cash</button>
        <button class="btn btn-primary" onclick="processPayment('mpesa')">Mpesa</button>
        <button class="btn btn-secondary" onclick="toggleModal('paymentModal', false)">Cancel</button>
        <button class="btn btn-info" onclick="printReceipt()">Print Receipt</button>
    </div>
</div>

<div class="text-right">
    <button class="btn btn-danger" onclick="window.location.href='http://localhost/hotel/index.php?Meals'">Place Order</button>
</div>
<!-- HTML to display the orders -->
<div class="content">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><em class="fa fa-plates"></em></a></li>
            <li class="active">Active Orders</li>
        </ol>
    </div>

    <!-- Date picker and order type filter (Pending/Paid) in the same row -->
    <div class="form-group d-flex justify-content-between mb-3">
        <!-- Date Picker (on the left side) -->
        <div class="col-md-8">
            <label for="orderDate">Select Date:</label>
            <input type="date" id="orderDate" name="orderDate" class="form-control" value="<?php echo $selected_date; ?>" onchange="filterByDate()">
        </div>

        <!-- Order Type Filter (on the right side) -->
        <div class="col-md-4">
            <label for="orderType">Order Status:</label>
            <select id="orderType" name="orderType" class="form-control" onchange="filterByOrderType()">
                <option value="pending" <?php echo $order_type === 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="paid" <?php echo $order_type === 'paid' ? 'selected' : ''; ?>>Paid</option>
            </select>
        </div>
    </div>
    <?php if (count($orders) > 0): ?>
    <!-- If there are active orders, display them in a table -->
    <table class="table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Order Details</th>
                <th>Total Price</th>
                <th>Time Ordered</th>
                <?php if ($order_type === 'pending'): ?>
                    <th>Action</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td><?php echo $order['id']; ?></td>
                <td><?php echo $order['order_details']; ?></td>
                <td>Ksh <?php echo number_format($order['total_price'], 2); ?></td>
                <td><?php echo $order['order_time']; ?></td>
                <?php if ($order_type === 'pending'): ?>
                    <td>
                        <button class="btn btn-warning" onclick="openPaymentModal(<?php echo $order['id']; ?>)">Payment</button>
                    </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination controls -->
    <div class="pagination-controls text-center">
        <?php if ($page > 1): ?>
            <!-- Back button will be visible if we're not on the first page -->
            <a href="?orders&page=<?php echo $page - 1; ?>&date=<?php echo $selected_date; ?>&type=<?php echo $order_type; ?>" class="btn btn-secondary">Back</a>
        <?php endif; ?>

        <?php if ($page < $total_pages): ?>
            <!-- Next button will be visible if there are more pages to show -->
            <a href="?orders&page=<?php echo $page + 1; ?>&date=<?php echo $selected_date; ?>&type=<?php echo $order_type; ?>" class="btn btn-primary">Next</a>
        <?php endif; ?>
    </div>

    <?php else: ?>
        <!-- If no active orders are found, show a message -->
        <p>No active orders found for the selected date.</p>
    <?php endif; ?>
</div>

<!-- JavaScript to handle the date and order type filters -->
<script>
    function filterByDate() {
        var selectedDate = document.getElementById('orderDate').value;
        var orderType = document.getElementById('orderType').value;
        window.location.href = "index.php?orders&page=1&date=" + selectedDate + "&type=" + orderType;  // Reload with selected date and order type
    }

    function filterByOrderType() {
        var orderType = document.getElementById('orderType').value;
        var selectedDate = document.getElementById('orderDate').value;
        window.location.href = "index.php?orders&page=1&date=" + selectedDate + "&type=" + orderType;  // Reload with selected order type and date
    }

let currentOrderId = null;  // Store the current order ID to process payment for

// Function to open the payment modal
function openPaymentModal(orderId) {
    currentOrderId = orderId;  // Set the current order ID
    toggleModal('paymentModal', true);  // Open the payment modal
}

// Function to toggle modal visibility
// Function to toggle modal visibility
function toggleModal(modalId, show) {
    const modal = document.getElementById(modalId);
    console.log("Toggling modal", show);  // Debugging line
    
    if (show) {
        modal.classList.remove('hidden');  // Show the modal
        modal.style.display = "block"; // Ensure the modal is shown
    } else {
        modal.classList.add('hidden');  // Hide the modal
        modal.style.display = "none";  // Ensure the modal is hidden
    }
}

// Function to open the payment modal
function openPaymentModal(orderId) {
    currentOrderId = orderId;  // Set the current order ID
    toggleModal('paymentModal', true);  // Open the payment modal
}

// Function to process payment (either Cash or Mpesa)
function processPayment(method) {
    if (!currentOrderId) {
        alert('No order selected!');
        return;
    }

    // AJAX request to update the order status in the database based on payment method
    $.ajax({
        url: 'orders/process_payment.php',  // PHP file to process payment
        type: 'POST',
        data: {
            order_id: currentOrderId,
            payment_method: method
        },
        success: function(response) {
            const data = JSON.parse(response);
            if (data.success) {
                // Close the payment modal
                toggleModal('paymentModal', false);
                alert('Payment processed successfully!');
                location.reload();  // Reload the page to update order status
            } else {
                alert('Error processing payment: ' + data.message);
            }
        },
        error: function() {
            alert('An error occurred while processing payment.');
        }
    });
}

function printReceipt() {
    if (!currentOrderId) {
        alert('No order selected for printing!');
        return;
    }

    // Fetch the receipt details from the server
    fetch(`orders/fetch_receipt.php?order_id=${currentOrderId}`)
        .then(response => response.json())
        .then(data => {
            // Generate receipt content with improved styling
            const receiptContent = `
                <div style="text-align: center; font-family: Arial, sans-serif; padding: 20px; color: #333;">
                    <h2 style="color: #007BFF; font-size: 24px; margin-bottom: 10px;">${data.hotel_name}</h2>
                    <p style="font-size: 16px; margin: 5px 0;"><strong>Date:</strong> ${data.order_date}</p>
                    <p style="font-size: 16px; margin: 5px 0;"><strong>Time:</strong> ${data.order_time}</p>
                    <hr style="border: 1px solid #ddd; margin: 20px 0;">
                    
                    <table style="width: 100%; text-align: left; border-collapse: collapse; margin-bottom: 20px;">
                        <thead>
                            <tr style="background-color: #f4f4f4; font-size: 16px; color: #333;">
                                <th style="padding: 8px 12px; border-bottom: 1px solid #ddd;">Item</th>
                                <th style="padding: 8px 12px; border-bottom: 1px solid #ddd;">Quantity</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 14px;">
                            ${data.items.map(item => `
                                <tr>
                                    <td style="padding: 8px 12px; border-bottom: 1px solid #ddd;">${item.name}</td>
                                    <td style="padding: 8px 12px; border-bottom: 1px solid #ddd;">${item.quantity}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                    
                    <hr style="border: 1px solid #ddd; margin: 20px 0;">
                    <h3 style="font-size: 20px; color: #007BFF; margin: 10px 0;">Total: Ksh ${data.total_price}</h3>
                    <p style="font-size: 16px; margin: 5px 0;"><strong>Served By:</strong> ${data.served_by}</p>
                    <p style="font-size: 14px; margin-top: 20px;">&copy; Developed by McLogic Systems</p>
                </div>
            `;

            // Open a new window for printing
            const newWindow = window.open('', '', 'width=500,height=400');
            newWindow.document.write('<html><head><title>Receipt</title>');
            newWindow.document.write('<style>');
            newWindow.document.write('body { font-family: Arial, sans-serif; color: #333; margin: 0; padding: 0; }');
            newWindow.document.write('h2, h3 { color: #007BFF; }');
            newWindow.document.write('table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }');
            newWindow.document.write('th, td { padding: 8px 12px; border-bottom: 1px solid #ddd; }');
            newWindow.document.write('tr:nth-child(even) { background-color: #f9f9f9; }');
            newWindow.document.write('hr { border: 1px solid #ddd; margin: 20px 0; }');
            newWindow.document.write('</style>');
            newWindow.document.write('</head><body>');
            newWindow.document.write(receiptContent);
            newWindow.document.write('</body></html>');
            newWindow.document.close();

            // Trigger print dialog
            newWindow.print();
        })
        .catch(error => {
            console.error('Error fetching receipt:', error);
            alert('Failed to fetch receipt details.');
        });
}

</script>
