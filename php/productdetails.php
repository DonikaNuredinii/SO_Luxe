<?php
include 'header.php';
include 'db.php';

if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    try {
        $stmt = $pdo->prepare("SELECT p.*, b.brand_name AS brand
                               FROM products p
                               JOIN brands b ON p.brand_id = b.brand_id
                               WHERE p.product_id = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            echo "<p>Produkti nuk u gjet.</p>";
            exit();
        }
    } catch (PDOException $e) {
        die("Gabim: " . $e->getMessage());
    }
} else {
    echo "<p>Asnjë produkt i zgjedhur.</p>";
    exit();
}
?>
<div class="container_pd">
    <div class="product-section_pd">
        <div class="product-image">
            <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>" />
        </div>
        <div class="product-details">
            <h1><?php echo $product['name']; ?></h1>
            <div class="price"><?php echo number_format($product['price'], 2); ?> €</div>

            <div class="label">Madhësia</div>
            <select class="select-box">
                <option><?php echo $product['size']; ?></option>
            </select>

            <div class="label-pd">Sasia</div>
            <select class="quantity-select">
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
            </select>

            <button class="btn_pd cart-btn">Shto në shportë</button>
            <button class="btn_pd buy-btn">Blej tani</button>
        </div>
    </div>

    <div class="tabs">
        <div class="tab active" onclick="openTab(event, 'pershkrim')">
            Përshkrim
        </div>
        <div class="tab" onclick="openTab(event, 'atributet')">Atributet</div>
    </div>

    <div id="pershkrim" class="tab-content active">
        <h2>Përshkrimi i Produktit</h2>
        <p><?php echo $product['description']; ?></p>
    </div>

    <div id="atributet" class="tab-content">
        <h2>Atributet e Produktit</h2>
        <ul class="attributes-list">
            <li><strong>Marka:</strong> <?php echo $product['brand']; ?></li>
            <li><strong>Madhësia:</strong> <?php echo $product['size']; ?></li>
            <li><strong>Gjinia:</strong> <?php echo ucfirst($product['gender']); ?></li>
            <li><strong>Lloji:</strong> <?php echo $product['type']; ?></li>
            <li><strong>Përbërsit kryesorë:</strong> <?php echo $product['main_notes']; ?></li>
            <li><strong>Përdorimi:</strong> <?php echo $product['product_usage']; ?></li>
        </ul>
    </div>
</div>
<?php include 'footer.php'; ?>
