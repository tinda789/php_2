<?php
// controller/CheckoutController.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../model/Cart.php';
require_once __DIR__ . '/../model/Order.php';
date_default_timezone_set('Asia/Ho_Chi_Minh');

class CheckoutController {
    // Cấu hình VNPay - đưa ra thành constant để đảm bảo nhất quán
    const VNPAY_TMN_CODE = '7JX9OTN2';
    const VNPAY_HASH_SECRET = 'U5IUS3GKIH1RA41A3OU40XPNLTJIE0FF';
    
    const VNPAY_URL = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
    const VNPAY_RETURN_URL = 'http://localhost:8000/index.php?controller=checkout&action=vnpay_return';
    public function checkout() {
        // Debug: ghi log dữ liệu POST và SESSION user
        file_put_contents('debug_checkout.txt', "POST:\n".print_r($_POST, true)."\nSESSION user:\n".print_r($_SESSION['user'] ?? null, true));
        
        // Kiểm tra đăng nhập
        if (empty($_SESSION['user'])) {
            echo '<div style="max-width:600px;margin:60px auto;text-align:center;font-size:20px;color:red;">Bạn cần <a href="index.php?controller=auth&action=login">đăng nhập</a> để thanh toán!</div>';
            exit;
        }
        
        // Lấy giỏ hàng từ session
        global $conn;
        $cartModel = new Cart($conn);
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
        
        // Kiểm tra tồn kho trước khi tạo đơn hàng
        foreach ($cart_selected as $item) {
            $stmt = $GLOBALS['conn']->prepare("SELECT stock FROM products WHERE id = ?");
            $stmt->bind_param("i", $item['id']);
            $stmt->execute();
            $stmt->bind_result($stock);
            $stmt->fetch();
            $stmt->close();
            if ($stock < $item['quantity']) {
                $error = "Sản phẩm '{$item['name']}' không đủ hàng trong kho (còn $stock cái)!";
                // Ghi log cho admin
                $user_id = $_SESSION['user']['id'] ?? 0;
                file_put_contents('out_of_stock.log', date('Y-m-d H:i:s') . " - UserID: {$user_id} - Sản phẩm: {$item['name']} - Đặt: {$item['quantity']} - Còn: $stock\n", FILE_APPEND);
                require __DIR__ . '/../view/user/checkout.php';
                return;
            }
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
            $payment_method = 'vnpay'; // Chỉ cho phép VNPay
            
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
            if (!$order_id) {
                file_put_contents('debug_checkout_error.txt', print_r($order_data, true) . print_r($items, true)); // thanhdat debug
            }
            
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
            $_SESSION['error'] = 'Không tìm thấy đơn hàng!';
            header('Location: index.php?controller=cart&action=view');
            exit;
        }
        
        // Kiểm tra nếu đơn hàng đã thanh toán
        if ($order['payment_status'] === 'paid') {
            $_SESSION['error'] = 'Đơn hàng này đã được thanh toán trước đó!';
            header('Location: index.php?controller=checkout&action=success&order_id=' . $order_id);
            exit;
        }
        
        // Sử dụng constant để đảm bảo nhất quán
        $vnp_TmnCode = self::VNPAY_TMN_CODE;
        $vnp_HashSecret = self::VNPAY_HASH_SECRET;
        $vnp_Url = self::VNPAY_URL;
        $vnp_Returnurl = self::VNPAY_RETURN_URL;
        
        // Thông tin đơn hàng
        $vnp_TxnRef = $order['order_number'];
        $vnp_OrderInfo = 'Thanh toán đơn hàng #' . $order['order_number'];
        $vnp_OrderType = 'other';
        $vnp_Amount = (int)round($order['total_amount']); 
        $vnp_Locale = 'vn';
        $vnp_BankCode = ''; // Để người dùng chọn ngân hàng trên VNPay
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
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => date('YmdHis', strtotime('+15 minutes')), // Hết hạn sau 15 phút
        );
        
        // Sắp xếp lại mảng theo key để tạo chữ ký
        ksort($inputData);
        
        // Tạo chuỗi dữ liệu để tạo chữ ký
        $hashData = '';
        foreach ($inputData as $key => $value) {
            if ($value !== '' && $value !== null) {
                $hashData .= ($hashData ? '&' : '') . urlencode($key) . '=' . urlencode($value);
            }
        }
        
        // Tạo chữ ký
        $vnp_SecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        
        // Thêm chữ ký vào mảng dữ liệu
        $inputData['vnp_SecureHash'] = $vnp_SecureHash;
        
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
        
