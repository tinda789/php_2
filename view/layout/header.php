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
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
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
      <i class="fa-solid fa-bolt"></i> Shop Điện Tử
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php">Trang chủ</a></li>
        <li class="nav-item">
          <a class="nav-link<?php if(isset($_GET['controller']) && $_GET['controller']==='product' && (empty($_GET['action']) || $_GET['action']==='list')) echo ' active'; ?>" href="index.php?controller=product&action=list">
            <i class="fa fa-box"></i> Sản phẩm
          </a>
        </li>
        <li class="nav-item"><a class="nav-link" href="index.php?controller=news&action=list">Tin tức</a></li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?controller=page&action=gioi_thieu">Giới thiệu</a>
        </li>
        <li class="nav-item"><a class="nav-link" href="#">Liên hệ</a></li>
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
          <div class="dropdown ms-2">
            <button class="user-dropdown-toggle dropdown-toggle d-flex align-items-center gap-2 bg-transparent border-0 text-white" 
                    type="button" 
                    data-bs-toggle="dropdown" 
                    aria-expanded="false"
                    style="padding: 0.5rem 1rem; border-radius: 50px; transition: all 0.3s ease;">
              <?php if (!empty($_SESSION['user']['avatar'])): ?>
                <img src="<?php echo htmlspecialchars($_SESSION['user']['avatar']); ?>" 
                     alt="Avatar" 
                     class="user-avatar rounded-circle" 
                     style="width: 32px; height: 32px; object-fit: cover;">
              <?php else: ?>
                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" 
                     style="width: 32px; height: 32px;">
                  <i class="fa-solid fa-user text-dark"></i>
                </div>
              <?php endif; ?>
              <span class="d-none d-md-inline"><?php echo htmlspecialchars($_SESSION['user']['username']); ?></span>
              <i class="fa-solid fa-chevron-down ms-1" style="font-size: 0.8em;"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="min-width: 220px; border: none; border-radius: 10px; overflow: hidden;">
              <li><h6 class="dropdown-header fw-bold text-uppercase small">Tài khoản</h6></li>
              <li>
                <a class="dropdown-item d-flex align-items-center py-2" 
                   href="?controller=user"
                   style="transition: all 0.2s ease;">
                  <i class="fa-solid fa-user me-2 text-primary"></i>
                  <span>Thông tin cá nhân</span>
                </a>
              </li>
              <li>
                <a class="dropdown-item d-flex align-items-center py-2" 
                   href="?controller=user&action=edit"
                   style="transition: all 0.2s ease;">
                  <i class="fa-solid fa-edit me-2 text-primary"></i>
                  <span>Sửa thông tin</span>
                </a>
              </li>
              <li>
                <a class="dropdown-item d-flex align-items-center py-2" 
                   href="?controller=user&action=change_password"
                   style="transition: all 0.2s ease;">
                  <i class="fa-solid fa-key me-2 text-primary"></i>
                  <span>Đổi mật khẩu</span>
                </a>
              </li>
              <li><hr class="dropdown-divider my-1"></li>
              <li>
                <a class="dropdown-item d-flex align-items-center py-2" 
                   href="?controller=checkout&action=orderHistory"
                   style="transition: all 0.2s ease;">
                  <i class="fa-solid fa-history me-2 text-primary"></i>
                  <span>Lịch sử đơn hàng</span>
                </a>
              </li>
              <li><hr class="dropdown-divider my-1"></li>
              <li>
                <form method="post" action="?controller=auth&action=logout" class="w-100">
                  <button type="submit" 
                          class="dropdown-item d-flex align-items-center py-2 w-100 text-start"
                          style="transition: all 0.2s ease; background: none; border: none;">
                    <i class="fa-solid fa-sign-out-alt me-2 text-danger"></i>
                    <span class="text-danger">Đăng xuất</span>
                  </button>
                </form>
              </li>
            </ul>
          </div>
          <style>
            .user-dropdown-toggle:hover {
              background-color: rgba(255, 255, 255, 0.15) !important;
            }
            .dropdown-item {
              font-size: 0.9rem;
              padding: 0.5rem 1rem;
              border-radius: 6px;
              margin: 0.15rem 0.5rem;
              width: auto;
            }
            .dropdown-item:hover {
              background-color: #f8f9fa;
              transform: translateX(3px);
            }
            .dropdown-menu {
              border: 1px solid rgba(0,0,0,0.05);
              box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            }
            .dropdown-header {
              font-size: 0.7rem;
              padding: 0.5rem 1rem 0.25rem;
              color: #6c757d;
              text-transform: uppercase;
              letter-spacing: 0.5px;
            }
          </style>
        <?php else: ?>
          <a href="?controller=auth&action=login" class="btn btn-login btn-auth">Đăng nhập</a>
          <a href="?controller=auth&action=register" class="btn btn-register btn-auth">Đăng ký</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
<div class="container mt-4 main-content"> 