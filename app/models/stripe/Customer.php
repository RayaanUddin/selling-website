<?php

$stripe = new \Stripe\StripeClient(STRIPE_PUBLISHABLE_KEY);

class Customer {
    private $data;

    public function __construct($email, $name, $id=null) {
        global $stripe;
        try {
            $this->data = $stripe->customers->retrieve($id);
            // if name does not match, update the name
            if ($this->data['name'] != $name) {
                $this->data = $stripe->customers->update($id, [
                    'name' => $name
                ]);
            }
        } catch (Exception $e) {
            $data = $stripe->customers->search([
                'query' => 'email:\'' . $email . '\'',
            ])["data"][0];
            if (!(isset($data))) {
                $data = $stripe->customers->create([
                    'email' => $email,
                    'name' => $name
                ]);
            }
            $this->data = $data;
        }
    }

    public function id() {
        return $this->data['id'];
    }

    public function name() {
        return $this->data['name'];
    }

    public function email() {
        return $this->data['email'];
    }

    public function __toString() {
        if (isset($this->data)) {
            return $this->data;
        }
        return null;
    }

    public function updateEmail($email): bool {
        global $stripe;
        try {
            $this->data = $stripe->customers->update($this->id(), [
                'email' => $email
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateName(string $string): bool {
        global $stripe;
        try {
            $this->data = $stripe->customers->update($this->id(), [
                'name' => $string
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete(): bool {
        global $stripe;
        try {
            $stripe->customers->delete($this->id(), []);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}