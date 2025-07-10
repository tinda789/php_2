<!-- Thanhdat -->
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['user']) || !in_array($_SESSION['user']['role_name'], ['admin', 'super_admin'])) {
    header('Location: index.php');
    exit;
}
require_once 'config/config.php';
require_once 'model/Review.php';

$action = $_GET['action'] ?? 'index';

// Hiển thị danh sách đánh giá
if ($action === 'index') {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $status = isset($_GET['status']) ? trim($_GET['status']) : '';
    $limit = 10;
    $offset = ($page - 1) * $limit;

    $reviews = Review::getAll($conn, $limit, $offset, $search, $status);
    $total = Review::countAll($conn, $search, $status);
    $totalPages = ceil($total / $limit);
    $stats = Review::getStats($conn);

    $view_file = 'view/admin/review_index.php';
    include 'view/layout/admin_layout.php';
    exit;
}

// Duyệt đánh giá
if ($action === 'approve' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if (Review::approve($conn, $id)) {
        header('Location: index.php?controller=review&action=index&msg=Đã duyệt đánh giá thành công');
    } else {
        header('Location: index.php?controller=review&action=index&error=Có lỗi khi duyệt đánh giá');
    }
    exit;
}

// Ẩn đánh giá
if ($action === 'hide' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if (Review::hide($conn, $id)) {
        header('Location: index.php?controller=review&action=index&msg=Đã ẩn đánh giá thành công');
    } else {
        header('Location: index.php?controller=review&action=index&error=Có lỗi khi ẩn đánh giá');
    }
    exit;
}

// Xóa đánh giá
if ($action === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if (Review::delete($conn, $id)) {
        header('Location: index.php?controller=review&action=index&msg=Đã xóa đánh giá thành công');
    } else {
        header('Location: index.php?controller=review&action=index&error=Có lỗi khi xóa đánh giá');
    }
    exit;
}

// Đánh dấu đã xác minh
if ($action === 'verify' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if (Review::markVerified($conn, $id)) {
        header('Location: index.php?controller=review&action=index&msg=Đã đánh dấu xác minh thành công');
    } else {
        header('Location: index.php?controller=review&action=index&error=Có lỗi khi đánh dấu xác minh');
    }
    exit;
}

// Hiển thị form trả lời đánh giá
if ($action === 'reply' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $review = Review::getById($conn, $id);
    if (!$review) {
        header('Location: index.php?controller=review&action=index&error=Không tìm thấy đánh giá');
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $admin_reply = trim($_POST['admin_reply']);
        if (empty($admin_reply)) {
            $error = 'Vui lòng nhập nội dung trả lời';
        } else {
            if (Review::addReply($conn, $id, $admin_reply)) {
                header('Location: index.php?controller=review&action=index&msg=Đã trả lời đánh giá thành công');
                exit;
            } else {
                $error = 'Có lỗi khi trả lời đánh giá';
            }
        }
    }
    
    $view_file = 'view/admin/review_reply.php';
    include 'view/layout/admin_layout.php';
    exit;
}

// Xem chi tiết đánh giá
if ($action === 'view' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $review = Review::getById($conn, $id);
    if (!$review) {
        header('Location: index.php?controller=review&action=index&error=Không tìm thấy đánh giá');
        exit;
    }
    
    $view_file = 'view/admin/review_view.php';
    include 'view/layout/admin_layout.php';
    exit;
}

// Duyệt nhiều đánh giá cùng lúc
if ($action === 'bulk_approve' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $ids = $_POST['review_ids'] ?? [];
    $success_count = 0;
    
    foreach ($ids as $id) {
        if (Review::approve($conn, (int)$id)) {
            $success_count++;
        }
    }
    
    header('Location: index.php?controller=review&action=index&msg=Đã duyệt ' . $success_count . ' đánh giá thành công');
    exit;
}

// Ẩn nhiều đánh giá cùng lúc
if ($action === 'bulk_hide' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $ids = $_POST['review_ids'] ?? [];
    $success_count = 0;
    
    foreach ($ids as $id) {
        if (Review::hide($conn, (int)$id)) {
            $success_count++;
        }
    }
    
    header('Location: index.php?controller=review&action=index&msg=Đã ẩn ' . $success_count . ' đánh giá thành công');
    exit;
}

// Xóa nhiều đánh giá cùng lúc
if ($action === 'bulk_delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $ids = $_POST['review_ids'] ?? [];
    $success_count = 0;
    
    foreach ($ids as $id) {
        if (Review::delete($conn, (int)$id)) {
            $success_count++;
        }
    }
    
    header('Location: index.php?controller=review&action=index&msg=Đã xóa ' . $success_count . ' đánh giá thành công');
    exit;
}

// Mặc định chuyển về trang danh sách
header('Location: index.php?controller=review&action=index');
exit;
?> 