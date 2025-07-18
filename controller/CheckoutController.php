<?php
// controller/CheckoutController.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../model/Cart.php';
require_once __DIR__ . '/../model/Order.php';

class CheckoutController {
    // Cấu hình VNPay - đưa ra thành constant để đảm bảo nhất quán
    const VNPAY_TMN_CODE ='IZUX3WYL';
    const VNPAY_HASH_SECRET ='ZKDYIL6HKT28NPKKKH5E37BP5LQ4I3Z2';
    const VNPAY_URL = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
    const VNPAY_RETURN_URL = 'http://localhost:8080/index.php?controller=checkout&action=vnpay_return';

    public function checkout() {
        // Debug: ghi log dữ liệu POST và SESSION user
        file_put_contents('debug_checkout.txt', "POST:\n".print_r($_POST, true)."\nSESSION user:\n".print_r($_SESSION['user'] ?? null, true));
        
        // Kiểm tra đăng nhập
        if (empty($_SESSION['user'])) {
            echo '<div style="max-width:600px;margin:60px auto;text-align:center;font-size:20px;color:red;">Bạn cần <a href="index.php?controller=auth&action=login">đăng nhập</a> để thanh toán!</div>';
            exit;
        }
        
        // Lấy giỏ hàng từ session
        $cartModel = new Cart();
        $cart = $cartModel->getCart();
        
        // Lấy danh sách sản phẩm đã chọn
        $selected = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $selected = isset($_POST['selected_products']) ? $_POST['selected_products'] : [];
            if (empty($selected)) {
                $_SESSION['error'] = 'Vui lòng chọn ít nhất một sản phẩm để thanh toán!';
                header('Location: index.php?controller=cart&action=view');
                exit;
            }
        } else {
            // Nếu vào trực tiếp không qua POST, mặc định chọn tất cả
            foreach ($cart as $item) $selected[] = $item['id'];
        }
        
        // Lọc lại giỏ hàng chỉ còn sản phẩm đã chọn
        $cart_selected = array_filter($cart, function($item) use ($selected) {
            return in_array($item['id'], $selected);
        });
        
        if (empty($cart_selected)) {
            $_SESSION['error'] = 'Không có sản phẩm nào để thanh toán!';
            header('Location: index.php?controller=cart&action=view');
            exit;
        }
        
        // Nếu submit form đặt hàng
        if (isset($_POST['name'], $_POST['phone'], $_POST['address'], $_POST['city'], $_POST['district'], $_POST['ward'])) {
            $user_id = $_SESSION['user']['id'] ?? null;
            $shipping_address = json_encode([
                'name' => $_POST['name'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address'],
                'city' => $_POST['city'],
                'district' => $_POST['district'],
                'ward' => $_POST['ward'],
            ], JSON_UNESCAPED_UNICODE);
            $billing_address = $shipping_address;
            $notes = $_POST['notes'] ?? '';
            $payment_method = $_POST['payment_method'] ?? 'vnpay';
            
            $subtotal = 0;
            $items = [];
            foreach ($cart_selected as $item) {
                $price = $item['sale_price'] > 0 ? $item['sale_price'] : $item['price'];
                $subtotal += $price * $item['quantity'];
                $items[] = [
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'product_sku' => $item['sku'] ?? '',
                    'quantity' => $item['quantity'],
                    'unit_price' => $price,
                    'total_price' => $price * $item['quantity']
                ];
            }
            
            $tax_amount = 0;
            $shipping_fee = 0;
            $discount_amount = 0;
            $total_amount = $subtotal + $tax_amount + $shipping_fee - $discount_amount;
            $order_number = 'OD' . date('YmdHis') . rand(100,999);
            
            $order_data = [
                'user_id' => $user_id,
                'order_number' => $order_number,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $payment_method,
                'subtotal' => $subtotal,
                'tax_amount' => $tax_amount,
                'shipping_fee' => $shipping_fee,
                'discount_amount' => $discount_amount,
                'total_amount' => $total_amount,
                'shipping_address' => $shipping_address,
                'billing_address' => $billing_address,
                'notes' => $notes
            ];
            
            $order_id = Order::create($GLOBALS['conn'], $order_data, $items);
            
            if ($order_id) {
                // Xóa các sản phẩm đã chọn khỏi giỏ hàng session
                foreach ($selected as $pid) {
                    unset($_SESSION['cart_items'][$pid]);
                }
                
                // Chuyển hướng sang trang thanh toán VNPay
                if ($payment_method === 'vnpay') {
                    header('Location: index.php?controller=checkout&action=pay_vnpay&order_id=' . $order_id);
                    exit;
                } else {
                    header('Location: index.php?controller=checkout&action=success&order_id=' . $order_id);
                    exit;
                }
            } else {
                $error = 'Có lỗi khi tạo đơn hàng!';
            }
        }
        
        // Hiển thị form checkout với các sản phẩm đã chọn
        $cart = $cart_selected;
        $selected_products = $selected;
        require __DIR__ . '/../view/user/checkout.php';
    }

    public function success() {
        $order_id = $_GET['order_id'] ?? 0;
        $order = Order::getById($GLOBALS['conn'], $order_id);
        require __DIR__ . '/../view/user/checkout_success.php';
    }

