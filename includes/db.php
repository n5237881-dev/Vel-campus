<?php
error_reporting(0);
ini_set('display_errors', 0);

define('DB_HOST', 'localhost:3307');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'campus_events');

// Admin credentials
define('ADMIN_ROLL', 'admin');
define('ADMIN_PASS', 'admin');

function getConnection() {
    $conn = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        header('Content-Type: application/json');
        die(json_encode(['success' => false, 'message' => 'DB connection failed: ' . $conn->connect_error]));
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}
?>
