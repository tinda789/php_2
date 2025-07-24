<?php 
// Kiểm tra quyền truy cập
if (!in_array($_SESSION['user']['role_name'] ?? '', ['admin', 'super_admin'])) {
    echo '<div class="alert alert-danger text-center my-5">Bạn không có quyền truy cập trang này!</div>';
    include 'view/layout/footer.php';
    exit;
}

// Form thêm/sửa banner
$is_edit = isset($banner);

// Tương thích với dữ liệu cũ
if ($is_edit && isset($banner['image_url'])) {
    $banner['image'] = $banner['image_url'];
}
if ($is_edit && isset($banner['is_active'])) {
    $banner['status'] = $banner['is_active'];
}

// Đặt tiêu đề trang
$pageTitle = $is_edit ? 'Sửa Banner' : 'Thêm Banner Mới';

// Include header admin
include 'view/layout/header_admin.php';
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $is_edit ? 'Sửa Banner' : 'Thêm Banner Mới' ?></h1>
        <a href="index.php?controller=banner&action=index" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại danh sách
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thông tin banner</h6>
        </div>
        <div class="card-body">
          <form method="POST" enctype="multipart/form-data" id="bannerForm">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title">Tiêu đề <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="<?= $is_edit ? htmlspecialchars($banner['title']) : '' ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="position">Vị trí hiển thị <span class="text-danger">*</span></label>
                        <select class="form-control" id="position" name="position" required>
                            <option value="">Chọn vị trí</option>
                            <option value="header" <?= $is_edit && $banner['position'] == 'header' ? 'selected' : '' ?>>Header</option>
                            <option value="sidebar" <?= $is_edit && $banner['position'] == 'sidebar' ? 'selected' : '' ?>>Sidebar</option>
                            <option value="footer" <?= $is_edit && $banner['position'] == 'footer' ? 'selected' : '' ?>>Footer</option>
                            <option value="homepage" <?= $is_edit && $banner['position'] == 'homepage' ? 'selected' : '' ?>>Homepage</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="description">Mô tả</label>
                        <textarea class="form-control" id="description" name="description" rows="3" 
                                 placeholder="Mô tả ngắn về banner..."><?= $is_edit ? htmlspecialchars($banner['description']) : '' ?></textarea>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="link">Liên kết</label>
                        <input type="url" class="form-control" id="link" name="link" 
                               value="<?= $is_edit ? htmlspecialchars($banner['link']) : '' ?>" 
                               placeholder="https://example.com">
                        <small class="form-text text-muted">Để trống nếu không cần liên kết</small>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="sort_order">Thứ tự hiển thị</label>
                        <input type="number" class="form-control" id="sort_order" name="sort_order" 
                               value="<?= $is_edit ? $banner['sort_order'] : '0' ?>" min="0">
                        <small class="form-text text-muted">Số càng nhỏ hiển thị càng trước</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status">Trạng thái</label>
                        <select class="form-control" id="status" name="status">
                            <option value="1" <?= $is_edit && $banner['status'] == 1 ? 'selected' : '' ?>>Đang hoạt động</option>
                            <option value="0" <?= $is_edit && $banner['status'] == 0 ? 'selected' : '' ?>>Tạm ẩn</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="image">Ảnh banner <?= $is_edit ? '' : '<span class="text-danger">*</span>' ?></label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="image" name="image" 
                                   accept="image/*" <?= $is_edit ? '' : 'required' ?>>
                            <label class="custom-file-label" for="image">Chọn file ảnh</label>
                        </div>
                        <small class="form-text text-muted">Định dạng: JPG, PNG, GIF. Kích thước tối đa: 2MB</small>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="form-group d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> <?= $is_edit ? 'Cập nhật' : 'Thêm' ?> Banner
                        </button>
                        <a href="index.php?controller=banner&action=index" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i> Hủy bỏ
                        </a>
                    </div>
                </div>
            </div>
          </form>
        </div>
      </div>
      
      <!-- Xem trước ảnh -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-eye me-1"></i> Xem trước ảnh
            </h6>
        </div>
        <div class="card-body text-center">
            <div id="imagePreview" class="mb-3">
                <?php if ($is_edit && !empty($banner['image'])): ?>
                    <?php 
                    $image_path = (strpos($banner['image'], 'http') === 0) ? 
                        $banner['image'] : 
                        'uploads/' . ltrim($banner['image'], '/');
                    ?>
                    <img src="<?= $image_path ?>" alt="Banner preview" class="img-fluid rounded" style="max-height: 200px;">
                <?php else: ?>
                    <div class="bg-light border rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                        <div class="text-muted">
                            <i class="fas fa-image fa-3x mb-2"></i>
                            <p>Chưa có ảnh</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <small class="text-muted">Ảnh sẽ được hiển thị ở đây khi bạn chọn file</small>
        </div>
      </div>
      <!-- Hướng dẫn vị trí -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-info-circle me-1"></i> Hướng dẫn vị trí
            </h6>
        </div>
        <div class="card-body">
            <div class="mb-2"><strong>Header:</strong> Hiển thị ở đầu trang (kích thước khuyến nghị: 1920x150px)</div>
            <div class="mb-2"><strong>Sidebar:</strong> Hiển thị ở thanh bên (kích thước khuyến nghị: 300x250px)</div>
            <div class="mb-2"><strong>Footer:</strong> Hiển thị ở cuối trang (kích thước khuyến nghị: 1920x150px)</div>
            <div class="mb-2"><strong>Homepage:</strong> Hiển thị ở trang chủ (kích thước khuyến nghị: 1920x400px)</div>
        </div>
      </div>
    </div>
  </div>
    </div>