    public function pay_vnpay() {
        $order_id = $_GET['order_id'] ?? 0;
        $order = Order::getById($GLOBALS['conn'], $order_id);
        
        if (!$order) {
            echo 'Không tìm thấy đơn hàng!';
            exit;
        }
        
        // Sử dụng constant để đảm bảo nhất quán
        $vnp_TmnCode = self::VNPAY_TMN_CODE;
        $vnp_HashSecret = self::VNPAY_HASH_SECRET;
        $vnp_Url = self::VNPAY_URL;
        $vnp_Returnurl = self::VNPAY_RETURN_URL;
        
        $vnp_TxnRef = $order['order_number'];
        $vnp_OrderInfo = 'Thanh toan don hang #' . $order['order_number'];
        $vnp_OrderType = 'other';
        $vnp_Amount = (int)round($order['total_amount'] * 100);
        $vnp_Locale = 'vn';
        $vnp_BankCode = '';
        $vnp_IpAddr = $this->getClientIp();
        
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        );
        
        // Tạo chữ ký theo đúng chuẩn VNPay
        $vnp_SecureHash = $this->createVnpayHash($inputData, $vnp_HashSecret);
        
        // Tạo URL thanh toán
        $query = [];
        foreach ($inputData as $key => $value) {
            $query[] = urlencode($key) . "=" . urlencode($value);
        }
        $vnp_Url .= "?" . implode('&', $query) . "&vnp_SecureHash=" . $vnp_SecureHash;
        
        // Debug log
        file_put_contents('vnpay_debug.txt', 
            "INPUT DATA:\n" . print_r($inputData, true) . 
            "\nHASH SECRET: " . $vnp_HashSecret . 
            "\nSECURE HASH: " . $vnp_SecureHash . 
            "\nURL: " . $vnp_Url
        );
        
        header('Location: ' . $vnp_Url);
        exit;
    }

    public function vnpay_return() {
        $vnp_HashSecret = self::VNPAY_HASH_SECRET;
        
        // Lấy tất cả tham số vnp_
        $inputData = [];
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        
        // Lấy chữ ký từ VNPay
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        
        // Loại bỏ chữ ký khỏi data để tạo hash
        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);
        
        // Tạo lại chữ ký để so sánh
        $secureHash = $this->createVnpayHash($inputData, $vnp_HashSecret);
        
        // Tìm đơn hàng
        $order_number = $_GET['vnp_TxnRef'] ?? '';
        $order = null;
        if ($order_number) {
            $order = Order::getByOrderNumber($GLOBALS['conn'], $order_number);
        }
        
        // Debug log
        file_put_contents('vnpay_return_debug.txt', 
            "INPUT DATA:\n" . print_r($inputData, true) . 
            "\nVNPAY HASH: " . $vnp_SecureHash . 
            "\nCALCULATED HASH: " . $secureHash . 
            "\nORDER: " . print_r($order, true)
        );
        
        if ($secureHash === $vnp_SecureHash && $order) {
            if ($_GET['vnp_ResponseCode'] == '00') {
                Order::updatePaymentStatus($GLOBALS['conn'], $order['id'], 'paid');
                Order::updateStatus($GLOBALS['conn'], $order['id'], 'confirmed');
                $message = "Thanh toán thành công! Đơn hàng của bạn đã được xác nhận.";
            } else {
                Order::updatePaymentStatus($GLOBALS['conn'], $order['id'], 'failed');
                $message = "Thanh toán thất bại hoặc bị hủy.";
            }
        } else {
            $message = "Xác thực giao dịch không hợp lệ!";
        }
        
        require __DIR__ . '/../view/user/vnpay_result.php';
    }

    public function vnpay_ipn() {
        $vnp_HashSecret = self::VNPAY_HASH_SECRET;
        
        // Lấy tất cả tham số vnp_
        $inputData = [];
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        
        // Lấy chữ ký từ VNPay
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        
        // Loại bỏ chữ ký khỏi data để tạo hash
        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);
        
        // Tạo lại chữ ký để so sánh
        $secureHash = $this->createVnpayHash($inputData, $vnp_HashSecret);
        
        // Tìm đơn hàng
        $order_number = $_GET['vnp_TxnRef'] ?? '';
        $order = null;
        if ($order_number) {
            $order = Order::getByOrderNumber($GLOBALS['conn'], $order_number);
        }

        $response = [
            'RspCode' => '97',
            'Message' => 'Invalid signature'
        ];

        if ($secureHash === $vnp_SecureHash && $order) {
            if ($_GET['vnp_ResponseCode'] == '00') {
                Order::updatePaymentStatus($GLOBALS['conn'], $order['id'], 'paid');
                Order::updateStatus($GLOBALS['conn'], $order['id'], 'confirmed');
                $response = [
                    'RspCode' => '00',
                    'Message' => 'Confirm Success'
                ];
            } else {
                Order::updatePaymentStatus($GLOBALS['conn'], $order['id'], 'failed');
                $response = [
                    'RspCode' => '00',
                    'Message' => 'Confirm Success'
                ];
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    /**
     * Tạo chữ ký VNPay theo đúng chuẩn
     */
    private function createVnpayHash($data, $hashSecret) {
        // Sắp xếp theo key
        ksort($data);
        
        // Tạo hash string
        $hashData = '';
        foreach ($data as $key => $value) {
            if ($key != "vnp_SecureHash" && $key != "vnp_SecureHashType") {
                $hashData .= $key . "=" . $value . "&";
            }
        }
        
        // Loại bỏ dấu & cuối cùng
        $hashData = rtrim($hashData, "&");
        
        // Tạo hash
        return hash_hmac('sha512', $hashData, $hashSecret);
    }

    /**
     * Lấy IP của client
     */
    private function getClientIp() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
            // Chuyển đổi localhost thành IP hợp lệ
            if ($ip === '::1' || $ip === 'localhost') {
                return '127.0.0.1';
            }
            return $ip;
        }
        return '127.0.0.1';
    }
}

// Router đơn giản cho controller này
$action = $_GET['action'] ?? 'checkout';
$controller = new CheckoutController();
if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    $controller->checkout();
}
?>