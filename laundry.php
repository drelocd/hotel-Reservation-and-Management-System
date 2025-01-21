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
?>
		
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

        <body>
    <!-- Main content container -->
    <div id="laundryPage">
        <!-- Breadcrumb Navigation -->
        <div class="row">
            <ol class="breadcrumb" id="breadcrumb" style="margin-bottom: 20px;">
                <li><a href="#">
                    <em class="fa fa-tshirt "></em>
                </a></li>
                <li class="active">Laundry</li>
            </ol>
        </div>

        <!-- Laundry Cards Section -->
        <div class="row">
            <!-- New Laundry Card -->
            <div class="col-md-6">
                <div class="card laundry-card">
                    <div class="card-body">
                    <i class="fas fa-plus x"></i>

                        <h4 class="card-title">New Laundry Job Request</h4>
                        <button class="btn btn-success" onclick="showModal('newLaundryModal')">Open</button>
                    </div>
                </div>
            </div>

            <!-- Edit Laundry Card -->
            <div class="col-md-6">
                <div class="card laundry-card">
                    <div class="card-body">
                    <i class="fas fa-pencil x"></i>

                        <h4 class="card-title">Edit Laundry</h4>
                        <button class="btn btn-warning" onclick="showModal('editLaundryModal')">Open</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Laundry Collection Card -->
        <div class="row">
            <div class="col-md-12">
                <div class="card laundry-card">
                    <div class="card-body">
                    <i class="fas fa-delivery "></i>

                        <h4 class="card-title">Laundry Collection</h4>
                        <button class="btn btn-info" onclick="showModal('LaundryCollectionModal')">Open</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- New Laundry Modal -->
<div id="newLaundryModal" class="modal hidden">
    <div class="modal-content">
        <span class="close" onclick="closeModal('newLaundryModal')">&times;</span>
        <h2 style="text-align:center; font-family:Gothic;"><b>Create New Laundry</b></h2>
        
        <div id="errorMessage" class="hidden"></div>


        <form id="newLaundryForm" class="form-grid">
            <!-- Room Number Section -->
            <div class="form-row">
                <div class="form-group">
                    <label for="roomNumber">Room Number:</label>
                    <select id="roomNumber" name="roomNumber" onchange="fetchCustomerName()">
                        <option value="">Select Room</option>
                        <!-- Dynamic room numbers will be populated here -->
                    </select>
                </div>
                <div id="errorDiv" style="color: red; margin-top: 10px;"></div>

                <div class="form-group">
                    <label for="roomNumberInput">Enter Room Number:</label>
                    <input type="text" id="roomNumberInput" name="roomNumberInput" placeholder="Type Room Number" oninput="fetchCustomerName()" />
                </div>
            </div>

            <!-- Customer Name Section -->
            <div id="customerNameDisplay" class="form-group">
                <label>Customer Name:</label>
                <div class="customer-name-box">No customer selected</div>
            </div>

            <!-- Laundry Details Section -->
            <div class="form-row">
                <div class="form-group">
                    <label for="weight">Weight:</label>
                    <input type="number" id="weight" name="weight" required min="0.01" step="any" />
                </div>
                <div class="form-group">
                    <label for="laundryType">Laundry Type:</label>
                    <select id="laundryType" name="laundryType" required>
                        <option value="">Select Laundry Type</option>
                        <!-- Dynamic laundry types will be populated here -->
                    </select>
                </div>
            </div>

            <!-- Buttons Section -->
            <div class="form-row buttons">
                <button type="button" onclick="closeModal('newLaundryModal')" class="cancel-button">Cancel</button>
                <button type="button" onclick="submitLaundryForm()" class="submit-button">Confirm</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Laundry Modal -->
<div id="editLaundryModal" class="modal hidden">
    <div class="modal-content">
        <span class="close" onclick="closeModal('editLaundryModal')">&times;</span>
        <h2 style="text-align:center; font-family:Roboto;"><b>Edit Laundry Job Details</b></h2>
        <div id="description" style="font-size: 18px; margin-bottom: 10px;text-align:center; color: green;">Update Details of a Laundry Job Here  </div>
        <!-- Error Message Div -->
        <div id="errorMessage1" class="hidden alert alert-danger"></div>

        <div class="modal-body">
            <div class="form-group">
                <label for="referenceNumber" class="text-center">Reference Number</label>
                <input type="text" id="referenceNumber" class="form-control" placeholder="Enter Reference Number" />
            </div>
            <div class="text-center">
                <button class="btn btn-primary mt-2 text-center" onclick="searchLaundry()">Search</button>
            </div>

            <div id="laundryDetails" class="mt-3 hidden">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Weight</th>
                            <th>Laundry Name</th>
                            <th>Room Number</th>
                            <th>Total Cost</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="laundryDetailsBody">
                        <!-- Rows will be dynamically added here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Laundry Collection Modal -->
