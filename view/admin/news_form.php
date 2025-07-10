<?php // thanhdat: Form thêm/sửa tin tức
require_once 'view/layout/admin_layout.php';

$is_edit = isset($news);
$title = $is_edit ? 'Sửa Tin tức' : 'Thêm Tin tức Mới';
$action = $is_edit ? "index.php?controller=news&action=edit&id={$news['id']}" : "index.php?controller=news&action=create";
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-<?= $is_edit ? 'edit' : 'plus' ?>"></i> <?= $title ?>
        </h1>
        <a href="index.php?controller=news&action=index" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Thông tin Bài viết
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= $action ?>" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="title" class="form-label">Tiêu đề bài viết <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   value="<?= $is_edit ? htmlspecialchars($news['title']) : '' ?>" 
                                   required onkeyup="generateSlug()">
                            <small class="form-text text-muted">Tiêu đề sẽ được tự động chuyển thành slug URL</small>
                        </div>

                        <div class="form-group">
                            <label for="slug" class="form-label">Slug URL</label>
                            <input type="text" class="form-control" id="slug" name="slug" 
                                   value="<?= $is_edit ? htmlspecialchars($news['slug']) : '' ?>" 
                                   readonly>
                            <small class="form-text text-muted">URL thân thiện cho bài viết (tự động tạo từ tiêu đề)</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category" class="form-label">Chuyên mục <span class="text-danger">*</span></label>
                                    <select class="form-control" id="category" name="category" required>
                                        <option value="">Chọn chuyên mục</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= $cat['id'] ?>" <?= ($is_edit && $news['category'] == $cat['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($cat['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-label">Trạng thái</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="1" <?= ($is_edit && $news['status'] == 1) ? 'selected' : '' ?>>Hiển thị</option>
                                        <option value="0" <?= ($is_edit && $news['status'] == 0) ? 'selected' : '' ?>>Tạm ẩn</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="summary" class="form-label">Tóm tắt</label>
                            <textarea class="form-control" id="summary" name="summary" rows="3" 
                                      placeholder="Tóm tắt ngắn gọn về bài viết..."><?= $is_edit ? htmlspecialchars($news['summary']) : '' ?></textarea>
                            <small class="form-text text-muted">Mô tả ngắn gọn sẽ hiển thị trong danh sách bài viết</small>
                        </div>

                        <div class="form-group">
                            <label for="content" class="form-label">Nội dung bài viết <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="content" name="content" rows="12" 
                                      placeholder="Nhập nội dung bài viết..." required><?= $is_edit ? htmlspecialchars($news['content']) : '' ?></textarea>
                            <small class="form-text text-muted">Nội dung chính của bài viết</small>
                        </div>

                        <div class="form-group">
                            <label for="image" class="form-label">
                                Ảnh đại diện
                                <?php if ($is_edit && $news['image']): ?>
                                    <small class="text-muted">(Chọn ảnh mới để thay thế)</small>
                                <?php endif; ?>
                            </label>
                            <input type="file" class="form-control-file" id="image" name="image" 
                                   accept="image/*" onchange="previewImage(this)" <?= $is_edit ? '' : 'required' ?>>
                            <small class="form-text text-muted">
                                Định dạng: JPG, PNG, GIF, WebP. Kích thước tối đa: 5MB
                            </small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> <?= $is_edit ? 'Cập nhật' : 'Thêm' ?> Bài viết
                            </button>
                            <a href="index.php?controller=news&action=index" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Preview ảnh -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-eye"></i> Xem trước ảnh
                    </h6>
                </div>
                <div class="card-body">
                    <div id="imagePreview" class="text-center">
                        <?php if ($is_edit && $news['image']): ?>
                            <img src="uploads/news/<?= $news['image'] ?>" 
                                 alt="News preview" 
                                 class="img-fluid rounded" 
                                 style="max-height: 200px;">
                            <div class="mt-2">
                                <small class="text-muted">Ảnh hiện tại</small>
                            </div>
                        <?php else: ?>
                            <div class="bg-light rounded p-4" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                                <div class="text-center">
                                    <i class="fas fa-image fa-3x text-gray-300 mb-2"></i>
                                    <p class="text-gray-500 mb-0">Chưa có ảnh</p>
                                    <small class="text-gray-400">Chọn ảnh để xem trước</small>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Thông tin chuyên mục -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tags"></i> Chuyên mục
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="font-weight-bold text-primary">
                            <i class="fas fa-newspaper"></i> Tin tức
                        </h6>
                        <small class="text-muted">Các bài viết về tin tức, sự kiện, cập nhật</small>
                    </div>
                    <div class="mb-3">
                        <h6 class="font-weight-bold text-success">
                            <i class="fas fa-tag"></i> Khuyến mãi
                        </h6>
                        <small class="text-muted">Thông tin về các chương trình khuyến mãi</small>
                    </div>
                    <div class="mb-3">
                        <h6 class="font-weight-bold text-info">
                            <i class="fas fa-book"></i> Hướng dẫn
                        </h6>
                        <small class="text-muted">Hướng dẫn sử dụng, tips và tricks</small>
                    </div>
                    <div class="mb-3">
                        <h6 class="font-weight-bold text-warning">
                            <i class="fas fa-briefcase"></i> Tuyển dụng
                        </h6>
                        <small class="text-muted">Thông tin tuyển dụng, cơ hội nghề nghiệp</small>
                    </div>
                </div>
            </div>

            <!-- Lưu ý -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-exclamation-triangle"></i> Lưu ý
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> Tiêu đề sẽ tự động tạo slug URL
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> Nội dung có thể sử dụng HTML cơ bản
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> Ảnh đại diện giúp bài viết hấp dẫn hơn
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> Bản nháp có thể chỉnh sửa trước khi xuất bản
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function generateSlug() {
    const title = document.getElementById('title').value;
    const slug = title
        .toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/[\s-]+/g, '-')
        .trim('-');
    
    document.getElementById('slug').value = slug;
}

function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.innerHTML = `
                <img src="${e.target.result}" alt="News preview" class="img-fluid rounded" style="max-height: 200px;">
                <div class="mt-2">
                    <small class="text-muted">Ảnh mới</small>
                </div>
            `;
        };
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.innerHTML = `
            <div class="bg-light rounded p-4" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                <div class="text-center">
                    <i class="fas fa-image fa-3x text-gray-300 mb-2"></i>
                    <p class="text-gray-500 mb-0">Chưa có ảnh</p>
                    <small class="text-gray-400">Chọn ảnh để xem trước</small>
                </div>
            </div>
        `;
    }
}

// Validate form
document.querySelector('form').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    const category = document.getElementById('category').value;
    const content = document.getElementById('content').value.trim();
    
    if (!title) {
        alert('Vui lòng nhập tiêu đề bài viết.');
        e.preventDefault();
        return;
    }
    
    if (!category) {
        alert('Vui lòng chọn chuyên mục.');
        e.preventDefault();
        return;
    }
    
    if (!content) {
        alert('Vui lòng nhập nội dung bài viết.');
        e.preventDefault();
        return;
    }
});

// Auto-generate slug on page load if editing
<?php if ($is_edit): ?>
document.addEventListener('DOMContentLoaded', function() {
    if (!document.getElementById('slug').value) {
        generateSlug();
    }
});
<?php endif; ?>
</script>

<?php require_once 'view/layout/footer.php'; ?> 