<!-- End of Main Content -->

<?php 
// Include footer
include 'view/layout/footer.php'; 
?>

<script>
// Hiển thị tên file khi chọn
const imgInput = document.getElementById('image');
const fileLabel = document.querySelector('.custom-file-label');

if (imgInput) {
    // Hiển thị tên file khi chọn
    imgInput.addEventListener('change', function(e) {
        const fileName = e.target.files.length ? e.target.files[0].name : 'Chọn file ảnh';
        fileLabel.textContent = fileName;
        
        // Xem trước ảnh
        const file = e.target.files[0];
        const preview = document.getElementById('imagePreview');
        
        if (file) {
            // Kiểm tra kích thước file
            const maxSize = 2 * 1024 * 1024; // 2MB
            if (file.size > maxSize) {
                alert('File ảnh quá lớn. Vui lòng chọn file nhỏ hơn 2MB.');
                this.value = '';
                fileLabel.textContent = 'Chọn file ảnh';
                return;
            }
            
            // Kiểm tra định dạng file
            const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Định dạng file không hợp lệ. Vui lòng chọn file ảnh (JPG, PNG, GIF).');
                this.value = '';
                fileLabel.textContent = 'Chọn file ảnh';
                return;
            }
            
            // Hiển thị ảnh xem trước
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `
                    <div class="position-relative">
                        <img src="${e.target.result}" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        } else {
            // Nếu không có file, hiển thị ảnh mặc định
            showDefaultPreview(preview);
        }
    });
    
    // Kiểm tra nếu đang ở chế độ sửa và có ảnh
    const preview = document.getElementById('imagePreview');
    if (preview && !preview.querySelector('img')) {
        showDefaultPreview(preview);
    }
}

// Hiển thị ảnh mặc định
function showDefaultPreview(previewElement) {
    previewElement.innerHTML = `
        <div class="bg-light border rounded d-flex align-items-center justify-content-center" style="height: 200px;">
            <div class="text-muted text-center">
                <i class="fas fa-image fa-3x mb-2"></i>
                <p class="mb-0">Chưa có ảnh</p>
            </div>
        </div>
    `;
}
// Xử lý validate form
const bannerForm = document.getElementById('bannerForm');
if (bannerForm) {
    bannerForm.addEventListener('submit', function(e) {
        // Reset các thông báo lỗi cũ
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
        
        let isValid = true;
        
        // Validate tiêu đề
        const titleInput = document.getElementById('title');
        const title = titleInput.value.trim();
        if (!title) {
            showError(titleInput, 'Vui lòng nhập tiêu đề banner');
            isValid = false;
        } else if (title.length > 255) {
            showError(titleInput, 'Tiêu đề không được vượt quá 255 ký tự');
            isValid = false;
        }
        
        // Validate vị trí
        const positionInput = document.getElementById('position');
        const position = positionInput.value;
        if (!position) {
            showError(positionInput, 'Vui lòng chọn vị trí hiển thị');
            isValid = false;
        }
        
        // Validate ảnh (chỉ khi thêm mới)
        <?php if (!$is_edit): ?>
        const imageInput = document.getElementById('image');
        if (!imageInput.files.length) {
            showError(imageInput, 'Vui lòng chọn ảnh banner');
            isValid = false;
        }
        <?php endif; ?>
        
        if (!isValid) {
            e.preventDefault();
            // Cuộn đến trường đầu tiên bị lỗi
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
}

// Hiển thị thông báo lỗi
function showError(input, message) {
    input.classList.add('is-invalid');
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = message;
    input.parentNode.appendChild(errorDiv);
}
</script> 