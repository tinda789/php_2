<?php // thinh: Form thêm/sửa mã giảm giá cho admin ?>
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-md-7">
      <div class="card shadow">
        <div class="card-header bg-primary text-white">
          <h4 class="mb-0"><?php echo isset($is_edit) ? 'Sửa mã giảm giá' : 'Thêm mã giảm giá'; ?></h4>
        </div>
        <div class="card-body">
          <form method="post" action="index.php?controller=admin&action=<?php echo isset($is_edit) ? 'coupon_update' : 'coupon_store'; ?>">
            <?php if (isset($is_edit)): ?>
              <input type="hidden" name="id" value="<?php echo htmlspecialchars($coupon['id']); ?>">
            <?php endif; ?>
            <div class="mb-3">
              <label class="form-label">Mã giảm giá</label>
              <input name="code" class="form-control" required value="<?php echo isset($coupon['code']) ? htmlspecialchars($coupon['code']) : ''; ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Tên chương trình</label>
              <input name="name" class="form-control" required value="<?php echo isset($coupon['name']) ? htmlspecialchars($coupon['name']) : ''; ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Mô tả</label>
              <textarea name="description" class="form-control" rows="2"><?php echo isset($coupon['description']) ? htmlspecialchars($coupon['description']) : ''; ?></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Loại</label>
              <select name="type" class="form-select">
                <option value="fixed" <?php if(isset($coupon['type']) && $coupon['type']==='fixed') echo 'selected'; ?>>Tiền mặt</option>
                <option value="percentage" <?php if(isset($coupon['type']) && $coupon['type']==='percentage') echo 'selected'; ?>>Phần trăm</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Giá trị</label>
              <input name="value" type="number" step="0.01" class="form-control" required value="<?php echo isset($coupon['value']) ? htmlspecialchars($coupon['value']) : ''; ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Đơn tối thiểu</label>
              <input name="minimum_amount" type="number" step="0.01" class="form-control" value="<?php echo isset($coupon['minimum_amount']) ? htmlspecialchars($coupon['minimum_amount']) : ''; ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Giảm tối đa</label>
              <input name="maximum_discount" type="number" step="0.01" class="form-control" value="<?php echo isset($coupon['maximum_discount']) ? htmlspecialchars($coupon['maximum_discount']) : ''; ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Giới hạn lượt dùng</label>
              <input name="usage_limit" type="number" class="form-control" value="<?php echo isset($coupon['usage_limit']) ? htmlspecialchars($coupon['usage_limit']) : ''; ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Ngày bắt đầu</label>
              <input name="start_date" type="date" class="form-control" required value="<?php echo isset($coupon['start_date']) ? htmlspecialchars($coupon['start_date']) : ''; ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Ngày kết thúc</label>
              <input name="end_date" type="date" class="form-control" required value="<?php echo isset($coupon['end_date']) ? htmlspecialchars($coupon['end_date']) : ''; ?>">
            </div>
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" <?php echo (isset($coupon['is_active']) && $coupon['is_active']) ? 'checked' : (!isset($is_edit) ? 'checked' : ''); ?>>
              <label class="form-check-label" for="is_active">Kích hoạt</label>
            </div>
            <div class="mb-3">
              <label class="form-label">Phương thức áp dụng</label>
              <select name="payment_method" class="form-select">
                <option value="all" <?php if(isset($coupon['payment_method']) && $coupon['payment_method']==='all') echo 'selected'; ?>>Tất cả</option>
                <option value="cod" <?php if(isset($coupon['payment_method']) && $coupon['payment_method']==='cod') echo 'selected'; ?>>Tiền mặt</option>
                <option value="bank" <?php if(isset($coupon['payment_method']) && $coupon['payment_method']==='bank') echo 'selected'; ?>>Chuyển khoản</option>
                <option value="online" <?php if(isset($coupon['payment_method']) && $coupon['payment_method']==='online') echo 'selected'; ?>>Thanh toán online</option>
              </select>
            </div>
            <div class="d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-primary flex-grow-1"><?php echo isset($is_edit) ? 'Cập nhật' : 'Lưu mã giảm giá'; ?></button>
              <a href="index.php?controller=admin&action=coupon_manage" class="btn btn-secondary flex-grow-1">Hủy</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div> 