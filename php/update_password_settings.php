<?php
include 'header.php';
?>
<div class="container-accountsettings">
    <div class="sidebar-acc">
        <a href="account_settings.php" class="linkacc">Profile</a>
        <a href="update_password_settings.php" class="linkacc">Change Password</a>
        <form method="POST" action="logout.php" style="display: inline;">
            <button type="submit" class="linkaccbutton">Log Out</button>
        </form>
    </div>
    <div class="form-containerA">
        <h1>Update Password</h1>
        <?php if (isset($_SESSION['error'])): ?>
            <script>
                alert('<?php echo $_SESSION['error']; ?>');
                window.location.href = 'update_password_settings.php';
            </script>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <script>
                alert('<?php echo $_SESSION['success']; ?>');
                window.location.href = 'update_password_settings.php';
            </script>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form action="update_password.php" method="POST">
            <div class="inputs-logIn-acc">
                <input
                    type="password"
                    name="currentPassword"
                    placeholder="Current Password"
                    required />
            </div>
            <div class="inputs-logIn-acc">
                <input
                    type="password"
                    name="newPassword"
                    placeholder="New Password"
                    required />
            </div>
            <div class="inputs-logIn-acc">
                <input
                    type="password"
                    name="confirmPassword"
                    placeholder="Confirm New Password"
                    required />
            </div>
            <button type="submit" class="acc-button1">Update Password</button>
        </form>
    </div>
</div>
<?php
include 'footer.php';
?>