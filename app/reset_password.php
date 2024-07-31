<?php
require_once('config/config.php');
require_once('models/db.php');
require_once('models/Classes/User.php');
require_once('models/send_mail.php');
global $connection;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];
    $userId = doesUserExist($email);
    if (doesUserExist($email) === false) {
        header('Location: reset_password.php?error=Account does not exist under email address.');
    } else {
        $user = getUserById($userId);
        if ($user === false) {
            header('Location: reset_password.php?error=Failed to send email link. Please try again or contact support.');
        } else {
            $code = generateVerificationCode($userId);
            $link = ROOT_URL . ROOT_PATH . "/reset_password.php?code=$code&userId=$userId";
            $htmlTemplate = file_get_contents('assets/password_change_template.html');
            $htmlContent = str_replace('{{RESET_PASSWORD_LINK}}', $link, $htmlTemplate);
            $subject = 'Reset Password Link';

            if (sendMail($subject, $htmlContent, $user->email(), $user->name())) {
                header('Location: reset_password.php?success=Reset password link sent to email.');
            } else {
                header('Location: reset_password.php?error=Failed to send email link. Please try again or contact support.');
            }
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['code']) && isset($_GET['userId'])) {
    $userId = $_GET['userId'];
    if (verifyVerificationCode($userId, $_GET['code'])) {
        $user = getUserById($userId);
        if ($user === false) {
            header('Location: index.php?error=Error occurred resetting password.');
            exit();
        }
        // Generate a strong password
        $password = bin2hex(random_bytes(8));
        $user->updatePassword($password);

        // Send the new password to the user via email
        $htmlTemplate = file_get_contents('assets/password_display_template.html');
        $htmlContent = str_replace('{{PASSWORD}}', $password, $htmlTemplate);
        $subject = 'New Password';
        sendMail($subject, $htmlContent, $user->email(), $user->name());
    } else {
        echo 'Invalid code.';
        //header('Location: reset_password.php?error=Invalid code.');
    }
} else {
    // Enter Email Address HTML Form
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Reset Password</title>
        <style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                font-family: Arial, sans-serif;
                background-color: #f0f0f0;
            }
            .container {
                text-align: center;
                background: white;
                padding: 3rem;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            h1 {
                margin-bottom: 2rem;
                font-size: 4rem;
            }
            label, input, button {
                display: block;
                width: 100%;
                margin-bottom: 1rem;
            }
            input[type="email"] {
                padding: 1rem;
                font-size: 2rem;
                border: 1px solid #ccc;
                border-radius: 5px;
                text-align: center;
                box-sizing: border-box;
            }
            button {
                padding: 1rem;
                font-size: 2.8rem;
                color: white;
                background-color: #007BFF;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }
            button:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body>
    <div class="container">
        <h1>Reset Password</h1>
        <form action="reset_password.php" method="post">
            <input type="email" id="email" name="email" placeholder="Enter Email Address:" required>
            <button type="submit">Reset Password</button>
        </form>
    </div>
    </body>
    </html>
    <?php
}
exit();
?>