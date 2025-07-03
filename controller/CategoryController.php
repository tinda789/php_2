<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['user']) || !in_array($_SESSION['user']['role_name'], ['admin', 'super_admin'])) {
    header('Location: index.php');
    exit;
}
require_once 'config/config.php';
require_once 'model/Category.php';

$action = $_GET['action'] ?? 'index';

if ($action === 'index') {
    $limit = 10;
    $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $categories = Category::getAll($conn, $limit, $offset, $search);
    $total = Category::countAll($conn, $search);
    $total_pages = ceil($total / $limit);
    $current_page = $page;
    $view_file = 'view/admin/categories_admin.php';
    include 'view/layout/admin_layout.php';
    exit;
}

if ($action === 'create') {
    $parents = Category::getParents($conn);
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'name' => trim($_POST['name']),
            'description' => trim($_POST['description']),
            'parent_id' => !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null,
            'slug' => trim($_POST['slug']),
            'image' => trim($_POST['image']), // Xử lý upload ảnh sẽ bổ sung sau
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'sort_order' => (int)($_POST['sort_order'] ?? 0)
        ];
        if (empty($data['name']) || empty($data['slug'])) {
            $error = 'Tên và slug không được để trống!';
        } else {
            if (Category::create($conn, $data)) {
                header('Location: index.php?controller=category&action=index&msg=Thêm danh mục thành công');
                exit;
            } else {
                $error = 'Có lỗi khi thêm danh mục!';
            }
        }
    }
    $view_file = 'view/admin/create_category.php';
    include 'view/layout/admin_layout.php';
    exit;
}

if ($action === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $category = Category::getById($conn, $id);
    $parents = Category::getParents($conn);
    $error = '';
    if (!$category) {
        header('Location: index.php?controller=category&action=index&msg=Danh mục không tồn tại');
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'name' => trim($_POST['name']),
            'description' => trim($_POST['description']),
            'parent_id' => !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null,
            'slug' => trim($_POST['slug']),
            'image' => trim($_POST['image']), // Xử lý upload ảnh sẽ bổ sung sau
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'sort_order' => (int)($_POST['sort_order'] ?? 0)
        ];
        if (empty($data['name']) || empty($data['slug'])) {
            $error = 'Tên và slug không được để trống!';
        } else {
            if (Category::update($conn, $id, $data)) {
                header('Location: index.php?controller=category&action=index&msg=Cập nhật thành công');
                exit;
            } else {
                $error = 'Có lỗi khi cập nhật danh mục!';
            }
        }
    }
    $view_file = 'view/admin/edit_category.php';
    include 'view/layout/admin_layout.php';
    exit;
}

if ($action === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if (Category::delete($conn, $id)) {
        header('Location: index.php?controller=category&action=index&msg=Đã xóa danh mục');
        exit;
    } else {
        header('Location: index.php?controller=category&action=index&msg=Lỗi khi xóa danh mục');
        exit;
    }
} 