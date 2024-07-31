<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once('../config/config.php');
    require_once('db.php');
    require_once('Classes/User.php');
    require_once ('send_mail.php');
    global $connection;
    session_start();

    if (!(isset($_SESSION['user']) && $_SESSION['user']->isLoggedIn())) {
        header('Location: ../login.php&error=Please login first.');
        exit();
    }

    if (isset($_POST["current_password"])) {
        $currentPassword = $_POST["current_password"];
        $newPassword = $_POST["new_password"];
        $confirmPassword = $_POST["confirm_password"];
        if ($newPassword != $confirmPassword) {
            header('Location: ../account.php?error=New password and confirm password do not match.');
            exit();
        }

        if ($_SESSION['user']->verifyPassword($currentPassword)) {
            $_SESSION['user']->updatePassword($newPassword);
            header('Location: ../account.php?success=Password updated successfully.');
        } else {
            header('Location: ../account.php?error=Current password is incorrect.');
        }
        exit();
    } elseif (isset($_POST["email"])) {
        $email = $_POST["email"];
        if (doesUserExist($email) !== false) {
            header('Location: ../account.php?error=Account already exist under email address.');
            exit();
        }
        $code = generateVerificationCode($_SESSION['user']->id());
        if ($code) {
            $link = ROOT_URL . ROOT_PATH . "/models/change_email.php?code=$code&newEmail=$email&userId=" . $_SESSION['user']->id();
            $htmlTemplate = file_get_contents('../assets/email_change_template.html');
            $htmlContent = str_replace('{{CHANGE_EMAIL_LINK}}', $link, $htmlTemplate);
            $subject = 'Change Email Link';
            sendMail($subject, $htmlContent, $_SESSION['user']->email(), $_SESSION['user']->name());
        } else {
            header('Location: ../account.php?error=Failed to send email link. Please try again.');
        }
        header('Location: ../account.php?success=Email change link sent to current email.');
        exit();
    } elseif (isset($_POST["first_name"]) || isset($_POST["last_name"])) {
        $firstName = $_POST["first_name"] ?? $_SESSION['user']->firstName();
        $lastName = $_POST["last_name"] ?? $_SESSION['user']->lastName();
        if ($_SESSION['user']->updateName($firstName, $lastName)) {
            header('Location: ../account.php?success=Name updated successfully.');
        } else {
            header('Location: ../account.php?error=Failed to update name.');
        }
        exit();
    }
}
?>