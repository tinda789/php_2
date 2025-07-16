<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Điện Tử</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6fb; margin: 0; padding: 0; }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            letter-spacing: 2px;
            color: #007bff !important;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .navbar {
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            background: linear-gradient(90deg, #007bff 60%, #00c6ff 100%);
        }
        .navbar-nav .nav-link {
            color: #fff !important;
            font-weight: 500;
            font-size: 1.08rem;
            margin: 0 6px;
            transition: color 0.2s;
        }
        .navbar-nav .nav-link.active, .navbar-nav .nav-link:hover {
            color: #ffe082 !important;
        }
        .navbar-toggler {
            border: none;
            font-size: 1.5rem;
        }
        .navbar .dropdown-menu {
            min-width: 180px;
        }
        .user-info {
            color: #fff;
            font-weight: 500;
            margin-right: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .btn-auth {
            border-radius: 22px;
            font-weight: bold;
            padding: 8px 22px;
            font-size: 1.08rem;
            margin-left: 6px;
        }
        .btn-login {
            background: #fff;
            color: #007bff;
            border: 1.5px solid #007bff;
        }
        .btn-login:hover {
            background: #e3f2fd;
            color: #0056b3;
        }
        .btn-register {
            background: #007bff;
            color: #fff;
            border: 1.5px solid #fff;
        }
        .btn-register:hover {
            background: #0056b3;
            color: #fff;
        }
        .container { max-width: 1200px; margin: 36px auto; background: #fff; padding: 36px 36px 30px 36px; border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); }
        @media (max-width: 900px) { .container { padding: 16px; } }
        @media (max-width: 700px) { .container { padding: 8px; } }
    </style>
</head>
<body>
<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
  <div class="container-fluid px-3">
    <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
      <i class="fa-solid fa-bolt"></i> Shop Điện Tử
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php">Trang chủ</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?controller=product&action=index">Sản phẩm</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?controller=news&action=list">Tin tức</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Giới thiệu</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Liên hệ</a></li>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <?php if (!empty($_SESSION['user'])): ?>
          <?php if (in_array($_SESSION['user']['role_name'] ?? '', ['admin', 'super_admin'])): ?>
            <a href="?controller=admin" class="btn btn-warning btn-sm me-2"><i class="fa-solid fa-gauge"></i> Dashboard</a>
          <?php endif; ?>
          <a href="?controller=user" class="btn btn-outline-light btn-sm me-2"><i class="fa-solid fa-user"></i> Thông tin cá nhân</a>
          <span class="user-info"><i class="fa-solid fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['user']['username']); ?></span>
          <form method="post" action="?controller=auth&action=logout" style="display:inline;">
            <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-sign-out-alt"></i> Đăng xuất</button>
          </form>
        <?php else: ?>
          <a href="?controller=auth&action=login" class="btn btn-login btn-auth">Đăng nhập</a>
          <a href="?controller=auth&action=register" class="btn btn-register btn-auth">Đăng ký</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
<!-- <div class="container mt-4"> --> 