<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['user']) || !in_array($_SESSION['user']['role_name'], ['admin', 'super_admin'])) {
    header('Location: index.php');
    exit;
}
require_once 'config/config.php';
require_once 'model/User.php';
require_once 'model/Product.php';
require_once 'model/Category.php';

$action = $_GET['action'] ?? '';

if ($action === 'edit_user_admin' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    // Lấy thông tin user
    $stmt = $conn->prepare("SELECT u.*, r.name as role_name FROM users u
        LEFT JOIN user_roles ur ON u.id = ur.user_id
        LEFT JOIN roles r ON ur.role_id = r.id
        WHERE u.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $user_edit = $stmt->get_result()->fetch_assoc();

    // Lấy danh sách role
    $roles = [];
    $result = $conn->query("SELECT name FROM roles");
    while ($row = $result->fetch_assoc()) {
        $roles[] = $row['name'];
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $role = $_POST['role'];

        // Cập nhật thông tin user
        $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, email=?, phone=?, is_active=? WHERE id=?");
        $stmt->bind_param("ssssii", $first_name, $last_name, $email, $phone, $is_active, $id);
        $stmt->execute();

        // Cập nhật quyền
        $stmt = $conn->prepare("SELECT id FROM roles WHERE name=? LIMIT 1");
        $stmt->bind_param("s", $role);
        $stmt->execute();
        $role_id = $stmt->get_result()->fetch_assoc()['id'] ?? null;
        if ($role_id) {
            $stmt = $conn->prepare("UPDATE user_roles SET role_id=? WHERE user_id=?");
            $stmt->bind_param("ii", $role_id, $id);
            $stmt->execute();
        }

        header("Location: index.php?controller=user&action=manage");
        exit;
    }

    $view_file = 'view/admin/edit_user_admin.php';
    include 'view/layout/admin_layout.php';
    exit;
}

if ($action === 'delete_user_admin' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($id == $_SESSION['user']['id']) {
        echo '<div style="color:red;text-align:center;margin:40px 0;">Không thể xóa chính bạn!</div>';
        exit;
    }
    $stmt = $conn->prepare("DELETE FROM user_roles WHERE user_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: index.php?controller=user&action=manage");
    exit;
}

if ($action === 'add_user_admin') {
    $success = '';
    $error = '';
    $roles = [];
    $result = $conn->query("SELECT name FROM roles");
    while ($row = $result->fetch_assoc()) {
        $roles[] = $row['name'];
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $role = $_POST['role'];
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $username = trim($_POST['username']);
        $password = 'shop1234567890@';
        // Kiểm tra trùng username/email
        $stmt = $conn->prepare("SELECT id FROM users WHERE username=? OR email=?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = 'Tên đăng nhập hoặc email đã tồn tại!';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password_hash, email, first_name, last_name, phone, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssi", $username, $hash, $email, $first_name, $last_name, $phone, $is_active);
            if ($stmt->execute()) {
                $user_id = $conn->insert_id;
                // Gán quyền
                $stmt2 = $conn->prepare("SELECT id FROM roles WHERE name=? LIMIT 1");
                $stmt2->bind_param("s", $role);
                $stmt2->execute();
                $role_id = $stmt2->get_result()->fetch_assoc()['id'] ?? null;
                if ($role_id) {
                    $stmt3 = $conn->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
                    $stmt3->bind_param("ii", $user_id, $role_id);
                    $stmt3->execute();
                }
                $success = 'Tạo tài khoản thành công!';
                $created_user = [
                    'username' => $username,
                    'password' => $password,
                    'email' => $email
                ];
            } else {
                $error = 'Có lỗi khi tạo tài khoản!';
            }
        }
    }
    $view_file = 'view/admin/add_user_admin.php';
    include 'view/layout/admin_layout.php';
    exit;
}

// Category Management
if ($action === 'category_index') {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $limit = 10;
    $offset = ($page - 1) * $limit;

    $categories = Category::getAll($conn, $limit, $offset, $search);
    $total = Category::countAll($conn, $search);
    $totalPages = ceil($total / $limit);
    $current_page = $page;

    $view_file = 'view/admin/category_index.php';
    include 'view/layout/admin_layout.php';
    exit;
}

