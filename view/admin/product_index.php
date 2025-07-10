<?php echo '<link rel="stylesheet" href="view/layout/product_index.css">'; ?>
<style> /* thinh */
.badge-active {
    background: #00e676;
    color: #23272f;
    border-radius: 10px;
    padding: 4px 16px;
    font-weight: 600;
    font-size: 1rem;
    display: inline-block;
    min-width: 36px;
    text-align: center;
}
.badge-inactive {
    background: #b0bec5;
    color: #23272f;
    border-radius: 10px;
    padding: 4px 16px;
    font-weight: 600;
    font-size: 1rem;
    display: inline-block;
    min-width: 36px;
    text-align: center;
}
</style> /* thinh */
<div class="main-content" style="margin-left:5%; padding:40px 0 32px 0; min-height:100vh;">
    <div class="dashboard-title" style="margin-bottom:32px;">Quản lý sản phẩm</div>
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
        <a href="index.php?controller=admin&action=product_create" class="add-category-btn" style="margin-bottom:0;">
            <i class="fas fa-plus"></i> Thêm sản phẩm mới
        </a>
        <form method="GET" class="form-inline" style="display:flex; gap:10px;">
            <input type="hidden" name="controller" value="admin">
            <input type="hidden" name="action" value="product_index">
            <input type="text" class="form-control" name="search" placeholder="Tìm kiếm sản phẩm..." value="<?php echo htmlspecialchars($search ?? ''); ?>" style="min-width:220px;">
            <button type="submit" class="btn-save" style="padding:8px 18px;">Tìm kiếm</button>
            <a href="index.php?controller=admin&action=product_index" class="btn-cancel" style="padding:8px 18px;">Xóa bộ lọc</a>
        </form>
    </div>
  

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom:18px;">
            <?php
            $success = $_GET['success'];
            if ($success == '1') echo 'Thêm sản phẩm thành công!';
            elseif ($success == '2') echo 'Cập nhật sản phẩm thành công!';
            elseif ($success == '3') echo 'Xóa sản phẩm thành công!';
            ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom:18px;">
            <?php
            $error = $_GET['error'];
            if ($error == 'not_found') echo 'Không tìm thấy sản phẩm!';
            elseif ($error == 'delete_failed') echo 'Xóa sản phẩm thất bại!';
            else echo 'Có lỗi xảy ra!';
            ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="user-manage-container">
        <div class="table-responsive">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Thương hiệu</th>
                        <th>Giá</th>
                        <th>Giá khuyến mãi</th>
                        <th>Tồn kho</th>
                        <th>Trạng thái</th>
                        <th>Nổi bật</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="10" class="text-center">Không có sản phẩm nào</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo $product['id']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                                    <?php if ($product['sku']): ?>
                                        <br><small class="text-muted">SKU: <?php echo htmlspecialchars($product['sku']); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($product['category_name'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($product['brand_name'] ?? 'N/A'); ?></td>
                                <td><?php echo number_format($product['price'], 0, ',', '.'); ?> đ</td>
                                <td>
                                    <?php if ($product['sale_price'] > 0): ?>
                                        <?php echo number_format($product['sale_price'], 0, ',', '.'); ?> đ
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge-active"><?php echo $product['stock_quantity']; ?></span>
                                </td>
                                <td>
                                    <?php if ($product['status']): ?>
                                        <span class="badge-active">Hoạt động</span>
                                    <?php else: ?>
                                        <span class="badge-inactive">Ẩn</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-<?php echo $product['featured'] ? 'warning' : 'secondary'; ?>">
                                        <?php echo $product['featured'] ? 'Nổi bật' : 'Bình thường'; ?>
                                    </span>
                                </td>
                                <td class="actions">
                                    <button class="btn-edit" title="Sửa" onclick="window.location.href='index.php?controller=admin&action=product_edit&id=<?php echo $product['id']; ?>'">✏️</button>
                                    <button class="btn-delete" title="Xóa" data-id="<?php echo $product['id']; ?>">🗑️</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if ($totalPages > 1): ?>
            <nav aria-label="Page navigation" style="margin-top:18px;">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?controller=admin&action=product_index&page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search ?? ''); ?>">
                                Trước
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?controller=admin&action=product_index&page=<?php echo $i; ?>&search=<?php echo urlencode($search ?? ''); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?controller=admin&action=product_index&page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search ?? ''); ?>">
                                Sau
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div> 