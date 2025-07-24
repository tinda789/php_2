<?php
require_once 'view/layout/header_admin.php';
// Nếu truy cập trực tiếp file này, vẫn có layout và header
// Đã xóa dòng include __DIR__ . '/../layout/admin_layout.php'; để tránh vòng lặp layout.
if (!in_array($_SESSION['user']['role_name'] ?? '', ['admin', 'super_admin'])) {
    echo '<div style="color:red;text-align:center;margin:40px 0;">Bạn không có quyền truy cập trang này!</div>';
    include 'view/layout/footer.php';
    exit;
}
require_once 'config/config.php';
require_once 'model/Order.php';
$total_users = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];
$total_products = $conn->query("SELECT COUNT(*) FROM products")->fetch_row()[0];
$total_orders = $conn->query("SHOW TABLES LIKE 'orders'")->num_rows ? $conn->query("SELECT COUNT(*) FROM orders")->fetch_row()[0] : 0;
// Thống kê kinh doanh
$total_revenue = Order::getTotalRevenue($conn);
$total_completed = Order::getTotalCompletedOrders($conn);
$total_pending = Order::getTotalPendingOrders($conn);
$total_cancelled = Order::getTotalCancelledOrders($conn);
$monthly_revenue = Order::getMonthlyRevenue($conn);
$order_status_stats = Order::getOrderStatusStats($conn);
?>
<style>
body {
    background: #fff;
    font-family: 'Segoe UI', Arial, sans-serif;
    margin: 0;
    color: #23272f;
}
.dashboard-container {
    display: flex;
    min-height: 100vh;
}
.sidebar {
    width: 230px;
    background: #f4f6fb;
    padding: 30px 0 0 0;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}
.sidebar h2 {
    color: #1976d2;
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
    color: #23272f;
    text-decoration: none;
    padding: 12px 24px;
    border-left: 4px solid transparent;
    transition: background 0.2s, border-color 0.2s;
    font-size: 16px;
}
.sidebar ul li a.active,
.sidebar ul li a:hover {
    background: #e3f2fd;
    border-left: 4px solid #1976d2;
    color: #1976d2;
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
    background: #e3f2fd;
    padding: 18px 0 18px 0;
    margin-bottom: 30px;
    border-bottom: 1px solid #b0bec5;
}
.header .admin-info {
    margin-right: 20px;
    display: flex;
    align-items: center;
    color: #23272f;
    font-size: 15px;
}
.header .admin-info i {
    margin-right: 8px;
    color: #1976d2;
}
.header .logout-btn {
    background: none;
    border: 1px solid #1976d2;
    color: #1976d2;
    padding: 7px 18px;
    border-radius: 20px;
    cursor: pointer;
    font-size: 15px;
    transition: background 0.2s, color 0.2s;
}
.header .logout-btn:hover {
    background: #1976d2;
    color: #fff;
}
.dashboard-title {
    font-size: 2.2rem;
    font-weight: 600;
    color: #1976d2;
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
    background: #f4f6fb;
    border-radius: 18px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    padding: 32px 36px;
    flex: 1 1 220px;
    min-width: 220px;
    display: flex;
    flex-direction: column;
    align-items: center;
    border-bottom: 4px solid #1976d2;
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
    color: #607d8b;
    margin-bottom: 8px;
}
.card .value {
    font-size: 2.1rem;
    font-weight: bold;
    color: #1976d2;
}
@media (max-width: 900px) {
    .dashboard-container { flex-direction: column; }
    .sidebar { width: 100%; flex-direction: row; min-height: unset; }
    .main-content { padding: 20px; }
    .cards-row { flex-direction: column; gap: 18px; }
}
.main-content, .dashboard-container {
    margin-top: 70px !important;
}
</style>
<?php include 'view/layout/admin_layout.php'; ?>
<div class="cards-row">
    <div class="card users">
        <div class="icon"><i class="fa-solid fa-users"></i></div>
        <div class="label">Tổng người dùng</div>
        <div class="value"><?php echo $total_users; ?></div>
    </div>
    <div class="card products">
        <div class="icon"><i class="fa-solid fa-box"></i></div>
        <div class="label">Tổng sản phẩm</div>
        <div class="value"><?php echo $total_products; ?></div>
    </div>
    <div class="card orders">
        <div class="icon"><i class="fa-solid fa-file-invoice"></i></div>
        <div class="label">Tổng đơn hàng</div>
        <div class="value"><?php echo $total_orders; ?></div>
    </div>
    <div class="card" style="border-bottom-color:#00e676;">
        <div class="icon"><i class="fa-solid fa-sack-dollar text-success"></i></div>
        <div class="label">Tổng doanh thu</div>
        <div class="value text-success"><?php echo number_format($total_revenue,0,',','.'); ?> đ</div>
    </div>
    <div class="card" style="border-bottom-color:#2196f3;">
        <div class="icon"><i class="fa-solid fa-check-circle text-info"></i></div>
        <div class="label">Đơn hoàn thành</div>
        <div class="value"><?php echo $total_completed; ?></div>
    </div>
    <div class="card" style="border-bottom-color:#ffd600;">
        <div class="icon"><i class="fa-solid fa-hourglass-half text-warning"></i></div>
        <div class="label">Đơn chờ xử lý</div>
        <div class="value"><?php echo $total_pending; ?></div>
    </div>
    <div class="card" style="border-bottom-color:#ff5252;">
        <div class="icon"><i class="fa-solid fa-times-circle text-danger"></i></div>
        <div class="label">Đơn bị hủy</div>
        <div class="value"><?php echo $total_cancelled; ?></div>
    </div>
