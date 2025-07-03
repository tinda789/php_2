<?php include 'view/layout/header.php'; ?>
<?php if (!empty($_GET['msg'])): ?>
    <div style="background:#e6f7ff;color:#007bff;border:1.5px solid #90caf9;padding:12px 18px;margin-bottom:18px;border-radius:6px;text-align:center;font-weight:500;">
        <?php echo htmlspecialchars($_GET['msg']); ?>
    </div>
<?php endif; ?>
<!-- Banner/Intro -->
<div class="home-banner">
    <div class="banner-content">
        <h1>Chào mừng bạn đến với <span style="color:#007bff;">Shop Điện Tử</span>!</h1>
        <p>Chuyên cung cấp các sản phẩm điện tử chất lượng cao, giá tốt, dịch vụ tận tâm.</p>
    </div>
</div>
<!-- Sản phẩm nổi bật -->
<?php
require_once 'config/config.php';
require_once 'model/Product.php';
$products = Product::getAll($conn, 6);
?>
<?php if (!empty($products)): ?>
    <section class="home-section">
        <h2 class="section-title">Sản phẩm nổi bật</h2>
        <div class="product-list">
            <?php foreach ($products as $p): ?>
                <div class="product-card">
                    <div class="product-img">
                        <img src="<?php echo htmlspecialchars($p['image'] ?? 'https://via.placeholder.com/150'); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
                    </div>
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($p['name']); ?></h3>
                        <div class="product-price"><?php echo number_format($p['price']); ?> đ</div>
                        <div class="product-desc"><?php echo htmlspecialchars($p['description']); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>
<!-- Dịch vụ/giới thiệu -->
<section class="home-section">
    <h2 class="section-title">Dịch vụ & Ưu điểm</h2>
    <div class="service-list">
        <div class="service-item">
            <span class="service-icon">💡</span>
            <div>
                <b>Sản phẩm đa dạng</b>
                <p>Bóng đèn, công tắc, ổ cắm... và nhiều thiết bị điện tử khác.</p>
            </div>
        </div>
        <div class="service-item">
            <span class="service-icon">🚚</span>
            <div>
                <b>Giao hàng nhanh chóng</b>
                <p>Giao hàng toàn quốc, thanh toán tiện lợi.</p>
            </div>
        </div>
        <div class="service-item">
            <span class="service-icon">📞</span>
            <div>
                <b>Hỗ trợ 24/7</b>
                <p>Đội ngũ tư vấn nhiệt tình, hỗ trợ khách hàng mọi lúc.</p>
            </div>
        </div>
    </div>
</section>
<style>
.home-banner { background: linear-gradient(90deg, #007bff 60%, #00c6ff 100%); color: #fff; padding: 48px 0 36px 0; text-align: center; border-radius: 12px; margin-bottom: 32px; }
.banner-content h1 { font-size: 2.2rem; margin-bottom: 10px; }
.banner-content p { font-size: 1.15rem; }
.home-section { margin-bottom: 38px; }
.section-title { color: #007bff; font-size: 1.4rem; margin-bottom: 18px; text-align: left; }
.product-list { display: flex; flex-wrap: wrap; gap: 24px; margin-bottom: 10px; }
.product-card { background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); width: 250px; padding: 18px 14px; }
.product-img { text-align: center; margin-bottom: 10px; }
.product-img img { max-width: 100%; max-height: 120px; border-radius: 6px; }
.product-info h3 { margin: 0 0 8px 0; font-size: 1.15rem; color: #007bff; }
.product-price { color: #d32f2f; font-weight: bold; margin-bottom: 8px; }
.product-desc { color: #444; font-size: 0.98rem; }
.service-list { display: flex; gap: 32px; flex-wrap: wrap; justify-content: center; }
.service-item { background: #f4f6fb; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 22px 24px; display: flex; align-items: flex-start; gap: 16px; width: 320px; margin-bottom: 16px; }
.service-icon { font-size: 2.1rem; color: #007bff; }
@media (max-width: 900px) { .product-list, .service-list { flex-direction: column; align-items: center; } .service-item { width: 100%; } }
</style>
<?php include 'view/layout/footer.php'; ?> 