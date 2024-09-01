<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your database connection file
include '../includes/db_connect.php';

// Get the search query
$search_query = isset($_GET['query']) ? $_GET['query'] : '';

// Initialize filtering variables
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';
$filter_type = isset($_GET['type']) ? $_GET['type'] : '';

// Pagination settings
$products_per_page = 12;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $products_per_page;

// Construct the base query
$query = "SELECT p.*, a.status, o.discount, o.start_date, o.end_date 
          FROM products p 
          LEFT JOIN availability a ON p.id = a.product_id 
          LEFT JOIN offers o ON p.id = o.product_id 
          WHERE p.title LIKE ?";

// Add type filter if set
if ($filter_type == 'velvet_vibes') {
    $query .= " AND p.type = 'Velvet & Vibes'";
} elseif ($filter_type == 'totsy') {
    $query .= " AND p.type = 'Totsy'";
}

// Add sorting
switch ($sort) {
    case 'price_asc':
        $query .= " ORDER BY CASE WHEN o.discount IS NOT NULL THEN p.price * (1 - o.discount/100) ELSE p.price END ASC";
        break;
    case 'price_desc':
        $query .= " ORDER BY CASE WHEN o.discount IS NOT NULL THEN p.price * (1 - o.discount/100) ELSE p.price END DESC";
        break;
    case 'offers':
        $query .= " AND o.discount IS NOT NULL AND CURDATE() BETWEEN o.start_date AND o.end_date ORDER BY o.discount DESC";
        break;
    case 'times_sold_asc':
        $query .= " ORDER BY p.times_sold ASC";
        break;
    case 'times_sold_desc':
        $query .= " ORDER BY p.times_sold DESC";
        break;
    case 'most_recent':
        $query .= " ORDER BY p.created_at DESC";
        break;
    default:
        $query .= " ORDER BY p.created_at DESC";
}

// Add LIMIT and OFFSET for pagination
$query .= " LIMIT ? OFFSET ?";

