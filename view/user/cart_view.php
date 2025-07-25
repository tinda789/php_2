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
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <input type="text" id="coupon_code" name="coupon_code" class="form-control" placeholder="Nhập mã giảm giá" value="<?php echo isset($cart['coupon']) ? htmlspecialchars($cart['coupon']['code']) : ''; ?>">
                <?php if (isset($cart['coupon'])): ?>
                    <button type="button" class="btn btn-danger" id="remove-coupon">
                        <i class="fas fa-times"></i> Xóa
                    </button>
                <?php else: ?>
                    <button type="button" class="btn btn-primary" id="apply-coupon">
                        <i class="fas fa-tag"></i> Áp dụng
                    </button>
                <?php endif; ?>
            </div>
            <div id="coupon-message" class="mt-2">
                <?php if (isset($cart['coupon'])): ?>
                    <div class="alert alert-success py-2 mb-0">
                        <i class="fas fa-check-circle"></i> Đã áp dụng mã <strong><?php echo htmlspecialchars($cart['coupon']['code']); ?></strong> - Giảm <?php echo $cart['coupon']['formatted_discount']; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
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
          <?php 
          $total = 0;
          $has_out_of_stock = false;
          $cart_items = is_array($cart) ? $cart : [];
          
          // Filter out non-array items (like 'summary' key)
          $cart_items = array_filter($cart_items, function($item) {
              return is_array($item) && isset($item['id']);
          });
          ?>
          <?php foreach ($cart_items as $item): 
            if (!is_array($item)) continue;
            
            $item = array_merge([
                'id' => null,
                'name' => 'Sản phẩm không xác định',
                'price' => 0,
                'sale_price' => 0,
                'quantity' => 0,
                'stock' => 0,
                'sku' => '',
                'images' => []
            ], $item);
            
            $is_out_of_stock = ($item['stock'] ?? 0) <= 0;
            $is_insufficient_stock = ($item['stock'] ?? 0) < ($item['quantity'] ?? 0);
            $is_invalid = $is_out_of_stock || $is_insufficient_stock;
            if ($is_invalid) $has_out_of_stock = true;
            
            $price = $item['sale_price'] > 0 ? $item['sale_price'] : $item['price'];
            $subtotal = $price * $item['quantity']; 
            $total += $subtotal; 
          ?>
            <tr class="align-middle text-center <?php echo $is_invalid ? 'table-warning position-relative' : ''; ?>" <?php echo $is_invalid ? 'title="Sản phẩm ' . ($is_out_of_stock ? 'đã hết hàng' : 'không đủ số lượng yêu cầu') . '"' : ''; ?>>
              <?php if ($is_invalid): ?>
                <div class="position-absolute top-0 end-0 bg-danger text-white px-2 py-1 rounded-start" style="z-index: 1; transform: translateY(-50%);">
                  <i class="fas fa-exclamation-triangle me-1"></i>
                  <?php echo $is_out_of_stock ? 'Hết hàng' : 'Còn ' . $item['stock'] . ' sản phẩm'; ?>
                </div>
              <?php endif; ?>
              <td>
                <?php if ($is_invalid): ?>
                  <i class="fas fa-exclamation-circle text-danger" title="Sản phẩm <?php echo $is_out_of_stock ? 'đã hết hàng' : 'không đủ số lượng' ?>"></i>
                <?php else: ?>
                  <input type="checkbox" name="selected_products[]" value="<?php echo $item['id']; ?>" class="cart-checkbox">
                <?php endif; ?>
              </td>
              <td>
                <div class="ratio ratio-4x3 bg-light rounded-3 overflow-hidden mx-auto" style="width:90px;">
                  <?php 
                  // thanhdat: xử lý lấy ảnh sản phẩm tối ưu
                  $img_url = 'https://via.placeholder.com/90x68?text=No+Image';
                  
                  if (!empty($item['images']) && is_array($item['images'])) {
                      require_once 'helpers/image_helper.php';
                      $img = '';
                      
                      if (!empty($item['images'][0]) && is_array($item['images'][0])) {
                          $img = $item['images'][0]['image_url'] ?? $item['images'][0]['url'] ?? $item['images'][0]['image'] ?? '';
                      } elseif (!empty($item['images'][0])) {
                          $img = $item['images'][0];
                      }
                      
                      if (!empty($img)) {
                          $temp_url = getImageUrl($img);
                          if ($temp_url) $img_url = $temp_url;
                      }
                  }
                  ?>
                  <img src="<?php echo htmlspecialchars($img_url ?? ''); ?>" alt="<?php echo htmlspecialchars($item['name'] ?? 'Sản phẩm'); ?>" class="img-fluid h-100 w-100 object-fit-cover rounded-3">
                </div>
              </td>
              <td class="text-start">
                <div class="fw-semibold text-dark">
                  <?php echo htmlspecialchars($item['name']); ?>
                  <?php if ($is_invalid): ?>
                    <span class="badge bg-danger ms-2"><?php echo $is_out_of_stock ? 'Hết hàng' : 'Còn ' . $item['stock'] . ' sản phẩm'; ?></span>
                  <?php endif; ?>
                </div>
                <div class="small text-muted">SKU: <?php echo htmlspecialchars($item['sku'] ?? ''); ?></div>
                <?php if ($is_invalid): ?>
                  <div class="text-danger small mt-1">
                    <i class="fas fa-exclamation-triangle"></i> 
                    <?php echo $is_out_of_stock ? 'Sản phẩm tạm hết hàng' : 'Chỉ còn ' . $item['stock'] . ' sản phẩm trong kho'; ?>
                  </div>
                <?php endif; ?>
              </td>
              <td class="text-danger fw-bold"><?php echo number_format($item['price'], 0, ',', '.'); ?> đ</td>
              <td>
                <div class="d-flex justify-content-center align-items-center gap-1">
                  <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                  <input type="number" 
                         name="quantity" 
                         class="form-control text-center quantity-input <?php echo $is_invalid ? 'is-invalid' : ''; ?>" 
                         value="<?php echo min($item['quantity'], $is_out_of_stock ? 0 : ($item['stock'] ?? 0)); ?>" 
                         min="1" 
                         max="<?php echo $is_out_of_stock ? '0' : ($item['stock'] ?? ''); ?>"
                         style="max-width:60px;"
                         data-product-id="<?php echo $item['id']; ?>"
                         data-price="<?php echo $item['price']; ?>"
                         <?php echo $is_invalid ? 'disabled' : ''; ?>>
                  <button type="button" 
                          class="btn btn-outline-<?php echo $is_invalid ? 'secondary' : 'primary'; ?> btn-sm update-quantity" 
                          title="<?php echo $is_invalid ? 'Không thể cập nhật số lượng' : 'Cập nhật số lượng'; ?>" 
                          data-product-id="<?php echo $item['id']; ?>"
                          <?php echo $is_invalid ? 'disabled' : ''; ?>>
                    <i class="fas <?php echo $is_invalid ? 'fa-ban' : 'fa-sync-alt'; ?>"></i>
                  </button>
                </div>
              </td>
              <td class="fw-bold text-success product-subtotal" data-product-id="<?php echo $item['id']; ?>">
                <?php echo number_format($subtotal, 0, ',', '.'); ?> đ
              </td>
              <td class="text-end">
                <a href="javascript:void(0)" 
                   class="btn btn-outline-danger btn-sm remove-from-cart" 
                   data-product-id="<?php echo $item['id']; ?>"
                   title="Xóa sản phẩm">
                  <i class="fas fa-trash-alt"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <?php if (isset($cart['coupon'])): ?>
          <tr>
            <td colspan="5" class="text-end">
                <div class="text-success">
                    <i class="fas fa-tag"></i> Giảm giá (<?php echo htmlspecialchars($cart['coupon']['name']); ?>):
                </div>
            </td>
            <td class="text-success fw-bold text-center">-<?php echo $cart['coupon']['formatted_discount']; ?></td>
            <td></td>
          </tr>
          <?php endif; ?>
          <tr class="border-top">
            <th colspan="5" class="text-end fs-5">Tổng cộng:</th>
            <th class="text-danger fs-4 text-center" id="cart-total">
                <?php 
                $formatted_total = number_format($total, 0, ',', '.') . ' đ';
                // Check if we have a coupon discount
                if (isset($cart['coupon']) && is_array($cart['coupon'])) {
                    $coupon = $cart['coupon'];
                    $discount = 0;
                    
                    if ($coupon['type'] === 'fixed') {
                        $discount = min($coupon['value'], $total);
                    } else {
                        $discount = $total * ($coupon['value'] / 100);
                        if (isset($coupon['maximum_discount']) && $discount > $coupon['maximum_discount']) {
                            $discount = $coupon['maximum_discount'];
                        }
                    }
                    
                    $total_after_discount = $total - $discount;
                    if ($total_after_discount < 0) $total_after_discount = 0;
                    
                    $formatted_total = number_format($total_after_discount, 0, ',', '.') . ' đ';
                }
                echo $formatted_total;
                ?>
            </th>
            <th></th>
          </tr>
        </tfoot>
      </table>
    </div>
    <?php if ($has_out_of_stock): ?>
      <div class="alert alert-warning">
        <div class="d-flex align-items-center">
          <i class="fas fa-exclamation-triangle me-2"></i>
          <span>Không thể thanh toán khi có sản phẩm hết hàng hoặc không đủ số lượng. Vui lòng xóa hoặc cập nhật lại giỏ hàng.</span>
        </div>
      </div>
    <?php endif; ?>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-4">
      <a href="index.php?controller=product&action=list" class="btn btn-outline-primary btn-lg"><i class="fas fa-arrow-left"></i> Tiếp tục mua sắm</a>
      <div class="d-flex flex-column flex-md-row gap-3">
        <a href="index.php?controller=cart&action=clear" class="btn btn-outline-danger btn-lg" onclick="return confirm('Bạn có chắc chắn muốn xóa tất cả sản phẩm khỏi giỏ hàng?')">
          <i class="fas fa-trash-alt"></i> Xóa giỏ hàng
        </a>
        <button type="submit" class="btn btn-success btn-lg px-5" id="checkout-button" <?php echo $has_out_of_stock ? 'disabled' : ''; ?>>
          <i class="fas fa-credit-card"></i> Thanh toán VNPAY
        </button>
      </div>
    </div>
    <?php if ($has_out_of_stock): ?>
        <div class="alert alert-warning mt-2 mb-0" role="alert">
          <i class="fas fa-exclamation-triangle"></i> Vui lòng xử lý các sản phẩm hết hàng hoặc không đủ số lượng trước khi thanh toán.
        </div>
      <?php endif; ?>
    </div>
    </form>
  <?php endif; ?>
