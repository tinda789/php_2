<?php
session_start();
ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

$controller = $_GET['controller'] ?? '';
$action = $_GET['action'] ?? 'index';

// Xử lý controller OOP
if ($controller === 'banner') {
    require 'controller/BannerController.php';
    $bannerController = new BannerController();
    
    if (method_exists($bannerController, $action)) {
        if (isset($_GET['id'])) {
            $bannerController->$action($_GET['id']);
        } elseif (isset($_GET['position'])) {
            $bannerController->$action($_GET['position']);
        } else {
            $bannerController->$action();
        }
    } else {
        $bannerController->index();
    }
} elseif ($controller === 'news') {
    require 'controller/NewsController.php';
    $newsController = new NewsController();
    
    if (method_exists($newsController, $action)) {
        if (isset($_GET['id'])) {
            $newsController->$action($_GET['id']);
        } elseif (isset($_GET['status'])) {
            $newsController->$action($_GET['id'], $_GET['status']);
        } elseif (isset($_GET['category'])) {
            $newsController->$action($_GET['category']);
        } else {
            $newsController->$action();
        }
    } else {
        $newsController->index();
    }
} elseif ($controller === 'review') {
    require 'controller/ReviewController.php';
} elseif ($controller === 'auth') {
    require 'controller/AuthController.php';
} elseif ($controller === 'user') {
    require 'controller/UserController.php';
} elseif ($controller === 'admin') {
    require 'controller/AdminController.php';
} elseif ($controller === 'category') {
    require 'controller/CategoryController.php';
} elseif ($controller === 'product') {
    require 'controller/ProductController.php';
    require 'config/config.php';
    require 'model/Product.php';
    require 'model/Review.php';
    $productController = new ProductController($conn);
    
    if (method_exists($productController, $action)) {
        if (isset($_GET['id'])) {
            $productController->$action($_GET['id']);
        } else {
            $productController->$action();
        }
    } else {
        $productController->index();
    }
} else {
    include 'view/home.php';
}
?> 