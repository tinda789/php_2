<?php
$controller = $_GET['controller'] ?? '';
if ($controller === 'auth') {
    require 'controller/AuthController.php';
} elseif ($controller === 'user') {
    require 'controller/UserController.php';
} elseif ($controller === 'admin') {
    require 'controller/AdminController.php';
} elseif ($controller === 'category') {
    require 'controller/CategoryController.php';
} elseif ($controller === 'product') {
    require 'controller/ProductController.php';
} else {
    include 'view/home.php';
}
?> 