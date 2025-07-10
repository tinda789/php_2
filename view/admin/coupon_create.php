<?php // thinh: Form thêm mã giảm giá cho admin ?>
<div class="coupon-create-container"> <!-- thinh -->
    <h2 class="coupon-manage-title"><span style="font-size:1.3em;vertical-align:middle;">🏷️</span> Thêm mã giảm giá</h2>
    <form method="post" action="index.php?controller=admin&action=coupon_store" class="coupon-form">
        <label>Mã giảm giá:<input name="code" required></label>
        <label>Tên chương trình:<input name="name" required></label>
        <label>Mô tả:<textarea name="description" rows="2"></textarea></label>
        <label>Loại:
            <select name="type">
                <option value="fixed">Tiền mặt</option>
                <option value="percentage">Phần trăm</option>
            </select>
        </label>
        <label>Giá trị:<input name="value" type="number" step="0.01" required></label>
        <label>Đơn tối thiểu:<input name="minimum_amount" type="number" step="0.01"></label>
        <label>Giảm tối đa:<input name="maximum_discount" type="number" step="0.01"></label>
        <label>Giới hạn lượt dùng:<input name="usage_limit" type="number"></label>
        <label>Ngày bắt đầu:<input name="start_date" type="date" required></label>
        <label>Ngày kết thúc:<input name="end_date" type="date" required></label>
        <label>Kích hoạt:<input type="checkbox" name="is_active" value="1" checked></label>
        <label>Phương thức áp dụng: <!-- thinh -->
            <select name="payment_method">
                <option value="all">Tất cả</option>
                <option value="cod">Tiền mặt</option>
                <option value="bank">Chuyển khoản</option>
                <option value="online">Thanh toán online</option>
            </select>
        </label>
        <button type="submit" class="btn-save-coupon">Lưu mã giảm giá</button>
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