<?php
require_once __DIR__ . '/../config/config.php';

class Product {
    // Lấy tất cả sản phẩm (có phân trang, lọc theo tên, sắp xếp)
    public static function getAll($conn, $limit = 10, $offset = 0, $search = '', $category_id = 0, $sort = 'newest') {
        $sql = "SELECT p.*, c.name as category_name, b.name as brand_name FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN brands b ON p.brand_id = b.id";
        $params = [];
        $types = '';
        $where_conditions = [];
        
        // Chỉ lấy sản phẩm active
        $where_conditions[] = "p.status = 'active'";
        
        // Ưu tiên tìm kiếm sản phẩm có tên chứa nguyên cụm từ khóa (không tách từ)
        if ($search !== '') {
            $like_param = '%' . strtolower(trim($search)) . '%';
            $stmt = $conn->prepare($sql . " WHERE p.status = 'active' AND LOWER(p.name) LIKE ? ORDER BY p.created_at DESC LIMIT ? OFFSET ?");
            $stmt->bind_param('sii', $like_param, $limit, $offset);
            $stmt->execute();
            $result = $stmt->get_result();
            $exact_products = [];
            while ($row = $result->fetch_assoc()) {
                $exact_products[] = $row;
            }
            if (!empty($exact_products)) {
                return $exact_products;
            }
        }
        
        // thanhdat: tìm kiếm thông minh - tìm theo từ khóa gốc và danh mục
        if ($search !== '') {
            $search_terms = explode(' ', trim($search));
            $search_conditions = [];
            
            // Tìm kiếm thông minh theo cụm từ gốc trước
            $smart_search_global = [];
            if (strpos($search, 'điện thoại') !== false || strpos($search, 'dien thoai') !== false || strpos($search, 'smartphone') !== false) {
                // Tìm tất cả sản phẩm trong danh mục điện thoại và danh mục con
                $smart_search_global[] = "(c.id = 1 OR c.parent_id = 1)";
            } elseif (strpos($search, 'laptop') !== false || strpos($search, 'máy tính') !== false || strpos($search, 'may tinh') !== false || strpos($search, 'pc') !== false) {
                // Tìm tất cả sản phẩm trong danh mục máy tính và danh mục con
                $smart_search_global[] = "(c.id = 2 OR c.parent_id = 2)";
            } elseif (strpos($search, 'máy ảnh') !== false || strpos($search, 'may anh') !== false || strpos($search, 'camera') !== false) {
                // Tìm tất cả sản phẩm trong danh mục máy ảnh và danh mục con
                $smart_search_global[] = "(c.id = 3 OR c.parent_id = 3)";
            } elseif (strpos($search, 'linh kiện') !== false || strpos($search, 'linh kien') !== false || strpos($search, 'ram') !== false || strpos($search, 'cpu') !== false || strpos($search, 'vga') !== false) {
                // Tìm tất cả sản phẩm trong danh mục linh kiện PC và danh mục con
                $smart_search_global[] = "(c.id = 4 OR c.parent_id = 4)";
            }
            
            foreach ($search_terms as $original_term) {
                if (strlen($original_term) > 1) { // Chỉ tìm từ có ít nhất 2 ký tự
                    $term = '%' . $original_term . '%';
                    
                    // Tìm kiếm thông minh theo từ khóa
                    $smart_search = [];
                    
                    // Tìm trong tên sản phẩm, SKU, model, tên danh mục, tên thương hiệu
                    $smart_search[] = "(p.name LIKE ? OR p.sku LIKE ? OR p.model LIKE ? OR c.name LIKE ? OR b.name LIKE ?)";
                    $params[] = $term;
                    $params[] = $term;
                    $params[] = $term;
                    $params[] = $term;
                    $params[] = $term;
                    $types .= 'sssss';
                    
                    $search_conditions[] = '(' . implode(' OR ', $smart_search) . ')';
                }
            }
            
            // Kết hợp tìm kiếm từng từ và tìm kiếm thông minh
            if (!empty($search_conditions) || !empty($smart_search_global)) {
                $all_conditions = array_merge($search_conditions, $smart_search_global);
                $where_conditions[] = '(' . implode(' OR ', $all_conditions) . ')';
            }
        }
        
        // Lọc theo danh mục
        if ($category_id > 0) {
            $where_conditions[] = "p.category_id = ?";
            $params[] = $category_id;
            $types .= 'i';
        }
        
        if (!empty($where_conditions)) {
            $sql .= " WHERE " . implode(' AND ', $where_conditions);
        }
        
        // Thêm sắp xếp
        switch ($sort) {
            case 'price_asc':
                $sql .= " ORDER BY p.price ASC, p.name ASC";
                break;
            case 'price_desc':
                $sql .= " ORDER BY p.price DESC, p.name ASC";
                break;
            case 'name_asc':
                $sql .= " ORDER BY p.name ASC, p.price ASC";
                break;
            case 'name_desc':
                $sql .= " ORDER BY p.name DESC, p.price ASC";
                break;
            case 'newest':
            default:
                $sql .= " ORDER BY p.created_at DESC, p.id DESC";
                break;
        }
        
        // Thêm phân trang
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $types .= 'ii';
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products;
    }

