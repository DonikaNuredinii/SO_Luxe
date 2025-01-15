<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: php/login.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link link rel="stylesheet" href="../../assets/dashboard.css">
</head>
<body>
    <div class="d-flex">
        <div class="sidebar p-3">
            <h3>Admin Dashboard</h3>
            <a href="../home.php">Home</a>
            <a href="usertable.php">Users</a>
            <a href="product_table.php">Products</a>
            <a href="orders_table.php">Orders</a>
            <a href="contact_messages_table.php">Contact</a>
            <a href="../logout.php">Logout</a>
        </div>
            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div class="alert alert-success">
                    Produkti u shtua/ndryshua me sukses!
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
