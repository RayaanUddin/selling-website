<?php
require_once('config/config.php');
require_once('models/Classes/User.php');
require_once('models/db.php');
require_once ('models/send_mail.php');
session_start();

$userId = isset($_SESSION['user']) && $_SESSION['user']->requireVerification() ? $_SESSION['user']->id() : header('Location: login.php');

if (isset($_POST['resend'])) {
    $code = generateVerificationCode($userId);
    if ($code) {
        // Send the verification code to the user's email
        $htmlTemplate = file_get_contents('assets/email_verification_template.html');
        $htmlContent = str_replace('{{CODE}}', $code, $htmlTemplate);
        $subject = 'Your Verification Code';
        sendMail($subject, $htmlContent, $_SESSION['user']->email(), $_SESSION['user']->name());
    } else {
        header('Location: verification.php?error=Failed to send verification code');
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification</title>
    <link rel="stylesheet" href="style/reset.css">
    <link rel="stylesheet" href="style/verification.css">
</head>
<body>
<div class="verification-container">
    <h2>Verification Required</h2>
    <p>Please enter the 6-digit verification code sent to your email.</p>
    <form id="verificationForm" action="models/verify_code.php" method="POST">
        <div class="wrap-input">
            <input type="text" id="code1" name="code[]" maxlength="1" required>
            <input type="text" id="code2" name="code[]" maxlength="1" required>
            <input type="text" id="code3" name="code[]" maxlength="1" required>
            <input type="text" id="code4" name="code[]" maxlength="1" required>
            <input type="text" id="code5" name="code[]" maxlength="1" required>
            <input type="text" id="code6" name="code[]" maxlength="1" required>
        </div>
        <p class="error"><?php echo $_GET['error'] ?? '' ?></p>
        <p class="success"><?php echo isset($_POST["resend"]) ? "Verification code sent" : '' ?></p>
        <input type="submit" value="Verify">
    </form>
    <form action="verification.php" method="POST">
        <button type="submit" class="button-style" id="send-code" name="resend" value=<?php echo isset($_POST["resend"]) ? "resend" : "" ?> > <?php echo isset($_POST["resend"]) ? "Resend Code" : "Send Code" ?></button>
    </form>
    <a class="button-style" href="index.php">Go to Home</a>
</div>
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const inputs = document.querySelectorAll('.wrap-input input');
        inputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                if (input.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });
        });
        // if the value of send-code is Resend Code, add a timer to prevent spamming
        const sendCode = document.getElementById('send-code');
        if (sendCode.value === 'resend') {
            sendCode.disabled = true;
            let timeLeft = 60;
            const timer = setInterval(() => {
                timeLeft--;
                sendCode.innerText = `Resend Code (${timeLeft})`;
                if (timeLeft === 0) {
                    clearInterval(timer);
                    sendCode.innerText = 'Resend Code';
                    sendCode.disabled = false;
                }
            }, 1000);
        }

    });
</script>
</body>
</html>
