<!-- Thanhdat -->
<?php
if (!in_array($_SESSION['user']['role_name'] ?? '', ['admin', 'super_admin'])) {
    echo '<div style="color:red;text-align:center;margin:40px 0;">Bạn không có quyền truy cập trang này!</div>';
    exit;
}
?>

<style>
.review-detail-container {
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

.back-btn {
    padding: 10px 20px;
    background: #6c757d;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    transition: background 0.2s;
}

.back-btn:hover {
    background: #5a6268;
}

.review-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-bottom: 30px;
}

.review-info,
.review-details {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 8px;
}

.section-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e9ecef;
}

.info-item {
    margin-bottom: 15px;
}

.info-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 5px;
}

.info-value {
    color: #6c757d;
}

.product-name {
    color: #007bff;
    font-weight: 600;
}

.user-name {
    color: #28a745;
    font-weight: 600;
}

.rating-display {
    display: flex;
    align-items: center;
    gap: 10px;
}

.stars {
    color: #ffc107;
    font-size: 20px;
}

.rating-number {
    font-size: 1.2rem;
    font-weight: 600;
    color: #495057;
}

.status-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
    margin: 5px 5px 5px 0;
}

.status-approved {
    background: #d4edda;
    color: #155724;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-verified {
    background: #d1ecf1;
    color: #0c5460;
}

.review-text {
    background: white;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #007bff;
    margin-bottom: 20px;
}

.review-title-text {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 10px;
}

.review-comment {
    color: #495057;
    line-height: 1.6;
    margin-bottom: 15px;
}

.pros-cons {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-top: 15px;
}

.pros,
.cons {
    padding: 15px;
    border-radius: 6px;
}

.pros {
    background: #d4edda;
    border-left: 4px solid #28a745;
}

.cons {
    background: #f8d7da;
    border-left: 4px solid #dc3545;
}

.pros-title,
.cons-title {
    font-weight: 600;
    margin-bottom: 8px;
}

.pros-title {
    color: #155724;
}

.cons-title {
    color: #721c24;
}

.admin-reply {
    background: #e3f2fd;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #2196f3;
    margin-top: 20px;
}

.admin-reply-title {
    font-weight: 600;
    color: #1976d2;
    margin-bottom: 10px;
}

.admin-reply-content {
    color: #1565c0;
    line-height: 1.6;
}

.action-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 20px;
}

.action-btn {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    text-decoration: none;
    display: inline-block;
    transition: all 0.2s;
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

@media (max-width: 768px) {
    .review-content {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .pros-cons {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}
</style>

<div class="review-detail-container">
    <div class="review-header">
        <h1 class="review-title">Chi tiết đánh giá</h1>
        <a href="index.php?controller=review&action=index" class="back-btn">← Quay lại danh sách</a>
    </div>

    <div class="review-content">
        <!-- Thông tin cơ bản -->
        <div class="review-info">
            <h3 class="section-title">Thông tin cơ bản</h3>
            
            <div class="info-item">
                <div class="info-label">Sản phẩm:</div>
                <div class="info-value product-name"><?php echo htmlspecialchars($review['product_name'] ?? 'N/A'); ?></div>
            </div>
            
            <div class="info-item">
                <div class="info-label">Khách hàng:</div>
                <div class="info-value user-name">
                    <?php echo htmlspecialchars($review['first_name'] . ' ' . $review['last_name']); ?>
                    (<?php echo htmlspecialchars($review['username']); ?>)
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-label">Điểm đánh giá:</div>
                <div class="info-value">
                    <div class="rating-display">
                        <div class="stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?php if ($i <= $review['rating']): ?>★<?php else: ?>☆<?php endif; ?>
                            <?php endfor; ?>
                        </div>
                        <span class="rating-number"><?php echo $review['rating']; ?>/5</span>
                    </div>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-label">Trạng thái:</div>
                <div class="info-value">
                    <?php if ($review['is_approved']): ?>
                        <span class="status-badge status-approved">Đã duyệt</span>
                    <?php else: ?>
                        <span class="status-badge status-pending">Chờ duyệt</span>
                    <?php endif; ?>
                    
                    <?php if ($review['is_verified']): ?>
                        <span class="status-badge status-verified">Đã xác minh</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-label">Ngày tạo:</div>
                <div class="info-value"><?php echo date('d/m/Y H:i:s', strtotime($review['created_at'])); ?></div>
            </div>
            
            <?php if ($review['updated_at'] !== $review['created_at']): ?>
                <div class="info-item">
                    <div class="info-label">Cập nhật lần cuối:</div>
                    <div class="info-value"><?php echo date('d/m/Y H:i:s', strtotime($review['updated_at'])); ?></div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Nội dung đánh giá -->
        <div class="review-details">
            <h3 class="section-title">Nội dung đánh giá</h3>
            
            <div class="review-text">
                <div class="review-title-text"><?php echo htmlspecialchars($review['title']); ?></div>
                <div class="review-comment"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></div>
                
                <?php if (!empty($review['pros']) || !empty($review['cons'])): ?>
                    <div class="pros-cons">
                        <?php if (!empty($review['pros'])): ?>
                            <div class="pros">
                                <div class="pros-title">Ưu điểm:</div>
                                <div><?php echo nl2br(htmlspecialchars($review['pros'])); ?></div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($review['cons'])): ?>
                            <div class="cons">
                                <div class="cons-title">Nhược điểm:</div>
                                <div><?php echo nl2br(htmlspecialchars($review['cons'])); ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($review['admin_reply'])): ?>
                <div class="admin-reply">
                    <div class="admin-reply-title">Trả lời từ Admin:</div>
                    <div class="admin-reply-content"><?php echo nl2br(htmlspecialchars($review['admin_reply'])); ?></div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Các nút thao tác -->
    <div class="action-buttons">
        <?php if (!$review['is_approved']): ?>
            <a href="index.php?controller=review&action=approve&id=<?php echo $review['id']; ?>" 
               class="action-btn approve" 
               onclick="return confirm('Duyệt đánh giá này?')">Duyệt đánh giá</a>
        <?php else: ?>
            <a href="index.php?controller=review&action=hide&id=<?php echo $review['id']; ?>" 
               class="action-btn hide" 
               onclick="return confirm('Ẩn đánh giá này?')">Ẩn đánh giá</a>
        <?php endif; ?>
        
        <a href="index.php?controller=review&action=reply&id=<?php echo $review['id']; ?>" 
           class="action-btn reply">Trả lời đánh giá</a>
        
        <?php if (!$review['is_verified']): ?>
            <a href="index.php?controller=review&action=verify&id=<?php echo $review['id']; ?>" 
               class="action-btn verify" 
               onclick="return confirm('Đánh dấu đã xác minh?')">Đánh dấu xác minh</a>
        <?php endif; ?>
        
        <a href="index.php?controller=review&action=delete&id=<?php echo $review['id']; ?>" 
           class="action-btn delete" 
           onclick="return confirm('Xóa đánh giá này? Hành động này không thể hoàn tác!')">Xóa đánh giá</a>
    </div>
</div> 