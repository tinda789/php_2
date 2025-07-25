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
        // Xóa dữ liệu cũ trong session nếu có
        if (isset($_SESSION['old_input'])) {
            unset($_SESSION['old_input']);
        }
        
        $categories = Product::getCategories($this->conn);
        $brands = Product::getBrands($this->conn);
        $view_file = 'view/admin/product_create.php';
        include 'view/layout/admin_layout.php';
    }

    // Lưu sản phẩm mới
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Phương thức yêu cầu không hợp lệ';
            header('Location: index.php?controller=admin&action=product_create');
            exit;
        }
        
        // Khởi tạo mảng lưu dữ liệu đã nhập
        $_SESSION['old_input'] = $_POST;

        try {
            // Bắt đầu transaction
            $this->conn->begin_transaction();

            // Xác thực dữ liệu đầu vào
            $required_fields = ['name', 'price', 'category_id', 'brand_id'];
            $errors = [];
            
            foreach ($required_fields as $field) {
                if (empty(trim($_POST[$field] ?? ''))) {
                    $errors[] = "Trường " . ucfirst(str_replace('_', ' ', $field)) . " là bắt buộc";
                }
            }

            // Kiểm tra giá và giá khuyến mãi
            $price = (float)($_POST['price'] ?? 0);
            $sale_price = (float)($_POST['sale_price'] ?? 0);
            
            if ($price <= 0) {
                $errors[] = "Giá sản phẩm phải lớn hơn 0";
            }
            
            if ($sale_price > 0 && $sale_price >= $price) {
                $errors[] = "Giá khuyến mãi phải nhỏ hơn giá gốc";
            }

            // Kiểm tra tồn kho
            $stock = (int)($_POST['stock'] ?? 0);
            if ($stock < 0) {
                $errors[] = "Số lượng tồn kho không được âm";
            }

            // Kiểm tra SKU và tổ hợp tên + thương hiệu + danh mục
            $sku = trim($_POST['sku'] ?? '');
            $name = trim($_POST['name'] ?? '');
            $brand_id = (int)($_POST['brand_id'] ?? 0);
            $category_id = (int)($_POST['category_id'] ?? 0);
            
            // Kiểm tra trùng SKU nếu có nhập
            if (!empty($sku)) {
                $stmt = $this->conn->prepare("SELECT id, name FROM products WHERE sku = ? LIMIT 1");
                $stmt->bind_param("s", $sku);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $product = $result->fetch_assoc();
                    $errors[] = "Mã SKU '$sku' đã được sử dụng cho sản phẩm '{$product['name']}'. Vui lòng nhập mã SKU khác.";
                }
                $stmt->close();
                
                // Kiểm tra trùng tổ hợp tên + thương hiệu + danh mục
                $stmt = $this->conn->prepare("SELECT id, sku FROM products WHERE name = ? AND brand_id = ? AND category_id = ? LIMIT 1");
                $stmt->bind_param("sii", $name, $brand_id, $category_id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $product = $result->fetch_assoc();
                    $errors[] = "Đã tồn tại sản phẩm cùng tên, cùng thương hiệu và cùng danh mục (SKU: {$product['sku']}). Vui lòng kiểm tra lại thông tin.";
                }
                $stmt->close();
            }

            // Kiểm tra file ảnh (nếu có)
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $max_file_size = 5 * 1024 * 1024; // 5MB
            $uploaded_files = [];
            
            // Kiểm tra nếu có tải lên ảnh
            if (!empty($_FILES['product_images']['name'][0])) {
                foreach ($_FILES['product_images']['tmp_name'] as $key => $tmp_name) {
                    $file_type = $_FILES['product_images']['type'][$key];
                    $file_size = $_FILES['product_images']['size'][$key];
                    $file_error = $_FILES['product_images']['error'][$key];
                    
                    if ($file_error !== UPLOAD_ERR_OK) {
                        $errors[] = "Lỗi khi tải lên ảnh: " . $this->getUploadError($file_error);
                        continue;
                    }
                    
                    if (!in_array($file_type, $allowed_types)) {
                        $errors[] = "Chỉ chấp nhận file ảnh định dạng JPG, PNG, GIF hoặc WebP";
                        continue;
                    }
                    
                    if ($file_size > $max_file_size) {
                        $errors[] = "Kích thước file không được vượt quá 5MB";
                        continue;
                    }
                }
            }

            // Nếu có lỗi, hiển thị và dừng xử lý
            if (!empty($errors)) {
                throw new Exception(implode("<br>", $errors));
            }

            // Chuẩn bị dữ liệu sản phẩm
            $data = [
                'name' => trim($_POST['name']),
                'slug' => $this->createSlug(trim($_POST['name'])),
                'description' => trim($_POST['description'] ?? ''),
                'short_description' => trim($_POST['short_description'] ?? ''),
                'price' => $price,
                'sale_price' => $sale_price > 0 ? $sale_price : null,
                'stock' => $stock,
                'min_stock_level' => max(0, (int)($_POST['min_stock_level'] ?? 0)),
                'category_id' => (int)$_POST['category_id'],
                'brand_id' => (int)$_POST['brand_id'],
                'model' => trim($_POST['model'] ?? ''),
                'sku' => trim($_POST['sku'] ?? ''),
                'barcode' => trim($_POST['barcode'] ?? ''),
                'weight' => max(0, (float)($_POST['weight'] ?? 0)),
                'dimensions' => trim($_POST['dimensions'] ?? ''),
                'warranty_period' => max(0, (int)($_POST['warranty_period'] ?? 0)),
                'status' => isset($_POST['status']) ? 1 : 0,
                'featured' => isset($_POST['featured']) ? 1 : 0,
                'created_by' => $_SESSION['user_id'] ?? 1,
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Tạo sản phẩm
            if (!Product::create($this->conn, $data)) {
                throw new Exception("Không thể tạo sản phẩm. Vui lòng thử lại sau.");
            }

            $product_id = $this->conn->insert_id;
            $target_dir = 'uploads/products/';
            $first_image_name = '';

            // Tạo thư mục nếu chưa tồn tại
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            // Xử lý upload ảnh
            foreach ($_FILES['product_images']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['product_images']['error'][$key] !== UPLOAD_ERR_OK) {
                    continue;
                }

                $file_name = basename($_FILES['product_images']['name'][$key]);
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $new_name = 'product_' . $product_id . '_' . time() . '_' . uniqid() . '.' . $file_ext;
                $target_file = $target_dir . $new_name;

                // Di chuyển file tải lên
                if (move_uploaded_file($tmp_name, $target_file)) {
                    $uploaded_files[] = $target_file;
                    
                    // Thêm vào bảng product_images
                    $stmt = $this->conn->prepare("INSERT INTO product_images (product_id, image_url) VALUES (?, ?)");
                    if (!$stmt) {
                        throw new Exception("Lỗi khi chuẩn bị câu lệnh SQL: " . $this->conn->error);
                    }
                    
                    $stmt->bind_param("is", $product_id, $new_name);
                    if (!$stmt->execute()) {
                        throw new Exception("Lỗi khi lưu ảnh sản phẩm: " . $stmt->error);
                    }
                    $stmt->close();

                    // Lưu ảnh đầu tiên làm ảnh đại diện
                    if ($first_image_name === '') {
                        $first_image_name = $new_name;
                        $update_stmt = $this->conn->prepare("UPDATE products SET image_link = ? WHERE id = ?");
                        $update_stmt->bind_param("si", $first_image_name, $product_id);
                        if (!$update_stmt->execute()) {
                            throw new Exception("Lỗi khi cập nhật ảnh đại diện: " . $update_stmt->error);
                        }
                        $update_stmt->close();
                    }
                }
            }

            // Commit transaction nếu mọi thứ thành công
            $this->conn->commit();
            
            // Xóa dữ liệu đã nhập trong session
            if (isset($_SESSION['old_input'])) {
                unset($_SESSION['old_input']);
            }
            
            // Xóa thông báo lỗi nếu có
            if (isset($_SESSION['error'])) {
                unset($_SESSION['error']);
            }
            
            // Gửi thông báo thành công
            $_SESSION['success'] = 'Thêm sản phẩm thành công!';
            header('Location: index.php?controller=admin&action=product_index');
            exit;

        } catch (Exception $e) {
            // Rollback transaction nếu có lỗi
            if (isset($this->conn) && $this->conn->ping()) {
                $this->conn->rollback();
            }
            
            // Xóa các file đã upload nếu có lỗi
            if (!empty($uploaded_files)) {
                foreach ($uploaded_files as $file) {
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
            }
            
            // Lưu thông báo lỗi và dữ liệu đã nhập để hiển thị lại form
            $_SESSION['error'] = $e->getMessage();
            $_SESSION['old_input'] = $_POST;
            
            // Chuyển hướng về trang tạo sản phẩm với thông báo lỗi
            header('Location: index.php?controller=product&action=create');
            exit;
        }
    }
    
    // Hàm lấy thông báo lỗi upload file
    private function getUploadError($error_code) {
        $upload_errors = [
            UPLOAD_ERR_INI_SIZE => 'Kích thước file vượt quá giới hạn cho phép',
            UPLOAD_ERR_FORM_SIZE => 'Kích thước file vượt quá giới hạn cho phép trong form',
            UPLOAD_ERR_PARTIAL => 'File chỉ được tải lên một phần',
            UPLOAD_ERR_NO_FILE => 'Không có file được tải lên',
            UPLOAD_ERR_NO_TMP_DIR => 'Thiếu thư mục tạm',
            UPLOAD_ERR_CANT_WRITE => 'Không thể ghi file vào ổ đĩa',
            UPLOAD_ERR_EXTENSION => 'Một tiện ích mở rộng PHP đã dừng việc tải lên file'
        ];
        
        return $upload_errors[$error_code] ?? 'Lỗi không xác định khi tải lên file';
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

    // Kiểm tra trùng lặp SKU sản phẩm
    private function checkDuplicateSKU($sku, $exclude_id = 0) {
        $sku = trim($sku);
        $errors = [];
        
        // Kiểm tra trùng SKU nếu có
        if (!empty($sku)) {
            $stmt = $this->conn->prepare("SELECT id, name FROM products WHERE sku = ? AND id != ? LIMIT 1");
            $stmt->bind_param("si", $sku, $exclude_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $product = $result->fetch_assoc();
                $errors[] = "Mã SKU '$sku' đã được sử dụng cho sản phẩm '{$product['name']}'. Vui lòng nhập mã SKU khác.";
            }
        }
        
        return $errors;
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
                'name' => trim($_POST['name'] ?? ''),
                'slug' => $this->createSlug(trim($_POST['name'] ?? '')),
                'description' => trim($_POST['description'] ?? ''),
                'short_description' => trim($_POST['short_description'] ?? ''),
                'price' => (float)($_POST['price'] ?? 0),
                'sale_price' => (float)($_POST['sale_price'] ?? 0),
                'stock' => (int)($_POST['stock'] ?? 0),
                'min_stock_level' => (int)($_POST['min_stock_level'] ?? 0),
                'category_id' => (int)($_POST['category_id'] ?? 0),
                'brand_id' => (int)($_POST['brand_id'] ?? 0),
                'model' => trim($_POST['model'] ?? ''),
                'sku' => trim($_POST['sku'] ?? ''),
                'barcode' => trim($_POST['barcode'] ?? ''),
                'weight' => (float)($_POST['weight'] ?? 0),
                'dimensions' => trim($_POST['dimensions'] ?? ''),
                'warranty_period' => (int)($_POST['warranty_period'] ?? 0),
                'status' => isset($_POST['status']) ? 1 : 0,
                'featured' => isset($_POST['featured']) ? 1 : 0,
                'meta_title' => trim($_POST['meta_title'] ?? ''),
                'meta_description' => trim($_POST['meta_description'] ?? '')
            ];
            
            // Kiểm tra dữ liệu đầu vào
            $errors = [];
            
            // Kiểm tra các trường bắt buộc
            if (empty($data['name'])) {
                $errors[] = "Vui lòng nhập tên sản phẩm";
            }
            
            if ($data['price'] <= 0) {
                $errors[] = "Giá sản phẩm phải lớn hơn 0";
            }
            
            if ($data['sale_price'] > 0 && $data['sale_price'] >= $data['price']) {
                $errors[] = "Giá khuyến mãi phải nhỏ hơn giá gốc";
            }
            
            // Kiểm tra trùng lặp SKU (bắt buộc phải khác nhau)
            $duplicate_errors = $this->checkDuplicateSKU($data['sku'], $id);
            $errors = array_merge($errors, $duplicate_errors);
            
            // Kiểm tra nếu SKU bị bỏ trống
            if (empty($data['sku'])) {
                $errors[] = "Vui lòng nhập mã SKU cho sản phẩm";
            }
            
            // Nếu có lỗi, quay lại form với thông báo lỗi
            if (!empty($errors)) {
                // Lưu lại dữ liệu đã nhập vào session
                $_SESSION['old_input'] = $_POST;
                $_SESSION['error'] = implode('<br>', $errors);
                header('Location: index.php?controller=admin&action=product_create');
                exit;
            }

            if (Product::update($this->conn, $id, $data)) {
                // Xử lý upload ảnh sản phẩm khi cập nhật
                if (!empty($_FILES['product_images']['name'][0])) {
                    $target_dir = 'uploads/products/';
                    $first_image_name = '';
                    foreach ($_FILES['product_images']['tmp_name'] as $key => $tmp_name) {
                        $file_name = basename($_FILES['product_images']['name'][$key]);
                        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                        $new_name = 'product_' . time() . '_' . rand(1000,9999) . '.' . $file_ext;
                        $target_file = $target_dir . $new_name;
                        if (move_uploaded_file($tmp_name, $target_file)) {
                            $stmt = $this->conn->prepare("INSERT INTO product_images (product_id, image_url) VALUES (?, ?)");
                            $stmt->bind_param("is", $id, $new_name);
                            if (!$stmt->execute()) {
                                echo "Lỗi thêm ảnh vào product_images: " . $stmt->error;
                            }
                            if ($first_image_name === '') {
                                $first_image_name = $new_name;
                            }
                        } else {
                            echo "Lỗi upload file: $file_name";
                        }
                    }
                    // Nếu có ảnh upload, cập nhật image_link của sản phẩm
                    if ($first_image_name !== '') {
                        $stmt = $this->conn->prepare("UPDATE products SET image_link = ? WHERE id = ?");
                        $stmt->bind_param("si", $first_image_name, $id);
                        if (!$stmt->execute()) {
                            echo "Lỗi update image_link: " . $stmt->error;
                        }
                    }
                }
                $_SESSION['success'] = 'Cập nhật sản phẩm thành công!';
                header('Location: index.php?controller=admin&action=product_index');
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
        $reviews = []; // (giả sử có code lấy reviews ở dưới)
        $view_file = 'view/user/product_detail.php';
        include 'view/layout/user_layout.php';
    }

    // Hiển thị danh sách sản phẩm cho người dùng
    public function list() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
        $limit = 12;
        $offset = ($page - 1) * $limit;

        require_once __DIR__ . '/../config/config.php';
        $categories = Product::getCategories($this->conn);
        
        // thanhdat: tìm kiếm thông minh - không chuyển đổi từ khóa, chỉ tìm kiếm trực tiếp
        if ($search !== '') {
            // Giữ nguyên từ khóa người dùng nhập, không chuyển đổi
            $search = trim($search);
        }
        
        $products = Product::getAll($this->conn, $limit, $offset, $search, $category_id, $sort);
        
        // thanhdat: lấy ảnh cho từng sản phẩm
        foreach ($products as &$product) {
            $product['images'] = Product::getImages($this->conn, $product['id']);
        }
        unset($product);
        
        $total = Product::countAll($this->conn, $search, $category_id);
        $totalPages = ceil($total / $limit);
        
        // Lưu các tham số tìm kiếm để dùng trong phân trang
        $search_params = [];
        if ($search) $search_params['search'] = $search;
        if ($category_id) $search_params['category_id'] = $category_id;
        if ($sort && $sort !== 'newest') $search_params['sort'] = $sort;
        $pagination_url = 'index.php?controller=product&action=list' . (!empty($search_params) ? '&' . http_build_query($search_params) : '');

        $view_file = 'view/user/product_list.php';
        include 'view/layout/header.php';
        include $view_file;
        include 'view/layout/footer.php';
    }

    // Nhận và lưu đánh giá sản phẩm
    public function review() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            $user_id = $_SESSION['user']['id'] ?? 0;
            $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
            $comment = trim($_POST['comment'] ?? '');
            if ($product_id > 0 && $user_id > 0 && $rating >= 1 && $rating <= 5 && $comment !== '') {
                if (Product::hasUserPurchased($this->conn, $user_id, $product_id)) {
                    if (Product::addReview($this->conn, $user_id, $product_id, $rating, $comment)) {
                        header('Location: index.php?controller=product&action=detail&id=' . $product_id . '&msg=review_sent');
                        exit;
                    } else {
                        header('Location: index.php?controller=product&action=detail&id=' . $product_id . '&error=review_failed');
                        exit;
                    }
                } else {
                    header('Location: index.php?controller=product&action=detail&id=' . $product_id . '&error=not_purchased');
                    exit;
                }
            } else {
                header('Location: index.php?controller=product&action=detail&id=' . $product_id . '&error=invalid_review');
                exit;
            }
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