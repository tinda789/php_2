<!-- Bootstrap 5 & FontAwesome CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top shadow">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="index.php?controller=admin&action=dashboard">
      <i class="fa-solid fa-gauge-high me-2"></i>AdminPanel
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="adminNavbar">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="index.php">
            <i class="fa-solid fa-house me-1"></i> Trang chủ
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link<?php if (($action ?? '') === 'dashboard') echo ' active'; ?>" href="index.php?controller=admin&action=dashboard">
            <i class="fa-solid fa-gauge-high me-1"></i> Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link<?php if (strpos($action ?? '', 'category') !== false) echo ' active'; ?>" href="index.php?controller=admin&action=category_index">
            <i class="fa-solid fa-list me-1"></i> Danh mục
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link<?php if (strpos($action ?? '', 'product') !== false) echo ' active'; ?>" href="index.php?controller=admin&action=product_index">
            <i class="fa-solid fa-boxes-stacked me-1"></i> Sản phẩm
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link<?php if (strpos($action ?? '', 'user') !== false) echo ' active'; ?>" href="index.php?controller=admin&action=manage_user">
            <i class="fa-solid fa-users me-1"></i> Người dùng
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link<?php if (strpos($action ?? '', 'order') !== false) echo ' active'; ?>" href="index.php?controller=admin&action=order_manage">
            <i class="fa-solid fa-file-invoice-dollar me-1"></i> Đơn hàng
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link<?php if (strpos($action ?? '', 'coupon') !== false) echo ' active'; ?>" href="index.php?controller=admin&action=coupon_manage">
            <i class="fa-solid fa-ticket me-1"></i> Mã giảm giá
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link<?php if (($controller ?? '') === 'banner') echo ' active'; ?>" href="index.php?controller=banner&action=index">
            <i class="fa-solid fa-image me-1"></i> Banner
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link<?php if (($controller ?? '') === 'news') echo ' active'; ?>" href="index.php?controller=news&action=index">
            <i class="fa-solid fa-newspaper me-1"></i> Quản lý tin tức
          </a>
        </li>
      </ul>
      <div class="d-flex align-items-center ms-auto">
        <span class="text-white me-3">
          <i class="fa-solid fa-user-circle me-1"></i>
          <?php echo $_SESSION['user']['username'] ?? 'admin'; ?>
          (<?php echo $_SESSION['user']['role_name'] ?? 'admin'; ?>)
        </span>
        <form method="post" action="index.php?controller=auth&action=logout" class="d-inline">
          <button class="btn btn-outline-light btn-sm"><i class="fa-solid fa-sign-out-alt me-1"></i>Đăng xuất</button>
        </form>
      </div>
    </div>
  </div>
</nav>
<div style="height:56px;"></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> 