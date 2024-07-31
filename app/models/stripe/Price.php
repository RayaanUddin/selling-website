<?php
$stripe = new \Stripe\StripeClient(STRIPE_PUBLISHABLE_KEY);

class Price
{
    private $data;

    public function __construct($data)
    {
        global $stripe;
        if (gettype($data) == "string") {
            $data = $stripe->prices->retrieve($data, []);
        }
        $this->data = $data;
        return $this;
    }

    public function id()
    {
        return $this->data['id'];
    }

    public function unitAmount()
    {
        return $this->data['unit_amount'];
    }

    public function currency()
    {
        return $this->data['currency'];
    }

    public function isRecurring()
    {
        if ($this->data["type"] == "recurring" && isset($this->data['recurring'])) {
            return array(
                "interval" => $this->data['recurring']['interval'],
                "interval_count" => $this->data['recurring']['interval_count']
            );
        } else {
            return false;
        }
    }

    public function product(): Product{
        return new Product($this->data['product']);
    }

    public function __toString()
    {
        if (isset($this->data)) {
            return $this->data;
        }
        return null;
    }
}