<style>
.add-user-container {
    max-width: 500px;
    margin: 40px auto;
    background: #23272f;
    border-radius: 16px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.13);
    padding: 36px 32px 32px 32px;
    color: #f1f1f1;
}
.add-user-title {
    font-size: 1.5rem;
    color: #4fc3f7;
    font-weight: 600;
    margin-bottom: 28px;
    text-align: center;
}
.add-user-form label {
    display: block;
    margin-bottom: 6px;
    color: #b0bec5;
}
.add-user-form input, .add-user-form select {
    width: 100%;
    padding: 8px 10px;
    margin-bottom: 18px;
    border-radius: 8px;
    border: none;
    background: #353b48;
    color: #f1f1f1;
    font-size: 1rem;
}
.add-user-form input[type="checkbox"] {
    width: auto;
    margin-right: 8px;
}
.add-user-form .form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}
.add-user-form button {
    background: #00e676;
    color: #23272f;
    border: none;
    border-radius: 18px;
    padding: 8px 22px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.18s, color 0.18s;
}
.add-user-form button:hover {
    background: #00b248;
    color: #fff;
}
.add-user-success {
    background: #353b48;
    border-radius: 10px;
    padding: 18px 18px 10px 18px;
    margin-bottom: 18px;
    color: #00e676;
    font-size: 1.1rem;
    text-align: center;
}
.add-user-error {
    background: #ff5252;
    color: #fff;
    border-radius: 10px;
    padding: 12px 18px;
    margin-bottom: 18px;
    text-align: center;
}
</style>

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