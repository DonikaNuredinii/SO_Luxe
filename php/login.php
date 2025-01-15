<?php
session_start();
require __DIR__ . '/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];  
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: home.php");
                exit();
            } else {
                header("Location: home.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Invalid email or password.";
            header("Location: loginsignup.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Query failed: " . $e->getMessage();
        header("Location: loginsignup.php");
        exit();
    }
}
?>
