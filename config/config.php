<?php
$servername = "localhost";
$username = "root";
$password = "root"; // hoặc "" nếu không có mật khẩu
$dbname = "shopelectrics";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?> 