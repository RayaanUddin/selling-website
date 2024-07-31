<?php

require_once (dirname(dirname(dirname(__FILE__))) . '/config/config.php');
require_once (dirname(dirname(dirname(__FILE__))) . '/models/stripe/Customer.php');
require_once (dirname(dirname(__FILE__)) . '/db.php');

class User {
    private $id;
    private $customer;
    private $email;
    private $fname;
    private $lname;
    private $permission;
    private $allowJunkMail;
    private bool $accountVerified = false;

    public function __construct($email, $password) {
        global $connection;
        $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
        $result = $connection->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (isset($row['verify']) && $row['verify']) {
                $this->accountVerified = true;
            }
            $this->id = $row['id'];
            $this->email = $row['email'];
            $this->fname = $row['fname'];
            $this->lname = $row['lname'];
            $this->permission = $row['permission'];
            $this->allowJunkMail = $row['allowJunkMail'];
            $this->customer = new Customer($this->email, $this->fname . ' ' . $this->lname, $row['customerId']);
            if ($this->customer->id() != $row['customerId']) {
                // Update the customerId in the database
                $sql = "UPDATE users SET customerId = '" . $this->customer->id() . "' WHERE id = '" . $this->id . "'";
                $connection->query($sql);
            }
            return $this;
        }
        return false;
    }

    public function id(): int {
        return $this->id;
    }

    public function name(): String {
        return $this->fname . ' ' . $this->lname;
    }

    public function firstName(): String {
        return $this->fname;
    }

    public function lastName(): String {
        return $this->lname;
    }

    public function updateName($fname, $lname): bool {
        global $connection;
        $sql = "UPDATE users SET fname = '$fname', lname = '$lname' WHERE id = '$this->id'";
        if ($connection->query($sql) === TRUE) {
            $this->fname = $fname;
            $this->lname = $lname;
            $this->customer->updateName($fname . ' ' . $lname);
            return true;
        }
        return false;
    }

    public function email(): String {
        return $this->email;
    }

    public function updateEmail($email): bool {
        global $connection;
        $sql = "UPDATE users SET email = '$email' WHERE id = '$this->id'";
        if ($connection->query($sql) === TRUE) {
            $this->email = $email;
            $this->customer->updateEmail($email);
            return true;
        }
        return false;
    }

    public function permissionLevel(): int {
        return $this->permission;
    }

    public function allowJunkMail(): bool {
        return $this->allowJunkMail;
    }

    public function requireVerification(): bool {
        if (isset($this->id) && !$this->accountVerified) {
            return true;
        }
        return false;
    }

    public function verifyPassword($password): bool {
        global $connection;
        $password = hash('sha256', $password);
        $sql = "SELECT * FROM users WHERE id = '$this->id' AND password = '$password'";
        $result = $connection->query($sql);
        if ($result->num_rows > 0) {
            return true;
        }
        return false;
    }

    public function updatePassword($password): bool {
        global $connection;
        $password = hash('sha256', $password);
        $sql = "UPDATE users SET password = '$password' WHERE id = '$this->id'";
        if ($connection->query($sql) === TRUE) {
            return true;
        }
        return false;
    }

    public function verifyUser(): bool {
        global $connection;
        $sql = "UPDATE users SET verify = 1 WHERE id = '$this->id'";
        if ($connection->query($sql) === TRUE) {
            $this->accountVerified = true;
            // Delete the verification code
            $sql = "DELETE FROM verification_codes WHERE userId = '$this->id'";
            $connection->query($sql);
            return true;
        }
        return false;
    }

    public function isLoggedIn(): bool {
        return isset($this->id) && $this->accountVerified;
    }

    public function customer(): Customer {
        return $this->customer;
    }

    public function delete(): bool {
        global $connection;
        $sql = "DELETE FROM users WHERE id = '$this->id'";
        if ($connection->query($sql) === TRUE) {
            $this->customer->delete();
            return true;
        }
        return false;
    }
}

function doesUserExist($email) {
    global $connection;
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = $connection->query($sql);
    if ($result->num_rows > 0) {
        // User exists; Return its id read from the database
        return $result->fetch_assoc()['id'];
    }
    return false;
}

function getUserById($id) {
    global $connection;
    $sql = "SELECT email, password FROM users WHERE id = '$id'";
    $result = $connection->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return new User($row['email'], $row['password']);
    }
    return false;
}

function createUser($email, $password, $fname, $lname, $allowJunkMail=0, $permission=0) {
    global $connection;
    if (doesUserExist($email) !== false) {
        return false;
    }
    // Hash the password
    $password = hash('sha256', $password);
    $sql = "INSERT INTO users (email, password, fname, lname, permission, allowJunkMail) VALUES ('$email', '$password', '$fname', '$lname', $permission, $allowJunkMail)";
    if ($connection->query($sql) === TRUE) {
        return new User($email, $password);
    }
    return false;
}