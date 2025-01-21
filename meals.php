<?php
include_once "db.php"; // Database connection

// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit; // Ensure no further code is executed
}
?>
<!-- Include jQuery (from CDN) -->
<script src="js/jquery-1.11.1.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


<!--show active orders-->
<div class="text-right">
    <button class="btn btn-danger" onclick="window.location.href='http://localhost/hotel/index.php?orders'">Active Orders</button>
</div>


<div class="content">
    <div class="row">
        <ol class="breadcrumb">
        <li><a href="#"><em class="fas fa-utensils"></em></a></li>
            <li class="active">Meals</li>
        </ol>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <input type="text" id="searchMeals" placeholder="Search meals by name or type" onkeyup="searchMeals()" class="form-control">
        </div>
        
        <div class="col-md-4">
            <select id="mealTypeFilter" class="form-control" onchange="filterMeals()">
                <option value="">All Meal Types</option>
                <option value="Breakfast">Breakfast</option>
                <option value="Lunch">Lunch</option>
                <option value="Dinner">Dinner</option>
                <option value="Snacks">Snacks</option>
                <option value="Beverages">Beverages</option>
            </select>
        </div>
    </div>
    <br>

    <div class="panel panel-container">
        <div class="row" id="mealCardsContainer">
            
            <!-- Meal cards load dynamically -->
        </div>
<!-- Notification container -->
<div id="notification-container"></div>
<div id="place-order-notification-container"></div>
        <div class="text-center">
            <button id="prevPage" onclick="changePage(-1)" disabled>Previous</button>
            <button id="nextPage" onclick="changePage(1)" disabled>Next</button>
        </div>
    </div>

    <div class="text-center">
        <button class="btn btn-success" onclick="openOrderSummaryModal()">Place Order</button>
    </div>
    <!-- Modals -->
    <div id="quantityModal" class="modal hidden">
    <div class="modal-content">
    <!-- Notification area for messages -->
    <div id="quantity-notification-container"></div>

        <h2>Enter Quantity</h2>
        <p id="mealName"></p>
        <input type="number" id="mealQuantity" min="1" class="form-control" placeholder="1">
        <button class="btn btn-primary" onclick="addMealToOrder()">Add to Order</button>
        <button class="btn btn-danger" onclick="toggleModal('quantityModal', false)">Cancel</button>
    </div>
</div>


<div id="orderSummaryModal" class="modal hidden">
    <div class="modal-content">
        <h2>Order Summary</h2>
        <div id="orderSummaryList"></div>
        <p><strong>Total: Ksh <span id="totalCost">0</span></strong></p>
        <button class="btn btn-danger" onclick="clearCart()">Clear Cart</button>
<button class="btn btn-primary" onclick="confirmOrder()">Confirm Order</button>
<button class="btn btn-secondary" onclick="toggleModal('orderSummaryModal', false)">Cancel</button>


    </div>
</div>

<!-- Custom Modal CSS -->
<style>
#place-order-notification-container {
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 5px;
    font-size: 14px;
    text-align: center;
    width: 100%;
}

#place-order-notification-container.success {
    background-color: #4CAF50; /* Green */
    color: white;
}

#place-order-notification-container.error {
    background-color: #f44336; /* Red */
    color: white;
}


 #quantity-notification-container {
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 5px;
    font-size: 14px;
    text-align: center;
    width: 100%;
}

#quantity-notification-container.success {
    background-color: #4CAF50; /* Green */
    color: white;
}

#quantity-notification-container.error {
    background-color: #f44336; /* Red */
    color: white;
}


    #notification-container {
    position: fixed;
    top: 10px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999;
    width: 300px;
    padding: 10px;
    display: none;
    border-radius: 5px;
    font-size: 16px;
    text-align: center;
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
}

#notification-container.success {
    background-color: #4CAF50;  /* Green */
    color: white;
}

