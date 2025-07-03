<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'config/config.php';
require_once 'model/User.php'; // Đảm bảo đã require model

$user = $_SESSION['user'] ?? null;
$error = '';
$success = '';
$action = $_GET['action'] ?? '';

// Chỉ admin/super_admin mới được truy cập các chức năng quản lý user
function is_admin() {
    return isset($_SESSION['user']['role_name']) && in_array($_SESSION['user']['role_name'], ['admin', 'super_admin']);
}

if ($action === 'manage' || $action === 'add' || ($action === 'edit' && isset($_GET['id'])) || ($action === 'delete' && isset($_GET['id']))) {
    if (!is_admin()) {
        echo '<div style="color:red;text-align:center;margin:40px 0;">Bạn không có quyền truy cập chức năng này!</div>';
        exit;
    }
}

if ($action === 'edit' && $user) {
    // Lấy lại thông tin mới nhất từ DB
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $phone = trim($_POST['phone']);
        $email = trim($_POST['email']);

        // Kiểm tra hợp lệ
        if (empty($first_name) || empty($last_name)) {
            $error = "Vui lòng nhập đầy đủ họ và tên!";
        } elseif (!preg_match('/^[0-9]{8,15}$/', $phone)) {
            $error = "Số điện thoại không hợp lệ!";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Email không hợp lệ!";
        } else {
            // Kiểm tra trùng email với user khác
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->bind_param("si", $email, $user['id']);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $error = "Email đã được sử dụng!";
            } else {
                // Cập nhật
                $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, phone=?, email=? WHERE id=?");
                $stmt->bind_param("ssssi", $first_name, $last_name, $phone, $email, $user['id']);
                if ($stmt->execute()) {
                    $success = "Cập nhật thông tin thành công!";
                    // Cập nhật lại session
                    $_SESSION['user']['first_name'] = $first_name;
                    $_SESSION['user']['last_name'] = $last_name;
                    $_SESSION['user']['phone'] = $phone;
                    $_SESSION['user']['email'] = $email;
                    // Lấy lại thông tin mới nhất
                    $user['first_name'] = $first_name;
                    $user['last_name'] = $last_name;
                    $user['phone'] = $phone;
                    $user['email'] = $email;
                } else {
                    $error = "Có lỗi khi cập nhật!";
                }
            }
        }
    }
    include 'view/user/edit_profile.php';
    exit;
}

if ($action === 'change_password' && $user) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        // Lấy hash cũ từ DB
        $stmt = $conn->prepare("SELECT password_hash FROM users WHERE id = ?");
        $stmt->bind_param("i", $user['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if (!$row || !password_verify($old_password, $row['password_hash'])) {
            $error = "Mật khẩu hiện tại không đúng!";
        } elseif (strlen($new_password) < 6) {
            $error = "Mật khẩu mới phải từ 6 ký tự trở lên!";
        } elseif ($new_password !== $confirm_password) {
            $error = "Mật khẩu xác nhận không khớp!";
        } else {
            $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password_hash=? WHERE id=?");
            $stmt->bind_param("si", $new_hash, $user['id']);
            if ($stmt->execute()) {
                $success = "Đổi mật khẩu thành công!";
            } else {
                $error = "Có lỗi khi đổi mật khẩu!";
            }
        }
    }
    include 'view/user/change_password.php';
    exit;
}

if ($action === 'manage') {
    $users = User::getAll($conn);
    $view_file = 'view/admin/manage_user.php';
    include 'view/layout/admin_layout.php';
    exit;
}

if ($action === 'edit' && isset($_GET['id']) && ($_SESSION['user']['role_name'] === 'admin' || $_SESSION['user']['role_name'] === 'super_admin')) {
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

    $view_file = 'view/user/edit_user.php';
    include 'view/layout/admin_layout.php';
    exit;
}

// Lấy thông tin vai trò từ DB (nếu cần)
if (!empty($_SESSION['user'])) {
    $user_id = $_SESSION['user']['id'];
    $sql = "SELECT r.name FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $_SESSION['user']['role_name'] = $row['name'];
    }
}

include 'view/user/profile.php';
?> 