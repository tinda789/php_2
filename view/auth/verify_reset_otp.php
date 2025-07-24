<?php include 'view/layout/header.php'; ?>
<div class="container" style="max-width:400px;margin:40px auto;">
    <h2 class="mb-3 text-primary">Nhập mã OTP</h2>
    <form method="post" action="index.php?controller=auth&action=verify_reset_otp">
        <div class="mb-3">
            <label for="otp" class="form-label">Nhập mã OTP vừa nhận qua email:</label>
            <input type="text" class="form-control" id="otp" name="otp" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Xác thực</button>
    </form>
</div>
<?php include 'view/layout/footer.php'; ?> 