<?php
require_once('config/config.php');
if (!isset($_GET['session_id'])) {
    header('Location: index.php');
    exit();
}
$stripe = new \Stripe\StripeClient(STRIPE_PUBLISHABLE_KEY);
$stripe->checkout->sessions->expire(
    $_GET['session_id'],
    []
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Canceled</title>
    <link rel="stylesheet" href="style/style.css">
    <script src="https://kit.fontawesome.com/a7ff570bc3.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        h1 {
            color: #333;
        }
        p {
            color: #666;
        }
        .home-button {
            display: block;
            margin-top: 20px;
            padding: 10px 0;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
            text-align: center;
        }
        .home-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Payment Canceled</h1>
    <p>Your payment has been canceled. If this was a mistake, you can try again or contact support for assistance.</p>
    <a href="index.php" class="home-button">Return to Home Page</a>
</div>
</body>
</html>
