<?php include 'view/layout/header.php'; ?>
<div class="container" style="max-width:400px;margin:40px auto;">
    <h2 class="mb-3 text-primary">Quên mật khẩu</h2>
    <form method="post" action="index.php?controller=auth&action=send_reset_otp">
        <div class="mb-3">
            <label for="email" class="form-label">Nhập email để khôi phục mật khẩu:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Gửi mã OTP</button>
    </form>
</div>
<?php include 'view/layout/footer.php'; ?> 