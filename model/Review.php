<?php
class Review {
    // Lấy tất cả đánh giá (có phân trang, lọc theo trạng thái)
    public static function getAll($conn, $limit = 10, $offset = 0, $search = '', $status = '') {
        $sql = "SELECT r.*, p.name as product_name, u.first_name, u.last_name, u.username 
                FROM reviews r
                LEFT JOIN products p ON r.product_id = p.id
                LEFT JOIN users u ON r.user_id = u.id";
        
        $where_conditions = [];
        $params = [];
        $types = '';
        
        if ($search !== '') {
            $where_conditions[] = "(p.name LIKE ? OR u.first_name LIKE ? OR u.last_name LIKE ? OR r.title LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $types .= 'ssss';
        }
        
        if ($status !== '') {
            if ($status === 'approved') {
                $where_conditions[] = "r.is_approved = 1";
            } elseif ($status === 'pending') {
                $where_conditions[] = "r.is_approved = 0";
            } elseif ($status === 'verified') {
                $where_conditions[] = "r.is_verified = 1";
            }
        }
        
        if (!empty($where_conditions)) {
            $sql .= " WHERE " . implode(' AND ', $where_conditions);
        }
        
        $sql .= " ORDER BY r.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $types .= 'ii';
        
        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
        return $reviews;
    }

    // Đếm tổng số đánh giá (lọc)
    public static function countAll($conn, $search = '', $status = '') {
        $sql = "SELECT COUNT(*) as total FROM reviews r
                LEFT JOIN products p ON r.product_id = p.id
                LEFT JOIN users u ON r.user_id = u.id";
        
        $where_conditions = [];
        $params = [];
        $types = '';
        
        if ($search !== '') {
            $where_conditions[] = "(p.name LIKE ? OR u.first_name LIKE ? OR u.last_name LIKE ? OR r.title LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $types .= 'ssss';
        }
        
        if ($status !== '') {
            if ($status === 'approved') {
                $where_conditions[] = "r.is_approved = 1";
            } elseif ($status === 'pending') {
                $where_conditions[] = "r.is_approved = 0";
            } elseif ($status === 'verified') {
                $where_conditions[] = "r.is_verified = 1";
            }
        }
        
        if (!empty($where_conditions)) {
            $sql .= " WHERE " . implode(' AND ', $where_conditions);
        }
        
        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return (int)$row['total'];
    }

    // Lấy đánh giá theo id
    public static function getById($conn, $id) {
        $stmt = $conn->prepare("SELECT r.*, p.name as product_name, u.first_name, u.last_name, u.username 
                               FROM reviews r
                               LEFT JOIN products p ON r.product_id = p.id
                               LEFT JOIN users u ON r.user_id = u.id
                               WHERE r.id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Duyệt đánh giá
    public static function approve($conn, $id) {
        $stmt = $conn->prepare("UPDATE reviews SET is_approved = 1, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Ẩn đánh giá
    public static function hide($conn, $id) {
        $stmt = $conn->prepare("UPDATE reviews SET is_approved = 0, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Xóa đánh giá
    public static function delete($conn, $id) {
        // Xóa review_images trước
        $stmt = $conn->prepare("DELETE FROM review_images WHERE review_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        // Xóa review
        $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Thêm trả lời cho đánh giá
    public static function addReply($conn, $review_id, $admin_reply) {
        $stmt = $conn->prepare("UPDATE reviews SET admin_reply = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("si", $admin_reply, $review_id);
        return $stmt->execute();
    }

    // Đánh dấu đánh giá đã xác minh
    public static function markVerified($conn, $id) {
        $stmt = $conn->prepare("UPDATE reviews SET is_verified = 1, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Lấy thống kê đánh giá
    public static function getStats($conn) {
        $stats = [];
        
        // Tổng số đánh giá
        $result = $conn->query("SELECT COUNT(*) as total FROM reviews");
        $stats['total'] = $result->fetch_assoc()['total'];
        
        // Đánh giá đã duyệt
        $result = $conn->query("SELECT COUNT(*) as approved FROM reviews WHERE is_approved = 1");
        $stats['approved'] = $result->fetch_assoc()['approved'];
        
        // Đánh giá chờ duyệt
        $result = $conn->query("SELECT COUNT(*) as pending FROM reviews WHERE is_approved = 0");
        $stats['pending'] = $result->fetch_assoc()['pending'];
        
        // Đánh giá đã xác minh
        $result = $conn->query("SELECT COUNT(*) as verified FROM reviews WHERE is_verified = 1");
        $stats['verified'] = $result->fetch_assoc()['verified'];
        
        return $stats;
    }

    // Lấy đánh giá theo sản phẩm
    public static function getByProduct($conn, $product_id, $limit = 10) {
        $stmt = $conn->prepare("SELECT r.*, u.first_name, u.last_name, u.username 
                               FROM reviews r
                               LEFT JOIN users u ON r.user_id = u.id
                               WHERE r.product_id = ? AND r.is_approved = 1
                               ORDER BY r.created_at DESC LIMIT ?");
        $stmt->bind_param("ii", $product_id, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
        return $reviews;
    }
}
?> 