</div>

<script>
// Handle remove from cart
$(document).on('click', '.remove-from-cart', function(e) {
    e.preventDefault();
    
    const productId = $(this).data('product-id');
    const $row = $(this).closest('tr');
    
    if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
        $.ajax({
            url: 'index.php?controller=cart&action=remove&id=' + productId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Remove the row with animation
                    $row.fadeOut(300, function() {
                        $(this).remove();
                        updateCartTotals();
                        
                        // If cart is empty, reload the page
                        if ($('.cart-checkbox').length === 0) {
                            window.location.reload();
                        }
                    });
                    
                    // Show success message
                    showToast('success', response.message);
                } else {
                    showToast('error', response.message || 'Có lỗi xảy ra khi xóa sản phẩm');
                }
            },
            error: function() {
                showToast('error', 'Lỗi kết nối. Vui lòng thử lại sau.');
            }
        });
    }
});

// Update cart totals after removing an item
function updateCartTotals() {
    let subtotal = 0;
    
    // Recalculate subtotal
    $('.product-subtotal').each(function() {
        const itemTotal = parseInt($(this).text().replace(/[^0-9]/g, ''));
        subtotal += isNaN(itemTotal) ? 0 : itemTotal;
    });
    
    // Update total
    $('#cart-total').text(subtotal.toLocaleString('vi-VN') + ' đ');
}

