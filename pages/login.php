<?php
// Include database connection
include_once '../includes/db_connect.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'login') {
            // Login logic
            $email = $db->real_escape_string($_POST['email']);
            $password = $_POST['password'];

            $stmt = $db->prepare("SELECT id, email, password FROM user WHERE email = ?");
            if ($stmt === false) {
                die("Error preparing statement: " . $db->error);
            }
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    session_start(); // Start the session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_authenticated'] = true; // Set the authentication flag
                    $success = "Login successful! Redirecting to homepage...";
                    header("refresh:2;url=../index.php");
                    exit(); // Always use exit after header redirection
                } else {
                    $error = "Invalid email or password";
                }
            } else {
                $error = "Invalid email or password";
            }
            $stmt->close();
        } elseif ($_POST['action'] == 'signup') {
            // Signup logic
            $email = $db->real_escape_string($_POST['email']);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            // Validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Invalid email format";
            } else {
                $check_email = $db->prepare("SELECT id FROM user WHERE email = ?");
                if ($check_email === false) {
                    die("Error preparing statement: " . $db->error);
                }
                $check_email->bind_param("s", $email);
                $check_email->execute();
                $result = $check_email->get_result();
                if ($result->num_rows > 0) {
                    $error = "Email already exists";
                }
                $check_email->close();
            }

            // Validate password
            if (strlen($password) < 8) {
                $error = "Password must be at least 8 characters long";
            } elseif ($password !== $confirm_password) {
                $error = "Passwords do not match";
            }

            // If there are no errors, proceed with registration
            if (empty($error)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $db->prepare("INSERT INTO user (email, password) VALUES (?, ?)");
                if ($stmt === false) {
                    die("Error preparing statement: " . $db->error);
                }
                $stmt->bind_param("ss", $email, $hashed_password);

                if ($stmt->execute()) {
                    $success = "Registration successful! You can now log in.";
                } else {
                    $error = "Registration failed. Error: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }
}

// Close the database connection
$db->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Signup - Totsy</title>
    <link rel="icon" href="../logo/totsy_logo.jpg" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="../css/styles.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4ab6f4;
            --secondary-color: #ff69b4;
        }
        body {
           
            display: flex;
            align-items: center;
            background-color: #f8f9fa;
            padding: 2rem 0;
        }
        .gradient-text {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .gradient-bg {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }
        .form-control:focus {
            color: #000;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(74, 182, 244, 0.25);
        }
        .btn-gradient {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            color: white;
        }
        .btn-outline {
            background: transparent;
            border: 2px solid white;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-outline:hover {
            background: white;
            color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(255,255,255,0.2);
        }
        .flip-container {
            perspective: 1000px;
        }
        .flipper {
            transition: 0.6s;
            transform-style: preserve-3d;
            position: relative;
        }
        .front, .back {
            backface-visibility: hidden;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        .back {
            transform: rotateY(180deg);
        }
        .flip {
            transform: rotateY(180deg);
        }
        .card {
            border-radius: 20px;
            overflow: hidden;
        }
        .card-body {
            padding: 2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-5 gradient-text">Welcome to Totsy</h1>
        <?php if ($error || $success): ?>
            <div class="row justify-content-center mb-4">
                <div class="col-lg-10">
                    <div class="card shadow">
                        <div class="card-body">
                            <?php if ($error): ?>
                                <div class="alert alert-danger mb-0" style="background: linear-gradient(90deg, #ff69b4, #4ab6f4); color: white; border: none; border-radius: 10px; padding: 15px;">
                                    <i class='bx bx-error-circle'></i> <?php echo $error; ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($success): ?>
                                <div class="alert alert-success mb-0" style="background: linear-gradient(90deg, #4ab6f4, #ff69b4); color: white; border: none; border-radius: 10px; padding: 15px;">
                                    <i class='bx bx-check-circle'></i> <?php echo $success; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="flip-container">
                    <div class="flipper" id="flipper">
                        <div class="front">
                            <div class="card shadow">
                                <div class="row g-0">
                                    <div class="col-md-6">
                                        <div class="card-body">
                                            <h2 class="text-center mb-4 gradient-text">Login</h2>
                                            <form method="POST" action="">
                                                <input type="hidden" name="action" value="login">
                                                <div class="mb-4">
                                                    <label for="login-email" class="form-label gradient-text"><i class='bx bx-envelope'></i> Email</label>
                                                    <input type="email" class="form-control" id="login-email" name="email" required>
                                                </div>
                                                <div class="mb-4">
                                                    <label for="login-password" class="form-label gradient-text"><i class='bx bx-lock-alt'></i> Password</label>
                                                    <input type="password" class="form-control" id="login-password" name="password" required>
                                                </div>
                                                <button type="submit" class="btn btn-gradient w-100 py-2">
                                                    <i class='bx bx-log-in'></i> Login
                                                </button>
                                                <a href="../components/user_logout.php" class="mt-3 btn btn-gradient w-100 py-2">
                                                    <i class='bx bx-log-in'></i> Logout
                                                </a>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-md-6 gradient-bg">
                                        <div class="card-body text-white d-flex flex-column justify-content-between h-100">
                                            <div>
                                           
                                                <div class="text-center mb-4">
                                                    <img src="../logo/totsy_logo.jpg" alt="TOTSY Logo - Kids Cartoon Printed Single Bed Sheets" class="img-fluid mx-auto d-block" style="max-width: 150px; height: auto; border-radius: 50%;">
                                                </div>
                                                <h2 class="text-center mb-4">About Totsy</h2>
                                                <p>Totsy is your one-stop shop for all things trendy and cute. Join our community to discover the latest fashion, accessories, and more!</p>
                                            </div>
                                            <button class="btn btn-outline w-100 py-2 mt-4" onclick="flip()">
                                                <i class='bx bx-user-plus'></i> Sign Up
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="back">
                            <div class="card shadow">
                                <div class="row g-0">
                                    <div class="col-md-6 gradient-bg">
                                        <div class="card-body text-white d-flex flex-column justify-content-between h-100">
                                        <div class="text-center mb-4">
                                                    <img src="../logo/totsy_logo.jpg" alt="TOTSY Logo - Kids Cartoon Printed Single Bed Sheets" class="img-fluid mx-auto d-block" style="max-width: 150px; height: auto; border-radius: 50%;">
                                                </div>
                                            <div>
                                                <h2 class="text-center mb-4">About Totsy</h2>
                                                <p>Totsy is your one-stop shop for all things trendy and cute. Join our community to discover the latest fashion, accessories, and more!</p>
                                            </div>
                                            <button class="btn btn-outline w-100 py-2 mt-4" onclick="flip()">
                                                <i class='bx bx-log-in'></i> Login
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-body">
                                            <h2 class="text-center mb-4 gradient-text">Sign Up</h2>
                                            <form method="POST" action="">
                                                <input type="hidden" name="action" value="signup">
                                                <div class="mb-3">
                                                    <label for="signup-email" class="form-label gradient-text"><i class='bx bx-envelope'></i> Email</label>
                                                    <input type="email" class="form-control" id="signup-email" name="email" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="signup-password" class="form-label gradient-text"><i class='bx bx-lock-alt'></i> Password</label>
                                                    <input type="password" class="form-control" id="signup-password" name="password" required>
                                                </div>
                                                <div class="mb-4">
                                                    <label for="confirm-password" class="form-label gradient-text"><i class='bx bx-lock-alt'></i> Confirm Password</label>
                                                    <input type="password" class="form-control" id="confirm-password" name="confirm_password" required>
                                                </div>
                                                <button type="submit" class="btn btn-gradient w-100 py-2">
                                                    <i class='bx bx-user-plus'></i> Sign Up
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function flip() {
            document.getElementById('flipper').classList.toggle('flip');
        }
    </script>
</body>
</html>