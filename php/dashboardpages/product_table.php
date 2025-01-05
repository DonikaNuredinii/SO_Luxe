<?php
session_start();
require __DIR__ . '/../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../home.php");
    exit();
}

try {
    $stmt = $pdo->query("SELECT p.*, b.brand_name AS brand, s.stock_number AS stock, s.stock_id
                         FROM products p
                         JOIN brands b ON p.brand_id = b.brand_id
                         LEFT JOIN stocks s ON p.stock_id = s.stock_id
                         ORDER BY p.created_at ASC");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

try {
    $brands = $pdo->query("SELECT * FROM brands ORDER BY brand_name ASC")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

$productToEdit = null;
if (isset($_GET['edit_id'])) {
    $editId = $_GET['edit_id'];
    try {
        $stmt = $pdo->prepare("SELECT p.*, s.stock_number, s.stock_id 
                               FROM products p 
                               LEFT JOIN stocks s ON p.stock_id = s.stock_id 
                               WHERE p.product_id = ?");
        $stmt->execute([$editId]);
        $productToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $brand_id = $_POST['brand_id'];
    $gender = $_POST['gender'];
    $size = $_POST['size'];
    $image_url = $_POST['image_url'];
    $type = $_POST['type'];
    $main_notes = $_POST['main_notes'];
    $product_usage = $_POST['product_usage'];
    $stock_number = $_POST['stock_number'];

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO stocks (stock_number) VALUES (?)");
        $stmt->execute([$stock_number]);
        $stockId = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, brand_id, gender, size, image_url, type, main_notes, product_usage, stock_id) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $brand_id, $gender, $size, $image_url, $type, $main_notes, $product_usage, $stockId]);

        $pdo->commit();
        header("Location: product_table.php?success=1");
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Gabim: " . $e->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_product'])) {
    $productId = $_POST['product_id'];
    $stockId = $_POST['stock_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $brand_id = $_POST['brand_id'];
    $gender = $_POST['gender'];
    $size = $_POST['size'];
    $image_url = $_POST['image_url'];
    $type = $_POST['type'];
    $main_notes = $_POST['main_notes'];
    $product_usage = $_POST['product_usage'];
    $stock_number = $_POST['stock_number'];

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, brand_id = ?, gender = ?, size = ?, image_url = ?, type = ?, main_notes = ?, product_usage = ? 
                               WHERE product_id = ?");
        $stmt->execute([$name, $description, $price, $brand_id, $gender, $size, $image_url, $type, $main_notes, $product_usage, $productId]);

        if (!empty($stockId)) {
            $stmt = $pdo->prepare("UPDATE stocks SET stock_number = ? WHERE stock_id = ?");
            $stmt->execute([$stock_number, $stockId]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO stocks (stock_number) VALUES (?)");
            $stmt->execute([$stock_number]);
            $newStockId = $pdo->lastInsertId();

            $stmt = $pdo->prepare("UPDATE products SET stock_id = ? WHERE product_id = ?");
            $stmt->execute([$newStockId, $productId]);
        }

        $pdo->commit();
        header("Location: product_table.php");
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Gabim: " . $e->getMessage();
    }
}

if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->execute([$deleteId]);

        $stmt = $pdo->prepare("DELETE FROM stocks WHERE stock_id = ?");
        $stmt->execute([$deleteId]);

        $pdo->commit();
        header("Location: product_table.php");
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Gabim: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/dashboard.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Product Management</h1>
        <a href="dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

        <a href="product_table.php?add_product=1" class="btn btn-success mb-3">Add Product</a>

        <?php if (isset($_GET['add_product']) || isset($_GET['edit_id'])): ?>
            <div class="card mb-5">
                <div class="card-body">
                    <h3><?php echo isset($_GET['edit_id']) ? 'Edit Product' : 'Add New Product'; ?></h3>
                    <form method="POST" action="product_table.php">
                        <input type="hidden" name="product_id" value="<?php echo $productToEdit['product_id'] ?? ''; ?>">
                        <input type="hidden" name="stock_id" value="<?php echo $productToEdit['stock_id'] ?? ''; ?>">

                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo $productToEdit['name'] ?? ''; ?>" required>

                        <label>Description</label>
                        <textarea name="description" class="form-control"><?php echo $productToEdit['description'] ?? ''; ?></textarea>

                        <label>Price (€)</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $productToEdit['price'] ?? ''; ?>" required>

                        <label>Brand</label>
                        <select name="brand_id" class="form-control" required>
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?php echo $brand['brand_id']; ?>" 
                                    <?php echo (isset($productToEdit['brand_id']) && $productToEdit['brand_id'] == $brand['brand_id']) ? 'selected' : ''; ?>>
                                    <?php echo $brand['brand_name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <label>Gender</label>
                        <select name="gender" class="form-control">
                            <option value="Femra" <?php echo (isset($productToEdit['gender']) && $productToEdit['gender'] == 'Femra') ? 'selected' : ''; ?>>Femra</option>
                            <option value="Meshkuj" <?php echo (isset($productToEdit['gender']) && $productToEdit['gender'] == 'Meshkuj') ? 'selected' : ''; ?>>Meshkuj</option>
                        </select>

                        <label>Size</label>
                        <input type="text" name="size" class="form-control" value="<?php echo $productToEdit['size'] ?? ''; ?>">

                        <label>Image URL</label>
                        <input type="text" name="image_url" class="form-control" value="<?php echo $productToEdit['image_url'] ?? ''; ?>">

                        <label>Type</label>
                        <input type="text" name="type" class="form-control" value="<?php echo $productToEdit['type'] ?? ''; ?>">

                        <label>Main Notes</label>
                        <input type="text" name="main_notes" class="form-control" value="<?php echo $productToEdit['main_notes'] ?? ''; ?>">

                        <label>Usage</label>
                        <input type="text" name="product_usage" class="form-control" value="<?php echo $productToEdit['product_usage'] ?? ''; ?>">

                        <label>Stock</label>
                        <input type="number" name="stock_number" class="form-control" value="<?php echo $productToEdit['stock_number'] ?? '0'; ?>" required>
                        
                        <button type="submit" name="<?php echo isset($_GET['edit_id']) ? 'edit_product' : 'add_product'; ?>" class="btn btn-primary mt-3">Save Product</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Brand</th>
                    <th>Gender</th>
                    <th>Size</th>
                    <th>Image URL</th>
                    <th>Type</th>
                    <th>Main Notes</th>
                    <th>Usage</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo $product['product_id']; ?></td>
                        <td><?php echo $product['name']; ?></td>
                        <td><?php echo $product['description']; ?></td>
                        <td>€<?php echo $product['price']; ?></td>
                        <td><?php echo $product['brand']; ?></td>
                        <td><?php echo $product['gender']; ?></td>
                        <td><?php echo $product['size']; ?></td>
                        <td><?php echo $product['image_url']; ?></td>
                        <td><?php echo $product['type']; ?></td>
                        <td><?php echo $product['main_notes']; ?></td>
                        <td><?php echo $product['product_usage']; ?></td>
                        <td><?php echo $product['stock'] ?? '0'; ?></td>
                        <td>
                            <a href="product_table.php?edit_id=<?php echo $product['product_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="product_table.php?delete_id=<?php echo $product['product_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
