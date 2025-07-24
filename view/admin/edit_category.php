<?php // thanhdat: merge giao diện Bootstrap + upload file + preview ảnh
?>
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-lg-7 col-md-10">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white fw-bold">
          <i class="fa fa-edit me-1"></i> Sửa danh mục
        </div>
        <div class="card-body">
          <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
          <?php endif; ?>
          <form method="post" action="index.php?controller=admin&action=category_update&id=<?php echo $category['id']; ?>" enctype="multipart/form-data"> <!-- thanhdat: thêm enctype để upload file -->
            <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
            <div class="mb-3">
              <label for="name" class="form-label">Tên danh mục *</label>
              <input type="text" class="form-control" id="name" name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? $category['name'] ?? ''); ?>">
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Mô tả</label>
              <textarea class="form-control" id="description" name="description"><?php echo htmlspecialchars($_POST['description'] ?? $category['description'] ?? ''); ?></textarea>
            </div>
            <div class="mb-3">
              <label for="parent_id" class="form-label">Danh mục cha</label>
              <select class="form-select" id="parent_id" name="parent_id">
                <option value="">(Không chọn)</option>
                <?php foreach ($parentCategories as $p): ?>
                  <?php if ($p['id'] != $category['id']): ?>
                    <option value="<?php echo $p['id']; ?>" <?php if ((isset($_POST['parent_id']) && $_POST['parent_id'] == $p['id']) || (!isset($_POST['parent_id']) && $category['parent_id'] == $p['id'])) echo 'selected'; ?>><?php echo htmlspecialchars($p['name']); ?></option>
                  <?php endif; ?>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="slug" class="form-label">Slug *</label>
              <input type="text" class="form-control" id="slug" name="slug" required value="<?php echo htmlspecialchars($_POST['slug'] ?? $category['slug'] ?? ''); ?>">
            </div>
            <div class="mb-3">
              <label for="image" class="form-label">Ảnh (upload hoặc URL)</label> <!-- thanhdat: cho phép upload file hoặc nhập URL -->
              <?php if (!empty($category['image'])): ?>
                <div class="mb-2">
                  <img src="<?php echo htmlspecialchars($category['image']); ?>" alt="Ảnh danh mục" style="max-width:120px;max-height:80px;border-radius:8px;box-shadow:0 2px 8px #0002;">
                </div>
              <?php endif; ?>
              <input type="file" class="form-control mb-2" id="image_file" name="image_file"> <!-- thanhdat: upload file -->
              <input type="text" class="form-control" id="image" name="image" placeholder="Hoặc nhập URL ảnh" value="<?php echo htmlspecialchars($_POST['image'] ?? $category['image'] ?? ''); ?>"> <!-- thanhdat: nhập URL ảnh -->
            </div>
            <div class="form-check mb-3">
              <input type="checkbox" class="form-check-input" id="is_active" name="is_active" <?php if ((isset($_POST['is_active']) && $_POST['is_active']) || (!isset($_POST['is_active']) && $category['is_active'])) echo 'checked'; ?> >
              <label class="form-check-label" for="is_active">Hoạt động</label>
            </div>
            <div class="d-flex justify-content-between">
              <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Lưu</button>
              <a href="index.php?controller=admin&action=category_index" class="btn btn-secondary">Quay lại</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