        // Chuyển hướng đến VNPay
        header('Location: ' . $vnp_Url);
        exit;
    }

    /**
     * Hiển thị kết quả thanh toán
     */
    public function payment_result() {
        // Lấy thông báo từ session
        $message = $_SESSION['payment_message'] ?? 'Không có thông tin thanh toán.';
        $isSuccess = $_SESSION['is_payment_success'] ?? false;
        $order_number = $_GET['order_number'] ?? '';
        
        // Lấy thông tin đơn hàng nếu có
        $order = null;
        if ($order_number) {
            $order = Order::getByOrderNumber($GLOBALS['conn'], $order_number);
        }
        
        // Xóa thông báo khỏi session sau khi đã lấy
        unset($_SESSION['payment_message']);
        unset($_SESSION['is_payment_success']);
        
        // Hiển thị view
        require __DIR__ . '/../view/user/checkout/payment_result.php';
    }
    
    /**
     * Xử lý kết quả trả về từ VNPay sau khi thanh toán
     */
    public function vnpay_return() {
        // Lưu toàn bộ dữ liệu trả về để debug
        $logData = "[VNPAY_RETURN] " . date('Y-m-d H:i:s') . "\n";
        $logData .= "GET DATA: " . print_r($_GET, true) . "\n";
        
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
        
        // Sắp xếp lại mảng theo key để tạo chữ ký
        ksort($inputData);
        
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
            $isSuccess = false;
            $logData .= "INVALID SIGNATURE OR ORDER NOT FOUND\n";
        }
        
        // Ghi log
        file_put_contents('vnpay_return_debug.txt', $logData . "\n", FILE_APPEND);
        
        // Chuyển hướng đến trang kết quả với thông báo
        $_SESSION['payment_message'] = $message;
        $_SESSION['is_payment_success'] = $isSuccess;
        header('Location: index.php?controller=checkout&action=payment_result&order_number=' . $order_number);
        exit;
    }

    public function vnpay_ipn() {
        // Lưu toàn bộ dữ liệu IPN để debug
        $logData = "[VNPAY_IPN] " . date('Y-m-d H:i:s') . "\n";
        $logData .= "GET DATA: " . print_r($_GET, true) . "\n";
        
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
        
        // Sắp xếp lại mảng theo key để tạo chữ ký
        ksort($inputData);
        
        // Tạo lại chữ ký để so sánh
        $secureHash = $this->createVnpayHash($inputData, $vnp_HashSecret);
        
        // Tìm đơn hàng
        $order_number = $_GET['vnp_TxnRef'] ?? '';
        $order = null;
        if ($order_number) {
            $order = Order::getByOrderNumber($GLOBALS['conn'], $order_number);
        }
        
        // Thêm thông tin vào log
        $logData .= "ORDER NUMBER: " . $order_number . "\n";
        $logData .= "SECURE HASH: " . $vnp_SecureHash . "\n";
        $logData .= "CALCULATED HASH: " . $secureHash . "\n";
        
        // Mặc định là lỗi
        $response = [
            'RspCode' => '99',
            'Message' => 'Unknown error'
        ];
        
        // Kiểm tra chữ ký
        $isValidSignature = ($secureHash === $vnp_SecureHash);
        
        if ($isValidSignature && $order) {
            $responseCode = $_GET['vnp_ResponseCode'] ?? '';
            $transactionNo = $_GET['vnp_TransactionNo'] ?? '';
            $amount = $_GET['vnp_Amount'] ?? 0;
            $bankCode = $_GET['vnp_BankCode'] ?? '';
            $payDate = $_GET['vnp_PayDate'] ?? '';
            $transactionStatus = $_GET['vnp_TransactionStatus'] ?? '';
            $txnResponseCode = $_GET['vnp_ResponseCode'] ?? '';
            
            $logData .= "RESPONSE CODE: " . $responseCode . "\n";
            $logData .= "TRANSACTION STATUS: " . $transactionStatus . "\n";
            $logData .= "TRANSACTION NO: " . $transactionNo . "\n";
            $logData .= "AMOUNT: " . $amount . "\n";
            $logData .= "BANK CODE: " . $bankCode . "\n";
            
            // Kiểm tra số tiền thanh toán có khớp với đơn hàng không
            $orderAmount = (int)round($order['total_amount'] * 100); // Chuyển đổi sang VNĐ (đơn vị nhỏ nhất)
            
            if ($orderAmount != $amount) {
                $logData .= "AMOUNT MISMATCH: Order amount ($orderAmount) != Paid amount ($amount)\n";
                $response = [
                    'RspCode' => '04',
                    'Message' => 'Invalid amount'
                ];
            } else if ($responseCode === '00' && $transactionStatus === '00') {
                // Thanh toán thành công
                $updateResult = Order::updatePaymentStatus($GLOBALS['conn'], $order['id'], 'paid');
                $updateStatus = Order::updateStatus($GLOBALS['conn'], $order['id'], 'confirmed');
                
                // Lưu thông tin giao dịch vào ghi chú
                $transactionInfo = "IPN: Thanh toán thành công qua VNPay. Mã GD: $transactionNo, Ngân hàng: $bankCode, Ngày: $payDate";
                $stmt = $GLOBALS['conn']->prepare("UPDATE orders SET notes = CONCAT(IFNULL(notes, ''), '\\n$transactionInfo') WHERE id = ?");
                $stmt->bind_param("i", $order['id']);
                $stmt->execute();
                
                $logData .= "PAYMENT STATUS UPDATED: " . ($updateResult ? 'SUCCESS' : 'FAILED') . "\n";
                $logData .= "ORDER STATUS UPDATED: " . ($updateStatus ? 'SUCCESS' : 'FAILED') . "\n";
                
                $response = [
                    'RspCode' => '00',
                    'Message' => 'Confirm Success'
                ];
            } else {
                // Giao dịch thất bại
                $errorMessages = [
                    '07' => 'Trừ tiền thành công. Chi trả lỗi',
                    '09' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking tại ngân hàng.',
                    '10' => 'Giao dịch không thành công do: Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần',
                    '11' => 'Giao dịch không thành công do: Đã hết hạn chờ thanh toán. Xin quý khách vui lòng thực hiện lại giao dịch.',
                    '12' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng bị khóa.',
                    '13' => 'Giao dịch không thành công do Quý khách nhập sai mật khẩu xác thực giao dịch (OTP). Xin quý khách vui lòng thực hiện lại giao dịch.',
                    '24' => 'Giao dịch không thành công do: Khách hàng hủy giao dịch',
                    '51' => 'Giao dịch không thành công do: Tài khoản của quý khách không đủ số dư để thực hiện giao dịch.',
                    '65' => 'Giao dịch không thành công do: Tài khoản của Quý khách đã vượt quá hạn mức giao dịch trong ngày.',
                    '75' => 'Ngân hàng thanh toán đang bảo trì.',
                    '79' => 'Giao dịch không thành công do: KH nhập sai mật khẩu thanh toán quá số lần quy định. Xin quý khách vui lòng thực hiện lại giao dịch',
                    '99' => 'Các lỗi khác (lỗi còn lại, không có trong danh sách mã lỗi đã liệt kê)'
                ];
                
                $errorMessage = $errorMessages[$responseCode] ?? "Mã lỗi: $responseCode";
                
                // Cập nhật trạng thái đơn hàng là thất bại
                Order::updatePaymentStatus($GLOBALS['conn'], $order['id'], 'failed');
                
                // Lưu thông tin lỗi vào ghi chú
                $errorInfo = "IPN: Thanh toán thất bại. $errorMessage. Mã GD: $transactionNo";
                $stmt = $GLOBALS['conn']->prepare("UPDATE orders SET notes = CONCAT(IFNULL(notes, ''), '\\n$errorInfo') WHERE id = ?");
                $stmt->bind_param("i", $order['id']);
                $stmt->execute();
                
                $logData .= "PAYMENT FAILED: " . $errorMessage . "\n";
                
                $response = [
                    'RspCode' => '00',
                    'Message' => 'Confirm Success'
                ];
            }
        } else {
            $logData .= "INVALID SIGNATURE OR ORDER NOT FOUND\n";
            $response = [
                'RspCode' => '97',
                'Message' => 'Invalid signature or order not found'
            ];
        }
        
        // Ghi log
        file_put_contents('vnpay_ipn_debug.txt', $logData . "\n", FILE_APPEND);
        
        // Trả về kết quả cho VNPay
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    /**
     * Tạo chữ ký VNPay theo đúng chuẩn
     */
    private function createVnpayHash($data, $hashSecret) {
        // Sắp xếp các trường theo thứ tự alphabet
        ksort($data);
        
        // Tạo chuỗi dữ liệu cần mã hóa
        $hashData = '';
        $i = 0;
        $dataCount = count($data);
        
        foreach ($data as $key => $value) {
            // Bỏ qua các trường không cần thiết
            if ($key === 'vnp_SecureHash' || $key === 'vnp_SecureHashType') {
                continue;
            }
            
            // Chỉ thêm vào nếu giá trị không rỗng
            if (strlen($value) > 0) {
                $i++;
                // Không thêm dấu & sau tham số cuối cùng
                $hashData .= ($i === 1 ? '' : '&') . urlencode($key) . '=' . urlencode($value);
            }
        }
        
        // Mã hóa bằng HMAC-SHA256
        $signature = hash_hmac('sha512', $hashData, $hashSecret);
        
        // Ghi log để debug
        $debugInfo = "==============================\n";
        $debugInfo .= "RAW DATA:\n" . print_r($data, true) . "\n";
        $debugInfo .= "HASH DATA: " . $hashData . "\n";
        $debugInfo .= "HASH SECRET: " . $hashSecret . "\n";
        $debugInfo .= "GENERATED SIGNATURE: " . $signature . "\n\n";
        file_put_contents('vnpay_hash_debug.txt', $debugInfo, FILE_APPEND);
        
        return $signature;
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