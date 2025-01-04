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
        <input type="text" placeholder="Search for perfumes..." />
      </div>
      <i class="fas fa-bars menu-toggle"></i>
      <ul class="navbar-links">
        <li><a href="home.php">Home</a></li>
        <li><a href="aboutus.php">About us</a></li>
        <li><a href="product.php">Products</a></li>
        <li><a href="contactus.php">Contact us</a></li>
        <div class="icons">
          <i class="fas fa-shopping-cart"></i>
          <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="loginsignup.php"> <i class="fas fa-user"></i>  </a> 
          <?php endif; ?>
        </div>
        <?php if (isset($_SESSION['user_id'])): ?>
          <?php if ($_SESSION['role'] == 'admin'): ?>
            <li><a href="dashboardpages/dashboard.php">Dashboard</a></li>
          <?php endif; ?>
          <li><a href="logout.php">Logout</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </body>
</html>
