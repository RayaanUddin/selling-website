<?php
require_once ('../config/config.php');
require_once('stripe/Cart.php');
require ('db.php');
require_once ('Classes/User.php');
require_once('stripe/Customer.php');
session_start();

if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
    $cart->pay(isset($_SESSION['user'])? $_SESSION['user']->customer()->id() : null);
} else {
    header('Location: ../view_cart.php');
    exit();
}
