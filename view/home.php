<?php
require_once 'config/config.php';
require_once 'model/Product.php';
require_once 'model/Banner.php';
$products = Product::getAll($conn, 6);
foreach ($products as &$p) {
    $p['images'] = Product::getImages($conn, $p['id']);
}
unset($p);
$bannerModel = new Banner($conn);
$banners = $bannerModel->getBannersByPosition('home', 1);
$banner = !empty($banners) ? $banners[0] : null;
?>
<?php include 'view/layout/header.php'; ?>
<?php if (!empty($_GET['msg'])): ?>
    <div style="background:#e6f7ff;color:#007bff;border:1.5px solid #90caf9;padding:12px 18px;margin-bottom:18px;border-radius:6px;text-align:center;font-weight:500;">
        <?php echo htmlspecialchars($_GET['msg']); ?>
    </div>
<?php endif; ?>
<!-- Banner/Intro -->
<div class="home-banner">
    <?php if ($banner && !empty($banner['image'])): ?>
        <img src="uploads/banners/<?php echo htmlspecialchars($banner['image']); ?>" alt="Banner" style="width:100%;max-height:220px;object-fit:cover;border-radius:12px;">
    <?php else: ?>
        <div class="banner-content">
            <h1>Chào mừng bạn đến với <span style="color:#fff;">Shop Điện Tử</span>!</h1>
            <p id="typing-slogan"></p>
        </div>
    <?php endif; ?>
    <!-- thanhdat: sóng động SVG -->
    <div class="wave-container">
        <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:100%;height:60px;display:block;"><path id="wavePath" fill="#f4f6fb" d="M0,30 C360,90 1080,-30 1440,30 L1440,60 L0,60 Z"></path></svg>
    </div>
