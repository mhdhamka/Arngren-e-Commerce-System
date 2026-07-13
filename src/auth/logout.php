<?php
session_start();
include("../config/db_carngren.php");

// Get user ID before destroying session
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    
    // Update user's login status in database
    $stmt = $conn->prepare("UPDATE user SET logStatus = 0 WHERE userID = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
}

// Destroy session
session_destroy();

// Clear session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// Redirect to home
header("Location: index.php");
exit();
?>