<?php
$stripe = new \Stripe\StripeClient(STRIPE_PUBLISHABLE_KEY);

class Cart {
    private $cart = array();

    public function __construct($cart = null) {
        if ($cart !== null) {
            $this->cart = $cart;
        }
    }

    public function addProduct($productPriceId, $quantity = 1) {
        if (array_key_exists($productPriceId, $this->cart)) {
            $this->cart[$productPriceId] += $quantity;
            return;
        }
        $this->cart[$productPriceId] = $quantity;
    }

    public function updateProduct($productPriceId, $quantity) {
        if (!array_key_exists($productPriceId, $this->cart)) {
            return;
        }
        $this->cart[$productPriceId] = $quantity;
    }

    public function removeProduct($productPriceId, $quantity = null) {
        if (!array_key_exists($productPriceId, $this->cart)) {
            return;
        }
        if ($quantity === null) {
            unset($this->cart[$productPriceId]);
            return;
        }
        $this->cart[$productPriceId] -= $quantity;
        if ($this->cart[$productPriceId] <= 0) {
            unset($this->cart[$productPriceId]);
        }
    }

    public function getCart(): array {
        return $this->cart;
    }

    public function pay($customer = null, $mode = 'payment') {
        global $stripe;

        // Reformat the cart array to match the expected format
        $temp_cart = array_map(function($priceId, $quantity) {
            return [
                'price' => $priceId,
                'quantity' => $quantity
            ];
        }, array_keys($this->cart), array_values($this->cart));

        if ($customer == null) {
            $checkout_session = $stripe->checkout->sessions->create([
                'line_items' => $temp_cart,
                'mode' => $mode,
                'success_url' => CHECKOUT_SUCCESS_URL . "?session_id={CHECKOUT_SESSION_ID}",
                'cancel_url' => CHECKOUT_CANCEL_URL,
            ]);
        } else {
            $checkout_session = $stripe->checkout->sessions->create([
                'line_items' => $temp_cart,
                'customer' => $customer,
                'mode' => $mode,
                'invoice_creation' => ['enabled' => true],
                'success_url' => CHECKOUT_SUCCESS_URL . "?session_id={CHECKOUT_SESSION_ID}",
                'cancel_url' => CHECKOUT_CANCEL_URL . "?session_id={CHECKOUT_SESSION_ID}",
            ]);
        }
        header("HTTP/1.1 303 See Other");
        header("Location: " . $checkout_session->url);
    }

    public function __toString() {
        return json_encode($this->cart, JSON_PRETTY_PRINT);
    }
}

