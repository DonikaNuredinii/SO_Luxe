<?php
include 'header.php';
include 'db.php';

if (isset($_POST['buy_now'])) {
    $productId = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;

    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $_SESSION['cart'] = [
            [
                'product_id' => $product['product_id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'image_url' => $product['image_url'],
            ]
        ];
    }

    header("Location: checkout.php"); 
    exit();
}
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

?>
<div class="checkout-container_ch">
    <form class="checkout-form_ch" method="POST" action="process_checkout.php">
        <div class="left-section_ch">
            <div class="contact_ch">
                <h2 class="h2ch">Contact</h2>
                <input type="email" name="email" placeholder="Your Email" required>
            </div>
            <div class="delivery_ch">
                <h2 class="h2ch">Delivery</h2>
                <select name="country" required>
                    <option value="">Select Country</option>
                    <option value="1">Kosova</option>
                    <option value="2">Albania</option>
                    <option value="3">Macedonia</option>
                </select>
                <input type="text" name="firstName" placeholder="First Name" required>
                <input type="text" name="lastName" placeholder="Last Name" required>
                <input type="text" name="address" placeholder="Address" required>
                <input type="text" name="zipCode" placeholder="Zip Code" required>
                <input type="text" name="city" placeholder="City" required>
                <input type="tel" name="phone" placeholder="Phone Number" required>
            </div>

            <div class="payment_ch">
                <h2 class="h2ch">Payment</h2>
                <div class="payment-method_ch">
                    <input type="radio" name="paymentMethod" id="cashOnDelivery" value="cashOnDelivery" checked>
                    <label for="cashOnDelivery">
                        <i class="fas fa-hand-holding-usd"></i> Cash on Delivery
                    </label>
                </div>
            </div>
            <button type="submit" class="complete-order_ch">Complete Order</button>
        </div>

        <div class="right-section_ch">
            <div class="summary_ch">
                <h2 class="h2ch">Order Summary</h2>
                <ul>
                    <?php $total = 0; ?>
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                        <li>
                            <div class="item-image_ch">
                                <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>">
                            </div>
                            <div class="item-details_ch">
                                <p><?php echo $item['name']; ?></p>
                                <p><?php echo $item['quantity']; ?> x <?php echo number_format($item['price'], 2); ?>€</p>
                            </div>
                            <div class="item-total_ch">
                                <?php 
                                    $subtotal = $item['price'] * $item['quantity'];
                                    echo number_format($subtotal, 2);
                                    $total += $subtotal;
                                ?>€
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="summary-details_ch">
                <p>Subtotal: <?php echo number_format($total, 2); ?>€</p>
                <p>Shipping: Free</p>
                <p><strong>Total: <?php echo number_format($total, 2); ?>€</strong></p>
            </div>
        </div>
    </form>
</div>
<?php include 'footer.php'; ?>
