<?php
require_once('config/config.php');
$stripe = new \Stripe\StripeClient(STRIPE_PUBLISHABLE_KEY);

try {
    session_start();
    unset($_SESSION['cart']);
    $session = $stripe->checkout->sessions->retrieve($_GET['session_id']);
    $customerName = isset($session->customer) ? $stripe->customers->retrieve($session->customer)->name : $session->customer_details->name;
    $paymentIntent = $session->payment_intent;
    $paymentStatus = $session->payment_status;
    $thanksMessage = $paymentStatus == 'paid' ? "Thank you for your purchase!" : "There was an issue with your payment.";
} catch (Error $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
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
        .order-number-box {
            background: #eee;
            padding: 10px;
            border-radius: 8px;
            margin-top: 10px;
            position: relative;
            display: inline-block;
            width: calc(100% - 40px);
        }
        .copy-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: #007bff;
        }
        .copy-button:hover .tooltip {
            visibility: visible;
            opacity: 1;
        }
        .tooltip {
            visibility: hidden;
            width: 40px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 4px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%; /* Position above the button */
            left: 50%;
            margin-left: -20px;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .tooltip::after {
            content: "";
            position: absolute;
            top: 100%; /* Bottom of the tooltip */
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #555 transparent transparent transparent;
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
    <h1><?php echo $thanksMessage; ?></h1>
    <p><?php echo $paymentStatus == 'paid' ? "Dear $customerName, your payment was successful. A confirmation email has been sent to you." : "Dear $customerName, your payment was not successful. Please try again."; ?></p>
    <?php if ($paymentStatus == 'paid'): ?>
        <div class="order-number-box">
            <p>Order Number: <span id="order-number"><?php echo $paymentIntent; ?></span></p>
            <button class="copy-button" onclick="copyOrderNumber()">
                <i class="fa fa-copy"></i>
                <span class="tooltip">Copy</span>
            </button>
        </div>
    <?php endif; ?>
    <a href="index.php" class="home-button">Return to Home Page</a>
</div>
<script>
    function copyOrderNumber() {
        const orderNumberElement = document.getElementById('order-number');
        const tempInput = document.createElement('input');
        document.body.appendChild(tempInput);
        tempInput.value = orderNumberElement.textContent;
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        alert('Order number copied to clipboard');
    }
</script>
</body>
</html>
