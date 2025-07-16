<?php /* @var $products, $total, $totalPages, $categories, $page, $search, $category_id */ ?>
<div class="container-fluid py-4" style="padding-left:0;margin-left:0;">
  <div class="row g-0" style="margin-left:0;">
    <!-- Sidebar lọc -->
    <aside class="col-lg-3 col-md-4 mb-4" style="min-width:200px;max-width:320px;padding-left:0;margin-left:0;">
      <div class="position-sticky" style="top:90px;">
        <form method="GET" action="index.php" class="p-2 bg-white border-end border-2" style="min-height:100vh;">
          <input type="hidden" name="controller" value="product">
          <input type="hidden" name="action" value="list">
          <div class="mb-3">
            <label for="search" class="form-label">Tìm kiếm</label>
            <div class="input-group">
              <input type="text" class="form-control" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Tên sản phẩm...">
              <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Danh mục</label>
            <div class="dropdown w-100">
              <button class="btn btn-outline-primary dropdown-toggle w-100 text-start" type="button" id="dropdownCategory" data-bs-toggle="dropdown" aria-expanded="false">
                <?php
                $catName = 'Tất cả danh mục';
                foreach ($categories as $cat) {
                  if ($cat['id'] == $category_id) $catName = $cat['name'];
                }
                echo htmlspecialchars($catName);
                ?>
              </button>
              <ul class="dropdown-menu w-100" aria-labelledby="dropdownCategory" style="max-height:350px;overflow-y:auto;">
                <li><a class="dropdown-item<?php if (!$category_id) echo ' active'; ?>" href="index.php?controller=product&action=list">Tất cả danh mục</a></li>
                <?php foreach ($categories as $cat): ?>
                  <li><a class="dropdown-item<?php if ($category_id == $cat['id']) echo ' active'; ?>" href="index.php?controller=product&action=list&category_id=<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></a></li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
          <a href="index.php?controller=product&action=list" class="btn btn-outline-secondary w-100 mt-2"><i class="fas fa-times"></i> Xóa bộ lọc</a>
        </form>
      </div>
    </aside>
    <!-- Danh sách sản phẩm -->
    <section class="col-lg-9 col-md-8">
      <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
          <span class="fw-bold">Tìm thấy <?php echo $total; ?> sản phẩm</span>
        </div>
        <div class="dropdown">
          <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <i class="fas fa-sort"></i> Sắp xếp
          </button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="?controller=product&action=list&sort=name">Tên A-Z</a></li>
            <li><a class="dropdown-item" href="?controller=product&action=list&sort=price_asc">Giá tăng dần</a></li>
            <li><a class="dropdown-item" href="?controller=product&action=list&sort=price_desc">Giá giảm dần</a></li>
            <li><a class="dropdown-item" href="?controller=product&action=list&sort=newest">Mới nhất</a></li>
          </ul>
        </div>
      </div>
      <?php if (!empty($products)): ?>
        <div class="row g-4">
          <?php foreach ($products as $product): ?>
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
              <div class="card h-100 shadow-sm product-card border-0">
                <div class="position-relative">
                  <?php if ($product['featured']): ?>
                    <span class="badge bg-warning position-absolute top-0 start-0 m-2"><i class="fas fa-star"></i> Nổi bật</span>
                  <?php endif; ?>
                  <?php if ($product['stock_quantity'] <= 0): ?>
                    <span class="badge bg-danger position-absolute top-0 end-0 m-2"><i class="fas fa-times"></i> Hết hàng</span>
                  <?php endif; ?>
                  <a href="index.php?controller=product&action=detail&id=<?php echo $product['id']; ?>" class="d-block">
                    <?php if (!empty($product['images'])): ?>
                      <img src="<?php echo htmlspecialchars($product['images'][0]); ?>" class="card-img-top product-image" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <?php else: ?>
                      <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top product-image" alt="No image">
                    <?php endif; ?>
                  </a>
                </div>
                <div class="card-body d-flex flex-column">
                  <h6 class="card-title product-title mb-1 text-truncate" title="<?php echo htmlspecialchars($product['name']); ?>"><?php echo htmlspecialchars($product['name']); ?></h6>
                  <div class="mb-2 text-muted small">
                    <i class="fas fa-tag"></i> <?php echo htmlspecialchars($product['category_name'] ?? 'N/A'); ?>
                  </div>
                  <div class="mb-2">
                    <span class="fs-5 fw-bold text-danger"><?php echo number_format($product['price'], 0, ',', '.'); ?> đ</span>
                    <?php if (!empty($product['sale_price']) && $product['sale_price'] > 0): ?>
                      <span class="text-muted ms-2"><del><?php echo number_format($product['sale_price'], 0, ',', '.'); ?> đ</del></span>
                    <?php endif; ?>
                  </div>
                  <div class="mt-auto d-flex gap-2">
                    <a href="index.php?controller=product&action=detail&id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm flex-fill"><i class="fas fa-eye"></i> Xem chi tiết</a>
                    <?php if ($product['stock_quantity'] > 0): ?>
                      <form method="POST" action="index.php?controller=cart&action=add" class="add-to-cart-form" style="display:inline;">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-outline-success btn-sm" title="Thêm vào giỏ hàng"><i class="fas fa-cart-plus"></i></button>
                      </form>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <!-- Phân trang đẹp -->
        <?php if ($totalPages > 1): ?>
          <?php
          $queryBase = "?controller=product&action=list";
          if ($search !== '') $queryBase .= "&search=" . urlencode($search);
          if ($category_id) $queryBase .= "&category_id=" . urlencode($category_id);
          if (!empty($_GET['sort'])) $queryBase .= "&sort=" . urlencode($_GET['sort']);
          $range = 2;
          $showDots = false;
          ?>
          <nav aria-label="Phân trang" class="mt-4">
            <ul class="pagination justify-content-center pagination-lg">
              <?php if ($page > 1): ?>
                <li class="page-item"><a class="page-link" href="<?php echo $queryBase . '&page=' . ($page-1); ?>"><i class="fas fa-chevron-left"></i></a></li>
              <?php endif; ?>
              <?php for ($i = 1; $i <= $totalPages; $i++) {
                if ($i == 1 || $i == $totalPages || ($i >= $page - $range && $i <= $page + $range)) {
                  if ($showDots) {
                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    $showDots = false;
                  }
                  echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '"><a class="page-link" href="' . $queryBase . '&page=' . $i . '">' . $i . '</a></li>';
                } elseif (!$showDots) {
                  $showDots = true;
                }
              } ?>
              <?php if ($page < $totalPages): ?>
                <li class="page-item"><a class="page-link" href="<?php echo $queryBase . '&page=' . ($page+1); ?>"><i class="fas fa-chevron-right"></i></a></li>
              <?php endif; ?>
            </ul>
            <div class="text-center text-muted small mt-2">Trang <?php echo $page; ?> / <?php echo $totalPages; ?> (<?php echo $total; ?> sản phẩm)</div>
          </nav>
        <?php endif; ?>
      <?php else: ?>
        <div class="text-center py-5">
          <i class="fas fa-search fa-3x text-muted mb-3"></i>
          <h5 class="text-muted">Không tìm thấy sản phẩm nào</h5>
          <p class="text-muted">Hãy thử tìm kiếm với từ khóa khác hoặc chọn danh mục khác</p>
          <a href="index.php?controller=product&action=list" class="btn btn-primary"><i class="fas fa-home"></i> Xem tất cả sản phẩm</a>
        </div>
      <?php endif; ?>
    </section>
  </div>
