<?php // thanhdat: Form thêm/sửa banner
$is_edit = isset($banner);
?>
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white fw-bold">
          <i class="fa fa-<?= $is_edit ? 'edit' : 'plus' ?> me-1"></i> <?= $is_edit ? 'Sửa Banner' : 'Thêm Banner' ?>
        </div>
        <div class="card-body">
          <form method="POST" enctype="multipart/form-data" id="bannerForm">
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="title" name="title" value="<?= $is_edit ? htmlspecialchars($banner['title']) : '' ?>" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="position" class="form-label">Vị trí hiển thị <span class="text-danger">*</span></label>
                  <select class="form-select" id="position" name="position" required>
                    <option value="">Chọn vị trí</option>
                    <option value="header" <?= $is_edit && $banner['position'] == 'header' ? 'selected' : '' ?>>Header</option>
                    <option value="sidebar" <?= $is_edit && $banner['position'] == 'sidebar' ? 'selected' : '' ?>>Sidebar</option>
                    <option value="footer" <?= $is_edit && $banner['position'] == 'footer' ? 'selected' : '' ?>>Footer</option>
                    <option value="homepage" <?= $is_edit && $banner['position'] == 'homepage' ? 'selected' : '' ?>>Homepage</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Mô tả</label>
              <textarea class="form-control" id="description" name="description" rows="3" placeholder="Mô tả ngắn về banner..."><?= $is_edit ? htmlspecialchars($banner['description']) : '' ?></textarea>
            </div>
            <div class="mb-3">
              <label for="link" class="form-label">Liên kết</label>
              <input type="url" class="form-control" id="link" name="link" value="<?= $is_edit ? htmlspecialchars($banner['link']) : '' ?>" placeholder="https://example.com">
              <small class="form-text text-muted">Để trống nếu không cần liên kết</small>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="sort_order" class="form-label">Thứ tự hiển thị</label>
                  <input type="number" class="form-control" id="sort_order" name="sort_order" value="<?= $is_edit ? $banner['sort_order'] : '0' ?>" min="0">
                  <small class="form-text text-muted">Số càng nhỏ hiển thị càng trước</small>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="status" class="form-label">Trạng thái</label>
                  <select class="form-select" id="status" name="status">
                    <option value="1" <?= $is_edit && $banner['status'] == 1 ? 'selected' : '' ?>>Đang hoạt động</option>
                    <option value="0" <?= $is_edit && $banner['status'] == 0 ? 'selected' : '' ?>>Tạm ẩn</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label for="image" class="form-label">Ảnh banner <?= $is_edit ? '' : '<span class="text-danger">*</span>' ?></label>
              <input type="file" class="form-control" id="image" name="image" accept="image/*" <?= $is_edit ? '' : 'required' ?>>
              <small class="form-text text-muted">Định dạng: JPG, PNG, GIF. Kích thước tối đa: 2MB</small>
            </div>
            <div class="mb-3 d-flex justify-content-between">
              <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> <?= $is_edit ? 'Cập nhật' : 'Thêm' ?> Banner</button>
              <a href="index.php?controller=banner&action=index" class="btn btn-secondary"><i class="fa fa-times me-1"></i> Hủy</a>
            </div>
          </form>
        </div>
      </div>
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-white fw-bold text-primary">
          <i class="fa fa-eye me-1"></i> Xem trước ảnh
        </div>
        <div class="card-body text-center">
          <div id="imagePreview" class="mb-3">
            <?php if ($is_edit && $banner['image']): ?>
              <img src="uploads/banners/<?= $banner['image'] ?>" alt="Banner preview" class="img-fluid rounded" style="max-height: 200px;">
            <?php else: ?>
              <div class="bg-light border rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                <div class="text-muted">
                  <i class="fa fa-image fa-3x mb-2"></i>
                  <p>Chưa có ảnh</p>
                </div>
              </div>
            <?php endif; ?>
          </div>
          <small class="text-muted">Ảnh sẽ được hiển thị ở đây khi bạn chọn file</small>
        </div>
      </div>
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-white fw-bold text-primary">
          <i class="fa fa-info-circle me-1"></i> Hướng dẫn vị trí
        </div>
        <div class="card-body">
          <div class="mb-2"><strong>Header:</strong> Hiển thị ở đầu trang</div>
          <div class="mb-2"><strong>Sidebar:</strong> Hiển thị ở thanh bên</div>
          <div class="mb-2"><strong>Footer:</strong> Hiển thị ở cuối trang</div>
          <div class="mb-2"><strong>Homepage:</strong> Hiển thị ở trang chủ</div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
const imgInput = document.getElementById('image');
if (imgInput) {
  imgInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    if (file) {
      if (file.size > 2 * 1024 * 1024) {
        alert('File ảnh quá lớn. Vui lòng chọn file nhỏ hơn 2MB.');
        this.value = '';
        return;
      }
      const reader = new FileReader();
      reader.onload = function(e) {
        preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">`;
      };
      reader.readAsDataURL(file);
    } else {
      preview.innerHTML = `
        <div class="bg-light border rounded d-flex align-items-center justify-content-center" style="height: 200px;">
          <div class="text-muted">
            <i class="fa fa-image fa-3x mb-2"></i>
            <p>Chưa có ảnh</p>
          </div>
        </div>
      `;
    }
  });
}
const bannerForm = document.getElementById('bannerForm');
if (bannerForm) {
  bannerForm.addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    const position = document.getElementById('position').value;
    const image = document.getElementById('image').files[0];
    if (!title) {
      alert('Vui lòng nhập tiêu đề banner.');
      e.preventDefault();
      return;
    }
    if (!position) {
      alert('Vui lòng chọn vị trí hiển thị.');
      e.preventDefault();
      return;
    }
    <?php if (!$is_edit): ?>
    if (!image) {
      alert('Vui lòng chọn ảnh banner.');
      e.preventDefault();
      return;
    }
    <?php endif; ?>
  });
}
</script> 