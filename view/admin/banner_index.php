<?php // thanhdat: Giao diện quản lý banner
$page_title = "Quản lý Banner";
include 'view/layout/admin_layout.php';
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-images"></i> Quản lý Banner
        </h1>
        <a href="index.php?controller=banner&action=create" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Thêm Banner
        </a>
    </div>

    <!-- Thống kê -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tổng Banner</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['total'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-images fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Đang hoạt động</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['active'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Vị trí khác nhau</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['positions'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-map-marker-alt fa-2x text-gray-300"></i>
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
                <input type="hidden" name="controller" value="banner">
                <input type="hidden" name="action" value="index">
                <div class="col-md-3">
                    <label class="form-label">Vị trí</label>
                    <select name="position" class="form-control">
                        <option value="">Tất cả vị trí</option>
                        <option value="header" <?= isset($_GET['position']) && $_GET['position'] == 'header' ? 'selected' : '' ?>>Header</option>
                        <option value="sidebar" <?= isset($_GET['position']) && $_GET['position'] == 'sidebar' ? 'selected' : '' ?>>Sidebar</option>
                        <option value="footer" <?= isset($_GET['position']) && $_GET['position'] == 'footer' ? 'selected' : '' ?>>Footer</option>
                        <option value="homepage" <?= isset($_GET['position']) && $_GET['position'] == 'homepage' ? 'selected' : '' ?>>Homepage</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-control">
                        <option value="">Tất cả trạng thái</option>
                        <option value="1" <?= isset($_GET['status']) && $_GET['status'] == '1' ? 'selected' : '' ?>>Đang hoạt động</option>
                        <option value="0" <?= isset($_GET['status']) && $_GET['status'] == '0' ? 'selected' : '' ?>>Tạm ẩn</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Lọc
                    </button>
                    <a href="index.php?controller=banner&action=index" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Xóa lọc
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Danh sách banner -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách Banner</h6>
            <button type="button" class="btn btn-danger btn-sm" onclick="deleteSelected()">
                <i class="fas fa-trash"></i> Xóa đã chọn
            </button>
        </div>
        <div class="card-body">
            <?php if (empty($banners)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-images fa-3x text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Chưa có banner nào.</p>
                    <a href="index.php?controller=banner&action=create" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm banner đầu tiên
                    </a>
                </div>
            <?php else: ?>
                <form id="bannerForm" method="POST" action="index.php?controller=banner&action=deleteMultiple">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="30">
                                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                    </th>
                                    <th width="80">Ảnh</th>
                                    <th>Tiêu đề</th>
                                    <th>Vị trí</th>
                                    <th width="100">Thứ tự</th>
                                    <th width="100">Trạng thái</th>
                                    <th width="150">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($banners as $banner): ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="selected_banners[]" value="<?= $banner['id'] ?>" class="banner-checkbox">
                                        </td>
                                        <td>
                                            <?php if ($banner['image']): ?>
                                                <img src="uploads/banners/<?= $banner['image'] ?>" 
                                                     alt="<?= htmlspecialchars($banner['title']) ?>" 
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
                                                <strong><?= htmlspecialchars($banner['title']) ?></strong>
                                                <?php if ($banner['description']): ?>
                                                    <br><small class="text-muted"><?= htmlspecialchars(substr($banner['description'], 0, 50)) ?>...</small>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-info"><?= ucfirst($banner['position']) ?></span>
                                        </td>
                                        <td class="text-center"><?= $banner['sort_order'] ?></td>
                                        <td class="text-center">
                                            <a href="index.php?controller=banner&action=toggle&id=<?= $banner['id'] ?>" 
                                               class="btn btn-sm <?= $banner['status'] ? 'btn-success' : 'btn-secondary' ?>"
                                               onclick="return confirm('Bạn có chắc muốn thay đổi trạng thái?')">
                                                <i class="fas <?= $banner['status'] ? 'fa-eye' : 'fa-eye-slash' ?>"></i>
                                                <?= $banner['status'] ? 'Hiện' : 'Ẩn' ?>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="index.php?controller=banner&action=edit&id=<?= $banner['id'] ?>" 
                                                   class="btn btn-primary btn-sm" title="Sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="index.php?controller=banner&action=delete&id=<?= $banner['id'] ?>" 
                                                   class="btn btn-danger btn-sm" title="Xóa"
                                                   onclick="return confirm('Bạn có chắc muốn xóa banner này?')">
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
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.banner-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

function deleteSelected() {
    const checkboxes = document.querySelectorAll('.banner-checkbox:checked');
    
    if (checkboxes.length === 0) {
        alert('Vui lòng chọn banner cần xóa.');
        return;
    }
    
    if (confirm(`Bạn có chắc muốn xóa ${checkboxes.length} banner đã chọn?`)) {
        document.getElementById('bannerForm').submit();
    }
}
</script>

<?php include 'view/layout/footer.php'; ?> 