if ($action === 'category_create') {
    $parentCategories = Category::getParents($conn);
    $view_file = 'view/admin/create_category.php';
    include 'view/layout/admin_layout.php';
    exit;
}

if ($action === 'category_store') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'name' => $_POST['name'] ?? '',
            'slug' => $_POST['slug'] ?? '',
            'description' => $_POST['description'] ?? '',
            'parent_id' => (int)($_POST['parent_id'] ?? 0),
            'image' => $_POST['image'] ?? '',
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'sort_order' => 0
        ];
        if (Category::create($conn, $data)) {
            header('Location: index.php?controller=admin&action=category_index&success=1');
            exit;
        } else {
            header('Location: index.php?controller=admin&action=category_create&error=1');
            exit;
        }
    }
}

if ($action === 'category_edit') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) {
        header('Location: index.php?controller=admin&action=category_index');
        exit;
    }
    $category = Category::getById($conn, $id);
    if (!$category) {
        header('Location: index.php?controller=admin&action=category_index&error=not_found');
        exit;
    }
    $parents = Category::getParents($conn);
    $view_file = 'view/admin/edit_category.php';
    include 'view/layout/admin_layout.php';
    exit;
}

if ($action === 'category_update') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            header('Location: index.php?controller=admin&action=category_index');
            exit;
        }
        $data = [
            'name' => $_POST['name'] ?? '',
            'slug' => $_POST['slug'] ?? '',
            'description' => $_POST['description'] ?? '',
            'parent_id' => (int)($_POST['parent_id'] ?? 0),
            'image' => $_POST['image'] ?? '',
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'sort_order' => 0
        ];
        if (Category::update($conn, $id, $data)) {
            header('Location: index.php?controller=admin&action=category_index&success=2');
            exit;
        } else {
            header('Location: index.php?controller=admin&action=category_edit&id=' . $id . '&error=1');
            exit;
        }
    }
}

if ($action === 'category_delete') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) {
        header('Location: index.php?controller=admin&action=category_index');
        exit;
    }
    if (Category::delete($conn, $id)) {
        header('Location: index.php?controller=admin&action=category_index&success=3');
        exit;
    } else {
        header('Location: index.php?controller=admin&action=category_index&error=delete_failed');
        exit;
    }
}

// Product Management
if ($action === 'product_index') {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $limit = 10;
    $offset = ($page - 1) * $limit;

    $products = Product::getAll($conn, $limit, $offset, $search);
    $total = Product::countAll($conn, $search);
    $totalPages = ceil($total / $limit);

    $view_file = 'view/admin/product_index.php';
    include 'view/layout/admin_layout.php';
    exit;
}

if ($action === 'product_create') {
    $categories = Product::getCategories($conn);
    $brands = Product::getBrands($conn);
    $view_file = 'view/admin/product_create.php';
    include 'view/layout/admin_layout.php';
    exit;
}

if ($action === 'product_store') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'name' => $_POST['name'] ?? '',
            'slug' => createSlug($_POST['name']),
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

        if (Product::create($conn, $data)) {
            header('Location: index.php?controller=admin&action=product_index&success=1');
            exit;
        } else {
            header('Location: index.php?controller=admin&action=product_create&error=1');
            exit;
        }
    }
}

if ($action === 'product_edit') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) {
        header('Location: index.php?controller=admin&action=product_index');
        exit;
    }

    $product = Product::getById($conn, $id);
    if (!$product) {
        header('Location: index.php?controller=admin&action=product_index&error=not_found');
        exit;
    }

    $categories = Product::getCategories($conn);
    $brands = Product::getBrands($conn);
    $view_file = 'view/admin/product_edit.php';
    include 'view/layout/admin_layout.php';
    exit;
}

if ($action === 'product_update') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            header('Location: index.php?controller=admin&action=product_index');
            exit;
        }

        $data = [
            'name' => $_POST['name'] ?? '',
            'slug' => createSlug($_POST['name']),
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

        if (Product::update($conn, $id, $data)) {
            header('Location: index.php?controller=admin&action=product_index&success=2');
            exit;
        } else {
            header('Location: index.php?controller=admin&action=product_edit&id=' . $id . '&error=1');
            exit;
        }
    }
}

