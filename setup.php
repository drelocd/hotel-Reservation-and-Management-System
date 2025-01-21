<style>
        body {
            background-color: darkgrey;
            height: 80vh;

        }
/* Modal Background */
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

    /* Modal Content */
    .modal-content {
        background-color: whitesmoke;
        padding: 20px;
        border-radius: 10px;
        width: 50%;
        max-width: 600px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        position: relative;
        text-align: center;
    }

    /* Close Button */
    .close {
        position: absolute;
        right: 15px;
        top: 10px;
        font-size: 20px;
        cursor: hand;
    }

        .setup-container {
            height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .setup-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
            width: 100%;
            max-width: 900px;
        }

        .setup-card {
            background: white;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            transition: 0.3s ease-in-out;
            cursor: pointer;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .setup-card:hover {
            transform: scale(1.05);
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.15);
        }

        .setup-card i {
            font-size: 40px;
            color: #007bff;
            margin-bottom: 15px;
        }

        .setup-card h3 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .setup-card p {
            font-size: 14px;
            color: #555;
        }
        .hidden {
    display: none;
}
/* Meal Function Buttons */
.mealfunction-container {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 20px;
    }

    .mealfunction {
        border: none;
        color: white;
        padding: 12px 20px;
        font-size: 16px;
        font-weight: bold;
        border-radius: 8px;
        cursor: hand;
        transition: all 0.3s ease-in-out;
    }

    .mealfunction:hover {
        transform: scale(1.1);
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    }

    /* Form Styling */
    form {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 20px;
    }

    input, select, button {
        padding: 10px;
        font-size: 16px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    button {
        background-color: #007bff;
        color: white;
        cursor: pointer;
        border: none;
    }

    button:hover {
        background-color: #0056b3;
    }

    .hidden {
        display: none;

   }
/* New Meal Modal */
#addNewMealModal .modal-content {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    width: 800px; /* Wider, but shorter */
    max-width: 90%; /* Responsive scaling */
    box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
    position: relative;
    text-align: center;
    max-height: 600px; /* Set max height to prevent the modal from growing too tall */
    overflow-y: auto; /* Allow scrolling if content overflows */
}

#addNewMealModal h2 {
    font-family: 'Gothic', sans-serif;
    text-align: center;
    font-size: 24px;
    margin-bottom: 20px;
    color: #333;
}

/* Close button style */
#addNewMealModal .close {
    position: absolute;
    right: 15px;
    top: 10px;
    font-size: 22px;
    color: #333;
    cursor: pointer;
}

/* Meal Form Elements */
#addNewMealModal form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

#addNewMealModal input,
#addNewMealModal select,
#addNewMealModal textarea,
#addNewMealModal button {
    padding: 12px 16px;
    font-size: 16px;
    border-radius: 8px;
    border: 1px solid #ddd;
    margin-bottom: 10px;
}

#addNewMealModal input[type="file"] {
    padding: 6px;
}

/* Meal Type Dropdown */
#mealType {
    background-color: #f7f7f7;
    border: 1px solid #ccc;
}

/* Error Message */
#errorMessageMeal {
    display: none;
    color: red;
    font-size: 14px;
    padding: 10px;
    background-color: #f8d7da;
    border: 1px solid red;
    border-radius: 5px;
}

/* Button Styling */
#addNewMealModal button[type="submit"] {
    background-color: #33AFFF;
    color: #fff;
    cursor: pointer;
    border: none;
    transition: all 0.3s ease-in-out;
}

#addNewMealModal button[type="submit"]:hover {
    background-color: #0288d1;
}

/* Image Preview */
#imagePreview {
    margin-top: 10px;
    max-width: 100%;
    max-height: 150px; /* Limit image height */
    overflow: hidden; /* Prevent expansion of the modal */
}

#imagePreview img {
    width: auto;
    height: 100%;
    border-radius: 8px;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    #addNewMealModal .modal-content {
        width: 80%;
        padding: 15px;
    }

    #addNewMealModal h2 {
        font-size: 20px;
    }
}



