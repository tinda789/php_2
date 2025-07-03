<link rel="stylesheet" href="view/layout/admin.css?v=1">
<header class="admin-header" style="background:#1a1d23; padding:18px 40px; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #23272f; position:fixed; top:0; left:0; width:100%; z-index:1000;">
    <div class="admin-logo" style="font-size:1.5rem; color:#4fc3f7; font-weight:bold; letter-spacing:2px;">QUẢN TRỊ</div>
    <div class="admin-user-info" style="display:flex; align-items:center; gap:16px;">
        <span style="color:#f1f1f1; font-size:1rem;"><i>👤</i> <?php echo $_SESSION['user']['username'] ?? 'admin'; ?> (<?php echo $_SESSION['user']['role_name'] ?? 'admin'; ?>)</span>
        <form method="post" action="index.php?controller=auth&action=logout" style="display:inline;">
            <button class="logout-btn" style="background:none; border:1px solid #4fc3f7; color:#4fc3f7; padding:7px 18px; border-radius:20px; cursor:pointer; font-size:15px; transition:background 0.2s, color 0.2s;">Đăng xuất</button>
        </form>
    </div>
</header>
<div style="height:60px;"></div> 