<?php // thinh: Quản lý đơn hàng - cập nhật cho đúng cấu trúc bảng orders mới
/*
    Biến $orders là mảng các đơn hàng, mỗi đơn có các trường:
    id, order_number, user_id, order_date, total_amount, status
*/
?>
<div class="container-fluid py-3">
  <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2">
    <h1 class="h4 text-primary m-0">Quản lý đơn hàng</h1>
  </div>
  
  <!-- Form lọc đơn hàng -->
  <div class="card mb-4 shadow-sm">
    <div class="card-header bg-light">
      <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Bộ lọc đơn hàng</h5>
    </div>
    <div class="card-body">
      <form method="get" class="row g-3">
        <input type="hidden" name="controller" value="admin">
        <input type="hidden" name="action" value="order_manage">
        
        <div class="col-md-3">
          <label class="form-label">Mã đơn hàng</label>
          <input type="text" class="form-control" name="order_number" value="<?php echo htmlspecialchars($pagination['filters']['order_number'] ?? ''); ?>" placeholder="Nhập mã đơn hàng">
        </div>
        
        <div class="col-md-3">
          <label class="form-label">Trạng thái</label>
          <select class="form-select" name="status">
            <option value="">Tất cả trạng thái</option>
            <?php foreach ($statuses as $key => $label): ?>
              <option value="<?php echo $key; ?>" <?php echo (isset($pagination['filters']['status']) && $pagination['filters']['status'] == $key) ? 'selected' : ''; ?>>
                <?php echo $label; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        
        <div class="col-md-3">
          <label class="form-label">Từ ngày</label>
          <input type="date" class="form-control" name="start_date" value="<?php echo htmlspecialchars($pagination['filters']['start_date'] ?? ''); ?>">
        </div>
        
        <div class="col-md-3">
          <label class="form-label">Đến ngày</label>
          <input type="date" class="form-control" name="end_date" value="<?php echo htmlspecialchars($pagination['filters']['end_date'] ?? ''); ?>">
        </div>
        
        <div class="col-md-6">
          <label class="form-label">Tên sản phẩm</label>
          <input type="text" class="form-control" name="product_name" value="<?php echo htmlspecialchars($pagination['filters']['product_name'] ?? ''); ?>" placeholder="Nhập tên sản phẩm">
        </div>
        
        <div class="col-md-12 d-flex justify-content-between align-items-center">
          <div>
            <button type="submit" class="btn btn-primary me-2">
              <i class="fas fa-search me-1"></i> Lọc
            </button>
            <a href="?controller=admin&action=order_manage" class="btn btn-outline-secondary">
              <i class="fas fa-undo me-1"></i> Đặt lại
            </a>
          </div>
          <div class="text-muted small">
            Tìm thấy <strong><?php echo number_format($pagination['total_items']); ?></strong> đơn hàng
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="card shadow-sm mb-4">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-bordered align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Mã đơn</th>
              <th>User ID</th>
              <th>Tên hàng</th>
              <th>Ngày đặt</th>
              <th>Tổng tiền</th>
              <th>Trạng thái</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($orders)): foreach ($orders as $order): ?>
            <tr>
              <td><?php echo $order['id']; ?></td>
              <td><?php echo htmlspecialchars($order['order_number']); ?></td>
              <td><?php echo $order['user_id']; ?></td>
              <td><?php echo isset($order['product_names']) ? htmlspecialchars($order['product_names']) : ''; ?></td>
              <td><?php echo $order['order_date']; ?></td>
              <td><?php echo number_format($order['total_amount']); ?> đ</td>
              <td>
                <?php
                  $status_map = [
                    'pending' => ['Chờ xác nhận', 'secondary'],
                    'confirmed' => ['Đã xác nhận', 'primary'],
                    'processing' => ['Đang xử lý', 'warning text-dark'],
                    'shipped' => ['Đã gửi hàng', 'info'],
                    'delivered' => ['Đã giao', 'success'],
                    'cancelled' => ['Đã hủy', 'danger'],
                    'refunded' => ['Đã hoàn tiền', 'dark'],
                  ];
                  $status = $order['status'];
                  $badge = $status_map[$status] ?? [$status, 'secondary'];
                ?>
                <span class="badge rounded-pill bg-<?php echo $badge[1]; ?>"> <?php echo $badge[0]; ?> </span>
              </td>
              <td>
                <form method="post" action="index.php?controller=admin&action=order_update_status" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn cập nhật trạng thái đơn hàng này?')">
                  <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                  <select name="status" class="form-select d-inline-block w-auto">
                    <option value="pending" <?php if($order['status']=='pending') echo 'selected'; ?>>Chờ xác nhận</option>
                    <option value="confirmed" <?php if($order['status']=='confirmed') echo 'selected'; ?>>Đã xác nhận</option>
                    <option value="processing" <?php if($order['status']=='processing') echo 'selected'; ?>>Đang xử lý</option>
                    <option value="shipped" <?php if($order['status']=='shipped') echo 'selected'; ?>>Đã gửi hàng</option>
                    <option value="delivered" <?php if($order['status']=='delivered') echo 'selected'; ?>>Đã giao</option>
                    <option value="cancelled" <?php if($order['status']=='cancelled') echo 'selected'; ?>>Đã hủy</option>
                    <option value="refunded" <?php if($order['status']=='refunded') echo 'selected'; ?>>Đã hoàn tiền</option>
                  </select>
                  <button type="submit" class="btn btn-sm btn-primary">Cập nhật</button>
                </form>
                <form method="post" action="index.php?controller=admin&action=order_confirm" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn xác nhận đơn hàng này?')">
                  <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                  <button type="submit" class="btn btn-sm btn-success">Xác nhận</button>
                </form>
                <a href="index.php?controller=admin&action=order_cancel&id=<?php echo $order['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn hủy đơn này?')">Hủy</a>
              </td>
            </tr>
            <?php endforeach; else: ?>
            <tr><td colspan="8" class="text-center text-muted">Chưa có đơn hàng nào.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="mt-3 text-end">
    <a href="index.php?controller=admin&action=export_orders_excel" class="btn btn-success btn-sm">
      <i class="fa fa-file-excel"></i> Xuất Excel
    </a>
  </div>
  
  <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
  <nav aria-label="Page navigation" class="mt-4">
    <ul class="pagination justify-content-center">
      <?php if ($pagination['current_page'] > 1): ?>
      <li class="page-item">
        <a class="page-link" href="?controller=admin&action=order_manage&page=<?php echo ($pagination['current_page'] - 1); ?>" aria-label="Previous">
          <span aria-hidden="true">&laquo;</span>
        </a>
      </li>
      <?php endif; ?>
      
      <?php 
      $start_page = max(1, $pagination['current_page'] - 2);
      $end_page = min($pagination['total_pages'], $pagination['current_page'] + 2);
      
      // Đảm bảo luôn hiển thị đủ 5 trang nếu có thể
      if ($end_page - $start_page < 4) {
          if ($start_page == 1) {
              $end_page = min($pagination['total_pages'], $start_page + 4);
          } else {
              $start_page = max(1, $end_page - 4);
          }
      }
      
      // Hiển thị nút trang đầu tiên
      if ($start_page > 1): ?>
      <li class="page-item">
        <a class="page-link" href="?controller=admin&action=order_manage&page=1">1</a>
      </li>
      <?php if ($start_page > 2): ?>
      <li class="page-item disabled">
        <span class="page-link">...</span>
      </li>
      <?php endif; ?>
      <?php endif; ?>
      
      <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
      <li class="page-item <?php echo $i == $pagination['current_page'] ? 'active' : ''; ?>">
        <a class="page-link" href="?controller=admin&action=order_manage&page=<?php echo $i; ?>">
          <?php echo $i; ?>
          <?php if ($i == $pagination['current_page']): ?>
          <span class="sr-only">(current)</span>
          <?php endif; ?>
        </a>
      </li>
      <?php endfor; ?>
      
      <?php // Hiển thị nút trang cuối cùng
      if ($end_page < $pagination['total_pages']): ?>
      <?php if ($end_page < $pagination['total_pages'] - 1): ?>
      <li class="page-item disabled">
        <span class="page-link">...</span>
      </li>
      <?php endif; ?>
      <li class="page-item">
        <a class="page-link" href="?controller=admin&action=order_manage&page=<?php echo $pagination['total_pages']; ?>">
          <?php echo $pagination['total_pages']; ?>
        </a>
      </li>
      <?php endif; ?>
      
      <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
      <li class="page-item">
        <a class="page-link" href="?controller=admin&action=order_manage&page=<?php echo ($pagination['current_page'] + 1); ?>" aria-label="Next">
          <span aria-hidden="true">&raquo;</span>
        </a>
      </li>
      <?php endif; ?>
    </ul>
    <div class="text-center text-muted small mt-2">
      Hiển thị <?php echo (($pagination['current_page'] - 1) * $pagination['items_per_page'] + 1); ?> 
      - <?php echo min($pagination['current_page'] * $pagination['items_per_page'], $pagination['total_items']); ?> 
      trong tổng số <?php echo $pagination['total_items']; ?> đơn hàng
    </div>
  </nav>
  <?php endif; ?>
</div> 