    // Đếm tổng số sản phẩm (lọc)
    public static function countAll($conn, $search = '', $category_id = 0) {
        $sql = "SELECT COUNT(*) as total FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN brands b ON p.brand_id = b.id";
        $params = [];
        $types = '';
        $where_conditions = [];
        
        // Chỉ lấy sản phẩm active
        $where_conditions[] = "p.status = 'active'";
        
        // thanhdat: tìm kiếm thông minh - tìm theo từ khóa gốc và danh mục
        if ($search !== '') {
            $search_terms = explode(' ', trim($search));
            $search_conditions = [];
            
            // Tìm kiếm thông minh theo cụm từ gốc trước
            $smart_search_global = [];
            if (strpos($search, 'điện thoại') !== false || strpos($search, 'dien thoai') !== false || strpos($search, 'smartphone') !== false) {
                // Tìm tất cả sản phẩm trong danh mục điện thoại và danh mục con
                $smart_search_global[] = "(c.id = 1 OR c.parent_id = 1)";
            } elseif (strpos($search, 'laptop') !== false || strpos($search, 'máy tính') !== false || strpos($search, 'may tinh') !== false || strpos($search, 'pc') !== false) {
                // Tìm tất cả sản phẩm trong danh mục máy tính và danh mục con
                $smart_search_global[] = "(c.id = 2 OR c.parent_id = 2)";
            } elseif (strpos($search, 'máy ảnh') !== false || strpos($search, 'may anh') !== false || strpos($search, 'camera') !== false) {
                // Tìm tất cả sản phẩm trong danh mục máy ảnh và danh mục con
                $smart_search_global[] = "(c.id = 3 OR c.parent_id = 3)";
            } elseif (strpos($search, 'linh kiện') !== false || strpos($search, 'linh kien') !== false || strpos($search, 'ram') !== false || strpos($search, 'cpu') !== false || strpos($search, 'vga') !== false) {
                // Tìm tất cả sản phẩm trong danh mục linh kiện PC và danh mục con
                $smart_search_global[] = "(c.id = 4 OR c.parent_id = 4)";
            }
            
            foreach ($search_terms as $original_term) {
                if (strlen($original_term) > 1) { // Chỉ tìm từ có ít nhất 2 ký tự
                    $term = '%' . $original_term . '%';
                    
                    // Tìm kiếm thông minh theo từ khóa
                    $smart_search = [];
                    
                    // Tìm trong tên sản phẩm, SKU, model, tên danh mục, tên thương hiệu
                    $smart_search[] = "(p.name LIKE ? OR p.sku LIKE ? OR p.model LIKE ? OR c.name LIKE ? OR b.name LIKE ?)";
                    $params[] = $term;
                    $params[] = $term;
                    $params[] = $term;
                    $params[] = $term;
                    $params[] = $term;
                    $types .= 'sssss';
                    
                    $search_conditions[] = '(' . implode(' OR ', $smart_search) . ')';
                }
            }
            
            // Kết hợp tìm kiếm từng từ và tìm kiếm thông minh
            if (!empty($search_conditions) || !empty($smart_search_global)) {
                $all_conditions = array_merge($search_conditions, $smart_search_global);
                $where_conditions[] = '(' . implode(' OR ', $all_conditions) . ')';
            }
        }
        
        // Lọc theo danh mục
        if ($category_id > 0) {
            $where_conditions[] = "p.category_id = ?";
            $params[] = $category_id;
            $types .= 'i';
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

    // Lấy sản phẩm theo id
    public static function getById($conn, $id) {
        $stmt = $conn->prepare("SELECT p.*, c.name as category_name, b.name as brand_name FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN brands b ON p.brand_id = b.id
                WHERE p.id = ?"); // Bỏ kiểm tra status
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Thêm mới sản phẩm
    public static function create($conn, $data) {
        $stmt = $conn->prepare("INSERT INTO products (name, slug, description, short_description, price, sale_price, stock, min_stock_level, category_id, brand_id, model, sku, barcode, weight, dimensions, warranty_period, status, featured, image_link, meta_title, meta_description, created_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->bind_param(
            "ssssddiiisssddsisisssi",
            $data['name'],
            $data['slug'],
            $data['description'],
            $data['short_description'],
            $data['price'],
            $data['sale_price'],
            $data['stock'],
            $data['min_stock_level'],
            $data['category_id'],
            $data['brand_id'],
            $data['model'],
            $data['sku'],
            $data['barcode'],
            $data['weight'],
            $data['dimensions'],
            $data['warranty_period'],
            $data['status'],
            $data['featured'],
            $data['image_link'],
            $data['meta_title'],
            $data['meta_description'],
            $data['created_by']
        );
        
        if ($stmt->execute()) {
            return $conn->insert_id; // Trả về ID của sản phẩm vừa tạo
        }
        return false;
    }

    // Cập nhật sản phẩm
    public static function update($conn, $id, $data) {
        $stmt = $conn->prepare("UPDATE products SET name=?, slug=?, description=?, short_description=?, price=?, sale_price=?, stock=?, min_stock_level=?, category_id=?, brand_id=?, model=?, sku=?, barcode=?, weight=?, dimensions=?, warranty_period=?, status=?, featured=?, image_link=?, meta_title=?, meta_description=?, updated_at=NOW() WHERE id=?");
        $stmt->bind_param(
            "ssssddiiisssddsisisssi",
            $data['name'],
            $data['slug'],
            $data['description'],
            $data['short_description'],
            $data['price'],
            $data['sale_price'],
            $data['stock'],
            $data['min_stock_level'],
            $data['category_id'],
            $data['brand_id'],
            $data['model'],
            $data['sku'],
            $data['barcode'],
            $data['weight'],
            $data['dimensions'],
            $data['warranty_period'],
            $data['status'],
            $data['featured'],
            $data['image_link'],
            $data['meta_title'],
            $data['meta_description'],
            $id
        );
        return $stmt->execute();
    }

    // Xóa sản phẩm
    public static function delete($conn, $id) {
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Lấy danh sách danh mục
    public static function getCategories($conn) {
        $sql = "SELECT id, name FROM categories ORDER BY name";
        $result = $conn->query($sql);
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
        return $categories;
    }

    // Lấy danh sách thương hiệu
    public static function getBrands($conn) {
        $sql = "SELECT id, name FROM brands ORDER BY name";
        $result = $conn->query($sql);
        $brands = [];
        while ($row = $result->fetch_assoc()) {
            $brands[] = $row;
        }
        return $brands;
    }

    // thanhdat: Lấy ảnh sản phẩm
    public static function getImages($conn, $product_id) {
        $stmt = $conn->prepare("SELECT * FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, sort_order ASC");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $images = [];
        while ($row = $result->fetch_assoc()) {
            $images[] = $row;
        }
        return $images;
    }

    // Lấy thông số kỹ thuật sản phẩm
    public static function getSpecifications($conn, $product_id) {
        $stmt = $conn->prepare("SELECT spec_name, spec_value, spec_group FROM product_specifications WHERE product_id = ? ORDER BY sort_order ASC, id ASC");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $specs = [];
        while ($row = $result->fetch_assoc()) {
            $specs[] = $row;
        }
        return $specs;
    }

    // Lấy tất cả sản phẩm cho người dùng (chỉ sản phẩm active)
    public static function getAllForUser($conn, $limit = 12, $offset = 0, $search = '', $category_id = 0) {
        $sql = "SELECT p.*, c.name as category_name, b.name as brand_name FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN brands b ON p.brand_id = b.id
                WHERE p.status = 1";
        $params = [];
        $types = '';
        
        if ($search !== '') {
            $sql .= " AND p.name LIKE ?";
            $params[] = "%$search%";
            $types .= 's';
        }
        
        if ($category_id > 0) {
            $sql .= " AND p.category_id = ?";
            $params[] = $category_id;
            $types .= 'i';
        }
        
        $sql .= " ORDER BY p.featured DESC, p.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $types .= 'ii';
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products;
    }

    // Đếm tổng số sản phẩm cho người dùng (chỉ sản phẩm active)
    public static function countAllForUser($conn, $search = '', $category_id = 0) {
        $sql = "SELECT COUNT(*) as total FROM products WHERE status = 1";
        $params = [];
        $types = '';
        
        if ($search !== '') {
            $sql .= " AND name LIKE ?";
            $params[] = "%$search%";
            $types .= 's';
        }
        
        if ($category_id > 0) {
            $sql .= " AND category_id = ?";
            $params[] = $category_id;
            $types .= 'i';
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

    // Lấy sản phẩm theo id (dùng cho Cart)
    public static function findById($product_id) {
        if (!isset($GLOBALS['conn'])) {
            require_once __DIR__ . '/../config/config.php';
        }
        $conn = $GLOBALS['conn'];
        if (!$conn) {
            throw new Exception('Database connection not found!');
        }
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        if ($product) {
            // Lấy thêm ảnh
            $product['images'] = self::getImages($conn, $product_id);
            // Lấy thêm thông số kỹ thuật
            $product['specs'] = self::getSpecifications($conn, $product_id);
        }
        return $product;
    }

    // Kiểm tra user đã mua sản phẩm chưa
    public static function hasUserPurchased($conn, $user_id, $product_id) {
        $sql = "SELECT COUNT(*) as total FROM order_items oi
                JOIN orders o ON oi.order_id = o.id
                WHERE o.user_id = ? AND oi.product_id = ? AND o.status IN ('completed', 'delivered')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $user_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'] > 0;
    }

    // Thêm đánh giá sản phẩm
    public static function addReview($conn, $user_id, $product_id, $rating, $comment) {
        $stmt = $conn->prepare("INSERT INTO product_reviews (product_id, user_id, rating, comment, created_at, status) VALUES (?, ?, ?, ?, NOW(), 'pending')");
        $stmt->bind_param('iiis', $product_id, $user_id, $rating, $comment);
        return $stmt->execute();
    }

    // Lấy danh sách đánh giá đã duyệt cho sản phẩm
    public static function getReviews($conn, $product_id) {
        $sql = "SELECT r.*, u.first_name, u.last_name FROM product_reviews r
                JOIN users u ON r.user_id = u.id
                WHERE r.product_id = ? AND r.status = 'approved'
                ORDER BY r.created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
        return $reviews;
    }
    
    // Lấy sản phẩm nổi bật
    public static function getFeaturedProducts($conn, $limit = 8) {
        $sql = "SELECT p.*, 
                       CASE 
                           WHEN p.sale_price > 0 AND p.price > 0 
                           THEN ROUND(((p.price - p.sale_price) / p.price) * 100) 
                           ELSE 0 
                       END as discount,
                       DATEDIFF(NOW(), p.created_at) <= 30 as is_new
                FROM products p 
                WHERE p.featured = 1 
                AND p.status = 'active'
                ORDER BY p.created_at DESC 
                LIMIT ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $products = [];
        while ($row = $result->fetch_assoc()) {
            // Convert is_new from 1/0 to boolean
            $row['is_new'] = (bool)$row['is_new'];
            $products[] = $row;
        }
        return $products;
    }
}
?>