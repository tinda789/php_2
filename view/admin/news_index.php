<?php // thanhdat: Giao diện quản lý tin tức
$page_title = "Quản lý Tin tức";
include 'view/layout/admin_layout.php';
?>

<style>
@media (max-width: 900px) {
  .container-fluid, .main-content {
    padding: 10px !important;
  }
  .card, .table {
    font-size: 15px;
  }
  .table-responsive {
    overflow-x: auto;
  }
  .admin-sidebar {
    min-width: 64px !important;
  }
}
@media (max-width: 600px) {
  .container-fluid, .main-content {
    padding: 2px !important;
  }
  .d-sm-flex, .row, .card-header, .card-body {
    flex-direction: column !important;
    align-items: stretch !important;
  }
  .card, .table {
    font-size: 13px;
  }
  .table th, .table td {
    padding: 4px 6px !important;
  }
  .btn, .btn-sm {
    font-size: 13px !important;
    padding: 4px 10px !important;
  }
  .table-responsive {
    overflow-x: auto;
  }
}
</style>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-newspaper"></i> Quản lý Tin tức
        </h1>
        <a href="index.php?controller=news&action=create" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Thêm Tin tức
        </a>
    </div>

    <!-- Thống kê -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tổng Tin tức</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['total'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-newspaper fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Đang hiển thị</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['active'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-eye fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Tạm ẩn</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['inactive'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-eye-slash fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Chuyên mục</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['categories'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bộ lọc -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Bộ lọc</h6>
        </div>
        <div class="card-body">
            <form method="GET" class="row">
                <input type="hidden" name="controller" value="news">
                <input type="hidden" name="action" value="index">
                <div class="col-md-3">
                    <label class="form-label">Chuyên mục</label>
                    <select name="category" class="form-control">
                        <option value="">Tất cả</option>
                        <?php if (!empty($stats['category_list'])): foreach ($stats['category_list'] as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= isset($_GET['category']) && $_GET['category'] == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-control">
                        <option value="">Tất cả</option>
                        <option value="1" <?= isset($_GET['status']) && $_GET['status'] == '1' ? 'selected' : '' ?>>Hiển thị</option>
                        <option value="0" <?= isset($_GET['status']) && $_GET['status'] == '0' ? 'selected' : '' ?>>Tạm ẩn</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Lọc
                    </button>
                    <a href="index.php?controller=news&action=index" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Xóa lọc
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Danh sách tin tức -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách Tin tức</h6>
            <button type="button" class="btn btn-danger btn-sm" onclick="deleteSelected()">
                <i class="fas fa-trash"></i> Xóa đã chọn
            </button>
        </div>
        <div class="card-body">
            <?php if (empty($news_list)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-newspaper fa-3x text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Chưa có tin tức nào.</p>
                    <a href="index.php?controller=news&action=create" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm tin tức đầu tiên
                    </a>
                </div>
            <?php else: ?>
                <form id="newsForm" method="POST" action="index.php?controller=news&action=deleteMultiple">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="30">
                                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                    </th>
                                    <th width="80">Ảnh</th>
                                    <th>Tiêu đề</th>
                                    <th>Chuyên mục</th>
                                    <th width="120">Ngày đăng</th>
                                    <th width="100">Trạng thái</th>
                                    <th width="150">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($news_list as $news): ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="selected_news[]" value="<?= $news['id'] ?>" class="news-checkbox">
                                        </td>
                                        <td>
                                            <?php if ($news['image']): ?>
                                                <img src="uploads/news/<?= $news['image'] ?>" 
                                                     alt="<?= htmlspecialchars($news['title']) ?>" 
                                                     class="img-thumbnail" style="width: 60px; height: 40px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                                     style="width: 60px; height: 40px;">
                                                    <i class="fas fa-image text-gray-400"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div>
                                                <strong><?= htmlspecialchars($news['title']) ?></strong>
                                                <?php if ($news['summary']): ?>
                                                    <br><small class="text-muted"><?= htmlspecialchars(substr($news['summary'], 0, 50)) ?>...</small>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($news['category_name'] ?? '') ?></td>
                                        <td><?= date('d/m/Y', strtotime($news['created_at'])) ?></td>
                                        <td class="text-center">
                                            <a href="index.php?controller=news&action=toggle&id=<?= $news['id'] ?>" 
                                               class="btn btn-sm <?= $news['status'] ? 'btn-success' : 'btn-secondary' ?>"
                                               onclick="return confirm('Bạn có chắc muốn thay đổi trạng thái?')">
                                                <i class="fas <?= $news['status'] ? 'fa-eye' : 'fa-eye-slash' ?>"></i>
                                                <?= $news['status'] ? 'Hiện' : 'Ẩn' ?>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="index.php?controller=news&action=edit&id=<?= $news['id'] ?>" 
                                                   class="btn btn-primary btn-sm" title="Sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="index.php?controller=news&action=delete&id=<?= $news['id'] ?>" 
                                                   class="btn btn-danger btn-sm" title="Xóa"
                                                   onclick="return confirm('Bạn có chắc muốn xóa tin tức này?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </form>
                <!-- Phân trang -->
                <?php if ($total_pages > 1): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="<?= build_query(['page' => $i]) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.news-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}
function deleteSelected() {
    const checkboxes = document.querySelectorAll('.news-checkbox:checked');
    if (checkboxes.length === 0) {
        alert('Vui lòng chọn tin tức cần xóa.');
        return;
    }
    if (confirm(`Bạn có chắc muốn xóa ${checkboxes.length} tin tức đã chọn?`)) {
        document.getElementById('newsForm').submit();
    }
}
</script>
<?php include 'view/layout/footer.php'; ?>
<?php
// Hàm build_query giữ lại các tham số lọc khi phân trang
function build_query($params) {
    $query = array_merge($_GET, $params);
    return 'index.php?' . http_build_query($query);
}
?> 