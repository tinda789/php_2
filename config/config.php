<?php
$servername = "localhost";
$username = "root";
$password = ""; // hoặc "" nếu không có mật khẩu
$dbname = "shopelectrics1";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?> 