<div id="LaundryCollectionModal" class="modal hidden">
    <div class="modal-content">
        <span class="close" onclick="closeModal('LaundryCollectionModal')">&times;</span>
        <h2 style="text-align:center; font-family:Roboto;"><b>View and Update Collection Status</b></h2>

        <!-- Error Message Div -->
        <div id="errorMessage2" class="hidden alert alert-danger"></div>

        <div id="viewingDateLabel" style="font-size: 18px; margin-bottom: 10px;text-align:center; color: green;">
    You're viewing jobs for <span id="viewingDate">today's date</span>
</div>
<input type="date" id="datePicker" class="form-control" />


        <div class="row">
            <!-- Search Input -->
            <div class="col-md-8">
                <input type="text" id="searchLaundryJobs" placeholder="Search Laundry jobs by room number" onkeyup="searchJobs()" class="form-control">
            </div>

            <!-- Filter Dropdown (Laundry Types) -->
            <div class="col-md-4">
                <select id="LaundryJobFilter" class="form-control" onchange="filterLaundryJobs()">
                    <option value="">All Laundry Types</option>
                </select>
            </div>
        </div>

        <!-- Laundry Jobs Table -->
        <div id="laundryJobsContainer" class="mt-3">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Job ID</th>
                        <th>Room Number</th>
                        <th>Total Weight (kg)</th>
                        <th>Created Time</th>
                        <th>Total Cost (KES)</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="laundryJobsTableBody">
                    <!-- Jobs will be inserted dynamically -->
                </tbody>
            </table>

           <!-- Pagination Controls -->
<div class="pagination-controls">
    <button id="prevPage">Previous</button>
    <button id="nextPage">Next</button>
</div>

        </div>
    </div>
</div>

<style>

/* Center the pagination buttons and make them smaller */
.pagination-controls {
    display: flex;
    justify-content: center;
    gap: 10px;  /* Adds spacing between buttons */
    margin-top: 10px;
}

.pagination-controls button {
    font-size: 1.5rem;  /* Make buttons smaller */
    padding: 15px 10px;  /* Reduce padding for smaller buttons */
    border-radius: 3px;  /* Optional: round the corners */
}

.pagination-controls button:hover {
    background-color:black;  /* Add a hover effect */
    color: white;
}

/* Modal styling */
.hidden {
    display: none;
}
.alert-danger {
    color: red;
    padding: 10px;
    margin-top: 10px;
    border: 1px solid red;
    background-color: #f8d7da;
    border-radius: 5px;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}
#errorMessage {
    color: red; /* Red text color */
    font-size: 16px; /* Adjust the font size */
    padding: 10px; /* Some padding for spacing */
    text-align: center; /* Center the text */
    background-color: #f8d7da; /* Light red background for better visibility */
    border: 1px solid #f5c6cb; /* Border with a light red color */
    border-radius: 5px; /* Rounded corners */
    margin-top: 10px; /* Add some space above the error message */
    display: none; /* Initially hide the error message */
    width: 100%; /* Ensure the error message takes up full width */
    box-sizing: border-box; /* Prevents overflow */
}

#errorMessage.active {
    display: block; /* Display the error message when 'active' */
}


.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    width: 50%;
    max-width: 600px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    position: relative;
}

.close {
    font-size: 28px;
    color: darkred;
    cursor: pointer;
    position: absolute;
    top: 10px;
    right: 20px;
}
.hidden {
    display: none;
    color: red; /* Optional: Make error messages stand out */
}

.close:hover,
.close:focus {
    color: black;
}

