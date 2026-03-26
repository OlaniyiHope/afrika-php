<?php
require_once 'helpers.php';
$posts = read_csv('insights.csv');
$id = (int)($_GET['id'] ?? 1);
$post = null;
foreach ($posts as $p) { if ((int)$p['id'] === $id) { $post = $p; break; } }
if (!$post) { header('Location: insights.php'); exit; }
$page_title = $post['title'];
include 'header.php';
$others = array_filter($posts, fn($p) => $p['id'] !== $post['id']);
?>

<div class="breadcrumb">
    <a href="insights.php">Insights</a>
    <i class="fas fa-chevron-right"></i>
    <span><?= htmlspecialchars(substr($post['title'],0,40)) ?>...</span>
</div>

<section class="section">
    <div style="max-width:800px;margin:0 auto">
        <div style="margin-bottom:12px">
            <span class="article-tag"><?= htmlspecialchars($post['category']) ?></span>
        </div>
        <h1 style="font-size:32px;font-weight:800;line-height:1.3;margin-bottom:16px"><?= htmlspecialchars($post['title']) ?></h1>
        <div style="display:flex;align-items:center;gap:12px;color:var(--text-gray);font-size:14px;margin-bottom:32px">
            <span><i class="fas fa-calendar" style="color:var(--orange)"></i> <?= htmlspecialchars($post['date']) ?></span>
            <span>Afrika Scholar Editorial Team</span>
        </div>
        <div style="background:linear-gradient(135deg,#4B5FAB,#2D2B8F);border-radius:16px;height:320px;display:flex;align-items:center;justify-content:center;margin-bottom:40px">
            <i class="fas fa-book-open" style="font-size:80px;color:rgba(255,255,255,.2)"></i>
        </div>
        <div style="font-size:16px;line-height:1.9;color:var(--text-blue)">
            <p style="margin-bottom:20px"><?= htmlspecialchars($post['excerpt']) ?></p>
            <p style="margin-bottom:20px">African scholarship has long faced systemic challenges in gaining global visibility and recognition. Despite the continent producing increasingly sophisticated research across disciplines, African scholars often find their work underrepresented in major international databases, citation networks, and academic discourse.</p>
            <h2 style="font-size:22px;font-weight:700;color:var(--text-dark);margin:32px 0 16px">The Core Challenge</h2>
            <p style="margin-bottom:20px">The academic publishing landscape is dominated by Western institutions and publishing houses, creating structural barriers for African researchers seeking to disseminate their work. High publication fees, limited access to international journals, and inadequate institutional support are among the primary obstacles.</p>
            <p style="margin-bottom:20px">Afrika Scholar addresses these challenges directly by providing a dedicated publishing infrastructure that is built for African contexts, governed by African academic standards, and committed to open access principles that ensure research reaches those who need it most.</p>
            <h2 style="font-size:22px;font-weight:700;color:var(--text-dark);margin:32px 0 16px">Building for the Future</h2>
            <p style="margin-bottom:20px">Our platform is designed not just for today's researchers, but for the next generation of African scholars who will drive knowledge production across the continent. By combining rigorous peer review with modern publishing infrastructure and AI-powered research tools, we are creating an ecosystem where African research can thrive.</p>
            <div style="border-left:4px solid var(--orange);padding:20px 24px;background:rgba(232,101,26,.05);border-radius:0 10px 10px 0;margin:32px 0">
                <p style="font-size:15px;font-style:italic;color:var(--text-dark)">"The future of African scholarship depends on infrastructure that is built by Africans, for Africans, and governed by the highest standards of academic integrity."</p>
                <p style="font-size:13px;color:var(--text-gray);margin-top:8px">— Afrika Scholar Editorial Team</p>
            </div>
        </div>

        <div style="display:flex;gap:12px;margin-top:40px;padding-top:40px;border-top:1px solid var(--border)">
            <a href="insights.php" class="btn btn-outline">← All Insights</a>
            <a href="submit_manuscript.php" class="btn btn-orange">Publish Your Research →</a>
        </div>
    </div>
</section>

<!-- MORE POSTS -->
<section class="section" style="background:#f8f9ff;border-top:1px solid var(--border)">
    <h2 style="font-size:22px;font-weight:700;margin-bottom:28px">More Insights</h2>
    <div class="cards-grid cards-3">
        <?php foreach (array_slice($others, 0, 3) as $o): ?>
        <div class="blog-card">
            <div class="blog-img" style="background:linear-gradient(135deg,#4B5FAB,#2D2B8F);display:flex;align-items:center;justify-content:center">
                <i class="fas fa-book-open" style="font-size:48px;color:rgba(255,255,255,.2)"></i>
            </div>
            <div class="blog-body">
                <div class="blog-date"><?= htmlspecialchars($o['date']) ?></div>
                <div class="blog-title"><?= htmlspecialchars($o['title']) ?></div>
                <a href="insight.php?id=<?= $o['id'] ?>" class="blog-link">Read More →</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<?php include 'footer.php'; ?>
