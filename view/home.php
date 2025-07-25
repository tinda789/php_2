<?php
require_once 'config/config.php';
require_once 'model/Banner.php';
require_once __DIR__ . '/../model/Product.php';
require_once __DIR__ . '/../model/Category.php';

// Lấy sản phẩm nổi bật
$products = Product::getFeaturedProducts($conn, 8);
foreach ($products as &$p) {
    $p['images'] = Product::getImages($conn, $p['id']);
}
unset($p);

// Lấy banner
$bannerModel = new Banner($conn);
$banner = $bannerModel->getActiveBanner('homepage');

// Lấy banner active cho danh mục nổi bật
$banners = $bannerModel->getAllBanners('featured_categories', 1);
?>

<?php include 'view/layout/header.php'; ?>
<!-- Add Lightbox2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">

<!-- Thông báo -->
<?php if (!empty($_GET['msg'])): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
<?php endif; ?>

<!-- Hero Section Optimized -->
<section class="hero-section py-5 py-lg-7 position-relative overflow-hidden">
    <!-- Background Gradient -->
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-primary"></div>
    
    <!-- Animated Elements -->
    <div class="position-absolute top-0 start-0 w-100 h-100">
        <div class="hero-shape-1"></div>
        <div class="hero-shape-2"></div>
    </div>
    
    <div class="container position-relative z-2">
        <div class="row align-items-center min-vh-60">
            <div class="col-lg-6 text-center text-lg-start mb-5 mb-lg-0">
                <h1 class="display-4 fw-bold mb-4 text-white">
                    <span class="text-warning">Khám phá thế giới điện tử</span> hiện đại
                </h1>
                <p class="lead text-white-70 mb-5">
                    Trải nghiệm chất lượng vượt trội với các sản phẩm công nghệ chính hãng, 
                    giá cả cạnh tranh cùng nhiều ưu đãi hấp dẫn.
                </p>
                <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                    <a href="#products" class="btn btn-warning btn-lg fw-bold px-4 py-3 rounded-pill shadow-lg">
                        <i class="fas fa-shopping-cart me-2"></i> Mua sắm ngay
                    </a>
                    <a href="http://127.0.0.1:8000/index.php?controller=product&action=list" class="btn btn-warning btn-lg fw-bold px-4 py-3 rounded-pill shadow-lg">
                        <i class="fas fa-th-large me-2"></i> Danh mục
                    </a>
                </div>
                
                <!-- Trust Badges -->
                <div class="mt-5 pt-3">
                    <div class="d-flex flex-wrap justify-content-center justify-content-lg-start gap-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-success me-2 fs-5"></i>
                            <span style="color: #000;">Chính hãng 100%</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-shipping-fast text-info me-2 fs-5"></i>
                            <span style="color: #000;">Giao hàng toàn quốc</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-headset text-warning me-2 fs-5"></i>
                            <span style="color: #000;">Hỗ trợ 24/7</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sale Badge -->
            <div class="col-lg-6">
                <div class="sale-banner p-4 rounded-4 text-center" style="background: linear-gradient(135deg, #ff6b6b 0%, #ff8e53 100%);">
                    <div class="sale-percent display-1 fw-bold text-white mb-2">30%</div>
                    <h3 class="text-white fw-bold mb-3">GIẢM GIÁ LỚN</h3>
                    <p class="text-white mb-4">Áp dụng cho tất cả sản phẩm điện tử</p>
                    <div class="sale-timer d-flex justify-content-center gap-3 mb-3">
                        <div class="time-box bg-white text-dark rounded-3 p-2">
                            <div class="fw-bold fs-4">02</div>
                            <small>Ngày</small>
                        </div>
                        <div class="time-box bg-white text-dark rounded-3 p-2">
                            <div class="fw-bold fs-4">12</div>
                            <small>Giờ</small>
                        </div>
                        <div class="time-box bg-white text-dark rounded-3 p-2">
                            <div class="fw-bold fs-4">45</div>
                            <small>Phút</small>
                        </div>
                    </div>
                    <a href="#sale" class="btn btn-light btn-lg fw-bold px-4 mt-2">
                        Mua ngay <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
        
        <!-- Full-width Carousel -->
        <div class="row">
            <div class="col-12">
                <?php 
                // Lấy tất cả banner đang hoạt động
                $banners = $bannerModel->getAllBanners(null, 1);
                
                // Debug: Chỉ in ra image_url của các banner
                echo '<script>';
                echo 'console.log("=== BANNERS IMAGE_URLS ===");';
                if (!empty($banners)) {
                    foreach ($banners as $banner) {
                        echo 'console.log("Image URL: ' . addslashes($banner['image_url']) . '");';
                    }
                } else {
                    echo 'console.log("Không tìm thấy banner nào");';
                }
                echo 'console.log("=======================");';
                echo '</script>';
                
                if (!empty($banners)): 
                ?>
                <div id="bannerCarousel" class="carousel slide w-100" data-bs-ride="carousel">
                    <div class="carousel-inner rounded-3 shadow w-100" style="height: 400px; overflow: hidden; background: #f8f9fa;">
                    <?php foreach ($banners as $index => $banner): ?>
                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?> h-100">
                            <div class="banner-link d-block h-100 position-relative">
                                <!-- Banner Image with Lightbox -->
                                <div class="banner-image-container d-flex justify-content-center align-items-center h-100 w-100">
                                    <div style="max-width: 100%; max-height: 100%; padding: 20px;">
                                        <a href="uploads/<?php echo htmlspecialchars($banner['image_url']); ?>" 
                                           data-lightbox="banner-gallery" 
                                           data-title="<?php echo htmlspecialchars($banner['title'] ?? 'Banner'); ?>"
                                           style="display: block; height: 100%;">
                                            <img src="uploads/<?php echo htmlspecialchars($banner['image_url']); ?>"
                                             class="img-fluid"
                                             style="
                                                max-height: 360px;
                                                width: auto;
                                                max-width: 100%;
                                                height: auto;
                                                object-fit: contain;
                                                transition: transform 0.4s ease;
                                                filter: drop-shadow(0 15px 35px rgba(0,0,0,0.2));
                                                margin: 0 auto;
                                                display: block;
                                             "
                                             alt="<?php echo htmlspecialchars($banner['title'] ?? 'Banner'); ?>">
                                        </a>
                                        <?php if (!empty($banner['link'])): ?>
                                        <a href="<?php echo htmlspecialchars($banner['link']); ?>" class="banner-click-area"></a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Gradient Overlay -->
                                <div class="position-absolute top-0 start-0 w-100 h-100" style="
                                    background: linear-gradient(135deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.05) 50%, rgba(255,255,255,0.1) 100%);
                                    z-index: 2;
                                    pointer-events: none;
                                "></div>
                                
                                <!-- Shine Effect -->
                                <div class="banner-shine position-absolute top-0 start-0 w-100 h-100" style="
                                    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
                                    transform: translateX(-100%);
                                    transition: transform 0.6s ease;
                                    pointer-events: none;
                                "></div>
                              
                                <!-- Floating particles -->
                                <div class="banner-particles position-absolute w-100 h-100" style="pointer-events: none;">
                                    <div class="particle" style="
                                        position: absolute;
                                        width: 4px;
                                        height: 4px;
                                        background: rgba(255,255,255,0.6);
                                        border-radius: 50%;
                                        top: 20%;
                                        left: 10%;
                                        animation: particle-float 8s ease-in-out infinite;
                                    "></div>
                                    <div class="particle" style="
                                        position: absolute;
                                        width: 3px;
                                        height: 3px;
                                        background: rgba(255,255,255,0.4);
                                        border-radius: 50%;
                                        top: 60%;
                                        right: 15%;
                                        animation: particle-float 12s ease-in-out infinite 2s;
                                    "></div>
                                    <div class="particle" style="
                                        position: absolute;
                                        width: 2px;
                                        height: 2px;
                                        background: rgba(255,255,255,0.5);
                                        border-radius: 50%;
                                        bottom: 30%;
                                        left: 20%;
                                        animation: particle-float 10s ease-in-out infinite 4s;
                                    "></div>
                                </div>
                            
                                <!-- Title overlay (if exists) -->
                                <?php if (!empty($banner['title'])): ?>
                                <div class="banner-title position-absolute bottom-0 start-0 end-0 p-4" style="
                                    background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
                                    color: white;
                                    z-index: 3;
                                ">
                                    <h3 class="mb-0 fw-bold" style="
                                        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
                                        font-size: 1.5rem;
                                    "><?php echo htmlspecialchars($banner['title']); ?></h3>
                                </div>
                                <?php endif; ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                    </div>
                    
                    <!-- Carousel Controls -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

                <style>
              /* Hide newsletter section */
              .newsletter-section {
                display: none !important;
              }
              
              /* Keyframe animations */
              @keyframes float {
                  0%, 100% { transform: translateY(0px) rotate(0deg); }
                  25% { transform: translateY(-10px) rotate(1deg); }
                  50% { transform: translateY(-5px) rotate(-1deg); }
                  75% { transform: translateY(-15px) rotate(0.5deg); }
              }

              @keyframes particle-float {
                  0%, 100% { 
                      transform: translateY(0px) translateX(0px) scale(1);
                      opacity: 0.6;
                  }
                  25% { 
                      transform: translateY(-20px) translateX(10px) scale(1.2);
                      opacity: 1;
                  }
                  50% { 
                      transform: translateY(-10px) translateX(-5px) scale(0.8);
                      opacity: 0.4;
                  }
                  75% { 
                      transform: translateY(-30px) translateX(15px) scale(1.1);
                      opacity: 0.8;
                  }
              }

              /* Hover effects */
              .banner-link:hover .banner-image-wrapper {
                  transform: scale(1.05);
              }

              .banner-link:hover .banner-image-wrapper img {
                  transform: scale(1.05);
              }

              /* Active/Focus effects for better interaction */
              .banner-link:active .banner-image-wrapper {
                  transform: scale(1.15);
                  transition: all 0.2s ease;
              }

              .banner-link:focus .banner-image-wrapper {
                  transform: scale(1.08);
                  outline: none;
              }

              .banner-link:hover .banner-shine {
                  left: 100%;
              }

              /* Responsive adjustments */
              @media (max-width: 768px) {
                  .carousel-item {
                      height: 300px !important;
                  }
                  
                  .banner-image-wrapper {
                      max-height: 90% !important;
                      max-width: 90% !important;
                  }
                  
                  .banner-title h3 {
                      font-size: 1.2rem !important;
                  }
              }

              @media (max-width: 576px) {
                  .carousel-item {
                      height: 250px !important;
                  }
                  
                  .banner-title h3 {
                      font-size: 1rem !important;
                  }
              }

              /* Smooth transitions for carousel */
              .carousel-item {
                  transition: transform 0.8s ease-in-out;
              }

              .carousel-item.active {
                  animation: slideInScale 0.8s ease-out;
              }

              @keyframes slideInScale {
                  0% {
                      transform: scale(0.95);
                      opacity: 0.8;
                  }
                  100% {
                      transform: scale(1);
                      opacity: 1;
                  }
              }
              </style>
            

                
                <!-- Shine effect -->
                <div class="banner-shine position-absolute" style="
                    top: 0;
                    left: -100%;
                    width: 100%;
                    height: 100%;
                    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
                    transition: left 0.6s ease;
                "></div>

