<?php
require_once('Price.php');
$stripe = new \Stripe\StripeClient(STRIPE_PUBLISHABLE_KEY);

class Product {
    private $data;
    private $prices;

    public function __construct($data)
    {
        global $stripe;
        if (gettype($data) == "string") {
            $data = $stripe->products->retrieve($data, []);
        }
        $this->data = $data;
        return $this;
    }

    public function id(): string
    {
        return $this->data['id'];
    }

    public function name(): string
    {
        return $this->data['name'];
    }

    public function description(): string
    {
        return $this->data['description'];
    }

    public function images(): array
    {
        return $this->data['images'];
    }

    public function prices(): array
    {
        global $stripe;
        if (!isset($this->prices)) {
            $prices = $stripe->prices->search([
                'query' => 'active:\'true\' AND product:\'' . $this->id() . '\'',
            ])["data"];
            if (isset($prices) && gettype($prices) == "array") {
                for ($i = 0; $i < count($prices); $i++) {
                    $prices[$i] = new Price($prices[$i]);
                }
                $this->prices = $prices;
            }
        }
        return $this->prices;
    }

    public function __toString()
    {
        return $this->data;
    }
}

function getProductList(): array {
    global $stripe;
    $products = array();
    $current_products_pointer = $stripe->products->all(['limit' => PRODUCT_LIMIT]);
    $page = 0;
    for ($i = 0; $i < count($current_products_pointer->data); $i++) {
        $products[$page][$i] = new Product($current_products_pointer->data[$i]);
    }
    while ($current_products_pointer->has_more) {
        $page++;
        $current_products_pointer = $stripe->products->all([
            'limit' => PRODUCT_LIMIT,
            'starting_after' => $current_products_pointer->data[count($current_products_pointer->data) - 1]->id
        ]);
        for ($i = 0; $i < count($current_products_pointer->data); $i++) {
            $products[$page][$i] = new Product($current_products_pointer->data[$i]);
        }
    }
    return $products;
}