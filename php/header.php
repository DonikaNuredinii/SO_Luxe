<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <link rel="stylesheet" href="../assets/style.css">
  </head>
  <body>
    <nav>
      <div class="logo">
        <img src="../images/l.png" alt="Logo" />
      </div>
      <div class="filter-search">
        <form method="GET" action="product.php">
            <input type="text" name="search" placeholder="Search for perfumes..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>"/>
            <button type="submit"></button>
        </form>
      </div>

      <i class="fas fa-bars menu-toggle"></i>
      <ul class="navbar-links">
        <li><a href="home.php">Home</a></li>
        <li><a href="aboutus.php">About us</a></li>
        <li><a href="product.php">Products</a></li>
        <li><a href="contactus.php">Contact us</a></li>
        
        <?php if (isset($_SESSION['user_id'])): ?>
          <?php if ($_SESSION['role'] == 'admin'): ?>
            <li><a href="dashboardpages/dashboard.php">Dashboard</a></li>
          <?php else: ?>
            <div class="icons">
              <a href="account_settings.php" title="Account Settings">
                <i class="fas fa-user-cog"></i>
              </a>
              <a href="cart.php" title="Cart">
                <i class="fas fa-shopping-cart"></i>
              </a>
            </div>
          <?php endif; ?>
          <li>
            <a href="logout.php" title="Logout">
              <i class="fas fa-sign-out-alt"></i> 
            </a>
          </li>
        <?php else: ?>
          <div class="icons">
            <a href="cart.php" title="Cart">
              <i class="fas fa-shopping-cart"></i>
            </a>
            <a href="loginsignup.php" title="Login/Signup">
              <i class="fas fa-user"></i>
            </a>
          </div>
        <?php endif; ?>
      </ul>
    </nav>
  </body>
</html>
