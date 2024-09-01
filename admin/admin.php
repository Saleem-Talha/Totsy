<?php
// Start the session at the very beginning of the script
session_start();

// Include your database connection file
include '../includes/db_connect.php';

// Check if the user is already logged in
if (!isset($_SESSION['admin_authenticated']) || $_SESSION['admin_authenticated'] !== true) {
    // If not logged in, check if it's a login attempt
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
        // Fetch the hashed password from the database
        $sql = "SELECT password FROM password LIMIT 1";
        $result = $db->query($sql);

        if ($result && $result->num_rows > 0) {
            $hashed_password = $result->fetch_assoc()['password'];
            
            // Verify the submitted password
            if (verify_password($_POST['password'], $hashed_password)) {
                $_SESSION['admin_authenticated'] = true;
            } else {
                // Incorrect password, redirect back to login page
                header("Location: pass.php?error=1");
                exit();
            }
        } else {
            die("No password set in the database.");
        }
    } else {
        // Not logged in and not a login attempt, redirect to login page
        header("Location: pass.php");
        exit();
    }
}

// If we reach here, the user is authenticated or it's a valid form submission

function verify_password($entered_password, $hashed_password) {
    return $entered_password === $hashed_password;
}

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to handle file upload
function uploadImage($file) {
    $target_dir = "uploads/";
    
    // Create the directory if it doesn't exist
    if (!file_exists($target_dir) && !is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    
    $target_file = $target_dir . basename($file["name"]);
    
    // Check if file already exists, if so, append a number
    $i = 1;
    $file_parts = pathinfo($target_file);
    while (file_exists($target_file)) {
        $target_file = $target_dir . $file_parts['filename'] . '_' . $i . '.' . $file_parts['extension'];
        $i++;
    }
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $target_file;
    }
    return false;
}

// Initialize a variable to store toast messages
$toast_message = '';

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_product'])) {
        $title = $db->real_escape_string($_POST['title']);
        $price = intval($_POST['price']);
        $created_at = date('Y-m-d');
        $times_sold = intval($_POST['times_sold']); 
        $type = $db->real_escape_string($_POST['type']);
        
        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $image = uploadImage($_FILES['image']);
            if ($image === false) {
                $toast_message = "Failed to upload image.";
            }
        } else {
            $toast_message = "No file was uploaded or an error occurred during upload.";
        }
        
        $query = "INSERT INTO products (title, image, created_at, price, times_sold, type) 
                  VALUES ('$title', '$image', '$created_at', $price, $times_sold, '$type')";
        if ($db->query($query) === TRUE) {
            $product_id = $db->insert_id;
            
            // Add the product to availability with "In stock" status
            $availability_query = "INSERT INTO availability (status, product_id) VALUES ('In stock', $product_id)";
            if ($db->query($availability_query) === TRUE) {
                $toast_message = "Product added successfully and set to 'In stock'";
            } else {
                $toast_message = "Product added but failed to set availability: " . $db->error;
            }
        } else {
            $toast_message = "Error: " . $query . "<br>" . $db->error;
        }
    }
    elseif (isset($_POST['add_availability'])) {
        $status = $db->real_escape_string($_POST['status']);
        $product_name = $db->real_escape_string($_POST['product_name']);
        
        // First, get the product_id based on the product name
        $query = "SELECT id FROM products WHERE title = '$product_name'";
        $result = $db->query($query);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $product_id = $row['id'];
            
            $query = "INSERT INTO availability (status, product_id) VALUES ('$status', $product_id)";
            if ($db->query($query) === TRUE) {
                $toast_message = "Availability added successfully";
            } else {
                $toast_message = "Error: " . $query . "<br>" . $db->error;
            }
        } else {
            $toast_message = "Product not found";
        }
    }
    
    elseif (isset($_POST['add_offer'])) {
        $product_name = $db->real_escape_string($_POST['product_name']);
        $discount = intval($_POST['discount']);
        $start_date = $db->real_escape_string($_POST['start_date']);
        $end_date = $db->real_escape_string($_POST['end_date']);
        
        // First, get the product_id based on the product name
        $query = "SELECT id FROM products WHERE title = '$product_name'";
        $result = $db->query($query);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $product_id = $row['id'];
            
            $query = "INSERT INTO offers (product_id, discount, start_date, end_date) 
                      VALUES ($product_id, $discount, '$start_date', '$end_date')";
            if ($db->query($query) === TRUE) {
                $toast_message = "Offer added successfully";
            } else {
                $toast_message = "Error: " . $query . "<br>" . $db->error;
            }
        } else {
            $toast_message = "Product not found";
        }
    }
   
    elseif (isset($_POST['change_password'])) {
        $new_password = $_POST['password'];
        
        // First, delete the existing password
        $delete_query = "DELETE FROM password";
        if ($db->query($delete_query) === TRUE) {
            // Now insert the new password
            $insert_query = "INSERT INTO password (password) VALUES ('$new_password')";
            if ($db->query($insert_query) === TRUE) {
                $toast_message = "Password changed successfully";
            } else {
                $toast_message = "Error inserting new password: " . $db->error;
            }
        } else {
            $toast_message = "Error deleting old password: " . $db->error;
        }
    }
}

