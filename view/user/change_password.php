<?php include 'view/layout/header.php'; ?>
<?php if (empty($_SESSION['user'])): ?>
    <div class="profile-section">
        <div class="profile-card">
            <div class="profile-error">Bạn chưa đăng nhập!</div>
        </div>
    </div>
<?php else: ?>
    <div class="profile-section">
        <div class="profile-card">
            <div class="profile-avatar">🔒</div>
            <h2 class="profile-title">Đổi mật khẩu</h2>
            <?php if (!empty($error)) echo "<div class='profile-error'>$error</div>"; ?>
            <?php if (!empty($success)) echo "<div class='profile-success'>$success</div>"; ?>
            <form method="post" action="?controller=user&action=change_password" class="profile-form">
                <label for="old_password">Mật khẩu hiện tại</label>
                <input type="password" name="old_password" id="old_password" required placeholder="Nhập mật khẩu hiện tại...">
                <label for="new_password">Mật khẩu mới</label>
                <input type="password" name="new_password" id="new_password" required placeholder="Nhập mật khẩu mới...">
                <label for="confirm_password">Xác nhận mật khẩu mới</label>
                <input type="password" name="confirm_password" id="confirm_password" required placeholder="Nhập lại mật khẩu mới...">
                <button type="submit" class="profile-edit-btn">Đổi mật khẩu</button>
            </form>
            <a href="?controller=user" class="profile-back-link">Quay lại thông tin cá nhân</a>
        </div>
    </div>
    <style>
    .profile-section { display: flex; justify-content: center; align-items: flex-start; min-height: 60vh; background: #f4f6fb; }
    .profile-card { background: #fff; border-radius: 14px; box-shadow: 0 2px 16px rgba(0,0,0,0.08); padding: 38px 36px 32px 36px; max-width: 420px; width: 100%; margin: 48px 0; text-align: center; }
    .profile-avatar { font-size: 3.2rem; margin-bottom: 12px; }
    .profile-title { color: #007bff; margin-bottom: 24px; font-size: 1.5rem; }
    .profile-form { text-align: left; }
    .profile-form label { display: block; margin-bottom: 6px; font-weight: 500; }
    .profile-form input[type="password"] { width: 100%; padding: 10px 12px; margin-bottom: 18px; border: 1.5px solid #cfd8dc; border-radius: 6px; font-size: 1rem; transition: border 0.2s; }
    .profile-form input:focus { border: 1.5px solid #007bff; outline: none; }
    .profile-edit-btn { width: 100%; padding: 12px 0; background: #007bff; color: #fff; border: none; border-radius: 6px; font-size: 1.1rem; font-weight: bold; cursor: pointer; transition: background 0.2s; margin-bottom: 10px; }
    .profile-edit-btn:hover { background: #0056b3; }
    .profile-back-link { display: block; text-align: center; margin-top: 18px; color: #007bff; text-decoration: none; }
    .profile-error { background: #ffeaea; color: #d32f2f; border: 1px solid #ffcdd2; border-radius: 6px; padding: 10px 14px; margin-bottom: 18px; text-align: center; }
    .profile-success { background: #e6f7ff; color: #007bff; border: 1.5px solid #90caf9; border-radius: 6px; padding: 10px 14px; margin-bottom: 18px; text-align: center; }
    @media (max-width: 600px) { .profile-card { padding: 16px 4px; } }
    </style>
<?php endif; ?>
<?php include 'view/layout/footer.php'; ?> 