<style>
/* Hero Section Styles */
.hero-section {
    background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
    color: #fff;
    position: relative;
    overflow: hidden;
}

.hero-section .min-vh-60 {
    min-height: 60vh;
    display: flex;
    align-items: center;
}

.hero-shape-1, 
.hero-shape-2 {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.8);
    z-index: 1;
    filter: blur(60px);
    opacity: 0.7;
    animation: float 15s ease-in-out infinite;
}

.hero-shape-1 {
    width: 400px;
    height: 400px;
    top: -100px;
    right: -100px;
}

.hero-shape-2 {
    width: 300px;
    height: 300px;
    bottom: -50px;
    left: -50px;
    animation-delay: -5s;
    animation-direction: reverse;
}

.hero-device-img {
    max-width: 100%;
    height: auto;
    filter: drop-shadow(0 15px 30px rgba(0,0,0,0.3));
    animation: float 6s ease-in-out infinite;
    transform-origin: center bottom;
}

.hero-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    padding: 10px 15px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    animation: pulse 2s infinite;
}

.hero-badge .sale-badge {
    font-size: 1.2rem;
    font-weight: 700;
}

.hero-badge small {
    font-size: 0.8rem;
    opacity: 0.9;
}

@keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-15px) rotate(1deg); }
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* Sale Banner Styles */
.sale-banner {
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border: none;
    transition: all 0.3s ease;
}

