<?php
session_start();
include '../includes/db_connect.php';

// Fetch the hashed password from the database
$sql = "SELECT password FROM password LIMIT 1";
$result = $db->query($sql);

if ($result && $result->num_rows > 0) {
    $hashed_password = $result->fetch_assoc()['password'];
} else {
    die("No password set in the database.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_password = $_POST["password"];
    
    if ($entered_password === $hashed_password) {
        $_SESSION["admin_authenticated"] = true;
        header("Location: admin.php");
        exit();
    } else {
        $error_message = "Incorrect password. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - TOTSY.pk</title>
    <link rel="icon" href="../logo/totsy_logo.jpg" type="image/x-icon">
    <?php include '../includes/header-links.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body, html {
            height: 100%;
            background-color: #f8f9fa;
        }
        .gradient-text {
            font-weight: 500;
            background: linear-gradient(90deg, #4ab6f4, #ff69b4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: transparent;
            border-bottom: none;
            padding-top: 30px;
        }
        .btn-primary {
            background: linear-gradient(90deg, #4ab6f4, #ff69b4);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            opacity: 0.9;
            box-shadow: 0 0 10px rgba(74, 182, 244, 0.5), 0 0 10px rgba(255, 105, 180, 0.5);
        }
        .password-container {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #4ab6f4;
        }
    </style>
</head>
<body class="d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card animate__animated animate__fadeInDown">
                    <div class="card-header">
                        <h2 class="text-center">
                            <span class="gradient-text">TOTSY</span><span style="font-weight: 300;">.pk</span>
                        </h2>
                        <h3 class="text-center mt-3" style="font-weight: 300;">Admin Login</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error_message)): ?>
                            <div class='alert alert-danger animate__animated animate__shakeX'><?= $error_message ?></div>
                        <?php endif; ?>
                        <form method="POST" action="" class="animate__animated animate__fadeInUp animate__delay-1s">
                            <div class="mb-4 password-container">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <i class="fas fa-eye password-toggle mt-3" id="togglePassword" style="top: 50%; transform: translateY(-50%);"></i>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include '../includes/footer_links.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new bootstrap.Tooltip(document.body, {
                selector: '[data-bs-toggle="tooltip"]'
            });

            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#password');

            togglePassword.addEventListener('click', function (e) {
                // toggle the type attribute
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                // toggle the eye slash icon
                this.classList.toggle('fa-eye-slash');
            });
        });
    </script>
</body>
</html>