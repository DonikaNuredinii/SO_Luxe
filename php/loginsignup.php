<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Sign Up</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="container-auth">
    <div class="form-box form-box-login" id="loginForm">
        <h1>Login</h1>
        <form action="login.php" method="POST">
            <div class="input-group">
                <label for="email-login">Email</label>
                <input type="email" id="email-login" name="email" required>
            </div>
            <div class="input-group">
                <label for="password-login">Password</label>
                <input type="password" id="password-login" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
            <p class="switch">Don't have an account? <a href="#" id="showSignup">Sign Up</a></p>
        </form>
    </div>

    <div class="form-box form-box-signup hidden" id="signupForm">
        <h1>Sign Up</h1>
        <form action="signup.php" method="POST">
            <div class="input-group">
                <label for="name-signup">Full Name</label>
                <input type="text" id="name-signup" name="name" required>
            </div>
            <div class="input-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
            <div class="input-group">
                <label for="email-signup">Email</label>
                <input type="email" id="email-signup" name="email" required>
            </div>
            <div class="input-group">
                <label for="password-signup">Password</label>
                <input type="password" id="password-signup" name="password" required>
            </div>
            <div class="input-group">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" id="confirm-password" name="confirm-password" required>
                </div>
                <div class="input-group terms-group">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">
                        I agree to the <a href="#" class="terms-link">Terms and Conditions</a>
                    </label>
                </div>
                
            <button type="submit" class="btn">Sign Up</button>
            <p class="switch">Already have an account? <a href="#" id="showLogin">Login</a></p>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>
