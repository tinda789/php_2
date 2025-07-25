<?php include 'view/layout/header.php'; ?>
<style>
.forgot-form-container {
    max-width: 400px;
    margin: 40px auto;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.10);
    padding: 32px 28px 24px 28px;
}
.forgot-form-container h2 {
    font-weight: 700;
    color: #0d6efd;
    margin-bottom: 18px;
    text-align: center;
}
.forgot-form-container label {
    font-weight: 500;
    margin-bottom: 6px;
}
.forgot-form-container .form-control {
    border-radius: 8px;
    font-size: 1.08rem;
    padding: 10px 14px;
    margin-bottom: 16px;
}
.forgot-form-container button[type="submit"] {
    border-radius: 8px;
    font-weight: 600;
    font-size: 1.08rem;
    padding: 10px 0;
    background: linear-gradient(90deg, #0d6efd 60%, #00c6ff 100%);
    border: none;
    color: #fff;
    box-shadow: 0 2px 8px rgba(13,110,253,0.08);
    transition: background 0.2s;
}
.forgot-form-container button[type="submit"]:hover {
    background: linear-gradient(90deg, #0056b3 60%, #0dcaf0 100%);
}
</style>
<div class="forgot-form-container">
    <h2>Quên mật khẩu</h2>
    <form method="post" action="index.php?controller=auth&action=send_reset">
        <div class="mb-3">
            <label for="email" class="form-label">Nhập email để khôi phục mật khẩu:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Gửi link đặt lại mật khẩu</button>
    </form>
</div>
<?php include 'view/layout/footer.php'; ?> 