.form-grid {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-row {
    display: flex;
    gap: 20px;
}

.form-group {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.customer-name-box {
    padding: 10px;
    background-color: grey;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 18px;
    color: blue;
    text-align: center;
    text-decoration: bold;

}

.buttons {
    display: flex;
    justify-content: space-between;
}

button {
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
}

.cancel-button {
    background-color: #f44336;
    color: white;
}

.cancel-button:hover {
    background-color: #d32f2f;
}

.submit-button {
    background-color: #4CAF50;
    color: white;
}

.submit-button:hover {
    background-color: #388E3C;
}

@media (max-width: 767px) {
    .modal-content {
        width: 80%;
    }

    .form-row {
        flex-direction: column;
        gap: 10px;
    }
}

/* Ensure buttons are placed next to each other horizontally */
.action-btn-container {
    display: flex;  /* Use flexbox for horizontal alignment */
    justify-content: flex-start;  /* Align buttons to the left */
    gap: 5px;  /* Add a small space between buttons */
}

/* Style the buttons inside the container */
.btn-sm {
    display: inline-flex;  /* Align icon and button */
    align-items: center;  /* Center the icon vertically */
    justify-content: center;  /* Center the icon horizontally */
    border-radius: 50%;  /* Round the buttons */
    width: 30px;  /* Set button width */
    height: 30px;  /* Set button height */
    padding: 0;  /* Remove padding */
}

/* Pencil icon (update button) */
.btn-success .fa-pencil-alt {
    font-size: 14px;  /* Smaller icon size */
}

/* Trash can icon (delete button) */
.btn-danger .fa-trash-alt {
    font-size: 14px;  /* Smaller icon size */
}

/* Hover effects */
.btn-sm:hover {
    opacity: 0.8;  /* Slight transparency on hover */
    cursor: pointer;  /* Change cursor to pointer */
}

/* Tooltip on hover */
button[title]:hover::after {
    content: attr(title);
    position: absolute;
    background: #333;
    color: #fff;
    padding: 5px;
    border-radius: 3px;
    font-size: 12px;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
}

</style>

</body>

<style>
/* Ensure the content starts after the sidebar */
#laundryPage {
    margin-left: 220px; /* Adjust the margin to account for the sidebar */
    padding-top: 20px;
    padding-left: 20px;
    width: calc(100% - 220px); /* Ensure the content width adjusts for the sidebar */
}

/* Breadcrumb Styles */
.breadcrumb {
    background: #f8f9fa; /* Light background */
    padding: 10px 15px;
    border-radius: 5px;
    font-size: 16px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 100%; /* Make it fit the width of the content */
    margin-left: 20px; /* Align it with the content when sidebar is visible */
}

/* Card Styles */
.card {
    border: none;
    box-shadow: 0 4px 8px yellow;
    cursor: pointer;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 200px; /* Adjust the height */
    margin: 30px;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 20px;
    background-color: grey;
}

.card:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 16px red;
}

.card-body {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 100%;
}

.card-title {
    font-size: 20px;
    font-weight: bold;
}

.btn {
    font-size: 16px;
    padding: 12px;
    text-transform: uppercase;
    font-weight: bold;
}
.small-action-btn {
    font-size: 12px; /* Reduce text size */
    padding: 4px 8px; /* Smaller padding */
    border-radius: 3px; /* Slightly rounded corners */
    cursor: pointer;
    border: none;
    color: white;
}

.complete-btn {
    background-color: green; /* Green for "Complete" */
}

.activate-btn {
    background-color: orange; /* Orange for "Activate" */
}
/* For large screens: Two cards horizontally at the top and one at the bottom */
@media (min-width: 768px) {
    .laundry-card {
        height: 320px; /* Adjusted height for larger screens */
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .col-md-6 {
        flex: 1 1 48%; /* Two cards on top with 48% width each */
        margin-bottom: 20px;
    }

    .col-md-12 {
        flex: 1 1 100%; /* One card at the bottom with full width */
        margin-bottom: 20px;
    }

    /* Optional styling adjustments for large screens */
    .container-fluid {
        padding-left: 10px;
        padding-right: 10px;
    }
}

/* For small screens: Cards stack vertically */
@media (max-width: 767px) {
    .col-md-6, .col-md-12 {
        flex: 1 1 100%; /* Cards will stack vertically */
        margin-bottom: 15px;
    }

    .row {
        display: block; /* Stack all cards vertically */
    }

    .laundry-card {
        height: 200px; /* Smaller height for small screens */
    }

    #laundryPage {
        margin-left: 0; /* No margin on the left when sidebar is hidden */
        width: 100%; /* Full width when sidebar is hidden */
        padding-left: 10px; /* Adjust left padding if needed */
    }

    /* Breadcrumb alignment on small screens */
    .breadcrumb {
        margin-left: 0; /* Align breadcrumb with the left side */
    }
}
</style>


<script>

// Function to show the modal
function showModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.getElementById(modalId).classList.add('active');
    fetchRoomNumbers(); // Fetch available room numbers when modal is opened
    fetchLaundryTypes(); // Fetch laundry types for the dropdown
    fetchLaundryJobs(); // Fetch jobs on page load
    fetchLaundryTypes(); // Fetch laundry types for filter
    initializeDatePicker(); // Initialize date picker functionality
}

