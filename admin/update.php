<?php
// Include your database connection file
include '../includes/db_connect.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$table = $_GET['table'] ?? $_POST['table'] ?? '';
$id = $_GET['id'] ?? $_POST['id'] ?? '';

if (empty($table) || empty($id)) {
    die("Table or ID not provided");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle the form submission to update the record
    $updates = [];
    foreach ($_POST as $key => $value) {
        if ($key != 'table' && $key != 'id') {
            $updates[] = "`$key` = '" . $db->real_escape_string($value) . "'";
        }
    }
    
    $updateQuery = "UPDATE `$table` SET " . implode(', ', $updates) . " WHERE `id` = $id";
    if ($db->query($updateQuery)) {
        echo "<script>alert('Record updated successfully'); window.location.href = 'data.php';</script>";
    } else {
        echo "Error updating record: " . $db->error;
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
    <title>Update Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Update Record in <?php echo htmlspecialchars($table); ?></h1>
        <form method="post">
            <input type="hidden" name="table" value="<?php echo htmlspecialchars($table); ?>">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <?php
            foreach ($record as $key => $value) {
                if ($key != 'id') {
                    echo "<div class='mb-3'>";
                    echo "<label for='$key' class='form-label'>" . htmlspecialchars($key) . "</label>";
                    echo "<input type='text' class='form-control' id='$key' name='$key' value='" . htmlspecialchars($value) . "'>";
                    echo "</div>";
                }
            }
            ?>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="view_data.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>