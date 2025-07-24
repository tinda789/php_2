<?php
// Kiểm tra quyền truy cập
if (!isset($_SESSION['user'])) {
    header('Location: index.php?controller=user&action=login');
    exit();
}

// Kiểm tra xem đơn hàng có thuộc về người dùng hiện tại không
if ($order['user_id'] != $_SESSION['user']['id']) {
    header('Location: index.php?controller=user&action=orders');
    exit();
}

// Hàm chuyển đổi trạng thái thanh toán
function getPaymentStatusText($status) {
    $statuses = [
        'pending' => 'Chờ thanh toán',
        'paid' => 'Đã thanh toán',
        'failed' => 'Thanh toán thất bại',
        'refunded' => 'Đã hoàn tiền',
        'cancelled' => 'Đã hủy'
    ];
    return $statuses[$status] ?? 'Không xác định';
}

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
        'vnpay' => 'Thanh toán qua VNPAY',
        'momo' => 'Ví điện tử MoMo',
        'zalopay' => 'Ví điện tử ZaloPay'
    ];
    return $methods[$method] ?? $method;
}

// Hàm lấy màu cho trạng thái thanh toán
function getPaymentStatusColor($status) {
    $colors = [
        'pending' => 'warning',
        'paid' => 'success',
        'failed' => 'danger',
        'refunded' => 'info',
        'cancelled' => 'secondary'
    ];
    return $colors[$status] ?? 'secondary';
}
?>