// Function to close the modal
function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
    document.getElementById(modalId).classList.add('hidden');

    // Clear the reference number input
    const referenceInput = document.getElementById('referenceNumber');
    if (referenceInput) {
        referenceInput.value = '';
    }

    // Clear the laundry details table
    const tableBody = document.getElementById('laundryDetailsBody');
    if (tableBody) {
        tableBody.innerHTML = '';
    }

    // Clear the laundry Jobs table
    
    const referenceSearchInput = document.getElementById('searchLaundryJobs');
    if (referenceSearchInput) {
        referenceSearchInput.value = '';
    }

    
    // Hide the laundry details section
    const laundryDetails = document.getElementById('laundryDetails');
    if (laundryDetails) {
        laundryDetails.classList.add('hidden');
    }

    // Reset error message content
    const errorMessageDiv = document.getElementById('errorMessage1');
    errorMessageDiv.innerText = '';  // Clear any error message text
    errorMessageDiv.classList.add('hidden');
    resetModalForm(); // Reset the form data when the modal is closed
}

// Reset the form and error card when modal is closed
function resetModalForm() {
    document.getElementById('newLaundryForm').reset();
    document.getElementById('customerNameDisplay').innerHTML = '<div class="customer-name-box">No customer selected</div>';
    
    // Reset error message content
    const errorMessageDiv = document.getElementById('errorMessage');
    errorMessageDiv.innerText = '';  // Clear any error message text
    
    // Ensure the error message is hidden
    errorMessageDiv.classList.add('hidden');
}


// Fetch room numbers for the dropdown and search input
function fetchRoomNumbers() {
    const roomSelect = document.getElementById('roomNumber');
    const roomInput = document.getElementById('roomNumberInput');

    // Fetch all room numbers to populate the dropdown
    fetch('laundry/fetch_room_numbers.php')
        .then(response => response.json())
        .then(data => {
            // Populate dropdown with room numbers
            roomSelect.innerHTML = '<option value="">Select Room</option>';
            data.roomNumbers.forEach(room => {
                const option = document.createElement('option');
                option.value = room.room_no;
                option.textContent = room.room_no;
                roomSelect.appendChild(option);
            });
        })
        .catch(err => console.error('Error fetching room numbers:', err));

    // Add event listener for dynamic search
    roomInput.addEventListener('input', function () {
        const searchTerm = roomInput.value;
        
        // Only send request if input is non-empty
        if (searchTerm.length >= 2) {  // Trigger search after 2 characters
            fetch(`laundry/fetch_room_numbers.php?searchTerm=${searchTerm}`)
                .then(response => response.json())
                .then(data => {
                    // Update the dropdown with search results
                    roomSelect.innerHTML = '<option value="">Select Room</option>';
                    data.roomNumbers.forEach(room => {
                        const option = document.createElement('option');
                        option.value = room.room_no;
                        option.textContent = room.room_no;
                        roomSelect.appendChild(option);
                    });
                })
                .catch(err => console.error('Error fetching filtered room numbers:', err));
        }
    });
}


let customerName = '';  // Variable to store customer name
let roomId = '';  // Variable to store room ID

function fetchCustomerName() {
    const roomNumber = document.getElementById('roomNumber').value || document.getElementById('roomNumberInput').value;

    if (!roomNumber) {
        document.getElementById('customerNameDisplay').innerHTML = `<div class="customer-name-box">No customer selected</div>`;
        return;
    }

    fetch(`laundry/fetch_customer_name.php?room_number=${roomNumber}`)
        .then((response) => response.json())
        .then((data) => {
            const customerNameBox = document.getElementById('customerNameDisplay');

            if (data.customer_name) {
                customerName = data.customer_name;  // Store customer name
                roomId = roomNumber;  // Store room number as room ID (assuming this is correct)
                customerNameBox.innerHTML = `<div class="customer-name-box">${customerName}</div>`;
            } else if (data.error) {
                customerNameBox.innerHTML = `<div class="customer-name-box" style="color: red;">${data.error}</div>`;
            } else {
                customerNameBox.innerHTML = `<div class="customer-name-box">No customer found</div>`;
            }
        })
        .catch((error) => {
            document.getElementById('customerNameDisplay').innerHTML = `<div class="customer-name-box" style="color: red;">Error fetching customer</div>`;
            console.error('Error fetching customer name:', error);
        });
}


// Fetch available laundry types from the database
function fetchLaundryTypes() {
    const laundryTypeSelect = document.getElementById('laundryType');

    fetch('laundry/fetch_laundry_types.php') // Replace with your endpoint for fetching laundry types
        .then(response => response.json())
        .then(data => {
            // Clear existing options
            laundryTypeSelect.innerHTML = '<option value="">Select Laundry Type</option>';

            // Populate laundry types
            data.laundryTypes.forEach(type => {
                const option = document.createElement('option');
                option.value = type.id;
                option.textContent = type.name; // Assuming each type has "id" and "name"
                laundryTypeSelect.appendChild(option);
            });
        })
        .catch(err => console.error('Error fetching laundry types:', err));
}

