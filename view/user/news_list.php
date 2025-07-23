<?php include 'view/layout/header.php'; ?>
<style>
.news-list-section {
    min-height: 60vh;
    margin-top: 32px;
}
.news-list-title {
    color: #1976d2;
    font-weight: 700;
    margin-bottom: 28px;
    font-size: 2rem;
    letter-spacing: 1px;
}
.news-card {
    border-radius: 14px;
    box-shadow: 0 2px 12px rgba(25, 118, 210, 0.08);
    background: #fff;
    transition: box-shadow 0.2s, transform 0.2s;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
}
.news-card:hover {
    box-shadow: 0 6px 24px rgba(25, 118, 210, 0.18);
    transform: translateY(-4px) scale(1.02);
}
.news-card-img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    background: #e3f2fd;
}
.news-card-body {
    padding: 18px 18px 10px 18px;
    flex: 1 1 auto;
    display: flex;
    flex-direction: column;
}
.news-card-title {
    font-size: 1.18rem;
    font-weight: 600;
    color: #1976d2;
    margin-bottom: 8px;
    min-height: 48px;
}
.news-card-summary {
    color: #555;
    font-size: 0.98rem;
    margin-bottom: 12px;
    min-height: 44px;
}
.news-card-footer {
    background: #f4f6fb;
    color: #888;
    font-size: 0.93rem;
    padding: 10px 18px;
    border-top: 1px solid #e3eaf2;
}
.news-card-btn {
    margin-top: auto;
    background: #1976d2;
    color: #fff;
    border-radius: 22px;
    padding: 8px 22px;
    font-weight: 500;
    text-decoration: none;
    transition: background 0.2s;
    display: inline-block;
    border: none;
}
.news-card-btn:hover {
    background: #0d47a1;
    color: #fff;
}
@media (max-width: 900px) {
    .news-card-img { height: 140px; }
    .news-list-title { font-size: 1.3rem; }
}
@media (max-width: 600px) {
    .news-card-img { height: 100px; }
    .news-list-section { margin-top: 12px; }
}
</style>
<div class="container news-list-section">
    <h2 class="news-list-title"><i class="fas fa-newspaper"></i> Tin tức mới nhất</h2>
    <?php if (empty($news_list)): ?>
        <div class="alert alert-info">Chưa có tin tức nào được đăng.</div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($news_list as $news): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="news-card h-100">
                        <?php if (!empty($news['image_url'])): ?>
                            <img src="uploads/news/<?= htmlspecialchars($news['image_url']) ?>" class="news-card-img" alt="<?= htmlspecialchars($news['title']) ?>">
                        <?php endif; ?>
                        <div class="news-card-body">
                            <div class="news-card-title"><?= htmlspecialchars($news['title']) ?></div>
                            <div class="news-card-summary">
                                <?= htmlspecialchars(mb_substr($news['summary'] ?? '', 0, 100)) ?><?= (mb_strlen($news['summary'] ?? '') > 100 ? '...' : '') ?>
                            </div>
                            <a href="index.php?controller=news&action=view&id=<?= $news['id'] ?>" class="news-card-btn">Xem chi tiết</a>
                        </div>
                        <div class="news-card-footer">
                            <i class="far fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($news['created_at'])) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- Phân trang nếu cần -->
        <?php if ($total_pages > 1): ?>
        <nav class="mt-4">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="index.php?controller=news&action=list&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php include 'view/layout/footer.php'; ?> 