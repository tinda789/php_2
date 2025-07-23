<?php
// view/admin/cart_manage.php
?>
<h2>Danh sách giỏ hàng</h2>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>User</th>
        <th>Ngày tạo</th>
        <th>Hành động</th>
    </tr>
    <?php foreach ($carts as $cart): ?>
    <tr>
        <td><?= $cart['id'] ?></td>
        <td><?= htmlspecialchars($cart['username']) ?></td>
        <td><?= $cart['created_at'] ?></td>
        <td>
            <a href="index.php?controller=admin&action=cart_detail&id=<?= $cart['id'] ?>">Xem chi tiết</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table> 