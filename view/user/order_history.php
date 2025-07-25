<?php include 'view/layout/header.php'; ?>
<?php /* @var $orders */ ?>
<div class="container py-4">
    <h2 class="mb-4 text-center fw-bold text-primary">
        <i class="fas fa-history"></i> Lịch sử đơn hàng
    </h2>
    
    <?php if (empty($orders)): ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Bạn chưa có đơn hàng nào.
            <br>
            <a href="index.php?controller=product&action=list" class="btn btn-primary mt-2">
                <i class="fas fa-shopping-cart"></i> Mua sắm ngay
            </a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($orders as $order): ?>
                <div class="col-12 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 fw-bold">
                                    <i class="fas fa-receipt"></i> Đơn hàng #<?php echo $order['id']; ?>
                                </h6>
                                <small class="text-muted">
                                    <i class="fas fa-calendar"></i> 
                                    <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                                </small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-danger fs-5">
                                    <?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ
                                </div>
                                <span class="badge bg-<?php echo getStatusColor($order['status']); ?>">
                                    <?php echo getStatusText($order['status']); ?>
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <strong><i class="fas fa-map-marker-alt"></i> Địa chỉ:</strong>
                                        <div class="text-muted"><?php echo htmlspecialchars($order['shipping_address']); ?></div>
                                    </div>
                                    <div class="mb-2">
                                        <strong><i class="fas fa-phone"></i> Số điện thoại:</strong>
                                        <div class="text-muted"><?php echo htmlspecialchars($order['phone']); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <strong><i class="fas fa-credit-card"></i> Thanh toán:</strong>
                                        <div class="text-muted"><?php echo getPaymentMethodText($order['payment_method']); ?></div>
                                    </div>
                                    <?php if (!empty($order['note'])): ?>
                                        <div class="mb-2">
                                            <strong><i class="fas fa-sticky-note"></i> Ghi chú:</strong>
                                            <div class="text-muted"><?php echo htmlspecialchars($order['note']); ?></div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Product Images -->
                            <?php if (!empty($order['items'])): ?>
                            <div class="mt-3">
                                <strong><i class="fas fa-box"></i> Sản phẩm:</strong>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    <?php foreach ($order['items'] as $item): 
                                        $image_url = !empty($item['image_url']) ? $item['image_url'] : 'assets/images/no-image.png';
                                        // Ensure the image URL is correct
                                        if (!preg_match('/^https?:\/\//', $image_url) && strpos($image_url, 'uploads/') !== 0) {
                                            $image_url = 'uploads/products/' . $image_url;
                                        }
                                    ?>
                                    <div class="position-relative" style="width: 50px; height: 50px; border-radius: 4px; overflow: hidden;">
                                        <img src="<?php echo $image_url; ?>" 
                                             alt="<?php echo htmlspecialchars($item['product_name']); ?>" 
                                             class="img-fluid h-100 w-100" 
                                             style="object-fit: cover;"
                                             onerror="this.onerror=null; this.src='assets/images/no-image.png';">
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary" style="font-size: 0.6rem;">
                                            <?php echo $item['quantity']; ?>
                                        </span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <a href="index.php?controller=checkout&action=orderDetail&id=<?php echo $order['id']; ?>" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye"></i> Xem chi tiết
                                </a>
                                <?php if ($order['status'] === 'pending'): ?>
                                    <button class="btn btn-outline-danger btn-sm" 
                                            onclick="cancelOrder(<?php echo $order['id']; ?>)">
                                        <i class="fas fa-times"></i> Hủy đơn hàng
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
function cancelOrder(orderId) {
    if (confirm('Bạn có chắc muốn hủy đơn hàng này?')) {
        // Gửi request hủy đơn hàng
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
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1) !important;
}
.card-header {
    border-bottom: none;
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}
.badge {
    font-size: 0.75rem;
    padding: 0.5rem 0.75rem;
}
.btn {
    border-radius: 8px;
    font-weight: 500;
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