</div>
<!-- Sản phẩm nổi bật -->
<?php if (!empty($products)): ?>
    <section class="home-section">
        <h2 class="section-title">Sản phẩm nổi bật</h2>
        <div class="product-list">
            <?php foreach ($products as $p): ?>
                <div class="product-card">
                    <div class="product-img slideshow-container" id="slideshow-<?php echo $p['id']; ?>">
                        <?php if (!empty($p['images'])): ?>
                            <?php foreach ($p['images'] as $idx => $img): ?>
                                <img src="<?php echo htmlspecialchars($img); ?>" class="slide-img" style="display:<?php echo $idx === 0 ? 'block' : 'none'; ?>;">
                            <?php endforeach; ?>
                            <?php if (count($p['images']) > 1): ?>
                                <button class="prev" onclick="plusSlides(-1, <?php echo $p['id']; ?>)">&#10094;</button>
                                <button class="next" onclick="plusSlides(1, <?php echo $p['id']; ?>)">&#10095;</button>
                            <?php endif; ?>
                        <?php else: ?>
                            <img src="<?php echo htmlspecialchars($p['image'] ?? 'https://via.placeholder.com/150'); ?>" class="slide-img" style="display:block;">
                        <?php endif; ?>
                    </div>
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($p['name']); ?></h3>
                        <div class="product-price"><?php echo number_format($p['price']); ?> đ</div>
                        <div class="product-desc desc-limit"><?php echo htmlspecialchars($p['description']); ?></div>
                        <a href="index.php?controller=product&action=detail&id=<?php echo $p['id']; ?>" class="btn btn-sm btn-primary product-cta">Xem chi tiết</a>
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
<!-- thanhdat: nút về đầu trang -->
<button id="scrollTopBtn" title="Về đầu trang">⬆️</button>
<style>
.home-banner { background: linear-gradient(90deg, #007bff 60%, #00c6ff 100%); color: #fff; padding: 48px 0 36px 0; text-align: center; border-radius: 12px; margin-bottom: 32px; }
.banner-content h1 { font-size: 2.2rem; margin-bottom: 10px; }
.banner-content p { font-size: 1.15rem; }
.home-section { margin-bottom: 38px; }
.section-title { color: #007bff; font-size: 1.4rem; margin-bottom: 18px; text-align: left; }
.product-list { display: flex; flex-wrap: wrap; gap: 24px; margin-bottom: 10px; }
.product-card { background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); width: 250px; padding: 18px 14px; display: flex; flex-direction: column; justify-content: space-between; height: 320px; }
.product-img { text-align: center; margin-bottom: 10px; }
.product-img img { max-width: 100%; max-height: 120px; border-radius: 6px; }
.product-info h3 { margin: 0 0 8px 0; font-size: 1.15rem; color: #007bff; }
.product-price { color: #d32f2f; font-weight: bold; margin-bottom: 8px; }
.product-desc { color: #444; font-size: 0.98rem; }
.product-desc.desc-limit {
    max-height: 44px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    margin-bottom: 10px;
}
.product-cta {
    display: inline-block;
    margin-top: 4px;
    padding: 7px 18px;
    font-size: 0.98rem;
    border-radius: 16px;
    background: #007bff;
    color: #fff;
    border: none;
    font-weight: 500;
    text-decoration: none;
    transition: background 0.18s, color 0.18s;
}
.product-cta:hover {
    background: #0056b3;
    color: #fff;
}
.service-list { display: flex; gap: 32px; flex-wrap: wrap; justify-content: center; }
.service-item { background: #f4f6fb; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 22px 24px; display: flex; align-items: flex-start; gap: 16px; width: 320px; margin-bottom: 16px; }
.service-icon { font-size: 2.1rem; color: #007bff; }
@media (max-width: 900px) { .product-list, .service-list { flex-direction: column; align-items: center; } .service-item { width: 100%; } }
.slide-img {
  width: 100%;
  height: 120px;
  object-fit: cover;
  border-radius: 6px;
  background: #eee;
}
.slideshow-container { position: relative; }
.prev, .next {
  position: absolute; top: 50%; transform: translateY(-50%);
  background: rgba(0,0,0,0.3); color: #fff; border: none; border-radius: 50%;
  width: 28px; height: 28px; cursor: pointer; font-size: 1.2rem;
}
.prev { left: 4px; }
.next { right: 4px; }
.wave-container { position: relative; margin-top: -10px; }
#typing-slogan { font-size: 1.15rem; color: #fff; min-height: 28px; }
#scrollTopBtn {
  position: fixed;
  right: 28px;
  bottom: 28px;
  z-index: 9999;
  background: #007bff;
  color: #fff;
  border: none;
  border-radius: 50%;
  width: 48px;
  height: 48px;
  font-size: 2rem;
  box-shadow: 0 2px 8px rgba(0,0,0,0.18);
  cursor: pointer;
  display: none;
  align-items: center;
  justify-content: center;
  transition: background 0.2s, box-shadow 0.2s;
}
#scrollTopBtn:hover {
  background: #0056b3;
  box-shadow: 0 4px 16px rgba(0,0,0,0.22);
}
</style>
<script>
function plusSlides(n, id) {
  var slides = document.querySelectorAll('#slideshow-' + id + ' .slide-img');
  var current = Array.from(slides).findIndex(img => img.style.display === 'block');
  slides[current].style.display = 'none';
  var next = (current + n + slides.length) % slides.length;
  slides[next].style.display = 'block';
}
// thanhdat: Hiệu ứng chữ chạy (typing effect) cho slogan
const slogan = "Chuyên cung cấp các sản phẩm điện tử chất lượng cao, giá tốt, dịch vụ tận tâm.";
let i = 0;
function typeSlogan() {
  if (i <= slogan.length) {
    document.getElementById("typing-slogan").innerHTML = slogan.substring(0, i) + '<span style="border-right:2px solid #fff;">&nbsp;</span>';
    i++;
    setTimeout(typeSlogan, 32);
  } else {
    document.getElementById("typing-slogan").innerHTML = slogan;
  }
}
document.addEventListener('DOMContentLoaded', typeSlogan);
// thanhdat: Hiện/ẩn nút về đầu trang và xử lý scroll
window.addEventListener('scroll', function() {
  var btn = document.getElementById('scrollTopBtn');
  if (window.scrollY > 200) {
    btn.style.display = 'flex';
  } else {
    btn.style.display = 'none';
  }
});
document.getElementById('scrollTopBtn').onclick = function() {
  window.scrollTo({ top: 0, behavior: 'smooth' });
};
</script>
<?php include 'view/layout/footer.php'; ?> 