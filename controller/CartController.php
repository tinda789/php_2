<?php
// Bật báo lỗi
error_reporting(E_ALL);
ini_set('display_errors', 1);

// CartController.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../model/Product.php';
require_once __DIR__ . '/../model/Cart.php';

class CartController {
    public function add() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false, 
                    'login_required' => true,
                    'message' => 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.'
                ]);
                exit;
            } else {
                $_SESSION['redirect_after_login'] = $_SERVER['HTTP_REFERER'] ?? 'index.php';
                header('Location: index.php?controller=auth&action=login&error=login_required');
                exit;
            }
        }
        
        // Check if this is an AJAX request
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                 strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        
        if ($product_id <= 0) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Sản phẩm không hợp lệ']);
                exit;
            } else {
                header('Location: index.php?controller=product&action=list&error=invalid_product');
                exit;
            }
        }
        
        try {
            $cartModel = new Cart();
            $productModel = new Product();
            $product = $productModel::getById($GLOBALS['conn'], $product_id);
            
            if ($product['stock'] <= 0) {
                $_SESSION['error'] = 'Sản phẩm "' . $product['name'] . '" đã hết hàng!';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }
            
            // Kiểm tra nếu số lượng yêu cầu vượt quá tồn kho
            $cart = $cartModel->getCart();
            $current_quantity_in_cart = isset($cart[$product_id]) ? $cart[$product_id]['quantity'] : 0;
            $total_quantity = $current_quantity_in_cart + $quantity;
            
            if ($total_quantity > $product['stock']) {
                $available = $product['stock'] - $current_quantity_in_cart;
                if ($available <= 0) {
                    $_SESSION['error'] = 'Sản phẩm "' . $product['name'] . '" đã đạt giới hạn mua tối đa trong giỏ hàng!';
                } else {
                    $_SESSION['error'] = 'Số lượng sản phẩm "' . $product['name'] . '" vượt quá tồn kho. Chỉ còn ' . $available . ' sản phẩm có sẵn.';
                }
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }
            
            $result = $cartModel->addToCart($product_id, $quantity);
            
            if ($isAjax) {
                // Get updated cart count
                $cartCount = 0;
                if (!empty($_SESSION['cart_items'])) {
                    foreach ($_SESSION['cart_items'] as $qty) $cartCount += $qty;
                }
                
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'cartCount' => $cartCount,
                    'message' => 'Đã thêm sản phẩm vào giỏ hàng'
                ]);
                exit;
            } else {
                header('Location: index.php?controller=cart&action=view');
                exit;
            }
        } catch (Exception $e) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
                ]);
                exit;
            } else {
                header('Location: index.php?controller=product&action=list&error=add_to_cart_failed');
                exit;
            }
        }
    }
    public function view() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $cartModel = new Cart();
        $cart = $cartModel->getCart();
        require __DIR__ . '/../view/layout/header.php';
        require __DIR__ . '/../view/user/cart_view.php';
        require __DIR__ . '/../view/layout/footer.php';
    }
    public function remove() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Check for both 'id' and 'product_id' parameters
        $product_id = isset($_GET['id']) ? intval($_GET['id']) : 
                     (isset($_GET['product_id']) ? intval($_GET['product_id']) : 0);
        
        if ($product_id > 0) {
            $cartModel = new Cart();
            $cartModel->removeFromCart($product_id);
            
            // If this is an AJAX request, return JSON response
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Đã xóa sản phẩm khỏi giỏ hàng',
                    'redirect' => 'index.php?controller=cart&action=view'
                ]);
                exit;
            }
        }
        
        header('Location: index.php?controller=cart&action=view');
        exit;
    }
    public function update() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        if ($product_id > 0 && $quantity > 0) {
            $cartModel = new Cart();
            $cartModel->updateQuantity($product_id, $quantity);
        }
        header('Location: index.php?controller=cart&action=view');
        exit;
    }
    public function count() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $count = 0;
        if (!empty($_SESSION['cart_items'])) {
            foreach ($_SESSION['cart_items'] as $qty) $count += $qty;
        }
        header('Content-Type: application/json');
        echo json_encode(['count' => $count]);
        exit;
    }
    // Có thể bổ sung các action khác như remove, update quantity...
    
    public function applyCoupon() {
        // Đảm bảo không có output nào trước khi gửi header
        if (ob_get_level() > 0) {
            ob_clean();
        }
        
        // Đặt header JSON
        header('Content-Type: application/json');
        
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            // Kiểm tra phương thức request
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                throw new Exception('Phương thức không được hỗ trợ. Vui lòng sử dụng POST.');
            }
            
            // Kiểm tra đăng nhập
            if (!isset($_SESSION['user'])) {
                throw new Exception('Vui lòng đăng nhập để sử dụng mã giảm giá.', 401);
            }
            
            // Lấy và validate mã giảm giá
            $coupon_code = isset($_POST['coupon_code']) ? trim($_POST['coupon_code']) : '';
            if (empty($coupon_code)) {
                throw new Exception('Vui lòng nhập mã giảm giá.');
            }
        
            // Kiểm tra giỏ hàng
            $cartModel = new Cart();
            $cartItems = $cartModel->getCart();
            
            if (empty($cartItems)) {
                throw new Exception('Giỏ hàng của bạn đang trống.');
            }
            
            // Tính tổng tiền giỏ hàng
            $subtotal = 0;
            foreach ($cartItems as $item) {
                if (!is_array($item) || !isset($item['price'], $item['quantity'])) {
                    continue;
                }
                
                $price = isset($item['sale_price']) && $item['sale_price'] > 0 ? 
                    $item['sale_price'] : $item['price'];
                $subtotal += $price * $item['quantity'];
            }
            
            // Kiểm tra mã giảm giá
            require_once __DIR__ . '/../model/Coupon.php';
            global $conn; // Sử dụng biến kết nối toàn cục từ config.php
            $coupon = Coupon::getCouponByCode($conn, $coupon_code);
            
            if (!$coupon) {
                throw new Exception('Mã giảm giá không hợp lệ hoặc đã hết hạn.');
            }
            
            // Kiểm tra điều kiện sử dụng mã giảm giá
            if ($coupon['usage_limit'] > 0 && $coupon['used_count'] >= $coupon['usage_limit']) {
                throw new Exception('Mã giảm giá đã hết lượt sử dụng.');
            }
            
            if ($subtotal < $coupon['minimum_amount']) {
                throw new Exception('Đơn hàng chưa đạt giá trị tối thiểu ' . 
                    number_format($coupon['minimum_amount'], 0, ',', '.') . 
                    'đ để áp dụng mã giảm giá.');
            }
            
            // Tính toán giảm giá
            $discount = 0;
            if ($coupon['type'] === 'fixed') {
                $discount = min($coupon['value'], $subtotal);
            } else {
                $discount = $subtotal * ($coupon['value'] / 100);
                if ($coupon['maximum_discount'] > 0) {
                    $discount = min($discount, $coupon['maximum_discount']);
                }
            }
            
            // Cập nhật giỏ hàng với mã giảm giá
            $_SESSION['applied_coupon'] = [
                'id' => $coupon['id'],
                'code' => $coupon['code'],
                'name' => $coupon['name'],
                'type' => $coupon['type'],
                'value' => $coupon['value'],
                'minimum_amount' => $coupon['minimum_amount'],
                'maximum_discount' => $coupon['maximum_discount'],
                'discount' => $discount
            ];
            
            $total = max(0, $subtotal - $discount);
            
            // Trả về kết quả
            echo json_encode([
                'success' => true,
                'message' => 'Áp dụng mã giảm giá thành công!',
                'coupon' => [
                    'code' => $coupon['code'],
                    'name' => $coupon['name'],
                    'type' => $coupon['type'],
                    'value' => $coupon['value'],
                    'discount' => $discount,
                    'formatted_discount' => number_format($discount, 0, ',', '.') . 'đ',
                    'total' => $total,
                    'formatted_total' => number_format($total, 0, ',', '.') . 'đ',
                    'subtotal' => $subtotal,
                    'formatted_subtotal' => number_format($subtotal, 0, ',', '.') . 'đ'
                ]
            ]);
        } catch (Exception $e) {
            // Xóa mã giảm giá nếu có lỗi
            unset($_SESSION['applied_coupon']);
            
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }
    
    public function removeCoupon() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $removed = false;
        if (isset($_SESSION['applied_coupon'])) {
            unset($_SESSION['applied_coupon']);
            $removed = true;
        }
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $removed,
            'message' => $removed ? 'Đã xóa mã giảm giá khỏi đơn hàng.' : 'Không tìm thấy mã giảm giá để xóa.'
        ]);
        exit;
    }
} 