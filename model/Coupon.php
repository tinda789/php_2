<?php
class Coupon {
    public static function getAll($conn) {
        $sql = "SELECT *, 
            CASE 
                WHEN end_date < NOW() THEN 'Hết hạn'
                WHEN is_active = 0 THEN 'Ẩn'
                ELSE 'Đang hoạt động'
            END as status_label
            FROM coupons ORDER BY id ASC"; // thinh
        $result = $conn->query($sql);
        $coupons = [];
        while ($row = $result->fetch_assoc()) {
            $coupons[] = $row;
        }
        return $coupons;
    }
    // Lấy mã giảm giá theo id // thinh
    public static function getById($conn, $id) {
        $stmt = $conn->prepare("SELECT * FROM coupons WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    // Thêm mới mã giảm giá // thinh
    public static function create($conn, $data) { // thinh
        $stmt = $conn->prepare("INSERT INTO coupons (code, name, description, type, value, minimum_amount, maximum_discount, usage_limit, used_count, start_date, end_date, is_active, payment_method, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, ?, ?, ?, ?, NOW())");
        $stmt->bind_param(
            "ssssdddiisis",
            $data['code'],
            $data['name'],
            $data['description'],
            $data['type'],
            $data['value'],
            $data['minimum_amount'],
            $data['maximum_discount'],
            $data['usage_limit'],
            $data['start_date'],
            $data['end_date'],
            $data['is_active'],
            $data['payment_method']
        );
        return $stmt->execute();
    }
    // Cập nhật mã giảm giá // thinh
    public static function update($conn, $id, $data) {
        $stmt = $conn->prepare("UPDATE coupons SET code=?, name=?, description=?, type=?, value=?, minimum_amount=?, maximum_discount=?, usage_limit=?, start_date=?, end_date=?, is_active=? WHERE id=?");
        $stmt->bind_param("ssssdddiissi",
            $data['code'],
            $data['name'],
            $data['description'],
            $data['type'],
            $data['value'],
            $data['minimum_amount'],
            $data['maximum_discount'],
            $data['usage_limit'],
            $data['start_date'],
            $data['end_date'],
            $data['is_active'],
            $id
        );
        return $stmt->execute();
    }
    // Xóa mã giảm giá // thinh
    public static function delete($conn, $id) {
        $stmt = $conn->prepare("DELETE FROM coupons WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?> 