<div class="container-fluid py-3">
  <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2">
    <h1 class="h4 text-primary m-0"><i class="fa fa-image me-1"></i> Quản lý Banner</h1>
    <a href="index.php?controller=banner&action=create" class="btn btn-primary">
      <i class="fa fa-plus me-1"></i> Thêm Banner
    </a>
  </div>
  <?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>
  <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>
  <div class="card shadow-sm mb-4">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-bordered align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Ảnh</th>
              <th>Tiêu đề</th>
              <th>Vị trí</th>
              <th>Thứ tự</th>
              <th>Trạng thái</th>
              <th style="width:140px;">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($banners)): ?>
              <?php foreach ($banners as $i => $banner): ?>
                <tr>
                  <td><?php echo $i+1; ?></td>
                  <td>
                    <?php if (!empty($banner['image'])): ?>
                      <img src="uploads/banners/<?php echo htmlspecialchars($banner['image']); ?>" alt="Banner" style="width:64px; height:40px; object-fit:cover; border-radius:6px;">
                    <?php else: ?>
                      <span class="text-muted small">Không có ảnh</span>
                    <?php endif; ?>
                  </td>
                  <td><?php echo htmlspecialchars($banner['title'] ?? ''); ?></td>
                  <td><span class="badge bg-info text-dark"><?php echo htmlspecialchars($banner['position'] ?? ''); ?></span></td>
                  <td><?php echo (int)($banner['sort_order'] ?? 0); ?></td>
                  <td>
                    <span class="badge rounded-pill bg-<?php echo $banner['status'] ? 'success' : 'secondary'; ?>">
                      <?php echo $banner['status'] ? 'Đang hoạt động' : 'Tạm ẩn'; ?>
                    </span>
                  </td>
                  <td>
                    <a href="index.php?controller=banner&action=edit&id=<?php echo $banner['id']; ?>" class="btn btn-sm btn-primary me-1" title="Sửa"><i class="fa fa-edit"></i></a>
                    <a href="index.php?controller=banner&action=delete&id=<?php echo $banner['id']; ?>" class="btn btn-sm btn-danger" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa banner này?')"><i class="fa fa-trash"></i></a>
                    <a href="index.php?controller=banner&action=toggleStatus&id=<?php echo $banner['id']; ?>" class="btn btn-sm btn-warning ms-1" title="Bật/Tắt"><i class="fa fa-toggle-on"></i></a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="7" class="text-center text-muted">Chưa có banner nào.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div> 