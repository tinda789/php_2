<?php
// view/admin/cart_detail.php
?>
<h2>Chi tiết giỏ hàng #<?= $_GET['id'] ?></h2>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Sản phẩm</th>
        <th>Số lượng</th>
        <th>Giá</th>
    </tr>
    <?php foreach ($cart_items as $item): ?>
    <tr>
        <td><?= htmlspecialchars($item['product_name']) ?></td>
        <td><?= $item['quantity'] ?></td>
        <td><?= number_format($item['price']) ?>đ</td>
    </tr>
    <?php endforeach; ?>
</table>
<a href="index.php?controller=admin&action=cart_manage">Quay lại danh sách</a> 