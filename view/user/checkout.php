<?php
// view/user/checkout.php
require_once __DIR__ . '/../layout/header.php';
if (empty($cart)) {
    echo '<div class="container py-4"><div class="alert alert-info text-center">Không có sản phẩm nào để thanh toán.</div></div>';
    require_once __DIR__ . '/../layout/footer.php';
    return;
}
if (!empty($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>
<div class="container" style="max-width:700px;margin:40px auto;">
    <h2>Thanh toán đơn hàng</h2>
    <?php if (!empty($error)) echo '<div style="color:red">'.$error.'</div>'; ?>
    <form method="post">
        <?php if (!empty($selected_products)) foreach ($selected_products as $pid): ?>
          <input type="hidden" name="selected_products[]" value="<?= htmlspecialchars($pid) ?>">
        <?php endforeach; ?>
        <h4>Thông tin giao hàng</h4>
        <div class="form-group">
            <label>Họ tên người nhận</label>
            <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Số điện thoại</label>
            <input type="text" name="phone" class="form-control" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Địa chỉ</label>
            <input type="text" name="address" class="form-control" required value="<?= htmlspecialchars($_POST['address'] ?? '') ?>">
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Tỉnh/Thành phố</label>
                <input type="text" name="city" class="form-control" required value="<?= htmlspecialchars($_POST['city'] ?? '') ?>">
            </div>
            <div class="form-group col-md-4">
                <label>Quận/Huyện</label>
                <input type="text" name="district" class="form-control" required value="<?= htmlspecialchars($_POST['district'] ?? '') ?>">
            </div>
            <div class="form-group col-md-4">
                <label>Phường/Xã</label>
                <input type="text" name="ward" class="form-control" required value="<?= htmlspecialchars($_POST['ward'] ?? '') ?>">
            </div>
        </div>
        <div class="form-group">
            <label>Ghi chú</label>
            <textarea name="notes" class="form-control"><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>
        </div>
        <h4>Phương thức thanh toán</h4>
        <div class="form-group">
            <label><input type="radio" name="payment_method" value="vnpay"> Thanh toán qua VNPay</label>
        </div>
        <h4>Thông tin đơn hàng</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; foreach ($cart as $item): 
                    $price = $item['sale_price'] > 0 ? $item['sale_price'] : $item['price'];
                    $line_total = $price * $item['quantity'];
                    $total += $line_total;
                ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= number_format($price, 0, ",", ".") ?>₫</td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= number_format($line_total, 0, ",", ".") ?>₫</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="text-align:right">Tổng cộng:</th>
                    <th><?= number_format($total, 0, ",", ".") ?>₫</th>
                </tr>
            </tfoot>
        </table>
        <button type="submit" class="btn btn-primary">Đặt hàng & Thanh toán</button>
    </form>
</div>
<script>
// thanhdat: validate form checkout
const checkoutForm = document.querySelector('form');
checkoutForm.addEventListener('submit', function(e) {
    // Validate phương thức thanh toán
    const paymentChecked = checkoutForm.querySelector('input[name="payment_method"]:checked');
    if (!paymentChecked) {
        alert('Vui lòng chọn phương thức thanh toán!');
        e.preventDefault();
        return false;
    }
    // Validate các trường required (HTML5 đã có, nhưng thêm JS cho chắc)
    const requiredFields = checkoutForm.querySelectorAll('[required]');
    for (let field of requiredFields) {
        if (!field.value.trim()) {
            alert('Vui lòng nhập đầy đủ thông tin giao hàng!');
            field.focus();
            e.preventDefault();
            return false;
        }
    }
});
</script>
<?php require_once __DIR__ . '/../layout/footer.php'; ?> 