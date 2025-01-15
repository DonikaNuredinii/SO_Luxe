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

if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;

    $productExistsInCart = false;
    foreach ($_SESSION['cart'] as $item) {
        if ($item['product_id'] == $productId) {
            $productExistsInCart = true;
            break;
        }
    }

    if (!$productExistsInCart) {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $_SESSION['cart'][] = [
                'product_id' => $product['product_id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'image_url' => $product['image_url'],
            ];

            $_SESSION['cart_modal'] = true;
            $_SESSION['cart_image'] = $product['image_url'];
            $_SESSION['cart_title'] = $product['name'];
            $_SESSION['cart_price'] = $product['price'] * $quantity;
        }
    } else {
        $_SESSION['cart_modal'] = false;
    }

    header("Location: product.php?id=" . $productId);
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

            <form method="POST" action="">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" name="add_to_cart" class="btn_pd cart-btn">Shto në shportë</button>
            </form>

            <form method="POST" action="checkout.php">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" name="buy_now" class="btn_pd buy-btn">Blej tani</button>
            </form>
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
<?php include 'footer.php'; ?>