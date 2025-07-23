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
</div> 