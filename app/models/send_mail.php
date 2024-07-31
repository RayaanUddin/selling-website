<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendMail($subject, $body, $userEmail, $userName): bool {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'ssl0.ovh.net';
        $mail->SMTPAuth = true;
        $mail->Username = 'no-reply@rayaanuddin.com';
        $mail->Password = 'Test1234!';
        $mail->SMTPSecure = 'starttls';
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('no-reply@rayaanuddin.com', 'Rayaan Uddin');
        $mail->addAddress($userEmail, $userName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;

        // Load the HTML template

        $mail->Body = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    return false;
}