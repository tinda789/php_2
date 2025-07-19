<?php
require_once __DIR__ . '/../model/Product.php';
// Model sẽ được require từ index.php

class ProductController {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Hiển thị danh sách sản phẩm
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $products = Product::getAll($this->conn, $limit, $offset, $search);
        $total = Product::countAll($this->conn, $search);
        $totalPages = ceil($total / $limit);

        $view_file = 'view/admin/product_index.php';
        include 'view/layout/admin_layout.php';
    }

    // Hiển thị form thêm mới
    public function create() {
        $categories = Product::getCategories($this->conn);
        $brands = Product::getBrands($this->conn);
        $view_file = 'view/admin/product_create.php';
        include 'view/layout/admin_layout.php';
    }

    // Lưu sản phẩm mới
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'slug' => $this->createSlug($_POST['name']),
                'description' => $_POST['description'] ?? '',
                'short_description' => $_POST['short_description'] ?? '',
                'price' => (float)($_POST['price'] ?? 0),
                'sale_price' => (float)($_POST['sale_price'] ?? 0),
                'stock_quantity' => (int)($_POST['stock_quantity'] ?? 0),
                'min_stock_level' => (int)($_POST['min_stock_level'] ?? 0),
                'category_id' => (int)($_POST['category_id'] ?? 0),
                'brand_id' => (int)($_POST['brand_id'] ?? 0),
                'model' => $_POST['model'] ?? '',
                'sku' => $_POST['sku'] ?? '',
                'barcode' => $_POST['barcode'] ?? '',
                'weight' => (float)($_POST['weight'] ?? 0),
                'dimensions' => $_POST['dimensions'] ?? '',
                'warranty_period' => (int)($_POST['warranty_period'] ?? 0),
                'status' => (int)($_POST['status'] ?? 1),
                'featured' => (int)($_POST['featured'] ?? 0),
                'meta_title' => $_POST['meta_title'] ?? '',
                'meta_description' => $_POST['meta_description'] ?? '',
                'created_by' => $_SESSION['user_id'] ?? 1
            ];

            if (Product::create($this->conn, $data)) {
                $product_id = $this->conn->insert_id;
                // thanhdat: upload nhiều ảnh sản phẩm
                if (!empty($_FILES['product_images']['name'][0])) {
                    $target_dir = 'uploads/products/';
                    foreach ($_FILES['product_images']['tmp_name'] as $key => $tmp_name) {
                        $file_name = basename($_FILES['product_images']['name'][$key]);
                        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                        $new_name = 'product_' . time() . '_' . rand(1000,9999) . '.' . $file_ext;
                        $target_file = $target_dir . $new_name;
                        if (move_uploaded_file($tmp_name, $target_file)) {
                            $stmt = $this->conn->prepare("INSERT INTO product_images (product_id, image_url) VALUES (?, ?)");
                            $stmt->bind_param("is", $product_id, $new_name);
                            $stmt->execute();
                        }
                    }
                }
                header('Location: index.php?controller=product&action=index&success=1');
                exit;
            } else {
                header('Location: index.php?controller=product&action=create&error=1');
                exit;
            }
        }
    }

    // Hiển thị form chỉnh sửa
    public function edit() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: index.php?controller=product&action=index');
            exit;
        }

        $product = Product::getById($this->conn, $id);
        if (!$product) {
            header('Location: index.php?controller=product&action=index&error=not_found');
            exit;
        }

        $categories = Product::getCategories($this->conn);
        $brands = Product::getBrands($this->conn);
        $view_file = 'view/admin/product_edit.php';
        include 'view/layout/admin_layout.php';
    }

    // Cập nhật sản phẩm
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) {
                header('Location: index.php?controller=product&action=index');
                exit;
            }

            $data = [
                'name' => $_POST['name'] ?? '',
                'slug' => $this->createSlug($_POST['name']),
                'description' => $_POST['description'] ?? '',
                'short_description' => $_POST['short_description'] ?? '',
                'price' => (float)($_POST['price'] ?? 0),
                'sale_price' => (float)($_POST['sale_price'] ?? 0),
                'stock_quantity' => (int)($_POST['stock_quantity'] ?? 0),
                'min_stock_level' => (int)($_POST['min_stock_level'] ?? 0),
                'category_id' => (int)($_POST['category_id'] ?? 0),
                'brand_id' => (int)($_POST['brand_id'] ?? 0),
                'model' => $_POST['model'] ?? '',
                'sku' => $_POST['sku'] ?? '',
                'barcode' => $_POST['barcode'] ?? '',
                'weight' => (float)($_POST['weight'] ?? 0),
                'dimensions' => $_POST['dimensions'] ?? '',
                'warranty_period' => (int)($_POST['warranty_period'] ?? 0),
                'status' => (int)($_POST['status'] ?? 1),
                'featured' => (int)($_POST['featured'] ?? 0),
                'meta_title' => $_POST['meta_title'] ?? '',
                'meta_description' => $_POST['meta_description'] ?? ''
            ];

            if (Product::update($this->conn, $id, $data)) {
                // thanhdat: upload nhiều ảnh sản phẩm khi cập nhật
                if (!empty($_FILES['product_images']['name'][0])) {
                    $target_dir = 'uploads/products/';
                    foreach ($_FILES['product_images']['tmp_name'] as $key => $tmp_name) {
                        $file_name = basename($_FILES['product_images']['name'][$key]);
                        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                        $new_name = 'product_' . time() . '_' . rand(1000,9999) . '.' . $file_ext;
                        $target_file = $target_dir . $new_name;
                        if (move_uploaded_file($tmp_name, $target_file)) {
                            $stmt = $this->conn->prepare("INSERT INTO product_images (product_id, image_url) VALUES (?, ?)");
                            $stmt->bind_param("is", $id, $new_name);
                            $stmt->execute();
                        }
                    }
                }
                header('Location: index.php?controller=product&action=index&success=2');
                exit;
            } else {
                header('Location: index.php?controller=product&action=edit&id=' . $id . '&error=1');
                exit;
            }
        }
    }

    // Xóa sản phẩm
    public function delete() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: index.php?controller=product&action=index');
            exit;
        }

        if (Product::delete($this->conn, $id)) {
            header('Location: index.php?controller=product&action=index&success=3');
            exit;
        } else {
            header('Location: index.php?controller=product&action=index&error=delete_failed');
            exit;
        }
    }

    // Hiển thị chi tiết sản phẩm cho người dùng
    public function detail() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            header('Location: index.php');
            exit;
        }
        $product = Product::getById($this->conn, $id);
        if (!$product) {
            header('Location: index.php?msg=Không tìm thấy sản phẩm');
            exit;
        }
        // Lấy ảnh sản phẩm
        $product['images'] = Product::getImages($this->conn, $id);
        // Lấy thông số kỹ thuật
        $product['specs'] = Product::getSpecifications($this->conn, $id);
        // Lấy đánh giá sản phẩm (chỉ lấy 5 đánh giá mới nhất, đã duyệt)
        $reviews = Review::getByProduct($this->conn, $id, 5);
        $view_file = 'view/user/product_detail.php';
        include 'view/layout/header.php';
        include $view_file;
        include 'view/layout/footer.php';
    }

    // Hiển thị danh sách sản phẩm cho người dùng
    public function list() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
        $limit = 12;
        $offset = ($page - 1) * $limit;

        require_once __DIR__ . '/../config/config.php';
        $categories = Product::getCategories($this->conn);
        
            // thanhdat: tìm kiếm thông minh - không chuyển đổi từ khóa, chỉ tìm kiếm trực tiếp
        if ($search !== '') {
            // Giữ nguyên từ khóa người dùng nhập, không chuyển đổi
            $search = trim($search);
        }
        
        $products = Product::getAll($this->conn, $limit, $offset, $search, $category_id);
        
        // thanhdat: lấy ảnh cho từng sản phẩm
        foreach ($products as &$product) {
            $product['images'] = Product::getImages($this->conn, $product['id']);
        }
        unset($product);
        
        $total = Product::countAll($this->conn, $search, $category_id);
        $totalPages = ceil($total / $limit);

        $view_file = 'view/user/product_list.php';
        include 'view/layout/header.php';
        include $view_file;
        include 'view/layout/footer.php';
    }

    // Tạo slug từ tên sản phẩm
    private function createSlug($name) {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }
}
?> 