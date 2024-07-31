<?php
require_once('../config/config.php');
require_once('../models/stripe/Product.php');

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$productId = $input['productId'];

$product = new Product($productId);

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

echo json_encode($prices_toArray);
