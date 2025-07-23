<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['user']) || !in_array($_SESSION['user']['role_name'], ['admin', 'super_admin'])) {
    header('Location: index.php');
    exit;
}

require_once 'config/config.php';
require_once 'model/Brand.php';

$action = $_GET['action'] ?? 'index';

// ------------------------
// HIỂN THỊ DANH SÁCH THƯƠNG HIỆU
// ------------------------
if ($action === 'index') {
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $brands = Brand::getAll($conn, $search);
    $view_file = 'view/admin/brand_index.php';  
    include 'view/layout/admin_layout.php';
    exit;
}

// ------------------------
// THÊM THƯƠNG HIỆU MỚI
// ------------------------
if ($action === 'create') {
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $website = trim($_POST['website']);
        $created_at = $_POST['created_at'] ?? date('Y-m-d H:i:s');
        $is_active = isset($_POST['is_active']) && $_POST['is_active'] == '1' ? 1 : 0;

        $logo_path = '';
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $targetDir = 'uploads/brands/';
            if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

            $fileName = basename($_FILES['logo']['name']);
            $targetFile = $targetDir . time() . '_' . $fileName;
            move_uploaded_file($_FILES['logo']['tmp_name'], $targetFile);
            $logo_path = $targetFile;
        }

        if (empty($name)) {
            $error = 'Tên thương hiệu không được để trống!';
        } else {
            $data = [
                'name' => $name,
                'description' => $description,
                'logo' => $logo_path,
                'website' => $website,
                'is_active' => $is_active,
                'created_at' => $created_at
            ];

            if (Brand::create($conn, $data)) {
                header('Location: index.php?controller=brand&action=index&success=1');
                exit;
            } else {
                $error = 'Có lỗi khi thêm thương hiệu!';
            }
        }
    }
    $view_file = 'view/admin/brand_create.php';
    include 'view/layout/admin_layout.php';
    exit;
}

// ------------------------
// SỬA THƯƠNG HIỆU
// ------------------------
if ($action === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $brand = Brand::getById($conn, $id);
    $error = '';

    // Kiểm tra xem brand có tồn tại không
    if (!$brand) {
        header('Location: index.php?controller=admin&action=brand_index&error=not_found');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'name' => trim($_POST['name']),
            'description' => trim($_POST['description']),
            'website' => trim($_POST['website']),
            'is_active' => isset($_POST['is_active']) ? (int)$_POST['is_active'] : 0,
            'logo' => $brand['logo'] // Giữ logo cũ nếu không upload mới
        ];

        // Xử lý upload logo mới
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $targetDir = 'uploads/brands/';
            if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

            $fileName = basename($_FILES['logo']['name']);
            $targetFile = $targetDir . time() . '_' . $fileName;
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetFile)) {
                $data['logo'] = $targetFile;
            }
        }

        if (empty($data['name'])) {
            $error = 'Tên thương hiệu không được để trống!';
        } else {
            if (Brand::update($conn, $id, $data)) {
                header('Location: index.php?controller=brand&action=index&success=2');
                exit;
            } else {
                $error = 'Có lỗi khi cập nhật thương hiệu!';
            }
        }
    }
    
    $view_file = 'view/admin/brand_edit.php';
    include 'view/layout/admin_layout.php';
    exit;
}

// ------------------------
// XÓA THƯƠNG HIỆU
// ------------------------
if ($action === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if (Brand::delete($conn, $id)) {
        header('Location: index.php?controller=brand&action=index&success=3');
        exit;
    } else {
        header('Location: index.php?controller=brand&action=index&error=delete_failed');
        exit;
    }
}