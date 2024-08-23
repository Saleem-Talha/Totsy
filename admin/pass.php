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
    <title>Admin Login</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Admin Login</h2>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error_message)): ?>
                            <div class='alert alert-danger'><?= $error_message ?></div>
                        <?php endif; ?>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include '../includes/footer_links.php'; ?>
</body>
</html>
