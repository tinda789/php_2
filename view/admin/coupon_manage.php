<?php // thinh: Quản lý mã giảm giá cho admin 
/*
    Biến $coupons là mảng các mã giảm giá, mỗi mã có các trường:
    id, code, name, type, value, minimum_amount, usage_limit, start_date, end_date, is_active, status_label
*/
?>
<div class="container-fluid py-3">
  <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2">
    <h1 class="h4 text-primary m-0">Quản lý mã giảm giá</h1>
    <a href="index.php?controller=admin&action=coupon_create" class="btn btn-primary">
      <i class="fa fa-plus me-1"></i> Thêm mã giảm giá
    </a>
  </div>
  <div class="card shadow-sm mb-4">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle mb-0">
          <thead class="table-primary">
            <tr>
              <th>ID</th>
              <th>Mã</th>
              <th>Tên chương trình</th>
              <th>Loại</th>
              <th>Giá trị</th>
              <th>Đơn tối thiểu</th>
              <th>Giới hạn</th>
              <th>Ngày bắt đầu</th>
              <th>Ngày kết thúc</th>
              <th>Trạng thái</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($coupons as $coupon): ?>
            <tr>
              <td><?= $coupon['id'] ?></td>
              <td><?= htmlspecialchars($coupon['code']) ?></td>
              <td><?= htmlspecialchars($coupon['name']) ?></td>
              <td><?= $coupon['type'] == 'fixed' ? 'Tiền mặt' : 'Phần trăm' ?></td>
              <td><?= $coupon['type'] == 'fixed' ? number_format($coupon['value']) . ' đ' : $coupon['value'] . '%' ?></td>
              <td><?= number_format($coupon['minimum_amount']) ?> đ</td>
              <td><?= $coupon['usage_limit'] ?></td>
              <td><?= $coupon['start_date'] ?></td>
              <td><?= $coupon['end_date'] ?></td>
              <td><?= $coupon['status_label'] ?></td>
              <td>
                <a href="index.php?controller=admin&action=coupon_edit&id=<?= $coupon['id'] ?>" class="btn btn-sm btn-primary me-1" title="Sửa"><i class="fa fa-edit"></i></a>
                <a href="index.php?controller=admin&action=coupon_delete&id=<?= $coupon['id'] ?>" class="btn btn-sm btn-danger" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?')"><i class="fa fa-trash"></i></a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="coupon-guide mb-3"> <!-- thinh -->
    <b>Bạn nên hỗ trợ:</b>
    <ul>
      <li>Tạo mã giảm giá (tên mã, loại, giá trị, điều kiện)</li>
      <li>Sửa mã (chỉnh % giảm, hạn sử dụng, giới hạn)</li>
      <li>Xóa mã</li>
      <li>Danh sách mã kèm trạng thái (đã dùng, hết hạn, đang hoạt động)</li>
    </ul>
  </div>
</div> 