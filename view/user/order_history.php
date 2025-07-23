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
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?php echo $order['id']; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                        <td><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ</td>
                        <td>
                            <span class="badge bg-<?php echo getStatusColor($order['status']); ?>">
                                <?php echo getStatusText($order['status']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="index.php?controller=checkout&action=orderDetail&id=<?php echo $order['id']; ?>" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye"></i> Xem
                            </a>
                            <?php if ($order['status'] === 'pending'): ?>
                                <button class="btn btn-outline-danger btn-sm" onclick="cancelOrder(<?php echo $order['id']; ?>)">
                                    <i class="fas fa-times"></i> Hủy
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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
.table th, .table td {
    vertical-align: middle;
    text-align: center;
}
.badge {
    font-size: 0.85rem;
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