.alert-danger {
    color: red;
    padding: 10px;
    margin-top: 10px;
    border: 1px solid red;
    background-color: #f8d7da;
    border-radius: 5px;
}
/* Specific Styling for Room Types Modal */

/* Modal Content Styling */
#roomTypesModal .modal-content {
    background-color: white;
    padding: 20px;
    max-width: 800px;
    margin: 0 auto;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    position: relative;
}

/* Close Button for Room Types Modal */
#roomTypesModal .close {
    font-size: 30px;
    color: #555;
    position: absolute;
    top: 10px;
    right: 20px;
    cursor: pointer;
}

/* Modal Header */
#roomTypesModal h2 {
    font-size: 24px;
    margin-bottom: 15px;
    text-align: center;
}

/* Table Styling for Room Types */
#roomTypesModal .styled-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

#roomTypesModal .styled-table th,
#roomTypesModal .styled-table td {
    padding: 12px;
    text-align: left;
}

/* Table Header Styling */
#roomTypesModal .styled-table th {
    background-color: #4CAF50;
    color: white;
    font-weight: bold;
}

/* Zebra striping for table rows */
#roomTypesModal .styled-table tr:nth-child(even) {
    background-color: #f2f2f2;
}

/* Button Styling for Actions (Edit, Delete) */
#roomTypesModal .styled-table td button {
    margin-right: 5px;
    padding: 6px 10px;
    border: none;
    background-color: #007bff;
    color: white;
    cursor: pointer;
    border-radius: 4px;
}

#roomTypesModal .styled-table td button:hover {
    background-color: #0056b3;
}

#roomTypesModal .styled-table td button:focus {
    outline: none;
}

/* Edit Input Fields Styling */
#roomTypesModal .styled-table td input[type="text"] {
    width: 70%; /* Ensures the input takes up full cell width */
    padding: 8px;
    margin: 4px 0;
    box-sizing: border-box; /* Prevents padding from affecting width */
    border-radius: 4px;
    border: 1px solid #ccc;
    font-size: 14px;
}

/* Pagination Controls for Room Types */
#roomTypesModal .pagination-controls {
    margin-top: 20px;
    text-align: center;
}

#roomTypesModal .pagination-controls button {
    padding: 8px 15px;
    margin: 5px;
    cursor: pointer;
    border: none;
    background-color: #007bff;
    color: white;
    border-radius: 4px;
    font-size: 14px;
}

#roomTypesModal .pagination-controls button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}

/* Close Button for Room Types Modal at the Bottom */
#roomTypesModal .close-btn {
    display: block;
    margin-top: 20px;
    padding: 10px 20px;
    background-color: #f44336;
    color: white;
    text-align: center;
    border-radius: 5px;
    border: none;
    width: 100%;
    font-size: 16px;
    cursor: pointer;
}

#roomTypesModal .close-btn:hover {
    background-color: #e53935;
}

/* Styling for the hotel name modal */
#hotelNameModal .styled-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    background-color: #fff;
}

/* Header Styling */
#hotelNameModal .styled-table th {
    background-color: #f7c31c;  /* A warm yellow color */
    color: #333;
    padding: 12px;
    font-weight: bold;
    text-align: left;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
}

/* Row Styling */
#hotelNameModal .styled-table tr:nth-child(even) {
    background-color: #f2f2f2;  /* Light gray for even rows */
}

#hotelNameModal .styled-table tr:nth-child(odd) {
    background-color: #ffffff;  /* White for odd rows */
}

/* Cell Styling */
#hotelNameModal .styled-table td {
    padding: 12px;
    text-align: left;
    font-size: 16px;
    color: #333;
}

/* Button Styling inside table */
#hotelNameModal .styled-table td button {
    cursor: pointer;
    background-color: transparent;
    border: none;
    color: #007bff;
    font-size: 18px;
    transition: color 0.3s;
}

#hotelNameModal .styled-table td button:hover {
    color: #0056b3;  /* Darker blue when hovered */
}

/* Input Field Styling when in edit mode */
#hotelNameModal #edit-form {
    margin-top: 20px;
    display: flex;
    justify-content: center;
    gap: 10px;
}

