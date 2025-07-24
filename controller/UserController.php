<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'config/config.php';
require_once 'model/User.php'; // Đảm bảo đã require model
require_once 'model/Order.php'; // Include Order model
require_once 'model/Address.php'; // Thêm model Address

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

if ($action === 'addresses' && $user) {
    $addressModel = new Address();
    $addresses = $addressModel->getUserAddresses($user['id']);
    
    // Xử lý thêm/sửa/xóa địa chỉ
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $subAction = $_POST['sub_action'] ?? '';
        
        if ($subAction === 'save') {
            $data = [
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'phone' => trim($_POST['phone']),
                'address_line1' => trim($_POST['address_line1']),
                'address_line2' => trim($_POST['address_line2'] ?? ''),
                'city' => trim($_POST['city']),
                'district' => trim($_POST['district']),
                'ward' => trim($_POST['ward']),
                'is_default' => isset($_POST['is_default']) ? 1 : 0
            ];
            
            // Validate dữ liệu
            $errors = [];
            if (empty($data['first_name'])) $errors[] = 'Vui lòng nhập họ';
            if (empty($data['last_name'])) $errors[] = 'Vui lòng nhập tên';
            if (empty($data['phone']) || !preg_match('/^[0-9]{10,15}$/', $data['phone'])) {
                $errors[] = 'Số điện thoại không hợp lệ';
            }
            if (empty($data['address_line1'])) $errors[] = 'Vui lòng nhập địa chỉ';
            if (empty($data['city'])) $errors[] = 'Vui lòng chọn tỉnh/thành phố';
            if (empty($data['district'])) $errors[] = 'Vui lòng chọn quận/huyện';
            if (empty($data['ward'])) $errors[] = 'Vui lòng chọn phường/xã';
            
            if (empty($errors)) {
                $addressId = $_POST['address_id'] ?? null;
                if ($addressId) {
                    // Cập nhật địa chỉ
                    $result = $addressModel->update($user['id'], $addressId, $data);
                    if ($result) {
                        $_SESSION['success'] = 'Cập nhật địa chỉ thành công';
                    } else {
                        $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật địa chỉ';
                    }
                } else {
                    // Thêm địa chỉ mới
                    $result = $addressModel->save($user['id'], $data);
                    if ($result) {
                        $_SESSION['success'] = 'Thêm địa chỉ mới thành công';
                    } else {
                        $_SESSION['error'] = 'Có lỗi xảy ra khi thêm địa chỉ';
                    }
                }
                header('Location: index.php?controller=user&action=addresses');
                exit;
            } else {
                $_SESSION['error'] = implode('<br>', $errors);
                $_SESSION['form_data'] = $data;
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }
        } elseif ($subAction === 'set_default' && !empty($_POST['id'])) {
            // Đặt địa chỉ mặc định
            $result = $addressModel->setDefaultAddress($_POST['id'], $user['id']);
            if ($result) {
                $_SESSION['success'] = 'Đã đặt địa chỉ mặc định thành công';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi đặt địa chỉ mặc định';
            }
            header('Location: index.php?controller=user&action=addresses');
            exit;
        } elseif ($subAction === 'delete' && !empty($_POST['id'])) {
            // Xóa địa chỉ
            $result = $addressModel->deleteAddress($_POST['id'], $user['id']);
            if ($result) {
                $_SESSION['success'] = 'Đã xóa địa chỉ thành công';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi xóa địa chỉ';
            }
            header('Location: index.php?controller=user&action=addresses');
            exit;
        }
    }
    
    include 'view/user/addresses.php';
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

