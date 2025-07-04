<?php echo '<link rel="stylesheet" href="view/layout/create_category.css">'; ?>
<div class="create-category-container">
    <div class="create-category-title">Thêm danh mục mới</div>
    <?php if (!empty($error)): ?>
        <div class="alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label for="name" class="form-label">Tên danh mục *</label>
            <input type="text" class="form-control" id="name" name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea class="form-control" id="description" name="description"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="parent_id" class="form-label">Danh mục cha</label>
            <select class="form-select" id="parent_id" name="parent_id">
                <option value="">(Không chọn)</option>
                <?php foreach ($parentCategories as $p): ?>
                    <option value="<?php echo $p['id']; ?>" <?php if (!empty($_POST['parent_id']) && $_POST['parent_id'] == $p['id']) echo 'selected'; ?>><?php echo htmlspecialchars($p['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="slug" class="form-label">Slug *</label>
            <input type="text" class="form-control" id="slug" name="slug" required value="<?php echo htmlspecialchars($_POST['slug'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Ảnh (URL)</label>
            <input type="text" class="form-control" id="image" name="image" value="<?php echo htmlspecialchars($_POST['image'] ?? ''); ?>">
        </div>
        <div class="mb-3 form-check" style="margin-bottom:18px;">
            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" <?php if (isset($_POST['is_active']) || !isset($_POST['name'])) echo 'checked'; ?> >
            <label class="form-check-label" for="is_active">Hoạt động</label>
        </div>
        <button type="submit" class="btn-save">Lưu</button>
        <a href="index.php?controller=category&action=index" class="btn-cancel">Quay lại</a>
    </form>
</div> 