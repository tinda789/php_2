<?php
if (!in_array($_SESSION['user']['role_name'] ?? '', ['admin', 'super_admin'])) {
    echo '<div style="color:red;text-align:center;margin:40px 0;">Bạn không có quyền truy cập trang này!</div>';
    include 'view/layout/footer.php';
    exit;
}
require_once 'config/config.php';
$total_users = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];
$total_products = $conn->query("SELECT COUNT(*) FROM products")->fetch_row()[0];
$total_orders = $conn->query("SHOW TABLES LIKE 'orders'")->num_rows ? $conn->query("SELECT COUNT(*) FROM orders")->fetch_row()[0] : 0;
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


