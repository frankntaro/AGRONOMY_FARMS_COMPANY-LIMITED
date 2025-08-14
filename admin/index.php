<?php
// Include configuration and setup
require_once 'config.php';
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/auth_modal.php'; ?>
<link rel="stylesheet" href="includes/assets/css/style.css">

<div class="container">
    <div class="main-content">
        <?php include 'includes/sidebar.php'; ?>

        <div class="product-grid">
            <h2 class="section-title">
                <?php
                $search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
                $category_filter = isset($_GET['category']) ? $_GET['category'] : '';
                $query_type = '';

                if ($search_term) {
                    $query_type = 'search';
                    echo '<i class="fas fa-search"></i> Search Results for "' . htmlspecialchars($search_term) . '"';
                } elseif ($category_filter) {
                    $query_type = 'category';
                    echo '<i class="fas fa-filter"></i> Products in ' . htmlspecialchars($category_filter);
                } else {
                    $query_type = 'all';
                    echo '<i class="fas fa-seedling"></i> Bulk Buy Farm Products To Sell';
                }
                ?>
            </h2>

            <div class="certification">
                <i class="fas fa-certificate"></i> All products meet Agronomy Farms quality standards
            </div>
            <div class="products">
                <?php
                $result = false;
                
                if ($query_type === 'search') {
                    // UPDATED: Search query now includes the 'category' field
                    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ? OR category LIKE ?");
                    $search_param = '%' . $search_term . '%';
                    $stmt->bind_param("sss", $search_param, $search_param, $search_param);
                    $stmt->execute();
                    $result = $stmt->get_result();

                } elseif ($query_type === 'category') {
                    // Your existing secure category filter
                    $stmt = $conn->prepare("SELECT * FROM products WHERE category = ?");
                    $stmt->bind_param("s", $category_filter);
                    $stmt->execute();
                    $result = $stmt->get_result();
                } else {
                    // Your existing query to show all products
                    $result = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
                }

                if ($result && $result->num_rows > 0) {
                    while ($product = $result->fetch_assoc()):
                        ?>
                        <div class="product-card">
                            <div class="product-image">
                                <?php
                                $imagePath = htmlspecialchars($product['image']);
                                if (!file_exists($imagePath)) {
                                    $imagePath = 'uploads/default.jpg';
                                }
                                ?>
                                <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="max-width:100%; height:150px; object-fit:cover;">
                            </div>
                            <div class="product-details">
                                <span class="product-badge">Category: <?= htmlspecialchars($product['category']) ?></span>
                                <h3 class="product-title"><?= htmlspecialchars($product['name']) ?></h3>
                                <p class="product-description"><?= htmlspecialchars($product['description']) ?></p>
                                <div class="product-price">TSh <?= number_format($product['price'], 2) ?></div>
                                <div class="product-moq">
                                    <span class="moq-label">Stock:</span>
                                    <span class="moq-value"><?= htmlspecialchars($product['stock']) ?> units</span>
                                </div>
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <form class="add-to-cart-form" method="POST" action="add_to_cart.php">
                                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
                                        <input type="number" class="quantity-input" name="quantity" value="1" min="1" max="<?= htmlspecialchars($product['stock']) ?>">
                                        <button type="submit" name="add_to_cart" class="add-to-cart-btn">
                                            <i class="fas fa-cart-plus"></i> Add to Cart
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <div class="guest-cart-warning" onclick="showLoginPrompt(<?= htmlspecialchars($product['id']) ?>)">
                                        <i class="fas fa-cart-plus"></i> Add to cart
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile;
                } else {
                    // Display message if no products are found
                    echo '<p style="text-align: center; width: 100%;">No products found matching your criteria.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
<div id="loginPrompt" class="login-prompt">
    <div class="login-message">
        <p>You need to <a href="login.php" id="loginLink">log in</a> to add this product to your cart.</p>
        <button onclick="closePrompt()">Close</button>
    </div>
</div>

<style>
.login-prompt {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.login-message {
    background: white;
    padding: 30px;
    border-radius: 10px;
    text-align: center;
    animation: slideFade 0.4s ease-out;
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
}

.login-message a {
    color: #007BFF;
    text-decoration: underline;
}

@keyframes slideFade {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>

<script>
function showLoginPrompt(productId) {
    const prompt = document.getElementById("loginPrompt");
    prompt.style.display = "flex";
    
    // Store intended product ID in localStorage
    localStorage.setItem('intendedProductId', productId);
}

function closePrompt() {
    document.getElementById("loginPrompt").style.display = "none";
}
</script>


<?php include 'includes/footer.php'; ?>