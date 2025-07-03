<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Shop Điện Tử</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6fb; margin: 0; padding: 0; }
        .header { background: linear-gradient(90deg, #007bff 60%, #00c6ff 100%); color: #fff; padding: 0; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.04); /* position: sticky; top: 0; z-index: 100; */ }
        .header-inner { display: flex; align-items: center; justify-content: space-between; max-width: 1200px; margin: 0 auto; padding: 0 32px; height: 70px; }
        .logo { display: flex; align-items: center; gap: 10px; }
        .logo-icon { font-size: 2.1rem; }
        .logo-text { font-size: 1.5rem; font-weight: bold; letter-spacing: 2px; }
        .menu-bar {
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 32px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }
        .menu { display: flex; gap: 20px; }
        .menu a { color: #007bff; text-decoration: none; font-weight: 500; padding: 18px 0; transition: color 0.2s; position: relative; }
        .menu a:hover { color: #0056b3; }
        .auth-btns { display: flex; gap: 12px; align-items: center; }
        .auth-btns a, .auth-btns form { display: inline-block; margin: 0; }
        .auth-btns a, .auth-btns button { padding: 8px 22px; border-radius: 20px; font-weight: bold; text-decoration: none; border: 1.5px solid #007bff; background: #fff; color: #007bff; transition: background 0.2s, color 0.2s; cursor: pointer; }
        .auth-btns a.login { background: #007bff; color: #fff; border: 1.5px solid #007bff; }
        .auth-btns a:hover, .auth-btns button:hover { background: #007bff; color: #fff; }
        .auth-btns .user-info { color: #333; font-weight: 500; margin-right: 8px; }
        .container { max-width: 1200px; margin: 36px auto; background: #fff; padding: 36px 36px 30px 36px; border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); }
        h1 { margin-top: 0; }
        footer { background: #222; color: #fff; text-align: center; padding: 16px 0; margin-top: 40px; letter-spacing: 1px; font-size: 1rem; }
        @media (max-width: 900px) {
            .header-inner, .menu-bar, .container { padding: 0 10px; }
        }
        @media (max-width: 700px) {
            .header-inner { flex-direction: column; height: auto; gap: 8px; }
            .container { padding: 16px; }
            .menu-bar { flex-direction: column; gap: 8px; padding: 0 10px; }
        }
        .header-fixed {
            /* position: fixed; */
            /* top: 0; */
            /* left: 0; */
            /* width: 100vw; */
            z-index: 1000;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .header-spacer {
            height: 72px; /* hoặc chiều cao thực tế của header */
        }
        .menu-bar-spacer {
            height: 64px; /* hoặc chiều cao thực tế của menu-bar */
        }
    </style>
</head>
<body>
<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
    <div class="menu-bar">
        <nav class="menu">
            <a href="index.php">Trang chủ</a>
            <a href="#">Sản phẩm</a>
            <a href="#">Giới thiệu</a>
            <a href="#">Liên hệ</a>
        </nav>
        <div class="auth-btns">
            <?php if (!empty($_SESSION['user'])): ?>
                <?php if (in_array($_SESSION['user']['role_name'] ?? '', ['admin', 'super_admin'])): ?>
                    <a href="?controller=admin" class="profile-link" style="margin-right:8px;background:#007bff;color:#fff;padding:8px 18px;border-radius:18px;">Dashboard</a>
                <?php endif; ?>
                <a href="?controller=user" class="profile-link" style="margin-right:8px;">Thông tin cá nhân</a>
                <span class="user-info">👤 <?php echo htmlspecialchars($_SESSION['user']['username']); ?></span>
                <form method="post" action="?controller=auth&action=logout" style="display:inline;">
                    <button type="submit">Đăng xuất</button>
                </form>
            <?php else: ?>
                <a href="?controller=auth&action=register" class="register">Đăng ký</a>
                <a href="?controller=auth&action=login" class="login">Đăng nhập</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="menu-bar-spacer"></div>
    <div class="container"> 