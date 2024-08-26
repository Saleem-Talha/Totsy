<?php
// Include your database connection file
include 'includes/db_connect.php';

// Get the product ID from the URL parameter
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the specific product details
$query = "SELECT p.*, a.status 
          FROM products p 
          LEFT JOIN availability a ON p.id = a.product_id
          WHERE p.id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// Fetch recommended products (e.g., random products excluding the current one)
$recommended_query = "SELECT p.*, a.status 
                      FROM products p 
                      LEFT JOIN availability a ON p.id = a.product_id
                      WHERE p.id != ?
                      ORDER BY RAND()
                      LIMIT 3";
$recommended_stmt = $db->prepare($recommended_query);
$recommended_stmt->bind_param("i", $product_id);
$recommended_stmt->execute();
$recommended_result = $recommended_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['title']); ?> - Product Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="css/styles.css" rel="stylesheet">
    <style>
        body {
            padding-top: 76px; /* Adjust this value based on your navbar height */
        }
        .navbar {
            background-color: #fff !important;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .navbar-brand, .navbar-nav .nav-link {
            color: #000 !important;
        }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%280, 0, 0, 0.5%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        .product-image {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }
        .product-details {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
        }
        .recommended-products {
            margin-top: 40px;
        }
        .recommended-product-card {
            height: 100%;
        }
        .btn-add-to-cart {
            background: linear-gradient(90deg, #4ab6f4, #ff69b4);
            border: none;
            color: white;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            margin-top: 20px;
            margin-bottom: 10px;
            position: relative;
            overflow: hidden;
        }
        .btn-add-to-cart:before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: all 0.6s;
        }
        .btn-add-to-cart:hover:before {
            left: 100%;
        }
        .btn-add-to-cart:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        .btn-copy-url {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            color: #495057;
            padding: 5px 10px;
            font-size: 0.9rem;
            border-radius: 5px;
            transition: all 0.3s ease;
            margin-left: 10px;
            position: relative;
            overflow: hidden;
        }
        .btn-copy-url:before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(0,0,0,0.1), transparent);
            transition: all 0.6s;
        }
        .btn-copy-url:hover:before {
            left: 100%;
        }
        .btn-copy-url:hover {
            background-color: #e9ecef;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .product-features {
            margin-top: 30px;
        }
        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .feature-icon {
            font-size: 1.5rem;
            margin-right: 10px;
            color: #4ab6f4;
        }
        .card {
            border: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            height: 350px;
            display: flex;
            flex-direction: column;
        }
        .card:hover {
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
            transform: translateY(-5px);
        }
        .card-img-top {
            border-radius: 10px 10px 0 0;
            object-fit: cover;
            height: 150px;
            transition: transform 0.3s ease;
        }
        .card:hover .card-img-top {
            transform: scale(1.05);
        }
        .card-body {
            padding: 1rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .card-title {
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
        }
        .price {
            font-size: 0.9rem;
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .card-footer {
            background-color: transparent;
            border-top: 1px solid rgba(0,0,0,0.1);
            padding: 0.5rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }
        .cart-icon {
            font-size: 1.2rem;
            background: linear-gradient(90deg, #4ab6f4, #ff69b4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            color: transparent;
        }
        .times-sold {
            font-size: 0.8rem;
            color: var(--text-color);
            margin: 0;
            display: flex;
            align-items: center;
        }
        .times-sold i {
            margin-right: 0.3rem;
            color: var(--primary-color);
        }
        .card-link {
            text-decoration: none;
            color: inherit;
        }
        .card-link:hover {
            text-decoration: none;
            color: inherit;
        }
    </style>
</head>
<body class="bootstrap-override">
    <?php include 'includes/navbar.php'; ?>
    <div class="container mt-5 mb-3">
        <h1 class="text-center mb-5 super-color">Product Details</h1>
        <div class="row">
            <div class="col-md-6">
                <?php
                $image_path = "admin/uploads/" . basename($product['image']);
                if (file_exists($image_path) && is_readable($image_path)) {
                    echo "<img src='" . htmlspecialchars($image_path) . "' class='product-image' alt='" . htmlspecialchars($product['title']) . "'>";
                } else {
                    echo "<img src='https://via.placeholder.com/500x500' class='product-image' alt='Placeholder for " . htmlspecialchars($product['title']) . "'>";
                }
                ?>
            </div>
            <div class="col-md-6">
                <div class="product-details">
                    <h1 class="super-color"><?php echo htmlspecialchars($product['title']); ?></h1>
                    <p class="price h3 mt-3"><?php echo number_format($product['price'], 0); ?> PKR</p>
                    <p class="mt-3"><?php echo htmlspecialchars($product['description']); ?></p>
                    <p class="times-sold">
                        <i class='bx bx-purchase-tag'></i>
                        Sold <?php echo htmlspecialchars($product['times_sold']); ?> times
                    </p>
                    <button class="btn btn-add-to-cart text-white">
                        <i class='bx bx-cart'></i> Add to Cart
                    </button>
                    <button class="btn btn-copy-url" onclick="copyProductUrl()">
                        <i class='bx bx-link'></i> Copy URL
                    </button>
                </div>
                <div class="product-features">
                    <h3 class="super-color mb-3">Product Features</h3>
                    <div class="feature-item">
                        <i class='bx bx-check-circle feature-icon'></i>
                        <span>High-quality material</span>
                    </div>
                    <div class="feature-item">
                        <i class='bx bx-check-circle feature-icon'></i>
                        <span>Durable and long-lasting</span>
                    </div>
                    <div class="feature-item">
                        <i class='bx bx-check-circle feature-icon'></i>
                        <span>Easy to clean and maintain</span>
                    </div>
                    <div class="feature-item">
                        <i class='bx bx-check-circle feature-icon'></i>
                        <span>Suitable for all ages</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="recommended-products">
            <h2 class="super-color mb-4">Recommended Products</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php while ($recommended_product = $recommended_result->fetch_assoc()): ?>
                    <div class="col">
                        <a href="product_details.php?id=<?php echo $recommended_product['id']; ?>" class="card-link">
                            <div class="card">
                                <?php
                                $image_path = "admin/uploads/" . basename($recommended_product['image']);
                                if (file_exists($image_path) && is_readable($image_path)) {
                                    echo "<img src='" . htmlspecialchars($image_path) . "' class='card-img-top' alt='" . htmlspecialchars($recommended_product['title']) . "'>";
                                } else {
                                    echo "<img src='https://via.placeholder.com/300x150' class='card-img-top' alt='Placeholder for " . htmlspecialchars($recommended_product['title']) . "'>";
                                }
                                ?>
                                <div class="card-body">
                                    <h5 class="card-title super-color"><?php echo htmlspecialchars($recommended_product['title']); ?></h5>
                                    <p class="price"><?php echo number_format($recommended_product['price'], 0); ?> PKR</p>
                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <p class="times-sold mb-0">
                                            <i class='bx bx-purchase-tag'></i>
                                            Sold <?php echo htmlspecialchars($recommended_product['times_sold']); ?> times
                                        </p>
                                        <i class='bx bx-cart cart-icon'></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function copyProductUrl() {
            var dummy = document.createElement('input'),
            text = window.location.href;
            document.body.appendChild(dummy);
            dummy.value = text;
            dummy.select();
            document.execCommand('copy');
            document.body.removeChild(dummy);
            alert('Product URL copied to clipboard!');
        }
    </script>
</body>
</html>