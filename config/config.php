<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/error.log');

// Create logs directory if it doesn't exist
if (!file_exists(__DIR__ . '/../logs')) {
    mkdir(__DIR__ . '/../logs', 0755, true);
}

// Debug mode
if (!defined('DEBUG')) {
    define('DEBUG', true);
}

// Database configuration
$servername = "localhost";
$username = "root";
$password = ""; // hoặc "" nếu không có mật khẩu
$dbname = "shopelectrics";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    $error_message = "Kết nối thất bại: " . $conn->connect_error;
    error_log($error_message);
    if (DEBUG) {
        die($error_message);
    } else {
        die("Có lỗi xảy ra. Vui lòng thử lại sau.");
    }
}

// Set charset to utf8mb4
if (!$conn->set_charset("utf8mb4")) {
    $error_message = "Lỗi khi thiết lập bộ mã ký tự: " . $conn->error;
    error_log($error_message);
    if (DEBUG) {
        die($error_message);
    }
}

$GLOBALS['conn'] = $conn;
?>