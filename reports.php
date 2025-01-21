<style>
/* General container styling */
.container {
    width: 80%;
    margin: 0 auto;
    padding: 20px;
    height:70Vh;
}

h1 {
    text-align: center;
    margin-bottom: 20px;
}

/* Date Picker Section */
.date-picker-container {
    margin-bottom: 20px;
    text-align: center;
}

.date-picker-container input[type="date"] {
    padding: 5px;
    font-size: 16px;
    margin-right: 10px;
}

/* Transaction Table Styling */
.transaction-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.transaction-table th, .transaction-table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.transaction-table th {
    background-color: #f8f8f8;
}

.transaction-table button {
    padding: 5px 10px;
    background-color: #f0ad4e;
    color: white;
    border: none;
    cursor: pointer;
}

.transaction-table button:hover {
    background-color: #ec971f;
}

/* Pagination Controls */
.pagination-controls {
    text-align: center;
    margin-top: 20px;
}

.pagination-controls button {
    padding: 8px 15px;
    background-color: #5bc0de;
    color: white;
    border: none;
    cursor: pointer;
}

.pagination-controls button:hover {
    background-color: #31b0d5;
}
/* Style for the Total Income div */
#total-income {
    background-color: #f8f9fa;   /* Light background color */
    color: #333;                 /* Dark text color */
    font-size: 1.5rem;           /* Slightly larger font size */
    font-weight: bold;           /* Bold text */
    padding: 15px 30px;          /* Add padding around the content */
    border-radius: 8px;          /* Rounded corners */
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
    margin: 20px auto;           /* Center the div horizontally */
    text-align: center;          /* Center the text */
    width: 50%;                  /* Make the div 50% of the page width */
    max-width: 400px;            /* Set a maximum width for larger screens */
}

#total-income strong {
    color: #007bff;              /* Blue color for the total income value */
}

@media (max-width: 768px) {
    #total-income {
        width: 80%;              /* On smaller screens, take up 80% of the width */
    }
}


</style>

<div class="content"|>
<div id="ReportsPage">
        <!-- Breadcrumb Navigation -->
        <div class="row">
            <ol class="breadcrumb" id="breadcrumb" style="margin-bottom: 20px;">
                <li><a href="#">
                    <em class="fa fa-credit-card "></em>
                </a></li>
                <li class="active">Reports</li>
            </ol>
        </div>

<body>
    <div class="container">
        <h1>Accounting Overview</h1>

        <!-- Date Picker -->
        <div class="date-picker-container">
            <label for="transaction-date">Select Date: </label>
            <input type="date" id="transaction-date">
            <button onclick="fetchTransactions()">Get Transactions</button>
        </div>

        <!-- Transactions Table -->
        <div id="transaction-report">
            <h2>Transactions</h2>
            <table class="transaction-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Type</th>
                    </tr>
                </thead>
                <tbody id="transaction-table-body">
                    <!-- Dynamic Transaction Data -->
                </tbody>
            </table>

            <!-- Pagination Controls -->
            <div class="pagination-controls">
                <button id="prevBtn" onclick="prevPage()">⬅️ Prev</button>
                <button id="nextBtn" onclick="nextPage()">Next ➡️</button>
            </div>
        </div>
    </div>
<!-- Total Income div -->
<div id="total-income">
    Total Income for <strong>2025-01-07</strong>: KSh <strong>5000.00</strong>
</div>

    <script>
function setDefaultDate() {
    const today = new Date();
    const formattedDate = today.toISOString().split('T')[0]; // Format as YYYY-MM-DD
    document.getElementById('transaction-date').value = formattedDate;
}

// Call the function when the page loads
window.onload = function() {
    setDefaultDate();  // Set the default date to today
    fetchTransactions();  // Fetch transactions for the current date
};


let currentPage = 1; // Default to page 1

// Function to fetch transactions based on the selected date
function fetchTransactions() {
    const selectedDate = document.getElementById('transaction-date').value;

    if (selectedDate) {
        fetch(`extras/fetch_transactions.php?date=${selectedDate}&page=${currentPage}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const tableBody = document.getElementById('transaction-table-body');
                    tableBody.innerHTML = ''; // Clear the table body

                    // Populate the table with transaction data
                    data.transactions.forEach(transaction => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${transaction.id}</td>
                            <td>${transaction.date}</td>
                            <td>${transaction.transaction_name}</td> <!-- Updated to transaction_name -->
                            <td>${transaction.total_cost}</td> <!-- Updated to total_cost -->
                            <td>${transaction.transaction_type}</td> <!-- Added transaction_type -->
                        `;
                        tableBody.appendChild(row);
                    });

                    // Handle pagination visibility
                    document.getElementById('prevBtn').style.display = data.prevPage ? 'inline-block' : 'none';
                    document.getElementById('nextBtn').style.display = data.nextPage ? 'inline-block' : 'none';

                    // Display total income fetched from the backend
                    const totalIncomeDiv = document.getElementById('total-income');
                    totalIncomeDiv.innerHTML = `Total Income for ${selectedDate}: KSh ${parseFloat(data.totalIncome).toFixed(2)}`;
                } else {
                    alert('No transactions found for the selected date.');
                }
            })
            .catch(error => console.error('Error:', error));
    } else {
        alert('Please select a date.');
    }
}




// Function to go to the previous page
function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        fetchTransactions();
    }
}

// Function to go to the next page
function nextPage() {
    currentPage++;
    fetchTransactions();
}



    </script>