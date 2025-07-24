<?php // thanhdat: Controller quản lý banner OOP
require_once 'config/config.php';
require_once 'model/Banner.php';

class BannerController {
    private $bannerModel;
    
    public function __construct() {
        global $conn;
        $this->bannerModel = new Banner($conn);
    }
    
    // Hiển thị danh sách banner
    public function index() {
        $position = isset($_GET['position']) ? $_GET['position'] : null;
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        
        $banners = $this->bannerModel->getAllBanners($position, $status); // thanhdat
        $stats = $this->bannerModel->getBannerStats();
        $view_file = 'view/admin/banner_index.php';
        include 'view/layout/admin_layout.php';
    }
    
    // Hiển thị form thêm banner
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->store();
        } else {
            $view_file = 'view/admin/banner_form.php';
            include 'view/layout/admin_layout.php';
        }
    }
    
    // Lưu banner mới
    public function store() {
        $data = [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'link' => $_POST['link'],
            'position' => $_POST['position'],
            'status' => $_POST['status'],
            'sort_order' => (int)$_POST['sort_order'],
            'image' => ''
        ];
        
        // Upload ảnh nếu có
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $uploaded_image = $this->bannerModel->uploadImage($_FILES['image']);
            if ($uploaded_image) {
                $data['image'] = $uploaded_image;
            } else {
                $_SESSION['error'] = "Lỗi upload ảnh. Vui lòng thử lại.";
                header('Location: index.php?controller=banner&action=create');
                exit;
            }
        } else {
            $_SESSION['error'] = "Vui lòng chọn ảnh banner.";
            header('Location: index.php?controller=banner&action=create');
            exit;
        }
        
        if ($this->bannerModel->createBanner($data)) {
            $_SESSION['success'] = "Thêm banner thành công!";
            header('Location: index.php?controller=banner&action=index');
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra. Vui lòng thử lại.";
            header('Location: index.php?controller=banner&action=create');
        }
        exit;
    }
    
    // Hiển thị form sửa banner
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->update($id);
        } else {
            $banner = $this->bannerModel->getBannerById($id);
            if (!$banner) {
                $_SESSION['error'] = "Banner không tồn tại.";
                header('Location: index.php?controller=banner&action=index');
                exit;
            }
            include 'view/admin/banner_form.php';
        }
    }
    
    // Cập nhật banner
    public function update($id) {
        $banner = $this->bannerModel->getBannerById($id);
        if (!$banner) {
            $_SESSION['error'] = "Banner không tồn tại.";
            header('Location: index.php?controller=banner&action=index');
            exit;
        }
        
        $data = [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'link' => $_POST['link'],
            'position' => $_POST['position'],
            'status' => $_POST['status'],
            'sort_order' => (int)$_POST['sort_order'],
            'image' => $banner['image'] // Giữ ảnh cũ
        ];
        
        // Upload ảnh mới nếu có
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $uploaded_image = $this->bannerModel->uploadImage($_FILES['image']);
            if ($uploaded_image) {
                // Xóa ảnh cũ
                if ($banner['image']) {
                    $old_image_path = "uploads/banners/" . $banner['image'];
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }
                }
                $data['image'] = $uploaded_image;
            } else {
                $_SESSION['error'] = "Lỗi upload ảnh. Vui lòng thử lại.";
                header('Location: index.php?controller=banner&action=edit&id=' . $id);
                exit;
            }
        }
        
        if ($this->bannerModel->updateBanner($id, $data)) {
            $_SESSION['success'] = "Cập nhật banner thành công!";
            header('Location: index.php?controller=banner&action=index');
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra. Vui lòng thử lại.";
            header('Location: index.php?controller=banner&action=edit&id=' . $id);
        }
        exit;
    }
    
    // Xóa banner
    public function delete($id) {
        if ($this->bannerModel->deleteBanner($id)) {
            $_SESSION['success'] = "Xóa banner thành công!";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra. Vui lòng thử lại.";
        }
        header('Location: index.php?controller=banner&action=index');
        exit;
    }
    
    // Bật/tắt banner
    public function toggle($id) {
        if ($this->bannerModel->toggleStatus($id)) {
            $_SESSION['success'] = "Cập nhật trạng thái banner thành công!";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra. Vui lòng thử lại.";
        }
        header('Location: index.php?controller=banner&action=index');
        exit;
    }
    
    // Xóa nhiều banner
    public function deleteMultiple() {
        if (isset($_POST['selected_banners']) && is_array($_POST['selected_banners'])) {
            $success_count = 0;
            $error_count = 0;
            
            foreach ($_POST['selected_banners'] as $id) {
                if ($this->bannerModel->deleteBanner($id)) {
                    $success_count++;
                } else {
                    $error_count++;
                }
            }
            
            if ($success_count > 0) {
                $_SESSION['success'] = "Đã xóa thành công $success_count banner.";
            }
            if ($error_count > 0) {
                $_SESSION['error'] = "Có $error_count banner không thể xóa.";
            }
        } else {
            $_SESSION['error'] = "Vui lòng chọn banner cần xóa.";
        }
        
        header('Location: index.php?controller=banner&action=index');
        exit;
    }
    
    // API lấy banner theo vị trí (cho frontend)
    public function getByPosition($position) {
        $banners = $this->bannerModel->getBannersByPosition($position);
        header('Content-Type: application/json');
        echo json_encode($banners);
    }
}
?> 