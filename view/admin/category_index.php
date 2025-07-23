<div class="container-fluid py-3">
  <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2">
    <h1 class="h4 text-primary m-0">Quản lý danh mục</h1>
    <a href="index.php?controller=admin&action=category_create" class="btn btn-primary">
      <i class="fa fa-plus me-1"></i> Thêm danh mục mới
    </a>
  </div>

  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?php
      $success = $_GET['success'];
      if ($success == '1') echo 'Thêm danh mục thành công!';
      elseif ($success == '2') echo 'Cập nhật danh mục thành công!';
      elseif ($success == '3') echo 'Xóa danh mục thành công!';
      ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>
  <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?php
      $error = $_GET['error'];
      if ($error == 'not_found') echo 'Không tìm thấy danh mục!';
      elseif ($error == 'delete_failed') echo 'Xóa danh mục thất bại!';
      else echo 'Có lỗi xảy ra!';
      ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>
  <?php if (isset($_GET['error_msg'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?php echo htmlspecialchars($_GET['error_msg']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
      <form method="GET" class="row g-2 align-items-center">
        <input type="hidden" name="controller" value="admin">
        <input type="hidden" name="action" value="category_index">
        <div class="col-md-6 col-12">
          <input type="text" class="form-control" name="search" placeholder="Tìm kiếm danh mục..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
        </div>
        <div class="col-auto">
          <button type="submit" class="btn btn-primary">Tìm kiếm</button>
        </div>
        <div class="col-auto">
          <a href="index.php?controller=admin&action=category_index" class="btn btn-secondary">Xóa bộ lọc</a>
        </div>
      </form>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-bordered align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Thứ tự</th>
              <th>Tên danh mục</th>
              <th>Mô tả</th>
              <th>Danh mục cha</th>
              <th>Trạng thái</th>
              <th style="width:120px;">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($categories)): ?>
              <tr>
                <td colspan="6" class="text-center">Không có danh mục nào</td>
              </tr>
            <?php else: ?>
              <?php foreach ($categories as $i => $category): ?>
                <tr>
                  <td><?php echo $i + 1; ?></td>
                  <td>
                    <strong><?php echo htmlspecialchars($category['name']); ?></strong>
                    <?php if ($category['slug']): ?>
                      <br><small class="text-muted">Slug: <?php echo htmlspecialchars($category['slug']); ?></small>
                    <?php endif; ?>
                  </td>
                  <td><?php echo htmlspecialchars($category['description'] ?? ''); ?></td>
                  <td>
                    <?php 
                    if ($category['parent_id']) {
                      $parent = Category::getById($conn, $category['parent_id']);
                      echo htmlspecialchars($parent['name'] ?? 'N/A');
                    } else {
                      echo '<span class="text-muted">Danh mục gốc</span>';
                    }
                    ?>
                  </td>
                  <td>
                    <span class="badge rounded-pill bg-<?php echo $category['is_active'] ? 'success' : 'secondary'; ?>">
                      <?php echo $category['is_active'] ? 'Hoạt động' : 'Ẩn'; ?>
                    </span>
                  </td>
                  <td>
                    <a href="index.php?controller=admin&action=category_edit&id=<?php echo $category['id']; ?>" class="btn btn-sm btn-primary me-1" title="Sửa"><i class="fa fa-edit"></i></a>
                    <a href="index.php?controller=admin&action=category_delete&id=<?php echo $category['id']; ?>" class="btn btn-sm btn-danger" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')"><i class="fa fa-trash"></i></a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php if ($totalPages > 1): ?>
      <div class="card-footer bg-white py-3">
        <nav aria-label="Page navigation">
          <ul class="pagination justify-content-center mb-0">
            <?php if ($page > 1): ?>
              <li class="page-item">
                <a class="page-link" href="?controller=admin&action=category_index&page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search ?? ''); ?>">Trước</a>
              </li>
            <?php endif; ?>
            <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
              <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                <a class="page-link" href="?controller=admin&action=category_index&page=<?php echo $i; ?>&search=<?php echo urlencode($search ?? ''); ?>"><?php echo $i; ?></a>
              </li>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
              <li class="page-item">
                <a class="page-link" href="?controller=admin&action=category_index&page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search ?? ''); ?>">Sau</a>
              </li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>
    <?php endif; ?>
  </div>
</div> 
