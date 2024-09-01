<?php
include_once '../components/user_auth_check.php'; // Ensure user authentication
include_once '../includes/db_connect.php';
session_start();

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Fetch user email from the database
$user_query = "SELECT email FROM user WHERE id = ?";
$user_stmt = $db->prepare($user_query);
if ($user_stmt === false) {
    die("Error preparing statement: " . $db->error);
}
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();
$user_email = htmlspecialchars($user['email']);
$user_stmt->close();

$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - TOTSY.pk</title>
    <link rel="icon" href="../logo/totsy_logo.jpg" type="image/x-icon">
    <?php include '../includes/header-links.php'; ?>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: 300;
        }
        .gradient-text {
            background: linear-gradient(90deg, #ff69b4, #4ab6f4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-body {
            padding: 40px;
        }
    </style>
</head>
<body>
    <?php include '../includes/other_nav.php'; ?>
    <div class="container">
        <h1 class="mb-4">
            <span class="gradient-text">Thank You!</span>
        </h1>

        <div class="card">
            <div class="card-body text-center">
                <img src="../logo/totsy_logo.jpg" alt="TOTSY.pk Logo" style="width: 100px; height: 100px; border-radius: 50%; margin-bottom: 20px;">
                <h2 class="mb-4">Your order has been placed successfully!</h2>
                <p class="mb-3">Thank you for shopping with TOTSY.pk</p>
                <p class="mb-4">For further queries, please DM us at <a href="https://www.instagram.com/totsy.pk/" target="_blank" class="text-decoration-none">     <span style="font-weight: 500; background: linear-gradient(90deg, #4ab6f4, #ff69b4); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">TOTSY</span>
                </a></p>
                
            </div>
            
        </div>
        
    </div>
    <?php include '../includes/other_footer.php'; ?>
</body>
</html>
