<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$controller = $_GET['controller'] ?? '';
$action = $_GET['action'] ?? 'index';

// Định nghĩa các controller và tham số cần thiết
$controllers = [
    'banner' => [
        'file' => 'controller/BannerController.php',
        'class' => 'BannerController',
        'params' => ['id', 'position']
    ],
    'news' => [
        'file' => 'controller/NewsController.php',
        'class' => 'NewsController',
        'params' => ['id', 'status', 'category']
    ],
    'review' => [
        'file' => 'controller/ReviewController.php',
        'class' => null, // File này không có class, chỉ có logic xử lý trực tiếp
        'params' => [],
        'legacy_mode' => true,
        'dependencies' => ['model/Review.php']
    ],
    'auth' => [
        'file' => 'controller/AuthController.php',
        'class' => null, // File này không có class, chỉ có logic xử lý trực tiếp
        'params' => [],
        'legacy_mode' => true
    ],
    'user' => [
        'file' => 'controller/UserController.php',
        'class' => null, // File này không có class, chỉ có logic xử lý trực tiếp
        'params' => [],
        'legacy_mode' => true
    ],
    'admin' => [
        'file' => 'controller/AdminController.php',
        'class' => null, // File này không có class, chỉ có logic xử lý trực tiếp
        'params' => [],
        'legacy_mode' => true
    ],
    'category' => [
        'file' => 'controller/CategoryController.php',
        'class' => null, // File này không có class, chỉ có logic xử lý trực tiếp
        'params' => [],
        'legacy_mode' => true
    ],
    'product' => [
        'file' => 'controller/ProductController.php',
        'class' => 'ProductController',
        'params' => ['id'],
        'dependencies' => ['config/config.php', 'model/Review.php'],
        'constructor_params' => ['$conn']
    ],
    'cart' => [
        'file' => 'controller/CartController.php',
        'class' => 'CartController',
        'params' => [],
        'default_action' => 'view'
    ],
    'checkout' => [
        'file' => 'controller/CheckoutController.php',
        'class' => 'CheckoutController',
        'params' => [],
        'default_action' => 'checkout'
    ]
];

// Hàm xử lý controller
function handleController($controllerName, $action, $controllerConfig) {
    try {
        // Load dependencies nếu có
        if (isset($controllerConfig['dependencies'])) {
            foreach ($controllerConfig['dependencies'] as $dependency) {
                require_once $dependency;
            }
        }
        
        // Load controller file
        require_once $controllerConfig['file'];
        
        // Kiểm tra legacy mode (file không có class, chỉ có logic xử lý trực tiếp)
        if (isset($controllerConfig['legacy_mode']) && $controllerConfig['legacy_mode']) {
            // File đã được load và logic đã được thực thi
            return;
        }
        
        // Tạo instance controller
        $className = $controllerConfig['class'];
        if (isset($controllerConfig['constructor_params'])) {
            // Xử lý trường hợp đặc biệt cho ProductController
            if ($className === 'ProductController') {
                $controllerInstance = new $className($conn);
            } else {
                $controllerInstance = new $className();
            }
        } else {
            $controllerInstance = new $className();
        }
        
        // Kiểm tra method tồn tại
        if (method_exists($controllerInstance, $action)) {
            // Chuẩn bị tham số
            $params = [];
            foreach ($controllerConfig['params'] as $param) {
                if (isset($_GET[$param])) {
                    $params[] = $_GET[$param];
                }
            }
            
            // Gọi method với tham số
            if (!empty($params)) {
                call_user_func_array([$controllerInstance, $action], $params);
            } else {
                $controllerInstance->$action();
            }
        } else {
            // Gọi action mặc định
            $defaultAction = $controllerConfig['default_action'] ?? 'index';
            if (method_exists($controllerInstance, $defaultAction)) {
                $controllerInstance->$defaultAction();
            } else {
                throw new Exception("Method $defaultAction not found in $className");
            }
        }
        
    } catch (Exception $e) {
        // Log lỗi
        error_log("Controller Error: " . $e->getMessage());
        
        // Hiển thị trang lỗi hoặc redirect
        http_response_code(500);
        echo "Có lỗi xảy ra. Vui lòng thử lại sau.";
        exit;
    }
}

// Xử lý routing
if (!empty($controller) && isset($controllers[$controller])) {
    handleController($controller, $action, $controllers[$controller]);
} else {
    // Trang chủ mặc định
    include 'view/home.php';
}
?> 