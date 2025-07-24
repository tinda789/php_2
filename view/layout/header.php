<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Điện Tử</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            min-width: 200px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-radius: 8px;
        }
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
        }
        .user-dropdown-toggle {
            background: none;
            border: none;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 20px;
            transition: background 0.2s;
        }
        .user-dropdown-toggle:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }
        .user-dropdown-toggle:focus {
            box-shadow: none;
        }
        .dropdown-item {
            padding: 8px 16px;
            font-size: 0.9rem;
        }
        .dropdown-item:hover {
            background: #f8f9fa;
        }
        .dropdown-divider {
            margin: 4px 0;
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
      Shop Điện Tử
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php">Trang chủ</a></li>
        <li class="nav-item">
          <a class="nav-link<?php if(isset($_GET['controller']) && $_GET['controller']==='product' && (empty($_GET['action']) || $_GET['action']==='list')) echo ' active'; ?>" href="index.php?controller=product&action=list">
            Sản phẩm
          </a>
        </li>
        <li class="nav-item"><a class="nav-link" href="index.php?controller=news&action=list">Tin tức</a></li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?controller=page&action=gioi_thieu">Giới thiệu</a>
        </li>
        <li class="nav-item"><a class="nav-link" href="index.php?controller=contact">Liên hệ</a></li>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <a href="index.php?controller=cart&action=view" class="btn btn-outline-light position-relative me-2" title="Giỏ hàng">
          <i class="fa-solid fa-cart-shopping fa-lg"></i>
          <?php 
          $cartCount = 0;
          if (!empty($_SESSION['cart_items'])) {
            foreach ($_SESSION['cart_items'] as $qty) $cartCount += $qty;
          }
          if ($cartCount > 0): ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger btn-cart-badge" style="font-size:0.85rem;"> <?php echo $cartCount; ?> </span>
          <?php endif; ?>
        </a>
        <?php if (!empty($_SESSION['user'])): ?>
          <?php if (in_array($_SESSION['user']['role_name'] ?? '', ['admin', 'super_admin'])): ?>
            <a href="?controller=admin" class="btn btn-warning btn-sm me-2"><i class="fa-solid fa-gauge"></i> Dashboard</a>
          <?php endif; ?>
          
          <!-- User Dropdown Menu -->
          <div class="dropdown">
            <button class="user-dropdown-toggle dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <?php if (!empty($_SESSION['user']['avatar'])): ?>
                <img src="<?php echo htmlspecialchars($_SESSION['user']['avatar']); ?>" alt="Avatar" class="user-avatar">
              <?php else: ?>
                <i class="fa-solid fa-user-circle fa-lg"></i>
              <?php endif; ?>
              <span><?php echo htmlspecialchars($_SESSION['user']['username']); ?></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><h6 class="dropdown-header">Tài khoản</h6></li>
              <li><a class="dropdown-item" href="?controller=user"><i class="fa-solid fa-user me-2"></i>Thông tin cá nhân</a></li>
              <li><a class="dropdown-item" href="?controller=user&action=edit"><i class="fa-solid fa-edit me-2"></i>Sửa thông tin</a></li>
              <li><a class="dropdown-item" href="?controller=user&action=change_password"><i class="fa-solid fa-key me-2"></i>Đổi mật khẩu</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="?controller=checkout&action=orderHistory"><i class="fa-solid fa-history me-2"></i>Lịch sử đơn hàng</a></li>
              <li><hr class="dropdown-divider"></li>
              <li>
          <form method="post" action="?controller=auth&action=logout" style="display:inline;">
                  <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-sign-out-alt me-2"></i>Đăng xuất</button>
          </form>
              </li>
            </ul>
          </div>
        <?php else: ?>
          <a href="?controller=auth&action=login" class="btn btn-login btn-auth">Đăng nhập</a>
          <a href="?controller=auth&action=register" class="btn btn-register btn-auth">Đăng ký</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
<div class="main-content"> 