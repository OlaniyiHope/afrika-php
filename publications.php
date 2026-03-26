<?php
$page_title = 'Publications';
require_once 'helpers.php';

$articles    = read_csv('articles.csv');
$journals    = read_csv('journals.csv');
$active_cat  = $_GET['cat']    ?? 'All';
$search      = trim($_GET['search'] ?? '');
$categories  = ['All','STEM','Social Sciences','Law & Governance','Business & Economics','Environmental Sciences','Health Sciences'];
$sort_by     = $_GET['sort']   ?? 'recent';
$view_mode   = $_GET['view']   ?? 'grid';

/* ── FILTER ─────────────────────────────────────────────────────────── */
$filtered = $articles;

if ($active_cat !== 'All')
    $filtered = array_filter($filtered, fn($a) => ($a['category'] ?? '') === $active_cat);

if ($search)
    $filtered = array_filter($filtered, fn($a) =>
        stripos($a['title']    ?? '', $search) !== false ||
        stripos($a['authors']  ?? '', $search) !== false ||
        stripos($a['abstract'] ?? '', $search) !== false
    );

$filtered = array_values($filtered);

/* ── SORT ────────────────────────────────────────────────────────────── */
if ($sort_by === 'alphabetical') {
    usort($filtered, fn($a,$b) => strcmp($a['title'] ?? '', $b['title'] ?? ''));
} else {
    // Most recent — stable sort by year DESC, then by id ASC as tiebreaker
usort($filtered, function($a, $b) {
    $year_a = (int) preg_replace('/[^0-9]/', '', $a['year'] ?? '0');
    $year_b = (int) preg_replace('/[^0-9]/', '', $b['year'] ?? '0');
    $id_a   = (int) preg_replace('/[^0-9]/', '', $a['id']   ?? '0');
    $id_b   = (int) preg_replace('/[^0-9]/', '', $b['id']   ?? '0');
    return $year_b !== $year_a ? $year_b - $year_a : $id_a - $id_b;
});
}

/* ── PAGINATION ──────────────────────────────────────────────────────── */
$per_page     = 10;
$total        = count($filtered);
$total_pages  = max(1, (int)ceil($total / $per_page));
$current_page = max(1, min((int)($_GET['page'] ?? 1), $total_pages));
$offset       = ($current_page - 1) * $per_page;
$paginated    = array_slice($filtered, $offset, $per_page);

/* ── SIDEBAR VALUES ──────────────────────────────────────────────────── */
$all_years   = array_unique(array_filter(array_column($articles, 'year')));
$all_regions = []; // not in CSV
rsort($all_years);

/* ── FLAGS ───────────────────────────────────────────────────────────── */
$filter_year   = '';   // not filtering by these since CSV doesn't have them
$filter_region = '';
$filter_access = '';
$has_filters   = ($active_cat !== 'All') || $search;

/* ── QUERY-STRING HELPER ─────────────────────────────────────────────── */
function qstr(array $overrides = []): string {
    $params = array_merge($_GET, $overrides);
    $params = array_filter($params, fn($v) => $v !== '' && $v !== null && $v !== false);
    return count($params) ? '?' . http_build_query($params) : '?';
}

include 'header.php';
?>
<style>
/* ══ PUBLICATIONS PAGE ════════════════════════════════════════════════ */