// Function to show error messages in the error card

// Function to show error messages in the error div
function showError(message) {
    const errorMessageDiv = document.getElementById('errorMessage');
    
    // Clear any previous error messages
    errorMessageDiv.innerHTML = message;
    
    // Ensure error message div is visible
    errorMessageDiv.classList.remove('hidden');
    errorMessageDiv.classList.add('active');
    
    // Hide the error message after 10 seconds
    setTimeout(function() {
        errorMessageDiv.classList.remove('active');
        errorMessageDiv.classList.add('hidden');
    }, 10000);
}

// Function to submit the new laundry form
function submitLaundryForm() {
    const roomNumber = document.getElementById('roomNumber').value || document.getElementById('roomNumberInput').value;
    const weight = document.getElementById('weight').value;
    const laundryType = document.getElementById('laundryType').value;

    // Clear any previous error messages
    const errorMessageDiv = document.getElementById('errorMessage');
    errorMessageDiv.innerHTML = ''; // Clear previous error messages

    // Validate form inputs
    let errors = [];

    if (!roomNumber) {
        errors.push('Room number is required.');
    }

    if (!weight || weight <= 0) {
        errors.push('Please enter a valid weight.');
    }

    if (!laundryType) {
        errors.push('Please select a laundry type.');
    }

    if (!customerName) {
        errors.push('Customer name is not found.');
    }

    // If there are errors, display them in the error message div
    if (errors.length > 0) {
        errorMessageDiv.innerHTML = errors.join('<br>');
        errorMessageDiv.classList.remove('hidden'); // Show the error message div
        errorMessageDiv.classList.add('active'); // Add 'active' to make it visible
        // Hide the error message after 10 seconds
        setTimeout(function() {
            errorMessageDiv.classList.add('hidden');
            errorMessageDiv.classList.remove('active');
        }, 10000);
        return; // Do not proceed with form submission
    }

    // Submit the data to add the new laundry if no errors
    fetch('laundry/add_laundry.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            room_number: roomNumber,
            weight: weight,
            laundry_type: laundryType,
            customer_name: customerName,  // Add customer_name
            room_id: roomId  // Add room_id
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Laundry added successfully.');
            // Use the returned data to print the receipt
            printReceipt({
                referenceNumber: data.reference_number,
                customerName: data.customer_name,
                laundryType: data.laundry_type_name,
                totalCost: data.total_cost,
                ratePerKg: data.cost_per_kg
            });
            closeModal('newLaundryModal');
        } else {
            alert('Failed to add laundry.');
        }
    })
    .catch(err => console.error('Error adding laundry', err));
}



function printReceipt(laundryDetails) {
    // Validate that totalCost is available; if not, assume a placeholder value

    const receiptContent = `
        <div style="text-align:center; font-family: Arial, sans-serif; padding: 20px; border: 1px solid #ddd; width: 300px; margin: auto;">
            <h2>Laundry Receipt</h2>
            <p><strong>Reference Number:</strong> ${laundryDetails.referenceNumber}</p>
            <p><strong>Customer Name:</strong> ${laundryDetails.customerName}</p>
            <p><strong>Laundry Type:</strong> ${laundryDetails.laundryType}</p>
            <p><strong>Rate/Kg:</strong> ${laundryDetails.ratePerKg}</p>
            <p><strong>Total Cost:</strong> ${laundryDetails.totalCost}</p>
            <p><strong>Date:</strong> ${new Date().toLocaleString()}</p>
            <p style="font-size: 12px; color: gray;">Thank you for using our service!</p>
        </div>
    `;

    // Open a new window to print the receipt
    const newWindow = window.open('', '', 'width=600,height=600');
    newWindow.document.write('<html><head><title>Print Receipt</title>');
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
    newWindow.document.close(); // Close the document to trigger rendering

    // Wait for the content to load and then show the print dialog
    newWindow.onload = function() {
        newWindow.focus(); // Focus on the print window
        setTimeout(function() {
            newWindow.print();
 // Show the print dialog
        }, 100); // Add a small delay to ensure print dialog shows up correctly
    };

    // Reset the form once the print window is closed
   newWindow.onbeforeunload = function() {
        // Reset the form and close modal
        document.getElementById('newLaundryForm').reset(); // Reset the form inputs
        closeModal('newLaundryModal'); // Close the modal (if applicable)
    };
}

