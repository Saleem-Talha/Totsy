<?php
// Include your database connection file
include '../includes/db_connect.php';
include 'auth_check.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$table = $_GET['table'] ?? $_POST['table'] ?? '';
$id = $_GET['id'] ?? $_POST['id'] ?? '';

if (empty($table) || empty($id)) {
    die("Table or ID not provided");
}

$toast_message = '';
$toast_type = '';
$update_success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle the form submission to update the record
    $updates = [];
    if ($table === 'orders') {
        // For orders table, only allow status update
        if (isset($_POST['status'])) {
            $status = $db->real_escape_string($_POST['status']);
            $updates[] = "`status` = '$status'";
        }
    } else {
        foreach ($_POST as $key => $value) {
            if ($key != 'table' && $key != 'id') {
                $updates[] = "`$key` = '" . $db->real_escape_string($value) . "'";
            }
        }
    }
    
    if (!empty($updates)) {
        $updateQuery = "UPDATE `$table` SET " . implode(', ', $updates) . " WHERE `id` = $id";
        if ($db->query($updateQuery)) {
            $toast_message = "Record updated successfully";
            $toast_type = "success";
            $update_success = true;
        } else {
            $toast_message = "Error updating record: " . $db->error;
            $toast_type = "error";
        }
    } else {
        $toast_message = "No updates were made";
        $toast_type = "warning";
    }
}

// Fetch the record to be updated
$query = "SELECT * FROM `$table` WHERE `id` = $id";
$result = $db->query($query);
if ($result && $result->num_rows > 0) {
    $record = $result->fetch_assoc();
} else {
    die("Record not found");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Record - TOTSY.pk</title>
    <link rel="icon" href="../logo/totsy_logo.jpg" type="image/x-icon">
    <?php include '../includes/header-links.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 2rem;
            padding-bottom: 2rem;
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
            padding-top: 1.5rem;
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
        .btn-secondary {
            background: #6c757d;
            border: none;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            opacity: 0.9;
            box-shadow: 0 0 10px rgba(108, 117, 125, 0.5);
        }
        h1, h2 {
            font-weight: 300;
            color: #333;
        }
        .update-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
            padding: 0.75rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-5">
            <span class="gradient-text">TOTSY</span><span style="font-weight: 300;">.pk</span>
            <br>
            <small class="text-muted" style="font-weight: 300;">Update Record</small>
        </h1>

        <div class="card">
            <div class="card-header">
                <h2 class="text-center">Update Record in <?php echo htmlspecialchars(ucfirst($table)); ?></h2>
            </div>
            <div class="card-body">
                <?php if ($update_success): ?>
                    <div class="update-success">
                        Record updated successfully!
                    </div>
                <?php endif; ?>
                <form method="post">
                    <input type="hidden" name="table" value="<?php echo htmlspecialchars($table); ?>">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                    <?php
                    if ($table === 'orders') {
                        echo "<div class='mb-3'>";
                        echo "<label for='status' class='form-label'>Status</label>";
                        echo "<select class='form-select' id='status' name='status'>";
                        $statuses = ['Pending', 'Completed'];
                        foreach ($statuses as $status) {
                            $selected = ($record['status'] == $status) ? 'selected' : '';
                            echo "<option value='$status' $selected>$status</option>";
                        }
                        echo "</select>";
                        echo "</div>";
                    } else {
                        foreach ($record as $key => $value) {
                            if ($key != 'id') {
                                echo "<div class='mb-3'>";
                                echo "<label for='$key' class='form-label'>" . htmlspecialchars(ucfirst($key)) . "</label>";
                                echo "<input type='text' class='form-control' id='$key' name='$key' value='" . htmlspecialchars($value) . "'>";
                                echo "</div>";
                            }
                        }
                    }
                    ?>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="submit" class="btn btn-primary me-md-2">Update</button>
                        <a href="data.php" class="btn btn-primary">Back to Data</a>
                        <a href="data.php" class="btn btn-danger me-md-2">Cancel</a>
                       
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include '../includes/footer_links.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        <?php
        if (!empty($toast_message)) {
            echo "toastr['$toast_type']('$toast_message');";
        }
        ?>
    </script>
</body>
</html>