/* Hero */
.pub-hero {
    position: relative; min-height: 400px;
    display: flex; align-items: center; justify-content: center; overflow: hidden;
}
.pub-hero-bg {
    position: absolute; inset: 0;
    background-image: url('asset/publications-journals.jpg');
    background-size: cover; background-position: center; z-index: 0;
}
.pub-hero-overlay { position: absolute; inset: 0; background: rgba(10,22,55,.85); z-index: 1; }
.pub-hero-dots {
    position: absolute; inset: 0; z-index: 2; opacity: .08;
    background-image: radial-gradient(circle, #fff 1px, transparent 1px);
    background-size: 16px 16px;
}
.pub-hero-inner {
    position: relative; z-index: 3; text-align: center;
    color: #fff; padding: 80px 20px; max-width: 760px;
}
.pub-hero-eyebrow {
    font-size: 11px; font-weight: 700; letter-spacing: .14em;
    text-transform: uppercase; color: var(--orange,#e8651a); margin-bottom: 14px;
}
.pub-hero-title  { font-size: clamp(32px,5vw,52px); font-weight: 800; margin: 0 0 18px; line-height: 1.1; color: #fff; }
.pub-hero-sub    { font-size: 17px; color: rgba(255,255,255,.80); margin: 0; line-height: 1.6; }

/* Sticky search bar */
.pub-search-bar {
    position: sticky; top: 0; z-index: 40;
    background: #fff; border-bottom: 1px solid #e5e7eb; padding: 14px 20px;
}
.pub-search-inner {
    max-width: 1200px; margin: 0 auto;
    display: flex; flex-wrap: wrap; gap: 10px; align-items: center;
}
.pub-search-field { position: relative; flex: 1; min-width: 220px; }
.pub-search-field i {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    color: #94a3b8; font-size: 13px; pointer-events: none;
}
.pub-search-field input {
    width: 100%; padding: 9px 12px 9px 34px;
    border: 1px solid #e5e7eb; border-radius: 8px;
    font-size: 13px; color: #111827; outline: none;
    background: #fff; box-sizing: border-box; transition: border-color .18s;
}
.pub-search-field input:focus { border-color: var(--orange,#e8651a); }
.pub-sort-select {
    padding: 9px 12px; border: 1px solid #e5e7eb; border-radius: 8px;
    font-size: 13px; color: #374151; background: #fff;
    outline: none; cursor: pointer; min-width: 140px;
}
.pub-view-toggle  { display: flex; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; }
.pub-view-btn     { padding: 8px 12px; background: #fff; border: none; cursor: pointer; font-size: 13px; color: #64748b; transition: all .18s; text-decoration: none; display: flex; align-items: center; }
.pub-view-btn.active { background: #f1f5f9; color: #111827; }

/* Filter pills */
.pub-filter-pills { max-width: 1200px; margin: 8px auto 0; display: flex; flex-wrap: wrap; gap: 6px; align-items: center; }
.pub-pill {
    display: inline-flex; align-items: center; gap: 4px;
    background: #f1f5f9; border: 1px solid #e5e7eb;
    border-radius: 999px; padding: 3px 10px; font-size: 12px; color: #374151; font-weight: 600;
}
.pub-pill a { color: #94a3b8; text-decoration: none; font-size: 11px; margin-left: 2px; }
.pub-pill a:hover { color: #ef4444; }
.pub-clear-link { font-size: 12px; color: var(--orange,#e8651a); text-decoration: none; font-weight: 600; }
.pub-clear-link:hover { text-decoration: underline; }

/* Layout */
.pub-layout { max-width: 1200px; margin: 0 auto; display: flex; gap: 32px; padding: 40px 20px; align-items: flex-start; }

/* Sidebar */
.pub-sidebar { width: 220px; flex-shrink: 0; position: sticky; top: 80px; }
.pub-sidebar-title { font-size: 14px; font-weight: 700; color: #111827; margin-bottom: 16px; }
.pub-filter-group { margin-bottom: 24px; }
.pub-filter-group-label { font-size: 13px; font-weight: 700; color: #111827; margin-bottom: 10px; }
.pub-filter-item { display: flex; align-items: center; gap: 8px; margin-bottom: 7px; }
.pub-filter-item input[type=checkbox] { width: 14px; height: 14px; accent-color: var(--orange,#e8651a); cursor: pointer; }
.pub-filter-item label { font-size: 12.5px; color: #374151; cursor: pointer; }

/* Content */
.pub-content { flex: 1; min-width: 0; }
.pub-topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 8px; }
.pub-count { font-size: 13px; color: #64748b; }
.pub-count strong { color: #111827; }
.pub-pagination-mini { display: flex; align-items: center; gap: 8px; }
.pub-pg-btn {
    width: 32px; height: 32px; border-radius: 6px;
    border: 1px solid #e5e7eb; background: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; color: #374151; text-decoration: none; transition: all .15s;
}
.pub-pg-btn:hover:not(.disabled) { border-color: var(--orange,#e8651a); color: var(--orange,#e8651a); }
.pub-pg-btn.disabled { opacity: .4; pointer-events: none; }
.pub-pg-text { font-size: 12px; color: #64748b; }

/* Grid cards */
.pub-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%,320px),1fr)); gap: 20px; }
.pub-card {
    display: flex; flex-direction: column;
    background: #fff; border: 1px solid #e5e7eb; border-radius: 12px;
    padding: 20px; transition: box-shadow .18s, transform .18s;
}
.pub-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.10); transform: translateY(-2px); }
.pub-card-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; gap: 8px; }
.pub-badge {
    font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 999px;
    border: 1px solid #e5e7eb; color: #374151; background: #fff; white-space: nowrap;
}
.pub-badge.green { background: rgba(34,197,94,.08); color: #16a34a; border-color: rgba(34,197,94,.2); }
.pub-card-title {
    font-size: 15px; font-weight: 700; color: #111827; line-height: 1.4; margin-bottom: 8px;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    transition: color .15s;
}
.pub-card:hover .pub-card-title { color: var(--orange,#e8651a); }
.pub-card-authors  { font-size: 12px; color: #64748b; margin-bottom: 10px; }
.pub-card-abstract {
    font-size: 12.5px; color: #64748b; line-height: 1.6; margin-bottom: 14px; flex: 1;
    display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
}
.pub-keywords { display: flex; flex-wrap: wrap; gap: 4px; margin-bottom: 12px; }
.pub-kw { font-size: 11px; background: #f1f5f9; padding: 2px 7px; border-radius: 4px; color: #374151; }
.pub-card-meta {
    display: flex; justify-content: space-between; align-items: center;
    font-size: 11px; color: #94a3b8; margin-bottom: 12px;
}
.pub-card-meta span { display: flex; align-items: center; gap: 4px; }
.pub-card-btns { display: flex; gap: 8px; margin-top: auto; }
.pub-btn {
    flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 5px;
    padding: 7px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;
    border: 1px solid #e5e7eb; background: #fff; color: #374151;
    text-decoration: none; cursor: pointer; transition: all .15s;
}
.pub-btn:hover { border-color: var(--orange,#e8651a); color: var(--orange,#e8651a); }

/* List cards */
.pub-list { display: flex; flex-direction: column; gap: 14px; }
.pub-list-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; transition: box-shadow .18s; }
.pub-list-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); }
.pub-list-inner  { display: flex; gap: 16px; }
.pub-list-body   { flex: 1; }
.pub-list-badges { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; margin-bottom: 8px; }
.pub-list-year   { font-size: 11px; color: #94a3b8; }
.pub-list-title  { font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 6px; line-height: 1.35; }
.pub-list-authors{ font-size: 12px; color: #64748b; margin-bottom: 8px; }
.pub-list-meta   { display: flex; flex-wrap: wrap; gap: 14px; font-size: 11px; color: #94a3b8; margin-bottom: 10px; }
.pub-list-meta span { display: flex; align-items: center; gap: 3px; }
.pub-list-abstract {
    font-size: 12.5px; color: #64748b; line-height: 1.6;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.pub-list-actions { display: flex; flex-direction: column; gap: 8px; width: 90px; flex-shrink: 0; }

/* Empty state */
.pub-empty { text-align: center; padding: 64px 20px; border: 1px solid #e5e7eb; border-radius: 12px; background: #fff; }
.pub-empty i  { font-size: 48px; color: #cbd5e1; margin-bottom: 16px; display: block; }
.pub-empty h3 { font-size: 17px; font-weight: 700; color: #111827; margin-bottom: 8px; }
.pub-empty p  { font-size: 13px; color: #64748b; margin-bottom: 20px; }

/* Bottom pagination */
.pub-pages { display: flex; justify-content: center; align-items: center; gap: 6px; margin-top: 36px; flex-wrap: wrap; }
.pub-pg-num {
    width: 36px; height: 36px; border-radius: 7px;
    border: 1px solid #e5e7eb; background: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; color: #374151; text-decoration: none; font-weight: 600; transition: all .15s;
}
.pub-pg-num:hover        { border-color: var(--orange,#e8651a); color: var(--orange,#e8651a); }
.pub-pg-num.active       { background: var(--orange,#e8651a); color: #fff; border-color: var(--orange,#e8651a); }
.pub-pg-num.disabled     { opacity: .4; pointer-events: none; }
.pub-pg-ellipsis         { font-size: 13px; color: #94a3b8; padding: 0 4px; }

/* CTA */
.pub-cta { position: relative; overflow: hidden; padding: 80px 20px; background: #0f1f3d; text-align: center; color: #fff; }
.pub-cta-dots {
    position: absolute; inset: 0; opacity: .08;
    background-image: radial-gradient(circle, #fff 1px, transparent 1px);
    background-size: 12px 12px; z-index: 0;
}
.pub-cta-inner   { position: relative; z-index: 1; max-width: 680px; margin: 0 auto; }
.pub-cta h2      { font-size: clamp(24px,3vw,34px); font-weight: 800; margin-bottom: 14px; color: #fff; }
.pub-cta p       { font-size: 16px; color: rgba(255,255,255,.80); margin-bottom: 28px; }
.pub-cta-btns    { display: flex; flex-wrap: wrap; justify-content: center; gap: 12px; }
.btn-pub-orange  {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--orange,#e8651a); color: #fff; border: none; border-radius: 7px;
    padding: 12px 26px; font-size: 14px; font-weight: 700; text-decoration: none; transition: opacity .18s;
}
.btn-pub-orange:hover { opacity: .88; color: #fff; }
.btn-pub-ghost {
    display: inline-flex; align-items: center; gap: 6px;
    background: transparent; color: var(--orange,#e8651a);
    border: 1.5px solid rgba(255,255,255,.30); border-radius: 7px;
    padding: 12px 26px; font-size: 14px; font-weight: 700; text-decoration: none; transition: all .18s;
}
.btn-pub-ghost:hover { background: rgba(255,255,255,.08); }

@media (max-width: 768px) {
    .pub-sidebar { display: none; }
    .pub-layout  { padding: 24px 16px; }
    .pub-list-actions { width: auto; flex-direction: row; }
}
</style>


<!-- ══ HERO ══════════════════════════════════════════════════════════════ -->
<section class="pub-hero">
    <div class="pub-hero-bg"></div>
    <div class="pub-hero-overlay"></div>
    <div class="pub-hero-dots"></div>
    <div class="pub-hero-inner">
        <div class="pub-hero-eyebrow">Afrika Scholar</div>
        <h1 class="pub-hero-title">Publications</h1>
        <p class="pub-hero-sub">Explore peer-reviewed research from across Africa. Access open-access articles, journals, and special issues.</p>
    </div>
</section>


<!-- ══ STICKY SEARCH + SORT + VIEW ══════════════════════════════════════ -->
<div class="pub-search-bar">
    <form method="GET" id="search-form">
        <div class="pub-search-inner">
            <!-- Search field -->
            <div class="pub-search-field">
                <i class="fas fa-search"></i>
                <input type="text" name="search"
                       value="<?= htmlspecialchars($search) ?>"
                       placeholder="Search publications, authors, keywords..."
                       onchange="document.getElementById('search-form').submit()">
            </div>

            <!-- Preserve active filters in hidden fields -->
            <?php if ($active_cat !== 'All'): ?>
                <input type="hidden" name="cat" value="<?= htmlspecialchars($active_cat) ?>">
            <?php endif; ?>
            <?php if ($filter_year):   ?><input type="hidden" name="year"   value="<?= htmlspecialchars($filter_year) ?>"><?php endif; ?>
            <?php if ($filter_region): ?><input type="hidden" name="region" value="<?= htmlspecialchars($filter_region) ?>"><?php endif; ?>
            <?php if ($filter_access): ?><input type="hidden" name="access" value="<?= htmlspecialchars($filter_access) ?>"><?php endif; ?>
            <input type="hidden" name="view" value="<?= htmlspecialchars($view_mode) ?>">

            <!-- Sort -->
            <select name="sort" class="pub-sort-select" onchange="this.form.submit()">
                <option value="recent"       <?= $sort_by==='recent'       ?'selected':'' ?>>Most Recent</option>
                <option value="relevant"     <?= $sort_by==='relevant'     ?'selected':'' ?>>Most Cited</option>
                <option value="alphabetical" <?= $sort_by==='alphabetical' ?'selected':'' ?>>A–Z</option>
            </select>

            <!-- View toggle -->
            <div class="pub-view-toggle">
                <a href="<?= qstr(['view'=>'grid','page'=>1]) ?>"
                   class="pub-view-btn <?= $view_mode==='grid'?'active':'' ?>" title="Grid">
                    <i class="fas fa-th-large"></i>
                </a>
                <a href="<?= qstr(['view'=>'list','page'=>1]) ?>"
                   class="pub-view-btn <?= $view_mode==='list'?'active':'' ?>" title="List">
                    <i class="fas fa-list"></i>
                </a>
            </div>
        </div>
    </form>

    <!-- Active filter pills -->
    <?php if ($has_filters): ?>
    <div class="pub-filter-pills">
        <span style="font-size:12px;color:#94a3b8">Active filters:</span>
        <?php if ($active_cat !== 'All'): ?>
            <span class="pub-pill"><?= htmlspecialchars($active_cat) ?> <a href="<?= qstr(['cat'=>'All','page'=>1]) ?>">✕</a></span>
        <?php endif; ?>
        <?php if ($filter_year): ?>
            <span class="pub-pill"><?= htmlspecialchars($filter_year) ?> <a href="<?= qstr(['year'=>'','page'=>1]) ?>">✕</a></span>
        <?php endif; ?>
        <?php if ($filter_region): ?>
            <span class="pub-pill"><?= htmlspecialchars($filter_region) ?> <a href="<?= qstr(['region'=>'','page'=>1]) ?>">✕</a></span>
        <?php endif; ?>
        <?php if ($filter_access): ?>
            <span class="pub-pill"><?= $filter_access==='open'?'Open Access':'Restricted' ?> <a href="<?= qstr(['access'=>'','page'=>1]) ?>">✕</a></span>
        <?php endif; ?>
        <a href="publications.php" class="pub-clear-link">Clear all</a>
    </div>
    <?php endif; ?>
</div>


<!-- ══ MAIN LAYOUT ═══════════════════════════════════════════════════════ -->
<div class="pub-layout">

    <!-- SIDEBAR -->
    <aside class="pub-sidebar">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
            <div class="pub-sidebar-title">Filters</div>
            <?php if ($has_filters): ?>
            <a href="publications.php" class="pub-clear-link">Clear</a>
            <?php endif; ?>
        </div>

        <!-- Year (only shown if data has year column) -->
        <?php if (!empty($all_years)): ?>
        <div class="pub-filter-group">
            <div class="pub-filter-group-label">Year</div>
            <?php foreach ($all_years as $y): ?>
            <div class="pub-filter-item">
                <input type="checkbox" id="y-<?= htmlspecialchars($y) ?>"
                       <?= $filter_year === $y ? 'checked' : '' ?>
                       onchange="window.location='<?= qstr(['year'=>$y,'page'=>1]) ?>'">
                <label for="y-<?= htmlspecialchars($y) ?>"><?= htmlspecialchars($y) ?></label>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Discipline -->
        <div class="pub-filter-group">
            <div class="pub-filter-group-label">Discipline</div>
            <?php foreach (array_slice($categories, 1) as $cat): ?>
            <div class="pub-filter-item">
                <input type="checkbox" id="c-<?= md5($cat) ?>"
                       <?= $active_cat === $cat ? 'checked' : '' ?>
                       onchange="window.location='<?= qstr(['cat'=>$cat,'page'=>1]) ?>'">
                <label for="c-<?= md5($cat) ?>"><?= htmlspecialchars($cat) ?></label>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Region (only shown if data has region column) -->
        <?php if (!empty($all_regions)): ?>
        <div class="pub-filter-group">
            <div class="pub-filter-group-label">Region</div>
            <?php foreach ($all_regions as $r): ?>
            <div class="pub-filter-item">
                <input type="checkbox" id="r-<?= md5($r) ?>"
                       <?= $filter_region === $r ? 'checked' : '' ?>
                       onchange="window.location='<?= qstr(['region'=>$r,'page'=>1]) ?>'">
                <label for="r-<?= md5($r) ?>"><?= htmlspecialchars($r) ?></label>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Access Type -->
        <div class="pub-filter-group">
            <div class="pub-filter-group-label">Access Type</div>
            <div class="pub-filter-item">
                <input type="checkbox" id="a-open"
                       <?= $filter_access==='open' ? 'checked' : '' ?>
                       onchange="window.location='<?= qstr(['access'=>'open','page'=>1]) ?>'">
                <label for="a-open">Open Access</label>
            </div>
            <div class="pub-filter-item">
                <input type="checkbox" id="a-restricted"
                       <?= $filter_access==='restricted' ? 'checked' : '' ?>
                       onchange="window.location='<?= qstr(['access'=>'restricted','page'=>1]) ?>'">
                <label for="a-restricted">Restricted</label>
            </div>
        </div>
    </aside>


    <!-- CONTENT -->
    <div class="pub-content">

        <!-- Top bar -->
        <div class="pub-topbar">
            <p class="pub-count">
                Showing
                <strong><?= $total > 0 ? $offset + 1 : 0 ?>–<?= min($offset + $per_page, $total) ?></strong>
                of <strong><?= $total ?></strong> publications
            </p>
            <div class="pub-pagination-mini">
<a href="<?= qstr(['page'=>(int)$current_page-1]) ?>"
                   class="pub-pg-btn <?= $current_page<=1?'disabled':'' ?>">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <span class="pub-pg-text"><?= $current_page ?> / <?= $total_pages ?></span>
                <a href="<?= qstr(['page'=>(int)$current_page+1]) ?>"
                   class="pub-pg-btn <?= $current_page>=$total_pages?'disabled':'' ?>">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>

        <!-- ── EMPTY STATE ── -->
        <?php if (empty($paginated)): ?>
        <div class="pub-empty">
            <i class="fas fa-book-open"></i>
            <h3>No publications found</h3>
            <p>Try adjusting your search or filters</p>
            <a href="publications.php" class="btn-pub-orange">Clear filters</a>
        </div>

        <!-- ── GRID VIEW ── -->
        <?php elseif ($view_mode === 'grid'): ?>
        <div class="pub-grid">
            <?php foreach ($paginated as $art): ?>
            <div class="pub-card">
                <div class="pub-card-top">
                    <span class="pub-badge"><?= htmlspecialchars($art['category'] ?? '') ?></span>
                    <?php if (($art['access_type'] ?? 'open') === 'open'): ?>
                        <span class="pub-badge green">Open Access</span>
                    <?php endif; ?>
                </div>

                <div class="pub-card-title"><?= htmlspecialchars($art['title'] ?? '') ?></div>
                <div class="pub-card-authors"><?= htmlspecialchars($art['authors'] ?? '') ?></div>
                <div class="pub-card-abstract"><?= htmlspecialchars($art['abstract'] ?? '') ?></div>

                <?php
                $kws = !empty($art['keywords'])
                    ? array_slice(array_filter(array_map('trim', explode(',', $art['keywords']))), 0, 3)
                    : [];
                ?>
                <?php if (!empty($kws)): ?>
                <div class="pub-keywords">
                    <?php foreach ($kws as $kw): ?>
                    <span class="pub-kw"><?= htmlspecialchars($kw) ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div class="pub-card-meta">
                    <span><i class="fas fa-book-open"></i><?= htmlspecialchars($art['journal'] ?? '') ?></span>
                    <span><i class="fas fa-calendar-alt"></i><?= htmlspecialchars($art['year'] ?? '') ?></span>
                </div>

                <div class="pub-card-btns">
                    <a href="article.php?id=<?= $art['id'] ?>" class="pub-btn">
                        <i class="fas fa-file-alt"></i> Read&nbsp;<i class="fas fa-lock" style="font-size:10px"></i>
                    </a>
                    <button type="button" class="pub-btn"
                            onclick='pubCite(<?= htmlspecialchars(json_encode([
                                "authors" => $art["authors"] ?? "",
                                "year"    => $art["year"]    ?? "",
                                "title"   => $art["title"]   ?? "",
                                "journal" => $art["journal"] ?? "",
                                "doi"     => $art["doi"]     ?? "N/A",
                            ]), ENT_QUOTES) ?>)'>
                        <i class="fas fa-quote-right"></i> Cite
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- ── LIST VIEW ── -->
        <?php else: ?>
        <div class="pub-list">
            <?php foreach ($paginated as $art): ?>
            <div class="pub-list-card">
                <div class="pub-list-inner">
                    <div class="pub-list-body">
                        <div class="pub-list-badges">
                            <span class="pub-badge"><?= htmlspecialchars($art['category'] ?? '') ?></span>
                            <?php if (($art['access_type'] ?? 'open') === 'open'): ?>
                                <span class="pub-badge green">Open Access</span>
                            <?php endif; ?>
                            <span class="pub-list-year"><?= htmlspecialchars($art['year'] ?? '') ?></span>
                        </div>
                        <div class="pub-list-title"><?= htmlspecialchars($art['title'] ?? '') ?></div>
                        <div class="pub-list-authors"><?= htmlspecialchars($art['authors'] ?? '') ?></div>
                        <div class="pub-list-meta">
                            <?php if (!empty($art['journal'])): ?>
                                <span><i class="fas fa-book-open"></i><?= htmlspecialchars($art['journal']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($art['institution'])): ?>
                                <span><i class="fas fa-building"></i><?= htmlspecialchars($art['institution']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($art['region'])): ?>
                                <span><i class="fas fa-map-marker-alt"></i><?= htmlspecialchars($art['region']) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="pub-list-abstract"><?= htmlspecialchars($art['abstract'] ?? '') ?></div>
                    </div>
                    <div class="pub-list-actions">
                        <a href="article.php?id=<?= $art['id'] ?>" class="pub-btn">
                            <i class="fas fa-file-alt"></i> Read
                        </a>
                        <button type="button" class="pub-btn"
                                onclick='pubCite(<?= htmlspecialchars(json_encode([
                                    "authors" => $art["authors"] ?? "",
                                    "year"    => $art["year"]    ?? "",
                                    "title"   => $art["title"]   ?? "",
                                    "journal" => $art["journal"] ?? "",
                                    "doi"     => $art["doi"]     ?? "N/A",
                                ]), ENT_QUOTES) ?>)'>
                            <i class="fas fa-quote-right"></i> Cite
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Bottom pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="pub-pages">
<a href="<?= qstr(['page'=>(int)$current_page-1]) ?>"
               class="pub-pg-num <?= $current_page<=1?'disabled':'' ?>">
                <i class="fas fa-chevron-left" style="font-size:11px"></i>
            </a>
            <?php
            $show = [];
            for ($p = 1; $p <= $total_pages; $p++) {
                if ($p === 1 || $p === $total_pages || abs($p - $current_page) <= 1)
                    $show[] = $p;
            }
            $prev_pg = null;
            foreach ($show as $p):
                if ($prev_pg !== null && $p - $prev_pg > 1): ?>
                    <span class="pub-pg-ellipsis">…</span>
                <?php endif; ?>
                <a href="<?= qstr(['page'=>$p]) ?>"
                   class="pub-pg-num <?= $p===$current_page?'active':'' ?>"><?= $p ?></a>
            <?php $prev_pg = $p; endforeach; ?>
            <a href="<?= qstr(['page'=>(int)$current_page+1]) ?>"
               class="pub-pg-num <?= $current_page>=$total_pages?'disabled':'' ?>">
                <i class="fas fa-chevron-right" style="font-size:11px"></i>
            </a>
        </div>
        <?php endif; ?>

    </div><!-- /pub-content -->
</div><!-- /pub-layout -->


<!-- ══ BOTTOM CTA ════════════════════════════════════════════════════ -->
<section class="pub-cta">
    <div class="pub-cta-dots"></div>
    <div class="pub-cta-inner">
        <h2>Advance African Scholarship</h2>
        <p>Discover, publish, and engage with research that matters.</p>
        <div class="pub-cta-btns">
            <a href="publications.php" class="btn-pub-orange">Explore Journals</a>
            <a href="publishing.php"   class="btn-pub-ghost">Publish Your Research</a>
        </div>
    </div>
</section>


<!-- ══ CITATION MODAL ════════════════════════════════════════════════ -->
<div id="cite-modal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.5);align-items:center;justify-content:center;padding:20px">
    <div style="background:#fff;border-radius:16px;padding:32px;max-width:520px;width:100%;position:relative;box-shadow:0 20px 60px rgba(0,0,0,.3)">
        <h3 style="font-size:18px;font-weight:800;margin:0 0 4px;color:#111827">Cite This Article</h3>
        <p style="font-size:12px;color:#64748b;margin:0 0 16px">APA Format Citation</p>
        <div id="cite-text" style="background:#f1f5f9;border-radius:8px;padding:16px;font-size:13px;line-height:1.7;font-family:monospace;color:#374151;margin-bottom:20px;word-break:break-word"></div>
        <div style="display:flex;gap:10px;justify-content:flex-end">
            <button onclick="document.getElementById('cite-modal').style.display='none'"
                    style="padding:9px 18px;border:1px solid #e5e7eb;border-radius:7px;background:#fff;font-size:13px;font-weight:600;cursor:pointer;color:#374151">
                Close
            </button>
            <button id="copy-btn" onclick="copyCitation()"
                    style="padding:9px 18px;border:none;border-radius:7px;background:var(--orange,#e8651a);color:#fff;font-size:13px;font-weight:700;cursor:pointer">
                Copy Citation
            </button>
        </div>
    </div>
</div>

<script>
function pubCite(pub) {
    var text = pub.authors + ' (' + pub.year + '). ' + pub.title + '. ' + pub.journal + '. DOI: ' + pub.doi;
    document.getElementById('cite-text').textContent = text;
    document.getElementById('cite-modal').style.display = 'flex';
}
function copyCitation() {
    var text = document.getElementById('cite-text').textContent;
    var btn  = document.getElementById('copy-btn');
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(function() {
            btn.textContent = 'Copied!';
            setTimeout(function(){ btn.textContent = 'Copy Citation'; }, 1800);
        });
    } else {
        // fallback
        var ta = document.createElement('textarea');
        ta.value = text; document.body.appendChild(ta);
        ta.select(); document.execCommand('copy');
        document.body.removeChild(ta);
        btn.textContent = 'Copied!';
        setTimeout(function(){ btn.textContent = 'Copy Citation'; }, 1800);
    }
}
document.getElementById('cite-modal').addEventListener('click', function(e) {
    if (e.target === this) this.style.display = 'none';
});
</script>

<?php include 'footer.php'; ?>
