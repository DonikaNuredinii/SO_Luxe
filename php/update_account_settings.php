<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("Duhet të jeni i loguar për të aksesuar këtë faqe.");
}

$formErrors = [];
$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $phoneNumber = trim($_POST['phoneNumber'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (empty($firstName)) {
        $formErrors['firstName'] = "First name is required.";
    }
    if (empty($lastName)) {
        $formErrors['lastName'] = "Last name is required.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formErrors['email'] = "Invalid email format.";
    }
    if (!preg_match('/^\+?\d{7,15}$/', $phoneNumber)) {
        $formErrors['phoneNumber'] = "Valid phone number is required.";
    }

    if (empty($formErrors)) {
        try {
            $sql = "UPDATE users 
                    SET name = :name, phone = :phone, email = :email 
                    WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name' => "$firstName $lastName",
                ':phone' => $phoneNumber,
                ':email' => $email,
                ':id' => $userId
            ]);

            echo "<script>alert('Të dhënat u përditësuan me sukses!');</script>";
        } catch (PDOException $e) {
            echo "<script>alert('Gabim gjatë përditësimit: " . $e->getMessage() . "');</script>";
        }
    }
}
