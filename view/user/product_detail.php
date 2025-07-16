<?php /* @var $product */ ?>
<div class="container py-4">
  <div class="row g-4">
    <div class="col-lg-6 col-md-6 col-12">
      <div class="card shadow-sm border-0">
        <div class="card-body p-0">
          <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
              <?php if (!empty($product['images'])): ?>
                <?php foreach ($product['images'] as $idx => $img): ?>
                  <div class="carousel-item <?php echo $idx === 0 ? 'active' : ''; ?>">
                    <img src="<?php echo htmlspecialchars($img); ?>" class="d-block w-100 product-image-zoom" alt="Ảnh sản phẩm" style="max-height:420px;object-fit:contain;cursor:zoom-in;">
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <div class="carousel-item active">
                  <img src="https://via.placeholder.com/400x400?text=No+Image" class="d-block w-100" alt="No image">
                </div>
              <?php endif; ?>
            </div>
            <?php if (!empty($product['images']) && count($product['images']) > 1): ?>
              <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <!-- Modal phóng to ảnh -->
      <div class="modal fade" id="productImageModal" tabindex="-1" aria-labelledby="productImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0 text-center">
              <img id="modalProductImage" src="" alt="Ảnh phóng to" style="max-width:100%;max-height:80vh;border-radius:8px;box-shadow:0 2px 16px rgba(0,0,0,0.3);background:#fff;">
            </div>
          </div>
        </div>
      </div>
      <script>
      document.addEventListener('DOMContentLoaded', function() {
        var modal = document.getElementById('productImageModal');
        var modalImg = document.getElementById('modalProductImage');
        document.querySelectorAll('.product-image-zoom').forEach(function(img) {
          img.addEventListener('click', function() {
            var src = this.getAttribute('src');
            modalImg.src = src;
            var modalInstance = new bootstrap.Modal(modal);
            modalInstance.show();
          });
        });
        modal.addEventListener('hidden.bs.modal', function () {
          modalImg.src = '';
        });
      });
      </script>
    </div>
    <div class="col-lg-6 col-md-6 col-12">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <h2 class="fw-bold mb-2"><?php echo htmlspecialchars($product['name']); ?></h2>
          <div class="mb-2">
            <span class="fs-3 fw-bold text-danger"><?php echo number_format($product['price'], 0, ',', '.'); ?> đ</span>
            <?php if (!empty($product['sale_price']) && $product['sale_price'] > 0): ?>
              <span class="text-muted ms-2"><del><?php echo number_format($product['sale_price'], 0, ',', '.'); ?> đ</del></span>
            <?php endif; ?>
          </div>
          <div class="mb-3">
            <span class="badge bg-<?php echo $product['stock_quantity'] > 0 ? 'success' : 'danger'; ?>">
              <?php echo $product['stock_quantity'] > 0 ? 'Còn hàng' : 'Hết hàng'; ?>
            </span>
          </div>
          <div class="mb-3">
            <strong>Mô tả ngắn:</strong>
            <div><?php echo nl2br(htmlspecialchars($product['short_description'] ?? '')); ?></div>
          </div>
          <div class="mb-3">
            <strong>Mô tả chi tiết:</strong>
            <div><?php echo nl2br(htmlspecialchars($product['description'] ?? '')); ?></div>
          </div>
          <div class="row g-2 mb-3">
            <div class="col-6"><strong>Danh mục:</strong> <?php echo htmlspecialchars($product['category_name'] ?? ''); ?></div>
            <div class="col-6"><strong>Thương hiệu:</strong> <?php echo htmlspecialchars($product['brand_name'] ?? ''); ?></div>
            <div class="col-6"><strong>Model:</strong> <?php echo htmlspecialchars($product['model'] ?? ''); ?></div>
            <div class="col-6"><strong>SKU:</strong> <?php echo htmlspecialchars($product['sku'] ?? ''); ?></div>
            <div class="col-6"><strong>Bảo hành:</strong> <?php echo htmlspecialchars($product['warranty_period'] ?? ''); ?> tháng</div>
            <div class="col-6"><strong>Trọng lượng:</strong> <?php echo htmlspecialchars($product['weight'] ?? ''); ?> kg</div>
            <div class="col-12"><strong>Kích thước:</strong> <?php echo htmlspecialchars($product['dimensions'] ?? ''); ?></div>
          </div>
          <form method="POST" action="index.php?controller=cart&action=add" class="add-to-cart-form">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="btn btn-primary btn-lg w-100 mt-2"><i class="fas fa-cart-plus"></i> Thêm vào giỏ hàng</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="row mt-5 g-4">
    <div class="col-lg-6 col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h4 class="fw-bold mb-3">Thông số kỹ thuật</h4>
          <?php if (!empty($product['specs'])): ?>
            <table class="table table-bordered table-sm">
              <tbody>
                <?php foreach ($product['specs'] as $spec): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($spec['spec_name']); ?></td>
                    <td><?php echo htmlspecialchars($spec['spec_value']); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php else: ?>
            <div class="text-muted">Chưa có thông số kỹ thuật.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div class="col-lg-6 col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h4 class="fw-bold mb-3">Đánh giá sản phẩm</h4>
          <?php if (!empty($reviews)): ?>
            <?php foreach ($reviews as $review): ?>
              <div class="border rounded p-2 mb-2">
                <div class="fw-bold mb-1"><?php echo htmlspecialchars(($review['first_name'] ?? '') . ' ' . ($review['last_name'] ?? '')); ?>
                  <span class="text-warning ms-2">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                      <?php if ($i <= $review['rating']): ?>★<?php else: ?>☆<?php endif; ?>
                    <?php endfor; ?>
                  </span>
                </div>
                <div class="mb-1"><strong><?php echo htmlspecialchars($review['title'] ?? ''); ?></strong></div>
                <div><?php echo nl2br(htmlspecialchars($review['comment'] ?? '')); ?></div>
                <?php if (!empty($review['pros'])): ?><div class="text-success small mt-1">+ <?php echo htmlspecialchars($review['pros']); ?></div><?php endif; ?>
                <?php if (!empty($review['cons'])): ?><div class="text-danger small">- <?php echo htmlspecialchars($review['cons']); ?></div><?php endif; ?>
                <div class="text-muted small mt-1">Ngày: <?php echo date('d/m/Y', strtotime($review['created_at'])); ?></div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="text-muted">Chưa có đánh giá cho sản phẩm này.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<style>
.product-image-zoom {border-radius: 1rem; background: #f8f9fa;}
.card {border-radius: 1rem;}
</style> 
<script>
document.addEventListener('DOMContentLoaded', function() {
  var form = document.querySelector('.add-to-cart-form');
  if (form) {
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
        fetch('index.php?controller=cart&action=count')
          .then(r => r.json())
          .then(data => {
            const badge = document.querySelector('.btn-cart-badge');
            if (badge) badge.textContent = data.count > 0 ? data.count : '';
          });
      });
    });
  }
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