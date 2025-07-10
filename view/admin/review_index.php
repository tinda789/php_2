<!-- Thanhdat -->
<?php
if (!in_array($_SESSION['user']['role_name'] ?? '', ['admin', 'super_admin'])) {
    echo '<div style="color:red;text-align:center;margin:40px 0;">Bạn không có quyền truy cập trang này!</div>';
    exit;
}
?>

<style>
.review-container {
    background: linear-gradient(135deg, #e0e7ff 0%, #f0f4ff 100%);
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 30px;
    margin-bottom: 30px;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    flex-wrap: wrap;
    gap: 15px;
}

.review-title {
    font-size: 1.8rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
}

.stats-cards {
    display: flex;
    gap: 20px;
    margin-bottom: 25px;
    flex-wrap: wrap;
}

.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 10px;
    min-width: 150px;
    text-align: center;
}

.stat-card.pending {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.stat-card.approved {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.stat-card.verified {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.filters {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    flex-wrap: wrap;
    align-items: center;
}

.search-box {
    flex: 1;
    min-width: 250px;
}

.search-box input {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

.status-filter select {
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    background: white;
}

.bulk-actions {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.bulk-btn {
    padding: 8px 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s;
}

.bulk-btn.approve {
    background: #28a745;
    color: white;
}

.bulk-btn.hide {
    background: #6c757d;
    color: #fff;
    border: 1.5px solid #444;
}

.bulk-btn.delete {
    background: #dc3545;
    color: white;
}

.bulk-btn:hover {
    opacity: 0.8;
}

.review-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.review-table th,
.review-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.review-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #495057;
}

.review-table tr:hover {
    background: #f8f9fa;
}

.review-info {
    max-width: 300px;
}

.product-name {
    font-weight: 600;
    color: #007bff;
    margin-bottom: 5px;
}

.user-name {
    color: #6c757d;
    font-size: 0.9rem;
}

.review-content {
    max-width: 400px;
}

.review-title-text {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 5px;
}

.review-comment {
    color: #6c757d;
    font-size: 0.9rem;
    line-height: 1.4;
}

.rating {
    display: flex;
    align-items: center;
    gap: 5px;
}

.stars {
    color: #ffc107;
    font-size: 16px;
}

.rating-number {
    font-weight: 600;
    color: #495057;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-approved {
    background: #d4edda;
    color: #155724;
    padding-left: 1.8em;
    position: relative;
}

.status-approved::before {
    content: '✅';
    position: absolute;
    left: 8px;
    top: 2px;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
    padding-left: 1.8em;
    position: relative;
}

.status-pending::before {
    content: '⏳';
    position: absolute;
    left: 8px;
    top: 2px;
}

.status-verified {
    background: #d1ecf1;
    color: #0c5460;
    padding-left: 1.8em;
    position: relative;
}

.status-verified::before {
    content: '👁';
    position: absolute;
    left: 8px;
    top: 2px;
}

.action-buttons {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
}

.action-btn {
    padding: 5px 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
    text-decoration: none;
    display: inline-block;
    transition: all 0.2s;
}

.action-btn.view {
    background: #17a2b8;
    color: white;
}

.action-btn.approve {
    background: #28a745;
    color: white;
}

.action-btn.hide {
    background: #ffc107;
    color: #212529;
}

.action-btn.reply {
    background: #6f42c1;
    color: white;
}

.action-btn.verify {
    background: #20c997;
    color: white;
}

.action-btn.delete {
    background: #dc3545;
    color: white;
}

.action-btn:hover {
    opacity: 0.8;
}

.pagination {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 30px;
}

.pagination a,
.pagination span {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-decoration: none;
    color: #007bff;
}

.pagination .current {
    background: #007bff;
    color: white;
    border-color: #007bff;
}

.pagination a:hover {
    background: #f8f9fa;
}

@media (max-width: 768px) {
    .review-table {
        font-size: 14px;
    }
    
    .review-table th,
    .review-table td {
        padding: 8px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .stats-cards {
        flex-direction: column;
    }
    
    .filters {
        flex-direction: column;
        align-items: stretch;
    }
}
</style>

<div class="review-container">
    <div class="review-header">
        <h1 class="review-title">Quản lý đánh giá sản phẩm</h1>
        <div>
            <a href="index.php?controller=admin&action=dashboard" class="action-btn view">← Về Dashboard</a>
        </div>
    </div>

    <!-- Thống kê -->
    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-number"><?php echo $stats['total']; ?></div>
            <div class="stat-label">Tổng đánh giá</div>
        </div>
        <div class="stat-card approved">
            <div class="stat-number"><?php echo $stats['approved']; ?></div>
            <div class="stat-label">Đã duyệt</div>
        </div>
        <div class="stat-card pending">
            <div class="stat-number"><?php echo $stats['pending']; ?></div>
            <div class="stat-label">Chờ duyệt</div>
        </div>
        <div class="stat-card verified">
            <div class="stat-number"><?php echo $stats['verified']; ?></div>
            <div class="stat-label">Đã xác minh</div>
        </div>
    </div>

    <!-- Bộ lọc -->
    <form method="GET" action="index.php" class="filters">
        <input type="hidden" name="controller" value="review">
        <input type="hidden" name="action" value="index">
        
        <div class="search-box">
            <input type="text" name="search" placeholder="Tìm kiếm theo sản phẩm, tên khách hàng..." 
                   value="<?php echo htmlspecialchars($search); ?>">
        </div>
        
        <div class="status-filter">
            <select name="status">
                <option value="">Tất cả trạng thái</option>
                <option value="approved" <?php echo $status === 'approved' ? 'selected' : ''; ?>>Đã duyệt</option>
                <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Chờ duyệt</option>
                <option value="verified" <?php echo $status === 'verified' ? 'selected' : ''; ?>>Đã xác minh</option>
            </select>
        </div>
        
        <button type="submit" class="action-btn view">Lọc</button>
        <a href="index.php?controller=review&action=index" class="action-btn hide">Xóa bộ lọc</a>
    </form>

    <!-- Thao tác hàng loạt -->
    <form method="POST" action="index.php?controller=review&action=bulk_approve" id="bulkForm">
        <div class="bulk-actions">
            <button type="submit" class="bulk-btn approve">Duyệt đã chọn</button>
            <button type="button" onclick="bulkAction('bulk_hide')" class="bulk-btn hide">Ẩn đã chọn</button>
            <button type="button" onclick="bulkAction('bulk_delete')" class="bulk-btn delete">Xóa đã chọn</button>
        </div>

        <!-- Bảng đánh giá -->
        <table class="review-table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll" onchange="toggleSelectAll()"></th>
                    <th>Sản phẩm & Khách hàng</th>
                    <th>Nội dung đánh giá</th>
                    <th>Điểm</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reviews)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 60px 20px; color: #6c757d;">
                            <div style="font-size: 3.2rem; margin-bottom: 12px;">📭</div>
                            <div style="font-size: 1.15rem; font-weight: 500; margin-bottom: 6px;">Không có đánh giá nào</div>
                            <div style="font-size: 1rem; color: #888;">Chờ đánh giá từ khách hàng đầu tiên hoặc kiểm tra lại sau!</div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="review_ids[]" value="<?php echo $review['id']; ?>" class="review-checkbox">
                            </td>
                            <td class="review-info">
                                <div class="product-name"><?php echo htmlspecialchars($review['product_name'] ?? 'N/A'); ?></div>
                                <div class="user-name">
                                    <?php echo htmlspecialchars($review['first_name'] . ' ' . $review['last_name']); ?>
                                    (<?php echo htmlspecialchars($review['username']); ?>)
                                </div>
                            </td>
                            <td class="review-content">
                                <div class="review-title-text"><?php echo htmlspecialchars($review['title']); ?></div>
                                <div class="review-comment">
                                    <?php echo htmlspecialchars(substr($review['comment'], 0, 100)); ?>
                                    <?php if (strlen($review['comment']) > 100): ?>...<?php endif; ?>
                                </div>
                                <?php if (!empty($review['admin_reply'])): ?>
                                    <div style="margin-top: 8px; padding: 8px; background: #e3f2fd; border-radius: 4px; font-size: 0.85rem;">
                                        <strong>Trả lời:</strong> <?php echo htmlspecialchars(substr($review['admin_reply'], 0, 80)); ?>
                                        <?php if (strlen($review['admin_reply']) > 80): ?>...<?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="rating">
                                    <div class="stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= $review['rating']): ?>★<?php else: ?>☆<?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="rating-number"><?php echo $review['rating']; ?>/5</span>
                                </div>
                            </td>
                            <td>
                                <?php if ($review['is_approved']): ?>
                                    <span class="status-badge status-approved">Đã duyệt</span>
                                <?php else: ?>
                                    <span class="status-badge status-pending">Chờ duyệt</span>
                                <?php endif; ?>
                                <?php if ($review['is_verified']): ?>
                                    <br><span class="status-badge status-verified">Đã xác minh</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($review['created_at'])); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="index.php?controller=review&action=view&id=<?php echo $review['id']; ?>" 
                                       class="action-btn view">Xem</a>
                                    
                                    <?php if (!$review['is_approved']): ?>
                                        <a href="index.php?controller=review&action=approve&id=<?php echo $review['id']; ?>" 
                                           class="action-btn approve" 
                                           onclick="return confirm('Duyệt đánh giá này?')">Duyệt</a>
                                    <?php else: ?>
                                        <a href="index.php?controller=review&action=hide&id=<?php echo $review['id']; ?>" 
                                           class="action-btn hide" 
                                           onclick="return confirm('Ẩn đánh giá này?')">Ẩn</a>
                                    <?php endif; ?>
                                    
                                    <a href="index.php?controller=review&action=reply&id=<?php echo $review['id']; ?>" 
                                       class="action-btn reply">Trả lời</a>
                                    
                                    <?php if (!$review['is_verified']): ?>
                                        <a href="index.php?controller=review&action=verify&id=<?php echo $review['id']; ?>" 
                                           class="action-btn verify" 
                                           onclick="return confirm('Đánh dấu đã xác minh?')">Xác minh</a>
                                    <?php endif; ?>
                                    
                                    <a href="index.php?controller=review&action=delete&id=<?php echo $review['id']; ?>" 
                                       class="action-btn delete" 
                                       onclick="return confirm('Xóa đánh giá này? Hành động này không thể hoàn tác!')">Xóa</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </form>

    <!-- Phân trang -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="index.php?controller=review&action=index&page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>">← Trước</a>
            <?php endif; ?>
            
            <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                <?php if ($i == $page): ?>
                    <span class="current"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="index.php?controller=review&action=index&page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            
            <?php if ($page < $totalPages): ?>
                <a href="index.php?controller=review&action=index&page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>">Sau →</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<script>
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.review-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

function bulkAction(action) {
    const checkboxes = document.querySelectorAll('.review-checkbox:checked');
    if (checkboxes.length === 0) {
        alert('Vui lòng chọn ít nhất một đánh giá!');
        return;
    }
    
    if (action === 'bulk_delete') {
        if (!confirm('Bạn có chắc chắn muốn xóa ' + checkboxes.length + ' đánh giá đã chọn? Hành động này không thể hoàn tác!')) {
            return;
        }
    }
    
    const form = document.getElementById('bulkForm');
    form.action = 'index.php?controller=review&action=' + action;
    form.submit();
}
</script> 