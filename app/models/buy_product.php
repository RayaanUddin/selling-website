<?php
require_once ('../config/config.php');
require_once('stripe/Cart.php');
require_once ('Classes/User.php');
require_once('stripe/Customer.php');
require_once ('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $priceId = $_POST['priceId'];
    $quantity = $_POST['quantity'] ?? 1;

    // Create a temporary cart
    $cart = new Cart();
    $cart->addProduct($priceId, $quantity);

    try {
        $cart->pay(isset($_SESSION['user'])? $_SESSION['user']->customer()->id() : null);
    } catch (Exception $e) {
        $cart->pay(isset($_SESSION['user'])? $_SESSION['user']->customer()->id() : null,  'subscription');
    }
} else {
    header('Location: ../index.php');
    exit();
}
