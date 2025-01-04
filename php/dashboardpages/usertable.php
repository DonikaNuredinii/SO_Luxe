<?php
session_start();
require __DIR__ . '/../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../home.php");
    exit();
}

try {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY id ASC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);  
    $role = $_POST['role'];

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $password, $role]);
        header("Location: usertable.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_user'])) {
    $userId = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];

    try {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ?, role = ? WHERE id = ?");
        $stmt->execute([$name, $email, $phone, $role, $userId]);
        header("Location: usertable.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

if (isset($_GET['delete_id'])) {
    $userId = $_GET['delete_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        header("Location: usertable.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/dashboard.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">User Management</h1>
        <a href="dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

        <a href="usertable.php?add_user=1" class="btn btn-success mb-3">Add User</a>

        <?php if (isset($_GET['add_user'])): ?>
            <div class="card mb-5">
                <div class="card-body">
                    <h3>Add New User</h3>
                    <form method="POST">
                        <div class="mb-3">
                            <label>Full Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Role</label>
                            <select name="role" class="form-control">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
                        <a href="usertable.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- Tabela e userave -->
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['name']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['phone']; ?></td>
                        <td><?php echo $user['role']; ?></td>
                        <td><?php echo $user['created_at']; ?></td>
                        <td>
                            <a href="usertable.php?edit_id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="usertable.php?delete_id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>

                    <!-- Forma per editim  -->
                    <?php if (isset($_GET['edit_id']) && $_GET['edit_id'] == $user['id']): ?>
                        <tr>
                            <td colspan="7">
                                <form method="POST" class="mt-3">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <input type="text" name="name" class="form-control" value="<?php echo $user['name']; ?>" required>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="phone" class="form-control" value="<?php echo $user['phone']; ?>" required>
                                        </div>
                                        <div class="col-md-3">
                                            <select name="role" class="form-control">
                                                <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
                                                <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-3">
                                            <button type="submit" name="edit_user" class="btn btn-primary">Save</button>
                                            <a href="usertable.php" class="btn btn-secondary">Cancel</a>
                                        </div>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