.sale-banner:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.sale-percent {
    text-shadow: 0 4px 8px rgba(0,0,0,0.2);
    animation: pulse 2s infinite;
}

.sale-timer .time-box {
    min-width: 70px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.sale-timer .time-box:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
}

.sale-timer small {
    font-size: 0.8rem;
    font-weight: 500;
}

.btn-light {
    background: white;
    color: #ff6b6b;
    font-weight: 700;
    transition: all 0.3s ease;
}

.btn-light:hover {
    background: #f8f9fa;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

/* Responsive Adjustments */
@media (max-width: 991.98px) {
    .hero-section .min-vh-60 {
        min-height: auto;
        padding: 80px 0;
    }
    
    .hero-section h1 {
        font-size: 2.5rem !important;
    }
}

@media (max-width: 767.98px) {
    .hero-section h1 {
        font-size: 2rem !important;
    }
    
    .hero-buttons .btn {
        width: 100%;
        margin-bottom: 10px;
    }
}

/* Banner click area */
.banner-click-area {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 3;
    opacity: 0;
}

/* Lightbox adjustments */
.lb-outerContainer {
    max-width: 95vw !important;
    max-height: 95vh !important;
    border-radius: 8px !important;
}

.lb-container {
    padding: 0 !important;
}

.lb-container .lb-image {
    max-width: 90vw !important;
    max-height: 85vh !important;
    width: auto !important;
    height: auto !important;
    border: 10px solid #fff;
    border-radius: 4px;
    box-shadow: 0 0 25px rgba(0,0,0,0.3);
}

.lb-dataContainer {
    margin-top: 15px !important;
}

.lb-data .lb-details {
    width: 100% !important;
}

.lb-data .lb-caption {
    font-size: 16px !important;
    font-weight: 500;
    text-align: center;
    padding: 10px 0;
}

.lb-nav a.lb-prev,
.lb-nav a.lb-next {
    opacity: 1 !important;
}

.lb-close {
    margin-top: 20px !important;
    margin-right: 20px !important;
}

/* Keyframe animations */
@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    25% { transform: translateY(-10px) rotate(1deg); }
    50% { transform: translateY(-5px) rotate(-1deg); }
    75% { transform: translateY(-15px) rotate(0.5deg); }
}

