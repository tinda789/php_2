<?php /* @var $cart */ ?>
<div class="container py-4">
  <h2 class="mb-4 text-center fw-bold text-primary"><i class="fas fa-shopping-cart"></i> Giỏ hàng của bạn</h2>
  <?php if (empty($cart)): ?>
    <div class="alert alert-info text-center">Giỏ hàng của bạn đang trống.</div>
    <div class="text-center">
      <a href="index.php?controller=product&action=list" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Tiếp tục mua sắm</a>
    </div>
  <?php else: ?>
    <form method="post" action="index.php?controller=checkout&action=checkout">
    <div class="table-responsive">
      <table class="table table-hover align-middle shadow-sm rounded-4 overflow-hidden">
        <thead class="table-primary">
          <tr class="align-middle text-center">
            <th style="width:40px;"><input type="checkbox" id="checkAll" onclick="toggleAllCartCheckboxes(this)"></th>
            <th style="width:110px;">Ảnh</th>
            <th>Tên sản phẩm</th>
            <th>Giá</th>
            <th style="width:120px;">Số lượng</th>
            <th>Thành tiền</th>
            <th style="width:60px;"></th>
          </tr>
        </thead>
        <tbody>
          <?php $total = 0; ?>
          <?php foreach ($cart as $item): ?>
            <?php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; ?>
            <tr class="align-middle text-center">
              <td><input type="checkbox" name="selected_products[]" value="<?php echo $item['id']; ?>" class="cart-checkbox"></td>
              <td>
                <div class="ratio ratio-4x3 bg-light rounded-3 overflow-hidden mx-auto" style="width:90px;">
                  <?php 
                  // thanhdat: xử lý lấy ảnh sản phẩm tối ưu
                  require_once 'helpers/image_helper.php'; // thanhdat
                  $img = '';
                  if (!empty($item['images'][0]) && is_array($item['images'][0])) { // thanhdat
                      $img = $item['images'][0]['image_url'] ?? $item['images'][0]['url'] ?? $item['images'][0]['image'] ?? '';
                  } elseif (!empty($item['images'][0])) { // thanhdat
                      $img = $item['images'][0]; // thanhdat
                  }
                  $img_url = getImageUrl($img); // thanhdat
                  if (!$img_url) $img_url = 'https://via.placeholder.com/90x68?text=No+Image'; // thanhdat
                  ?>
                  <img src="<?php echo htmlspecialchars($img_url); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="img-fluid h-100 w-100 object-fit-cover rounded-3">
                </div>
              </td>
              <td class="text-start">
                <div class="fw-semibold text-dark"><?php echo htmlspecialchars($item['name']); ?></div>
                <div class="small text-muted">SKU: <?php echo htmlspecialchars($item['sku'] ?? ''); ?></div>
              </td>
              <td class="text-danger fw-bold"><?php echo number_format($item['price'], 0, ',', '.'); ?> đ</td>
              <td>
                <div class="d-flex justify-content-center align-items-center gap-1">
                  <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                  <input type="number" 
                         name="quantity" 
                         class="form-control text-center quantity-input" 
                         value="<?php echo $item['quantity']; ?>" 
                         min="1" 
                         style="max-width:60px;"
                         data-product-id="<?php echo $item['id']; ?>"
                         data-price="<?php echo $item['price']; ?>">
                  <button type="button" 
                          class="btn btn-outline-primary btn-sm update-quantity" 
                          title="Cập nhật số lượng" 
                          data-product-id="<?php echo $item['id']; ?>">
                    <i class="fas fa-sync-alt"></i>
                  </button>
                </div>
              </td>
              <td class="fw-bold text-success product-subtotal" data-product-id="<?php echo $item['id']; ?>">
                <?php echo number_format($subtotal, 0, ',', '.'); ?> đ
              </td>
              <td>
                <a href="index.php?controller=cart&action=remove&product_id=<?php echo $item['id']; ?>" class="btn btn-outline-danger btn-sm rounded-circle" title="Xóa khỏi giỏ hàng" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?');"><i class="fas fa-trash"></i></a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <th colspan="5" class="text-end fs-5">Tổng cộng:</th>
            <th class="text-danger fs-4 text-center" id="cart-total"><?php echo number_format($total, 0, ',', '.'); ?> đ</th>
            <th></th>
          </tr>
        </tfoot>
      </table>
    </div>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-4">
      <a href="index.php?controller=product&action=list" class="btn btn-outline-primary btn-lg"><i class="fas fa-arrow-left"></i> Tiếp tục mua sắm</a>
      <button type="submit" class="btn btn-success btn-lg px-5"><i class="fas fa-credit-card"></i> Thanh toán VNPAY</button>
    </div>
    </form>
  <?php endif; ?>
