<?php
$page_title = 'Insights & Updates';
require_once 'helpers.php';
$posts = read_csv('insights.csv');
include 'header.php';
?>

<div class="page-hero" style="min-height:260px">
    <div class="hero-pattern"></div>
    <div class="page-hero-content">
        <div class="section-eyebrow">INSIGHTS & UPDATES</div>
        <h1 style="color:#fff;font-size:38px;font-weight:800;margin-bottom:12px">From the Afrika Scholar Knowledge Desk</h1>
        <p style="color:rgba(255,255,255,.8);font-size:15px">Analysis, commentary, and updates from Africa's academic publishing frontier</p>
    </div>
</div>

<section class="section">
    <div class="cards-grid cards-3">
        <?php foreach ($posts as $post): ?>
        <div class="blog-card">
            <div class="blog-img" style="background:linear-gradient(135deg,#4B5FAB,#2D2B8F);display:flex;align-items:center;justify-content:center">
                <i class="fas fa-book-open" style="font-size:60px;color:rgba(255,255,255,.2)"></i>
            </div>
            <div class="blog-body">
                <div class="blog-date"><?= htmlspecialchars($post['date']) ?></div>
                <div class="blog-title"><?= htmlspecialchars($post['title']) ?></div>
                <div class="blog-excerpt"><?= htmlspecialchars($post['excerpt']) ?></div>
                <a href="insight.php?id=<?= $post['id'] ?>" class="blog-link">Read More →</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<?php include 'footer.php'; ?>
