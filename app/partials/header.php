<?php
require_once('config/config.php');
require_once('models/stripe/Cart.php');
require_once('models/stripe/Price.php');
require_once('models/stripe/Product.php');
require_once('models/stripe/Customer.php');
require_once('models/Classes/User.php');
session_start();

$_SESSION['cart'] = $_SESSION['cart'] ?? new Cart();
?>

<header>
    <div class="logo">
        <img src="assets/logo.png" alt="Rayaan Uddin">
    </div>
    <nav class="menu">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="index.php">Products</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Contact Us</a></li>
        </ul>
    </nav>
    <div class="header-icons">
        <div class="login">
            <?php if (isset($_SESSION['user']) && $_SESSION['user']->isLoggedIn()): ?>
                <a href="models/logout.php">Logout</a>
            <?php else: ?>
                <i class="fa-solid fa-user" id="loginIcon"></i>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
        <div class="basket" id="cart">
            <i class="fa-solid fa-basket-shopping" id="basketIcon"></i>
            <span id="basketCount"><?php echo count($_SESSION['cart']->getCart()) ?></span>
        </div>
        <?php if (isset($_SESSION['user']) && $_SESSION['user']->isLoggedIn()): ?>
            <div id="account">
                <i class="fa-solid fa-user" id="accountIcon"></i>
            </div>
        <?php endif; ?>
    </div>
</header>
<?php if (isset($_GET['success']) || isset($_GET['error'])): ?>
    <div class="success-error">
        <?php echo isset($_GET['success']) ? "<p class='success'>" . $_GET['success'] . "</p>" : "" ?>
        <?php echo isset($_GET['error']) ? "<p class='error'>" . $_GET['error'] . "</p>" : "" ?>
    </div>
<?php endif; ?>