</div>
<script>
// Hàm bật/tắt tất cả checkbox
function toggleAllCartCheckboxes(source) {
  var checkboxes = document.querySelectorAll('.cart-checkbox');
  for (var i = 0; i < checkboxes.length; i++) {
    checkboxes[i].checked = source.checked;
  }
  updateSelectedTotal();
}

// Hàm cập nhật tổng tiền của các sản phẩm được chọn
function updateSelectedTotal() {
  let total = 0;
  
  // Duyệt qua tất cả các sản phẩm được chọn
  document.querySelectorAll('.cart-checkbox:checked').forEach(checkbox => {
    const productId = checkbox.value;
    const quantity = parseInt(document.querySelector(`.quantity-input[data-product-id="${productId}"]`).value) || 0;
    const price = parseFloat(document.querySelector(`.quantity-input[data-product-id="${productId}"]`).dataset.price) || 0;
    const subtotal = price * quantity;
    
    // Cập nhật tổng tiền cho từng sản phẩm
    const subtotalElement = document.querySelector(`.product-subtotal[data-product-id="${productId}"]`);
    if (subtotalElement) {
      subtotalElement.textContent = subtotal.toLocaleString('vi-VN') + ' đ';
    }
    
    total += subtotal;
  });
  
  // Cập nhật tổng tiền
  document.getElementById('cart-total').textContent = total.toLocaleString('vi-VN') + ' đ';
}

// Xử lý sự kiện khi thay đổi số lượng
function handleQuantityChange(input) {
  const productId = input.dataset.productId;
  const quantity = parseInt(input.value) || 1;
  
  // Đảm bảo số lượng tối thiểu là 1
  if (quantity < 1) {
    input.value = 1;
  }
  
  // Cập nhật tổng tiền
  updateSelectedTotal();
  
  // Gửi yêu cầu cập nhật số lượng lên server
  updateCartOnServer(productId, quantity);
}

// Gửi yêu cầu cập nhật số lượng lên server
function updateCartOnServer(productId, quantity) {
  const formData = new FormData();
  formData.append('product_id', productId);
  formData.append('quantity', quantity);
  
  fetch('index.php?controller=cart&action=update', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Cập nhật thành công
      console.log('Cập nhật giỏ hàng thành công');
    } else {
      console.error('Lỗi khi cập nhật giỏ hàng:', data.message || 'Lỗi không xác định');
    }
  })
  .catch(error => {
    console.error('Lỗi khi gửi yêu cầu:', error);
  });
}

// Thêm sự kiện khi tài liệu được tải xong
document.addEventListener('DOMContentLoaded', function() {
  // Xử lý sự kiện thay đổi số lượng
  document.querySelectorAll('.quantity-input').forEach(input => {
    input.addEventListener('change', function() {
      handleQuantityChange(this);
    });
    
    input.addEventListener('blur', function() {
      if (this.value < 1) {
        this.value = 1;
        handleQuantityChange(this);
      }
    });
  });
  
  // Xử lý sự kiện click nút cập nhật
  document.querySelectorAll('.update-quantity').forEach(button => {
    button.addEventListener('click', function() {
      const productId = this.dataset.productId;
      const input = document.querySelector(`.quantity-input[data-product-id="${productId}"]`);
      handleQuantityChange(input);
    });
  });
  
  // Xử lý sự kiện checkbox
  document.querySelectorAll('.cart-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateSelectedTotal);
  });
  
  // Khởi tạo tổng tiền ban đầu
  updateSelectedTotal();
});
</script>
<style>
.table thead th { vertical-align: middle; }
.object-fit-cover { object-fit: cover; }
input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { opacity: 1; }
</style>