#hotelNameModal #edit-form input {
    padding: 10px;
    font-size: 16px;
    border: 2px solid #f7c31c;  /* Yellow border */
    border-radius: 4px;
    width: 300px;
}

#hotelNameModal #edit-form button {
    padding: 10px 20px;
    background-color:rgb(28, 61, 247);  /* Same warm yellow */
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
}

#hotelNameModal #edit-form button:hover {
    background-color: #e0a900;  /* Darker yellow on hover */
}
/* User Management Modal */
.user-management-modal {
    font-family: Arial, sans-serif;
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.user-management-modal h2 {
    text-align: center;
    margin-bottom: 20px;
}

.user-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.user-table th, .user-table td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: center;
}

.user-table th {
    background-color: #f4f4f4;
    font-weight: bold;
}

.pagination-controls {
    text-align: center;
    margin-top: 20px;
}

.pagination-controls button {
    padding: 5px 15px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.pagination-controls button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}

/* Modal Close Button */
.modal .close {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
}

.modal .close:hover,
.modal .close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}


        @media (max-width: 768px) {
            .setup-grid {
                grid-template-columns: 1fr;
                width: 90%;
            }
        }
    </style>
    <i class="fas fa-toolbox"></i>

<div class="setup-container">

    <div class="setup-grid">
        <!-- Section 1 -->
        <div class="setup-card" onclick="showModal('generalSettingsModal')">
            <i class="fas fa-cogs"></i>
            <h3>General Settings</h3>
            <p>Manage system preferences, time settings, and other configurations.</p>
        </div>

        <!-- Section 2 -->
        <div class="setup-card" onclick="showModal('newMealsModal')">
            <i class="fas fa-hamburger"></i>
            <h3>Meals Management</h3>
            <p>Add, edit, and manage meal menu and cuisines.</p>
        </div>

        <!-- Section 3 -->
        <div class="setup-card" onclick="showModal('userManagementModal')">
            <i class="fas fa-users"></i>
            <h3>Users</h3>
            <p>User Management.</p>
        </div>

        <!-- Section 4 -->
        <div class="setup-card" onclick="handleSetup('System Backup')">
            <i class="fas fa-database"></i>
            <h3>System Backup</h3>
            <p>Backup and restore system data to prevent data loss.</p>
        </div>
    </div>
</div>
</div>


<div id="generalSettingsModal" class="modal hidden">
    <div class="modal-content">
        <span class="close" onclick="closeModal('generalSettingsModal')">&times;</span>
        <h2>General Settings</h2>

        <button class="settings-btn" style="background-color:rgba(253, 207, 0, 0.71);" onclick="showModal('hotelNameModal')">Edit Hotel Name</button>
        <button class="settings-btn" onclick="showModal('roomTypesModal')">Manage Room Types</button>

        <button class="close-btn" style="background-color:rgb(250, 14, 14);" onclick="closeModal('generalSettingsModal')">Close</button>
    </div>
</div>

<div id="roomTypesModal" class="modal hidden">
    <div class="modal-content">
        <span class="close" onclick="closeModal('roomTypesModal')">&times;</span>
        <h2>Manage Room Types</h2>

        <table id="roomTypesTable" class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Room Type</th>
                    <th>Price</th>
                    <th>Max Persons</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Room types will be loaded here dynamically -->
            </tbody>
        </table>
<!-- Pagination Controls -->
<div class="pagination-controls">
            <button id="prevBtn" onclick="prevPage()">⬅️ Prev</button>
            <button id="nextBtn" onclick="nextPage()">Next ➡️</button>
        </div>

        <button class="close-btn" onclick="closeModal('roomTypesModal')">Close</button>
    </div>
</div>

<!-- Modal for editing the hotel name -->
<div id="hotelNameModal" class="modal hidden">
    <div class="modal-content">
        <span class="close" onclick="closeModal('hotelNameModal')">&times;</span>
        <h2>Edit Hotel Name</h2>

        <!-- Hotel name table with a pencil to edit -->
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Hotel Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr id="hotel-name-row">
                    <td id="hotel-name-text"></td>
                    <td>
                        <button onclick="editHotelName()">✏️</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Editable form for the hotel name -->
        <div id="edit-form" style="display: none;">
            <input type="text" id="edit-hotel-name" placeholder="Enter hotel name">
            <button onclick="saveHotelName()">Save</button>
            <button onclick="closeModal('hotelNameModal')">Close</button>
        </div>
    </div>
