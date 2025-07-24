<?php require_once 'view/layout/header_admin.php'; ?>
<?php // thanhdat: Form thêm/sửa tin tức

$is_edit = isset($news);
$title = $is_edit ? 'Sửa Tin tức' : 'Thêm Tin tức Mới';
$action = $is_edit ? "index.php?controller=news&action=edit&id={$news['id']}" : "index.php?controller=news&action=create";
?>
<style>
.news-form-container {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(33,150,243,0.08);
    padding: 36px 32px 32px 32px;
    margin: 32px auto 0 auto;
    max-width: 900px;
    margin-top: 90px !important;
}
.news-form-title {
    color: #1976d2;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
}
.news-form-title i { color: #1976d2; font-size: 1.5em; }
.news-form-label { font-weight: 600; color: #1976d2; margin-bottom: 6px; }
.news-form-group { margin-bottom: 22px; }
.news-form-input, .news-form-select, .news-form-textarea {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid #90caf9;
    border-radius: 10px;
    font-size: 1.08rem;
    background: #f4f6fb;
    color: #23272f;
    margin-bottom: 6px;
    transition: border 0.2s;
}
.news-form-input:focus, .news-form-select:focus, .news-form-textarea:focus {
    border: 1.5px solid #1976d2;
    outline: none;
    background: #fff;
}
.news-form-btn {
    background: #1976d2;
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 12px 32px;
    font-size: 1.1rem;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(33,150,243,0.10);
    transition: background 0.18s, color 0.18s, box-shadow 0.18s;
    margin-right: 12px;
}
.news-form-btn:hover { background: #0d47a1; color: #fff; box-shadow: 0 4px 16px rgba(33,150,243,0.13); }
.news-form-cancel {
    background: #f4f6fb;
    color: #1976d2;
    border: 1.5px solid #1976d2;
    border-radius: 10px;
    padding: 12px 32px;
    font-size: 1.1rem;
    font-weight: 600;
    transition: background 0.18s, color 0.18s;
}
.news-form-cancel:hover { background: #1976d2; color: #fff; }
.news-form-preview {
    background: #f4f6fb;
    border-radius: 12px;
    padding: 18px;
    text-align: center;
    min-height: 220px;
    margin-bottom: 18px;
    box-shadow: 0 2px 8px rgba(33,150,243,0.07);
}
.news-form-preview img {
    max-height: 180px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(33,150,243,0.10);
}
.news-form-card {
    background: #f4f6fb;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(33,150,243,0.07);
    padding: 18px 18px 10px 18px;
    margin-bottom: 18px;
}
@media (max-width: 900px) {
    .news-form-container { padding: 12px 4vw; }
}
</style>
<div class="news-form-container">
  <div class="news-form-title"><i class="fas fa-<?= $is_edit ? 'edit' : 'plus' ?>"></i> <?= $title ?></div>
  <form method="POST" action="<?= $action ?>" enctype="multipart/form-data">
    <div class="news-form-group">
      <label for="title" class="news-form-label">Tiêu đề bài viết <span class="text-danger">*</span></label>
      <input type="text" class="news-form-input" id="title" name="title" value="<?= $is_edit ? htmlspecialchars($news['title']) : '' ?>" required onkeyup="generateSlug()">
      <small class="form-text text-muted">Tiêu đề sẽ được tự động chuyển thành slug URL</small>
    </div>
    <div class="news-form-group">
      <label for="slug" class="news-form-label">Slug URL</label>
      <input type="text" class="news-form-input" id="slug" name="slug" value="<?= $is_edit ? htmlspecialchars($news['slug']) : '' ?>" readonly>
      <small class="form-text text-muted">URL thân thiện cho bài viết (tự động tạo từ tiêu đề)</small>
    </div>
    <div class="row">
      <div class="col-md-6 news-form-group">
        <label for="category" class="news-form-label">Chuyên mục <span class="text-danger">*</span></label>
        <select class="news-form-select" id="category" name="category" required>
          <option value="">Chọn chuyên mục</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= ($is_edit && $news['category'] == $cat['id']) ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6 news-form-group">
        <label for="status" class="news-form-label">Trạng thái</label>
        <select class="news-form-select" id="status" name="status">
          <option value="1" <?= ($is_edit && $news['status'] == 1) ? 'selected' : '' ?>>Hiển thị</option>
          <option value="0" <?= ($is_edit && $news['status'] == 0) ? 'selected' : '' ?>>Tạm ẩn</option>
        </select>
      </div>
    </div>
    <div class="news-form-group">
      <label for="summary" class="news-form-label">Tóm tắt</label>
      <textarea class="news-form-textarea" id="summary" name="summary" rows="3" placeholder="Tóm tắt ngắn gọn về bài viết..."><?= $is_edit ? htmlspecialchars($news['summary']) : '' ?></textarea>
      <small class="form-text text-muted">Mô tả ngắn gọn sẽ hiển thị trong danh sách bài viết</small>
    </div>
    <div class="news-form-group">
      <label for="content" class="news-form-label">Nội dung bài viết <span class="text-danger">*</span></label>
      <textarea class="news-form-textarea" id="content" name="content" rows="12" placeholder="Nhập nội dung bài viết..." required><?= $is_edit ? htmlspecialchars($news['content']) : '' ?></textarea>
      <small class="form-text text-muted">Nội dung chính của bài viết</small>
    </div>
    <div class="news-form-group">
      <label for="image" class="news-form-label">
        Ảnh đại diện
        <?php if ($is_edit && $news['image']): ?>
          <small class="text-muted">(Chọn ảnh mới để thay thế)</small>
        <?php endif; ?>
      </label>
      <input type="file" class="form-control-file" id="image" name="image" accept="image/*" onchange="previewImage(this)" <?= $is_edit ? '' : 'required' ?>>
      <small class="form-text text-muted">Định dạng: JPG, PNG, GIF, WebP. Kích thước tối đa: 5MB</small>
    </div>
    <div class="news-form-group">
      <button type="submit" class="news-form-btn"><i class="fas fa-save"></i> <?= $is_edit ? 'Cập nhật' : 'Thêm' ?> Bài viết</button>
      <a href="index.php?controller=news&action=index" class="news-form-cancel"><i class="fas fa-times"></i> Hủy</a>
    </div>
    <div class="news-form-preview" id="imagePreview">
      <?php if ($is_edit && $news['image']): ?>
        <img src="uploads/news/<?= $news['image'] ?>" alt="News preview">
        <div class="mt-2"><small class="text-muted">Ảnh hiện tại</small></div>
      <?php else: ?>
        <div style="height: 180px; display: flex; align-items: center; justify-content: center;">
          <div class="text-center">
            <i class="fas fa-image fa-3x text-gray-300 mb-2"></i>
            <p class="text-gray-500 mb-0">Chưa có ảnh</p>
            <small class="text-gray-400">Chọn ảnh để xem trước</small>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </form>
  <div class="row mt-4">
    <div class="col-md-6">
      <div class="news-form-card">
        <h6 class="font-weight-bold text-primary mb-2"><i class="fas fa-tags"></i> Chuyên mục</h6>
        <div class="mb-2"><i class="fas fa-newspaper"></i> Tin tức <span class="text-muted">- Các bài viết về tin tức, sự kiện, cập nhật</span></div>
        <div class="mb-2"><i class="fas fa-tag"></i> Khuyến mãi <span class="text-muted">- Thông tin về các chương trình khuyến mãi</span></div>
        <div class="mb-2"><i class="fas fa-book"></i> Hướng dẫn <span class="text-muted">- Hướng dẫn sử dụng, tips và tricks</span></div>
        <div class="mb-2"><i class="fas fa-briefcase"></i> Tuyển dụng <span class="text-muted">- Thông tin tuyển dụng, cơ hội nghề nghiệp</span></div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="news-form-card">
        <h6 class="font-weight-bold text-warning mb-2"><i class="fas fa-exclamation-triangle"></i> Lưu ý</h6>
        <ul class="list-unstyled mb-0">
          <li class="mb-2"><i class="fas fa-check text-success"></i> Tiêu đề sẽ tự động tạo slug URL</li>
          <li class="mb-2"><i class="fas fa-check text-success"></i> Nội dung có thể sử dụng HTML cơ bản</li>
          <li class="mb-2"><i class="fas fa-check text-success"></i> Ảnh đại diện giúp bài viết hấp dẫn hơn</li>
          <li class="mb-2"><i class="fas fa-check text-success"></i> Bản nháp có thể chỉnh sửa trước khi xuất bản</li>
        </ul>
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
                <img src="${e.target.result}" alt="News preview">
                <div class="mt-2"><small class="text-muted">Ảnh mới</small></div>
            `;
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.innerHTML = `
            <div style="height: 180px; display: flex; align-items: center; justify-content: center;">
                <div class="text-center">
                    <i class="fas fa-image fa-3x text-gray-300 mb-2"></i>
                    <p class="text-gray-500 mb-0">Chưa có ảnh</p>
                    <small class="text-gray-400">Chọn ảnh để xem trước</small>
                </div>
            </div>
        `;
    }
}
document.querySelector('form').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    const category = document.getElementById('category').value;
    const content = document.getElementById('content').value.trim();
    if (!title) { alert('Vui lòng nhập tiêu đề bài viết.'); e.preventDefault(); return; }
    if (!category) { alert('Vui lòng chọn chuyên mục.'); e.preventDefault(); return; }
    if (!content) { alert('Vui lòng nhập nội dung bài viết.'); e.preventDefault(); return; }
});
<?php if ($is_edit): ?>
document.addEventListener('DOMContentLoaded', function() {
    if (!document.getElementById('slug').value) {
        generateSlug();
    }
});
<?php endif; ?>
</script>
<?php require_once 'view/layout/footer.php'; ?> 