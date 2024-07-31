<?php
require_once ('../config/config.php');
require_once('stripe/cart.php');
require ('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['priceId'])) {
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart']->removeProduct($_POST['priceId']);
    }
    header('Location: ../view_cart.php');
    exit();
} else {
    header('Location: ../view_cart.php');
    exit();
}
