<<<<<<< HEAD
<?php echo '<link rel="stylesheet" href="view/layout/edit_category.css">'; ?>
<div class="edit-category-container"> <!-- thinh -->
    <div class="edit-category-title">Sửa danh mục</div>
    <?php if (!empty($error)): ?>
        <div class="alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data"> <!-- thinh: thêm enctype để upload ảnh -->
        <div class="mb-3">
            <label for="name" class="form-label">Tên danh mục *</label>
            <input type="text" class="form-control" id="name" name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? $category['name']); ?>">
=======
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-lg-7 col-md-10">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white fw-bold">
          <i class="fa fa-edit me-1"></i> Sửa danh mục
>>>>>>> main
        </div>
        <div class="card-body">
          <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
          <?php endif; ?>
          <form method="post" action="index.php?controller=admin&action=category_update&id=<?php echo $category['id']; ?>">
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
              <label for="image" class="form-label">Ảnh (URL)</label>
              <input type="text" class="form-control" id="image" name="image" value="<?php echo htmlspecialchars($_POST['image'] ?? $category['image'] ?? ''); ?>">
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
<<<<<<< HEAD
        <div class="mb-3">
            <label for="slug" class="form-label">Slug *</label>
            <input type="text" class="form-control" id="slug" name="slug" required value="<?php echo htmlspecialchars($_POST['slug'] ?? $category['slug']); ?>">
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Ảnh (upload)</label> <!-- thinh -->
            <?php if (!empty($category['image'])): ?>
                <div style="margin-bottom:10px;">
                    <img src="<?php echo htmlspecialchars($category['image']); ?>" alt="Ảnh danh mục" style="max-width:120px;max-height:80px;border-radius:8px;box-shadow:0 2px 8px #0002;">
                </div>
            <?php endif; ?>
            <input type="file" class="form-control" id="image" name="image"> <!-- thinh: đổi sang file -->
        </div>
        <div class="mb-3 form-check" style="margin-bottom:18px;">
            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" <?php if ((isset($_POST['is_active']) && $_POST['is_active']) || (!isset($_POST['is_active']) && $category['is_active'])) echo 'checked'; ?> >
            <label class="form-check-label" for="is_active">Hoạt động</label>
        </div>
        <div style="display:flex;gap:18px;align-items:center;"> <!-- thinh -->
            <button type="submit" class="btn-save">Lưu</button>
            <a href="index.php?controller=category&action=index" class="btn-cancel">Quay lại</a>
        </div>
    </form>
</div>
<style>
.edit-category-container {
    max-width: 480px;
    margin: 40px auto;
    background: #23272f;
    border-radius: 16px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.13);
    padding: 36px 32px 32px 32px;
    color: #f1f1f1;
}
.edit-category-title {
    font-size: 1.7rem;
    color: #4fc3f7;
    font-weight: 600;
    margin-bottom: 28px;
    text-align: center;
}
.form-label {
    color: #b0bec5;
    font-weight: 500;
    margin-bottom: 4px;
    display: block;
}
.form-control, .form-select {
    background: #353b48;
    color: #f1f1f1;
    border: 1.5px solid #4fc3f7;
    border-radius: 10px;
    padding: 10px 14px;
    margin-bottom: 12px;
    font-size: 1rem;
    font-family: inherit;
}
.form-control:focus, .form-select:focus {
    outline: none;
    border-color: #00e676;
    background: #23272f;
}
.btn-save {
    background: #00e676;
    color: #23272f;
    border: none;
    border-radius: 18px;
    padding: 10px 32px;
    font-size: 1.1rem;
    font-weight: 500;
    cursor: pointer;
    margin-right: 12px;
    transition: background 0.18s, color 0.18s;
    font-family: inherit;
}
.btn-save:hover { background: #00b248; color: #fff; }
.btn-cancel {
    background: #353b48;
    color: #4fc3f7;
    border: 1.5px solid #4fc3f7;
    border-radius: 18px;
    padding: 10px 32px;
    font-size: 1.1rem;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.18s, color 0.18s;
    font-family: inherit;
    text-decoration: none;
}
.btn-cancel:hover { background: #4fc3f7; color: #23272f; }
</style> 
=======
      </div>
    </div>
  </div>
</div> 
>>>>>>> main
