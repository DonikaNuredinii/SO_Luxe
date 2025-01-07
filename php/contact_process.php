<?php
include 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']) ?? '';
    $email = trim($_POST['email']) ?? '';
    $message = trim($_POST['message']) ?? '';

    if (!empty($name) && !empty($email) && !empty($message)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $message]);
            echo "<script>alert('Message sent successfully!'); window.location.href = 'contactus.php';</script>";
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    } else {
        echo "<script>alert('Please fill in all fields.'); window.history.back();</script>";
    }
}
?>
