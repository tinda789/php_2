<?php // thanhdat: Controller quản lý tin tức OOP
require_once 'config/config.php';
require_once 'model/News.php';
require_once 'model/Category.php';

class NewsController {
    private $newsModel;
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
        $this->newsModel = new News($conn);
    }
    
    // Hiển thị danh sách tin tức
    public function index() {
        $category = isset($_GET['category']) ? $_GET['category'] : null;
        $is_active = isset($_GET['status']) ? $_GET['status'] : null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $per_page = 10;
        $offset = ($page - 1) * $per_page;
        
        $news_list = $this->newsModel->getAllNews($category, $is_active, $per_page, $offset);
        $total_news = count($news_list); // Hoặc dùng hàm countNews nếu có
        $total_pages = ceil($total_news / $per_page);
        $stats = $this->newsModel->getNewsStats();
        
        $view_file = 'view/admin/news_index.php';
        include 'view/layout/admin_layout.php';
    }
    
    // Hiển thị form thêm tin tức
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->store();
        } else {
            global $conn;
            $categories = Category::getAll($conn, 100, 0);
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
            'is_active' => $_POST['status'],
            'image_url' => ''
        ];
        
        // Tạo slug từ title
        $data['slug'] = $this->createSlug($data['title']);
        
        // Upload ảnh nếu có
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $uploaded_image = $this->newsModel->uploadImage($_FILES['image']);
            if ($uploaded_image) {
                $data['image_url'] = $uploaded_image;
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
            global $conn;
            $categories = Category::getAll($conn, 100, 0);
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
            'is_active' => $_POST['status'],
            'image_url' => $news['image_url'] // Giữ ảnh cũ
        ];
        
        // Tạo slug từ title
        $data['slug'] = $this->createSlug($data['title']);
        
        // Upload ảnh mới nếu có
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $uploaded_image = $this->newsModel->uploadImage($_FILES['image']);
            if ($uploaded_image) {
                // Xóa ảnh cũ
                if ($news['image_url']) {
                    $old_image_path = "uploads/news/" . $news['image_url'];
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }
                }
                $data['image_url'] = $uploaded_image;
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
    
    // Hiển thị danh sách tin tức cho người dùng (public)
    public function list() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $per_page = 8;
        $offset = ($page - 1) * $per_page;
        // Lấy danh mục nếu có
        $category = isset($_GET['category']) ? $_GET['category'] : null;
        $is_active = 1;
        $news_list = $this->newsModel->getAllNews($category, $is_active, $per_page, $offset);
        $total_news = count($news_list); // Có thể dùng hàm countNews nếu muốn phân trang chuẩn
        $total_pages = ceil($total_news / $per_page);
        include 'view/user/news_list.php';
    }
    
    // Trang chi tiết tin tức public
    public function view($id) {
        $news = $this->newsModel->getNewsById($id);
        if (!$news || !$news['is_active']) {
            include 'view/user/news_not_found.php';
            return;
        }
        // Tăng lượt xem
        $this->newsModel->incrementViews($id);
        // Lấy danh mục, tags, tin nổi bật để truyền cho sidebar
        $categories = $this->newsModel->getCategories();
        $tags = $this->newsModel->getTags();
        $featuredNews = $this->newsModel->getFeaturedNews(5);
        include 'view/user/news_detail.php';
    }
}
?> 