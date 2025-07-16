<?php
class Category {
    // Lấy tất cả danh mục (có thể phân trang sau)
    public static function getAll($conn, $limit = 10, $offset = 0, $search = '') {
        $sql = "SELECT * FROM categories";
        $params = [];
        $types = '';
        if ($search !== '') {
            $sql .= " WHERE name LIKE ?";
            $params[] = "%$search%";
            $types .= 's';
        }
        $sql .= " ORDER BY sort_order, name LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $types .= 'ii';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
        return $categories;
    }

    // Đếm tổng số danh mục
    public static function countAll($conn, $search = '') {
        $sql = "SELECT COUNT(*) as total FROM categories";
        $params = [];
        $types = '';
        if ($search !== '') {
            $sql .= " WHERE name LIKE ?";
            $params[] = "%$search%";
            $types .= 's';
        }
        $stmt = $conn->prepare($sql);
        if ($search !== '') {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return (int)$row['total'];
    }

    // Lấy danh mục theo id
    public static function getById($conn, $id) {
        $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Thêm mới danh mục
    public static function create($conn, $data) {
        $stmt = $conn->prepare("INSERT INTO categories (name, description, parent_id, slug, image, is_active, sort_order, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->bind_param(
            "ssissii",
            $data['name'],
            $data['description'],
            $data['parent_id'],
            $data['slug'],
            $data['image'],
            $data['is_active'],
            $data['sort_order']
        );
        return $stmt->execute();
    }

    // Cập nhật danh mục
    public static function update($conn, $id, $data) {
        // Nếu parent_id là null, cần bind đúng kiểu
        if ($data['parent_id'] === null) {
            $stmt = $conn->prepare("UPDATE categories SET name=?, description=?, parent_id=NULL, slug=?, image=?, is_active=?, sort_order=?, updated_at=NOW() WHERE id=?");
            $stmt->bind_param(
                "sssiiii", // image là string (s)
                $data['name'],
                $data['description'],
                $data['slug'],
                $data['image'],
                $data['is_active'],
                $data['sort_order'],
                $id
            );
        } else {
            $stmt = $conn->prepare("UPDATE categories SET name=?, description=?, parent_id=?, slug=?, image=?, is_active=?, sort_order=?, updated_at=NOW() WHERE id=?");
            $stmt->bind_param(
                "ssissiii", // image là string (s)
                $data['name'],
                $data['description'],
                $data['parent_id'],
                $data['slug'],
                $data['image'],
                $data['is_active'],
                $data['sort_order'],
                $id
            );
        }
        return $stmt->execute();
    }

    // Xóa danh mục
    public static function delete($conn, $id) {
        try {
            $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            // Nếu là lỗi ràng buộc khóa ngoại
            if (strpos($e->getMessage(), 'a foreign key constraint fails') !== false) {
                throw new Exception('Không thể xóa danh mục vì còn danh mục con hoặc sản phẩm liên quan.');
            }
            throw $e;
        }
    }

    // Lấy danh mục cha (parent_id IS NULL)
    public static function getParents($conn) {
        $sql = "SELECT * FROM categories WHERE parent_id IS NULL ORDER BY sort_order, name";
        $result = $conn->query($sql);
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
        return $categories;
    }
}
?> 