@keyframes particle-float {
    0%, 100% { 
        transform: translateY(0px) translateX(0px) scale(1);
        opacity: 0.6;
    }
    25% { 
        transform: translateY(-20px) translateX(10px) scale(1.2);
        opacity: 1;
    }
    50% { 
        transform: translateY(-10px) translateX(-5px) scale(0.8);
        opacity: 0.4;
    }
    75% { 
        transform: translateY(-30px) translateX(15px) scale(1.1);
        opacity: 0.8;
    }
}

/* Hover effects */
.banner-link:hover .banner-image-wrapper {
    transform: scale(1.05);
}

.banner-link:hover .banner-image-wrapper img {
    transform: scale(1.05);
}

.banner-link:hover .banner-shine {
    left: 100%;
}

.banner-link:hover .banner-pattern {
    animation-duration: 5s;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .carousel-item {
        height: 300px !important;
    }
    
    .banner-image-wrapper {
        max-height: 90% !important;
        max-width: 90% !important;
    }
    
    .banner-title h3 {
        font-size: 1.2rem !important;
    }
}

@media (max-width: 576px) {
    .carousel-item {
        height: 250px !important;
    }
    
    .banner-title h3 {
        font-size: 1rem !important;
    }
}

/* Smooth transitions for carousel */
.carousel-item {
    transition: transform 0.8s ease-in-out;
}

.carousel-item.active {
    animation: slideInScale 0.8s ease-out;
}

@keyframes slideInScale {
    0% {
        transform: scale(0.95);
        opacity: 0.8;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}
</style>
                    </div>
                    <?php if (count($banners) > 1): ?>
                        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Trước</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Tiếp</span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sản phẩm nổi bật -->
<section id="products" class="py-5">
    <div class="container">
        <div class="section-header mb-4">
            <h2 class="section-title">Sản phẩm nổi bật</h2>
            <a href="index.php?controller=product&action=list" class="view-all-btn">
                Xem tất cả
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                </svg>
            </a>
        </div>
        <div class="row g-4">
            <?php foreach ($products as $p): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product-card">
                        <div class="product-badge">
                            <?php if ($p['discount'] > 0): ?>
                                <span class="badge bg-danger">-<?php echo $p['discount']; ?>%</span>
                            <?php endif; ?>
                            <?php if ($p['is_new']): ?>
                                <span class="badge bg-success">Mới</span>
                            <?php endif; ?>
                        </div>
                        <div class="product-image">
                            <?php if (!empty($p['images'][0]['image_url'])): ?>
                                <img src="<?php echo htmlspecialchars($p['images'][0]['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($p['name']); ?>"
                                     class="img-fluid">
                            <?php else: ?>
                                <div class="no-image">
                                    <i class="fas fa-image"></i>
                                </div>
                            <?php endif; ?>
                            <div class="product-actions">
                                <a href="index.php?controller=product&action=detail&id=<?php echo $p['id']; ?>" 
                                   class="btn btn-sm btn-info text-white" 
                                   title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-light btn-wishlist" data-id="<?php echo $p['id']; ?>" title="Thêm vào yêu thích">
                                    <i class="far fa-heart"></i>
                                </button>
                                <button class="btn btn-sm btn-primary btn-add-to-cart" 
                                        data-id="<?php echo $p['id']; ?>"
                                        data-name="<?php echo htmlspecialchars($p['name']); ?>"
                                        data-price="<?php echo $p['price']; ?>"
                                        title="Thêm vào giỏ hàng">
                                    <i class="fas fa-shopping-cart me-1"></i> Thêm giỏ
                                </button>
                            </div>
                        </div>
                        <div class="product-info">
                            <h3 class="product-title">
                                <a href="index.php?controller=product&action=detail&id=<?php echo $p['id']; ?>">
                                    <?php echo htmlspecialchars($p['name']); ?>
                                </a>
                            </h3>
                            <div class="product-price">
                                <?php if ($p['discount'] > 0): ?>
                                    <span class="text-muted text-decoration-line-through me-2">
                                        <?php echo number_format($p['price']); ?>đ
                                    </span>
                                    <span class="text-danger fw-bold">
                                        <?php echo number_format($p['price'] * (100 - $p['discount']) / 100); ?>đ
                                    </span>
                                <?php else: ?>
                                    <span class="fw-bold"><?php echo number_format($p['price']); ?>đ</span>
                                <?php endif; ?>
                            </div>
                            <div class="product-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?php echo $i <= ($p['rating'] ?? 0) ? 'text-warning' : 'text-muted'; ?>"></i>
                                <?php endfor; ?>
                                <small class="text-muted">(<?php echo $p['review_count'] ?? 0; ?>)</small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Dịch vụ -->
<section class="services-section py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3>Miễn phí vận chuyển</h3>
                    <p>Cho đơn hàng từ 500.000đ</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-undo"></i>
                    </div>
                    <h3>Đổi trả trong 7 ngày</h3>
                    <p>Đảm bảo chất lượng</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>Hỗ trợ 24/7</h3>
                    <p>Đội ngũ tư vấn chuyên nghiệp</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tin tức/Blog -->
<section class="blog-section py-5">
    <div class="container">
        <div class="section-header mb-4">
            <h2 class="section-title">Tin tức mới nhất</h2>
            <a href="#" class="btn btn-link">Xem tất cả</a>
        </div>
        <div class="row g-4">
            <?php for ($i = 1; $i <= 3; $i++): ?>
                <div class="col-md-4">
                    <div class="blog-card">
                        <div class="blog-image">
                            <img src="https://via.placeholder.com/400x250" alt="Blog <?php echo $i; ?>" class="img-fluid">
                            <div class="blog-date">24/07/2025</div>
                        </div>
                        <div class="blog-content">
                            <h3><a href="#">Cách chọn mua thiết bị điện tử phù hợp</a></h3>
                            <p>Những lưu ý quan trọng khi chọn mua thiết bị điện tử để đảm bảo chất lượng và phù hợp với nhu cầu sử dụng.</p>
                            <a href="#" class="read-more">Đọc thêm <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="newsletter-section py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="mb-3">Đăng ký nhận tin khuyến mãi</h2>
        <p class="mb-4">Nhận thông tin ưu đãi và khuyến mãi đặc biệt từ chúng tôi</p>
        <form class="newsletter-form">
            <div class="input-group mx-auto" style="max-width: 500px;">
                <input type="email" class="form-control" placeholder="Nhập email của bạn" required>
                <button class="btn btn-light" type="submit">Đăng ký</button>
            </div>
        </form>
    </div>
</section>

<style>
/* Global Styles */
:root {
    --primary-color: #007bff;
    --secondary-color: #6c757d;
    --success-color: #28a745;
    --danger-color: #dc3545;
    --warning-color: #ffc107;
    --light-color: #f8f9fa;
    --dark-color: #343a40;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #333;
    line-height: 1.6;
}

/* Section */
.section-title {
    font-size: 1.75rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    position: relative;
    display: inline-block;
}

.section-title:after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -8px;
    width: 50px;
    height: 3px;
    background: var(--primary-color);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

/* Hero Section */
.hero-section {
    padding: 5rem 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    position: relative;
    overflow: hidden;
}

.hero-section h1 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    line-height: 1.2;
}

