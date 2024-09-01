<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_authenticated']) || $_SESSION['user_authenticated'] !== true) {
    // Not logged in, redirect to login page
    header("Location: ../pages/login.php");
    exit();
}
?>
