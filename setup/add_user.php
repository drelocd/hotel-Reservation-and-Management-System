<?php
// Include the database connection file
include 'db.php';  // Ensure this points to the correct location of db.php

if (isset($_POST['submit'])) {
    // Collect form data
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Hash the password using bcrypt
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Prepare the SQL query to insert the new user
    $query = "INSERT INTO user (name, username, email, password, role) 
              VALUES ('$name', '$username', '$email', '$hashedPassword', '$role')";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        // If insertion is successful, redirect back to index.php with ?setup query parameter
        header('Location: index.php?');
        exit(); // Make sure to exit after header redirection
    } else {
        // If insertion fails, display an error message
        echo "Error: " . mysqli_error($conn);  // Use $conn here to get detailed error
    }
}
?>