</div>

<!-- Main User Management Modal -->
<div id="userManagementModal" class="modal hidden">
    <div class="modal-content">
        <span class="close" onclick="closeModal('userManagementModal')">&times;</span>
        <h2>User Management</h2>
        
        <button onclick="showModal('ViewUsersModal')">View Users</button>
        <button onclick="showModal('newUserModal')">New User</button>
        <button onclick="showModal('updateUserModal')">Update User</button>
    </div>
</div>

<!-- View Users Modal -->
<div id="ViewUsersModal" class="modal hidden">
    <div class="modal-content user-management-modal">
        <span class="close" onclick="closeModal('ViewUsersModal')">&times;</span>
        <h2>View Users</h2>
        
        <!-- Table displaying user data -->
        <table class="user-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Date Created</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody id="user-table-body">
                <!-- Dynamic User Data -->
            </tbody>
        </table>
        
        <!-- Pagination Controls -->
        <div class="pagination-controls">
            <button id="prevBtn" onclick="prevPage()">⬅️ Prev</button>
            <button id="nextBtn" onclick="nextPage()">Next ➡️</button>
        </div>
    </div>
</div>

<!-- New User Modal -->
<!-- New User Modal -->
<div id="newUserModal" class="modal hidden">
    <div class="modal-content user-management-modal">
        <span class="close" onclick="closeModal('newUserModal')">&times;</span>
        <h2>Create New User</h2>

        <form id="newUserForm" action="setup/add_user.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" name="name" id="new-name" required>

            <label for="username">Username:</label>
            <input type="text" name="username" id="new-username" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="new-email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="new-password" required>

            <label for="role">Role:</label>
            <select name="role" id="new-role">
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>

            <button type="submit" name="submit">Create User</button>
            <button type="button" onclick="closeModal('newUserModal')">Cancel</button>
        </form>
    </div>
</div>


<!-- Update User Modal -->
<div id="updateUserModal" class="modal hidden">
    <div class="modal-content user-management-modal">
        <span class="close" onclick="closeModal('updateUserModal')">&times;</span>
        <h2>Update User</h2>

        <!-- Table displaying user data -->
        <table class="user-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="user-table-body">
                <!-- Dynamic User Data -->
            </tbody>
        </table>

        <!-- Modal for updating password -->
        <div id="updatePasswordModal" class="modal hidden">
            <div class="modal-content">
                <span class="close" onclick="closeModal('updatePasswordModal')">&times;</span>
                <h2>Change Password</h2>
                <form id="updatePasswordForm">
                    <input type="hidden" id="user-id-password">
                    <label for="new-password">New Password:</label>
                    <input type="password" id="new-password" required>
                    <button type="submit">Save New Password</button>
                </form>
            </div>
        </div>

    </div>
</div>


<!-- Meals Management Modal -->
<div id="newMealsModal" class="modal hidden">
    <div class="modal-content">
        <span class="close" onclick="closeModal('newMealsModal')">&times;</span>
        <h2 style="text-align:center; font-family:Gothic;"><b>Meals Management</b></h2>

        <!-- Meal Function Selection -->
        <div class="mealfunction-container">
            
            <button class="mealfunction" style="background-color: #33AFFF;" onclick="showModal('addNewMealModal')">Add Meals</button>
            <button class="mealfunction" style="background-color: #28A745;" onclick="showModal('editMealsModal')">Edit Meals</button
        </div>
    </div>
</div>

