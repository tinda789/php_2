<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set page title
$pageTitle = 'Kết quả thanh toán';

// Parse shipping address if it's a JSON string
$shippingAddress = [];
if (!empty($order['shipping_address'])) {
    // Check if it's a JSON string
    if (is_string($order['shipping_address']) && json_decode($order['shipping_address']) !== null) {
        $shippingAddress = json_decode($order['shipping_address'], true);
    } else {
        // If it's not JSON, use it as is
        $shippingAddress = ['address' => $order['shipping_address']];
    }
}

// Debug: Log the include path
$headerPath = __DIR__ . '/../../layouts/user/header.php';
$footerPath = __DIR__ . '/../../layouts/user/footer.php';

// Include header
if (file_exists($headerPath)) {
    include $headerPath;
} else {
    // Fallback to a simple header if the main header is not found
    ?><!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($pageTitle); ?> - ShopElectrics</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
    <body>
    <div class="container">
    <?php
}
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-<?php echo $isSuccess ? 'success' : 'danger'; ?> text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-<?php echo $isSuccess ? 'check-circle' : 'times-circle'; ?> me-2"></i>
                        <?php echo $isSuccess ? 'Thanh toán thành công' : 'Thanh toán thất bại'; ?>
                    </h4>
                </div>
                <div class="card-body">
                    <?php if ($isSuccess): ?>
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                            </div>
                            <h3 class="text-success">Cảm ơn quý khách đã đặt hàng!</h3>
                        </div>
                        <div class="alert alert-success">
                            <i class="fas fa-info-circle me-2"></i>
                            <?php echo htmlspecialchars($message); ?>
                        </div>
                        
                        <?php if ($order): ?>
                            <div class="order-summary mt-4">
                                <h5 class="mb-3">Thông tin đơn hàng:</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Mã đơn hàng:</th>
                                            <td><?php echo htmlspecialchars($order['order_number']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Ngày đặt hàng:</th>
                                            <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Phương thức thanh toán:</th>
                                            <td>VNPay</td>
                                        </tr>
                                        <tr>
                                            <th>Tổng tiền:</th>
                                            <td class="fw-bold"><?php echo number_format($order['total_amount'], 0, ',', '.') . ' ₫'; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Trạng thái thanh toán:</th>
                                            <td>
                                                <span class="badge bg-success">Đã thanh toán</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Trạng thái đơn hàng:</th>
                                            <td>
                                                <span class="badge bg-primary">Đã xác nhận</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <div class="mt-4">
                                    <h5>Chi tiết đơn hàng:</h5>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Sản phẩm</th>
                                                    <th class="text-end">Đơn giá</th>
                                                    <th class="text-center">Số lượng</th>
                                                    <th class="text-end">Thành tiền</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($order['items'] as $item): ?>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <?php if (!empty($item['image_url'])): ?>
                                                                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" class="img-thumbnail me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                                <?php endif; ?>
                                                                <div>
                                                                    <h6 class="mb-0"><?php echo htmlspecialchars($item['product_name']); ?></h6>
                                                                    <?php if (!empty($item['color'])): ?>
                                                                        <small class="text-muted">Màu: <?php echo htmlspecialchars($item['color']); ?></small>
                                                                    <?php endif; ?>
                                                                    <?php if (!empty($item['ram']) || !empty($item['storage'])): ?>
                                                                        <div>
                                                                            <?php if (!empty($item['ram']) && !empty($item['storage'])): ?>
                                                                                <small class="text-muted"><?php echo htmlspecialchars($item['ram'] . ' / ' . $item['storage']); ?></small>
                                                                            <?php else: ?>
                                                                                <?php if (!empty($item['ram'])): ?>
                                                                                    <small class="text-muted">RAM: <?php echo htmlspecialchars($item['ram']); ?></small>
                                                                                <?php endif; ?>
                                                                                <?php if (!empty($item['storage'])): ?>
                                                                                    <small class="text-muted">Lưu trữ: <?php echo htmlspecialchars($item['storage']); ?></small>
                                                                                <?php endif; ?>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-end align-middle">
                                                            <?php echo number_format($item['unit_price'] ?? $item['price'] ?? 0, 0, ',', '.') . ' ₫'; ?>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <?php echo $item['quantity']; ?>
                                                        </td>
                                                        <td class="text-end align-middle fw-bold">
                                                            <?php 
                                                            $unitPrice = $item['unit_price'] ?? $item['price'] ?? 0;
                                                            $totalPrice = $item['total_price'] ?? ($unitPrice * $item['quantity']);
                                                            echo number_format($totalPrice, 0, ',', '.') . ' ₫'; 
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                            <tfoot>
                                                <?php if ($order['discount_amount'] > 0): ?>
                                                    <tr>
                                                        <td colspan="3" class="text-end">Giảm giá (Mã: <?php echo htmlspecialchars($order['coupon_code']); ?>):</td>
                                                        <td class="text-end text-danger">-<?php echo number_format($order['discount_amount'], 0, ',', '.') . ' ₫'; ?></td>
                                                    </tr>
                                                <?php endif; ?>
                                                <?php if ($order['shipping_fee'] > 0): ?>
                                                    <tr>
                                                        <td colspan="3" class="text-end">Phí vận chuyển:</td>
                                                        <td class="text-end"><?php echo number_format($order['shipping_fee'], 0, ',', '.') . ' ₫'; ?></td>
                                                    </tr>
                                                <?php endif; ?>
                                                <tr>
                                                    <td colspan="3" class="text-end fw-bold">Tổng cộng:</td>
                                                    <td class="text-end fw-bold"><?php echo number_format($order['total_amount'], 0, ',', '.') . ' ₫'; ?></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <h5>Thông tin giao hàng:</h5>
                                <div class="card">
                                    <div class="card-body">
                                        <?php if (!empty($shippingAddress['name'])): ?>
                                            <p class="mb-1"><strong>Người nhận:</strong> <?php echo htmlspecialchars($shippingAddress['name']); ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($shippingAddress['phone'])): ?>
                                            <p class="mb-1"><strong>Điện thoại:</strong> <?php echo htmlspecialchars($shippingAddress['phone']); ?></p>
                                        <?php endif; ?>
                                        <p class="mb-1"><strong>Địa chỉ:</strong> 
                                            <?php 
                                            $addressParts = [];
                                            if (!empty($shippingAddress['address'])) $addressParts[] = $shippingAddress['address'];
                                            if (!empty($shippingAddress['ward'])) $addressParts[] = $shippingAddress['ward'];
                                            if (!empty($shippingAddress['district'])) $addressParts[] = $shippingAddress['district'];
                                            if (!empty($shippingAddress['city'])) $addressParts[] = $shippingAddress['city'];
                                            echo htmlspecialchars(implode(', ', $addressParts)); 
                                            ?>
                                        </p>
                                        <?php if (!empty($order['shipping_note']) || !empty($shippingAddress['note'])): ?>
                                            <p class="mb-0"><strong>Ghi chú:</strong> <?php echo nl2br(htmlspecialchars($order['shipping_note'] ?? $shippingAddress['note'] ?? '')); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <a href="index.php?controller=product&action=index" class="btn btn-outline-primary">
                                    <i class="fas fa-shopping-bag me-2"></i>Tiếp tục mua sắm
                                </a>
                                <?php if (isset($order['id'])): ?>
                                    <a href="index.php?controller=user&action=order_detail&id=<?php echo $order['id']; ?>" class="btn btn-primary">
                                        <i class="fas fa-file-invoice me-2"></i>Xem chi tiết đơn hàng
                                    </a>
                                <?php else: ?>
                                    <a href="index.php?controller=user&action=orders" class="btn btn-primary">
                                        <i class="fas fa-list me-2"></i>Xem đơn hàng của tôi
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                    <?php else: ?>
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <i class="fas fa-times-circle text-danger" style="font-size: 5rem;"></i>
                            </div>
                            <h3 class="text-danger">Thanh toán không thành công!</h3>
                        </div>
                        
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php echo nl2br(htmlspecialchars($message)); ?>
                        </div>
                        
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle me-2"></i>Vui lòng thử lại</h5>
                            <p class="mb-0">Nếu bạn gặp khó khăn trong quá trình thanh toán, vui lòng thực hiện lại hoặc liên hệ bộ phận chăm sóc khách hàng để được hỗ trợ.</p>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="index.php?controller=cart&action=view" class="btn btn-outline-primary">
                                <i class="fas fa-shopping-cart me-2"></i>Quay lại giỏ hàng
                            </a>
                            <a href="index.php?controller=home&action=contact" class="btn btn-primary">
                                <i class="fas fa-headset me-2"></i>Liên hệ hỗ trợ
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Hướng dẫn thanh toán</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-phone-alt text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-1">Hỗ trợ 24/7</h6>
                                    <p class="text-muted mb-0">Liên hệ hotline: <a href="tel:19001000" class="text-primary">1900 1000</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-envelope text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-1">Email hỗ trợ</h6>
                                    <p class="text-muted mb-0"><a href="mailto:hotro@shopelectrics.vn" class="text-primary">hotro@shopelectrics.vn</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
if (isset($footerPath) && file_exists($footerPath)) {
    include $footerPath;
} else {
    // Fallback to a simple footer if the main footer is not found
    ?>
    </div><!-- End of container -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> ShopElectrics. All rights reserved.</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
}
?>
