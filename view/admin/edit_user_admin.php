<?php echo '<link rel="stylesheet" href="view/layout/edit_user_admin.css">'; ?>

<style>
.edit-user-container {
    max-width: 500px;
    margin: 40px auto;
    background: #23272f;
    border-radius: 16px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.13);
    padding: 36px 32px 32px 32px;
    color: #f1f1f1;
}
.edit-user-title {
    font-size: 1.5rem;
    color: #4fc3f7;
    font-weight: 600;
    margin-bottom: 28px;
    text-align: center;
}
.edit-user-form label {
    display: block;
    margin-bottom: 6px;
    color: #b0bec5;
}
.edit-user-form input, .edit-user-form select {
    width: 100%;
    padding: 8px 10px;
    margin-bottom: 18px;
    border-radius: 8px;
    border: none;
    background: #353b48;
    color: #f1f1f1;
    font-size: 1rem;
}
.edit-user-form input[type="checkbox"] {
    width: auto;
    margin-right: 8px;
}
.edit-user-form .form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}
.edit-user-form button {
    background: #4fc3f7;
    color: #23272f;
    border: none;
    border-radius: 18px;
    padding: 8px 22px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.18s, color 0.18s;
}
.edit-user-form button:hover {
    background: #0288d1;
    color: #fff;
}
</style>

<div class="edit-user-container">
    <div class="edit-user-title">Sửa thông tin người dùng</div>
    <form class="edit-user-form" method="post">
        <label>Họ</label>
        <input type="text" name="first_name" value="<?php echo htmlspecialchars($user_edit['first_name']); ?>" required>
        <label>Tên</label>
        <input type="text" name="last_name" value="<?php echo htmlspecialchars($user_edit['last_name']); ?>" required>
        <label>Email</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user_edit['email']); ?>" required>
        <label>Số điện thoại</label>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($user_edit['phone']); ?>">
        <label>Trạng thái</label>
        <input type="checkbox" name="is_active" value="1" <?php if ($user_edit['is_active']) echo 'checked'; ?>> Hoạt động
        <label>Quyền</label>
        <select name="role">
            <?php foreach ($roles as $role): ?>
                <option value="<?php echo $role; ?>" <?php if ($user_edit['role_name'] === $role) echo 'selected'; ?>><?php echo ucfirst($role); ?></option>
            <?php endforeach; ?>
        </select>
        <div class="form-actions">
            <button type="submit">Lưu</button>
            <a href="index.php?controller=user&action=manage" style="background:#ff5252; color:#fff; text-decoration:none; border-radius:18px; padding:8px 22px;">Hủy</a>
        </div>
    </form>
</div> 