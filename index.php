<?php

// Developed By Derrick for Mclogic Systems
//Contact me @ crazy.dre on instagram, @dreneedmoreice on X.com formerly Twitter or @ lifewithderrhoe@gmail.com
//For more projects visit my Github profile @xploit1017
//Dont use for commercial purposes without consent

include_once "db.php";
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user details, including the role
$userQuery = "SELECT * FROM user WHERE id = ?";
$stmt = $connection->prepare($userQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    // Invalid session, redirect to login
    header('Location: login.php');
    exit;
}

// Store the user role in a variable for convenience
$user_role = $user['role'];

// Include common layout components
include_once "header.php";
include_once "sidebar.php";

// Identify the page based on the query parameter
$page = null;

// Loop through $_GET keys to find the page name
foreach ($_GET as $key => $value) {
    $page = htmlspecialchars($key); // Sanitize the key for security
    break; // Only use the first key
}

// Default to 'dashboard' if no valid page is found
if (!$page) {
    $page = 'dashboard';
}

// Define role-based page access
$adminPages = ['dashboard', 'reservation','room_mang', 'setup', 'Meals', 'laundry', 'reports', 'orders'];
$userPages = ['dashboard', 'reservation', 'Meals', 'laundry','orders','room_mang'];

// Check if the user has permission to access the requested page
if (
    ($user_role === 'admin' && !in_array($page, $adminPages)) || 
    ($user_role === 'user' && !in_array($page, $userPages))
) {

    include_once "403.php"; // Optional: Create a 403 Forbidden page
    include_once "footer.php";
    exit;
}

// Include the requested page or fallback to default
switch ($page) {
    case 'room_mang':
        include_once "room_mang.php";
        break;
    case 'dashboard':
        include_once "dashboard.php";
        break;
    case 'reservation':
        include_once "reservation.php";
        break;
    case 'setup':
        include_once "setup.php";
        break;
    case 'Meals':
        include_once "Meals.php";
        break;
    case 'laundry':
        include_once "laundry.php";
        break;
    case 'reports':
        include_once "reports.php";
        break;
    case 'orders':
        include_once "orders.php";
        break;
    default:
        
        include_once "404.php";
        break;
}

// Include footer
include_once "footer.php";
?>
