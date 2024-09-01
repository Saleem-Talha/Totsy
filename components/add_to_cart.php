<?php
session_start();
include '../includes/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to add items to the cart.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
$date = date('Y-m-d H:i:s');

// Fetch the current price of the product
$query = "SELECT price FROM products WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo json_encode(['status' => 'error', 'message' => 'Product not found.']);
    exit;
}

// Check if the product is already in the cart for this user
$check_cart_query = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
$check_cart_stmt = $db->prepare($check_cart_query);
$check_cart_stmt->bind_param("ii", $user_id, $product_id);
$check_cart_stmt->execute();
$cart_result = $check_cart_stmt->get_result();
$existing_cart_item = $cart_result->fetch_assoc();

if ($existing_cart_item) {
    // If the product is already in the cart, update the quantity
    $update_cart_query = "UPDATE cart SET quantity = quantity + ?, total = total + (price_at_addition * ?) WHERE user_id = ? AND product_id = ?";
    $update_cart_stmt = $db->prepare($update_cart_query);
    $update_cart_stmt->bind_param("iiii", $quantity, $quantity, $user_id, $product_id);
    $update_cart_stmt->execute();
} else {
    // Otherwise, add the product to the cart
    $price_at_addition = $product['price'];
    $total = $price_at_addition * $quantity;
    $add_to_cart_query = "INSERT INTO cart (user_id, product_id, quantity, price_at_addition, total, date) VALUES (?, ?, ?, ?, ?, ?)";
    $add_to_cart_stmt = $db->prepare($add_to_cart_query);
    $add_to_cart_stmt->bind_param("iiidss", $user_id, $product_id, $quantity, $price_at_addition, $total, $date);
    $add_to_cart_stmt->execute();
}

echo json_encode(['status' => 'success', 'message' => 'Product added to cart successfully.']);
?>
