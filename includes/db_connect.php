<?php

// Begin output buffering
ob_start();

// Database connection details
$hostname = "localhost"; // The hostname of the database server
$username = "root"; // The username used to access the database
$password = ""; // The password for the database user
$database = "totsy_db"; // The name of the database

// Create a new MySQLi object to connect to the database
$db = new mysqli($hostname, $username, $password, $database);

// Check for a connection error and stop the script if one occurs
if ($db->connect_error) {
    // Log the error instead of displaying it publicly
    error_log("Database connection failed: " . $db->connect_error);
    die("We're experiencing technical difficulties. Please try again later.");
}

// Set the default timezone to Pakistan Standard Time
date_default_timezone_set('Asia/Karachi');

// Set character encoding to UTF-8
if (!$db->set_charset("utf8")) {
    error_log("Error loading character set utf8: " . $db->error);
}

// Prepare a function to sanitize user inputs
function sanitize_input($input) {
    global $db;
    return $db->real_escape_string(trim($input));
}

// Enable error reporting for development, disable for production
ini_set('display_errors', 0);
error_reporting(E_ALL);
