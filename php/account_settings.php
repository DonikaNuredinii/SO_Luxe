<?php
include 'header.php';
include 'db.php'; // Përfshi lidhjen PDO me bazën e të dhënave

// Sigurohu që përdoruesi është i kyçur
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to access this page.");
}

// Merr ID-në e përdoruesit të kyçur nga sesioni
$userId = $_SESSION['user_id'];

// Inicializo variablat dhe gabimet e formularit
$formErrors = [];
$name = '';
$phone = '';
$email = '';

// Merr të dhënat ekzistuese të përdoruesit
try {
    $sql = "SELECT name, phone, email FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $name = $user['name'];
        $phone = $user['phone'];
        $email = $user['email'];
    } else {
        die("User not found.");
    }
} catch (PDOException $e) {
    die("Error fetching user data: " . $e->getMessage());
}

// Përpunimi i formularit për përditësimin e të dhënave
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);

    // Validimi
    if (empty($name)) {
        $formErrors['name'] = "Name is required.";
    }
    if (empty($phone) || !preg_match('/^\+?\d{7,15}$/', $phone)) {
        $formErrors['phone'] = "Valid phone number is required.";
    }

    // Nëse nuk ka gabime, përditëso të dhënat
    if (empty($formErrors)) {
        try {
            $sql = "UPDATE users SET name = :name, phone = :phone WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'name' => $name,
                'phone' => $phone,
                'id' => $userId
            ]);
            echo "<script>alert('Account updated successfully!');</script>";
        } catch (PDOException $e) {
            echo "<script>alert('Failed to update account: " . $e->getMessage() . "');</script>";
        }
    }
}
?>


<div class="container-accountsettings">
    <!-- Sidebar për navigimin -->
    <div class="sidebar-acc">
        <a href="account_settings.php" class="linkacc">Profile</a>
        <a href="update_password_settings.php" class="linkacc">Change Password</a>
        <form method="POST" action="logout.php" style="display: inline;">
            <button type="submit" class="linkaccbutton">Log Out</button>
        </form>
    </div>

    <!-- Kutia për përmbajtjen kryesore -->
    <div class="form-containerA">
        <h1>My Profile</h1>
        <form action="account_settings.php" method="POST">
            <div class="inputs-logIn-acc">
                <input
                    type="text"
                    placeholder="Name"
                    name="name"
                    value="<?php echo htmlspecialchars($name); ?>"
                    required
                />
                <div class="error-message"><?php echo $formErrors['name'] ?? ''; ?></div>
            </div>
            <div class="inputs-logIn-acc">
                <input
                    type="text"
                    placeholder="Phone Number"
                    name="phone"
                    value="<?php echo htmlspecialchars($phone); ?>"
                    required
                />
                <div class="error-message"><?php echo $formErrors['phone'] ?? ''; ?></div>
            </div>
            <div class="inputs-logIn-acc">
                <input
                    type="email"
                    placeholder="Email"
                    name="email"
                    value="<?php echo htmlspecialchars($email); ?>"
                    disabled
                />
            </div>
            <button type="submit" class="acc-button1">Update Account</button>
        </form>
    </div>
</div>

<?php
include 'footer.php';
?>
