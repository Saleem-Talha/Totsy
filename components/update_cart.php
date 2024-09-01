<?php
include_once '../includes/db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$cart_id = isset($_POST['cart_id']) ? intval($_POST['cart_id']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
$total = isset($_POST['total']) ? floatval($_POST['total']) : 0;

$query = "UPDATE cart SET quantity = ?, total = ? WHERE id = ? AND user_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("idii", $quantity, $total, $cart_id, $user_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Cart updated successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update cart.']);
}

$stmt->close();
$db->close();
?>