<!-- Add New Meal Modal -->
<div id="addNewMealModal" class="modal hidden">
    <div class="modal-content">
        <span class="close" onclick="closeModal('addNewMealModal')">&times;</span>
        <h2 style="text-align:center; font-family:Gothic;"><b>Add New Meal</b></h2>

        <!-- Error Message -->
        <div id="errorMessageMeal" class="hidden alert-danger"></div>

        <!-- Add Meal Form -->
        <form id="addMealForm" enctype="multipart/form-data">
            <!-- Meal Name -->
            <label for="mealName">Meal Name:</label>
            <input type="text" id="mealName" name="name" required>

            <!-- Description -->
            <label for="mealDescription">Description:</label>
            <textarea id="mealDescription" name="description" required></textarea>

            <!-- Price -->
            <label for="mealPrice">Price (Ksh):</label>
            <input type="number" id="mealPrice" name="price" step="0.01" required>

            <!-- Meal Type -->
            <label for="mealType">Meal Type:</label>
            <select id="mealType" name="type" required>
                <option value="">Select Type</option>
                <option value="Breakfast">Breakfast</option>
                <option value="Lunch">Lunch</option>
                <option value="Dinner">Dinner</option>
                <option value="Snacks">Snacks</option>
                <option value="Beverages">Beverages</option>     
            </select>

            <!-- Image Upload -->
            <label for="mealImage">Meal Image (300x150px):</label>
            <input type="file" id="mealImage" name="image" accept="image/*" required>
            <div id="imagePreview"></div>

            <!-- Submit Button -->
            <button type="submit">Add Meal</button>
        </form>
    </div>
</div>



<script>
// Function to show modal
function showModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    if (modalId === "roomTypesModal") {
        loadRoomTypes(); // Load room types when opening the modal
    }
    setTimeout(() => {
        fetchHotelName();
    }, 100); // Add a small delay before calling fetchHotelName

    getUsers();
    
}
// Function to close modal
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

document.getElementById("mealImage").addEventListener("change", function (event) {
        const file = event.target.files[0];
        if (file) {
            const img = new Image();
            img.onload = function () {
                if (this.width == 300 || this.height == 150) {
                    document.getElementById("errorMessageMeal").innerText = "Image must be 300x150 pixels.";
                    document.getElementById("errorMessageMeal").classList.remove("hidden");
                    event.target.value = ""; // Reset the file input
                } else {
                    document.getElementById("errorMessageMeal").classList.add("hidden");
                    document.getElementById("imagePreview").innerHTML = `<img src="${URL.createObjectURL(file)}" width="100%">`;
                }
            };
            img.src = URL.createObjectURL(file);
        }
    });

    document.getElementById("addMealForm").addEventListener("submit", function (event) {
        event.preventDefault();

        let formData = new FormData(this);

        fetch("setup/add_meal.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Meal added successfully!");
                closeModal("addNewMealModal");
                location.reload();
            } else {
                document.getElementById("errorMessageMeal").innerText = data.message;
                document.getElementById("errorMessageMeal").classList.remove("hidden");
            }
        })
        .catch(error => console.error("Error:", error));
    });
    let roomTypeData = []; // Store fetched data
let currentPage = 1;
const rowsPerPage = 5;

function loadRoomTypes() {
    fetch("setup/fetch_room_types.php")
        .then(response => response.json())
        .then(data => {
            roomTypeData = data;
            displayRoomTypes(); // Show the first 5 records initially
        })
        .catch(error => console.error("Error loading room types:", error));
}

let editingRoomTypeId = null;

function displayRoomTypes() {
    let tableBody = document.querySelector("#roomTypesTable tbody");
    tableBody.innerHTML = ""; // Clear the table

    let start = (currentPage - 1) * rowsPerPage;
    let end = start + rowsPerPage;
    let paginatedData = roomTypeData.slice(start, end);

    if (paginatedData.length === 0) {
        tableBody.innerHTML = "<tr><td colspan='5'>No Room Types Found</td></tr>";
        return;
    }

    paginatedData.forEach(room => {
        let row = document.createElement("tr");
        row.id = `row-${room.room_type_id}`; // Add id to each row to target it easily

        // Check if this room type is being edited
        if (editingRoomTypeId === room.room_type_id) {
            row.innerHTML = `
                <td>${room.room_type_id}</td>
                <td><input type="text" value="${room.room_type}" class="edit-input" id="edit-room-type"></td>
                <td><input type="number" value="${room.price}" class="edit-input" id="edit-price"></td>
                <td><input type="number" value="${room.max_person}" class="edit-input" id="edit-max-person"></td>
                <td>
                    <button onclick="saveRoomType(${room.room_type_id})">💾</button>
                    <button onclick="cancelEdit(${room.room_type_id})">❌</button>
                </td>
            `;
        } else {
            row.innerHTML = `
                <td>${room.room_type_id}</td>
                <td>${room.room_type}</td>
                <td>${room.price}</td>
                <td>${room.max_person}</td>
                <td>
                    <button onclick="editRoomType(${room.room_type_id})">✏️</button>
                    <button onclick="deleteRoomType(${room.room_type_id})">🗑️</button>
                </td>
            `;
        }

        tableBody.appendChild(row);
    });

    updatePaginationControls();
}

