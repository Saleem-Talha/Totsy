<?php
include_once '../components/user_auth_check.php'; // Ensure user authentication
include_once '../includes/db_connect.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Fetch user email from the database
$user_query = "SELECT email FROM user WHERE id = ?";
$user_stmt = $db->prepare($user_query);
if ($user_stmt === false) {
    die("Error preparing statement: " . $db->error);
}
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();
$user_email = htmlspecialchars($user['email']);
$user_stmt->close();

// Fetch products in the user's cart
$query = "
    SELECT p.title, p.image, c.quantity, c.price_at_addition, c.total 
    FROM cart c
    JOIN products p ON c.product_id = p.id
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
$grand_total = 0;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $grand_total += $row['total'];
    }
}

$stmt->close();

// Function to save order to database and upload image
function saveOrderAndUploadImage($db, $user_id, $image_data) {
    // Create directory if it doesn't exist
    $upload_dir = '../order_slips/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Generate unique filename
    $image_name = 'order_slip_' . $user_id . '_' . time() . '.png';
    $image_path = $upload_dir . $image_name;

    // Save image to file
    file_put_contents($image_path, base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image_data)));

    // Save order to database
    $query = "INSERT INTO orders (user_id, order_image) VALUES (?, ?)";
    $stmt = $db->prepare($query);
    if ($stmt === false) {
        die("Error preparing statement: " . $db->error);
    }
    $stmt->bind_param("is", $user_id, $image_name);
    $result = $stmt->execute();
    $stmt->close();

    return $result;
}

// Handle AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['image_data'])) {
    $result = saveOrderAndUploadImage($db, $user_id, $_POST['image_data']);
    echo json_encode(['success' => $result]);
    exit;
}

$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Slip - TOTSY.pk</title>
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
            object-fit: cover;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .no-print {
            display: none;
        }
    </style>
</head>
<body>
    <?php include '../includes/other_nav.php'; ?>
    <div class="container">
        <h1 class="mb-4">
            <span class="gradient-text">Get Slip</span>
        </h1>

        <div class="card">
            <div class="card-header text-center">
                <img src="../logo/totsy_logo.jpg" alt="" style="width: 50px; height: 50px; border-radius: 50%;">    
            </div>
            <div class="card-body">
                <p><strong>Email:</strong> <?php echo $user_email; ?></p>
                <?php if (!empty($cart_items)): ?>
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
                                    <tr>
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
                                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                        <td><?php echo htmlspecialchars($item['total']); ?></td>
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
                <?php else: ?>
                    <div class="alert alert-info" role="alert">
                        Your cart is empty.
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="text-center mt-4">
            <button class="btn btn-login-signup" id="confirmOrderBtn">
                <i class='bx bx-download'></i>
                <span>Confirm Order & Download Slip</span>
            </button>
        </div>
    </div>
    <?php include '../includes/other_footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
    <script>
    document.getElementById('confirmOrderBtn').addEventListener('click', function() {
        html2canvas(document.querySelector(".card")).then(canvas => {
            // Get the image data
            const imageData = canvas.toDataURL("image/png");

            // Send image data to server
            fetch('<?php echo $_SERVER['PHP_SELF']; ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'image_data=' + encodeURIComponent(imageData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Order confirmed and slip saved successfully!');
                    // Download the image
                    const link = document.createElement('a');
                    link.href = imageData;
                    link.download = 'totsy_order_slip.png';
                    link.click();
                } else {
                    alert('There was an error saving your order. Please try again.');
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                alert('There was an error processing your order. Please try again.');
            });
        });
    });
    </script>
</body>
</html>