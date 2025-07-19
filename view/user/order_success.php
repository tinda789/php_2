<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<div class="order-success-container">
    <div class="order-success-icon">
        <i class="fas fa-check-circle"></i>
    </div>
    <h2>Cảm ơn bạn đã tin tưởng!</h2>
    <p class="order-success-msg">Đơn hàng của bạn đã được ghi nhận. Chúng tôi sẽ liên hệ xác nhận và giao hàng sớm nhất.</p>
    <div class="order-success-btns">
        <a href="/php_2/index.php" class="btn btn-home"><i class="fas fa-home"></i> Về trang chủ</a>
        <a href="/php_2/index.php?controller=product&action=list" class="btn btn-shop"><i class="fas fa-shopping-bag"></i> Tiếp tục mua sắm</a>
    </div>
</div>
<style>
.order-success-container {
    max-width: 420px;
    margin: 60px auto 0 auto;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 16px rgba(25,118,210,0.10);
    padding: 40px 28px 32px 28px;
    text-align: center;
}
.order-success-icon {
    font-size: 64px;
    color: #43a047;
    margin-bottom: 18px;
}
.order-success-container h2 {
    color: #1976d2;
    font-weight: 700;
    margin-bottom: 10px;
}
.order-success-msg {
    color: #444;
    font-size: 17px;
    margin-bottom: 32px;
}
.order-success-btns {
    display: flex;
    justify-content: center;
    gap: 16px;
    flex-wrap: wrap;
}
.order-success-btns .btn {
    border-radius: 8px;
    font-weight: 600;
    font-size: 17px;
    padding: 12px 28px;
    box-shadow: 0 2px 8px rgba(25,118,210,0.06);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    border: none;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
}
.btn-home {
    background: #f5f5f5;
    color: #1976d2;
}
.btn-home:hover {
    background: #e3f0ff;
    color: #1256a3;
}
.btn-shop {
    background: linear-gradient(90deg, #1976d2 0%, #00bcd4 100%);
    color: #fff;
}
.btn-shop:hover {
    background: linear-gradient(90deg, #00bcd4 0%, #1976d2 100%);
    color: #fff;
}
@media (max-width: 600px) {
    .order-success-container {
        padding: 18px 4px 18px 4px;
    }
    .order-success-btns {
        flex-direction: column;
        gap: 10px;
    }
    .order-success-btns .btn {
        width: 100%;
        justify-content: center;
    }
}
</style> 