#notification-container.error {
    background-color: #f44336;  /* Red */
    color: white;
}

    /* Modal container */
    .modal {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1000;
        background: rgba(0, 0, 0, 0.7); /* Semi-transparent background */
        width: 80%; /* Ensures the modal is responsive */
        max-width: 500px; /* Prevents the modal from being too wide */
        padding: 20px;
        border-radius: 10px;
        display: none; /* Initially hidden */
    }

    /* Modal content */
    .modal-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Modal header */
    .modal-content h2 {
        font-size: 1.8em;
        text-align: center;
        margin-bottom: 20px;
    }

    /* Modal buttons */
    .modal-content button {
        margin: 10px 0;
        width: 100%;
    }

    /* Hidden class to hide modal */
    .hidden {
        display: none !important;
    }

    /* Specific modal styles */
    #quantityModal .modal-content {
        max-width: 400px;
        width: 90%;
    }

    #orderSummaryModal .modal-content {
        max-width: 600px;
        width: 90%;
    }

    /* Form control */
    .form-control {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    /* Center the modal content */
    .modal-content p {
        text-align: center;
    }

    /* Background overlay */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
    }
</style>


<script>
    let currentPage = 1;
    let currentSearch = '';
    let currentType = '';
    let activeOrders = []; // This will store meals confirmed by the user
    let currentMeal = {};

    function fetchMeals() {
        $.ajax({
            url: 'orders/fetch_meals.php',
            type: 'GET',
            data: { page: currentPage, search: currentSearch, type: currentType },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#mealCardsContainer').html(response.mealsHtml);
                $('#nextPage').prop('disabled', !response.hasNextPage);
                $('#prevPage').prop('disabled', currentPage === 1);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching meals:", error);
            }
        });
    }

// Handle meal selection to show the modal for quantity entry
$(document).on('click', '.selectMealBtn', function() {
    const mealId = $(this).data('id');
    const mealName = $(this).data('name');
    const mealPrice = $(this).data('price');

    // Store the meal details in the currentMeal object only when it's clicked
    currentMeal = { id: mealId, name: mealName, price: mealPrice };

    // Set the meal name in the modal
    $('#mealName').text(`Meal: ${mealName}`);
    
    // Set the initial quantity to 1
    $('#mealQuantity').val(1);

    // Log to ensure the function is being triggered
    console.log("Meal selected:", currentMeal);
    
    // Show the modal to enter the quantity
    toggleModal('quantityModal', true);
});



// Open Select Quantity Modal
$('#openSelectQuantityModalButton').on('click', function () {
    // Add modal-open class to body to prevent page scroll when modal is open
    $('body').addClass('modal-open');
    // Show the Select Quantity modal
    toggleModal('quantityModal', true);
});

// Close Select Quantity Modal and restore body scroll
$('#quantityModal').on('hidden.bs.modal', function () {
    // Remove modal-open class to re-enable scrolling on the page
    $('body').removeClass('modal-open');
    
    // Remove any lingering backdrop (if you're using Bootstrap)
    $('.modal-backdrop').remove();
    
    // Ensure the body's overflow is reset
    $('body').css('overflow', 'auto');
});

 
// Cancel button logic for the modal
$('#quantityModal .btn-danger').on('click', function () {
    // Log currentMeal before clearing it
    console.log("Cancel button clicked. currentMeal before reset:", currentMeal);

    // Simply close the modal without adding the meal to the cart
    toggleModal('quantityModal', false);

    // Reset the current meal object to ensure nothing is selected
    currentMeal = {}; // Clear the current meal object (so nothing is added)

    console.log("Meal selection canceled. currentMeal reset.");
});



   
   // Function to toggle modal visibility
