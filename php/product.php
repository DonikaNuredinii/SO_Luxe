<?php
include 'header.php';
include 'db.php';

$selectedBrands = isset($_GET['brand']) ? $_GET['brand'] : [];
$selectedGenders = isset($_GET['gender']) ? $_GET['gender'] : [];
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT * FROM products WHERE 1=1";
$params = [];

if (!empty($selectedBrands)) {
    $brandPlaceholders = implode(',', array_fill(0, count($selectedBrands), '?'));
    $query .= " AND brand_id IN ($brandPlaceholders)";
    $params = array_merge($params, $selectedBrands);
}

if (!empty($selectedGenders)) {
    $genderPlaceholders = implode(',', array_fill(0, count($selectedGenders), '?'));
    $query .= " AND gender IN ($genderPlaceholders)";
    $params = array_merge($params, $selectedGenders);
}

if (!empty($searchTerm)) {
    $query .= " AND name LIKE ?";
    $params[] = '%' . $searchTerm . '%';
}


try {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

try {
    $brandStmt = $pdo->query("SELECT * FROM brands ORDER BY brand_name ASC");
    $brands = $brandStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

try {
    $genderStmt = $pdo->query("SELECT DISTINCT gender FROM products");
    $genders = $genderStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<div class="container_p">
    <form method="GET" action="" id="filterForm">
        <div class="filters">
            <div class="filter-group">
                <h3>Gjinia</h3>
                <?php foreach ($genders as $gender): ?>
                    <label>
                        <input type="checkbox" name="gender[]" value="<?php echo $gender['gender']; ?>"
                            <?php echo in_array($gender['gender'], $selectedGenders) ? 'checked' : ''; ?>
                            onchange="autoSubmit()">
                        <?php echo ucfirst($gender['gender']); ?>
                    </label>
                <?php endforeach; ?>
            </div>

            <div class="filter-group">
                <h3>Brendi</h3>
                <?php foreach ($brands as $brand): ?>
                    <label>
                        <input type="checkbox" name="brand[]" value="<?php echo $brand['brand_id']; ?>"
                            <?php echo in_array($brand['brand_id'], $selectedBrands) ? 'checked' : ''; ?>
                            onchange="autoSubmit()">
                        <?php echo $brand['brand_name']; ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
    </form>

    <div class="products">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <a href="productdetails.php?id=<?php echo $product['product_id']; ?>">
                        <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
                    </a>
                    <h2><?php echo $product['name']; ?></h2>
                    <p><?php echo $product['price']; ?> €</p>
                    <form method="POST" action="cart.php">
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                        <button type="submit" name="add_to_cart">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No products found matching the selected criteria.</p>
        <?php endif; ?>
    </div>
    <?php if (isset($_SESSION['cart_modal']) && $_SESSION['cart_modal'] == true): ?>
        <div class="modal-cart">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <p>Item added to cart</p>
                <img src="<?php echo $_SESSION['cart_image']; ?>" alt="<?php echo $_SESSION['cart_title']; ?>" class="design-preview-modal">
                <p>Amount: <?php echo number_format($_SESSION['cart_price'], 2); ?>€</p>
                <div class="view-cart-container">
                    <a href="cart.php" class="view-cart-button">View Cart</a>
                </div>
                <div class="view-cart-container">
                    <a href="product.php" class="continue-shopping">Continue Shopping</a>
                </div>
            </div>
        </div>
        <?php unset($_SESSION['cart_modal']); ?>
    <?php endif; ?>
</div>
<?php include 'footer.php'; ?>