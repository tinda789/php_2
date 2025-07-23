<?php
// model/Order.php
class Order {
    // Lấy tất cả đơn hàng
    public static function getAll($conn) {
        $result = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        return $orders;
    }

    // Tạo đơn hàng mới
    public static function create($conn, $data, $items, $coupons = []) {
        $conn->begin_transaction();
        try {
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
                // Trừ số lượng hàng trong kho
                $stmt_update_stock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
                $stmt_update_stock->bind_param("ii", $item['quantity'], $item['product_id']);
                if (!$stmt_update_stock->execute()) throw new Exception('Trừ kho thất bại');
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
    }

    // thanhdat: Thống kê tổng doanh thu
    public static function getTotalRevenue($conn) {
        $sql = "SELECT SUM(total_amount) as total FROM orders WHERE status IN ('completed', 'delivered')";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }
    // thanhdat: Thống kê tổng số đơn hoàn thành
    public static function getTotalCompletedOrders($conn) {
        $sql = "SELECT COUNT(*) as total FROM orders WHERE status IN ('completed', 'delivered')";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }
    // thanhdat: Thống kê tổng số đơn chờ xử lý
    public static function getTotalPendingOrders($conn) {
        $sql = "SELECT COUNT(*) as total FROM orders WHERE status IN ('pending', 'processing')";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }
    // thanhdat: Thống kê tổng số đơn bị hủy
    public static function getTotalCancelledOrders($conn) {
        $sql = "SELECT COUNT(*) as total FROM orders WHERE status = 'cancelled'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    // thanhdat: Lấy doanh thu từng tháng trong năm hiện tại
    public static function getMonthlyRevenue($conn) {
        $sql = "SELECT MONTH(created_at) as month, SUM(total_amount) as revenue FROM orders WHERE status IN ('completed', 'delivered') AND YEAR(created_at) = YEAR(CURDATE()) GROUP BY MONTH(created_at) ORDER BY month";
        $result = $conn->query($sql);
        $data = array_fill(1, 12, 0);
        while ($row = $result->fetch_assoc()) {
            $data[(int)$row['month']] = (float)$row['revenue'];
        }
        return $data;
    }
    // thanhdat: Lấy số đơn hàng theo trạng thái
    public static function getOrderStatusStats($conn) {
        $sql = "SELECT status, COUNT(*) as total FROM orders GROUP BY status";
        $result = $conn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[$row['status']] = (int)$row['total'];
        }
        return $data;
    }

    // thanhdat: Xác nhận đơn hàng (ví dụ)
    public static function confirm($conn, $id) {
        self::updateStatus($conn, $id, 'confirmed');
    }

    // thanhdat: Hủy đơn hàng (ví dụ)
    public static function cancel($conn, $id) {
        self::updateStatus($conn, $id, 'cancelled');
    }
}
?> 