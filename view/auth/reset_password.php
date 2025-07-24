<?php include 'view/layout/header.php'; ?>
<div class="container" style="max-width:400px;margin:40px auto;">
    <h2 class="mb-3 text-primary">Đặt lại mật khẩu mới</h2>
    <form method="post" action="index.php?controller=auth&action=save_new_password">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? $_POST['token'] ?? ''); ?>">
        <div class="mb-3">
            <label for="new_password" class="form-label">Mật khẩu mới:</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Cập nhật mật khẩu</button>
    </form>
</div>
<?php include 'view/layout/footer.php'; ?> 