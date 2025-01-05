<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    

    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($product) {
        $_SESSION['cart'][] = $product;

        $_SESSION['cart_modal'] = true;
        $_SESSION['cart_image'] = $product['image_url'];
        $_SESSION['cart_title'] = $product['name'];
        $_SESSION['cart_price'] = $product['price'];


        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit();
    }
}
?>
