<?php

// Begin output buffering
ob_start();

// Database connection details
$hostname = "localhost"; // The hostname of the database server
$username = "root"; // The username used to access the database
$password = ""; // The password for the database user
$dataabase = "totsy_db"; // The name of the database

// Create a new MySQLi object to connect to the database
$db = new mysqli($hostname, $username, $password, $dataabase);

// Check for a connection error and stop the script if one occurs
if ($db->connect_error) {
    die("Connection Failed: " . $db->connect_error); // Display error message and terminate the script
}

// Set the default timezone to Pakistan Standard Time
date_default_timezone_set('Asia/Karachi');
