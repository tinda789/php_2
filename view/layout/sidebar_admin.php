<aside class="admin-sidebar" style="width:230px; background:#23272f; padding:30px 0 0 0; min-height:100vh; display:flex; flex-direction:column; position:fixed; top:60px; left:0; height:calc(100vh - 60px); z-index:999;">
    <h2 style="color:#4fc3f7; text-align:center; margin-bottom:40px; letter-spacing:2px;">QUẢN TRỊ</h2>
    <ul style="list-style:none; padding:0; margin:0;">
        <li><a href="index.php?controller=admin" class="sidebar-link<?php if (!isset($_GET['action'])) echo ' active'; ?>" style="display:flex; align-items:center; color:#f1f1f1; text-decoration:none; padding:12px 24px; border-left:4px solid #4fc3f7;"><span style="margin-right:12px;">🏠</span>Dashboard</a></li>
        <li><a href="index.php?controller=admin&action=category_index" class="sidebar-link<?php if(isset($_GET['action']) && strpos($_GET['action'],'category')===0) echo ' active'; ?>" style="display:flex; align-items:center; color:#f1f1f1; text-decoration:none; padding:12px 24px;"><span style="margin-right:12px;">🗂️</span>Quản lý danh mục</a></li>
        <li><a href="index.php?controller=admin&action=product_index" class="sidebar-link<?php if(isset($_GET['action']) && strpos($_GET['action'],'product')===0) echo ' active'; ?>" style="display:flex; align-items:center; color:#f1f1f1; text-decoration:none; padding:12px 24px;"><span style="margin-right:12px;">📦</span>Quản lý sản phẩm</a></li>
        <li><a href="index.php?controller=user&action=manage" class="sidebar-link<?php if(isset($_GET['controller']) && $_GET['controller']==='user') echo ' active'; ?>" style="display:flex; align-items:center; color:#f1f1f1; text-decoration:none; padding:12px 24px;"><span style="margin-right:12px;">👥</span>Quản lý người dùng</a></li>
        <li><a href="index.php?controller=review&action=index" class="sidebar-link<?php if(isset($_GET['controller']) && $_GET['controller']==='review') echo ' active'; ?>" style="display:flex; align-items:center; color:#f1f1f1; text-decoration:none; padding:12px 24px;"><span style="margin-right:12px;">⭐</span>Quản lý đánh giá</a></li>
        <li><a href="index.php?controller=banner&action=index" class="sidebar-link<?php if(isset($_GET['controller']) && $_GET['controller']==='banner') echo ' active'; ?>" style="display:flex; align-items:center; color:#f1f1f1; text-decoration:none; padding:12px 24px;"><span style="margin-right:12px;">🖼️</span>Quản lý banner</a></li>
        <li><a href="index.php?controller=news&action=index" class="sidebar-link<?php if(isset($_GET['controller']) && $_GET['controller']==='news') echo ' active'; ?>" style="display:flex; align-items:center; color:#f1f1f1; text-decoration:none; padding:12px 24px;"><span style="margin-right:12px;">📰</span>Quản lý tin tức</a></li>
        <li><a href="#" class="sidebar-link"><span style="margin-right:12px;">🧾</span>Quản lý đơn hàng</a></li>
        <li><a href="#" class="sidebar-link"><span style="margin-right:12px;">🛡️</span>Phân quyền & Admin</a></li>
        <li><a href="index.php" class="sidebar-link"><span style="margin-right:12px;">🏠</span>Về trang chủ</a></li>
    </ul>
</aside>
<style>
.sidebar-link {
    display: flex;
    align-items: center;
    color: #f1f1f1;
    text-decoration: none;
    padding: 12px 24px;
    border-radius: 12px 0 0 12px;
    margin: 4px 0;
    font-size: 16px;
    transition: background 0.2s, color 0.2s, border-color 0.2s;
}
.sidebar-link.active, .sidebar-link:hover {
    background: #1a1d23;
    color: #4fc3f7;
    border-left: 4px solid #4fc3f7;
    font-weight: 500;
}
</style> 