<?php
require_once 'model/Product.php';

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