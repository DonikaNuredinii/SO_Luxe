<?php
session_start();
require __DIR__ . '/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];  

    try {
        // Marr përdoruesin vetëm bazuar në email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Kontrolloj nëse përdoruesi ekziston dhe verifikoj fjalëkalimin
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
            echo "Invalid email or password.";
        }
    } catch (PDOException $e) {
        echo "Query failed: " . $e->getMessage();
    }
}
?>
