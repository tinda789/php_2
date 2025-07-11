<?php 
if (session_status() === PHP_SESSION_NONE) session_start();
// Kiểm tra quyền admin
if (empty($_SESSION['user']) || !in_array($_SESSION['user']['role_name'] ?? '', ['admin', 'super_admin'])) {
    echo '<div style="color:red;text-align:center;margin:40px 0;">Bạn không có quyền truy cập trang này!</div>';
    exit;
}
include 'view/layout/header_admin.php'; 
?>
<main class="bg-light" style="min-height:100vh; padding-top:56px;">
  <div class="container-fluid pt-3">
    <?php if (isset($_SESSION['success'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> <?= $_SESSION['success'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i> <?= $_SESSION['error'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    <?php if (isset($view_file)) include $view_file; ?>
  </div>
</main> 