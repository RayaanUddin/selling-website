<?php
include "Classes/User.php";
function nameCapitalize($name) {
    return ucfirst(strtolower($name));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['lname']) && isset($_POST['fname'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $lname = nameCapitalize($_POST['lname']);
    $fname = nameCapitalize($_POST['fname']);
    if (isset($_POST['allow-junk-mail'])) {
        $allowJunkMail = true;
    } else {
        $allowJunkMail = false;
    }
    $user = createUser($email, $password, $fname, $lname, $allowJunkMail);
    if ($user) {
        session_start();
        $_SESSION['user'] = $user;
        header('Location: ../verification.php');
    } else {
        header('Location: ../signup.php?error=Invalid email or password, or email already exists.');
    }
    exit();
} else {
    header('Location: ../signup.php?error=Invalid email or password');
    exit();
}