<?php // thinh: Form thêm/sửa mã giảm giá cho admin ?>
<div class="coupon-create-container"> <!-- thinh -->
    <h2 class="coupon-manage-title"><span style="font-size:1.3em;vertical-align:middle;">🏷️</span> <?php echo isset($is_edit) ? 'Sửa mã giảm giá' : 'Thêm mã giảm giá'; ?></h2>
    <form method="post" action="index.php?controller=admin&action=<?php echo isset($is_edit) ? 'coupon_update' : 'coupon_store'; ?>" class="coupon-form">
        <?php if (isset($is_edit)): ?>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($coupon['id']); ?>">
        <?php endif; ?>
        <label>Mã giảm giá:<input name="code" required value="<?php echo isset($coupon['code']) ? htmlspecialchars($coupon['code']) : ''; ?>"></label>
        <label>Tên chương trình:<input name="name" required value="<?php echo isset($coupon['name']) ? htmlspecialchars($coupon['name']) : ''; ?>"></label>
        <label>Mô tả:<textarea name="description" rows="2"><?php echo isset($coupon['description']) ? htmlspecialchars($coupon['description']) : ''; ?></textarea></label>
        <label>Loại:
            <select name="type">
                <option value="fixed" <?php if(isset($coupon['type']) && $coupon['type']==='fixed') echo 'selected'; ?>>Tiền mặt</option>
                <option value="percentage" <?php if(isset($coupon['type']) && $coupon['type']==='percentage') echo 'selected'; ?>>Phần trăm</option>
            </select>
        </label>
        <label>Giá trị:<input name="value" type="number" step="0.01" required value="<?php echo isset($coupon['value']) ? htmlspecialchars($coupon['value']) : ''; ?>"></label>
        <label>Đơn tối thiểu:<input name="minimum_amount" type="number" step="0.01" value="<?php echo isset($coupon['minimum_amount']) ? htmlspecialchars($coupon['minimum_amount']) : ''; ?>"></label>
        <label>Giảm tối đa:<input name="maximum_discount" type="number" step="0.01" value="<?php echo isset($coupon['maximum_discount']) ? htmlspecialchars($coupon['maximum_discount']) : ''; ?>"></label>
        <label>Giới hạn lượt dùng:<input name="usage_limit" type="number" value="<?php echo isset($coupon['usage_limit']) ? htmlspecialchars($coupon['usage_limit']) : ''; ?>"></label>
        <label>Ngày bắt đầu:<input name="start_date" type="date" required value="<?php echo isset($coupon['start_date']) ? htmlspecialchars($coupon['start_date']) : ''; ?>"></label>
        <label>Ngày kết thúc:<input name="end_date" type="date" required value="<?php echo isset($coupon['end_date']) ? htmlspecialchars($coupon['end_date']) : ''; ?>"></label>
        <label>Kích hoạt:<input type="checkbox" name="is_active" value="1" <?php echo (isset($coupon['is_active']) && $coupon['is_active']) ? 'checked' : (!isset($is_edit) ? 'checked' : ''); ?>></label>
        <label>Phương thức áp dụng: <!-- thinh -->
            <select name="payment_method">
                <option value="all" <?php if(isset($coupon['payment_method']) && $coupon['payment_method']==='all') echo 'selected'; ?>>Tất cả</option>
                <option value="cod" <?php if(isset($coupon['payment_method']) && $coupon['payment_method']==='cod') echo 'selected'; ?>>Tiền mặt</option>
                <option value="bank" <?php if(isset($coupon['payment_method']) && $coupon['payment_method']==='bank') echo 'selected'; ?>>Chuyển khoản</option>
                <option value="online" <?php if(isset($coupon['payment_method']) && $coupon['payment_method']==='online') echo 'selected'; ?>>Thanh toán online</option>
            </select>
        </label>
        <button type="submit" class="btn-save-coupon"><?php echo isset($is_edit) ? 'Cập nhật' : 'Lưu mã giảm giá'; ?></button>
        <a href="index.php?controller=admin&action=coupon_manage" class="btn-cancel-coupon">Hủy</a>
    </form>
</div>
<style>
.coupon-create-container { max-width: 520px; margin: 40px auto; background: #23272f; border-radius: 16px; box-shadow: 0 2px 16px rgba(0,0,0,0.13); padding: 36px 32px 32px 32px; color: #f1f1f1; }
.coupon-form { display: flex; flex-direction: column; gap: 16px; }
.coupon-form label { display: flex; flex-direction: column; font-weight: 500; color: #4fc3f7; }
.coupon-form input, .coupon-form textarea, .coupon-form select { margin-top: 6px; border-radius: 8px; border: 1px solid #353b48; background: #2d333b; color: #f1f1f1; padding: 8px 12px; font-size: 1rem; }
.coupon-form textarea { resize: vertical; }
.btn-save-coupon { background: #00e676; color: #23272f; border: none; border-radius: 18px; padding: 10px 0; font-size: 16px; font-weight: 600; cursor: pointer; margin-top: 10px; }
.btn-save-coupon:hover { background: #00b248; color: #fff; }
.btn-cancel-coupon { background: #ff5252; color: #fff; border: none; border-radius: 18px; padding: 10px 0; font-size: 16px; font-weight: 600; cursor: pointer; margin-top: 10px; text-align: center; text-decoration: none; display: block; }
.btn-cancel-coupon:hover { background: #c62828; color: #fff; }
</style> 