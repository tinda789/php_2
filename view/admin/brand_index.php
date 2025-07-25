<?php /* Bỏ link CSS cũ */ ?>
<div class="container-fluid py-3">
  <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2">
    <h1 class="h4 text-primary m-0">Quản lý thương hiệu</h1>
    <a href="index.php?controller=brand&action=create" class="btn btn-primary">
      <i class="fa fa-plus me-1"></i> Thêm thương hiệu mới
    </a>
  </div>

  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?php
      $success = $_GET['success'];
      if ($success == '1') echo 'Thêm thương hiệu thành công!';
      elseif ($success == '2') echo 'Cập nhật thương hiệu thành công!';
      elseif ($success == '3') echo 'Xóa thương hiệu thành công!';
      ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>
  <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?php
      $error = $_GET['error'];
      if ($error == 'not_found') echo 'Không tìm thấy thương hiệu!';
      elseif ($error == 'delete_failed') echo 'Xóa thương hiệu thất bại!';
      else echo 'Có lỗi xảy ra!';
      ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
      <form method="GET" class="row g-2 align-items-center">
        <input type="hidden" name="controller" value="brand">
        <input type="hidden" name="action" value="index">
        <div class="col-md-6 col-12">
          <input type="text" class="form-control" name="search" placeholder="Tìm kiếm thương hiệu..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
        </div>
        <div class="col-auto">
          <button type="submit" class="btn btn-primary">Tìm kiếm</button>
        </div>
        <div class="col-auto">
          <a href="index.php?controller=brand&action=index" class="btn btn-secondary">Xóa bộ lọc</a>
        </div>
      </form>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-bordered align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Tên thương hiệu</th>
              <th>Mô tả</th>
              <th>Logo</th>
              <th>Website</th>
              <th>Trạng thái</th>
              <th>Ngày thành lập</th>
              <th style="width: 110px;">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($brands)): ?>
              <tr>
                <td colspan="8" class="text-center">Không có thương hiệu nào</td>
              </tr>
            <?php else: ?>
              <?php foreach ($brands as $brand): ?>
                <tr>
                  <td><?php echo $brand['id']; ?></td>
                  <td><strong><?php echo htmlspecialchars($brand['name']); ?></strong></td>
                  <td><?php echo htmlspecialchars($brand['description'] ?? ''); ?></td>
                  <td>
                    <?php if (!empty($brand['logo'])): ?>
                      <img src="<?php echo htmlspecialchars($brand['logo']); ?>" alt="Logo" width="60">
                    <?php else: ?>
                      <span class="text-muted">Không có ảnh</span>
                    <?php endif; ?>
                  </td>
                  <td><?php echo htmlspecialchars($brand['website'] ?? ''); ?></td>
                  <td>
                    <span class="badge rounded-pill bg-<?php echo $brand['is_active'] ? 'success' : 'secondary'; ?>">
                      <?php echo $brand['is_active'] ? 'Hoạt động' : 'Ẩn'; ?>
                    </span>
                  </td>
                  <td><?php echo htmlspecialchars($brand['created_at'] ?? ''); ?></td>
                  <td>
                    <a href="index.php?controller=brand&action=edit&id=<?php echo $brand['id']; ?>" class="btn btn-sm btn-primary me-1" title="Sửa"><i class="fa fa-edit"></i></a>
                    <a href="index.php?controller=brand&action=delete&id=<?php echo $brand['id']; ?>" class="btn btn-sm btn-danger" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa thương hiệu này?')"><i class="fa fa-trash"></i></a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php if (isset($totalPages) && $totalPages > 1): ?>
      <div class="card-footer bg-white py-3">
        <nav aria-label="Page navigation">
          <ul class="pagination justify-content-center mb-0">
            <?php if ($page > 1): ?>
              <li class="page-item">
                <a class="page-link" href="?controller=brand&action=index&page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search ?? ''); ?>">Trước</a>
              </li>
            <?php endif; ?>
            <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
              <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                <a class="page-link" href="?controller=brand&action=index&page=<?php echo $i; ?>&search=<?php echo urlencode($search ?? ''); ?>"><?php echo $i; ?></a>
              </li>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
              <li class="page-item">
                <a class="page-link" href="?controller=brand&action=index&page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search ?? ''); ?>">Sau</a>
              </li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>
    <?php endif; ?>
  </div>
</div>