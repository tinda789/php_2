<?php include 'view/layout/header_admin.php'; ?>
<div class="dashboard-container" style="display:flex; min-height:100vh;">
    <?php include 'view/layout/sidebar_admin.php'; ?>
    <div class="main-content" style="flex:1; padding:0 40px; padding-left:230px;">
        <?php if (isset($view_file)) include $view_file; ?>
    </div>
</div>
<?php include 'view/layout/footer.php'; ?> 