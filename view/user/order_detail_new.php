<?php
// Kiểm tra quyền truy cập
if (!isset($_SESSION['user'])) {
    header('Location: index.php?controller=user&action=login');
    exit();
}

// Lấy thông tin đơn hàng từ controller
$order = $data['order'] ?? [];
$orderItems = $data['orderItems'] ?? [];

// Hàm chuyển đổi trạng thái
function getStatusText($status) {
    $statuses = [
        'pending' => 'Chờ xử lý',
        'processing' => 'Đang xử lý',
        'shipped' => 'Đang giao hàng',
        'delivered' => 'Đã giao hàng',
        'cancelled' => 'Đã hủy'
    ];
    return $statuses[$status] ?? 'Không xác định';
}

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

function getStatusIcon($status) {
    $icons = [
        'pending' => 'clock',
        'processing' => 'sync-alt',
        'shipped' => 'truck',
        'delivered' => 'check-circle',
        'cancelled' => 'times-circle'
    ];
    return $icons[$status] ?? 'info-circle';
}

function getPaymentMethodText($method) {
    $methods = [
        'cod' => 'Thanh toán khi nhận hàng (COD)',
        'bank' => 'Chuyển khoản ngân hàng',
        'vnpay' => 'Thanh toán qua VNPAY'
    ];
    return $methods[$method] ?? $method;
}
?>