function toggleModal(modalId, show) {
    if (show) {
        // Remove any existing modal backdrop (if Bootstrap's backdrop was added)
        $('.modal-backdrop').remove();  // Remove any lingering backdrops

        // Show the modal (if not using Bootstrap's modal plugin)
        $('#' + modalId).removeClass('hidden').show();

        // Add modal-open class to prevent scrolling
        $('body').addClass('modal-open');
        $('body').css('overflow', 'hidden');

        // Create a new modal overlay if you're using a custom one
        let backdrop = $('<div class="modal-overlay"></div>');
        $('body').append(backdrop);

        // Close the modal when clicking on the backdrop
        backdrop.on('click', function() {
            toggleModal(modalId, false);
        });
    } else {
        // Hide the modal and remove modal-open class to restore scrolling
        $('#' + modalId).addClass('hidden').hide();
        $('body').removeClass('modal-open');
        $('body').css('overflow', 'auto');

        // Remove the modal overlay and backdrop
        $('.modal-overlay').remove();
        $('.modal-backdrop').remove(); // Ensure Bootstrap backdrop is also removed
    }
}

// Function to show notifications inside the place order modal
function showNotificationInPlaceOrder(message, type) {
    const notificationContainer = document.getElementById('place-order-notification-container');
    notificationContainer.textContent = message;
    notificationContainer.className = type; // Set success or error class

    // Show the notification
    notificationContainer.style.display = 'block';
    setTimeout(() => {
        notificationContainer.style.display = 'none';
    }, 5000); // Hide after 5 seconds
}
// Open the Order Summary Modal
function openOrderSummaryModal() {
    if (activeOrders.length === 0) {
        showNotificationInPlaceOrder('No meals selected to place an order!', 'error');
        return;
    }

    // Generate the order summary HTML using activeOrders
    let orderSummaryHtml = '';
    let totalCost = 0;

    // Loop through activeOrders and render each meal's items
    activeOrders.forEach(order => {
        order.items.forEach(item => {
            const orderTotal = parseFloat(item.price) * item.quantity;
            totalCost += orderTotal;
            orderSummaryHtml += `
                <div>
                    <p>${item.name} (x${item.quantity}) - Ksh ${orderTotal.toFixed(2)}</p>
                </div>
            `;
        });
    });

    // Update the order summary in the modal
    $('#orderSummaryList').html(orderSummaryHtml);
    $('#totalCost').text(totalCost.toFixed(2));

    // Open the modal to display the summary
    toggleModal('orderSummaryModal', true);
}

