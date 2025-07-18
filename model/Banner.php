<?php
require_once 'config/config.php';

class Banner {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    // Lấy tất cả banners
    public function getAllBanners($position = null, $status = null) {
        $sql = "SELECT * FROM banners WHERE 1=1";
        $params = [];
        $types = "";
        
        if ($position) {
            $sql .= " AND position = ?";
            $params[] = $position;
            $types .= "s";
        }
        
        if ($status) {
            $sql .= " AND status = ?";
            $params[] = $status;
            $types .= "s";
        }
        
        $sql .= " ORDER BY sort_order ASC, created_at DESC";
        
        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Lấy banner theo ID
    public function getBannerById($id) {
        $sql = "SELECT * FROM banners WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    // Tạo banner mới
    public function createBanner($data) {
        $sql = "INSERT INTO banners (title, description, image, link, position, status, sort_order) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssssi", 
            $data['title'],
            $data['description'],
            $data['image'],
            $data['link'],
            $data['position'],
            $data['status'],
            $data['sort_order']
        );
        
        return $stmt->execute();
    }
    
    // Cập nhật banner
    public function updateBanner($id, $data) {
        $sql = "UPDATE banners SET 
                title = ?, description = ?, image = ?, link = ?, 
                position = ?, status = ?, sort_order = ?, updated_at = CURRENT_TIMESTAMP 
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssssii", 
            $data['title'],
            $data['description'],
            $data['image'],
            $data['link'],
            $data['position'],
            $data['status'],
            $data['sort_order'],
            $id
        );
        
        return $stmt->execute();
    }
    
    // Xóa banner
    public function deleteBanner($id) {
        // Lấy thông tin ảnh để xóa file
        $banner = $this->getBannerById($id);
        if ($banner && $banner['image']) {
            $image_path = "uploads/banners/" . $banner['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        
        $sql = "DELETE FROM banners WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }
    
    // Bật/tắt banner
    public function toggleStatus($id) {
        $banner = $this->getBannerById($id);
        if (!$banner) return false;
        
        $new_status = ($banner['status'] == 1) ? 0 : 1;
        
        $sql = "UPDATE banners SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $new_status, $id);
        
        return $stmt->execute();
    }
    
    // Upload ảnh banner
    public function uploadImage($file) {
        $target_dir = "uploads/banners/";
        $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $allowed_extensions = array("jpg", "jpeg", "png", "gif", "webp");
        
        // Kiểm tra định dạng file
        if (!in_array($file_extension, $allowed_extensions)) {
            return false;
        }
        
        // Tạo tên file mới
        $new_filename = "banner_" . time() . "_" . rand(1000, 9999) . "." . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        // Kiểm tra kích thước file (max 5MB)
        if ($file["size"] > 5 * 1024 * 1024) {
            return false;
        }
        
        // Upload file
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return $new_filename;
        }
        
        return false;
    }
    
    // Lấy thống kê banner
    public function getBannerStats() {
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as inactive,
                COUNT(DISTINCT position) as positions
                FROM banners";
        $result = $this->conn->query($sql);
        
        return $result->fetch_assoc();
    }
    
    // Lấy banners theo vị trí
    public function getBannersByPosition($position, $limit = 5) {
        $sql = "SELECT * FROM banners WHERE position = ? AND status = 1 
                ORDER BY sort_order ASC, created_at DESC LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $position, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?> 