function updatePaginationControls() {
    document.getElementById("prevBtn").disabled = currentPage === 1;
    document.getElementById("nextBtn").disabled = currentPage * rowsPerPage >= roomTypeData.length;
}

function nextPage() {
    if (currentPage * rowsPerPage < roomTypeData.length) {
        currentPage++;
        displayRoomTypes();
    }
}

function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        displayRoomTypes();
    }
}

// Function to trigger editing mode
function editRoomType(roomTypeId) {
    // Find the row with the roomTypeId
    let row = document.querySelector(`#row-${roomTypeId}`);
    let cells = row.getElementsByTagName('td');

    // Store original values for canceling edit
    let originalRoomType = cells[1].innerText;
    let originalPrice = cells[2].innerText;
    let originalMaxPerson = cells[3].innerText;

    // Convert cells to input fields
    cells[1].innerHTML = `<input type="text" value="${originalRoomType}" class="edit-input" id="edit-room-type">`;
    cells[2].innerHTML = `<input type="number" value="${originalPrice}" class="edit-input" id="edit-price">`;
    cells[3].innerHTML = `<input type="number" value="${originalMaxPerson}" class="edit-input" id="edit-max-person">`;

    // Replace icons with save and cancel buttons
    cells[4].innerHTML = `
        <button onclick="saveRoomType(${roomTypeId})">💾</button>
        <button onclick="cancelEdit(${roomTypeId}, '${originalRoomType}', '${originalPrice}', '${originalMaxPerson}')">❌</button>
    `;
}

// Function to save the edited room type
function saveRoomType(roomTypeId) {
    // Get the updated values from input fields
    let roomType = document.getElementById('edit-room-type').value;
    let price = document.getElementById('edit-price').value;
    let maxPerson = document.getElementById('edit-max-person').value;

    // Example: you could send the updated field, such as 'room_type', 'price', etc.
    let updates = [
        { field: "room_type", value: roomType },
        { field: "price", value: price },
        { field: "max_person", value: maxPerson }
    ];

    // Send the updated data to the server for each field
    updates.forEach(update => {
        fetch("setup/update_room_type.php", { 
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                id: roomTypeId, 
                field: update.field, 
                value: update.value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // If the update is successful, reload the room types table
                loadRoomTypes();
            } else {
                alert("Update failed: " + data.message);
            }
        })
        .catch(error => console.error("Error updating room type:", error));
    });
}

// Function to cancel the edit
function cancelEdit(roomTypeId, originalRoomType, originalPrice, originalMaxPerson) {
    // Revert the cells back to original values
    let row = document.querySelector(`#row-${roomTypeId}`);
    let cells = row.getElementsByTagName('td');

    cells[1].innerText = originalRoomType;
    cells[2].innerText = originalPrice;
    cells[3].innerText = originalMaxPerson;

    // Restore the pencil and delete icons
    cells[4].innerHTML = `
        <button onclick="editRoomType(${roomTypeId})">✏️</button>
        <button onclick="deleteRoomType(${roomTypeId})">🗑️</button>
    `;
}

// Function to delete a room type
function deleteRoomType(roomTypeId) {
    if (!confirm("Are you sure you want to delete this room type?")) return;

    // Send delete request to the server
    fetch("setup/delete_room_type.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: roomTypeId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // If the delete is successful, reload the room types table
            loadRoomTypes();
        } else {
            alert("Delete failed");
        }
    })
    .catch(error => console.error("Error deleting room type:", error));
}

