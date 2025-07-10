<?php // thanhdat: Controller quản lý tin tức OOP
require_once 'config/config.php';
require_once 'model/News.php';

class NewsController {
    private $newsModel;
    
    public function __construct() {
        global $conn;
        $this->newsModel = new News($conn);
    }
    
    // Hiển thị danh sách tin tức
    public function index() {
        $category = isset($_GET['category']) ? $_GET['category'] : null;
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $per_page = 10;
        
        $news_list = $this->newsModel->getAllNews($category, $status, $page, $per_page);
        $total_news = $this->newsModel->getTotalNews($category, $status);
        $total_pages = ceil($total_news / $per_page);
        $stats = $this->newsModel->getNewsStats();
        
        include 'view/admin/news_index.php';
    }
    
    // Hiển thị form thêm tin tức
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->store();
        } else {
            $categories = $this->newsModel->getAllCategories();
            include 'view/admin/news_form.php';
        }
    }
    
    // Lưu tin tức mới
    public function store() {
        $data = [
            'title' => $_POST['title'],
            'content' => $_POST['content'],
            'summary' => $_POST['summary'],
            'category' => $_POST['category'],
            'status' => $_POST['status'],
            'image' => ''
        ];
        
        // Tạo slug từ title
        $data['slug'] = $this->createSlug($data['title']);
        
        // Upload ảnh nếu có
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $uploaded_image = $this->newsModel->uploadImage($_FILES['image']);
            if ($uploaded_image) {
                $data['image'] = $uploaded_image;
            } else {
                $_SESSION['error'] = "Lỗi upload ảnh. Vui lòng thử lại.";
                header('Location: index.php?controller=news&action=create');
                exit;
            }
        }
        
        if ($this->newsModel->createNews($data)) {
            $_SESSION['success'] = "Thêm tin tức thành công!";
            header('Location: index.php?controller=news&action=index');
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra. Vui lòng thử lại.";
            header('Location: index.php?controller=news&action=create');
        }
        exit;
    }
    
    // Hiển thị form sửa tin tức
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->update($id);
        } else {
            $news = $this->newsModel->getNewsById($id);
            if (!$news) {
                $_SESSION['error'] = "Tin tức không tồn tại.";
                header('Location: index.php?controller=news&action=index');
                exit;
            }
            $categories = $this->newsModel->getAllCategories();
            include 'view/admin/news_form.php';
        }
    }
    
    // Cập nhật tin tức
    public function update($id) {
        $news = $this->newsModel->getNewsById($id);
        if (!$news) {
            $_SESSION['error'] = "Tin tức không tồn tại.";
            header('Location: index.php?controller=news&action=index');
            exit;
        }
        
        $data = [
            'title' => $_POST['title'],
            'content' => $_POST['content'],
            'summary' => $_POST['summary'],
            'category' => $_POST['category'],
            'status' => $_POST['status'],
            'image' => $news['image'] // Giữ ảnh cũ
        ];
        
        // Tạo slug từ title
        $data['slug'] = $this->createSlug($data['title']);
        
        // Upload ảnh mới nếu có
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $uploaded_image = $this->newsModel->uploadImage($_FILES['image']);
            if ($uploaded_image) {
                // Xóa ảnh cũ
                if ($news['image']) {
                    $old_image_path = "uploads/news/" . $news['image'];
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }
                }
                $data['image'] = $uploaded_image;
            } else {
                $_SESSION['error'] = "Lỗi upload ảnh. Vui lòng thử lại.";
                header('Location: index.php?controller=news&action=edit&id=' . $id);
                exit;
            }
        }
        
        if ($this->newsModel->updateNews($id, $data)) {
            $_SESSION['success'] = "Cập nhật tin tức thành công!";
            header('Location: index.php?controller=news&action=index');
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra. Vui lòng thử lại.";
            header('Location: index.php?controller=news&action=edit&id=' . $id);
        }
        exit;
    }
    
    // Xóa tin tức
    public function delete($id) {
        if ($this->newsModel->deleteNews($id)) {
            $_SESSION['success'] = "Xóa tin tức thành công!";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra. Vui lòng thử lại.";
        }
        header('Location: index.php?controller=news&action=index');
        exit;
    }
    
    // Bật/tắt tin tức
    public function toggle($id) {
        if ($this->newsModel->toggleStatus($id)) {
            $_SESSION['success'] = "Cập nhật trạng thái tin tức thành công!";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra. Vui lòng thử lại.";
        }
        header('Location: index.php?controller=news&action=index');
        exit;
    }
    
    // Xóa nhiều tin tức
    public function deleteMultiple() {
        if (isset($_POST['selected_news']) && is_array($_POST['selected_news'])) {
            $success_count = 0;
            $error_count = 0;
            
            foreach ($_POST['selected_news'] as $id) {
                if ($this->newsModel->deleteNews($id)) {
                    $success_count++;
                } else {
                    $error_count++;
                }
            }
            
            if ($success_count > 0) {
                $_SESSION['success'] = "Đã xóa thành công $success_count tin tức.";
            }
            if ($error_count > 0) {
                $_SESSION['error'] = "Có $error_count tin tức không thể xóa.";
            }
        } else {
            $_SESSION['error'] = "Vui lòng chọn tin tức cần xóa.";
        }
        
        header('Location: index.php?controller=news&action=index');
        exit;
    }
    
    // Tạo slug từ title
    private function createSlug($title) {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Kiểm tra slug trùng lặp
        $original_slug = $slug;
        $counter = 1;
        while ($this->newsModel->slugExists($slug)) {
            $slug = $original_slug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    // API lấy tin tức theo danh mục (cho frontend)
    public function getByCategory($category, $limit = 10) {
        $news = $this->newsModel->getNewsByCategory($category, $limit);
        header('Content-Type: application/json');
        echo json_encode($news);
    }
    
    // API lấy tin tức mới nhất
    public function getLatest($limit = 5) {
        $news = $this->newsModel->getLatestNews($limit);
        header('Content-Type: application/json');
        echo json_encode($news);
    }
}
?> 