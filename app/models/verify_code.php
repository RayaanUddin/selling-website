<?php
require_once('../config/config.php');
require_once('db.php');
require_once('Classes/User.php');
global $connection;
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = isset($_SESSION['user']) & $_SESSION['user']->requireVerification() ? $_SESSION['user']->id() : null;
    $inputCodeArray = $_POST['code'];
    $inputCode = implode('', $inputCodeArray);

    if (!isset($userId) || !isset($inputCode)) {
        header('Location: ../index.php');
        exit();
    }
    if (verifyVerificationCode($userId, $inputCode)) {
        $_SESSION['user']->verifyUser();
        header('Location: ../index.php');
    } else {
        header('Location: ../verification.php?error=Invalid verification code (may have expired). Request a new one.');
    }
    exit();
}
?>