<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="?controller=user&action=orders">Đơn hàng của tôi</a></li>
            <li class="breadcrumb-item active" aria-current="page">Đơn hàng #<?php echo $order['id']; ?></li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Chi tiết đơn hàng #<?php echo $order['id']; ?></h2>
        <span class="badge bg-<?php echo getStatusColor($order['status']); ?> bg-opacity-10 text-<?php echo getStatusColor($order['status']); ?> px-3 py-2 rounded-pill">
            <i class="fas fa-<?php echo getStatusIcon($order['status']); ?> me-2"></i>
            <?php echo getStatusText($order['status']); ?>
        </span>
    </div>

    <div class="row g-4">
        <!-- Thông tin đơn hàng -->
        <div class="col-lg-8">
            <!-- Sản phẩm -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0"><i class="fas fa-box me-2"></i>Sản phẩm đã đặt</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" style="width: 50%;">Sản phẩm</th>
                                    <th class="text-center">Đơn giá</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end pe-4">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total = 0; ?>
                                <?php foreach ($orderItems as $item): ?>
                                    <?php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; ?>
                                    <tr class="border-top">
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <?php 
                                                    $images = json_decode($item['images'], true);
                                                    $img = !empty($images[0]) ? $images[0] : '';
                                                    if ($img && strpos($img, 'uploads/') === false && strpos($img, 'http') !== 0) {
                                                        $img = 'uploads/products/' . $img;
                                                    }
                                                    if (!$img) $img = 'https://via.placeholder.com/80x80?text=No+Image';
                                                    ?>
                                                    <img src="<?php echo htmlspecialchars($img); ?>" 
                                                         alt="<?php echo htmlspecialchars($item['product_name']); ?>" 
                                                         class="rounded-3 shadow-sm" style="width: 80px; height: 80px; object-fit: cover;">
                                                </div>
                                                <div>
                                                    <div class="fw-semibold mb-1"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                                    <div class="small text-muted mb-2">SKU: <?php echo $item['product_id']; ?></div>
                                                    <a href="?controller=product&action=detail&id=<?php echo $item['product_id']; ?>" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       data-bs-toggle="tooltip" 
                                                       title="Xem chi tiết sản phẩm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <?php echo number_format($item['price'], 0, ',', '.'); ?> đ
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                                                <?php echo $item['quantity']; ?>
                                            </span>
                                        </td>
                                        <td class="text-end fw-bold pe-4">
                                            <?php echo number_format($subtotal, 0, ',', '.'); ?> đ
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold border-0 py-3 ps-4">Tổng tiền hàng:</td>
                                    <td class="text-end fw-bold pe-4"><?php echo number_format($total, 0, ',', '.'); ?> đ</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold border-0 py-2 ps-4">Phí vận chuyển:</td>
                                    <td class="text-end fw-bold pe-4"><?php echo number_format($order['shipping_fee'] ?? 0, 0, ',', '.'); ?> đ</td>
                                </tr>
                                <tr class="border-top">
                                    <td colspan="3" class="text-end fw-bold fs-5 border-0 py-3 ps-4">Tổng thanh toán:</td>
                                    <td class="text-end fw-bold text-danger fs-5 pe-4">
                                        <?php echo number_format(($order['total_amount'] ?? $total), 0, ',', '.'); ?> đ
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Lịch sử đơn hàng -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Lịch sử đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item completed">
                            <div class="d-flex">
                                <div class="timeline-icon bg-primary bg-opacity-10 text-primary">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1 fw-semibold">Đơn hàng đã đặt</h6>
                                    <p class="small text-muted mb-1"><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
                                    <p class="small mb-0">Đơn hàng đã được tiếp nhận và đang chờ xử lý.</p>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (in_array($order['status'], ['processing', 'shipped', 'delivered'])): ?>
                        <div class="timeline-item <?php echo $order['status'] === 'processing' ? 'active' : 'completed'; ?>">
                            <div class="d-flex">
                                <div class="timeline-icon bg-info bg-opacity-10 text-info">
                                    <i class="fas fa-sync-alt"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1 fw-semibold">Đang xử lý</h6>
                                    <?php if (isset($order['updated_at'])): ?>
                                    <p class="small text-muted mb-1"><?php echo date('d/m/Y H:i', strtotime($order['updated_at'])); ?></p>
                                    <?php endif; ?>
                                    <p class="small mb-0">Đơn hàng của bạn đang được xử lý.</p>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (in_array($order['status'], ['shipped', 'delivered'])): ?>
                        <div class="timeline-item <?php echo $order['status'] === 'shipped' ? 'active' : 'completed'; ?>">
                            <div class="d-flex">
                                <div class="timeline-icon bg-primary bg-opacity-10 text-primary">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1 fw-semibold">Đang giao hàng</h6>
                                    <?php if (isset($order['shipped_at'])): ?>
                                    <p class="small text-muted mb-1"><?php echo date('d/m/Y H:i', strtotime($order['shipped_at'])); ?></p>
                                    <?php endif; ?>
                                    <p class="small mb-0">Đơn hàng đang được vận chuyển đến bạn.</p>
                                    <?php if (!empty($order['shipping_code'])): ?>
                                    <p class="small mt-2 mb-0">
                                        <strong>Mã vận đơn:</strong> <?php echo htmlspecialchars($order['shipping_code']); ?>
                                    </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($order['status'] === 'delivered'): ?>
                        <div class="timeline-item completed">
                            <div class="d-flex">
                                <div class="timeline-icon bg-success bg-opacity-10 text-success">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1 fw-semibold">Đã giao hàng</h6>
                                    <?php if (isset($order['delivered_at'])): ?>
                                    <p class="small text-muted mb-1"><?php echo date('d/m/Y H:i', strtotime($order['delivered_at'])); ?></p>
                                    <?php endif; ?>
                                    <p class="small mb-0">Đơn hàng đã được giao thành công.</p>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($order['status'] === 'cancelled'): ?>
                        <div class="timeline-item">
                            <div class="d-flex">
                                <div class="timeline-icon bg-danger bg-opacity-10 text-danger">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1 fw-semibold">Đã hủy</h6>
                                    <?php if (isset($order['cancelled_at'])): ?>
                                    <p class="small text-muted mb-1"><?php echo date('d/m/Y H:i', strtotime($order['cancelled_at'])); ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($order['cancel_reason'])): ?>
                                    <p class="small mb-0">
                                        <strong>Lý do hủy:</strong> <?php echo htmlspecialchars($order['cancel_reason']); ?>
                                    </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin thanh toán & giao hàng -->
        <div class="col-lg-4">
            <!-- Thông tin thanh toán -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Thông tin thanh toán</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Phương thức thanh toán:</span>
                                <span class="fw-medium"><?php echo getPaymentMethodText($order['payment_method']); ?></span>
                            </div>
                        </li>
                        <li class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Trạng thái thanh toán:</span>
                                <span class="fw-medium">
                                    <?php if ($order['payment_status'] === 'paid'): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success">
                                            <i class="fas fa-check-circle me-1"></i> Đã thanh toán
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning bg-opacity-10 text-warning">
                                            <i class="fas fa-clock me-1"></i> Chưa thanh toán
                                        </span>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </li>
                        <?php if ($order['payment_method'] === 'bank' && !empty($order['bank_transfer_info'])): ?>
                        <li class="border-top pt-3 mt-3">
                            <div class="small text-muted mb-2">Thông tin chuyển khoản:</div>
                            <div class="bg-light p-3 rounded-2 small">
                                <?php echo nl2br(htmlspecialchars($order['bank_transfer_info'])); ?>
                            </div>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <!-- Thông tin giao hàng -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0"><i class="fas fa-truck me-2"></i>Thông tin giao hàng</h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">
                        <i class="fas fa-user me-2"></i>
                        <?php echo htmlspecialchars($order['shipping_name'] ?? $order['full_name']); ?>
                    </h6>
                    <div class="mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-phone mt-1 me-2 text-muted"></i>
                            <div>
                                <div class="text-muted small">Số điện thoại</div>
                                <div class="fw-medium"><?php echo htmlspecialchars($order['phone']); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-map-marker-alt mt-1 me-2 text-muted"></i>
                            <div>
                                <div class="text-muted small">Địa chỉ nhận hàng</div>
                                <div class="fw-medium"><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></div>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($order['note'])): ?>
                    <div class="border-top pt-3 mt-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-sticky-note mt-1 me-2 text-muted"></i>
                            <div>
                                <div class="text-muted small">Ghi chú</div>
                                <div class="fw-medium"><?php echo nl2br(htmlspecialchars($order['note'])); ?></div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Hỗ trợ -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0"><i class="fas fa-headset me-2"></i>Hỗ trợ</h5>
                </div>
                <div class="card-body">
                    <p class="small mb-3">Nếu bạn có bất kỳ câu hỏi nào về đơn hàng của mình, vui lòng liên hệ với chúng tôi.</p>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-phone-alt me-2 text-primary"></i>
                        <span>Hotline: <a href="tel:0123456789" class="text-decoration-none">0123 456 789</a></span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-envelope me-2 text-primary"></i>
                        <span>Email: <a href="mailto:hotro@shopelectrics.vn" class="text-decoration-none">hotro@shopelectrics.vn</a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nút hành động -->
    <div class="d-flex justify-content-between mt-4">
        <a href="?controller=user&action=orders" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách đơn hàng
        </a>
        <div>
            <?php if ($order['status'] === 'pending'): ?>
                <button type="button" class="btn btn-outline-danger me-2" id="btnCancelOrder">
                    <i class="fas fa-times me-2"></i>Hủy đơn hàng
                </button>
            <?php endif; ?>
            
            <?php if ($order['payment_status'] !== 'paid' && $order['status'] !== 'cancelled'): ?>
                <a href="?controller=checkout&action=payment&order_id=<?php echo $order['id']; ?>" class="btn btn-primary">
                    <i class="fas fa-credit-card me-2"></i>Thanh toán ngay
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal hủy đơn hàng -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận hủy đơn hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="cancelOrderForm" method="post" action="?controller=order&action=cancel">
                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn hủy đơn hàng #<?php echo $order['id']; ?>?</p>
                    <div class="mb-3">
                        <label for="cancelReason" class="form-label">Lý do hủy đơn hàng <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="cancelReason" name="reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Timeline styles */