.hero-section p.lead {
    font-size: 1.25rem;
    color: #6c757d;
    margin-bottom: 2rem;
}

.hero-buttons .btn {
    padding: 0.75rem 2rem;
    font-weight: 500;
    border-radius: 50px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.9rem;
}

/* View All Button Styles */
.view-all-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1.5rem;
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    color: white !important;
    border-radius: 25px;
    font-weight: 500;
    text-decoration: none !important;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
    border: none;
    position: relative;
    overflow: hidden;
    z-index: 1;
}

.view-all-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(78, 115, 223, 0.4);
    color: white !important;
}

.view-all-btn:active {
    transform: translateY(1px);
}

.view-all-btn svg {
    margin-left: 8px;
    transition: transform 0.3s ease;
}

.view-all-btn:hover svg {
    transform: translateX(4px);
}

.view-all-btn::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #224abe 0%, #4e73df 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: -1;
    border-radius: 25px;
}

.view-all-btn:hover::after {
    opacity: 1;
}

/* Section Header Styles */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
    position: relative;
    display: inline-block;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 0;
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #4e73df, #224abe);
    border-radius: 3px;
}

/* Banner Section */
.banner-section {
    background-color: #f8f9fa;
}

.banner-container {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.banner-container:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

.banner-container img {
    width: 100%;
    max-height: 300px;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.banner-container:hover img {
    transform: scale(1.02);
}

@media (max-width: 767.98px) {
    .banner-container img {
        max-height: 200px;
    }
}

/* Product Card */
.product-card {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
    height: 100%;
    display: flex;
    flex-direction: column;
    border: 1px solid #eee;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.product-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 2;
}

.product-badge .badge {
    margin-right: 5px;
    font-weight: 500;
    padding: 5px 10px;
    border-radius: 4px;
}

.product-image {
    position: relative;
    overflow: hidden;
    background: #f8f9fa;
    padding-top: 100%;
}

.product-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 1rem;
    transition: transform 0.5s ease;
}

.no-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ccc;
    font-size: 3rem;
}

.product-actions {
    position: absolute;
    bottom: -50px;
    left: 0;
    right: 0;
    display: flex;
    justify-content: center;
    padding: 10px;
    background: rgba(255,255,255,0.9);
    transition: all 0.3s ease;
    opacity: 0;
}

.product-card:hover .product-actions {
    bottom: 0;
    opacity: 1;
}

.btn-wishlist {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 5px;
}

.btn-add-to-cart {
    white-space: nowrap;
    font-size: 0.8rem;
    padding: 0.4rem 0.8rem;
}

