<?php
// Include your database connection file
include 'includes/db_connect.php';

// Function to get random products
function getRandomProducts($db, $limit = 6) {
    $query = "SELECT p.*, a.status 
              FROM products p 
              LEFT JOIN availability a ON p.id = a.product_id
              ORDER BY RAND()
              LIMIT ?";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result;
}

// Fetch 3 random products
$result = getRandomProducts($db);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="css/styles.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        .card {
            border: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            height: 350px; /* Fixed height for all cards */
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
            height: 150px; /* Fixed image height */
            width: 100%;
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
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
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
    <div class="container mt-5 mb-5" id="products">
        <h1 class="text-center mb-5 super-color" data-aos="fade-up">People Also Buy</h1>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php 
            $delay = 0;
            while ($product = $result->fetch_assoc()): 
            ?>
                <div class="col" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                    <a href="/totsy/pages/product_details.php?id=<?php echo $product['id']; ?>" class="card-link">
                        <div class="card">
                            <?php
                            $image_path = "../admin/uploads/" . basename($product['image']);
                            if (file_exists($image_path) && is_readable($image_path)) {
                                echo "<img src='" . htmlspecialchars($image_path) . "' class='card-img-top' alt='" . htmlspecialchars($product['title']) . "'>";
                            } else {
                                echo "<img src='https://via.placeholder.com/300x150' class='card-img-top' alt='Placeholder for " . htmlspecialchars($product['title']) . "'>";
                            }
                            ?>
                            <div class="card-body">
                                <h5 class="card-title super-color"><?php echo htmlspecialchars($product['title']); ?></h5>
                                <p class="price"><?php echo number_format($product['price'], 0); ?> PKR</p>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <p class="times-sold mb-0">
                                        <i class='bx bx-purchase-tag'></i>
                                        Sold <?php echo htmlspecialchars($product['times_sold']); ?> times
                                    </p>
                                    <i class='bx bx-cart cart-icon'></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php 
            $delay += 100; // Increment delay for each product
            endwhile; 
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>
</body>
</html>