.timeline {
    position: relative;
    padding-left: 2rem;
    margin: 0;
}

.timeline:before {
    content: '';
    position: absolute;
    left: 0.5rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #e9ecef;
}

.timeline-item {
    position: relative;
    padding-bottom: 2rem;
    padding-left: 1.5rem;
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-icon {
    position: absolute;
    left: -1.5rem;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1;
}

.timeline-item.completed .timeline-icon {
    background-color: #19875410 !important;
    color: #198754 !important;
}

.timeline-item.active .timeline-icon {
    background-color: #0d6efd10 !important;
    color: #0d6efd !important;
    box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
}

/* Responsive adjustments */
@media (max-width: 991.98px) {
    .timeline {
        padding-left: 1.5rem;
    }
    
    .timeline-icon {
        width: 2rem;
        height: 2rem;
        left: -1rem;
    }
}
</style>

<script>
// Khởi tạo tooltip
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Xử lý nút hủy đơn hàng
    const btnCancelOrder = document.getElementById('btnCancelOrder');
    if (btnCancelOrder) {
        btnCancelOrder.addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
            modal.show();
        });
    }
    
    // Xử lý form hủy đơn hàng
    const cancelOrderForm = document.getElementById('cancelOrderForm');
    if (cancelOrderForm) {
        cancelOrderForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Hiển thị loading
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Đang xử lý...';
            
            // Gửi yêu cầu
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hiển thị thông báo thành công và tải lại trang
                    Swal.fire({
                        title: 'Thành công!',
                        text: 'Đơn hàng đã được hủy thành công',
                        icon: 'success',
                        confirmButtonText: 'Đóng'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    // Hiển thị thông báo lỗi
                    Swal.fire({
                        title: 'Lỗi!',
                        text: data.message || 'Có lỗi xảy ra khi hủy đơn hàng',
                        icon: 'error',
                        confirmButtonText: 'Đã hiểu'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Lỗi!',
                    text: 'Có lỗi kết nối xảy ra. Vui lòng thử lại sau.',
                    icon: 'error',
                    confirmButtonText: 'Đã hiểu'
                });
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
        });
    }
});
</script>
