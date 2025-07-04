<?php echo '<link rel="stylesheet" href="view/layout/add_user_admin.css">'; ?>

<div class="add-user-container">
    <div class="add-user-title">Cấp phát tài khoản mới</div>
    <?php if (!empty($success) && !empty($created_user)): ?>
        <div class="add-user-success">
            <b><?php echo $success; ?></b><br>
            <div>Tài khoản vừa tạo:</div>
            <div>Tên đăng nhập: <b><?php echo htmlspecialchars($created_user['username']); ?></b></div>
            <div>Email: <b><?php echo htmlspecialchars($created_user['email']); ?></b></div>
            <div>Mật khẩu mặc định: <b>shop1234567890@</b></div>
        </div>
    <?php elseif (!empty($error)): ?>
        <div class="add-user-error"><?php echo $error; ?></div>
    <?php endif; ?>
    <form class="add-user-form" method="post">
        <label>Họ</label>
        <input type="text" name="first_name" required>
        <label>Tên</label>
        <input type="text" name="last_name" required>
        <label>Email</label>
        <input type="email" name="email" required>
        <label>Số điện thoại</label>
        <input type="text" name="phone">
        <label>Tên đăng nhập</label>
        <input type="text" name="username" required>
        <label>Trạng thái</label>
        <input type="checkbox" name="is_active" value="1" checked> Hoạt động
        <label>Quyền</label>
        <select name="role">
            <?php foreach ($roles as $role): ?>
                <option value="<?php echo $role; ?>"><?php echo ucfirst($role); ?></option>
            <?php endforeach; ?>
        </select>
        <div class="form-actions">
            <button type="submit">Tạo tài khoản</button>
            <a href="index.php?controller=user&action=manage" style="background:#ff5252; color:#fff; text-decoration:none; border-radius:18px; padding:8px 22px;">Hủy</a>
        </div>
    </form>
</div> 