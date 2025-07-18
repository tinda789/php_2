<?php
<<<<<<< HEAD
// model/Order.php // thinh
class Order {
    public static function getAll($conn) {
        $result = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        return $orders;
    }
    public static function updateStatus($conn, $id, $status) {
        $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
    }
    public static function confirm($conn, $id) {
        self::updateStatus($conn, $id, 'confirmed');
    }
    public static function cancel($conn, $id) {
        self::updateStatus($conn, $id, 'cancelled');
=======
// model/Order.php
class Order {
    // Tạo đơn hàng mới
    public static function create($conn, $data, $items, $coupons = []) {
        $conn->begin_transaction();
        try {
            // Tạo order
            $stmt = $conn->prepare("INSERT INTO orders (user_id, order_number, status, payment_status, payment_method, subtotal, tax_amount, shipping_fee, discount_amount, total_amount, shipping_address, billing_address, notes, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
            $stmt->bind_param(
                "isssssddddsss",
                $data['user_id'],
                $data['order_number'],
                $data['status'],
                $data['payment_status'],
                $data['payment_method'],
                $data['subtotal'],
                $data['tax_amount'],
                $data['shipping_fee'],
                $data['discount_amount'],
                $data['total_amount'],
                $data['shipping_address'],
                $data['billing_address'],
                $data['notes']
            );
            if (!$stmt->execute()) throw new Exception('Tạo đơn hàng thất bại');
            $order_id = $conn->insert_id;

            // Thêm order_items
            $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, product_sku, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?, ?, ?)");
            foreach ($items as $item) {
                $stmt_item->bind_param(
                    "iissidd",
                    $order_id,
                    $item['product_id'],
                    $item['product_name'],
                    $item['product_sku'],
                    $item['quantity'],
                    $item['unit_price'],
                    $item['total_price']
                );
                if (!$stmt_item->execute()) throw new Exception('Thêm sản phẩm vào đơn hàng thất bại');
            }

            // Thêm coupon nếu có
            if (!empty($coupons)) {
                $stmt_coupon = $conn->prepare("INSERT INTO order_coupons (order_id, coupon_id, coupon_code, discount_amount) VALUES (?, ?, ?, ?)");
                foreach ($coupons as $coupon) {
                    $stmt_coupon->bind_param("iisd", $order_id, $coupon['coupon_id'], $coupon['coupon_code'], $coupon['discount_amount']);
                    if (!$stmt_coupon->execute()) throw new Exception('Thêm coupon thất bại');
                }
            }

            $conn->commit();
            return $order_id;
        } catch (Exception $e) {
            file_put_contents('order_error.txt', $e->getMessage());
            $conn->rollback();
            return false;
        }
    }

    // Lấy đơn hàng theo id
    public static function getById($conn, $order_id) {
        $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        if ($order) {
            // Lấy order_items
            $stmt2 = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
            $stmt2->bind_param("i", $order_id);
            $stmt2->execute();
            $order['items'] = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
            // Lấy coupon
            $stmt3 = $conn->prepare("SELECT * FROM order_coupons WHERE order_id = ?");
            $stmt3->bind_param("i", $order_id);
            $stmt3->execute();
            $order['coupons'] = $stmt3->get_result()->fetch_all(MYSQLI_ASSOC);
        }
        return $order;
    }

    // Lấy đơn hàng theo order_number
    public static function getByOrderNumber($conn, $order_number) {
        $stmt = $conn->prepare("SELECT * FROM orders WHERE order_number = ?");
        $stmt->bind_param("s", $order_number);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Cập nhật trạng thái thanh toán
    public static function updatePaymentStatus($conn, $order_id, $status) {
        $stmt = $conn->prepare("UPDATE orders SET payment_status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("si", $status, $order_id);
        return $stmt->execute();
    }

    // Cập nhật trạng thái đơn hàng
    public static function updateStatus($conn, $order_id, $status) {
        $stmt = $conn->prepare("UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("si", $status, $order_id);
        return $stmt->execute();
>>>>>>> thanhdat
    }
}
?> 