function searchLaundry() {
    const refNum = document.getElementById('referenceNumber').value.trim();
    const errorMessage = document.getElementById('errorMessage1'); // Get the error message div
    const tableBody = document.getElementById('laundryDetailsBody');
    const laundryDetails = document.getElementById('laundryDetails');

    // Clear previous error messages and table data
    errorMessage.innerHTML = '';
    errorMessage.classList.add('hidden'); // Hide the error div initially
    tableBody.innerHTML = '';
    laundryDetails.classList.add('hidden'); // Hide the table initially

    if (!refNum) {
        errorMessage.innerHTML = 'Please enter a reference number.';
        errorMessage.classList.remove('hidden'); // Show the error message
        return;
    }

    // Fetch laundry details
    fetch(`laundry/fetch_laundry_details.php?ref_num=${refNum}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('laundryDetails').classList.remove('hidden');
                const { weight, laundry_name, room_no } = data.data;

                // Create a new row
                const row = document.createElement('tr');

                // Add editable weight column
                const weightCell = document.createElement('td');
                const weightInput = document.createElement('input');
                weightInput.type = 'number';
                weightInput.min = 0; // Prevent negative values
                weightInput.value = weight;
                weightInput.classList.add('form-control');
                weightCell.appendChild(weightInput);
                row.appendChild(weightCell);

                // Add dropdown for laundry type
                const laundryTypeCell = document.createElement('td');
                const laundryTypeSelect = document.createElement('select');
                laundryTypeSelect.classList.add('form-select');

                let selectedRate = 0;

                fetch('laundry/new_laundry_type.php')
                    .then(response => response.json())
                    .then(types => {
                        types.forEach(type => {
                            const option = document.createElement('option');
                            option.value = JSON.stringify({ id: type.id, rate: type.rate_per_kg });
                            option.textContent = type.type_name;

                            if (type.type_name === laundry_name) {
                                option.selected = true;
                                selectedRate = type.rate_per_kg;
                            }
                            laundryTypeSelect.appendChild(option);
                        });

                        // Update total cost on change
                        laundryTypeSelect.addEventListener('change', calculateTotalCost);
                    })
                    .catch(error => {
                        errorMessage.innerHTML = 'Error fetching laundry types.';
                        errorMessage.classList.remove('hidden');
                    });

                laundryTypeCell.appendChild(laundryTypeSelect);
                row.appendChild(laundryTypeCell);

                // Add dropdown for room number
                const roomNoCell = document.createElement('td');
                const roomNoSelect = document.createElement('select');
                roomNoSelect.classList.add('form-select');

                fetch('laundry/fetch_rooms.php')
                    .then(response => response.json())
                    .then(rooms => {
                        rooms.forEach(room => {
                            const option = document.createElement('option');
                            option.value = room.room_id;
                            option.textContent = room.room_no;

                            if (room.room_no === room_no) {
                                option.selected = true;
                            }
                            roomNoSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        errorMessage.innerHTML = 'Error fetching rooms.';
                        errorMessage.classList.remove('hidden');
                    });

                roomNoCell.appendChild(roomNoSelect);
                row.appendChild(roomNoCell);

                // Add total cost column
                const totalCostCell = document.createElement('td');
                totalCostCell.textContent = (weight * selectedRate).toFixed(2);
                row.appendChild(totalCostCell);

              
// Add update and delete buttons with icons
const actionCell = document.createElement('td');

// Create a container to hold both buttons side by side
const actionContainer = document.createElement('div');
actionContainer.classList.add('action-btn-container');  // Use flexbox container for horizontal layout

// Create update button with pencil icon
const updateButton = document.createElement('button');
updateButton.classList.add('btn', 'btn-sm', 'btn-success');
updateButton.innerHTML = '<i class="fas fa-pencil-alt"></i>';  // Pencil icon
updateButton.title = "Update job";  // Tooltip for hover
updateButton.onclick = () =>
    updateLaundry(
        refNum,
        weightInput.value,
        JSON.parse(laundryTypeSelect.value).id,
        roomNoSelect.value
    );

// Create delete button with trash can icon
const deleteButton = document.createElement('button');
deleteButton.classList.add('btn', 'btn-sm', 'btn-danger');
deleteButton.innerHTML = '<i class="fas fa-trash-alt"></i>';  // Trash can icon
deleteButton.title = "Delete job";  // Tooltip for hover
deleteButton.onclick = () => deleteLaundry(refNum);  // Pass the reference number to delete function

// Append buttons to the action container
actionContainer.appendChild(updateButton);
actionContainer.appendChild(deleteButton);

// Append action container to the action cell
actionCell.appendChild(actionContainer);

// Append action cell to row
row.appendChild(actionCell);

// Append row to table body
tableBody.appendChild(row);



                // Recalculate total cost dynamically
                weightInput.addEventListener('input', calculateTotalCost);

                function calculateTotalCost() {
                    const selectedType = JSON.parse(laundryTypeSelect.value);
                    const ratePerKg = selectedType.rate;
                    const newWeight = parseFloat(weightInput.value) || 0;
                    if (newWeight < 0) {
                        errorMessage.innerHTML = 'Weight cannot be negative.';
                        errorMessage.classList.remove('hidden');
                        weightInput.value = 0;
                    }
                    const totalCost = newWeight * ratePerKg;
                    totalCostCell.textContent = totalCost.toFixed(2);
                }
            } else {
                errorMessage.innerHTML = data.message || 'Failed to fetch laundry details.';
                errorMessage.classList.remove('hidden');
            }
        })
        .catch(error => {
            errorMessage.innerHTML = 'An error occurred while fetching laundry details.';
            errorMessage.classList.remove('hidden');
        });
}


function updateLaundry(refNum, weight, laundryTypeId, roomId) {
    const errorMessage = document.getElementById('errorMessage1'); // Get the error message div
    errorMessage.innerHTML = ''; // Clear previous errors
    errorMessage.classList.add('hidden'); // Hide error message initially

    if (!weight || weight <= 0) {
        errorMessage.innerHTML = 'Weight must be greater than 0.';
        errorMessage.classList.remove('hidden');
        return;
    }

    const selectedType = JSON.parse(document.querySelector('select.form-select').value);
    const costPerKg = selectedType.rate;
    const totalCost = weight * costPerKg;

    const payload = {
        ref_num: refNum,
        weight: parseFloat(weight),
        laundry_type: parseInt(laundryTypeId),
        room_id: parseInt(roomId),
        cost_per_kg: parseFloat(costPerKg),
        total_cost: parseFloat(totalCost),
    };

    fetch('laundry/update_laundry.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                errorMessage.innerHTML = '<span style="color:green;">Laundry details updated successfully.</span>';
                errorMessage.classList.remove('hidden');

                // Wait 2 seconds before refreshing the data

                setTimeout(() => {
                    searchLaundry();
                }, 2000);
                        } else {
                errorMessage.innerHTML = data.message || 'Failed to update laundry details.';
                errorMessage.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error updating laundry details:', error);
            errorMessage.innerHTML = 'An error occurred while updating laundry details.';
            errorMessage.classList.remove('hidden');
        });
}


function deleteLaundry(refNum) {
    // Send a DELETE request to the server with the reference number
    fetch('laundry/delete_laundry_job.php', {
        method: 'POST',  // You can also use 'DELETE' depending on how you want to handle the request
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `ref_num=${refNum}`  // Send the reference number as the body
    })
    .then(response => response.json())
    .then(data => {
        // Handle the server response
        if (data.status === 'success') {
            alert('Job deleted successfully');
            // Optionally, remove the job from the table without reloading
            const row = document.querySelector(`tr[data-refnum="${refNum}"]`);
            if (row) {
                row.remove();  // Remove the row from the table
            }
            // Reload the modal or jobs list
            searchLaundry();
        } else {
            alert(data.message);  // Show the error message if deletion fails
        }
    })
    .catch(error => {
        console.error('Error deleting job:', error);
        alert('Failed to delete the job.');
    });
}


document.addEventListener("DOMContentLoaded", function () {
    fetchLaundryJobs(); // Fetch jobs on page load
    fetchLaundryTypes(); // Fetch laundry types for filter
    initializeDatePicker(); // Initialize date picker functionality
});

let currentPage = 1;
const itemsPerPage = 5;
let selectedDate = new Date().toISOString().split('T')[0]; // Default to today's date

// Fetch laundry jobs for the selected date
function fetchLaundryJobs(searchTerm = "", filterType = "") {
    const errorMessage = document.getElementById("errorMessage2");
    errorMessage.classList.add("hidden");

    fetch(`laundry/fetch_laundry_jobs.php?search=${searchTerm}&filter=${filterType}&page=${currentPage}&limit=${itemsPerPage}&date=${selectedDate}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                displayLaundryJobs(data.jobs, data.totalPages);
            } else {
                errorMessage.innerHTML = data.message || "No jobs found.";
                errorMessage.classList.remove("hidden");
            }
        })
        .catch(error => {
            console.error("Error fetching laundry jobs:", error);
            errorMessage.innerHTML = "An error occurred while fetching laundry jobs.";
            errorMessage.classList.remove("hidden");
        });
}

