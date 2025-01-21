<?php
include_once 'db.php';
session_start();

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!$email || !$password) {
        header('Location: login.php?empty');
    } else {
        // Query to get the user's stored hashed password
        $query = "SELECT * FROM user WHERE username = '$email' OR email='$email'";
        $result = mysqli_query($connection, $query);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            // Verify the entered password against the stored hashed password
            if (password_verify($password, $user['password'])) {
                // Password is correct, set session variables
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_id'] = $user['id'];
                header('Location: index.php?dashboard');
            } else {
                // Password is incorrect
                header('Location: login.php?login');
            }
        } else {
            // User not found
            header('Location: login.php?login');
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get action type
    $action = isset($_POST['action']) ? $_POST['action'] : null;

    if ($action === 'selectMeal') {
        // Retrieve meal data from POST
        $mealId = isset($_POST['mealId']) ? $_POST['mealId'] : null;
        $mealName = isset($_POST['mealName']) ? $_POST['mealName'] : null;
        $mealPrice = isset($_POST['mealPrice']) ? $_POST['mealPrice'] : null;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : null;

        // Validate input
        if ($mealId && $mealName && $mealPrice && $quantity) {
            // Store the selected meal in session
            $_SESSION['selectedMeals'][$mealId] = [
                'name' => $mealName,
                'price' => $mealPrice,
                'quantity' => $quantity,
            ];
            echo json_encode(['success' => true, 'message' => 'Meal selected successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid data provided.']);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

function fetchMeals() {
    global $conn; // Ensure you have a global DB connection

    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $search = isset($_POST['search']) ? $_POST['search'] : '';
    $type = isset($_POST['type']) ? $_POST['type'] : '';
    $itemsPerPage = 8;
    $offset = ($page - 1) * $itemsPerPage;

    $sql = "SELECT * FROM meals1 WHERE 1=1";
    $params = [];
    $paramTypes = '';

    if ($search) {
        $sql .= " AND (name LIKE ? OR description LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $paramTypes .= 'ss';
    }

    if ($type) {
        $sql .= " AND type = ?";
        $params[] = $type;
        $paramTypes .= 's';
    }

    $sql .= " LIMIT ? OFFSET ?";
    $params[] = $itemsPerPage;
    $params[] = $offset;
    $paramTypes .= 'ii';

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($paramTypes, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $mealsHtml = '';
    while ($row = $result->fetch_assoc()) {
        $mealsHtml .= "
            <div class='meal-card'>
                <img src='{$row['image']}' alt='{$row['name']}' />
                <h3>{$row['name']}</h3>
                <p>{$row['description']}</p>
                <p><strong>Ksh {$row['price']}</strong></p>
                <button class='selectMealBtn' data-id='{$row['id']}' data-name='{$row['name']}' data-price='{$row['price']}'>Select</button>
            </div>
        ";
    }

    echo json_encode(['success' => true, 'mealsHtml' => $mealsHtml]);
}

if (isset($_POST['add_room'])) {
    $room_type_id = $_POST['room_type_id'];
    $room_no = $_POST['room_no'];

    if ($room_no != '') {
        $sql = "SELECT * FROM room WHERE room_no = '$room_no'";
        if (mysqli_num_rows(mysqli_query($connection, $sql)) >= 1) {
            $response['done'] = false;
            $response['data'] = "Room No Already Exist";
        } else {
            $query = "INSERT INTO room (room_type_id,room_no) VALUES ('$room_type_id','$room_no')";
            $result = mysqli_query($connection, $query);

            if ($result) {
                $response['done'] = true;
                $response['data'] = 'Successfully Added Room';
            } else {
                $response['done'] = false;
                $response['data'] = "DataBase Error";
            }
        }
    } else {

        $response['done'] = false;
        $response['data'] = "Please Enter Room No";
    }

    echo json_encode($response);
}

if (isset($_POST['room'])) {
    $room_id = $_POST['room_id'];

    $sql = "SELECT * FROM room WHERE room_id = '$room_id'";
    $result = mysqli_query($connection, $sql);
    if ($result) {
        $room = mysqli_fetch_assoc($result);
        $response['done'] = true;
        $response['room_no'] = $room['room_no'];
        $response['room_type_id'] = $room['room_type_id'];
    } else {
        $response['done'] = false;
        $response['data'] = "DataBase Error";
    }

    echo json_encode($response);
}

if (isset($_POST['edit_room'])) {
    $room_type_id = $_POST['room_type_id'];
    $room_no = $_POST['room_no'];
    $room_id = $_POST['room_id'];

    if ($room_no != '') {
        $query = "UPDATE room SET room_no = '$room_no',room_type_id = '$room_type_id' where room_id = '$room_id'";
        $result = mysqli_query($connection, $query);

        if ($result) {
            $response['done'] = true;
            $response['data'] = 'Successfully Edit Room';
        } else {
            $response['done'] = false;
            $response['data'] = "DataBase Error";
        }

    } else {

        $response['done'] = false;
        $response['data'] = "Please Enter Room No";
    }

    echo json_encode($response);
}

if (isset($_GET['delete_room'])) {
    $room_id = $_GET['delete_room'];
    $sql = "UPDATE room set deleteStatus = '1' WHERE room_id = '$room_id' AND status IS NULL";
    $result = mysqli_query($connection, $sql);
    if ($result) {
        header("Location:index.php?room_mang&success");
    } else {
        header("Location:index.php?room_mang&error");
    }
}

if (isset($_POST['room_type'])) {
    $room_type_id = $_POST['room_type_id'];

    $sql = "SELECT * FROM room WHERE room_type_id = '$room_type_id' AND status IS NULL AND deleteStatus = '0'";
    $result = mysqli_query($connection, $sql);
    if ($result) {
        echo "<option selected disabled>Select Room Type</option>";
        while ($room = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $room['room_id'] . "'>" . $room['room_no'] . "</option>";
        }
    } else {
        echo "<option>No Available</option>";
    }
}

if (isset($_POST['room_price'])) {
    $room_id = $_POST['room_id'];

    $sql = "SELECT * FROM room NATURAL JOIN room_type WHERE room_id = '$room_id'";
    $result = mysqli_query($connection, $sql);
    if ($result) {
        $room = mysqli_fetch_assoc($result);
        echo $room['price'];
    } else {
        echo "0";
    }
}

if (isset($_POST['booking'])) {
    $room_id = $_POST['room_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $total_price = $_POST['total_price'];
    $name = $_POST['name'];
    $contact_no = $_POST['contact_no'];
    $email = $_POST['email'];
    $id_card_id = $_POST['id_card_id'];
    $id_card_no = $_POST['id_card_no'];
    $address = $_POST['address'];
    $room_no = $_POST['room_no'];

    $customer_sql = "INSERT INTO customer (customer_name,contact_no,email,id_card_type_id,id_card_no,address) VALUES ('$name','$contact_no','$email','$id_card_id','$id_card_no','$address')";
    $customer_result = mysqli_query($connection, $customer_sql);

    if ($customer_result) {
        $customer_id = mysqli_insert_id($connection);
        $booking_sql = "INSERT INTO booking (customer_id,room_id,check_in,check_out,total_price,remaining_price,room_number) VALUES ('$customer_id','$room_id','$check_in','$check_out','$total_price','$total_price','$room_no')";
        $booking_result = mysqli_query($connection, $booking_sql);
        if ($booking_result) {
            $room_stats_sql = "UPDATE room SET status = '1' WHERE room_id = '$room_id'";
            if (mysqli_query($connection, $room_stats_sql)) {
                $response['done'] = true;
                $response['data'] = 'Successfully Booking';
            } else {
                $response['done'] = false;
                $response['data'] = "DataBase Error in status change";
            }
        } else {
            $response['done'] = false;
            $response['data'] = "DataBase Error booking";
        }
    } else {
        $response['done'] = false;
        $response['data'] = "DataBase Error add customer";
    }

    echo json_encode($response);
}

if (isset($_POST['cutomerDetails'])) {
    //$customer_result='';
    $room_id = $_POST['room_id'];

    if ($room_id != '') {
        $sql = "SELECT * FROM room NATURAL JOIN room_type NATURAL JOIN booking NATURAL JOIN customer WHERE room_id = '$room_id' AND payment_status = '0'";
        $result = mysqli_query($connection, $sql);
        if ($result) {
            $customer_details = mysqli_fetch_assoc($result);
            $id_type = $customer_details['id_card_type_id'];
            $query = "select id_card_type from id_card_type where id_card_type_id = '$id_type'";
            $result = mysqli_query($connection, $query);
            $id_type_name = mysqli_fetch_assoc($result);
            $response['done'] = true;
            $response['customer_id'] = $customer_details['customer_id'];
            $response['customer_name'] = $customer_details['customer_name'];
            $response['contact_no'] = $customer_details['contact_no'];
            $response['email'] = $customer_details['email'];
            $response['id_card_no'] = $customer_details['id_card_no'];
            $response['id_card_type_id'] = $id_type_name['id_card_type'];
            $response['address'] = $customer_details['address'];
            $response['remaining_price'] = $customer_details['remaining_price'];
            $response['laundry_cost'] = $customer_details['laundry_cost'];
        } else {
            $response['done'] = false;
            $response['data'] = "DataBase Error";
        }

        echo json_encode($response);
    }
}

if (isset($_POST['booked_room'])) {
    $room_id = $_POST['room_id'];

    $sql = "SELECT * FROM room NATURAL JOIN room_type NATURAL JOIN booking NATURAL JOIN customer WHERE room_id = '$room_id' AND payment_status = '0'";
    $result = mysqli_query($connection, $sql);
    if ($result) {
        $room = mysqli_fetch_assoc($result);
        $response['done'] = true;
        $response['booking_id'] = $room['booking_id'];
        $response['name'] = $room['customer_name'];
        $response['room_no'] = $room['room_no'];
        $response['room_type'] = $room['room_type'];
        $response['check_in'] = date('M j, Y', strtotime($room['check_in']));
        $response['check_out'] = date('M j, Y', strtotime($room['check_out']));
        $response['total_price'] = $room['total_price'];
        $response['remaining_price'] = $room['remaining_price'];
        $response['laundry_cost'] = $room['laundry_cost'];
    } else {
        $response['done'] = false;
        $response['data'] = "DataBase Error";
    }

    echo json_encode($response);
}

if (isset($_POST['check_in_room'])) {
    $booking_id = $_POST['booking_id'];
    $advance_payment = $_POST['advance_payment'];

    if ($booking_id != '') {
        $query = "select * from booking where booking_id = '$booking_id'";
        $result = mysqli_query($connection, $query);
        $booking_details = mysqli_fetch_assoc($result);
        $room_id = $booking_details['room_id'];
        $remaining_price = $booking_details['total_price'] - $advance_payment;

        $updateBooking = "UPDATE booking SET remaining_price = '$remaining_price' where booking_id = '$booking_id'";
        $result = mysqli_query($connection, $updateBooking);
        if ($result) {
            $updateRoom = "UPDATE room SET check_in_status = '1' WHERE room_id = '$room_id'";
            $updateResult = mysqli_query($connection, $updateRoom);
            if ($updateResult) {
                $response['done'] = true;
            } else {
                $response['done'] = false;
                $response['data'] = "Problem in Update Room Check in status";
            }
        } else {
            $response['done'] = false;
            $response['data'] = "Problem in payment";
        }
    } else {
        $response['done'] = false;
        $response['data'] = "Error With Booking";
    }
    echo json_encode($response);
}

if (isset($_POST['check_out_room'])) {
    $booking_id = $_POST['booking_id'];
    $remaining_amount = floatval($_POST['remaining_amount']); // Ensure it is parsed as a float

    if ($booking_id != '') {
        $query = "SELECT * FROM booking WHERE booking_id = '$booking_id'";
        $result = mysqli_query($connection, $query);
        $booking_details = mysqli_fetch_assoc($result);

        $room_id = $booking_details['room_id'];
        $remaining_price = floatval($booking_details['remaining_price']); // Ensure it is parsed as a float
        $laundry_cost = floatval($booking_details['laundry_cost']); // Ensure it is parsed as a float

        // Calculate the total amount due (remaining price + laundry cost)
        $total_amount_due = $remaining_price + $laundry_cost;

        // Compare the total amount with the remaining amount
        if ($total_amount_due == $remaining_amount) {
            // Proceed with checkout process
            $updateBooking = "UPDATE booking SET room_number = NULL, remaining_price = '0', payment_status = '1' WHERE booking_id = '$booking_id'";
            $result = mysqli_query($connection, $updateBooking);

            if ($result) {
                // Update room status
                $updateRoom = "UPDATE room SET status = NULL, check_in_status = '0', check_out_status = '1' WHERE room_id = '$room_id'";
                $updateResult = mysqli_query($connection, $updateRoom);
                
                if ($updateResult) {
                    // Insert into transactions table
                    $transaction_name = "Accommodation Payment - Booking #$booking_id";
                    $transaction_type = "accommodation"; // Corrected typo in "Accommodation"
                    $transaction_query = "INSERT INTO transactions (transaction_name, transaction_type, total_cost, date) 
                                           VALUES ('$transaction_name', '$transaction_type', '$total_amount_due', NOW())";
                    $transaction_result = mysqli_query($connection, $transaction_query);

                    if ($transaction_result) {
                        $response['done'] = true;
                    } else {
                        $response['done'] = false;
                        $response['data'] = "Problem in recording transaction.";
                    }
                } else {
                    $response['done'] = false;
                    $response['data'] = "Problem in updating room check-in status.";
                }
            } else {
                $response['done'] = false;
                $response['data'] = "Problem in updating payment status.";
            }
        } else {
            $response['done'] = false;
            $response['data'] = "The entered amount does not match the total amount due.";
        }
    } else {
        $response['done'] = false;
        $response['data'] = "Error with booking.";
    }

    echo json_encode($response);
}
