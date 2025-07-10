<?php // thinh: Quản lý đơn hàng - cập nhật cho đúng cấu trúc bảng orders mới
/*
    Biến $orders là mảng các đơn hàng, mỗi đơn có các trường:
    id, order_number, user_id, order_date, total_amount, status
*/
?>
<?php if (!empty($_SESSION['order_message'])): ?>
    <div class="alert-success"> <!-- thinh -->
        <?php echo $_SESSION['order_message']; unset($_SESSION['order_message']); ?>
    </div>
<?php endif; ?>
<div class="order-manage-container"> <!-- thinh -->
    <h2 class="order-manage-title"> <!-- thinh -->
        <span style="font-size:1.3em;vertical-align:middle;"></span> Quản lý đơn hàng
    </h2>
    <table class="order-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mã đơn</th>
                <th>User ID</th>
                <th>Tên hàng</th> <!-- thinh -->
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td><?php echo $order['id']; ?></td>
                <td><?php echo htmlspecialchars($order['order_number']); ?></td>
                <td><?php echo $order['user_id']; ?></td>
                <td><?php echo isset($order['product_names']) ? htmlspecialchars($order['product_names']) : ''; ?></td> <!-- thinh -->
                <td><?php echo $order['order_date']; ?></td>
                <td><?php echo number_format($order['total_amount']); ?> đ</td>
                <td>
                    <form method="post" action="index.php?controller=admin&action=order_update_status" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                        <select name="status"> <!-- thinh -->
                            <option value="pending" <?php if($order['status']=='pending') echo 'selected'; ?>>Chờ xác nhận</option>
                            <option value="confirmed" <?php if($order['status']=='confirmed') echo 'selected'; ?>>Đã xác nhận</option>
                            <option value="processing" <?php if($order['status']=='processing') echo 'selected'; ?>>Đang xử lý</option>
                            <option value="shipped" <?php if($order['status']=='shipped') echo 'selected'; ?>>Đã gửi hàng</option>
                            <option value="delivered" <?php if($order['status']=='delivered') echo 'selected'; ?>>Đã giao</option>
                            <option value="cancelled" <?php if($order['status']=='cancelled') echo 'selected'; ?>>Đã hủy</option>
                            <option value="refunded" <?php if($order['status']=='refunded') echo 'selected'; ?>>Đã hoàn tiền</option>
                        </select>
                       
                    </form>
                </td>
                <td>
                    <form method="post" action="index.php?controller=admin&action=order_confirm" style="display:inline;"> <!-- thinh -->
                        <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                        <button type="submit" class="btn-confirm">Xác nhận</button>
                    </form>
                    <a href="index.php?controller=admin&action=order_cancel&id=<?php echo $order['id']; ?>" class="btn-cancel" onclick="return confirm('Bạn có chắc muốn hủy đơn này?')">Hủy</a> <!-- thinh -->
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<style> /* thinh */
.order-manage-container { max-width: 1100px; margin: 40px auto; background: #23272f; border-radius: 16px; box-shadow: 0 2px 16px rgba(0,0,0,0.13); padding: 36px 32px 32px 32px; color: #f1f1f1; }
.order-manage-title { /* thinh */
    font-size: 2.2rem;
    color: #4fc3f7;
    font-weight: 600;
    margin-bottom: 28px;
    text-align: center;
    letter-spacing: 0.5px;
}
.order-table { width: 100%; border-collapse: collapse; background: #353b48; border-radius: 10px; overflow: hidden; margin-bottom: 24px; }
.order-table th, .order-table td { padding: 14px 12px; text-align: left; }
.order-table th { background: #23272f; color: #4fc3f7; font-weight: 500; border-bottom: 2px solid #4fc3f7; }
.order-table tr { border-bottom: 1px solid #2c313a; }
.order-table tr:last-child { border-bottom: none; }
.btn-confirm { background: #00e676; color: #23272f; border: none; border-radius: 18px; padding: 8px 22px; font-size: 15px; font-weight: 500; cursor: pointer; margin-right: 8px; text-decoration: none; }
.btn-cancel { background: #ff5252; color: #fff; border: none; border-radius: 18px; padding: 8px 22px; font-size: 15px; font-weight: 500; cursor: pointer; text-decoration: none; }
.btn-confirm:hover { background: #00b248; color: #fff; }
.btn-cancel:hover { background: #c62828; color: #fff; }
select { border-radius: 8px; padding: 6px 12px; }
.alert-success { /* thinh */
    background: #00e676;
    color: #23272f;
    padding: 12px 20px;
    border-radius: 8px;
    margin-bottom: 18px;
    font-weight: 500;
    text-align: center;
}
.btn-update-status { /* thinh */
    background: #4fc3f7;
    color: #23272f;
    border: none;
    border-radius: 8px;
    padding: 6px 14px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    margin-left: 6px;
}
.btn-update-status:hover { background: #0288d1; color: #fff; }
</style> 