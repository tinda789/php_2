<!-- Thanhdat -->
<?php
if (!in_array($_SESSION['user']['role_name'] ?? '', ['admin', 'super_admin'])) {
    echo '<div style="color:red;text-align:center;margin:40px 0;">Bạn không có quyền truy cập trang này!</div>';
    exit;
}
?>

<style>
.reply-container {
    background: linear-gradient(135deg, #e0e7ff 0%, #f0f4ff 100%);
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 30px;
    margin-bottom: 30px;
}

.reply-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    flex-wrap: wrap;
    gap: 15px;
}

.reply-title {
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

.review-summary {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 25px;
    border-left: 4px solid #007bff;
}

.review-summary h3 {
    margin: 0 0 15px 0;
    color: #2c3e50;
    font-size: 1.2rem;
}

.review-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 15px;
}

.info-item {
    display: flex;
    flex-direction: column;
}

.info-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 5px;
    font-size: 0.9rem;
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
    gap: 8px;
}

.stars {
    color: #ffc107;
    font-size: 16px;
}

.rating-number {
    font-weight: 600;
    color: #495057;
}

.review-content {
    background: white;
    padding: 15px;
    border-radius: 6px;
    border: 1px solid #e9ecef;
}

.review-title-text {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 8px;
}

.review-comment {
    color: #495057;
    line-height: 1.5;
}

.reply-form {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 8px;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
}

.form-textarea {
    width: 100%;
    min-height: 150px;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    font-family: inherit;
    resize: vertical;
    transition: border-color 0.2s;
}

.form-textarea:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

.form-help {
    font-size: 0.85rem;
    color: #6c757d;
    margin-top: 5px;
}

.form-buttons {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    text-decoration: none;
    display: inline-block;
    transition: all 0.2s;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #c82333;
}

.error-message {
    background: #f8d7da;
    color: #721c24;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 20px;
    border: 1px solid #f5c6cb;
}

.success-message {
    background: #d4edda;
    color: #155724;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 20px;
    border: 1px solid #c3e6cb;
}

@media (max-width: 768px) {
    .review-info {
        grid-template-columns: 1fr;
    }
    
    .form-buttons {
        flex-direction: column;
    }
    
    .btn {
        text-align: center;
    }
}
</style>

<div class="reply-container">
    <div class="reply-header">
        <h1 class="reply-title">Trả lời đánh giá</h1>
        <a href="index.php?controller=review&action=index" class="back-btn">← Quay lại danh sách</a>
    </div>

    <!-- Thông báo lỗi/thành công -->
    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Tóm tắt đánh giá -->
    <div class="review-summary">
        <h3>Tóm tắt đánh giá</h3>
        
        <div class="review-info">
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
                <div class="info-label">Ngày đánh giá:</div>
                <div class="info-value"><?php echo date('d/m/Y H:i', strtotime($review['created_at'])); ?></div>
            </div>
        </div>
        
        <div class="review-content">
            <div class="review-title-text"><?php echo htmlspecialchars($review['title']); ?></div>
            <div class="review-comment"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></div>
        </div>
    </div>

    <!-- Form trả lời -->
    <div class="reply-form">
        <h3>Trả lời từ Admin</h3>
        
        <form method="POST" action="index.php?controller=review&action=reply&id=<?php echo $review['id']; ?>">
            <div class="form-group">
                <label for="admin_reply" class="form-label">Nội dung trả lời:</label>
                <textarea 
                    id="admin_reply" 
                    name="admin_reply" 
                    class="form-textarea" 
                    placeholder="Nhập nội dung trả lời cho khách hàng..."
                    required
                ><?php echo htmlspecialchars($review['admin_reply'] ?? ''); ?></textarea>
                <div class="form-help">
                    Trả lời sẽ được hiển thị công khai dưới đánh giá của khách hàng.
                </div>
            </div>
            
            <div class="form-buttons">
                <button type="submit" class="btn btn-primary">
                    <?php echo !empty($review['admin_reply']) ? 'Cập nhật trả lời' : 'Gửi trả lời'; ?>
                </button>
                
                <a href="index.php?controller=review&action=view&id=<?php echo $review['id']; ?>" class="btn btn-secondary">
                    Xem chi tiết
                </a>
                
                <a href="index.php?controller=review&action=index" class="btn btn-secondary">
                    Hủy bỏ
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-resize textarea
document.getElementById('admin_reply').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
});

// Set initial height
window.addEventListener('load', function() {
    const textarea = document.getElementById('admin_reply');
    textarea.style.height = 'auto';
    textarea.style.height = (textarea.scrollHeight) + 'px';
});
</script> 