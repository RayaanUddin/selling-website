<?php
include "Classes/User.php";
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    // Hash the password
    $password = hash('sha256', $password);
    $user = new User($email, $password);
    if ($user->isLoggedIn() == true) {
        $_SESSION['user'] = $user;
        header('Location: ../index.php');
        exit();
    } elseif ($user->requireVerification() == true) {
        $_SESSION['user'] = $user;
        header('Location: ../verification.php');
        exit();
    } else {
        header('Location: ../login.php?error=Invalid email or password');
        exit();
    }
} else {
    header('Location: ../login.php?error=Invalid email or password');
    exit();

}