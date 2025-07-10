<?php // thinh: Quản lý mã giảm giá cho admin 
/*
    Biến $coupons là mảng các mã giảm giá, mỗi mã có các trường:
    id, code, name, type, value, minimum_amount, usage_limit, start_date, end_date, is_active, status_label
*/
?>
<div class="coupon-manage-container"> <!-- thinh -->
    <h2 class="coupon-manage-title"><span style="font-size:1.3em;vertical-align:middle;"></span> Quản lý mã giảm giá</h2>
    <a href="index.php?controller=admin&action=coupon_create" class="btn-add-coupon">+ Thêm mã giảm giá</a>
    <div class="coupon-guide"> <!-- thinh -->
        <b>Bạn nên hỗ trợ:</b>
        <ul>
            <li>Tạo mã giảm giá (tên mã, loại, giá trị, điều kiện)</li>
            <li>Sửa mã (chỉnh % giảm, hạn sử dụng, giới hạn)</li>
            <li>Xóa mã</li>
            <li>Danh sách mã kèm trạng thái (đã dùng, hết hạn, đang hoạt động)</li>
        </ul>
    </div>
    <table class="coupon-table">
        <thead>
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
                    <a href="index.php?controller=admin&action=coupon_edit&id=<?= $coupon['id'] ?>" class="btn-edit">Sửa</a>
                    <a href="index.php?controller=admin&action=coupon_delete&id=<?= $coupon['id'] ?>" class="btn-delete" onclick="return confirm('Xóa mã này?')">Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<style>
.coupon-manage-container { max-width: 1100px; margin: 40px auto; background: #23272f; border-radius: 16px; box-shadow: 0 2px 16px rgba(0,0,0,0.13); padding: 36px 32px 32px 32px; color: #f1f1f1; }
.coupon-manage-title { font-size: 2.2rem; color: #4fc3f7; font-weight: 600; margin-bottom: 28px; text-align: center; letter-spacing: 0.5px; }
.coupon-table { width: 100%; border-collapse: collapse; background: #353b48; border-radius: 10px; overflow: hidden; margin-bottom: 24px; }
.coupon-table th, .coupon-table td { padding: 14px 12px; text-align: left; }
.coupon-table th { background: #23272f; color: #4fc3f7; font-weight: 500; border-bottom: 2px solid #4fc3f7; }
.coupon-table tr { border-bottom: 1px solid #2c313a; }
.coupon-table tr:last-child { border-bottom: none; }
.btn-add-coupon { background: #00e676; color: #23272f; border: none; border-radius: 18px; padding: 8px 22px; font-size: 15px; font-weight: 500; cursor: pointer; text-decoration: none; margin-bottom: 18px; display: inline-block; }
.btn-edit { background: #4fc3f7; color: #23272f; border: none; border-radius: 8px; padding: 6px 14px; font-size: 14px; font-weight: 500; cursor: pointer; text-decoration: none; margin-right: 6px; }
.btn-delete { background: #ff5252; color: #fff; border: none; border-radius: 8px; padding: 6px 14px; font-size: 14px; font-weight: 500; cursor: pointer; text-decoration: none; }
.btn-edit:hover { background: #0288d1; color: #fff; }
.btn-delete:hover { background: #c62828; color: #fff; }
.coupon-guide { background: #2d333b; color: #ffe082; border-radius: 8px; padding: 16px 24px; margin-bottom: 18px; font-size: 1.08rem; }
.coupon-guide ul { margin: 8px 0 0 18px; }
.coupon-guide li { margin-bottom: 4px; }
</style> 