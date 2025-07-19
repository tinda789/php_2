<?php /* @var $order */ /* @var $orderItems */ ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary mb-0">
            <i class="fas fa-receipt"></i> Chi tiết đơn hàng #<?php echo $order['id']; ?>
        </h2>
        <a href="index.php?controller=checkout&action=orderHistory" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>
    
    <div class="row">
        <!-- Thông tin đơn hàng -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Sản phẩm đã đặt</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Đơn giá</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total = 0; ?>
                                <?php foreach ($orderItems as $item): ?>
                                    <?php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <?php 
                                                    $images = json_decode($item['images'], true);
                                                    $img = !empty($images[0]) ? $images[0] : '';
                                                    if ($img && strpos($img, 'uploads/') === false && strpos($img, 'http') !== 0) {
                                                        $img = 'uploads/products/' . $img;
                                                    }
                                                    if (!$img) $img = 'https://via.placeholder.com/60x60?text=No+Image';
                                                    ?>
                                                    <img src="<?php echo htmlspecialchars($img); ?>" 
                                                         alt="<?php echo htmlspecialchars($item['product_name']); ?>" 
                                                         class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                                </div>
                                                <div>
                                                    <div class="fw-semibold"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                                    <small class="text-muted">SKU: <?php echo $item['product_id']; ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center"><?php echo $item['quantity']; ?></td>
                                        <td class="text-end"><?php echo number_format($item['price'], 0, ',', '.'); ?> đ</td>
                                        <td class="text-end fw-bold text-success"><?php echo number_format($subtotal, 0, ',', '.'); ?> đ</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <th colspan="3" class="text-end">Tổng cộng:</th>
                                    <th class="text-end text-danger fs-5"><?php echo number_format($total, 0, ',', '.'); ?> đ</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Thông tin giao hàng -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-shipping-fast"></i> Thông tin giao hàng</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong><i class="fas fa-map-marker-alt"></i> Địa chỉ:</strong>
                        <div class="text-muted mt-1"><?php echo htmlspecialchars($order['shipping_address']); ?></div>
                    </div>
                    <div class="mb-3">
                        <strong><i class="fas fa-phone"></i> Số điện thoại:</strong>
                        <div class="text-muted mt-1"><?php echo htmlspecialchars($order['phone']); ?></div>
                    </div>
                    <div class="mb-3">
                        <strong><i class="fas fa-credit-card"></i> Phương thức thanh toán:</strong>
                        <div class="text-muted mt-1"><?php echo getPaymentMethodText($order['payment_method']); ?></div>
                    </div>
                    <?php if (!empty($order['note'])): ?>
                        <div class="mb-3">
                            <strong><i class="fas fa-sticky-note"></i> Ghi chú:</strong>
                            <div class="text-muted mt-1"><?php echo htmlspecialchars($order['note']); ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Trạng thái đơn hàng -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Trạng thái đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Trạng thái hiện tại:</strong>
                        <div class="mt-2">
                            <span class="badge bg-<?php echo getStatusColor($order['status']); ?> fs-6">
                                <?php echo getStatusText($order['status']); ?>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong>Ngày đặt hàng:</strong>
                        <div class="text-muted mt-1">
                            <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong>Tổng tiền:</strong>
                        <div class="text-danger fw-bold fs-5 mt-1">
                            <?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ
                        </div>
                    </div>
                    
                    <?php if ($order['status'] === 'pending'): ?>
                        <div class="d-grid">
                            <button class="btn btn-outline-danger" onclick="cancelOrder(<?php echo $order['id']; ?>)">
                                <i class="fas fa-times"></i> Hủy đơn hàng
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function cancelOrder(orderId) {
    if (confirm('Bạn có chắc muốn hủy đơn hàng này?')) {
        fetch('index.php?controller=checkout&action=cancelOrder', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'order_id=' + orderId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Đã hủy đơn hàng thành công!');
                location.reload();
            } else {
                alert('Có lỗi xảy ra: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi hủy đơn hàng!');
        });
    }
}
</script>

<style>
.card {
    border-radius: 12px;
    overflow: hidden;
}
.card-header {
    border-bottom: none;
    padding: 1rem 1.5rem;
}
.badge {
    padding: 0.75rem 1rem;
}
.btn {
    border-radius: 8px;
    font-weight: 500;
}
.table th {
    border-top: none;
    font-weight: 600;
}
</style>

<?php
function getStatusColor($status) {
    $colors = [
        'pending' => 'warning',
        'processing' => 'info',
        'shipped' => 'primary',
        'delivered' => 'success',
        'cancelled' => 'danger'
    ];
    return $colors[$status] ?? 'secondary';
}

function getStatusText($status) {
    $texts = [
        'pending' => 'Chờ xử lý',
        'processing' => 'Đang xử lý',
        'shipped' => 'Đã gửi hàng',
        'delivered' => 'Đã giao hàng',
        'cancelled' => 'Đã hủy'
    ];
    return $texts[$status] ?? $status;
}

function getPaymentMethodText($method) {
    $texts = [
        'cod' => 'Thanh toán khi nhận hàng',
        'bank' => 'Chuyển khoản ngân hàng',
        'vnpay' => 'VNPAY'
    ];
    return $texts[$method] ?? $method;
}
?> 