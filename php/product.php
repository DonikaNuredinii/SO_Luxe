<?php
include 'header.php';
include 'db.php';

$selectedBrands = isset($_GET['brand']) ? $_GET['brand'] : [];
$selectedGenders = isset($_GET['gender']) ? $_GET['gender'] : [];

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
</div>
<?php include 'footer.php'; ?>
