<?php include 'view/layout/header.php'; ?>
<?php if (empty($_SESSION['user'])): ?>
    <div class="profile-section">
        <div class="profile-card">
            <div class="profile-error">Bạn chưa đăng nhập!</div>
        </div>
    </div>
<?php else: ?>
    <div class="profile-section">
        <div class="profile-card">
            <div class="profile-avatar">
                <?php if (!empty($_SESSION['user']['avatar'])): ?>
                    <img src="<?php echo htmlspecialchars($_SESSION['user']['avatar'] ?? ''); ?>" alt="Avatar" class="avatar-img">
                <?php else: ?>
                    👤
                <?php endif; ?>
            </div>
            <h2 class="profile-title">Thông tin cá nhân</h2>
            <table class="profile-table">
                <tr><td>Họ tên:</td><td><?php echo htmlspecialchars(trim(($_SESSION['user']['first_name'] ?? '') . ' ' . ($_SESSION['user']['last_name'] ?? ''))); ?></td></tr>
                <tr><td>Tên đăng nhập:</td><td><?php echo htmlspecialchars($_SESSION['user']['username'] ?? ''); ?></td></tr>
                <tr><td>Email:</td><td><?php echo htmlspecialchars($_SESSION['user']['email'] ?? ''); ?></td></tr>
                <tr><td>Số điện thoại:</td><td><?php echo htmlspecialchars($_SESSION['user']['phone'] ?? ''); ?></td></tr>
                <tr><td>Vai trò:</td><td><?php echo htmlspecialchars($_SESSION['user']['role_name'] ?? 'customer'); ?></td></tr>
            </table>
            
            <div class="profile-actions">
                <a href="?controller=user&action=edit" class="profile-edit-btn">
                    <i class="fas fa-user-edit me-1"></i> Sửa thông tin
                </a>
                <a href="?controller=user&action=change_password" class="profile-edit-btn" style="background:#fff;color:#007bff;border:2px solid #007bff;">
                    <i class="fas fa-key me-1"></i> Đổi mật khẩu
                </a>
                <a href="?controller=user&action=addresses" class="profile-edit-btn" style="background:#28a745;color:#fff;border:2px solid #28a745;">
                    <i class="fas fa-address-book me-1"></i> Địa chỉ
                </a>
            </div>
            
            <div class="order-history mt-5">
                <h3 class="text-center mb-4">Lịch sử đơn hàng</h3>
                <?php if (!empty($orders)): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã đơn hàng</th>
                                    <th>Ngày đặt</th>
                                    <th>Sản phẩm</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Thanh toán</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): 
                                    $status_class = [
                                        'pending' => 'warning',
                                        'processing' => 'info',
                                        'shipped' => 'primary',
                                        'delivered' => 'success',
                                        'cancelled' => 'danger'
                                    ][$order['status']] ?? 'secondary';
                                    
                                    $payment_status_class = [
                                        'pending' => 'warning',
                                        'paid' => 'success',
                                        'failed' => 'danger',
                                        'refunded' => 'info'
                                    ][$order['payment_status']] ?? 'secondary';
                                    
                                    $firstItem = $order['items'][0] ?? [];
                                ?>
                                <tr>
                                    <td>#<?php echo htmlspecialchars($order['order_number']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($order['created_at'])); ?></td>
                                    <td>
                                        <?php if (!empty($firstItem)): ?>
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    <img src="<?php echo !empty($firstItem['product_image']) ? htmlspecialchars($firstItem['product_image']) : '/assets/images/no-image.png'; ?>" alt="<?php echo htmlspecialchars($firstItem['product_name']); ?>" style="width: 50px; height: 50px; object-fit: cover;">
                                                </div>
                                                <div>
                                                    <div class="fw-bold"><?php echo htmlspecialchars($firstItem['product_name']); ?></div>
                                                    <small class="text-muted">x<?php echo $firstItem['quantity']; ?> sản phẩm</small>
                                                    <?php if (count($order['items']) > 1): ?>
                                                        <div class="text-primary small">+<?php echo count($order['items']) - 1; ?> sản phẩm khác</div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end fw-bold"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> ₫</td>
                                    <td>
                                        <span class="badge bg-<?php echo $status_class; ?>">
                                            <?php 
                                            $status_text = [
                                                'pending' => 'Chờ xử lý',
                                                'processing' => 'Đang xử lý',
                                                'shipped' => 'Đang giao hàng',
                                                'delivered' => 'Đã giao',
                                                'cancelled' => 'Đã hủy'
                                            ][$order['status']] ?? $order['status'];
                                            echo $status_text;
                                            ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $payment_status_class; ?>">
                                            <?php 
                                            $payment_text = [
                                                'pending' => 'Chờ thanh toán',
                                                'paid' => 'Đã thanh toán',
                                                'failed' => 'Thanh toán thất bại',
                                                'refunded' => 'Đã hoàn tiền'
                                            ][$order['payment_status']] ?? $order['payment_status'];
                                            echo $payment_text;
                                            ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="?controller=user&action=order_detail&id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> Xem
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="?controller=user&action=orders" class="btn btn-outline-primary">
                            <i class="fas fa-list me-2"></i>Xem tất cả đơn hàng
                        </a>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>Bạn chưa có đơn hàng nào.
                        <a href="/" class="alert-link">Tiếp tục mua sắm</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <style>
    .profile-section { 
        display: flex; 
        justify-content: center; 
        align-items: flex-start; 
        min-height: 60vh; 
        background: #f4f6fb; 
        padding: 20px 15px;
    }
    .profile-card { 
        background: #fff; 
        border-radius: 14px; 
        box-shadow: 0 2px 16px rgba(0,0,0,0.08); 
        padding: 35px 40px; 
        width: 100%; 
        max-width: 1200px; 
        margin: 0; 
        text-align: left;
    }
    .profile-avatar { 
        text-align: center;
        margin-bottom: 20px;
    }
    .avatar-img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #007bff; }
    .profile-title { 
        color: #007bff; 
        margin-bottom: 24px; 
        font-size: 1.75rem;
        text-align: center;
    }
    .profile-table { 
        width: 100%; 
        margin: 0 auto 25px; 
        font-size: 1.1rem;
        max-width: 600px;
    }
    .profile-table td { padding: 8px 0; text-align: left; }
    .profile-table tr td:first-child { color: #555; width: 120px; font-weight: 500; }
    .profile-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 12px;
        margin-bottom: 30px;
    }
    .profile-edit-btn { 
        display: inline-block; 
        background: #007bff; 
        color: #fff; 
        border-radius: 22px; 
        padding: 10px 28px; 
        font-weight: 500; 
        text-decoration: none; 
        font-size: 1rem; 
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    .profile-edit-btn:hover { 
        background: #0056b3; 
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .profile-error { color: #d32f2f; background: #ffeaea; border: 1px solid #ffcdd2; border-radius: 8px; padding: 16px; font-size: 1.1rem; }
    .order-history { 
        margin-top: 2.5rem; 
        padding-top: 2.5rem; 
        border-top: 1px solid #eee; 
    }
    .order-history .table { margin-bottom: 0; }
    .order-history th { white-space: nowrap; }
    .order-history .badge { font-size: 0.8em; padding: 0.4em 0.6em; }
    @media (max-width: 992px) {
        .order-history .table-responsive { font-size: 0.9rem; }
    }
    @media (max-width: 768px) {
        .order-history .table-responsive { font-size: 0.8rem; }
        .order-history th, .order-history td { padding: 0.5rem; }
    }
    @media (max-width: 768px) {
        .profile-card { 
            padding: 25px 20px;
            margin: 10px;
        }
        .profile-actions {
            flex-direction: column;
            align-items: center;
        }
        .profile-edit-btn {
            width: 100%;
            max-width: 280px;
            margin: 5px 0;
        }
        .order-history { 
            margin-top: 2rem; 
            padding-top: 2rem; 
        }
        .order-history h3 { 
            font-size: 1.4rem; 
            margin-bottom: 1.5rem;
        }
    }
    
    @media (max-width: 480px) {
        .profile-card { 
            padding: 20px 15px;
            margin: 10px 5px;
        }
        .profile-table {
            font-size: 1rem;
        }
        .profile-edit-btn {
            font-size: 0.95rem;
            padding: 10px 20px;
        }
    }
    </style>
<?php endif; ?>
<?php include 'view/layout/footer.php'; ?> 