function clearCart() {
    $.ajax({
        url: 'orders/clear_cart.php', // This file will clear the cart in the session
        type: 'POST',
        success: function(response) {
            alert('Your cart has been cleared.');
            // Close the modal or reload the page
            toggleModal('orderSummaryModal', false);
            location.reload();  // Reload the page to reflect changes
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}
// Function to show notifications inside the quantity modal
function showNotificationInModal(message, type) {
    const notificationContainer = document.getElementById('quantity-notification-container');
    notificationContainer.textContent = message;
    notificationContainer.className = type; // Set success or error class

    // Show the notification
    notificationContainer.style.display = 'block';
    setTimeout(() => {
        notificationContainer.style.display = 'none';
    }, 5000); // Hide after 5 seconds
}
// Function to add the selected meal to the order
function addMealToOrder() {
    if (!currentMeal.id) {
        showNotificationInModal('No meal selected!', 'error');
        return;  // Don't proceed if no meal is selected
    }

    const quantity = parseInt($('#mealQuantity').val());
    if (quantity <= 0) {
        showNotificationInModal('Please enter a valid quantity.', 'error');
        return;
    }

    // Modify the way meals are added to activeOrders
    activeOrders.push({
        items: [  // Wrap the meal in the 'items' array
            { 
                name: currentMeal.name,
                quantity: quantity,
                price: currentMeal.price
            }
        ]
    });

    // Log to see the active orders array
    console.log('Active Orders:', activeOrders);

    // Show success message
    showNotificationInModal('Meal added to order!', 'success');

    // Close the modal
    toggleModal('quantityModal', false);

    // Reset currentMeal after adding to order
    currentMeal = {};  // Clear current meal selection
}

//show notifications
function showNotification(message, type) {
    const notificationContainer = document.getElementById('notification-container');
    notificationContainer.textContent = message;
    notificationContainer.className = type;  // Set success or error class

    // Show the notification
    notificationContainer.style.display = 'block';
    notificationContainer.style.opacity = 1;

    // Hide the notification after 5 seconds
    setTimeout(() => {
        notificationContainer.style.opacity = 0;
        setTimeout(() => {
            notificationContainer.style.display = 'none';
        }, 5000); // Wait for fade-out to finish
    }, 500000); // Hide after 5 seconds
}

function confirmOrder() {
    console.log(activeOrders); // Debugging output

    if (!Array.isArray(activeOrders) || activeOrders.length === 0) {
        alert("No meals selected to confirm!");
        return;
    }

    try {
        const ordersToSave = activeOrders.map(order => {
            if (!order.items || !Array.isArray(order.items)) {
                throw new Error("Invalid order structure: items is not an array.");
            }

            return {
                items: order.items.map(item => {
                    if (!item.name || !item.quantity || !item.price) {  // Ensure price is present
                        throw new Error("Invalid item structure: missing name, quantity, or price.");
                    }
                    return {
                        name: item.name,
                        quantity: item.quantity,
                        price: item.price  // Include price in the final object
                    };
                })
            };
        });

        // Send the data to the server
        $.ajax({
            url: 'orders/confirm_order.php',
            type: 'POST',
            data: JSON.stringify({ orders: ordersToSave }),
            contentType: 'application/json',
            success: function(response) {
                try {
                    const res = JSON.parse(response);
                    if (res.success) {
                        showNotification("Orders confirmed successfully!", "success");
                        location.reload();
                    } else {
                        showNotification(res.message || "Failed to confirm orders.", "error");
                    }
                } catch (err) {
                    console.error("Error parsing server response:", err);
                    showNotification("An error occurred. Please try again.", "error");
                }
            },
            error: function(xhr, status, error) {
                console.error("Error confirming order:", error);
                showNotification("An error occurred. Please try again.", "error");
            }
        });
    } catch (error) {
        console.error("Error processing orders:", error.message);
        showNotification("An error occurred while processing your order. Please check your selection.", "error");
    }
}





//process payments

function processPayment(paymentMethod) {
    $.ajax({
        url: 'orders/place_order.php',
        type: 'POST',
        data: { paymentMethod: paymentMethod },
        success: function(response) {
            const res = JSON.parse(response);
            if (res.success) {
                alert(res.message);
                // Redirect to order success page or reload the current page
                location.reload();
            } else {
                alert(res.message);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error placing order:", error);
            alert("An error occurred. Please try again.");
        }
    });
}
function addToOrder(mealId, mealName, mealPrice, quantity) {
    $.ajax({
        url: 'orders/update_order.php',
        type: 'POST',
        data: {
            action: 'add',
            mealId: mealId,
            mealName: mealName,
            mealPrice: mealPrice,
            quantity: quantity
        },
        success: function(response) {
            const res = JSON.parse(response);
            if (res.success) {
                console.log("Order updated successfully.");
            } else {
                console.error(res.message);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error updating order:", error);
        }
    });
}


    function searchMeals() {
        currentSearch = $('#searchMeals').val();
        currentPage = 1;
        fetchMeals();
    }

    function filterMeals() {
        currentType = $('#mealTypeFilter').val();
        currentPage = 1;
        fetchMeals();
    }

    function changePage(direction) {
        currentPage += direction;
        fetchMeals();
    }

    
    $(document).ready(function() {
        fetchMeals();
    });
</script>
<style>
/* When modal is open, prevent body scroll */
body.modal-open {
    overflow: hidden;
}

/* Modal styling */
.modal {
    position: fixed;
    top: 50%;
    left: 60%;
    transform: translate(-50%, -50%);
    z-index: 1050;
    background: rgba(0, 0, 0, 0.7);
    width: 80%;
    max-width: 500px;
    padding: 20px;
    border-radius: 10px;
    display: none;
}

.modal-content {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.hidden {
    display: none !important;
}
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
    z-index: 999; /* Ensure it's above all content */
}

</style>