<?php
// Lidhja me bazën e të dhënave
$conn = new mysqli('localhost', 'root', '', 'emri_i_bazes_se_te_dhenave');

// Kontrollo lidhjen
if ($conn->connect_error) {
    die("Lidhja dështoi: " . $conn->connect_error);
}

$formErrors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $lastName = $conn->real_escape_string($_POST['lastName']);
    $phoneNumber = $conn->real_escape_string($_POST['phoneNumber']);
    $email = $conn->real_escape_string($_POST['email']);

    // Kontrollo validimet
    if (empty($firstName)) $formErrors['firstName'] = "First name is required";
    if (empty($lastName)) $formErrors['lastName'] = "Last name is required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $formErrors['email'] = "Invalid email format";

    if (empty($formErrors)) {
        // Përditëso të dhënat në bazën e të dhënave
        $userId = 1; // Përdor ID-në e përdoruesit të kyçur nga sesioni
        $sql = "UPDATE users 
                SET name = '$firstName $lastName', phone = '$phoneNumber', email = '$email' 
                WHERE id = $userId";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Të dhënat u përditësuan me sukses!');</script>";
        } else {
            echo "<script>alert('Gabim gjatë përditësimit: " . $conn->error . "');</script>";
        }
    } else {
        foreach ($formErrors as $error) {
            echo "<script>alert('$error');</script>";
        }
    }
}

// Mbyll lidhjen
$conn->close();
?>
