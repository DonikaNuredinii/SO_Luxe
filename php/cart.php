<?php
include 'header.php';
include 'db.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

function isProductInCart($productId) {
    foreach ($_SESSION['cart'] as $item) {
        if ($item['product_id'] == $productId) {
            return true;  
        }
    }
    return false;  
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];

    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        if (!isProductInCart($product['product_id'])) {
            $product['quantity'] = 1;
            $_SESSION['cart'][] = $product;

            $_SESSION['cart_modal'] = true;
            $_SESSION['cart_image'] = $product['image_url'];
            $_SESSION['cart_title'] = $product['name'];
            $_SESSION['cart_price'] = $product['price'];
        }
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
?>
<div class="cart-container">
    <div class="top-Cart">
        <h1>Your cart</h1>
        <a href="product.php" class="continue-shoppingg">Continue Shopping</a>
    </div>

    <div class="cart-items-name">
        <p>Product</p>
        <p id="q">Quantity</p>
        <p>Total Price</p>
    </div>

    <?php if (!empty($_SESSION['cart'])): ?>
        <?php foreach ($_SESSION['cart'] as $item): ?>
            <div class="cart-item">
                <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>" />
                <div class="items-cart-screen">
                    <div class="item-details">
                        <p><?php echo $item['name']; ?></p>
                        <p><?php echo number_format($item['price'], 2); ?>€</p>
                    </div>
                 
                    <div class="quantity-wrapper">
                        <div class="quantity-controls">
                            <form method="POST" action="update_cart.php">
                                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                <button type="submit" name="decrease">-</button>
                                <span>
                                    <?php 
                                    echo isset($item['quantity']) ? $item['quantity'] : 1; 
                                    ?>
                                </span>
                                <button type="submit" name="increase">+</button>
                            </form>
                        </div>

                        <form method="POST" action="update_cart.php" class="delete-form">
                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                            <button type="submit" name="delete" class="delete-icon" title="Remove item">
                                &#128465; 
                            </button>
                        </form>
                    </div>

                    <p class="total-price">
                        <?php echo number_format($item['price'] * $item['quantity'], 2); ?>€
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

    <div class="cart-summary">
        <p>Estimated total: 
            <?php 
                $total = 0;
                if (!empty($_SESSION['cart'])) {
                    $total = array_sum(array_map(function ($item) {
                        return $item['price'] * $item['quantity'];
                    }, $_SESSION['cart']));
                }
                echo number_format($total, 2); 
            ?> €
        </p>
        <p>Tax included. <a href="#">Shipping</a> and discounts calculated at checkout.</p>
        <button class="checkout-button" onclick="window.location.href='checkout.php';">
            <?php echo isset($_SESSION['user']) ? "Please log in to checkout" : "Checkout"; ?>
        </button>
    </div>
</div>

<?php if (isset($_SESSION['cart_modal']) && $_SESSION['cart_modal'] == true): ?>
    <div class="modal-cart">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p>Item added to cart</p>
            <img src="<?php echo $_SESSION['cart_image']; ?>" alt="<?php echo $_SESSION['cart_title']; ?>" class="design-preview-modal">
            <p><?php echo $_SESSION['cart_title']; ?></p>
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
