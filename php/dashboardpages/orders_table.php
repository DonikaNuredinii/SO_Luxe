<?php
session_start();
require __DIR__ . '/../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../home.php");
    exit();
}

// Terheq te gjitha porosite
try {
    $stmt = $pdo->query("SELECT * FROM orders ORDER BY order_date ASC");
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Fshirja e porosise
if (isset($_GET['delete_id'])) {
    $orderId = $_GET['delete_id'];
    try {
        $pdo->prepare("DELETE FROM order_items WHERE order_id = ?")->execute([$orderId]);
        $pdo->prepare("DELETE FROM orders WHERE order_id = ?")->execute([$orderId]);
        $_SESSION['message'] = "Order deleted successfully.";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to delete order.";
    }
    header("Location: orders_table.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/dashboard.css"> 
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Order Management</h1>
        <a href="dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

        <!-- Mesazhet per sukses/gabim -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"> <?php echo $_SESSION['message']; unset($_SESSION['message']); ?> </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?> </div>
        <?php endif; ?>

        <!-- Tabela e Porosive -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Total (€)</th>
                    <th>Order Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo $order['order_id']; ?></td>
                        <td><?php echo $order['first_name'] . ' ' . $order['last_name']; ?></td>
                        <td><?php echo $order['user_email']; ?></td>
                        <td><?php echo number_format($order['total'], 2); ?> €</td>
                        <td><?php echo $order['order_date']; ?></td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick="fetchOrderDetails(<?php echo $order['order_id']; ?>)">View</button>
                            <a href="orders_table.php?delete_id=<?php echo $order['order_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this order?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modali -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="modalOrderDetails">
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function fetchOrderDetails(orderId) {
            fetch('order_details.php?order_id=' + orderId)
            .then(response => response.text())
            .then(data => {
                document.getElementById('modalOrderDetails').innerHTML = data;
                var modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
                modal.show();
            });
        }
    </script>
</body>
</html>
