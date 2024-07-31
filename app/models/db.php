<?php
date_default_timezone_set('UTC');
// Connect to the database
$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check the connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

function generateVerificationCode($userId): int {
    global $connection;
    $code = rand(100000, 999999);
    // Delete any existing verification codes of userId
    $sql = "DELETE FROM verification_codes WHERE userId = '$userId'";
    $connection->query($sql);

    // Insert the new verification code
    $date_now = date('Y-m-d H:i:s');
    $sql = "INSERT INTO verification_codes (userId, code, created) VALUES ('$userId', '$code', '$date_now')";
    $connection->query($sql);
    return $code;
}

function verifyVerificationCode($userId, $code): bool {
    global $connection;
    $sql = "SELECT * FROM verification_codes WHERE userId = '$userId' AND code = '$code'";
    $result = $connection->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $date_now = date('Y-m-d H:i:s');
        $diff = strtotime($date_now) - strtotime($row['created']);
        // Delete the verification code
        $sql = "DELETE FROM verification_codes WHERE userId = '$userId'";
        $connection->query($sql);
        if ($diff > CODE_EXPIRE_TIME) {
            echo $diff;
            echo $date_now;
            return false;
        }
        return true;
    }
    return false;
}