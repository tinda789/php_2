<?php include 'view/layout/header.php'; ?>
<style>
.news-detail-main { margin-top: 32px; min-height: 60vh; }
.news-detail-title { color: #1976d2; font-size: 2rem; font-weight: 700; margin-bottom: 12px; }
.news-detail-meta { color: #888; font-size: 0.98rem; margin-bottom: 18px; }
.news-detail-img { width: 100%; max-height: 340px; object-fit: cover; border-radius: 12px; margin-bottom: 18px; background: #e3f2fd; }
.news-detail-content { font-size: 1.08rem; line-height: 1.7; color: #222; margin-bottom: 32px; }
.news-detail-category { display: inline-block; background: #e3f2fd; color: #1976d2; border-radius: 8px; padding: 3px 12px; font-size: 0.97rem; margin-right: 8px; }
.news-detail-tags { margin-top: 8px; }
.news-detail-tag { display: inline-block; background: #fce4ec; color: #c2185b; border-radius: 8px; padding: 3px 10px; font-size: 0.95rem; margin-right: 6px; margin-bottom: 4px; }
.news-sidebar-title { font-size: 1.1rem; font-weight: 600; color: #1976d2; margin-bottom: 10px; margin-top: 24px; }
.news-sidebar-list { list-style: none; padding-left: 0; }
.news-sidebar-list li { margin-bottom: 8px; }
.news-sidebar-list a { color: #1976d2; text-decoration: none; transition: color 0.2s; }
.news-sidebar-list a:hover { color: #0d47a1; text-decoration: underline; }
.news-featured-list li { margin-bottom: 14px; }
.news-featured-title { font-size: 1rem; font-weight: 500; color: #222; }
.news-featured-date { color: #888; font-size: 0.93rem; }
@media (max-width: 900px) { .news-detail-title { font-size: 1.3rem; } }
</style>
<div class="container news-detail-main">
  <div class="row g-4">
    <div class="col-lg-8">
      <?php if (!empty($news['image_url'])): ?>
        <img src="uploads/news/<?= htmlspecialchars($news['image_url']) ?>" class="news-detail-img" alt="<?= htmlspecialchars($news['title']) ?>">
      <?php endif; ?>
      <div class="news-detail-title"><?= htmlspecialchars($news['title']) ?></div>
      <div class="news-detail-meta">
        <span class="news-detail-category">
          <i class="fas fa-folder"></i> <?= htmlspecialchars($news['category_name'] ?? $news['category']) ?>
        </span>
        <span><i class="far fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($news['created_at'])) ?></span>
        <span style="margin-left:12px;"><i class="fas fa-eye"></i> <?= (int)($news['views'] ?? 0) ?> lượt xem</span>
      </div>
      <div class="news-detail-content">
        <?= nl2br(htmlspecialchars($news['content'])) ?>
      </div>
      <?php if (!empty($news['tags'])): ?>
        <div class="news-detail-tags">
          <?php foreach (explode(',', $news['tags']) as $tag): ?>
            <span class="news-detail-tag">#<?= htmlspecialchars(trim($tag)) ?></span>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
    <div class="col-lg-4">
      <div class="news-sidebar-title"><i class="fas fa-list"></i> Danh mục</div>
      <ul class="news-sidebar-list">
        <?php foreach ($categories as $cat): ?>
          <li><a href="index.php?controller=news&action=list&category=<?= $cat['id'] ?>"> <?= htmlspecialchars($cat['name']) ?> </a></li>
        <?php endforeach; ?>
      </ul>
      <?php if (!empty($tags)): ?>
        <div class="news-sidebar-title"><i class="fas fa-tags"></i> Tags</div>
        <ul class="news-sidebar-list">
          <?php foreach ($tags as $tag): ?>
            <li><a href="index.php?controller=news&action=list&tag=<?= urlencode($tag['name']) ?>">#<?= htmlspecialchars($tag['name']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
      <div class="news-sidebar-title"><i class="fas fa-star"></i> Tin nổi bật</div>
      <ul class="news-sidebar-list news-featured-list">
        <?php foreach ($featuredNews as $fnews): ?>
          <li>
            <a href="index.php?controller=news&action=view&id=<?= $fnews['id'] ?>" class="news-featured-title">
              <?= htmlspecialchars($fnews['title']) ?>
            </a><br>
            <span class="news-featured-date"><i class="far fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($fnews['created_at'])) ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</div>
<?php include 'view/layout/footer.php'; ?> 