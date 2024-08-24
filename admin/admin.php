<?php
// Include your database connection file
include '../includes/db_connect.php';

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

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_product'])) {
        $title = $db->real_escape_string($_POST['title']);
        $description = $db->real_escape_string($_POST['description']);
        $price = intval($_POST['price']);
        $created_at = date('Y-m-d');
        $times_sold = intval($_POST['times_sold']); // Add times_sold
        
        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $image = uploadImage($_FILES['image']);
            if ($image === false) {
                echo "Failed to upload image.";
                // Handle the error appropriately
            }
        } else {
            echo "No file was uploaded or an error occurred during upload.";
            // Handle the error appropriately
        }
        
        $query = "INSERT INTO products (title, description, image, created_at, price, times_sold) 
                  VALUES ('$title', '$description', '$image', '$created_at', $price, $times_sold)";
        if ($db->query($query) === TRUE) {
            echo "Product added successfully";
        } else {
            echo "Error: " . $query . "<br>" . $db->error;
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
                echo "Availability added successfully";
            } else {
                echo "Error: " . $query . "<br>" . $db->error;
            }
        } else {
            echo "Product not found";
        }
    }
    elseif (isset($_POST['add_review'])) {
        $username = $db->real_escape_string($_POST['username']);
        $description = $db->real_escape_string($_POST['description']);
        $rating = intval($_POST['rating']);
        
        $query = "INSERT INTO reviews (username, description, rating) VALUES ('$username', '$description', $rating)";
        if ($db->query($query) === TRUE) {
            echo "Review added successfully";
        } else {
            echo "Error: " . $query . "<br>" . $db->error;
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
                echo "Offer added successfully";
            } else {
                echo "Error: " . $query . "<br>" . $db->error;
            }
        } else {
            echo "Product not found";
        }
    }
    elseif (isset($_POST['add_to_cart'])) {
        $product_id = intval($_POST['product_id']);
        $quantity = intval($_POST['quantity']);
        $price_at_addition = intval($_POST['price_at_addition']);
        $total = $quantity * $price_at_addition;
        
        $query = "INSERT INTO cart (product_id, quantity, price_at_addition, total) 
                  VALUES ($product_id, $quantity, $price_at_addition, $total)";
        if ($db->query($query) === TRUE) {
            echo "Item added to cart successfully";
        } else {
            echo "Error: " . $query . "<br>" . $db->error;
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
                echo "Password changed successfully";
            } else {
                echo "Error inserting new password: " . $db->error;
            }
        } else {
            echo "Error deleting old password: " . $db->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Admin Panel</h1>
        <a href="data.php" class="btn btn-primary mb-4">View Data</a>

        <!-- Products Form -->
        <h2>Add Product</h2>
        <form action="" method="post" enctype="multipart/form-data" class="mb-4">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
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
            <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
        </form>

        <!-- Availability Form -->
        <h2>Add Availability</h2>
        <form action="" method="post" class="mb-4">
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="In stock">In stock</option>
                    <option value="Out of stock">Out of stock</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="product_name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="product_name" name="product_name" required>
            </div>
            <button type="submit" name="add_availability" class="btn btn-primary">Add Availability</button>
        </form>

        <!-- Reviews Form -->
        <h2>Add Review</h2>
        <form action="" method="post" class="mb-4">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="review_description" class="form-label">Description</label>
                <textarea class="form-control" id="review_description" name="description" required></textarea>
            </div>
            <div class="mb-3">
                <label for="rating" class="form-label">Rating</label>
                <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" required>
            </div>
            <button type="submit" name="add_review" class="btn btn-primary">Add Review</button>
        </form>

        <!-- Offers Form -->
        <h2>Add Offer</h2>
        <form action="" method="post" class="mb-4">
        <div class="mb-3">
                <label for="offer_product_name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="offer_product_name" name="product_name" required>
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
            <button type="submit" name="add_offer" class="btn btn-primary">Add Offer</button>
        </form>

        <!-- Cart Form -->
        <h2>Add to Cart</h2>
        <form action="" method="post" class="mb-4">
            <div class="mb-3">
                <label for="cart_product_id" class="form-label">Product ID</label>
                <input type="number" class="form-control" id="cart_product_id" name="product_id" required>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>
            <div class="mb-3">
                <label for="price_at_addition" class="form-label">Price at Addition</label>
                <input type="number" class="form-control" id="price_at_addition" name="price_at_addition" required>
            </div>
            <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
        </form>

        <!-- Password Form -->
        <h2>Change Password</h2>
        <form action="" method="post" class="mb-4">
            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
        </form>
    </div>

    <?php include '../includes/footer_links.php'; ?>
</body>
</html>