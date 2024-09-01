<?php
// Include your database connection file
include '../includes/db_connect.php';

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
    <title>Totsy</title>
    <link rel="icon" href="../logo/totsy_logo.jpg" type="image/x-icon">
  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="css/styles.css" rel="stylesheet">
    <style>
      
        .product-image {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            cursor: zoom-in;
            position: relative;
        }
        .product-image:hover::after {
            content: '\eb8b';
            font-family: 'boxicons';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 2rem;
            color: #fff;
            background-color: rgba(0,0,0,0.5);
            padding: 10px;
            border-radius: 50%;
        }
        .product-details {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
        }
        .btn-add-to-cart, .btn-copy-url {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            color: #495057;
            padding: 5px 10px;
            font-size: 0.9rem;
            border-radius: 5px;
            transition: all 0.3s ease;
            margin-top: 20px;
            margin-bottom: 10px;
            position: relative;
            overflow: hidden;
        }
        .btn-add-to-cart:before, .btn-copy-url:before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(0,0,0,0.1), transparent);
            transition: all 0.6s;
        }
        .btn-add-to-cart:hover:before, .btn-copy-url:hover:before {
            left: 100%;
        }
        .btn-add-to-cart:hover, .btn-copy-url:hover {
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
        .thumbnail-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .thumbnail {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .thumbnail:hover, .thumbnail.active {
            border: 2px solid #4ab6f4;
        }
        .card {
            border: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            height: 100%;
        }
        .card:hover {
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
            transform: translateY(-5px);
        }
        .card-img-top {
            border-radius: 10px 10px 0 0;
            object-fit: cover;
            height: 200px;
        }
        .card-body {
            padding: 1rem;
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
        .card-link {
            text-decoration: none;
            color: inherit;
        }
        .card-link:hover {
            text-decoration: none;
            color: inherit;
        }
        .modal-dialog {
            max-width: 100%;
            margin: 0;
            height: 100vh;
        }
        .modal-content {
            height: 100vh;
            border: none;
            border-radius: 0;
        }
        .modal-body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            background-color: white;
        }
        .modal-body img {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
        }
        @media (max-width: 767px) {
            .product-details {
                margin-top: 20px;
            }
            .thumbnail-container {
                flex-direction: row;
                justify-content: center;
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<?php include '../includes/other_nav.php'; ?>
<body class="bootstrap-override">
   
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-1 col-3">
                <div class="thumbnail-container">
                    <?php
                    $image_path = "../admin/uploads/" . basename($product['image']);
                    if (file_exists($image_path) && is_readable($image_path)) {
                        echo "<img src='" . htmlspecialchars($image_path) . "' class='thumbnail active' alt='Thumbnail 1' onclick='changeMainImage(this)'>";
                        // Add more thumbnails here if you have them
                    } else {
                        echo "<img src='https://via.placeholder.com/60x60' class='thumbnail active' alt='Placeholder thumbnail' onclick='changeMainImage(this)'>";
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-5 col-9">
                <?php
                if (file_exists($image_path) && is_readable($image_path)) {
                    echo "<img src='" . htmlspecialchars($image_path) . "' class='product-image' id='mainImage' alt='" . htmlspecialchars($product['title']) . "' data-bs-toggle='modal' data-bs-target='#imageModal'>";
                } else {
                    echo "<img src='https://via.placeholder.com/500x500' class='product-image' id='mainImage' alt='Placeholder for " . htmlspecialchars($product['title']) . "' data-bs-toggle='modal' data-bs-target='#imageModal'>";
                }
                ?>
            </div>
            <div class="col-md-6">
                <div class="product-details">
                    <h1 class="super-color"><?php echo htmlspecialchars($product['title']); ?></h1>
                    <p class="price h3 mt-3"><?php echo number_format($product['price'], 0); ?> PKR</p>
                    <p class="times-sold mt-2">
                        <i class='bx bx-purchase-tag'></i>
                        Sold <?php echo htmlspecialchars($product['times_sold']); ?> times
                    </p>
                    <button class="btn btn-add-to-cart">
                        <i class='bx bx-cart'></i> Add to Cart
                    </button>
                    <button class="btn btn-copy-url" onclick="copyProductUrl()">
                        <i class='bx bx-link'></i> Copy URL
                    </button>
                   
                   
                    <?php include '../components/product_features.php'; ?>
                    <?php include '../components/visit_store.php'; ?>
                    
                </div>
            </div>
        </div>
        <?php include '../components/product_details_description.php'; ?>
        <?php include '../components/recommended_products.php'; ?>
    </div>

    

    <?php include '../includes/other_footer.php'; ?>

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

        document.querySelector('.btn-add-to-cart').addEventListener('click', function() {
    const product_id = <?php echo json_encode($product_id); ?>;
    const quantity = 1; // Default quantity to add to the cart

    fetch('../components/add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            product_id: product_id,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Product added to cart successfully!');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});


        
    </script>
</body>
</html>