</div>
<style>
.product-card {transition: box-shadow 0.2s, transform 0.2s; border-radius: 1rem;}
.product-card:hover {box-shadow: 0 8px 32px rgba(0,0,0,0.15)!important; transform: translateY(-4px) scale(1.01);}
.product-image {height: 220px; object-fit: cover; border-radius: 1rem 1rem 0 0; background: #f8f9fa;}
.product-title {font-size: 1.1rem; font-weight: 600; color: #222; line-height: 1.4;}
@media (max-width: 768px) {.product-image {height: 160px;}}
.container-fluid, .row, aside.col-lg-3, aside.col-md-4 {
  padding-left: 0 !important;
  margin-left: 0 !important;
}
aside.col-lg-3, aside.col-md-4, aside {background: none !important; border: none !important;}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.add-to-cart-form').forEach(function(form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(form);
      fetch('index.php?controller=cart&action=add', {
        method: 'POST',
        body: formData
      })
      .then(res => res.ok ? res.text() : Promise.reject(res))
      .then(() => {
        showCartToast('Đã thêm vào giỏ hàng!');
        // Cập nhật badge giỏ hàng
        fetch('index.php?controller=cart&action=count')
          .then(r => r.json())
          .then(data => {
            const badge = document.querySelector('.btn-cart-badge');
            if (badge) badge.textContent = data.count > 0 ? data.count : '';
          });
      });
    });
  });
});
function showCartToast(msg) {
  let toast = document.getElementById('cart-toast');
  if (!toast) {
    toast = document.createElement('div');
    toast.id = 'cart-toast';
    toast.className = 'toast align-items-center text-bg-success border-0 position-fixed bottom-0 end-0 m-4 show';
    toast.style.zIndex = 9999;
    toast.innerHTML = '<div class="d-flex"><div class="toast-body">' + msg + '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>';
    document.body.appendChild(toast);
    toast.querySelector('.btn-close').onclick = () => toast.remove();
    setTimeout(() => { if (toast) toast.remove(); }, 2000);
  }
}
</script> 