$product_query = "SELECT id, title FROM products";
$product_result = $db->query($product_query);
$products = [];
if ($product_result) {
    while ($row = $product_result->fetch_assoc()) {
        $products[$row['id']] = $row['title'];
    }
}

?>
<!DOCTYPE html><html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - TOTSY.pk</title>
    <link rel="icon" href="../logo/totsy_logo.jpg" type="image/x-icon">
    <?php include '../includes/header-links.php'; ?>
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
        h2 {
            font-weight: 300;
            color: #333;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-5">
            <span class="gradient-text">TOTSY</span><span style="font-weight: 300;">.pk</span>
            <br>
            <small class="text-muted" style="font-weight: 300;">Admin Panel</small>
        </h1>
        
        <div class="text-center mb-4">
            <a href="data.php" class="btn btn-sm btn-primary btn-lg">View Data</a>
            <a href="logout.php" class="btn btn-sm btn-primary btn-lg">Logout</a>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Add Product</h2>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" id="image" name="image" required>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" class="form-control" id="price" name="price" required>
                            </div>
                            <div class="mb-3">
                                <label for="times_sold" class="form-label">Times Sold</label>
                                <input type="number" class="form-control" id="times_sold" name="times_sold" required>
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label">Type</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="">Select a type</option>
                                    <option value="Velvet & Vibes">Velvet & Vibes</option>
                                    <option value="Totsy">Totsy</option>
                                </select>
                            </div>
                            <div class="d-grid">
                                <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
            <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Add Offer</h2>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="mb-3">
                                <label for="offer_product_name" class="form-label">Product Name</label>
                                <select class="form-select" id="offer_product_name" name="product_name" required>
                                    <option value="">Select a product</option>
                                    <?php foreach ($products as $id => $title): ?>
                                        <option value="<?php echo htmlspecialchars($title); ?>"><?php echo htmlspecialchars($title); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="discount" class="form-label">Discount (%)</label>
                                <input type="number" class="form-control" id="discount" name="discount" required>
                            </div>
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                            </div>
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" name="add_offer" class="btn btn-primary">Add Offer</button>
                            </div>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
            <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Add Availability</h2>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="In stock">In stock</option>
                                    <option value="Out of stock">Out of stock</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="product_name" class="form-label">Product Name</label>
                                <select class="form-select" id="product_name" name="product_name" required>
                                    <option value="">Select a product</option>
                                    <?php foreach ($products as $id => $title): ?>
                                        <option value="<?php echo htmlspecialchars($title); ?>"><?php echo htmlspecialchars($title); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="d-grid">
                                <button type="submit" name="add_availability" class="btn btn-primary">Add Availability</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Change Password</h2>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <button class="btn btn-outline-primary" type="button" id="togglePassword">
                                        <i class="bx bx-show"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <script>
                document.getElementById('togglePassword').addEventListener('click', function () {
                    const password = document.getElementById('password');
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    const icon = this.querySelector('i');
                    icon.classList.toggle('bx-hide');
                    icon.classList.toggle('bx-show');
                });
            </script>
        </div>
    </div>

    <?php include '../includes/footer_links.php'; ?>

    <!-- Toast container -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Notification</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <?php echo $toast_message; ?>
            </div>
        </div>
    </div>

    <script>
</body>
</html>
