<?php
include_once '../includes/db_connect.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $db->real_escape_string($_POST['email']);
    $description = $db->real_escape_string($_POST['description']);
    $rating = intval($_POST['rating']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } elseif (strlen($description) < 10) {
        $error = "Description must be at least 10 characters long";
    } elseif ($rating < 1 || $rating > 5) {
        $error = "Rating must be between 1 and 5";
    } else {
        $stmt = $db->prepare("INSERT INTO reviews (email, description, rating, date) VALUES (?, ?, ?, CURDATE())");
        if ($stmt === false) {
            die("Error preparing statement: " . $db->error);
        }
        $stmt->bind_param("ssi", $email, $description, $rating);
        
        if ($stmt->execute()) {
            $success = "Thank you for your feedback!";
            echo "<script>
                setTimeout(function() {
                    alert('Thank you for your feedback! Redirecting to home page...');
                    window.location.href = '../index.php';
                }, 1000);
            </script>";
        } else {
            $error = "Error submitting feedback. Please try again.";
        }
        $stmt->close();
    }
}

$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback - Totsy</title>
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
        }
        .card {
            border-radius: 20px;
            overflow: hidden;
        }
        .card-body {
            padding: 2rem;
        }
        .btn-back {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="../index.php" class="btn btn-gradient btn-back">
            <i class='bx bx-arrow-back'></i> Back to Home
        </a>
        <h1 class="text-center mb-5 gradient-text">Submit Your Feedback</h1>
        <?php if ($error || $success): ?>
            <div class="row justify-content-center mb-4">
                <div class="col-lg-8">
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
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="mb-4">
                                <label for="email" class="form-label gradient-text"><i class='bx bx-envelope'></i> Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-4">
                                <label for="description" class="form-label gradient-text"><i class='bx bx-message-detail'></i> Feedback</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="rating" class="form-label gradient-text"><i class='bx bx-star'></i> Rating</label>
                                <select class="form-select" id="rating" name="rating" required>
                                    <option value="">Select a rating</option>
                                    <option value="1">1 - Poor</option>
                                    <option value="2">2 - Fair</option>
                                    <option value="3">3 - Good</option>
                                    <option value="4">4 - Very Good</option>
                                    <option value="5">5 - Excellent</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-gradient w-100 py-2">
                                <i class='bx bx-send'></i> Submit Feedback
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>