<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basket</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/basket.css">
    <script src="https://kit.fontawesome.com/a7ff570bc3.js" crossorigin="anonymous"></script>
    <script src="javascript/script.js" defer></script>
</head>
<body>
<div class="container">
    <?php include('partials/header.php'); ?>
    <main>
        <h2>Your Basket</h2>
        <div class="basket-container">
            <?php
            $cart_items = $_SESSION['cart']->getCart();
            if (isset($cart_items) && count($cart_items) > 0) {
                foreach ($cart_items as $priceId => $quantity) {
                    $price = new Price($priceId);
                    $product = $price->product();
                    $image = $product->images() ? $product->images()[0] : 'https://via.placeholder.com/150';
                    echo "<div class='basket-item'>
                                <img src='{$image}' alt='{$product->name()}'>
                                <div class='item-details'>
                                    <span class='item-name'>{$product->name()}</span>
                                    <span class='item-price'>{$price->unitAmount()} {$price->currency()}</span>
                                </div>
                                <form class='update-form' action='models/update_basket.php' method='POST'>
                                    <input type='number' name='quantity' value='{$quantity}' min='1'>
                                    <input type='hidden' name='priceId' value='{$priceId}'>
                                    <button type='submit'>Update</button>
                                </form>
                                <form class='delete-form' action='models/remove_from_basket.php' method='POST'>
                                    <input type='hidden' name='priceId' value='{$priceId}'>
                                    <button type='submit'>Delete</button>
                                </form>
                            </div>";
                }
                echo "<a class='checkout-button' href='models/buy_cart.php'>Checkout</a>";
            } else {
                echo "<p>Your basket is empty.</p>";
            }
            ?>
        </div>
    </main>
    <?php include('partials/footer.php'); ?>
</div>
</body>
</html>
