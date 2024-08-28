<div class="recommended-products mt-5 mb-4">
            <h2 class="super-color mb-4">Recommended Products</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php while ($recommended_product = $recommended_result->fetch_assoc()): ?>
                    <div class="col">
                        <a href="product_details.php?id=<?php echo $recommended_product['id']; ?>" class="card-link">
                            <div class="card">
                                <?php
                                $image_path = "../admin/uploads/" . basename($recommended_product['image']);
                                if (file_exists($image_path) && is_readable($image_path)) {
                                    echo "<img src='" . htmlspecialchars($image_path) . "' class='card-img-top' alt='" . htmlspecialchars($recommended_product['title']) . "'>";
                                } else {
                                    echo "<img src='https://via.placeholder.com/300x200' class='card-img-top' alt='Placeholder for " . htmlspecialchars($recommended_product['title']) . "'>";
                                }
                                ?>
                                <div class="card-body">
                                    <h5 class="card-title super-color"><?php echo htmlspecialchars($recommended_product['title']); ?></h5>
                                    <p class="price"><?php echo number_format($recommended_product['price'], 0); ?> PKR</p>
                                    <p class="times-sold mb-0">
                                        <i class='bx bx-purchase-tag'></i>
                                        Sold <?php echo htmlspecialchars($recommended_product['times_sold']); ?> times
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>