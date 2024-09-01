<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_authenticated']) || $_SESSION['admin_authenticated'] !== true) {
    // Not logged in, redirect to login page
    header("Location: pass.php");
    exit();
}
?>