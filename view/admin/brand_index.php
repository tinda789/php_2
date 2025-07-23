<?php echo '<link rel="stylesheet" href="view/layout/category_index.css">'; ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4" style="margin-top: 30px;">

        <h1 class="h3 mb-0 text-gray-800">Quản lý thương hiệu</h1>
        <a href="index.php?controller=brand&action=create" class="btn btn-primary btn-sm" style="margin-top: 25px;">
            <i class="fas fa-plus fa-sm"></i> Thêm thương hiệu mới
        </a>
    </div>

    

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php
            $success = $_GET['success'];
            if ($success == '1') echo 'Thêm thương hiệu thành công!';
            elseif ($success == '2') echo 'Cập nhật thương hiệu thành công!';
            elseif ($success == '3') echo 'Xóa thương hiệu thành công!';
            ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php
            $error = $_GET['error'];
            if ($error == 'not_found') echo 'Không tìm thấy thương hiệu!';
            elseif ($error == 'delete_failed') echo 'Xóa thương hiệu thất bại!';
            else echo 'Có lỗi xảy ra!';
            ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form method="GET" class="form-inline">
                <input type="hidden" name="controller" value="brand">
                <input type="hidden" name="action" value="index">
                <div class="form-group mr-2">
                    <input type="text" class="form-control" name="search" placeholder="Tìm kiếm thương hiệu..." 
                           value="<?php echo htmlspecialchars($search ?? ''); ?>">
                </div>
                <button type="submit" class="btn btn-primary mr-2">Tìm kiếm</button>
                <a href="index.php?controller=brand&action=index" class="btn btn-secondary">Xóa bộ lọc</a>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên thương hiệu</th>
                            <th>Mô tả</th>
                            <th>Logo</th>
                            <th>Website</th>
                            <th>Trạng thái</th>
                            <th>Ngày thành lập</th>
                            <th style="width: 110px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($brands)): ?>
                            <tr>
                                <td colspan="8" class="text-center">Không có thương hiệu nào</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($brands as $brand): ?>
                                <tr>
                                    <td><?php echo $brand['id']; ?></td>
                                    <td><strong><?php echo htmlspecialchars($brand['name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($brand['description'] ?? ''); ?></td>
                                    <td>
                                        <?php if (!empty($brand['logo'])): ?>
                                            <img src="<?php echo htmlspecialchars($brand['logo']); ?>" alt="Logo" width="60">
                                        <?php else: ?>
                                            <span class="text-muted">Không có ảnh</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($brand['website'] ?? ''); ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo $brand['is_active'] ? 'success' : 'secondary'; ?>">
                                            <?php echo $brand['is_active'] ? 'Hoạt động' : 'Ẩn'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($brand['created_at'] ?? ''); ?>
                                    </td>
                                    <td>
                                        <a href="index.php?controller=brand&action=edit&id=<?php echo $brand['id']; ?>"
                                        class="btn btn-sm" style="background-color: #28a745; color: white; margin-right: 5px;">
                                            <i class="fas fa-edit"></i> Sửa
                                        </a>
                                        <a href="index.php?controller=brand&action=delete&id=<?php echo $brand['id']; ?>" 
                                        class="btn btn-sm" style="background-color: #dc3545; color: white;"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa thương hiệu này?')">
                                            <i class="fas fa-trash"></i> Xóa
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (isset($totalPages) && $totalPages > 1): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?controller=brand&action=index&page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search ?? ''); ?>">
                                    Trước
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                <a class="page-link" href="?controller=admin&action=brand_index&page=<?php echo $i; ?>&search=<?php echo urlencode($search ?? ''); ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?controller=admin&action=brand_index&page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search ?? ''); ?>">
                                    Sau
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>