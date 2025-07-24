<?php include 'view/layout/header.php'; ?>
<div class="auth-form">
    <h2>Đăng nhập</h2>
    <?php if (!empty($error)) echo "<div class='auth-error'>$error</div>"; ?>
    <form method="post" action="?controller=auth&action=login">
        <label for="username">Tên đăng nhập</label>
        <input type="text" name="username" id="username" required placeholder="Nhập tên đăng nhập...">
        <label for="password">Mật khẩu</label>
        <input type="password" name="password" id="password" required placeholder="Nhập mật khẩu...">
        <button type="submit" class="auth-btn">Đăng nhập</button>
    </form>
    <p class="auth-link">Chưa có tài khoản? <a href="?controller=auth&action=register">Đăng ký</a></p>
    <p class="auth-link"><a href="?controller=auth&action=forget_password">Quên mật khẩu?</a></p>
</div>
<style>
.auth-form {
    max-width: 400px;
    margin: 40px auto 60px auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.07);
    padding: 36px 32px 28px 32px;
}
.auth-form h2 {
    color: #007bff;
    margin-bottom: 18px;
    text-align: center;
}
.auth-form label {
    display: block;
    margin-bottom: 6px;
    font-weight: 500;
}
.auth-form input[type="text"], .auth-form input[type="password"] {
    width: 100%;
    padding: 10px 12px;
    margin-bottom: 18px;
    border: 1.5px solid #cfd8dc;
    border-radius: 6px;
    font-size: 1rem;
    transition: border 0.2s;
}
.auth-form input:focus {
    border: 1.5px solid #007bff;
    outline: none;
}
.auth-btn {
    width: 100%;
    padding: 12px 0;
    background: #007bff;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 1.1rem;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.2s;
    margin-bottom: 10px;
}
.auth-btn:hover {
    background: #0056b3;
}
.auth-link {
    text-align: center;
    margin-top: 10px;
}
.auth-link a {
    color: #007bff;
    text-decoration: none;
    font-weight: 500;
}
.auth-error {
    background: #ffeaea;
    color: #d32f2f;
    border: 1px solid #ffcdd2;
    border-radius: 6px;
    padding: 10px 14px;
    margin-bottom: 18px;
    text-align: center;
}
@media (max-width: 500px) {
    .auth-form { padding: 16px 6px; }
}
</style>
<?php include 'view/layout/footer.php'; ?> 