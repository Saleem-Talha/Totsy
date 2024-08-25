

<?php

elseif (isset($_POST['add_review'])) {
    $username = $db->real_escape_string($_POST['username']);
    $description = $db->real_escape_string($_POST['description']);
    $rating = intval($_POST['rating']);
    
    $query = "INSERT INTO reviews (username, description, rating) VALUES ('$username', '$description', $rating)";
    if ($db->query($query) === TRUE) {
        echo "Review added successfully";
    } else {
        echo "Error: " . $query . "<br>" . $db->error;
    }
}

elseif (isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $price_at_addition = intval($_POST['price_at_addition']);
    $total = $quantity * $price_at_addition;
    
    $query = "INSERT INTO cart (product_id, quantity, price_at_addition, total) 
              VALUES ($product_id, $quantity, $price_at_addition, $total)";
    if ($db->query($query) === TRUE) {
        echo "Item added to cart successfully";
    } else {
        echo "Error: " . $query . "<br>" . $db->error;
    }
}
?>


<!-- Reviews Form -->
<h2>Add Review</h2>
        <form action="" method="post" class="mb-4">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="review_description" class="form-label">Description</label>
                <textarea class="form-control" id="review_description" name="description" required></textarea>
            </div>
            <div class="mb-3">
                <label for="rating" class="form-label">Rating</label>
                <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" required>
            </div>
            <button type="submit" name="add_review" class="btn btn-primary">Add Review</button>
        </form>


 <!-- Cart Form -->
 <h2>Add to Cart</h2>
        <form action="" method="post" class="mb-4">
            <div class="mb-3">
                <label for="cart_product_id" class="form-label">Product ID</label>
                <input type="number" class="form-control" id="cart_product_id" name="product_id" required>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>
            <div class="mb-3">
                <label for="price_at_addition" class="form-label">Price at Addition</label>
                <input type="number" class="form-control" id="price_at_addition" name="price_at_addition" required>
            </div>
            <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
        </form>