.product-info {
    padding: 1.25rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.product-title {
    font-size: 1rem;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.product-title a {
    color: var(--dark-color);
    text-decoration: none;
    transition: color 0.2s;
}

.product-title a:hover {
    color: var(--primary-color);
}

.product-price {
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--primary-color);
}

.product-rating {
    margin-top: auto;
    color: var(--warning-color);
    font-size: 0.85rem;
}

.product-rating .text-muted {
    color: #adb5bd !important;
}

/* Service Card */
.service-card {
    background: #fff;
    border-radius: 10px;
    padding: 2rem 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
    height: 100%;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    border: 1px solid #eee;
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.service-icon {
    width: 70px;
    height: 70px;
    background: rgba(0,123,255,0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 1.75rem;
    color: var(--primary-color);
    transition: all 0.3s ease;
}

.service-card:hover .service-icon {
    background: var(--primary-color);
    color: #fff;
    transform: rotateY(180deg);
}

.service-card h3 {
    font-size: 1.25rem;
    margin-bottom: 0.75rem;
    font-weight: 600;
}

.service-card p {
    color: #6c757d;
    margin-bottom: 0;
}

/* Blog Card */
.blog-card {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    border: 1px solid #eee;
}

.blog-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.blog-image {
    position: relative;
    overflow: hidden;
    height: 200px;
}

.blog-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.blog-card:hover .blog-image img {
    transform: scale(1.05);
}

.blog-date {
    position: absolute;
    top: 15px;
    right: 15px;
    background: var(--primary-color);
    color: #fff;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 500;
}

.blog-content {
    padding: 1.5rem;
}

.blog-content h3 {
    font-size: 1.25rem;
    margin-bottom: 0.75rem;
    font-weight: 600;
}

.blog-content h3 a {
    color: var(--dark-color);
    text-decoration: none;
    transition: color 0.2s;
}

.blog-content h3 a:hover {
    color: var(--primary-color);
}

.blog-content p {
    color: #6c757d;
    margin-bottom: 1rem;
}

.read-more {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
}

.read-more i {
    margin-left: 5px;
    transition: transform 0.3s ease;
}

.read-more:hover i {
    transform: translateX(5px);
}

/* Newsletter Section */
.newsletter-section {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    position: relative;
    overflow: hidden;
}

.newsletter-section h2 {
    font-weight: 700;
    margin-bottom: 1rem;
}

.newsletter-section p {
    font-size: 1.1rem;
    opacity: 0.9;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.newsletter-form .form-control {
    height: 50px;
    border: none;
    border-radius: 50px 0 0 50px;
    padding-left: 1.5rem;
    box-shadow: none;
}

.newsletter-form .btn {
    border-radius: 0 50px 50px 0;
    padding: 0 2rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    height: 50px;
    background: var(--dark-color);
    border-color: var(--dark-color);
}

.newsletter-form .btn:hover {
    background: #23272b;
    border-color: #1d2124;
}

/* Responsive */
@media (max-width: 991.98px) {
    .hero-section {
        padding: 4rem 0;
    }
    
    .hero-section h1 {
        font-size: 2.5rem;
    }
    
    .hero-section p.lead {
        font-size: 1.1rem;
    }
}

@media (max-width: 767.98px) {
    .section-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .section-header .btn-link {
        margin-top: 1rem;
    }
    
    .hero-section {
        text-align: center;
        padding: 3rem 0;
    }
    
    .hero-section h1 {
        font-size: 2rem;
    }
    
    .hero-buttons {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .hero-buttons .btn {
        width: 100%;
    }
    
    .newsletter-form .form-control,
    .newsletter-form .btn {
        border-radius: 50px;
    }
    
    .newsletter-form .btn {
        margin-top: 1rem;
        width: 100%;
    }
}
    transition: background 0.18s, color 0.18s;
}

/* Scroll to top button */
#scrollTopBtn {
    display: none;
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 99;
    width: 50px;
    height: 50px;
    border: none;
    border-radius: 50%;
    background: var(--primary-color);
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

#scrollTopBtn:hover {
    background: #0056b3;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeInUp {
    animation: fadeInUp 0.6s ease-out forwards;
}

.delay-1 {
    animation-delay: 0.2s;
}

.delay-2 {
    animation-delay: 0.4s;
}

.delay-3 {
    animation-delay: 0.6s;
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
// Hiệu ứng gõ chữ trong banner
let i = 0;
const slogan = 'Chất lượng tạo nên thương hiệu';

function typeSlogan() {
  const typingElement = document.getElementById('typing-slogan');
  if (typingElement) {
    typingElement.innerHTML = slogan.substring(0, i) + '<span style="border-right:2px solid #fff;">&nbsp;</span>';
    i++;
    if (i <= slogan.length) {
      setTimeout(typeSlogan, 100);
    } else {
      // Reset sau khi hoàn thành
      setTimeout(() => {
        i = 0;
        typeSlogan();
      }, 3000);
    }
  }
}

// Xử lý nút cuộn lên đầu trang
function initScrollToTop() {
  const scrollTopBtn = document.getElementById('scrollTopBtn');
  if (!scrollTopBtn) return;
  
  // Hiển thị nút khi cuộn xuống
  const handleScroll = () => {
    if (window.pageYOffset > 300) {
      scrollTopBtn.style.display = 'flex';
    } else {
      scrollTopBtn.style.display = 'none';
    }
  };

  // Cuộn lên đầu trang khi nhấn nút
  const scrollToTop = () => {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  };

  // Remove any existing event listeners
  window.removeEventListener('scroll', handleScroll);
  scrollTopBtn.removeEventListener('click', scrollToTop);
  
  // Add new event listeners
  window.addEventListener('scroll', handleScroll);
  scrollTopBtn.addEventListener('click', scrollToTop);
  
  // Initial check
  handleScroll();
}

// Call the function when DOM is loaded
document.addEventListener('DOMContentLoaded', initScrollToTop);

// Hàm thêm sản phẩm vào giỏ hàng
function addToCart(productId, productName, price) {
  // Hiển thị loading
  const button = document.querySelector(`.btn-add-to-cart[data-id="${productId}"]`);
  const originalHtml = button.innerHTML;
  button.disabled = true;
  button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang xử lý...';
  
  // Gọi API thêm vào giỏ hàng
  fetch('index.php?controller=cart&action=add', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'X-Requested-With': 'XMLHttpRequest'
    },
    body: `product_id=${productId}&quantity=1`
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    return response.json();
  })
  .then(data => {
    if (data.success) {
      // Cập nhật số lượng trong giỏ hàng
      updateCartCount(data.cartCount || 1);
      
      // Hiển thị thông báo
      showToast('success', 'Thành công', `${productName} đã được thêm vào giỏ hàng`);
    } else {
      showToast('error', 'Lỗi', data.message || 'Có lỗi xảy ra khi thêm vào giỏ hàng');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    showToast('error', 'Lỗi', 'Có lỗi xảy ra khi kết nối đến máy chủ');
  })
  .finally(() => {
    // Khôi phục trạng thái nút
    button.disabled = false;
    button.innerHTML = originalHtml;
  });
}

// Hàm thêm/xóa sản phẩm yêu thích
function addToWishlist(productId, isAdding) {
  const action = isAdding ? 'add' : 'remove';
  
  fetch(`index.php?controller=wishlist&action=${action}`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: `product_id=${productId}`
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      const message = isAdding ? 'Đã thêm vào yêu thích' : 'Đã xóa khỏi yêu thích';
      showToast('success', 'Thành công', message);
    } else {
      showToast('error', 'Lỗi', data.message || 'Có lỗi xảy ra');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    showToast('error', 'Lỗi', 'Có lỗi xảy ra khi kết nối đến máy chủ');
  });
}

// Hàm cập nhật số lượng sản phẩm trong giỏ hàng
function updateCartCount(count) {
  const cartCountElements = document.querySelectorAll('.cart-count');
  cartCountElements.forEach(element => {
    element.textContent = count;
    element.style.display = count > 0 ? 'inline-flex' : 'none';
  });
}

// Hàm hiển thị thông báo
function showToast(type, title, message) {
  // Tạo toast element nếu chưa tồn tại
  let toastContainer = document.querySelector('.toast-container');
  if (!toastContainer) {
    toastContainer = document.createElement('div');
    toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
    document.body.appendChild(toastContainer);
  }
  
  const toastId = 'toast-' + Date.now();
  const toastHtml = `
    <div id="${toastId}" class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header bg-${type} text-white">
        <strong class="me-auto">${title}</strong>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        ${message}
      </div>
    </div>
  `;
  
  // Thêm toast vào container
  toastContainer.insertAdjacentHTML('beforeend', toastHtml);
  
  // Tự động ẩn toast sau 5 giây
  setTimeout(() => {
    const toastElement = document.getElementById(toastId);
    if (toastElement) {
      toastElement.classList.remove('show');
      setTimeout(() => {
        toastElement.remove();
      }, 300);
    }
  }, 5000);
}

// Khởi tạo tooltips
const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl);
});

