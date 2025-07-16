<?php
require_once 'config/config.php';

class News {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    // Lấy tất cả tin tức
    public function getAllNews($category = null, $is_active = null, $limit = null, $offset = 0) {
        $sql = "SELECT n.*, c.name as category_name FROM news n LEFT JOIN categories c ON n.category = c.id WHERE 1=1";
        $params = [];
        $types = "";
        
        if ($category) {
            $sql .= " AND n.category = ?";
            $params[] = $category;
            $types .= "s";
        }
        
        if ($is_active !== null && $is_active !== '') {
            $sql .= " AND n.is_active = ?";
            $params[] = $is_active;
            $types .= "i";
        }
        
        $sql .= " ORDER BY n.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
            $types .= "ii";
        }
        
        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Lấy tin tức theo ID
    public function getNewsById($id) {
        $sql = "SELECT n.*, c.name as category_name FROM news n LEFT JOIN categories c ON n.category = c.id WHERE n.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    // Lấy tin tức theo slug
    public function getNewsBySlug($slug) {
        $sql = "SELECT * FROM news WHERE slug = ? AND status = 'published'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $slug);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    // Tạo tin tức mới
    public function createNews($data) {
        $sql = "INSERT INTO news (title, slug, summary, content, image_url, category, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssssi", 
            $data['title'],
            $data['slug'],
            $data['summary'],
            $data['content'],
            $data['image_url'],
            $data['category'],
            $data['is_active']
        );
        
        return $stmt->execute();
    }
    
    // Cập nhật tin tức
    public function updateNews($id, $data) {
        $sql = "UPDATE news SET 
                title = ?, slug = ?, summary = ?, content = ?, 
                image_url = ?, category = ?, is_active = ?, updated_at = CURRENT_TIMESTAMP 
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssssii", 
            $data['title'],
            $data['slug'],
            $data['summary'],
            $data['content'],
            $data['image_url'],
            $data['category'],
            $data['is_active'],
            $id
        );
        
        return $stmt->execute();
    }
    
    // Xóa tin tức
    public function deleteNews($id) {
        // Lấy thông tin ảnh để xóa file
        $news = $this->getNewsById($id);
        if ($news && $news['image_url']) {
            $image_path = "uploads/news/" . $news['image_url'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        
        $sql = "DELETE FROM news WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }
    
    // Thay đổi trạng thái tin tức
    public function changeStatus($id, $status) {
        $sql = "UPDATE news SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $status, $id);
        
        return $stmt->execute();
    }
    
    // Tăng lượt xem
    public function incrementViews($id) {
        $sql = "UPDATE news SET views = views + 1 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }
    
    // Upload ảnh tin tức
    public function uploadImage($file) {
        $target_dir = "uploads/news/";
        $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $allowed_extensions = array("jpg", "jpeg", "png", "gif", "webp");
        
        // Kiểm tra định dạng file
        if (!in_array($file_extension, $allowed_extensions)) {
            return false;
        }
        
        // Tạo tên file mới
        $new_filename = "news_" . time() . "_" . rand(1000, 9999) . "." . $file_extension;
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
    
    // Tạo slug từ title
    public function createSlug($title) {
        // Chuyển về chữ thường
        $slug = strtolower($title);
        
        // Thay thế các ký tự đặc biệt
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        
        // Thay thế khoảng trắng bằng dấu gạch ngang
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        
        // Loại bỏ dấu gạch ngang ở đầu và cuối
        $slug = trim($slug, '-');
        
        // Kiểm tra slug đã tồn tại chưa
        $original_slug = $slug;
        $counter = 1;
        
        while ($this->slugExists($slug)) {
            $slug = $original_slug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    // Bật/tắt trạng thái tin tức
    public function toggleStatus($id) {
        $news = $this->getNewsById($id);
        if (!$news) return false;
        $new_status = ($news['status'] == 1) ? 0 : 1;
        $sql = "UPDATE news SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $new_status, $id);
        return $stmt->execute();
    }

    // Kiểm tra slug đã tồn tại chưa
    public function slugExists($slug) {
        $sql = "SELECT COUNT(*) as count FROM news WHERE slug = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $slug);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }

    // Lấy tin tức mới nhất
    public function getLatestNews($limit = 5) {
        $sql = "SELECT * FROM news ORDER BY created_at DESC LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Lấy thống kê tin tức
    public function getNewsStats() {
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) as published,
                SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as draft,
                SUM(CASE WHEN status = 'archived' THEN 1 ELSE 0 END) as archived,
                SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive,
                SUM(views) as total_views,
                COUNT(DISTINCT category) as categories
                FROM news";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }
    
    // Lấy tin tức nổi bật (có nhiều lượt xem)
    public function getPopularNews($limit = 5) {
        $sql = "SELECT * FROM news WHERE status = 'published' 
                ORDER BY views DESC, created_at DESC LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Tìm kiếm tin tức
    public function searchNews($keyword, $limit = 10) {
        $sql = "SELECT * FROM news WHERE status = 'published' 
                AND (title LIKE ? OR summary LIKE ? OR content LIKE ?)
                ORDER BY created_at DESC LIMIT ?";
        $keyword = "%$keyword%";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssi", $keyword, $keyword, $keyword, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Đếm tổng số tin tức
    public function countNews($category = null, $status = null) {
        $sql = "SELECT COUNT(*) as count FROM news WHERE 1=1";
        $params = [];
        $types = "";
        
        if ($category) {
            $sql .= " AND category = ?";
            $params[] = $category;
            $types .= "s";
        }
        
        if ($status) {
            $sql .= " AND status = ?";
            $params[] = $status;
            $types .= "s";
        }
        
        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['count'];
    }
    
    // thanhdat: Thêm hàm getTotalNews để tương thích controller
    public function getTotalNews($category = null, $status = null) {
        return $this->countNews($category, $status);
    }

    // Lấy tin nổi bật (featured hoặc views cao)
    public function getFeaturedNews($limit = 5) {
        $sql = "SELECT * FROM news WHERE is_active = 1 AND (featured = 1 OR views >= 0) ORDER BY featured DESC, views DESC, created_at DESC LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy danh mục
    public function getCategories() {
        $sql = "SELECT * FROM categories WHERE is_active = 1 ORDER BY sort_order, name";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy tag (nếu có bảng tags)
    public function getTags() {
        if ($this->conn->query("SHOW TABLES LIKE 'tags'")->num_rows) {
            $sql = "SELECT * FROM tags ORDER BY name";
            $result = $this->conn->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    // Lấy tin theo category (public)
    public function getNewsByCategory($category_id, $limit = 10) {
        $sql = "SELECT * FROM news WHERE is_active = 1 AND category = ? ORDER BY created_at DESC LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $category_id, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy tin theo tag (nếu có)
    public function getNewsByTag($tag, $limit = 10) {
        $sql = "SELECT * FROM news WHERE is_active = 1 AND FIND_IN_SET(?, tags) ORDER BY created_at DESC LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $tag, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?> 