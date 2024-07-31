<?php
require_once('../config/config.php');
require_once('db.php');
global $connection;
session_start();
session_destroy();
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['userId']) && isset($_GET['code']) && isset($_GET['newEmail'])) {
    $userId = $_GET['userId'];
    $code = $_GET['code'];
    $newEmail = $_GET['newEmail'];
    if (verifyVerificationCode($userId, $code) === false) {
        header('Location: ../index.php?error=Invalid verification code.');
        exit();
    } else {
        $user = getUserById($userId);
        if ($user === false) {
            header('Location: ../index.php?error=Error occurred updating email.');
            exit();
        }
        if ($user->updateEmail($newEmail)) {
            header('Location: ../index.php?success=Email updated successfully. Please login and verify your new email.');
        } else {
            header('Location: ../index.php?error=Failed to update email.');
        }
    }
} else {
    header('Location: ../index.php?error=Invalid verification code.');
}
exit();