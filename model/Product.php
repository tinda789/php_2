<?php
class Product {
    // Lấy tất cả sản phẩm (có phân trang, lọc theo tên)
    public static function getAll($conn, $limit = 10, $offset = 0, $search = '') {
        $sql = "SELECT p.*, c.name as category_name, b.name as brand_name FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN brands b ON p.brand_id = b.id";
        $params = [];
        $types = '';
        if ($search !== '') {
            $sql .= " WHERE p.name LIKE ?";
            $params[] = "%$search%";
            $types .= 's';
        }
        $sql .= " ORDER BY p.created_at DESC LIMIT ? OFFSET ?";
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
    public static function countAll($conn, $search = '') {
        $sql = "SELECT COUNT(*) as total FROM products";
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

    // Lấy sản phẩm theo id
    public static function getById($conn, $id) {
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Thêm mới sản phẩm
    public static function create($conn, $data) {
        $stmt = $conn->prepare("INSERT INTO products (name, slug, description, short_description, price, sale_price, stock_quantity, min_stock_level, category_id, brand_id, model, sku, barcode, weight, dimensions, warranty_period, status, featured, meta_title, meta_description, created_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->bind_param(
            "ssssddiiisssddsisissi",
            $data['name'],
            $data['slug'],
            $data['description'],
            $data['short_description'],
            $data['price'],
            $data['sale_price'],
            $data['stock_quantity'],
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
        $stmt = $conn->prepare("UPDATE products SET name=?, slug=?, description=?, short_description=?, price=?, sale_price=?, stock_quantity=?, min_stock_level=?, category_id=?, brand_id=?, model=?, sku=?, barcode=?, weight=?, dimensions=?, warranty_period=?, status=?, featured=?, meta_title=?, meta_description=?, updated_at=NOW() WHERE id=?");
        $stmt->bind_param(
            "ssssddiiisssddsisissi",
            $data['name'],
            $data['slug'],
            $data['description'],
            $data['short_description'],
            $data['price'],
            $data['sale_price'],
            $data['stock_quantity'],
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

    // thanhdat: Lấy danh sách ảnh cho sản phẩm
    public static function getImages($conn, $product_id) {
        $stmt = $conn->prepare("SELECT image_url FROM product_images WHERE product_id = ? ORDER BY sort_order ASC, id ASC");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $images = [];
        while ($row = $result->fetch_assoc()) {
            $images[] = $row['image_url'];
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
        $conn = $GLOBALS['conn'];
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
}
?> 