<?php
require_once 'view/layout/admin_layout.php';
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-eye"></i> Xem chi tiết Tin tức
        </h1>
        <div>
            <a href="index.php?controller=news&action=edit&id=<?= $news['id'] ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i> Sửa
            </a>
            <a href="index.php?controller=news&action=index" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Nội dung bài viết -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-newspaper"></i> Nội dung bài viết
                    </h6>
                </div>
                <div class="card-body">
                    <h2 class="mb-3"><?= htmlspecialchars($news['title']) ?></h2>
                    
                    <?php if ($news['image']): ?>
                        <div class="text-center mb-4">
                            <img src="uploads/news/<?= $news['image'] ?>" 
                                 alt="<?= htmlspecialchars($news['title']) ?>" 
                                 class="img-fluid rounded" 
                                 style="max-height: 400px;">
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($news['summary']): ?>
                        <div class="alert alert-info">
                            <h6 class="alert-heading"><i class="fas fa-info-circle"></i> Tóm tắt</h6>
                            <p class="mb-0"><?= htmlspecialchars($news['summary']) ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <div class="news-content">
                        <?= nl2br(htmlspecialchars($news['content'])) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Thông tin bài viết -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Thông tin bài viết
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>ID:</strong></td>
                            <td>#<?= $news['id'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Slug:</strong></td>
                            <td><code><?= $news['slug'] ?></code></td>
                        </tr>
                        <tr>
                            <td><strong>Chuyên mục:</strong></td>
                            <td>
                                <?php
                                $category_labels = [
                                    'tin-tuc' => '<span class="badge badge-primary"><i class="fas fa-newspaper"></i> Tin tức</span>',
                                    'khuyen-mai' => '<span class="badge badge-success"><i class="fas fa-tag"></i> Khuyến mãi</span>',
                                    'huong-dan' => '<span class="badge badge-info"><i class="fas fa-book"></i> Hướng dẫn</span>',
                                    'tuyen-dung' => '<span class="badge badge-warning"><i class="fas fa-briefcase"></i> Tuyển dụng</span>'
                                ];
                                echo $category_labels[$news['category']] ?? $news['category'];
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Trạng thái:</strong></td>
                            <td>
                                <?php if ($news['status'] == 'published'): ?>
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle"></i> Đã xuất bản
                                    </span>
                                <?php elseif ($news['status'] == 'draft'): ?>
                                    <span class="badge badge-warning">
                                        <i class="fas fa-edit"></i> Bản nháp
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">
                                        <i class="fas fa-archive"></i> Đã lưu trữ
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Lượt xem:</strong></td>
                            <td>
                                <span class="badge badge-light">
                                    <i class="fas fa-eye"></i> <?= number_format($news['views']) ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Ngày tạo:</strong></td>
                            <td><?= date('d/m/Y H:i', strtotime($news['created_at'])) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Cập nhật:</strong></td>
                            <td><?= date('d/m/Y H:i', strtotime($news['updated_at'])) ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Thao tác nhanh -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tools"></i> Thao tác nhanh
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="index.php?controller=news&action=edit&id=<?= $news['id'] ?>" 
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Chỉnh sửa bài viết
                        </a>
                        
                        <?php if ($news['status'] == 'published'): ?>
                            <a href="index.php?controller=news&action=changeStatus&id=<?= $news['id'] ?>&status=draft" 
                               class="btn btn-warning btn-sm"
                               onclick="return confirm('Bạn có chắc muốn chuyển bài viết này thành bản nháp?')">
                                <i class="fas fa-edit"></i> Chuyển thành bản nháp
                            </a>
                        <?php else: ?>
                            <a href="index.php?controller=news&action=changeStatus&id=<?= $news['id'] ?>&status=published" 
                               class="btn btn-success btn-sm"
                               onclick="return confirm('Bạn có chắc muốn xuất bản bài viết này?')">
                                <i class="fas fa-check"></i> Xuất bản bài viết
                            </a>
                        <?php endif; ?>
                        
                        <a href="index.php?controller=news&action=delete&id=<?= $news['id'] ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Bạn có chắc muốn xóa bài viết này?')">
                            <i class="fas fa-trash"></i> Xóa bài viết
                        </a>
                    </div>
                </div>
            </div>

            <!-- Thống kê -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar"></i> Thống kê
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-right">
                                <h4 class="text-primary"><?= number_format($news['views']) ?></h4>
                                <small class="text-muted">Lượt xem</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success">
                                <?php
                                $days_ago = floor((time() - strtotime($news['created_at'])) / (60 * 60 * 24));
                                echo $days_ago;
                                ?>
                            </h4>
                            <small class="text-muted">Ngày tuổi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.news-content {
    line-height: 1.8;
    font-size: 16px;
}

.news-content p {
    margin-bottom: 1rem;
}

.table-borderless td {
    border: none;
    padding: 0.5rem 0;
}

.table-borderless td:first-child {
    width: 40%;
    color: #6c757d;
}
</style>

<?php require_once 'view/layout/footer.php'; ?> 