// Prepare and execute the statement
$stmt = $db->prepare($query);
$search_param = "%{$search_query}%";
$stmt->bind_param("sii", $search_param, $products_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Count total products for pagination
$count_query = "SELECT COUNT(*) as total FROM products WHERE title LIKE ?";
if ($filter_type == 'velvet_vibes') {
    $count_query .= " AND type = 'Velvet & Vibes'";
} elseif ($filter_type == 'totsy') {
    $count_query .= " AND type = 'Totsy'";
}
$count_stmt = $db->prepare($count_query);
$count_stmt->bind_param("s", $search_param);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$count_row = $count_result->fetch_assoc();
$total_products = $count_row['total'];
$total_pages = ceil($total_products / $products_per_page);

// Include the HTML header
include '../includes/header.php';
?>

<?php include '../includes/other_nav.php'; ?>

<style>
    :root {
        --gradient-color: linear-gradient(90deg, #4ab6f4, #ff69b4);
    }
    .filter-card {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .filter-title {
        font-weight: bold;
        margin-bottom: 15px;
        background: var(--gradient-color);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .filter-option {
        margin-bottom: 10px;
    }
    .filter-option a {
        color: var(--text-color);
        transition: all 0.3s ease;
        display: block;
        padding: 5px 10px;
        border-radius: 5px;
    }
    .filter-option a:hover {
        background: var(--gradient-color);
        color: #fff;
        transform: translateX(5px);
    }
    .filter-option.active a {
        background: var(--gradient-color);
        color: #fff;
    }
    .card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }
    .card-img-top {
        height: 200px;
        object-fit: cover;
    }
    .card-body {
        padding: 1rem;
    }
    .card-title {
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 0.5rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .price {
        font-weight: 600;
        background: var(--gradient-color);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .times-sold {
        font-size: 0.8rem;
        color: #6c757d;
    }
    .cart-icon {
        background: var(--gradient-color);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 1.5rem;
    }
    .discount-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: var(--gradient-color);
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-weight: bold;
    }
    .original-price {
        text-decoration: line-through;
        color: #6c757d;
        font-size: 0.9rem;
    }
    .pagination {
        justify-content: center;
        margin-top: 2rem;
    }
    .page-item.active .page-link {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    .page-link {
        color: var(--primary-color);
    }
</style>

<div class="container mt-5">
    <div class="row">
        <!-- Enhanced Filtration options -->
        <div class="col-md-3">
            <div class="filter-card">
                <h5 class="filter-title">Sort By</h5>
                <div class="filter-option <?php echo $sort == 'default' ? 'active' : ''; ?>">
                    <a href="?query=<?php echo urlencode($search_query); ?>&sort=default" class="text-decoration-none">
                        <i class='bx bx-sort-alt-2'></i> Default
                    </a>
                </div>
                <div class="filter-option <?php echo $sort == 'price_asc' ? 'active' : ''; ?>">
                    <a href="?query=<?php echo urlencode($search_query); ?>&sort=price_asc" class="text-decoration-none">
                        <i class='bx bx-sort-up'></i> Price: Low to High
                    </a>
                </div>
                <div class="filter-option <?php echo $sort == 'price_desc' ? 'active' : ''; ?>">
                    <a href="?query=<?php echo urlencode($search_query); ?>&sort=price_desc" class="text-decoration-none">
                        <i class='bx bx-sort-down'></i> Price: High to Low
                    </a>
                </div>
                <div class="filter-option <?php echo $sort == 'times_sold_asc' ? 'active' : ''; ?>">
                    <a href="?query=<?php echo urlencode($search_query); ?>&sort=times_sold_asc" class="text-decoration-none">
                        <i class='bx bx-sort-up'></i> Times Sold: Low to High
                    </a>
                </div>
                <div class="filter-option <?php echo $sort == 'times_sold_desc' ? 'active' : ''; ?>">
                    <a href="?query=<?php echo urlencode($search_query); ?>&sort=times_sold_desc" class="text-decoration-none">
                        <i class='bx bx-sort-down'></i> Times Sold: High to Low
                    </a>
                </div>
                <div class="filter-option <?php echo $sort == 'most_recent' ? 'active' : ''; ?>">
                    <a href="?query=<?php echo urlencode($search_query); ?>&sort=most_recent" class="text-decoration-none">
                        <i class='bx bx-time'></i> Most Recent
                    </a>
                </div>
                <div class="filter-option <?php echo $sort == 'offers' ? 'active' : ''; ?>">
                    <a href="?query=<?php echo urlencode($search_query); ?>&sort=offers" class="text-decoration-none">
                        <i class='bx bx-tag-alt'></i> Offers
                    </a>
                </div>
            </div>
            <div class="filter-card">
                <h5 class="filter-title">Product Type</h5>
                <div class="filter-option <?php echo $filter_type == 'velvet_vibes' ? 'active' : ''; ?>">
                    <a href="?query=<?php echo urlencode($search_query); ?>&type=velvet_vibes" class="text-decoration-none">
                        <i class='bx bx-diamond'></i> Velvet & Vibes
                    </a>
                </div>
                <div class="filter-option <?php echo $filter_type == 'totsy' ? 'active' : ''; ?>">
                    <a href="?query=<?php echo urlencode($search_query); ?>&type=totsy" class="text-decoration-none">
                        <i class='bx bx-star'></i> Totsy
                    </a>
                </div>
            </div>
        </div>

        <!-- Updated Product cards -->
        <div class="col-md-9 mb-5">
            <h2 class="text-center mb-4" style="background: linear-gradient(90deg, #4ab6f4, #ff69b4); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Search Results for "<?php echo htmlspecialchars($search_query); ?>"</h2>
            <p class="text-center mb-4">Found <?php echo $total_products; ?> results</p>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php 
                $delay = 0;
                while ($product = $result->fetch_assoc()): 
                    $discounted_price = $product['price'];
                    $has_discount = false;
                    if ($product['discount'] && $product['start_date'] <= date('Y-m-d') && $product['end_date'] >= date('Y-m-d')) {
                        $discounted_price = $product['price'] * (1 - $product['discount']/100);
                        $has_discount = true;
                    }
                ?>
                    <div class="col" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                        <a href="product_details.php?id=<?php echo $product['id']; ?>" class="text-decoration-none">
                            <div class="card h-100">
                                <?php if ($has_discount): ?>
                                    <span class="discount-badge"><?php echo $product['discount']; ?>% OFF</span>
                                <?php endif; ?>
                                <?php
                                $image_path = "../admin/uploads/" . basename($product['image']);
                                if (file_exists($image_path) && is_readable($image_path)) {
                                    echo "<img src='" . htmlspecialchars($image_path) . "' class='card-img-top' alt='" . htmlspecialchars($product['title']) . "'>";
                                } else {
                                    echo "<img src='https://via.placeholder.com/300x200' class='card-img-top' alt='Placeholder for " . htmlspecialchars($product['title']) . "'>";
                                }
                                ?>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo htmlspecialchars($product['title']); ?></h5>
                                    <?php if ($has_discount): ?>
                                        <p class="price mb-0"><?php echo number_format($discounted_price, 0); ?> PKR</p>
                                        <p class="original-price mb-2"><?php echo number_format($product['price'], 0); ?> PKR</p>
                                    <?php else: ?>
                                        <p class="price mb-2"><?php echo number_format($product['price'], 0); ?> PKR</p>
                                    <?php endif; ?>
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
            
            <!-- Pagination -->
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                            <a class="page-link" href="?query=<?php echo urlencode($search_query); ?>&page=<?php echo $i; ?>&sort=<?php echo $sort; ?>&type=<?php echo $filter_type; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php while ($product = $result->fetch_assoc()): 
            $discounted_price = $product['price'];
            $has_discount = false;
            if (isset($product['discount']) && $product['discount'] && $product['start_date'] <= date('Y-m-d') && $product['end_date'] >= date('Y-m-d')) {
                $discounted_price = $product['price'] * (1 - $product['discount']/100);
                $has_discount = true;
            }
        ?>
            <div class="col">
                <a href="product_details.php?id=<?php echo $product['id']; ?>" class="text-decoration-none">
                    <div class="card h-100 shadow-sm">
                        <?php if ($has_discount): ?>
                            <span class="discount-badge" style="position: absolute; top: 10px; right: 10px; background: linear-gradient(90deg, #4ab6f4, #ff69b4); color: white; padding: 5px 10px; border-radius: 20px;"><?php echo $product['discount']; ?>% OFF</span>
                        <?php endif; ?>
                        <?php
                        $image_path = "../admin/uploads/" . basename($product['image']);
                        if (file_exists($image_path) && is_readable($image_path)) {
                            echo "<img src='" . htmlspecialchars($image_path) . "' class='card-img-top' alt='" . htmlspecialchars($product['title']) . "' style='height: 200px; object-fit: cover;'>";
                        } else {
                            echo "<img src='https://via.placeholder.com/300x200' class='card-img-top' alt='Placeholder for " . htmlspecialchars($product['title']) . "' style='height: 200px; object-fit: cover;'>";
                        }
                        ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title" style="color: #333;"><?php echo htmlspecialchars($product['title']); ?></h5>
                            <?php if ($has_discount): ?>
                                <p class="price mb-0" style="font-weight: bold; color: #ff69b4;"><?php echo number_format($discounted_price, 0); ?> PKR</p>
                                <p class="original-price mb-2" style="text-decoration: line-through; color: #999;"><?php echo number_format($product['price'], 0); ?> PKR</p>
                            <?php else: ?>
                                <p class="price mb-2" style="font-weight: bold; color: #4ab6f4;"><?php echo number_format($product['price'], 0); ?> PKR</p>
                            <?php endif; ?>
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <p class="times-sold mb-0" style="color: #666;">
                                    <i class='bx bx-purchase-tag' style="color: #4ab6f4;"></i>
                                    Sold <?php echo htmlspecialchars($product['times_sold']); ?> times
                                </p>
                                <i class='bx bx-cart cart-icon' style="color: #ff69b4; font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                    <a class="page-link" href="?query=<?php echo urlencode($search_query); ?>&page=<?php echo $i; ?>" style="color: white; border-color: #4ab6f4; background-color: #4ab6f4;">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<?php
// Include the HTML footer
include '../includes/other_footer.php';
?>