<div class="container-fluid py-3">
  <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2">
    <div>
      <h1 class="h4 text-primary m-0">Quản lý người dùng</h1>
      <div class="text-muted small mt-1">Tổng số: <b><?php echo count($users ?? []); ?></b> người dùng</div>
    </div>
    <a href="index.php?controller=admin&action=add_user_admin" class="btn btn-primary">
      <i class="fa fa-plus me-1"></i> Thêm người dùng
    </a>
  </div>
  <div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
      <form method="GET" class="row g-2 align-items-center">
        <input type="hidden" name="controller" value="admin">
        <input type="hidden" name="action" value="manage_user">
        <div class="col-md-6 col-12">
          <input type="text" class="form-control" name="search" placeholder="Tìm kiếm tên, email..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
        </div>
        <div class="col-auto">
          <button type="submit" class="btn btn-primary">Tìm kiếm</button>
        </div>
        <div class="col-auto">
          <a href="index.php?controller=admin&action=manage_user" class="btn btn-secondary">Xóa bộ lọc</a>
        </div>
      </form>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-bordered align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th style="width:48px;"></th>
              <th>ID</th>
              <th>Tên đăng nhập</th>
              <th>Email</th>
              <th>Họ tên</th>
              <th>Số điện thoại</th>
              <th>Vai trò</th>
              <th>Trạng thái</th>
              <th style="width:120px;">Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($users)): ?>
              <?php foreach ($users as $user): ?>
                <tr>
                  <td class="text-center">
                    <span class="avatar bg-light text-secondary rounded-circle d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;font-size:1.1rem;">
                      <i class="fa fa-user"></i>
                    </span>
                  </td>
                  <td><?php echo $user['id']; ?></td>
                  <td><?php echo htmlspecialchars($user['username'] ?? ''); ?></td>
                  <td><?php echo htmlspecialchars($user['email'] ?? ''); ?></td>
                  <td><?php echo htmlspecialchars(trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''))); ?></td>
                  <td><?php echo htmlspecialchars($user['phone'] ?? ''); ?></td>
                  <td>
                    <span class="badge bg-<?php echo ($user['role_name'] ?? '') === 'super_admin' ? 'danger' : (($user['role_name'] ?? '') === 'admin' ? 'primary' : 'secondary'); ?>">
                      <?php echo htmlspecialchars($user['role_name'] ?? ''); ?>
                    </span>
                  </td>
                  <td>
                    <span class="badge rounded-pill bg-<?php echo $user['is_active'] ? 'success' : 'secondary'; ?>">
                      <?php echo $user['is_active'] ? 'Hoạt động' : 'Khóa'; ?>
                    </span>
                  </td>
                  <td>
                    <?php if ($user['id'] != $_SESSION['user']['id']): ?>
                      <a href="index.php?controller=admin&action=edit_user_admin&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-primary me-1" title="Sửa" data-bs-toggle="tooltip"><i class="fa fa-edit"></i></a>
                      <a href="index.php?controller=admin&action=delete_user_admin&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" title="Xóa" data-bs-toggle="tooltip" onclick="return confirm('Bạn có chắc muốn xóa?')"><i class="fa fa-trash"></i></a>
                    <?php else: ?>
                      <span class="text-muted small">Tài khoản của bạn</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="9" class="text-center text-muted">Không có người dùng nào.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script>
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.forEach(function (tooltipTriggerEl) {
    new bootstrap.Tooltip(tooltipTriggerEl);
  });
</script> 