<?php include 'includes/db_connect.php'; ?>

<!-- Product Cards Section -->
<section id="products" class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">
            <span style="font-weight: 300;">Our </span>
            <span style="font-weight: 500; background: linear-gradient(90deg, #4ab6f4, #ff69b4); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Products</span>
        </h2>
        <div class="row g-4">
            <?php
            $sql = "SELECT * FROM products ORDER BY times_sold DESC LIMIT 3";
            $result = $db->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    // Update the image path to point to the correct directory
                    $imagePath = 'admin/uploads/' . $row['image'];
                    // Check if the image file exists
                    if (!file_exists($imagePath)) {
                        $imagePath = 'images/placeholder.jpg'; // Use a placeholder image if the product image doesn't exist
                    }
            ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden">
                    <img src="<?php echo htmlspecialchars($imagePath); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['title']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="h5 mb-0">Rs<?php echo number_format($row['price'], 2); ?></span>
                            <span class="text-muted">Sold: <?php echo $row['times_sold']; ?></span>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="#" class="btn btn-sm" style="background: linear-gradient(90deg, #4ab6f4, #ff69b4); color: white; border: none; transition: all 0.3s ease;">Add to Cart</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
                echo "<p class='text-center'>No products found.</p>";
            }
            $db->close();
            ?>
        </div>
    </div>
</section>

<style>
    /* ... (your existing styles) ... */

    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    .card-img-top {
        height: 200px;
        object-fit: cover;
    }

    .btn:hover {
        background: linear-gradient(90deg, #ff69b4, #4ab6f4) !important;
        color: white !important;
    }
</style>