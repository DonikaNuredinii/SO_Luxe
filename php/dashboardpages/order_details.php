<?php
session_start();
require __DIR__ . '/../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    exit("Unauthorized access");
}
if (!isset($_GET['order_id'])) {
    exit("Order ID missing");
}

$orderId = $_GET['order_id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ?");
    $stmt->execute([$orderId]);
    $orderDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
    $stmt->execute([$orderId]);
    $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$orderDetails) {
        exit("Order not found");
    }
} catch (PDOException $e) {
    exit("Error: " . $e->getMessage());
}
?>

<p><strong>Customer:</strong> <?php echo $orderDetails['first_name'] . ' ' . $orderDetails['last_name']; ?></p>
<p><strong>Email:</strong> <?php echo $orderDetails['user_email']; ?></p>
<p><strong>City:</strong> <?php echo $orderDetails['city']; ?></p>
<p><strong>Address:</strong> <?php echo $orderDetails['address']; ?></p>
<p><strong>Phone:</strong> <?php echo $orderDetails['phone']; ?></p> 
<p><strong>Total:</strong> <?php echo number_format($orderDetails['total'], 2); ?> €</p>
<p><strong>Order Date:</strong> <?php echo $orderDetails['order_date']; ?></p>
<hr>

<h4>Items</h4>
<table class="table">
    <thead>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price (€)</th>
            <th>Subtotal (€)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orderItems as $item): ?>
            <tr>
                <td><?php echo $item['product_name']; ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td><?php echo number_format($item['price'], 2); ?></td>
                <td><?php echo number_format($item['subtotal'], 2); ?> €</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