// Thêm hiệu ứng animation khi scroll
function animateOnScroll() {
  const elements = document.querySelectorAll('.animate-on-scroll');
  
  elements.forEach(element => {
    const elementPosition = element.getBoundingClientRect().top;
    const screenPosition = window.innerHeight / 1.2;
    
    if (elementPosition < screenPosition) {
      element.classList.add('animate-fadeInUp');
    }
  });
}

// Khởi tạo tooltips khi DOM đã tải xong
document.addEventListener('DOMContentLoaded', function() {
  // Khởi tạo tooltips
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.forEach(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Khởi tạo hiệu ứng gõ chữ
  typeSlogan();
  
  // Thêm sự kiện scroll
  window.addEventListener('scroll', animateOnScroll);
  
  // Chạy lần đầu khi tải trang
  animateOnScroll();
});

// Xử lý nút scroll to top
const scrollTopBtn = document.getElementById('scrollTopBtn');
if (scrollTopBtn) {
  scrollTopBtn.addEventListener('click', function(e) {
    e.preventDefault();
    window.scrollTo({
      top: 0,
}

// Xử lý sự kiện click cho nút thêm vào giỏ hàng và yêu thích
document.addEventListener('click', function(e) {
  // Xử lý nút thêm vào giỏ hàng
  if (e.target.closest('.btn-add-to-cart')) {
    e.preventDefault();
    const button = e.target.closest('.btn-add-to-cart');
    const productId = button.getAttribute('data-id');
    const productName = button.getAttribute('data-name');
    const productPrice = button.getAttribute('data-price');
    
    if (productId && productName) {
      addToCart(productId, productName, productPrice);
    }
  }
  
  // Xử lý nút yêu thích
  if (e.target.closest('.btn-wishlist')) {
    e.preventDefault();
    const button = e.target.closest('.btn-wishlist');
    const productId = button.getAttribute('data-id');
    const icon = button.querySelector('i');
    
    if (icon) {
      const isAdding = icon.classList.contains('far');
      
      // Đổi trạng thái icon
      if (isAdding) {
        icon.classList.remove('far');
        icon.classList.add('fas', 'text-danger');
      } else {
        icon.classList.remove('fas', 'text-danger');
        icon.classList.add('far');
      }
      
      // Gọi API cập nhật yêu thích
      if (productId) {
        addToWishlist(productId, isAdding);
      }
    }
  }
});

// Hàm thêm/xóa sản phẩm yêu thích
function addToWishlist(productId, isAdding) {
  const action = isAdding ? 'add' : 'remove';
  
  fetch(`index.php?controller=wishlist&action=${action}`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: `product_id=${productId}`
  })
  .then(response => response.json())
  .then(data => {
    if (!data.success) {
      // Nếu có lỗi, đảo ngược trạng thái icon
      const button = document.querySelector(`.btn-wishlist[data-id="${productId}"]`);
      if (button) {
        const icon = button.querySelector('i');
        if (icon) {
          if (isAdding) {
            icon.classList.remove('fas', 'text-danger');
            icon.classList.add('far');
          } else {
            icon.classList.remove('far');
            icon.classList.add('fas', 'text-danger');
          }
        }
      }
      showToast('error', 'Lỗi', data.message || 'Có lỗi xảy ra');
    } else {
      const message = isAdding ? 'Đã thêm vào yêu thích' : 'Đã xóa khỏi yêu thích';
      showToast('success', 'Thành công', message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    showToast('error', 'Lỗi', 'Không thể kết nối đến máy chủ');
  });
}

// Hàm cập nhật số lượng sản phẩm trong giỏ hàng
function updateCartCount(count) {
  const cartCountElements = document.querySelectorAll('.cart-count');
  cartCountElements.forEach(element => {
    element.textContent = count;
    element.style.display = count > 0 ? 'inline-flex' : 'none';
  });
}

// Hàm hiển thị thông báo
function showToast(type, title, message) {
  // Tạo toast element nếu chưa tồn tại
  let toastContainer = document.querySelector('.toast-container');
  if (!toastContainer) {
    toastContainer = document.createElement('div');
    toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
    document.body.appendChild(toastContainer);
  }
  
  const toastId = 'toast-' + Date.now();
  const toastHtml = `
    <div id="${toastId}" class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header bg-${type} text-white">
        <strong class="me-auto">${title}</strong>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        ${message}
      </div>
    </div>
  `;
  
  // Thêm toast vào container
  toastContainer.insertAdjacentHTML('beforeend', toastHtml);
  
  // Tự động ẩn toast sau 5 giây
  setTimeout(() => {
    const toastElement = document.getElementById(toastId);
    if (toastElement) {
      toastElement.classList.remove('show');
      setTimeout(() => {
        toastElement.remove();
      }, 300);
    }
  }, 5000);
}

// Xử lý sự kiện scroll để ẩn/hiện nút scroll to top
window.addEventListener('scroll', function() {
  const scrollTopBtn = document.getElementById('scrollTopBtn');
  if (scrollTopBtn) {
    if (window.pageYOffset > 300) {
      scrollTopBtn.style.display = 'flex';
    } else {
      scrollTopBtn.style.display = 'none';
    }
  }
});

// Hàm xử lý animation khi scroll
function handleScrollAnimations() {
  const elements = document.querySelectorAll('.animate-on-scroll');
  elements.forEach(element => {
    const elementPosition = element.getBoundingClientRect().top;
    const screenPosition = window.innerHeight / 1.2;
    
    if (elementPosition < screenPosition) {
      element.classList.add('animate-fadeInUp');
    }
  });
}

// Gọi hàm khi trang tải xong
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    typeSlogan();
    window.addEventListener('scroll', handleScrollAnimations);
    handleScrollAnimations(); // Chạy lần đầu khi tải trang
  });
} else {
  typeSlogan();
  window.addEventListener('scroll', handleScrollAnimations);
  handleScrollAnimations(); // Chạy lần đầu nếu trang đã tải xong
}

// Cuộn lên đầu trang khi tải lại
window.scrollTo({ top: 0, behavior: 'smooth' });
// Configure Lightbox options
lightbox.option({
    'resizeDuration': 200,
    'wrapAround': true,
    'showImageNumberLabel': true,
    'disableScrolling': true,
    'albumLabel': 'Hình ảnh %1 của %2',
    'fadeDuration': 300,
    'imageFadeDuration': 300,
    'fitImagesInViewport': true,
    'maxWidth': 1200,
    'maxHeight': 800,
    'positionFromTop': 50,
    'alwaysShowNavOnTouchDevices': true
});
</script>

<!-- Add Lightbox2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox-plus-jquery.min.js"></script>

<?php include 'view/layout/footer.php';