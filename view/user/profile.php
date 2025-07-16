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
            <div class="profile-avatar">👤</div>
            <h2 class="profile-title">Thông tin cá nhân</h2>
            <table class="profile-table">
                <tr><td>Họ tên:</td><td><?php echo htmlspecialchars(($_SESSION['user']['first_name'] ?? '') . ' ' . ($_SESSION['user']['last_name'] ?? '')); ?></td></tr>
                <tr><td>Tên đăng nhập:</td><td><?php echo htmlspecialchars($_SESSION['user']['username'] ?? ''); ?></td></tr>
                <tr><td>Email:</td><td><?php echo htmlspecialchars($_SESSION['user']['email'] ?? ''); ?></td></tr>
                <tr><td>Số điện thoại:</td><td><?php echo htmlspecialchars($_SESSION['user']['phone'] ?? ''); ?></td></tr>
                <tr><td>Vai trò:</td><td><?php echo htmlspecialchars($_SESSION['user']['role_name'] ?? 'customer'); ?></td></tr>
            </table>
            <a href="?controller=user&action=edit" class="profile-edit-btn">Sửa thông tin</a>
            <a href="?controller=user&action=change_password" class="profile-edit-btn" style="background:#fff;color:#007bff;border:2px solid #007bff;margin-top:10px;">Đổi mật khẩu</a>
        </div>
    </div>
    <style>
    .profile-section { display: flex; justify-content: center; align-items: flex-start; min-height: 60vh; background: #f4f6fb; }
    .profile-card { background: #fff; border-radius: 14px; box-shadow: 0 2px 16px rgba(0,0,0,0.08); padding: 38px 36px 32px 36px; max-width: 420px; width: 100%; margin: 48px 0; text-align: center; }
    .profile-avatar { font-size: 3.2rem; margin-bottom: 12px; }
    .profile-title { color: #007bff; margin-bottom: 24px; font-size: 1.5rem; }
    .profile-table { width: 100%; margin-bottom: 18px; font-size: 1.08rem; }
    .profile-table td { padding: 8px 0; text-align: left; }
    .profile-table tr td:first-child { color: #555; width: 120px; font-weight: 500; }
    .profile-edit-btn { display: inline-block; background: #007bff; color: #fff; border-radius: 22px; padding: 10px 32px; font-weight: bold; text-decoration: none; font-size: 1.08rem; transition: background 0.2s; }
    .profile-edit-btn:hover { background: #0056b3; }
    .profile-error { color: #d32f2f; background: #ffeaea; border: 1px solid #ffcdd2; border-radius: 8px; padding: 16px; font-size: 1.1rem; }
    @media (max-width: 600px) { .profile-card { padding: 16px 4px; } }
    </style>
<?php endif; ?>
<?php include 'view/layout/footer.php'; ?> 