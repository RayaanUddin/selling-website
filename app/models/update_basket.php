<?php
require_once ('../config/config.php');
require_once('stripe/cart.php');
require ('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['priceId']) && isset($_POST['quantity'])) {
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart']->updateProduct($_POST['priceId'], $_POST['quantity']);
    }
    header('Location: ../view_cart.php');
    exit();
} else {
    header('Location: ../view_cart.php');
    exit();
}
