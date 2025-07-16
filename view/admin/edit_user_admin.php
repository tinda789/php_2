<?php echo '<link rel="stylesheet" href="view/layout/edit_user_admin.css">'; ?>

<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white fw-bold">
          <i class="fa fa-user-edit me-1"></i> Sửa thông tin người dùng
        </div>
        <div class="card-body">
          <form method="post">
            <div class="mb-3">
              <label class="form-label">Họ</label>
              <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($user_edit['first_name'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Tên</label>
              <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($user_edit['last_name'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user_edit['email'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Số điện thoại</label>
              <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($user_edit['phone'] ?? ''); ?>">
            </div>
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" <?php if ($user_edit['is_active']) echo 'checked'; ?>>
              <label class="form-check-label" for="is_active">Hoạt động</label>
            </div>
            <div class="mb-3">
              <label class="form-label">Quyền</label>
              <select class="form-select" name="role">
                <?php foreach ($roles as $role): ?>
                  <option value="<?php echo $role; ?>" <?php if ($user_edit['role_name'] === $role) echo 'selected'; ?>><?php echo ucfirst($role); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="d-flex justify-content-between">
              <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Lưu</button>
              <a href="index.php?controller=admin&action=manage_user" class="btn btn-secondary">Hủy</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div> 