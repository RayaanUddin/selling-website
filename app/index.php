<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RayaanUddin Shop</title>
    <link rel="stylesheet" href="style/style.css">
    <script src="https://kit.fontawesome.com/a7ff570bc3.js" crossorigin="anonymous"></script>
    <script src="javascript/script.js" type="application/javascript" defer></script>
</head>
<body>
<div class="container">
    <?php include('partials/header.php'); ?>
    <main>
        <div class="product-container">
            <?php
            $current_page = $_GET['page'] ?? 1;
            $products = getProductList()[$current_page - 1];
            foreach ($products as $product) {
                ?>
                <div class="product" data-product-description="<?php echo $product->description();?>" data-images="<?php echo htmlspecialchars(json_encode($product->images()));?>">
                    <div class="product-image">
                        <img src="<?php echo $product->images()? $product->images()[0] : 'https://via.placeholder.com/150'; ?>" alt="<?php echo $product->name(); ?>">
                    </div>
                    <div class="product-details">
                        <h2><?php echo $product->name(); ?></h2>
                        <p class="description"><?php echo $product->description(); ?></p>
                        <?php
                        if (count($product->prices()) == 0) {
                            ?>
                            <p class="price">Price not available</p>
                            <?php
                        } elseif (count($product->prices()) == 1) {
                            $price = $product->prices()[0];
                            ?>

                            <?php if ($price->isRecurring()) {
                                $interval = $price->isRecurring()["interval"];
                                if ($price->isRecurring()["interval_count"] > 1) {
                                    $interval .= 's';
                                    $interval = $price->isRecurring()["interval_count"] . ' ' . $interval;
                                }?>
                                <p class="price"><?php echo $price->unitAmount() / 100 . ' ' . $price->currency() . ' per ' . $interval; ?></p>
                                <form action="models/buy_product.php" method="POST">
                                    <input type="hidden" name="priceId" value="<?php echo $price->id(); ?>">
                                    <button type="submit">Subscribe now</button>
                                </form>
                            <?php } else { ?>
                                <p class="price"><?php echo $price->unitAmount() / 100 . ' ' . $price->currency(); ?></p>
                                <form action="models/add_to_cart.php" method="POST">
                                    <input type="hidden" name="priceId" value="<?php echo $price->id(); ?>">
                                    <button type="submit">Add to Basket</button>
                                </form>
                                <form action="models/buy_product.php" method="POST">
                                    <input type="hidden" name="priceId" value="<?php echo $price->id(); ?>">
                                    <button type="submit">Buy now</button>
                                </form>
                            <?php } ?>
                            <?php
                        } else {
                            $productPrices = $product->prices();
                            $prices_toArray = array();
                            for ($i=0; $i<count($productPrices); $i++) {
                                $prices_toArray[$i] = array(
                                    "id" => $productPrices[$i]->id(),
                                    "value" => $productPrices[$i]->unitAmount(),
                                    "currency" => $productPrices[$i]->currency(),
                                    "recurring" => $productPrices[$i]->isRecurring(),
                                );
                            }
                            ?>
                            <p class="price">Price options available</p>
                            <button class="view-options" data-product-prices="<?php echo htmlspecialchars(json_encode($prices_toArray)); ?>">View Options</button>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <!-- Pagination -->
        <div class="pagination">
            <?php if ($current_page > 1): ?>
                <a href="?page=<?php echo $current_page - 1; ?>">&laquo; Previous</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= count(getProductList()); $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $current_page ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
            <?php if ($current_page < count(getProductList())): ?>
                <a href="?page=<?php echo $current_page + 1; ?>">Next &raquo;</a>
            <?php endif; ?>
        </div>
    </main>
    <?php include('partials/footer.php'); ?>
</div>

<!-- Modal -->
<div id="price-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Price Options</h2>
        <div id="price-options"></div>
    </div>
</div>
<div id="product-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="image-container">
            <button id="prev-image">&laquo; Previous</button>
            <div class="product-image"><img id="product-image-modal" src="" alt="Product Image"></div>
            <button id="next-image">Next &raquo;</button>
        </div>
        <p id="product-description"></p>
    </div>
</div>
</body>
</html>
