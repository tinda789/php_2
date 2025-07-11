<?php echo '<link rel="stylesheet" href="view/layout/add_user_admin.css">'; ?>

<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white fw-bold">
          <i class="fa fa-user-plus me-1"></i> Cấp phát tài khoản mới
        </div>
        <div class="card-body">
          <?php if (!empty($success) && !empty($created_user)): ?>
            <div class="alert alert-success mb-3">
              <b><?php echo $success; ?></b><br>
              <div>Tài khoản vừa tạo:</div>
              <div>Tên đăng nhập: <b><?php echo htmlspecialchars($created_user['username']); ?></b></div>
              <div>Email: <b><?php echo htmlspecialchars($created_user['email']); ?></b></div>
              <div>Mật khẩu mặc định: <b>shop1234567890@</b></div>
            </div>
          <?php elseif (!empty($error)): ?>
            <div class="alert alert-danger mb-3"><?php echo $error; ?></div>
          <?php endif; ?>
          <form method="post">
            <div class="mb-3">
              <label class="form-label">Họ</label>
              <input type="text" class="form-control" name="first_name" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Tên</label>
              <input type="text" class="form-control" name="last_name" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Số điện thoại</label>
              <input type="text" class="form-control" name="phone">
            </div>
            <div class="mb-3">
              <label class="form-label">Tên đăng nhập</label>
              <input type="text" class="form-control" name="username" required>
            </div>
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
              <label class="form-check-label" for="is_active">Hoạt động</label>
            </div>
            <div class="mb-3">
              <label class="form-label">Quyền</label>
              <select class="form-select" name="role">
                <?php foreach ($roles as $role): ?>
                  <option value="<?php echo $role; ?>"><?php echo ucfirst($role); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="d-flex justify-content-between">
              <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Tạo tài khoản</button>
              <a href="index.php?controller=admin&action=manage_user" class="btn btn-secondary">Hủy</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div> 