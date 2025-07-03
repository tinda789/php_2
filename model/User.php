<?php
class User {
    public static function register($conn, $username, $password, $email, $confirm_password, $first_name, $last_name, $phone, &$error = null) {
        // Kiểm tra hợp lệ
        if (strlen($username) < 4) {
            $error = "Tên đăng nhập phải từ 4 ký tự trở lên!";
            return false;
        }
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $error = "Tên đăng nhập chỉ được chứa chữ, số và dấu gạch dưới!";
            return false;
        }
        if (strlen($password) < 6) {
            $error = "Mật khẩu phải từ 6 ký tự trở lên!";
            return false;
        }
        if ($password !== $confirm_password) {
            $error = "Mật khẩu xác nhận không khớp!";
            return false;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Email không hợp lệ!";
            return false;
        }
        if (empty($first_name) || empty($last_name)) {
            $error = "Vui lòng nhập đầy đủ họ và tên!";
            return false;
        }
        if (!preg_match('/^[0-9]{8,15}$/', $phone)) {
            $error = "Số điện thoại không hợp lệ!";
            return false;
        }
        // Kiểm tra trùng tên
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = "Tên đăng nhập đã tồn tại!";
            return false;
        }
        // Kiểm tra trùng email
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = "Email đã được sử dụng!";
            return false;
        }
        // Đăng ký
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, password_hash, email, first_name, last_name, phone) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $username, $hash, $email, $first_name, $last_name, $phone);
        if ($stmt->execute()) {
            $user_id = $conn->insert_id;
            // Gán quyền customer
            $role_id = self::getCustomerRoleId($conn);
            if ($role_id) {
                $stmt2 = $conn->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
                $stmt2->bind_param("ii", $user_id, $role_id);
                $stmt2->execute();
            }
            return true;
        } else {
            $error = "Có lỗi khi đăng ký!";
            return false;
        }
    }

    // Lấy role_id của quyền customer
    private static function getCustomerRoleId($conn) {
        $stmt = $conn->prepare("SELECT id FROM roles WHERE name = 'customer' LIMIT 1");
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return (int)$row['id'];
        }
        return null;
    }

    public static function login($conn, $username, $password, &$error = null) {
        if (empty($username) || empty($password)) {
            $error = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu!";
            return false;
        }
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password_hash'])) {
                return $user;
            } else {
                $error = "Mật khẩu không đúng!";
                return false;
            }
        } else {
            $error = "Tên đăng nhập không tồn tại!";
            return false;
        }
    }

    public static function getAll($conn) {
        $sql = "SELECT u.*, r.name as role_name
                FROM users u
                LEFT JOIN user_roles ur ON u.id = ur.user_id
                LEFT JOIN roles r ON ur.role_id = r.id
                ORDER BY u.id DESC";
        $result = $conn->query($sql);
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }
}
?> 