<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="?controller=user&action=orders">Đơn hàng của tôi</a></li>
            <li class="breadcrumb-item active" aria-current="page">Đơn hàng #<?php echo $order['order_number'] ?? $order['id']; ?></li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h2 class="fw-bold mb-1">Chi tiết đơn hàng #<?php echo $order['order_number'] ?? $order['id']; ?></h2>
            <p class="text-muted mb-0">Đặt ngày <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
        </div>
        <div class="d-flex align-items-center">
            <span class="badge bg-<?php echo getStatusColor($order['status']); ?> bg-opacity-10 text-<?php echo getStatusColor($order['status']); ?> px-3 py-2 rounded-pill">
                <i class="fas fa-<?php echo getStatusIcon($order['status']); ?> me-2"></i>
                <?php echo getStatusText($order['status']); ?>
            </span>
            <?php if ($order['status'] === 'pending'): ?>
                <button class="btn btn-outline-danger ms-3" onclick="cancelOrder(<?php echo $order['id']; ?>)">
                    <i class="fas fa-times me-2"></i>Hủy đơn hàng
                </button>
            <?php endif; ?>
        </div>
    </div>

    <style>
        /* Timeline Styles */
        .timeline {
            position: relative;
            padding-left: 2rem;
            margin: 0 0 0 1.5rem;
            border-left: 2px solid #e9ecef;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 2.5rem;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-marker {
            position: absolute;
            left: -2.75rem;
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            background-color: #fff;
            border: 2px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            z-index: 1;
        }

        .timeline-marker i {
            font-size: 0.8rem;
        }

        .timeline-content {
            padding: 0 0 0 1rem;
            position: relative;
            top: -0.3rem;
        }

        .timeline-content h6 {
            font-size: 1rem;
            margin-bottom: 0.25rem;
            color: #212529;
        }

        .timeline-item.completed .timeline-marker {
            background-color: #198754;
            border-color: #198754;
            color: #fff;
        }

        .timeline-item.completed .timeline-content h6 {
            color: #198754;
        }

        .timeline-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: -2px;
            top: 2rem;
            bottom: -0.5rem;
            width: 2px;
            background-color: #e9ecef;
        }

        .timeline-item.completed:not(:last-child)::before {
            background-color: #198754;
        }

        /* Responsive adjustments */
        @media (max-width: 767.98px) {
            .timeline {
                margin-left: 1rem;
                padding-left: 1.5rem;
            }
            
            .timeline-marker {
                left: -1.75rem;
                width: 1.5rem;
                height: 1.5rem;
            }
            
            .timeline-content {
                padding-left: 0.5rem;
            }
        }
    </style>

    <div class="row g-4">
        <!-- Thông tin đơn hàng -->
        <div class="col-lg-8">
            <!-- Order Status Timeline -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="mb-4"><i class="fas fa-truck me-2 text-primary"></i>Tiến trình đơn hàng</h5>
                    <div class="timeline">
                        <div class="timeline-item <?php echo in_array($order['status'], ['pending', 'processing', 'shipped', 'delivered']) ? 'completed' : ''; ?>">
                            <div class="timeline-marker">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>Đơn hàng đã đặt</h6>
                                <p class="text-muted small mb-0"><?php echo date('H:i d/m/Y', strtotime($order['created_at'])); ?></p>
                                <p class="mb-0">Đơn hàng đã được tiếp nhận</p>
                            </div>
                        </div>
                        
                        <div class="timeline-item <?php echo in_array($order['status'], ['processing', 'shipped', 'delivered']) ? 'completed' : ''; ?>">
                            <div class="timeline-marker">
                                <i class="fas <?php echo in_array($order['status'], ['processing', 'shipped', 'delivered']) ? 'fa-check' : 'fa-spinner fa-spin'; ?>"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>Đang xử lý</h6>
                                <?php if (in_array($order['status'], ['processing', 'shipped', 'delivered'])): ?>
                                    <p class="text-muted small mb-0"><?php echo date('H:i d/m/Y', strtotime($order['updated_at'])); ?></p>
                                    <p class="mb-0">Đơn hàng đang được xử lý</p>
                                <?php else: ?>
                                    <p class="text-muted mb-0">Đang chờ xử lý</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="timeline-item <?php echo in_array($order['status'], ['shipped', 'delivered']) ? 'completed' : ''; ?>">
                            <div class="timeline-marker">
                                <i class="fas <?php echo in_array($order['status'], ['shipped', 'delivered']) ? 'fa-check' : 'fa-truck'; ?>"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>Đang giao hàng</h6>
                                <?php if (in_array($order['status'], ['shipped', 'delivered'])): ?>
                                    <p class="text-muted small mb-0"><?php echo date('H:i d/m/Y', strtotime($order['shipped_at'] ?? $order['updated_at'])); ?></p>
                                    <p class="mb-0">Đơn hàng đang được giao</p>
                                <?php else: ?>
                                    <p class="text-muted mb-0">Đang chờ giao hàng</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="timeline-item <?php echo $order['status'] === 'delivered' ? 'completed' : ''; ?>">
                            <div class="timeline-marker">
                                <i class="fas <?php echo $order['status'] === 'delivered' ? 'fa-check' : 'fa-home'; ?>"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>Đã giao hàng</h6>
                                <?php if ($order['status'] === 'delivered'): ?>
                                    <p class="text-muted small mb-0"><?php echo date('H:i d/m/Y', strtotime($order['delivered_at'] ?? $order['updated_at'])); ?></p>
                                    <p class="mb-0">Đơn hàng đã được giao thành công</p>
                                <?php else: ?>
                                    <p class="text-muted mb-0">Đang chờ giao hàng</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sản phẩm -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0"><i class="fas fa-box me-2"></i>Chi tiết sản phẩm</h5>
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
                                    <?php 
                                        $price = $item['price'] ?? 0;
                                        $quantity = $item['quantity'] ?? 0;
                                        $subtotal = $price * $quantity; 
                                        $total += $subtotal; 
                                    ?>
                                    <tr class="border-top">
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <?php 
                                                require_once __DIR__ . '/../../helpers/image_helper.php';
                                                $images = $item['images'] ?? [];
                                                $img = '';
                                                
                                                if (!empty($images[0])) {
                                                    $img = is_array($images[0]) ? $images[0]['image_url'] ?? '' : $images[0];
                                                } elseif (!empty($item['image_link'])) {
                                                    $img = $item['image_link'];
                                                }
                                                
                                                $img = getImageUrl($img);
                                                if (!$img) $img = 'https://via.placeholder.com/80x80?text=No+Image';
                                                ?>
                                                <img src="<?php echo htmlspecialchars($img); ?>" 
                                                     alt="<?php echo htmlspecialchars($item['product_name']); ?>" 
                                                     class="rounded-3 me-3" 
                                                     style="width: 80px; height: 80px; object-fit: cover;"
                                                     onerror="this.onerror=null;this.src='https://via.placeholder.com/80x80?text=No+Image';">
                                                <div>
                                                    <h6 class="mb-1">
                                                        <a href="?controller=product&action=detail&id=<?php echo $item['product_id']; ?>" 
                                                           class="text-decoration-none text-dark"
                                                           target="_blank"
                                                           data-bs-toggle="tooltip"
                                                           title="Xem chi tiết sản phẩm">
                                                            <?php echo htmlspecialchars($item['product_name']); ?>
                                                        </a>
                                                    </h6>
                                                    <p class="text-muted small mb-0">Mã SP: <?php echo $item['product_id']; ?></p>
                                                    <p class="text-muted small mb-0">Mã đơn: <?php echo $item['order_id']; ?></p>
                                                    <a href="?controller=product&action=detail&id=<?php echo $item['product_id']; ?>" 
                                                       class="btn btn-sm btn-info text-white mt-2 d-inline-flex align-items-center"
                                                       target="_blank"
                                                       data-bs-toggle="tooltip"
                                                       data-bs-placement="top"
                                                       title="Xem chi tiết sản phẩm"
                                                       style="padding: 0.25rem 0.75rem;">
                                                        <i class="fas fa-eye me-1"></i> Xem chi tiết
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                                                <?php echo $item['quantity']; ?>
                                            </span>
                                        </td>
                                        <td class="text-end pe-4 fw-medium">
                                            <?php echo number_format($item['unit_price'], 0, ',', '.'); ?> đ
                                        </td>
                                        <td class="text-end pe-4 fw-bold text-dark">
                                            <?php echo number_format($item['total_price'], 0, ',', '.'); ?> đ
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold border-0 py-2">Tạm tính:</td>
                                    <td class="text-end border-0 py-2 pe-4">
                                        <?php echo isset($order['subtotal']) ? number_format($order['subtotal'], 0, ',', '.') : '0'; ?> đ
                                    </td>
                                </tr>
                                <?php if ($order['discount_amount'] > 0): ?>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold border-0 py-2">Giảm giá:</td>
                                    <td class="text-end text-success border-0 py-2 pe-4">
                                        -<?php echo number_format($order['discount_amount'], 0, ',', '.'); ?> đ
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold border-0 py-2">Phí vận chuyển:</td>
                                    <td class="text-end border-0 py-2 pe-4">
                                        <?php echo number_format($order['shipping_fee'], 0, ',', '.'); ?> đ
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold border-0 py-2">Thuế (VAT):</td>
                                    <td class="text-end border-0 py-2 pe-4">
                                        <?php echo number_format($order['tax_amount'], 0, ',', '.'); ?> đ
                                    </td>
                                </tr>
                                <tr class="border-top">
                                    <td colspan="3" class="text-end fw-bold border-0 py-3">Tổng cộng:</td>
                                    <td class="text-end fw-bold fs-5 text-danger border-0 py-3 pe-4">
                                        <?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Shipping Information -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-gradient-success bg-opacity-10 border-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 p-2 rounded-circle me-3">
                            <i class="fas fa-shipping-fast text-success fs-4"></i>
                        </div>
                        <h5 class="mb-0 fw-bold text-dark">Thông tin giao hàng</h5>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item border-0 px-0 py-2">
                            <div class="d-flex">
                                <div class="me-3 text-success">
                                    <i class="fas fa-hashtag"></i>
                                </div>
                                <div>
                                    <div class="small text-muted mb-1">Mã đơn hàng</div>
                                    <div class="fw-medium">#<?php echo htmlspecialchars($order['order_number']); ?></div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item border-0 px-0 py-2">
                            <div class="d-flex">
                                <div class="me-3 text-success">
                                    <i class="far fa-calendar-alt"></i>
                                </div>
                                <div>
                                    <div class="small text-muted mb-1">Ngày đặt hàng</div>
                                    <div class="fw-medium"><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item border-0 px-0 py-2">
                            <div class="d-flex">
                                <div class="me-3 text-success">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <div class="small text-muted mb-1">Địa chỉ giao hàng</div>
                                    <div class="fw-medium">
                                        <?php 
                                        $shipping_info = json_decode($order['shipping_address'], true);
                                        if (json_last_error() === JSON_ERROR_NONE) {
                                            // Format from JSON
                                            echo htmlspecialchars($shipping_info['name'] ?? '') . '<br>';
                                            echo 'SĐT: ' . htmlspecialchars($shipping_info['phone'] ?? '') . '<br>';
                                            echo htmlspecialchars($shipping_info['address'] ?? '') . ', ';
                                            echo 'P. ' . htmlspecialchars($shipping_info['ward'] ?? '') . ', ';
                                            echo 'Q. ' . htmlspecialchars($shipping_info['district'] ?? '') . ', ';
                                            echo 'TP. ' . htmlspecialchars($shipping_info['city'] ?? '');
                                        } else {
                                            // Fallback to original format if not JSON
                                            echo nl2br(htmlspecialchars($order['shipping_address']));
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item border-0 px-0 py-2">
                            <div class="d-flex">
                                <div class="me-3 text-success">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <div class="small text-muted mb-1">Số điện thoại</div>
                                    <div class="fw-medium"><?php echo isset($order['shipping_phone']) ? htmlspecialchars($order['shipping_phone']) : ''; ?></div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item border-0 px-0 py-2">
                            <div class="d-flex">
                                <div class="me-3 text-success">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div>
                                    <div class="small text-muted mb-1">Phương thức thanh toán</div>
                                    <div class="fw-medium"><?php echo getPaymentMethodText($order['payment_method']); ?></div>
                                    <div class="mt-2">
                                        <span class="badge bg-<?php echo getPaymentStatusColor($order['payment_status']); ?> bg-opacity-10 text-<?php echo getPaymentStatusColor($order['payment_status']); ?> px-3 py-2 rounded-pill">
                                            <i class="fas fa-<?php echo $order['payment_status'] === 'paid' ? 'check-circle' : ($order['payment_status'] === 'pending' ? 'clock' : 'exclamation-circle'); ?> me-2"></i>
                                            <?php echo getPaymentStatusText($order['payment_status']); ?>
                                        </span>
                                        
                                        <?php if ($order['payment_status'] === 'paid' && $order['payment_method'] !== 'cod'): ?>
                                            <p class="small text-muted mt-2 mb-0">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Thanh toán ngày <?php echo date('d/m/Y H:i', strtotime($order['paid_at'] ?? $order['updated_at'])); ?>
                                            </p>
                                        <?php endif; ?>
                                        
                                        <?php if ($order['status'] === 'pending' && $order['payment_status'] !== 'paid'): ?>
                                            <div class="mt-3">
                                                <div class="d-flex flex-wrap gap-2">
                                                    <?php if ($order['payment_method'] === 'bank'): ?>
                                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#bankTransferModal">
                                                            <i class="fas fa-university me-1"></i> Xem thông tin chuyển khoản
                                                        </button>
                                                    <?php endif; ?>
                                                    
                                                    <a href="?controller=checkout&action=pay&method=vnpay&order_id=<?php echo $order['id']; ?>" 
                                                       class="btn btn-sm btn-primary">
                                                        <i class="fas fa-credit-card me-1"></i> Thanh toán ngay
                                                    </a>
                                                </div>
                                                
                                                <p class="small text-muted mt-2 mb-0">
                                                    <i class="fas fa-clock me-1"></i>
                                                    Thời hạn thanh toán: <?php echo date('d/m/Y H:i', strtotime($order['created_at'] . ' + 1 day')); ?>
                                                </p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php if (!empty($order['note'])): ?>
                        <li class="list-group-item border-0 px-0 py-2">
                            <div class="d-flex">
                                <div class="me-3 text-success">
                                    <i class="fas fa-sticky-note"></i>
                                </div>
                                <div>
                                    <div class="small text-muted mb-1">Ghi chú</div>
                                    <div class="fw-medium"><?php echo htmlspecialchars($order['note']); ?><style>
    /* Status Circle */
    .status-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        position: relative;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }
    
    /* Status Badges */
    .status-badge {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        letter-spacing: 0.5px;
    }
    
    /* Button Styles */
    .btn-view-all, .btn-outline-primary, .btn-outline-danger {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        font-weight: 500;
        letter-spacing: 0.5px;
    }
    
    .btn-view-all:hover, .btn-outline-primary:hover, .btn-outline-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .btn-view-all .arrow, .btn-outline-primary i, .btn-outline-danger i {
        transition: transform 0.3s ease;
    }
    
    .btn-view-all:hover .arrow, .btn-outline-primary:hover i, .btn-outline-danger:hover i {
        transform: translateX(3px);
    }
    
    /* Order Timeline */
    .timeline {
        position: relative;
        padding-left: 1.5rem;
    }
    
    .timeline:before {
        content: '';
        position: absolute;
        left: 0.5rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
        padding-left: 1.5rem;
    }
    
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    
    .timeline-item:before {
        content: '';
        position: absolute;
        left: -0.5rem;
        top: 0.25rem;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #0d6efd;
    }
    
    .timeline-item.completed:before {
        background: #0d6efd;
    }
    
    .timeline-item.active:before {
        background: #fff;
        border-color: #0d6efd;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.25);
    }
    
    .timeline-item.pending:before {
        background: #fff;
        border-color: #adb5bd;
    }
    
    /* Card Styles */
    .card {
        border: none;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.05) !important;
    }
    
    /* Badge Styles */
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 991.98px) {
        .card {
            margin-bottom: 1.5rem;
        }
    }
    
    /* Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }
</style>                                </div>
                            </div>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            
            <!-- Order Status -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-gradient-info bg-opacity-10 border-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 p-2 rounded-circle me-3">
                            <i class="fas fa-info-circle text-info fs-4"></i>
                        </div>
                        <h5 class="mb-0 fw-bold text-dark">Trạng thái đơn hàng</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block mb-3">
                            <div class="status-circle bg-<?php echo getStatusColor($order['status']); ?>-light">
                                <i class="fas fa-<?php echo getStatusIcon($order['status']); ?> text-<?php echo getStatusColor($order['status']); ?> fs-4"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-1"><?php echo getStatusText($order['status']); ?></h5>
                        <p class="text-muted mb-0">Đơn hàng #<?php echo $order['id']; ?></p>
                    </div>

                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item border-0 px-0 py-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Ngày đặt hàng</span>
                                <span class="fw-medium"><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></span>
                            </div>
                        </li>
                        <li class="list-group-item border-0 px-0 py-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Tổng tiền</span>
                                <span class="fw-bold text-danger fs-5"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ</span>
                            </div>
                        </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function cancelOrder(orderId) {
    Swal.fire({
        title: 'Xác nhận hủy đơn hàng',
        text: 'Bạn có chắc chắn muốn hủy đơn hàng này?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Có, hủy đơn hàng',
        cancelButtonText: 'Quay lại',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Đang xử lý...',
                text: 'Vui lòng chờ trong giây lát',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Send request
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
                    Swal.fire({
                        title: 'Thành công!',
                        text: 'Đã hủy đơn hàng thành công',
                        icon: 'success',
                        confirmButtonColor: '#198754',
                        confirmButtonText: 'Đồng ý'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Lỗi!',
                        text: data.message || 'Có lỗi xảy ra khi hủy đơn hàng',
                        icon: 'error',
                        confirmButtonColor: '#6c757d',
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
                    confirmButtonColor: '#6c757d',
                    confirmButtonText: 'Đã hiểu'
                });
            });
        }
    });
}

// Add animation to elements when they come into view
document.addEventListener('DOMContentLoaded', function() {
    const animateOnScroll = function() {
        const elements = document.querySelectorAll('.animate-on-scroll');
        elements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            
            if (elementTop < windowHeight - 50) {
                element.classList.add('animate-fade-in');
            }
        });
    };
    
    // Initial check
    animateOnScroll();
    
    // Check on scroll
    window.addEventListener('scroll', animateOnScroll);
});
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

<!-- Bank Transfer Modal -->
<?php if ($order['payment_method'] === 'bank'): ?>
<div class="modal fade" id="bankTransferModal" tabindex="-1" aria-labelledby="bankTransferModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="bankTransferModalLabel"><i class="fas fa-university me-2"></i>Thông tin chuyển khoản</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Vui lòng chuyển khoản với nội dung: <strong>MAHD<?php echo $order['order_number'] ?? $order['id']; ?></strong>
                </div>
                
                <div class="bank-info p-3 border rounded">
                    <div class="d-flex align-items-center mb-3">
                        <img src="assets/img/vietcombank-logo.png" alt="Vietcombank" class="me-2" style="height: 24px;">
                        <h6 class="mb-0 fw-bold">Ngân hàng TMCP Ngoại Thương Việt Nam (Vietcombank)</h6>
                    </div>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><strong>Số tài khoản:</strong> 0011004133366</li>
                        <li class="mb-2"><strong>Tên tài khoản:</strong> CONG TY TNHH SHOP ELECTRONICS</li>
                        <li class="mb-2"><strong>Chi nhánh:</strong> Chi nhánh TP.HCM</li>
                        <li class="mb-0"><strong>Số tiền:</strong> <?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ</li>
                    </ul>
                </div>
                
                <div class="mt-3">
                    <p class="small text-muted mb-1"><i class="fas fa-check-circle text-success me-1"></i> Sau khi chuyển khoản, vui lòng chụp ảnh biên lai và gửi lên hệ thống hoặc gửi về email: support@shopelectronics.vn</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" onclick="copyBankInfo()">
                    <i class="far fa-copy me-1"></i> Sao chép thông tin
                </button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
// Hàm hủy đơn hàng
function cancelOrder(orderId) {
    Swal.fire({
        title: 'Xác nhận hủy đơn hàng',
        text: 'Bạn có chắc chắn muốn hủy đơn hàng này?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Đồng ý hủy',
        cancelButtonText: 'Quay lại',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Gọi AJAX để hủy đơn hàng
            fetch('index.php?controller=user&action=cancel_order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'order_id=' + orderId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Thành công!',
                        text: data.message || 'Đã hủy đơn hàng thành công',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Tải lại trang để cập nhật trạng thái
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Lỗi!',
                        text: data.message || 'Có lỗi xảy ra khi hủy đơn hàng',
                        icon: 'error',
                        confirmButtonText: 'Đóng'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Lỗi!',
                    text: 'Có lỗi xảy ra khi kết nối đến máy chủ',
                    icon: 'error',
                    confirmButtonText: 'Đóng'
                });
            });
        }
    });
}

// Hàm sao chép thông tin ngân hàng
function copyBankInfo() {
    const bankInfo = `Số tài khoản: 0011004133366\n` +
                    `Tên tài khoản: CONG TY TNHH SHOP ELECTRONICS\n` +
                    `Ngân hàng: Vietcombank - Chi nhánh TP.HCM\n` +
                    `Số tiền: <?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ\n` +
                    `Nội dung: MAHD<?php echo $order['order_number'] ?? $order['id']; ?>`;
    
    navigator.clipboard.writeText(bankInfo).then(() => {
        // Hiển thị thông báo
        const toast = document.createElement('div');
        toast.className = 'position-fixed bottom-0 end-0 m-3 p-3 bg-success text-white rounded-3 shadow';
        toast.style.zIndex = '1100';
        toast.innerHTML = '<i class="fas fa-check-circle me-2"></i>Đã sao chép thông tin ngân hàng';
        document.body.appendChild(toast);
        
        // Tự động ẩn thông báo sau 3 giây
        setTimeout(() => {
            toast.remove();
        }, 3000);
    });
}

// Khởi tạo tooltip
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>