// Show toast message
function showToast(type, message) {
    // Create toast if not exists
    if ($('#toast-container').length === 0) {
        $('body').append('<div id="toast-container" class="position-fixed bottom-0 end-0 p-3" style="z-index: 11"></div>');
    }
    
    const toastId = 'toast-' + Date.now();
    const toastClass = type === 'success' ? 'bg-success' : 'bg-danger';
    
    const toast = `
        <div id="${toastId}" class="toast align-items-center text-white ${toastClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    $('#toast-container').append(toast);
    const toastElement = $(`#${toastId}`);
    const bsToast = new bootstrap.Toast(toastElement);
    bsToast.show();
    
    // Remove toast after 3 seconds
    setTimeout(() => {
        toastElement.remove();
    }, 3000);
}

// Coupon functionality
$(document).ready(function() {
    // Apply coupon
    $('#apply-coupon').on('click', function() {
        const couponCode = $('#coupon_code').val().trim();
        
        if (!couponCode) {
            showCouponMessage('Vui lòng nhập mã giảm giá', 'danger');
            return;
        }
        
        $.ajax({
            url: 'index.php?controller=cart&action=applyCoupon',
            type: 'POST',
            data: { coupon_code: couponCode },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Reload the page to update cart totals
                    window.location.reload();
                } else {
                    showCouponMessage(response.message, 'danger');
                }
            },
            error: function() {
                showCouponMessage('Có lỗi xảy ra, vui lòng thử lại', 'danger');
            }
        });
    });
    
    // Remove coupon
    $('#remove-coupon').on('click', function() {
        $.ajax({
            url: 'index.php?controller=cart&action=removeCoupon',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    window.location.reload();
                } else {
                    showCouponMessage(response.message, 'danger');
                }
            },
            error: function() {
                showCouponMessage('Có lỗi xảy ra, vui lòng thử lại', 'danger');
            }
        });
    });
    
    // Handle Enter key in coupon input
    $('#coupon_code').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            if ($('#apply-coupon').length) {
                $('#apply-coupon').click();
            }
        }
    });
    
    function showCouponMessage(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        $('#coupon-message').html(`
            <div class="alert ${alertClass} py-2 mb-0">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i> ${message}
            </div>
        `);
    }
});
</script>

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
  let hasInvalidItems = false;
  
  // Duyệt qua tất cả các sản phẩm được chọn
  document.querySelectorAll('.cart-checkbox:checked').forEach(checkbox => {
    const productId = checkbox.value;
    const quantityInput = document.querySelector(`.quantity-input[data-product-id="${productId}"]`);
    
    // Bỏ qua nếu input không tồn tại hoặc bị vô hiệu hóa (sản phẩm hết hàng)
    if (!quantityInput || quantityInput.disabled) {
      hasInvalidItems = true;
      return;
    }
    
    const quantity = parseInt(quantityInput.value) || 0;
    const price = parseFloat(quantityInput.dataset.price) || 0;
    const subtotal = price * quantity;
    
    // Cập nhật tổng tiền cho từng sản phẩm
    const subtotalElement = document.querySelector(`.product-subtotal[data-product-id="${productId}"]`);
    if (subtotalElement) {
      subtotalElement.textContent = subtotal.toLocaleString('vi-VN') + ' đ';
      subtotalElement.classList.toggle('text-decoration-line-through', quantityInput.disabled);
    }
    
    total += subtotal;
  });
  
  // Cập nhật tổng tiền
  const totalElement = document.getElementById('cart-total');
  totalElement.textContent = total.toLocaleString('vi-VN') + ' đ';
  
  // Vô hiệu hóa nút thanh toán nếu có sản phẩm không hợp lệ được chọn
  const checkoutButton = document.getElementById('checkout-button');
  if (checkoutButton) {
    checkoutButton.disabled = hasInvalidItems;
    
    // Hiển thị thông báo nếu cần
    const warningMessage = document.querySelector('.checkout-warning');
    if (warningMessage) {
      warningMessage.style.display = hasInvalidItems ? 'block' : 'none';
    }
  }
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
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
  opacity: 1;
  margin-left: 5px;
}

/* Tùy chỉnh giao diện cho sản phẩm hết hàng */
.table-warning {
  background-color: #fff3cd !important;
  position: relative;
  opacity: 0.8;
}

.table-warning:hover {
  background-color: #ffe69c !important;
  opacity: 1;
}

.quantity-input:disabled {
  background-color: #f8f9fa;
  cursor: not-allowed;
}

.text-decoration-line-through {
  text-decoration: line-through;
  opacity: 0.7;
}

/* Hiệu ứng khi di chuột vào nút */
.btn-outline-secondary:disabled {
  cursor: not-allowed;
}

/* Tùy chỉnh thanh cuộn cho bảng */
.table-responsive {
  max-height: 70vh;
  overflow-y: auto;
}

/* Tùy chỉnh thanh cuộn cho trình duyệt Chrome */
.table-responsive::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>