<?php
// Include your database connection file
include '../includes/db_connect.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to delete a record
function deleteRecord($table, $id) {
    global $db;
    $query = "DELETE FROM $table WHERE id = $id";
    return $db->query($query);
}

// Handle delete requests
if (isset($_GET['delete'])) {
    $table = $_GET['table'];
    $id = $_GET['id'];
    if (deleteRecord($table, $id)) {
        echo "<script>alert('Record deleted successfully');</script>";
    } else {
        echo "<script>alert('Error deleting record');</script>";
    }
}

// Function to fetch all records from a table
function getRecords($table) {
    global $db;
    $query = "SELECT * FROM $table";
    $result = $db->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Data View</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Admin Data View</h1>

        <?php
        $tables = ['products', 'availability', 'reviews', 'offers', 'cart'];

        foreach ($tables as $table) {
            echo "<h2 class='mt-4'>" . htmlspecialchars(ucfirst($table)) . "</h2>";
            $records = getRecords($table);

            if (empty($records)) {
                echo "<p>No records found in " . htmlspecialchars($table) . ".</p>";
            } else {
                echo "<div class='table-responsive'>";
                echo "<table class='table table-striped'>";
                echo "<thead><tr>";
                foreach ($records[0] as $key => $value) {
                    echo "<th>" . htmlspecialchars($key) . "</th>";
                }
                echo "<th>Actions</th>";
                echo "</tr></thead>";
                echo "<tbody>";
                foreach ($records as $record) {
                    echo "<tr>";
                    foreach ($record as $value) {
                        echo "<td>" . htmlspecialchars($value) . "</td>";
                    }
                    echo "<td>
                            <a href='update.php?table=" . urlencode($table) . "&id=" . urlencode($record['id']) . "' class='btn btn-primary btn-sm'>Update</a>
                            <a href='?delete=1&table=" . urlencode($table) . "&id=" . urlencode($record['id']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
                echo "</div>";
            }
        }
        ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>