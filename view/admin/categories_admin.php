<?php echo '<link rel="stylesheet" href="view/layout/categories_admin.css">'; ?>
<div class="category-manage-container">
    <div class="category-manage-title">Quản lý danh mục</div>
    <form method="get" style="margin-bottom:18px;display:flex;gap:12px;align-items:center;">
        <input type="hidden" name="controller" value="category">
        <input type="text" name="search" class="form-control" placeholder="Tìm theo tên danh mục..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" style="max-width:260px;">
        <button type="submit" class="btn-save" style="padding:8px 22px;">Tìm kiếm</button>
        <?php if (!empty($_GET['search'])): ?>
            <a href="index.php?controller=category" class="btn-cancel" style="padding:8px 22px;">Xóa lọc</a>
        <?php endif; ?>
    </form>
    <?php if (!empty($_GET['msg'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
    <?php endif; ?>
    <a href="index.php?controller=category&action=create" class="add-category-btn">+ Thêm danh mục</a>
    <div class="table-responsive">
    <table class="category-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên danh mục</th>
                <th>Danh mục cha</th>
                <th>Slug</th>
                <th>Hoạt động</th>
                <th>Thứ tự</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $cat): ?>
                <tr>
                    <td><?php echo $cat['id']; ?></td>
                    <td><?php echo htmlspecialchars($cat['name']); ?></td>
                    <td>
                        <?php
                        if ($cat['parent_id']) {
                            foreach ($categories as $p) {
                                if ($p['id'] == $cat['parent_id']) {
                                    echo htmlspecialchars($p['name']);
                                    break;
                                }
                            }
                        } else {
                            echo '<span style="color:#b0bec5;">(gốc)</span>';
                        }
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($cat['slug']); ?></td>
                    <td><?php echo $cat['is_active'] ? '<span style="color:limegreen;font-size:1.2em;">✔</span>' : '<span style="color:red;font-size:1.2em;">✘</span>'; ?></td>
                    <td><?php echo $cat['sort_order']; ?></td>
                    <td class="actions">
                        <a href="index.php?controller=category&action=edit&id=<?php echo $cat['id']; ?>" class="btn-edit">Sửa</a>
                        <a href="index.php?controller=category&action=delete&id=<?php echo $cat['id']; ?>" class="btn-delete" onclick="return confirm('Bạn có chắc muốn xóa?');">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (isset($total_pages) && $total_pages > 1): ?>
    <nav aria-label="Pagination" style="margin-top:24px;">
        <ul class="pagination" style="display:flex;gap:6px;justify-content:center;list-style:none;padding:0;">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li>
                    <a href="index.php?controller=category&page=<?php echo $i; ?><?php echo !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>" style="padding:7px 16px;border-radius:8px;text-decoration:none;font-weight:500;<?php echo ($i == $current_page) ? 'background:#4fc3f7;color:#23272f;' : 'background:#353b48;color:#4fc3f7;'; ?>display:inline-block;">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>
    </div>
</div> 