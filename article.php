<?php
$page_title = 'Article';
require_once 'helpers.php';
$articles = read_csv('articles.csv');
$id = (int)($_GET['id'] ?? 1);
$article = null;
foreach ($articles as $a) { if ((int)$a['id'] === $id) { $article = $a; break; } }
if (!$article) { header('Location: publications.php'); exit; }
$page_title = substr($article['title'], 0, 60);
include 'header.php';
?>

<div class="breadcrumb">
    <a href="publications.php">Publications</a>
    <i class="fas fa-chevron-right"></i>
    <span><?= htmlspecialchars(substr($article['journal'],0,30)) ?>...</span>
</div>

<section class="section">
    <div style="max-width:900px;margin:0 auto">
        <!-- ARTICLE HEADER -->
        <div style="margin-bottom:40px">
            <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:16px">
                <span class="article-tag"><?= htmlspecialchars($article['category']) ?></span>
                <span class="article-tag blue"><?= htmlspecialchars($article['year']) ?></span>
                <?php if ($article['featured']=='1'): ?>
                <span class="article-tag green">Featured</span>
                <?php endif; ?>
            </div>
            <h1 style="font-size:28px;font-weight:800;line-height:1.35;color:var(--text-dark);margin-bottom:16px"><?= htmlspecialchars($article['title']) ?></h1>
            <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;margin-bottom:20px">
                <div style="display:flex;align-items:center;gap:6px;color:var(--text-gray);font-size:14px"><i class="fas fa-user" style="color:var(--orange)"></i><?= htmlspecialchars($article['authors']) ?></div>
            </div>
            <div style="display:flex;align-items:center;gap:6px;color:var(--text-blue);font-size:14px;margin-bottom:20px">
                <i class="fas fa-book" style="color:var(--orange)"></i>
                <strong><?= htmlspecialchars($article['journal']) ?></strong>
            </div>
            <div style="background:#f8f9ff;border:1px solid var(--border);border-radius:10px;padding:16px;display:flex;gap:20px;flex-wrap:wrap">
                <div style="font-size:13px;color:var(--text-gray)"><strong style="color:var(--text-dark)">DOI:</strong> <?= htmlspecialchars($article['doi']) ?></div>
                <div style="font-size:13px;color:var(--text-gray)"><strong style="color:var(--text-dark)">Year:</strong> <?= htmlspecialchars($article['year']) ?></div>
            </div>
        </div>

        <!-- ABSTRACT -->
        <div style="background:#fff;border:1px solid var(--border);border-radius:14px;padding:32px;margin-bottom:32px">
            <h2 style="font-size:18px;font-weight:700;margin-bottom:16px;color:var(--navy)">Abstract</h2>
            <p style="font-size:15px;line-height:1.8;color:var(--text-blue)"><?= htmlspecialchars($article['abstract']) ?></p>
        </div>

        <!-- ACTIONS -->
        <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:40px">
            <button onclick="copyCite('<?= addslashes($article['authors']) ?> (<?= $article['year'] ?>). <?= addslashes($article['title']) ?>. <?= addslashes($article['journal']) ?>. DOI: <?= $article['doi'] ?>')" class="btn btn-outline"><i class="fas fa-quote-right"></i> Cite</button>
            <a href="submit_manuscript.php" class="btn btn-navy"><i class="fas fa-paper-plane"></i> Submit to This Journal</a>
        </div>

        <!-- ACCESS NOTE -->
        <div style="background:linear-gradient(135deg,var(--navy),#1e1c6e);border-radius:16px;padding:40px;text-align:center;color:#fff">
            <i class="fas fa-lock" style="font-size:32px;margin-bottom:16px;opacity:.7"></i>
            <h3 style="font-size:20px;font-weight:700;margin-bottom:8px">Full Article Access</h3>
            <p style="color:rgba(255,255,255,.75);margin-bottom:24px;font-size:14px">Create a free account or sign in to read the full article, access supplementary materials, and download the PDF.</p>
            <div style="display:flex;gap:12px;justify-content:center">
                <a href="signin.php" class="btn btn-orange">Sign In to Read</a>
                <a href="signin.php?register=1" class="btn btn-white-outline">Create Free Account</a>
            </div>
        </div>

        <!-- RELATED -->
        <div style="margin-top:48px">
            <h2 style="font-size:20px;font-weight:700;margin-bottom:24px">More from <?= htmlspecialchars($article['journal']) ?></h2>
            <div class="cards-grid cards-2">
                <?php
                $related = array_filter($articles, fn($a) => $a['journal']===$article['journal'] && $a['id']!==$article['id']);
                foreach (array_slice($related,0,2) as $r): ?>
                <div class="article-card">
                    <div class="article-journal"><i class="fas fa-file-alt"></i> <?= htmlspecialchars($r['journal']) ?></div>
                    <div class="article-title"><?= htmlspecialchars(substr($r['title'],0,80)) ?>...</div>
                    <div class="article-authors"><?= htmlspecialchars($r['authors']) ?></div>
                    <a href="article.php?id=<?= $r['id'] ?>" style="display:inline-flex;margin-top:12px;font-size:13px;font-weight:600;color:var(--orange)">Read More →</a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<script>
function copyCite(text) {
    navigator.clipboard.writeText(text).then(() => alert('Citation copied!')).catch(() => prompt('Copy:', text));
}
</script>
<?php include 'footer.php'; ?>
