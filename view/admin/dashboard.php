<?php
if (!in_array($_SESSION['user']['role_name'] ?? '', ['admin', 'super_admin'])) {
    echo '<div style="color:red;text-align:center;margin:40px 0;">Bạn không có quyền truy cập trang này!</div>';
    include 'view/layout/footer.php';
    exit;
}
?>
<style>
body {
    background: #2c313a;
    font-family: 'Segoe UI', Arial, sans-serif;
    margin: 0;
    color: #f1f1f1;
}
.dashboard-container {
    display: flex;
    min-height: 100vh;
}
.sidebar {
    width: 230px;
    background: #23272f;
    padding: 30px 0 0 0;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}
.sidebar h2 {
    color: #4fc3f7;
    text-align: center;
    margin-bottom: 40px;
    letter-spacing: 2px;
}
.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
}
.sidebar ul li {
    margin-bottom: 10px;
}
.sidebar ul li a {
    display: flex;
    align-items: center;
    color: #f1f1f1;
    text-decoration: none;
    padding: 12px 24px;
    border-left: 4px solid transparent;
    transition: background 0.2s, border-color 0.2s;
    font-size: 16px;
}
.sidebar ul li a.active,
.sidebar ul li a:hover {
    background: #1a1d23;
    border-left: 4px solid #4fc3f7;
    color: #4fc3f7;
}
.sidebar ul li a i {
    margin-right: 12px;
    font-size: 18px;
}
.main-content {
    flex: 1;
    padding: 0 40px;
}
.header {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    background: #1a1d23;
    padding: 18px 0 18px 0;
    margin-bottom: 30px;
    border-bottom: 1px solid #23272f;
}
.header .admin-info {
    margin-right: 20px;
    display: flex;
    align-items: center;
    color: #f1f1f1;
    font-size: 15px;
}
.header .admin-info i {
    margin-right: 8px;
    color: #4fc3f7;
}
.header .logout-btn {
    background: none;
    border: 1px solid #4fc3f7;
    color: #4fc3f7;
    padding: 7px 18px;
    border-radius: 20px;
    cursor: pointer;
    font-size: 15px;
    transition: background 0.2s, color 0.2s;
}
.header .logout-btn:hover {
    background: #4fc3f7;
    color: #23272f;
}
.dashboard-title {
    font-size: 2.2rem;
    font-weight: 600;
    color: #4fc3f7;
    margin-bottom: 30px;
    margin-top: 10px;
}
.cards-row {
    display: flex;
    gap: 30px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}
.card {
    background: #353b48;
    border-radius: 18px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.12);
    padding: 32px 36px;
    flex: 1 1 220px;
    min-width: 220px;
    display: flex;
    flex-direction: column;
    align-items: center;
    border-bottom: 4px solid #4fc3f7;
    transition: transform 0.15s;
}
.card:hover {
    transform: translateY(-5px) scale(1.03);
}
.card .icon {
    font-size: 2.5rem;
    margin-bottom: 12px;
}
.card.users { border-bottom-color: #7c4dff; }
.card.products { border-bottom-color: #00e676; }
.card.orders { border-bottom-color: #ffd600; }
.card .label {
    font-size: 1.1rem;
    color: #b0bec5;
    margin-bottom: 8px;
}
.card .value {
    font-size: 2.1rem;
    font-weight: bold;
    color: #4fc3f7;
}
@media (max-width: 900px) {
    .dashboard-container { flex-direction: column; }
    .sidebar { width: 100%; flex-direction: row; min-height: unset; }
    .main-content { padding: 20px; }
    .cards-row { flex-direction: column; gap: 18px; }
}
</style>
<?php include 'view/layout/admin_layout.php'; ?>
<div class="container-fluid">
    <h2 class="mb-4 mt-2" style="color:#1976d2;font-weight:700;">Dashboard</h2>
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card users">
                <div class="icon"><i class="fa fa-users"></i></div>
                <div class="label">Tổng số người dùng</div>
                <div class="value"><?= $total_users ?></div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card products">
                <div class="icon"><i class="fa fa-box"></i></div>
                <div class="label">Tổng số sản phẩm</div>
                <div class="value"><?= $total_products ?></div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card orders">
                <div class="icon"><i class="fa fa-file-invoice"></i></div>
                <div class="label">Tổng số đơn hàng</div>
                <div class="value"><?= $total_orders ?></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white fw-bold">
                    Yêu cầu liên hệ mới nhất
                    <a href="index.php?controller=admin&action=contact_requests" class="btn btn-sm btn-light float-end">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Nội dung</th>
                                    <th>Thời gian</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($latest_contacts)): ?>
                                    <?php foreach ($latest_contacts as $i => $r): ?>
                                        <tr>
                                            <td><?= $i+1 ?></td>
                                            <td><?= htmlspecialchars($r['name']) ?></td>
                                            <td><a href="mailto:<?= htmlspecialchars($r['email']) ?>"><?= htmlspecialchars($r['email']) ?></a></td>
                                            <td><?= htmlspecialchars($r['phone']) ?></td>
                                            <td><?= nl2br(htmlspecialchars($r['message'])) ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="6" class="text-center text-muted">Chưa có yêu cầu liên hệ nào.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Có thể thêm các widget khác ở đây -->
    </div>
</div> 