// Function to display jobs in the table
function displayLaundryJobs(jobs, totalPages) {
    const tableBody = document.getElementById("laundryJobsTableBody");
    tableBody.innerHTML = "";

    jobs.forEach(job => {
    const row = document.createElement("tr");

    // Determine button text and styling based on job status
    let actionButton = "";
    if (job.status.toLowerCase() === "active") {
        actionButton = `<button class="small-action-btn btn-success" onclick="updateLaundryStatus(${job.id}, 'complete')">Complete</button>`;
    } else if (job.status.toLowerCase() === "done") {
        actionButton = `<button class="small-action-btn btn-warning" onclick="updateLaundryStatus(${job.id}, 'activate')">Activate</button>`;
    }
    // If status is "complete", actionButton remains an empty string (no button)

    row.innerHTML = `
        <td>${job.id}</td>
        <td>${job.room_no}</td>
        <td>${job.weight} </td>
        <td>${job.time_created}</td>
        <td>${job.total_cost}</td>
        <td>${job.status}</td>
        <td>${actionButton}</td>
    `;

    tableBody.appendChild(row);
});


    document.getElementById("prevPage").disabled = currentPage === 1;
    document.getElementById("nextPage").disabled = currentPage === totalPages;

    // Update the "You're viewing jobs for" label
    document.getElementById("viewingDateLabel").textContent = `You're viewing jobs for ${selectedDate}`;
}

