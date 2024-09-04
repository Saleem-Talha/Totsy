<?php
include_once '../components/user_auth_check.php'; // Ensure user authentication
include_once '../includes/db_connect.php';
session_start();

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Fetch products in the user's cart along with any active offers
$query = "
    SELECT p.id, p.title, p.image, c.id as cart_id, c.quantity, c.price_at_addition, c.total, 
           IF(o.discount IS NOT NULL AND o.start_date <= CURDATE() AND o.end_date >= CURDATE(), 
              p.price * (1 - o.discount / 100), p.price) AS effective_price
    FROM cart c
    JOIN products p ON c.product_id = p.id
    LEFT JOIN offers o ON p.id = o.product_id
    WHERE c.user_id = ?
";
$stmt = $db->prepare($query);
if ($stmt === false) {
    die("Error preparing statement: " . $db->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$grand_total = 0; // Initialize grand total
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $grand_total += $row['total']; // Add each item's total to the grand total
    }
}

$stmt->close();
$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - TOTSY.pk</title>
    <link rel="icon" href="../logo/totsy_logo.jpg" type="image/x-icon">
    <?php include '../includes/header-links.php'; ?>
    <style>
         body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: 300;
        }
        .gradient-text {
            background: linear-gradient(90deg, #ff69b4, #4ab6f4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        input:active{
            color: black !important;
        }
        .card {
           
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background: linear-gradient(90deg, #ff69b4, #4ab6f4);
            color: white;
            font-weight: bold;
        }
        .table {
            margin-bottom: 0;
        }
        .table th {
            background-color: #f8f9fa;
            border-top: none;
        }
        .table td {
            vertical-align: middle;
        }
        .product-image {
            max-width: 80px;
            max-height: 80px;
            border-radius: 5px;
        }
       
        .quantity-input {
            width: 80px;
            text-align: center;
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 5px;
            font-size: 16px;
          
    color: black !important; /* Force text color to be black */
    background-color: white; /* Ensure background color is white */


        }
        .quantity-input:focus,
        .quantity-input:active,
        .quantity-input:hover {
            color: black !important; /* Force text color to be black */
            background-color: #f1f1f1; /* Optional: Change background color on focus */
            border-color: #007bff; /* Optional: Change border color on focus */
        }

        .btn-gradient {
            background: linear-gradient(90deg, #ff69b4, #4ab6f4);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            opacity: 0.9;
            box-shadow: 0 0 10px rgba(74, 182, 244, 0.5), 0 0 10px rgba(255, 105, 180, 0.5);
        }
    </style>
    <script>
        function updateQuantity(cartId, element) {
            const quantity = element.value;
            const row = element.closest('tr');
            const effectivePrice = parseFloat(row.getAttribute('data-effective-price'));
            const totalElement = row.querySelector('.total');
            const newTotal = effectivePrice * quantity;
            totalElement.textContent = newTotal.toFixed(2);

            // Send updated quantity to server
            fetch('../components/update_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `cart_id=${cartId}&quantity=${quantity}&total=${newTotal}`
            }).then(response => response.json()).then(data => {
                if (data.status === 'error') {
                    alert(data.message);
                }
            }).catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</head>
<body>
    <?php include '../includes/other_nav.php'; ?>
    <div class="container">
        <h1 class="mb-4">
            <span class="gradient-text">Your Cart</span>
        </h1>

        <?php if (!empty($cart_items)): ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0" style="color: white;">Cart Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Image</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_items as $item): ?>
                                    <tr data-effective-price="<?php echo htmlspecialchars($item['effective_price']); ?>">
                                        <td><?php echo htmlspecialchars($item['title']); ?></td>
                                        <td>
                                            <?php
                                            $image_path = "../admin/uploads/" . basename($item['image']);
                                            if (file_exists($image_path) && is_readable($image_path)): ?>
                                                <img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="product-image">
                                            <?php else: ?>
                                                <p>Image not found</p>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control quantity-input"  value="<?php echo htmlspecialchars($item['quantity']); ?>" min="1" onchange="updateQuantity(<?php echo $item['cart_id']; ?>, this)">
                                        </td>
                                        <td class="total"><?php echo htmlspecialchars($item['total']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Grand Total:</strong></td>
                                    <td><strong><?php echo number_format($grand_total); ?> Rs</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                Your cart is empty.
            </div>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="../index.php" class="btn btn-gradient" style=" color: white;">Continue Shopping</a>
            <a href="get_slip.php" class="btn btn-gradient" style=" color: white; margin-left: 10px;">Get Slip</a>
        </div>
    </div>
    <?php include '../includes/other_footer.php'; ?>
</body>
</html>
