<?php
// Include your database connection file
include '../includes/db_connect.php';
include 'auth_check.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

function deleteRecord($table, $id) {
    global $db;
    $query = "DELETE FROM $table WHERE id = $id";
    return $db->query($query);
}

// Initialize toast message
$toast_message = '';
$toast_type = '';

// Handle delete requests
if (isset($_GET['delete'])) {
    $table = $_GET['table'];
    $id = $_GET['id'];
    if ($table !== 'orders' && deleteRecord($table, $id)) {
        $toast_message = "Record deleted successfully";
        $toast_type = "success";
    } else {
        $toast_message = "Error deleting record";
        $toast_type = "error";
    }
}

// Function to fetch all records from a table
function getRecords($table) {
    global $db;
    $query = "SELECT * FROM $table";
    $result = $db->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Function to get user email by id
function getUserEmail($user_id) {
    global $db;
    $query = "SELECT email FROM user WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    return $user ? $user['email'] : 'Unknown';
}

// Function to get product name by id
function getProductName($product_id) {
    global $db;
    $query = "SELECT title FROM products WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    return $product ? $product['title'] : 'Unknown';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Data View - TOTSY.pk</title>
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
            margin-bottom: 2rem;
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
        .btn-danger {
            background: linear-gradient(90deg, #ff6b6b, #ff8e8e);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-danger:hover {
            opacity: 0.9;
            box-shadow: 0 0 10px rgba(255, 107, 107, 0.5), 0 0 10px rgba(255, 142, 142, 0.5);
        }
        h2 {
            font-weight: 300;
            color: #333;
            margin-bottom: 1.5rem;
        }
        .table {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
        }
        .table thead th {
            background-color: #f8f9fa;
            border-top: none;
        }
        .product-image, .order-image {
            max-width: 100px;
            max-height: 100px;
            cursor: pointer;
        }
        .modal-body img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-5">
            <span class="gradient-text">TOTSY</span><span style="font-weight: 300;">.pk</span>
            <br>
            <small class="text-muted" style="font-weight: 300;">Admin Data View</small>
        </h1>

        <div class="text-center mb-4">
            <a href="admin.php" class="btn btn-sm btn-primary">Back to Admin Panel</a>
        </div>

        <?php
        $tables = ['products', 'availability', 'reviews', 'offers', 'cart', 'user', 'orders'];

        foreach ($tables as $table) {
            echo "<div class='card mb-4'>";
            echo "<div class='card-header'>";
            echo "<h2 class='text-center'>" . htmlspecialchars(ucfirst($table)) . "</h2>";
            echo "</div>";
            echo "<div class='card-body'>";
            
            $records = getRecords($table);

            if (empty($records)) {
                echo "<p class='text-center'>No records found in " . htmlspecialchars($table) . ".</p>";
            } else {
                echo "<div class='table-responsive'>";
                echo "<table class='table table-hover'>";
                echo "<thead><tr>";
                foreach ($records[0] as $key => $value) {
                    if ($table === 'orders' && $key === 'user_id') {
                        echo "<th>User Email</th>";
                    } elseif ($table === 'cart' && $key === 'user_id') {
                        echo "<th>User Email</th>";
                    } elseif ($table === 'cart' && $key === 'product_id') {
                        echo "<th>Product Name</th>";
                    } elseif ($table === 'cart' && $key === 'price_at_addition') {
                        // Skip this column for cart table
                        continue;
                    } else {
                        echo "<th>" . htmlspecialchars($key) . "</th>";
                    }
                }
                if ($table !== 'orders' && $table !== 'cart') {
                    echo "<th>Actions</th>";
                }
                echo "</tr></thead>";
                echo "<tbody>";
                foreach ($records as $record) {
                    echo "<tr>";
                    foreach ($record as $key => $value) {
                        if ($key === 'image' && $table === 'products') {
                            $image_path = "../admin/uploads/" . basename($value);
                            if (file_exists($image_path) && is_readable($image_path)) {
                                echo "<td><img src='" . htmlspecialchars($image_path) . "' alt='Product Image' class='product-image' onclick='showImageModal(this.src)'></td>";
                            } else {
                                echo "<td>Image not found</td>";
                            }
                        } elseif ($key === 'order_image' && $table === 'orders') {
                            $image_path = "../order_slips/" . basename($value);
                            if (file_exists($image_path) && is_readable($image_path)) {
                                echo "<td><img src='" . htmlspecialchars($image_path) . "' alt='Order Slip' class='order-image' onclick='showImageModal(this.src)'></td>";
                            } else {
                                echo "<td>Order slip not found</td>";
                            }
                        } elseif (($table === 'orders' || $table === 'cart') && $key === 'user_id') {
                            echo "<td>" . htmlspecialchars(getUserEmail($value)) . "</td>";
                        } elseif ($table === 'cart' && $key === 'product_id') {
                            echo "<td>" . htmlspecialchars(getProductName($value)) . "</td>";
                        } elseif ($table === 'cart' && $key === 'price_at_addition') {
                            // Skip this column for cart table
                            continue;
                        } else {
                            echo "<td>" . htmlspecialchars($value) . "</td>";
                        }
                    }
                    if ($table !== 'orders' && $table !== 'cart') {
                        echo "<td>
                                <a href='update.php?table=" . urlencode($table) . "&id=" . urlencode($record['id']) . "' class='btn btn-primary btn-sm me-2 mb-2'>Update</a>
                                <a href='?delete=1&table=" . urlencode($table) . "&id=" . urlencode($record['id']) . "' class='btn btn-danger btn-sm mb-2' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                              </td>";
                    }
                    echo "</tr>";
                }
                echo "</tbody></table>";
                echo "</div>";
            }
            
            echo "</div>"; // card-body
            echo "</div>"; // card
        }
        ?>

    </div>

    

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="" id="modalImage" alt="Full size image">
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer_links.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        <?php if (!empty($toast_message)): ?>
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            toastr.<?php echo $toast_type; ?>('<?php echo $toast_message; ?>');
        <?php endif; ?>

        function showImageModal(src) {
            document.getElementById('modalImage').src = src;
            var imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
            imageModal.show();
        }
    </script>
</body>
</html>