// Xem chi tiết đơn hàng
if ($action === 'order_detail' && isset($_GET['id'])) {
    if (empty($_SESSION['user'])) {
        header('Location: index.php?controller=user&action=login');
        exit;
    }

    $order_id = (int)$_GET['id'];
    $user_id = $_SESSION['user']['id'];
    
    // Kiểm tra xem đơn hàng có thuộc về người dùng này không
    $order = Order::getById($GLOBALS['conn'], $order_id);
    
    if (!$order || $order['user_id'] != $user_id) {
        echo '<div class="alert alert-danger">Bạn không có quyền xem đơn hàng này hoặc đơn hàng không tồn tại.</div>';
        exit;
    }
    
    try {
        // Log request information
        error_log('Order detail request - Order ID: ' . $order_id . ', User ID: ' . $user_id);
        
        // First, verify the order exists and belongs to the user
        if (!$order) {
            throw new Exception('Đơn hàng không tồn tại.');
        }
        
        if ($order['user_id'] != $user_id) {
            throw new Exception('Bạn không có quyền xem đơn hàng này.');
        }
        
        // Get order items with error handling
        $sql = "SELECT oi.*, p.image_link as images, p.slug, p.name as product_name 
                FROM order_items oi 
                LEFT JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = ?";
                
        error_log('Executing SQL: ' . $sql . ' with order_id: ' . $order_id);
        
        $stmt = $GLOBALS['conn']->prepare($sql);
        if (!$stmt) {
            throw new Exception('Lỗi chuẩn bị truy vấn: ' . $GLOBALS['conn']->error);
        }
        
        if (!$stmt->bind_param("i", $order_id)) {
            throw new Exception('Lỗi ràng buộc tham số: ' . $stmt->error);
        }
        
        if (!$stmt->execute()) {
            throw new Exception('Không thể thực hiện truy vấn: ' . $stmt->error);
        }
        
        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception('Lỗi khi lấy dữ liệu đơn hàng: ' . $GLOBALS['conn']->error);
        }
        
        $order_items = $result->fetch_all(MYSQLI_ASSOC);
        error_log('Found ' . count($order_items) . ' order items');
        
        // Format image data and add product URLs
        foreach ($order_items as &$item) {
            try {
                if (!empty($item['images'])) {
                    $images = json_decode($item['images'], true);
                    $item['images'] = is_array($images) ? $images : [];
                } else {
                    $item['images'] = [];
                }
                
                // Add default image if none exists
                if (empty($item['images'])) {
                    $item['images'][] = 'assets/images/no-image.jpg';
                }
                
                // Add product URL
                $item['product_url'] = !empty($item['slug']) 
                    ? 'index.php?controller=product&action=detail&id=' . $item['product_id'] . '&slug=' . $item['slug']
                    : '#';
                    
            } catch (Exception $e) {
                error_log('Error processing order item: ' . $e->getMessage());
                continue;
            }
        }
        unset($item);
        
        // Add items to order data
        $order['items'] = $order_items;
        
        // Verify view file exists
        $view_path = 'view/user/order_detail.php';
        if (!file_exists($view_path)) {
            throw new Exception('Không tìm thấy tệp giao diện: ' . realpath($view_path));
        }
        
        // Start output buffering
        ob_start();
        
        // Pass variables to the view
        $order = $order;
        $orderItems = $order_items; // For backward compatibility
        
        // Include the view
        include $view_path;
        
        // Get the content and clean the buffer
        $content = ob_get_clean();
        
        // Output the content
        echo $content;
        
    } catch (Exception $e) {
        // Log the error with stack trace
        error_log('Order Detail Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        
        // Start output buffering
        ob_start();
        ?>
        <div class="container py-5">
            <div class="alert alert-danger">
                <h4><i class="fas fa-exclamation-triangle me-2"></i>Lỗi khi tải chi tiết đơn hàng</h4>
                <p>Rất tiếc, đã xảy ra lỗi khi tải thông tin đơn hàng. Vui lòng thử lại sau hoặc liên hệ bộ phận hỗ trợ nếu vấn đề vẫn tiếp diễn.</p>
                <?php if (defined('DEBUG') && DEBUG === true): ?>
                    <hr>
                    <div class="bg-light p-3 mt-3 rounded">
                        <h5>Thông tin lỗi (Chỉ hiển thị trong chế độ debug):</h5>
                        <pre class="mb-0"><?php echo htmlspecialchars($e->getMessage()); ?></pre>
                        <pre class="small text-muted mt-2"><?php echo htmlspecialchars($e->getTraceAsString()); ?></pre>
                    </div>
                <?php endif; ?>
            </div>
            <div class="text-center mt-4">
                <a href="index.php?controller=user&action=orders" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách đơn hàng
                </a>
            </div>
        </div>
        <?php
        echo ob_get_clean();
    }
    
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
include 'view/user/profile.php';
?>