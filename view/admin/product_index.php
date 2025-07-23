<!-- thanhdat: Nút tải file Excel mẫu nhập sản phẩm -->
<div style="margin-bottom: 16px;">
  <a href="create_excel_template.php" class="btn btn-success">
    <i class="fa fa-file-excel"></i> Tải file Excel mẫu nhập sản phẩm
  </a>
</div>

<form action="import_products.php" method="post" enctype="multipart/form-data" style="margin-bottom: 20px;">
    <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" required>
    <button type="submit">Nhập sản phẩm từ Excel/CSV</button>
</form>

<div class="container-fluid py-3">
  <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2">
    <h1 class="h4 text-primary m-0">Quản lý sản phẩm</h1>
    <a href="index.php?controller=admin&action=product_create" class="btn btn-primary">
      <i class="fa fa-plus me-1"></i> Thêm sản phẩm mới
    </a>
  </div>

  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?php
      $success = $_GET['success'];
      if ($success == '1') echo 'Thêm sản phẩm thành công!';
      elseif ($success == '2') echo 'Cập nhật sản phẩm thành công!';
      elseif ($success == '3') echo 'Xóa sản phẩm thành công!';
      ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>
  <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?php
      $error = $_GET['error'];
      if ($error == 'not_found') echo 'Không tìm thấy sản phẩm!';
      elseif ($error == 'delete_failed') echo 'Xóa sản phẩm thất bại!';
      else echo 'Có lỗi xảy ra!';
      ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
      <form method="GET" class="row g-2 align-items-center">
        <input type="hidden" name="controller" value="admin">
        <input type="hidden" name="action" value="product_index">
        <div class="col-md-6 col-12">
          <input type="text" class="form-control" name="search" placeholder="Tìm kiếm sản phẩm..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
        </div>
        <div class="col-auto">
          <button type="submit" class="btn btn-primary">Tìm kiếm</button>
        </div>
        <div class="col-auto">
          <a href="index.php?controller=admin&action=product_index" class="btn btn-secondary">Xóa bộ lọc</a>
        </div>
      </form>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-bordered align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Tên sản phẩm</th>
              <th>Danh mục</th>
              <th>Thương hiệu</th>
              <th>Giá</th>
              <th>Giá khuyến mãi</th>
              <th>Tồn kho</th>
              <th>Trạng thái</th>
              <th>Nổi bật</th>
              <th style="width:120px;">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($products)): ?>
              <tr>
                <td colspan="10" class="text-center">Không có sản phẩm nào</td>
              </tr>
            <?php else: ?>
              <?php foreach ($products as $product): ?>
                <tr>
                  <td><?php echo $product['id']; ?></td>
                  <td>
                    <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                    <?php if ($product['sku']): ?>
                      <br><small class="text-muted">SKU: <?php echo htmlspecialchars($product['sku']); ?></small>
                    <?php endif; ?>
                  </td>
                  <td><?php echo htmlspecialchars($product['category_name'] ?? 'N/A'); ?></td>
                  <td><?php echo htmlspecialchars($product['brand_name'] ?? 'N/A'); ?></td>
                  <td><?php echo number_format($product['price'], 0, ',', '.'); ?> đ</td>
                  <td>
                    <?php if ($product['sale_price'] > 0): ?>
                      <?php echo number_format($product['sale_price'], 0, ',', '.'); ?> đ
                    <?php else: ?>
                      -
                    <?php endif; ?>
                  </td>
                  <td>
                    <span class="badge rounded-pill bg-<?php echo $product['stock_quantity'] > 0 ? 'success' : 'danger'; ?>">
                      <?php echo $product['stock_quantity']; ?> 
                    </span>
                  </td>
                  <td>
                    <span class="badge rounded-pill bg-<?php echo $product['status'] ? 'success' : 'secondary'; ?>">
                      <?php echo $product['status'] ? 'Hoạt động' : 'Ẩn'; ?>
                    </span>
                  </td>
                  <td>
                    <span class="badge rounded-pill bg-<?php echo $product['featured'] ? 'warning text-dark' : 'secondary'; ?>">
                      <?php echo $product['featured'] ? 'Nổi bật' : 'Bình thường'; ?>
                    </span>
                  </td>
                  <td>
                    <a href="index.php?controller=admin&action=product_edit&id=<?php echo $product['id']; ?>" class="btn btn-sm btn-primary me-1" title="Sửa"><i class="fa fa-edit"></i></a>
                    <a href="index.php?controller=admin&action=product_delete&id=<?php echo $product['id']; ?>" class="btn btn-sm btn-danger" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')"><i class="fa fa-trash"></i></a>
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
                <a class="page-link" href="?controller=admin&action=product_index&page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search ?? ''); ?>">Trước</a>
              </li>
            <?php endif; ?>
            <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
              <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                <a class="page-link" href="?controller=admin&action=product_index&page=<?php echo $i; ?>&search=<?php echo urlencode($search ?? ''); ?>"><?php echo $i; ?></a>
              </li>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
              <li class="page-item">
                <a class="page-link" href="?controller=admin&action=product_index&page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search ?? ''); ?>">Sau</a>
              </li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>
    <?php endif; ?>
  </div>
</div> 