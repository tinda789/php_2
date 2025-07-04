<?php echo '<link rel="stylesheet" href="view/layout/manage_user.css">'; ?>
<div class="user-manage-container">
    <div class="user-manage-title">Quản lý người dùng</div>
    <a href="index.php?controller=admin&action=add_user_admin" class="add-user-btn">+ Thêm người dùng</a>
    <table class="user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên đăng nhập</th>
                <th>Email</th>
                <th>Họ tên</th>
                <th>Số điện thoại</th>
                <th>Vai trò</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($user['phone']); ?></td>
                    <td><?php echo htmlspecialchars($user['role_name']); ?></td>
                    <td><?php echo $user['is_active'] ? 'Hoạt động' : 'Khóa'; ?></td>
                    <td class="actions">
                        <a href="index.php?controller=admin&action=edit_user_admin&id=<?php echo $user['id']; ?>" class="btn-edit">Sửa</a>
                        <a href="index.php?controller=admin&action=delete_user_admin&id=<?php echo $user['id']; ?>" class="btn-delete" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8" style="text-align:center; color:#b0bec5;">Không có người dùng nào.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div> 