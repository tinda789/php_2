<?php global $conn; ?>
<?php /* @var $product, $reviews */ ?>
<?php if (!$product): ?>
  <div class="container py-4">
    <div class="alert alert-danger">
      <h4>Không tìm thấy sản phẩm</h4>
      <p>Sản phẩm bạn đang tìm kiếm không tồn tại hoặc đã bị xóa.</p>
      <a href="index.php" class="btn btn-primary">Về trang chủ</a>
    </div>
  </div>
<?php else: ?>
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
                    <?php require_once 'helpers/image_helper.php'; ?>
                    <img src="<?php echo htmlspecialchars(getImageUrl($img['image_url'])); ?>" class="d-block w-100 product-image-zoom" alt="Ảnh sản phẩm" style="max-height:420px;object-fit:contain;cursor:zoom-in;" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x400?text=No+Image';">
                  </div>
                <?php endforeach; ?>
              <?php elseif (!empty($product['image_link'])): ?>
                <div class="carousel-item active">
                  <img src="<?php echo htmlspecialchars(getImageUrl($product['image_link'])); ?>" class="d-block w-100 product-image-zoom" alt="Ảnh sản phẩm" style="max-height:420px;object-fit:contain;cursor:zoom-in;" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x400?text=No+Image';">
                </div>
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
            <span class="badge bg-<?php echo $product['stock'] > 0 ? 'success' : 'danger'; ?>">
              <?php echo $product['stock'] > 0 ? 'Còn hàng' : 'Hết hàng'; ?>
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
          <?php
          // Hiển thị đánh giá sản phẩm
          $reviews = Product::getReviews($conn, $product['id']);
          ?>
          <?php if (!empty($reviews)): ?>
            <?php foreach ($reviews as $review): ?>
              <div class="review border rounded p-2 mb-2">
                <strong><?php echo htmlspecialchars($review['first_name'] . ' ' . $review['last_name']); ?></strong>
                <span class="text-warning">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="fa fa-star<?php echo $i <= $review['rating'] ? '' : '-o'; ?>"></i>
                    <?php endfor; ?>
                  </span>
                <span class="text-muted small ml-2"><?php echo date('d/m/Y H:i', strtotime($review['created_at'])); ?></span>
                <div><?php echo nl2br(htmlspecialchars($review['comment'])); ?></div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="text-muted">Chưa có đánh giá cho sản phẩm này.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <?php
  // Hiển thị form gửi đánh giá nếu user đã mua sản phẩm
  if (isset($_SESSION['user']['id']) && Product::hasUserPurchased($conn, $_SESSION['user']['id'], $product['id'])):
  ?>
  <div class="review-form mt-4">
      <h5>Gửi đánh giá của bạn</h5>
      <form method="POST" action="index.php?controller=product&action=review&id=<?php echo $product['id']; ?>">
          <div class="form-group">
              <label for="rating">Đánh giá:</label>
              <select name="rating" id="rating" class="form-control" required>
                  <option value="">Chọn số sao</option>
                  <?php for ($i = 5; $i >= 1; $i--): ?>
                      <option value="<?php echo $i; ?>"><?php echo $i; ?> sao</option>
                  <?php endfor; ?>
              </select>
          </div>
          <div class="form-group">
              <label for="comment">Bình luận:</label>
              <textarea name="comment" id="comment" class="form-control" rows="3" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
      </form>
  </div>
  <?php endif; ?>
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
<?php if (isset($_GET['msg']) && $_GET['msg'] === 'review_sent'): ?>
    <div class="alert alert-success">Đánh giá của bạn đã được gửi và chờ duyệt!</div>
<?php endif; ?>
<?php if (isset($_GET['error'])): ?>
    <?php if ($_GET['error'] === 'review_failed'): ?>
        <div class="alert alert-danger">Có lỗi khi gửi đánh giá, vui lòng thử lại.</div>
    <?php elseif ($_GET['error'] === 'not_purchased'): ?>
        <div class="alert alert-danger">Bạn chỉ có thể đánh giá sản phẩm đã mua.</div>
    <?php elseif ($_GET['error'] === 'invalid_review'): ?>
        <div class="alert alert-danger">Vui lòng nhập đầy đủ thông tin đánh giá.</div>
    <?php endif; ?>
<?php endif; ?>
<?php endif; ?> 