<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $zipCode = $_POST['zipCode'];
    $phone = $_POST['phone'];
    $paymentMethod = $_POST['paymentMethod'];
    $cart = $_SESSION['cart'];

    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // Ruajtja e porosise ne tabelën orders
    $stmt = $pdo->prepare("
        INSERT INTO orders (user_email, first_name, last_name, address, city, zip_code, phone, total, payment_method)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$email, $firstName, $lastName, $address, $city, $zipCode, $phone, $total, $paymentMethod]);
    $orderId = $pdo->lastInsertId();

    // Shto artikujt e shportës ne order_items
    foreach ($cart as $item) {
        $stmt = $pdo->prepare("
            INSERT INTO order_items (order_id, product_id, product_name, quantity, price, subtotal)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $orderId,
            $item['product_id'], 
            $item['name'],
            $item['quantity'],
            $item['price'],
            $item['price'] * $item['quantity']
        ]);
    }

    // Pastrimi i shportes pas blerjes
    unset($_SESSION['cart']);

    echo "<script>
            alert('Your order has been placed successfully!');
            window.location.href = 'home.php';
          </script>";
    exit();
}
?>