// Pagination controls
document.getElementById("prevPage").addEventListener("click", function () {
    if (currentPage > 1) {
        currentPage--;
        fetchLaundryJobs();
    }
});

document.getElementById("nextPage").addEventListener("click", function () {
    currentPage++;
    fetchLaundryJobs();
});

// Search function
function searchJobs() {
    const searchTerm = document.getElementById("searchLaundryJobs").value;
    fetchLaundryJobs(searchTerm, document.getElementById("LaundryJobFilter").value);
}

// Fetch laundry types dynamically for filtering
function fetchLaundryJobTypes() {
    fetch("laundry/fetch_laundryJob_types.php")
        .then(response => response.json())
        .then(types => {
            const filterSelect = document.getElementById("LaundryJobFilter");
            filterSelect.innerHTML = '<option value="">All Laundry Types</option>';

            types.forEach(type => {
                const option = document.createElement("option");
                option.value = type.id; // Capture the ID
                option.textContent = type.type_name; // Display the name
                filterSelect.appendChild(option);
            });
        })
        .catch(error => console.error("Error fetching laundry types:", error));
}

// Fetch types on page load
document.addEventListener("DOMContentLoaded", fetchLaundryJobTypes);

// Filter function that captures selected type ID
function filterLaundryJobs() {
    const selectedTypeId = document.getElementById("LaundryJobFilter").value;
    fetchLaundryJobs("", selectedTypeId); // Pass the selected ID for filtering
}

// Initialize the date picker and functionality
function initializeDatePicker() {
    const datePicker = document.getElementById("datePicker");
    
    // Initialize the date picker with the current date
    datePicker.value = selectedDate;

    // Update the date and fetch jobs when a new date is selected
    datePicker.addEventListener("change", function () {
        selectedDate = this.value;
        fetchLaundryJobs(); // Fetch jobs for the new selected date
    });
}
function updateLaundryStatus(jobId, status) {
    const errorMessageDiv = document.getElementById("errorMessage2");
    errorMessageDiv.classList.add("hidden"); // Hide error message initially

    // Clear the previous message
    errorMessageDiv.innerHTML = '';

    // Make the AJAX request to update laundry status
    fetch('laundry/update_laundry_status.php', {
        method: 'POST',
        body: new URLSearchParams({
            job_id: jobId
        })
    })
    .then(response => response.json())
    .then(data => {
        // Check the status from the PHP response
        if (data.status === "success") {
            // If success, hide the error message and perform necessary actions
            errorMessageDiv.classList.add("hidden"); // Hide the error message div
            alert(data.message);  // Optionally, show the message in an alert or you can refresh the job list
            fetchLaundryJobs();  // Refresh the laundry jobs list to reflect the updates
        } else {
            // If there's an error, display the error message in the error div
            errorMessageDiv.innerHTML = data.message;  // Set the error message text
            errorMessageDiv.classList.remove("hidden");  // Show the error message div
        }
    })
    .catch(error => {
        errorMessageDiv.innerHTML = "An unexpected error occurred: " + error.message;
        errorMessageDiv.classList.remove("hidden");  // Show the error message div
    });
}




</script>