<?php
require_once('../config/config.php');
require_once('db.php');
require_once('Classes/User.php');
require_once('send_mail.php');
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['userId']) && isset($_GET['code'])) {
    $userId = $_GET['userId'];
    $code = $_GET['code'];
    if (verifyVerificationCode($userId, $code) === false) {
        header('Location: ../index.php?error=Invalid verification code.');
        exit();
    } else {
        $user = getUserById($userId);
        if ($user === false) {
            header('Location: ../index.php?error=Error occurred deleting account.');
            exit();
        }
        if ($user->delete()) {
            header('Location: ../index.php?success=Account deleted successfully.');
        } else {
            header('Location: ../index.php?error=Failed to delete account.');
        }
    }
} else {
    session_start();
    if (isset($_SESSION['user']) && $_SESSION['user']->isLoggedIn()) {
        $code = generateVerificationCode($_SESSION['user']->id());
        if ($code) {
            $link = ROOT_URL . ROOT_PATH . "/models/delete_account.php?code=$code&userId=" . $_SESSION['user']->id();
            $htmlTemplate = file_get_contents('../assets/delete_account_template.html');
            $htmlContent = str_replace('{{DELETE_ACCOUNT_LINK}}', $link, $htmlTemplate);
            $subject = 'Delete Account Link';
            sendMail($subject, $htmlContent, $_SESSION['user']->email(), $_SESSION['user']->name());
            session_destroy();
        } else {
            header('Location: ../account.php?error=Failed to send email link. Please try again.');
        }
        header('Location: ../account.php?success=Delete account link sent to current email.');
    } else {
        header('Location: ../index.php?error=Invalid verification code.');
    }
}