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
        $avatar_path = $user['avatar']; // Giữ avatar cũ nếu không upload mới

        // Kiểm tra hợp lệ
        if (empty($first_name) || empty($last_name)) {
            $error = "Vui lòng nhập đầy đủ họ và tên!";
        } elseif (!preg_match('/^[0-9]{8,15}$/', $phone)) {
            $error = "Số điện thoại không hợp lệ!";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Email không hợp lệ!";
        } else {
            // Xử lý xóa avatar
            if (isset($_POST['remove_avatar']) && $_POST['remove_avatar'] == '1') {
                // Xóa file avatar cũ nếu có
                if (!empty($user['avatar']) && file_exists($user['avatar'])) {
                    unlink($user['avatar']);
                }
                $avatar_path = null; // Xóa avatar
            }
            // Xử lý upload avatar mới hoặc ảnh đã crop
            elseif (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['avatar'];
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                $max_size = 2 * 1024 * 1024; // 2MB

                // Kiểm tra loại file
                if (!in_array($file['type'], $allowed_types)) {
                    $error = "Chỉ chấp nhận file JPG, PNG, GIF!";
                } elseif ($file['size'] > $max_size) {
                    $error = "File quá lớn! Tối đa 2MB.";
                } else {
                    // Tạo thư mục uploads/avatars nếu chưa có
                    $upload_dir = 'uploads/avatars/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }

                    // Tạo tên file unique
                    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $new_filename = 'avatar_' . uniqid() . '.' . $file_extension;
                    $upload_path = $upload_dir . $new_filename;

                    // Upload file
                    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                        // Xóa avatar cũ nếu có
                        if (!empty($user['avatar']) && file_exists($user['avatar'])) {
                            unlink($user['avatar']);
                        }
                        $avatar_path = $upload_path;
                    } else {
                        $error = "Có lỗi khi upload file!";
                    }
                }
            }
            // Xử lý ảnh đã được crop (base64)
            elseif (isset($_POST['cropped_avatar']) && !empty($_POST['cropped_avatar'])) {
                $cropped_data = $_POST['cropped_avatar'];
                
                // Kiểm tra xem có phải base64 image không
                if (preg_match('/^data:image\/(jpeg|jpg|png|gif);base64,/', $cropped_data)) {
                    // Tạo thư mục uploads/avatars nếu chưa có
                    $upload_dir = 'uploads/avatars/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }

                    // Tạo tên file unique
                    $new_filename = 'avatar_' . uniqid() . '.jpg';
                    $upload_path = $upload_dir . $new_filename;

                    // Lấy base64 data
                    $base64_data = substr($cropped_data, strpos($cropped_data, ',') + 1);
                    $image_data = base64_decode($base64_data);

                    // Lưu file
                    if (file_put_contents($upload_path, $image_data)) {
                        // Xóa avatar cũ nếu có
                        if (!empty($user['avatar']) && file_exists($user['avatar'])) {
                            unlink($user['avatar']);
                        }
                        $avatar_path = $upload_path;
                    } else {
                        $error = "Có lỗi khi lưu ảnh đã crop!";
                    }
                } else {
                    $error = "Dữ liệu ảnh không hợp lệ!";
                }
            }

            if (empty($error)) {
                // Kiểm tra trùng email với user khác
                $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                $stmt->bind_param("si", $email, $user['id']);
                $stmt->execute();
                if ($stmt->get_result()->num_rows > 0) {
                    $error = "Email đã được sử dụng!";
                } else {
                    // Cập nhật
                    if ($avatar_path === null) {
                        $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, phone=?, email=?, avatar=NULL WHERE id=?");
                        $stmt->bind_param("ssssi", $first_name, $last_name, $phone, $email, $user['id']);
                    } else {
                        $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, phone=?, email=?, avatar=? WHERE id=?");
                        $stmt->bind_param("sssssi", $first_name, $last_name, $phone, $email, $avatar_path, $user['id']);
                    }
                    if ($stmt->execute()) {
                        $success = "Cập nhật thông tin thành công!";
                        // Cập nhật lại session
                        $_SESSION['user']['first_name'] = $first_name;
                        $_SESSION['user']['last_name'] = $last_name;
                        $_SESSION['user']['phone'] = $phone;
                        $_SESSION['user']['email'] = $email;
                        $_SESSION['user']['avatar'] = $avatar_path;
                        // Lấy lại thông tin mới nhất
                        $user['first_name'] = $first_name;
                        $user['last_name'] = $last_name;
                        $user['phone'] = $phone;
                        $user['email'] = $email;
                        $user['avatar'] = $avatar_path;
                        
                        // Thông báo thành công
                        if ($avatar_path === null) {
                            $success = "Cập nhật thông tin thành công! Ảnh đại diện đã được xóa.";
                        } else {
                            $success = "Cập nhật thông tin thành công!";
                        }
                    } else {
                        $error = "Có lỗi khi cập nhật!";
                    }
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
    $sql = "SELECT u.*, r.name as role_name, ur.role_id, 
                   (SELECT COUNT(*) FROM orders WHERE user_id = u.id) as total_orders
            FROM users u 
            LEFT JOIN user_roles ur ON u.id = ur.user_id 
            LEFT JOIN roles r ON ur.role_id = r.id 
            WHERE u.id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user_data = $result->fetch_assoc()) {
        // Cập nhật thông tin người dùng trong session
        $_SESSION['user'] = array_merge($_SESSION['user'], $user_data);
        
        // Lấy lịch sử đơn hàng của người dùng
        require_once 'model/Order.php';
        $orders = Order::getByUserId($conn, $user_id, 5); // Lấy 5 đơn hàng gần nhất
        
        // Thêm thông tin bổ sung cho mỗi đơn hàng
        if (!empty($orders)) {
            foreach ($orders as &$order) {
                // Định dạng lại ngày tháng
                $order['formatted_date'] = date('d/m/Y', strtotime($order['created_at']));
                
                // Thêm thông tin sản phẩm đầu tiên để hiển thị
                if (!empty($order['items']) && is_array($order['items'])) {
                    $order['first_item'] = $order['items'][0];
                }
            }
            unset($order); // Hủy tham chiếu
        }
    }
}

// Sử dụng giao diện mới
include 'view/user/profile_new.php';
?>