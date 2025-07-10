<?php echo '<link rel="stylesheet" href="view/layout/category_index.css">'; ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý danh mục</h1>
        <a href="index.php?controller=admin&action=category_create" class="btn btn-primary btn-sm">
            <i class="fas fa-plus fa-sm"></i> Thêm danh mục mới
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php
            $success = $_GET['success'];
            if ($success == '1') echo 'Thêm danh mục thành công!';
            elseif ($success == '2') echo 'Cập nhật danh mục thành công!';
            elseif ($success == '3') echo 'Xóa danh mục thành công!';
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
            if ($error == 'not_found') echo 'Không tìm thấy danh mục!';
            elseif ($error == 'delete_failed') echo 'Xóa danh mục thất bại!';
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
                <input type="hidden" name="controller" value="admin">
                <input type="hidden" name="action" value="category_index">
                <div class="form-group mr-2">
                    <input type="text" class="form-control" name="search" placeholder="Tìm kiếm danh mục..." 
                           value="<?php echo htmlspecialchars($search ?? ''); ?>">
                </div>
                <button type="submit" class="btn btn-primary mr-2">Tìm kiếm</button>
                <a href="index.php?controller=admin&action=category_index" class="btn btn-secondary">Xóa bộ lọc</a>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên danh mục</th>
                            <th>Mô tả</th>
                            <th>Danh mục cha</th>
                            <th>Thứ tự</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($categories)): ?>
                            <tr>
                                <td colspan="7" class="text-center">Không có danh mục nào</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><?php echo $category['id']; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($category['name']); ?></strong>
                                        <?php if ($category['slug']): ?>
                                            <br><small class="text-muted">Slug: <?php echo htmlspecialchars($category['slug']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($category['description'] ?? ''); ?></td>
                                    <td>
                                        <?php 
                                        if ($category['parent_id']) {
                                            // Lấy tên danh mục cha
                                            $parent = Category::getById($conn, $category['parent_id']);
                                            echo htmlspecialchars($parent['name'] ?? 'N/A');
                                        } else {
                                            echo '<span class="text-muted">Danh mục gốc</span>';
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $category['sort_order']; ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $category['is_active'] ? 'success' : 'secondary'; ?>">
                                            <?php echo $category['is_active'] ? 'Hoạt động' : 'Ẩn'; ?>
                                        </span>
                                    </td>
                                    <td class="actions">
                                        <a href="index.php?controller=admin&action=category_edit&id=<?php echo $category['id']; ?>" class="btn-edit">Sửa</a>
                                        <a href="#" class="btn-delete" data-id="<?php echo $category['id']; ?>" onclick="return false;">Xóa</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($totalPages > 1): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?controller=admin&action=category_index&page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search ?? ''); ?>">
                                    Trước
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                <a class="page-link" href="?controller=admin&action=category_index&page=<?php echo $i; ?>&search=<?php echo urlencode($search ?? ''); ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?controller=admin&action=category_index&page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search ?? ''); ?>">
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
<script>
document.querySelectorAll('.btn-delete').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        if (!confirm('Bạn có chắc muốn xóa?')) return;
        var id = this.getAttribute('data-id');
        fetch('api/category.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'action=delete&id=' + encodeURIComponent(id)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Xóa thành công!');
                this.closest('tr').remove();
            } else {
                alert('Xóa thất bại!');
            }
        })
        .catch(() => alert('Có lỗi khi kết nối API!'));
    });
});
</script> 
<style>
.btn-edit, .btn-delete {
    display: inline-block;
    border: none;
    border-radius: 20px;
    padding: 8px 22px;
    font-size: 16px;
    font-weight: 500;
    text-align: center;
    cursor: pointer;
    text-decoration: none;
    margin: 2px 0;
    transition: background 0.18s, color 0.18s;
}
.btn-edit {
    background: #4fc3f7;
    color: #23272f;
}
.btn-edit:hover {
    background: #0288d1;
    color: #fff;
}
.btn-delete {
    background: #ff5252;
    color: #fff;
}
.btn-delete:hover {
    background: #c62828;
    color: #fff;
}
</style> 