</div>
<div class="row" style="margin-top:40px;">
  <div class="col-md-7 mb-4">
    <div class="card" style="min-height:380px;">
      <h5 class="mb-3 text-primary"><i class="fa-solid fa-chart-column"></i> Doanh thu theo tháng (<?php echo date('Y'); ?>)</h5>
      <canvas id="revenueChart" height="120"></canvas>
    </div>
  </div>
  <div class="col-md-5 mb-4">
    <div class="card" style="min-height:380px;">
      <h5 class="mb-3 text-primary"><i class="fa-solid fa-chart-bar"></i> Đơn hàng theo trạng thái</h5>
      <canvas id="orderStatusChart" height="120"></canvas>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Biểu đồ doanh thu theo tháng
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"],
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data: <?php echo json_encode(array_values($monthly_revenue)); ?>,
            backgroundColor: 'rgba(0, 198, 255, 0.7)',
            borderColor: '#007bff',
            borderWidth: 2,
            borderRadius: 8,
            maxBarThickness: 38
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            title: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { callback: function(value) { return value.toLocaleString() + ' đ'; } }
            }
        }
    }
});
// Biểu đồ số đơn hàng theo trạng thái
const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
const orderStatusData = <?php echo json_encode($order_status_stats); ?>;
const statusLabels = Object.keys(orderStatusData).map(function(s) {
    switch(s) {
        case 'completed': return 'Hoàn thành';
        case 'delivered': return 'Đã giao';
        case 'pending': return 'Chờ xử lý';
        case 'processing': return 'Đang xử lý';
        case 'cancelled': return 'Đã hủy';
        default: return s;
    }
});
const statusColors = statusLabels.map(function(label) {
    if (label === 'Hoàn thành' || label === 'Đã giao') return '#00e676';
    if (label === 'Chờ xử lý' || label === 'Đang xử lý') return '#ffd600';
    if (label === 'Đã hủy') return '#ff5252';
    return '#4fc3f7';
});
const orderStatusChart = new Chart(orderStatusCtx, {
    type: 'bar',
    data: {
        labels: statusLabels,
        datasets: [{
            label: 'Số đơn hàng',
            data: Object.values(orderStatusData),
            backgroundColor: statusColors,
            borderColor: '#007bff',
            borderWidth: 2,
            borderRadius: 8,
            maxBarThickness: 38
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            title: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                precision: 0
            }
        }
    }
});
</script> 


