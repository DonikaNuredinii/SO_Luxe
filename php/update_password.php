<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to update your password.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $currentPassword = trim($_POST['currentPassword']);
    $newPassword = trim($_POST['newPassword']);
    $confirmPassword = trim($_POST['confirmPassword']);

    if (strlen($newPassword) < 6) {
        $_SESSION['error'] = "New password must be at least 6 characters long.";
        header("Location: update_password_settings.php");
        exit;
    }

    if ($newPassword !== $confirmPassword) {
        $_SESSION['error'] = "New password and confirmation do not match.";
        header("Location: update_password_settings.php");
        exit;
    }

    try {
        $sql = "SELECT password FROM users WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $_SESSION['error'] = "User not found.";
            header("Location: update_password_settings.php");
            exit;
        }

        if (!password_verify($currentPassword, $user['password'])) {
            $_SESSION['error'] = "Your current password is wrong.";
            header("Location: update_password_settings.php");
            exit;
        }

        $newHashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'password' => $newHashedPassword,
            'id' => $userId
        ]);

        $_SESSION['success'] = "Password updated successfully!";
        header("Location: update_password_settings.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: update_password_settings.php");
        exit;
    }
}
