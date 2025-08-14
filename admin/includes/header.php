<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check login status
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agronomy Farms | Agricultural Products Marketplace</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="container">
            <div class="top-header">
                <div class="logo">
                    <i class="fas fa-tractor"></i>
                    <span>Agronomy Farms Company Limited</span>
                </div>
                
                <form class="search-bar" method="GET" action="index.php">
                    <input type="text" name="search" placeholder="Search for seeds, equipment, fertilizers..." >
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
                
                <div class="user-actions">
                    <?php
                      if (session_status() === PHP_SESSION_NONE) {
                          session_start();
                      }

                      $cartCount = 0;
                      if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                          foreach ($_SESSION['cart'] as $qty) {
                              $cartCount += $qty;
                          }
                      }
                      ?>

                      <div class="cart-icon" id="cart-icon">
                          <a href="cart.php" style="color: white;">
                              <i class="fas fa-shopping-cart"></i>
                              <span class="cart-count"><?= $cartCount ?></span>
                          </a>
                      </div>

                    <?php if ($isLoggedIn): ?>
                        <!-- Show logged-in user name and logout -->
                        <div class="account">
                            <i class="fas fa-user-circle"></i>
                            <span><?php echo htmlspecialchars($userName); ?></span>
                        </div>
                        <a href="./logout.php" style="color: white;">
                            <i class="fas fa-sign-out-alt"></i>Logout
                        </a>
                    <?php else: ?>
                        <!-- Show login/signup if not logged in -->
                        <div class="account">
                            <a href="login.php" style="color: white;">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </div>
                        <div class="account">
                            <a href="registration.php " style="color: white;">
                            <i class="fas fa-user-circle"></i>Create account
                           <!--- <span>Create Account</span>-->
                            </a>
                        </div>
                    <?php endif; ?>
                    
                </div>
            </div>
            
            <div class="main-nav">
                <ul class="nav-links">
                    <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="buyer-hub.php"><i class="fas fa-shopping-cart"></i> Buyer Hub</a></li>
                    <li><a href="help-center.php"><i class="fas fa-question-circle"></i> Help Center</a></li>
                </ul>
                
                <div class="assurance-banner">
                    <i class="fas fa-shield-alt"></i> Agronomy Assurance: Secure payments and quality guarantees from farm to delivery
                </div>
            </div>
        </div>
    </header>
