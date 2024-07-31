<?php
require_once('../config/config.php');
require ('stripe/Cart.php');
require ('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $priceId = $_POST['priceId'];
    $quantity = $_POST['quantity'] ?? 1;

    // Add the product to the cart
    $_SESSION['cart'] = $_SESSION['cart'] ?? new Cart();
    $_SESSION['cart']->addProduct($priceId, $quantity);

    // Redirect back to the main page
    header('Location: ../index.php');
    exit();
}