function fetchHotelName() {
    fetch("setup/get_hotel_name.php")
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Display the hotel name in the table
                document.getElementById('hotel-name-text').innerText = data.hotel_name;
            } else {
                alert("Error fetching hotel name.");
            }
        })
        .catch(error => console.error("Error fetching hotel name:", error));
}
// Trigger edit mode
function editHotelName() {
    // Hide the table and show the edit form
    document.getElementById('hotel-name-row').style.display = 'none';
    document.getElementById('edit-form').style.display = 'block';

    // Pre-fill the input with the current hotel name
    let currentHotelName = document.getElementById('hotel-name-text').innerText;
    document.getElementById('edit-hotel-name').value = currentHotelName;
}

// Save the edited hotel name
function saveHotelName() {
    let newHotelName = document.getElementById('edit-hotel-name').value;

    // Validate input
    if (!newHotelName) {
        alert("Please enter a hotel name.");
        return;
    }

    // Send updated hotel name to the server
    fetch("setup/update_hotel_name.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ hotel_name: newHotelName })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the table and close the modal
            fetchHotelName();
            closeModal('hotelNameModal');
        } else {
            alert("Error updating hotel name.");
        }
    })
    .catch(error => console.error("Error saving hotel name:", error));
}
function getUsers() {
    fetch("setup/get_users.php")
        .then(response => response.json())
        .then(data => {
            userData = data.users;
            displayUserTable();
        })
        .catch(error => console.error("Error fetching users:", error));
}

function displayUserTable() {
    let tableBody = document.getElementById("user-table-body");
    tableBody.innerHTML = ""; // Clear the table

    let start = (currentPage - 1) * rowsPerPage;
    let end = start + rowsPerPage;
    let paginatedData = userData.slice(start, end);

    if (paginatedData.length === 0) {
        tableBody.innerHTML = "<tr><td colspan='5'>No Users Found</td></tr>";
        return;
    }

    paginatedData.forEach(user => {
        let row = document.createElement("tr");
        row.innerHTML = `
            <td>${user.id}</td>
            <td>${user.name}</td>
            <td>${user.username}</td>
            <td>${user.created_at}</td>
            <td>${user.role}</td>
        `;
        tableBody.appendChild(row);
    });

    updatePaginationControls();
}
function fetchUsers() {
    fetch('setup/fetch_users.php')  // Adjust the PHP endpoint to fetch user data
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tableBody = document.getElementById('user-table-body');
                tableBody.innerHTML = ''; // Clear the table

                data.users.forEach(user => {
                    const row = document.createElement('tr');

                    row.innerHTML = `
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td>
                            <button onclick="openPasswordModal(${user.id})">Change Password</button>
                        </td>
                        <td>
                            <button onclick="deleteUser(${user.id})">❌</button>
                        </td>
                    `;

                    tableBody.appendChild(row);
                });
            } else {
                alert('Error fetching users');
            }
        })
        .catch(error => console.error('Error:', error));
}

// Open the change password modal
function openPasswordModal(userId) {
    document.getElementById('user-id-password').value = userId;
    document.getElementById('updatePasswordModal').style.display = 'block';
}
// Handle password change form submission
document.getElementById('updatePasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const userId = document.getElementById('user-id-password').value;
    const newPassword = document.getElementById('new-password').value;

    // Hash the password before sending it to the server
    const hashedPassword = btoa(newPassword); // Simple encoding, consider using a stronger hashing approach client-side

    fetch('setup/update_password.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ userId, password: hashedPassword })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            fetchUsers();  // Refresh the user list
            closeModal('updatePasswordModal');
        } else {
            alert('Error updating password');
        }
    })
    .catch(error => console.error('Error:', error));
});

// Handle user deletion
function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        fetch('setup/delete_user.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ userId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchUsers();  // Refresh the user list
            } else {
                alert('Error deleting user');
            }
        })
        .catch(error => console.error('Error:', error));
    }
}
</script>