if ($action === 'product_delete') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) {
        header('Location: index.php?controller=admin&action=product_index');
        exit;
    }

    if (Product::delete($conn, $id)) {
        header('Location: index.php?controller=admin&action=product_index&success=3');
        exit;
    } else {
        header('Location: index.php?controller=admin&action=product_index&error=delete_failed');
        exit;
    }
}

if ($action === 'order_manage') { // thinh
    require_once 'model/Order.php'; // thinh
    $orders = Order::getAll($conn); // thinh
    $view_file = 'view/admin/order_manage.php'; // thinh
    include 'view/layout/admin_layout.php'; // thinh
    exit; // thinh
}

if ($action === 'order_update_status') { // thinh
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';
        require_once 'model/Order.php';
        Order::updateStatus($conn, $id, $status);
        $_SESSION['order_message'] = 'Cập nhật trạng thái thành công!';
        header('Location: index.php?controller=admin&action=order_manage');
        exit;
    }
}

if ($action === 'order_confirm') { // thinh
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)($_POST['id'] ?? 0);
        require_once 'model/Order.php';
        // Kiểm tra trạng thái hiện tại
        $stmt = $conn->prepare('SELECT status FROM orders WHERE id=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        if ($order && in_array($order['status'], ['pending', 'confirmed', 'processing', 'shipped'])) { // chỉ cho xác nhận nếu chưa giao/hủy/hoàn tiền
            Order::confirm($conn, $id);
            echo '
            <div style="width: 100vw; height: 100vh; background: #23272f; display: flex; justify-content: center; align-items: center; margin: 0; position: fixed; top: 0; left: 0; z-index: 9999;"> <!-- thinh -->
                <div style="background: #2d333b; color: #4fc3f7; padding: 48px 0; border-radius: 16px; font-size: 2.1rem; font-weight: 700; box-shadow: 0 2px 16px rgba(0,0,0,0.13); text-align: center; width: 100vw; max-width: 100vw; letter-spacing: 0.5px;"> <!-- thinh -->
                    Đã xác nhận đơn hàng thành công!<br>Đang chuyển về trang quản lý đơn hàng...
                </div>
            </div>
            <meta http-equiv="refresh" content="1.5;url=index.php?controller=admin&action=order_manage">'; // thinh
        } else {
            echo '<div style="text-align:center;margin-top:60px;font-size:1.3rem;color:red;">Không thể xác nhận đơn hàng này!</div>';
        }
        exit;
    }
}

if ($action === 'coupon_manage') { // thinh
    require_once 'model/Coupon.php';
    $coupons = Coupon::getAll($conn);
    $view_file = 'view/admin/coupon_manage.php';
    include 'view/layout/admin_layout.php';
    exit;
}

if ($action === 'coupon_create') { // thinh
    $view_file = 'view/admin/coupon_create.php';
    include 'view/layout/admin_layout.php';
    exit;
}

if ($action === 'coupon_store') { // thinh
    require_once 'model/Coupon.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'code' => $_POST['code'],
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'type' => $_POST['type'],
            'value' => (float)$_POST['value'],
            'minimum_amount' => (float)$_POST['minimum_amount'],
            'maximum_discount' => (float)$_POST['maximum_discount'],
            'usage_limit' => (int)$_POST['usage_limit'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'payment_method' => $_POST['payment_method'] ?? 'all'
        ];
        Coupon::create($conn, $data);
        echo '
        <div style="width: 100vw; height: 100vh; background: #23272f; display: flex; justify-content: center; align-items: center; margin: 0; position: fixed; top: 0; left: 0; z-index: 9999;"> <!-- thinh -->
            <div style="background: #2d333b; color: #4fc3f7; padding: 48px 0; border-radius: 16px; font-size: 2.1rem; font-weight: 700; box-shadow: 0 2px 16px rgba(0,0,0,0.13); text-align: center; width: 100vw; max-width: 100vw; letter-spacing: 0.5px;"> <!-- thinh -->
                Đã tạo mã giảm giá thành công!<br>Đang chuyển về trang quản lý mã giảm giá...
            </div>
        </div>
        <meta http-equiv="refresh" content="1.5;url=index.php?controller=admin&action=coupon_manage">';
        exit;
    }
}

// Helper function to create slug
function createSlug($name) {
    $slug = strtolower(trim($name));
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug;
}

include 'view/admin/dashboard.php';
?> 