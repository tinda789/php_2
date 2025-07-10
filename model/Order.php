<?php
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
    }
}
?> 