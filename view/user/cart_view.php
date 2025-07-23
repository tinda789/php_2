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
                  require_once 'helpers/image_helper.php';
                  $img = !empty($item['images'][0]['image_url']) ? $item['images'][0]['image_url'] : '';
                  $img_url = getImageUrl($img);
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
                <form method="POST" action="index.php?controller=cart&action=update" class="d-flex justify-content-center align-items-center gap-1">
                  <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                  <input type="number" name="quantity" class="form-control text-center" value="<?php echo $item['quantity']; ?>" min="1" style="max-width:60px;">
                  <button type="submit" class="btn btn-outline-primary btn-sm" title="Cập nhật số lượng"><i class="fas fa-sync-alt"></i></button>
                </form>
              </td>
              <td class="fw-bold text-success"><?php echo number_format($subtotal, 0, ',', '.'); ?> đ</td>
              <td>
                <a href="index.php?controller=cart&action=remove&product_id=<?php echo $item['id']; ?>" class="btn btn-outline-danger btn-sm rounded-circle" title="Xóa khỏi giỏ hàng" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?');"><i class="fas fa-trash"></i></a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <th colspan="5" class="text-end fs-5">Tổng cộng:</th>
            <th class="text-danger fs-4 text-center"><?php echo number_format($total, 0, ',', '.'); ?> đ</th>
            <th></th>
          </tr>
        </tfoot>
      </table>
    </div>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-4">
      <a href="index.php?controller=product&action=list" class="btn btn-outline-primary btn-lg"><i class="fas fa-arrow-left"></i> Tiếp tục mua sắm</a>
      <button type="submit" class="btn btn-success btn-lg px-5"><i class="fas fa-credit-card"></i> Thanh toán</button>
    </div>
    </form>
  <?php endif; ?>
</div>
<script>
function toggleAllCartCheckboxes(source) {
  var checkboxes = document.querySelectorAll('.cart-checkbox');
  for (var i = 0; i < checkboxes.length; i++) {
    checkboxes[i].checked = source.checked;
  }
}
</script>
<style>
.table thead th { vertical-align: middle; }
.object-fit-cover { object-fit: cover; }
input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { opacity: 1; }
</style> 