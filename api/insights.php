<?php
// ============================================================
//  api/insights.php
//
//  GET /api/insights.php                 → all insights (paginated)
//  GET /api/insights.php?id=2            → single insight
//  GET /api/insights.php?limit=3         → latest N (for homepage)
//  GET /api/insights.php?category=Publishing → filter by category
//  GET /api/insights.php?search=africa   → search title/excerpt
// ============================================================

require_once __DIR__ . '/../helpers.php';

$allowed = [
    'http://localhost:5173',
    'http://localhost:3000',
    'https://afrikascholars.com',
];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
header('Access-Control-Allow-Origin: ' . (in_array($origin, $allowed) ? $origin : '*'));
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

// ---- Load data using his read_csv() ----
$posts = read_csv('insights.csv');

if (empty($posts)) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'No insights found']);
    exit;
}

// ---- Normalise types ----
foreach ($posts as &$p) {
    $p['id'] = (int) ($p['id'] ?? 0);
    // slug and image_alt are already strings — no casting needed
}
unset($p);

// ---- Single insight by id ----
if (isset($_GET['id'])) {
    $id    = (int) $_GET['id'];
    $found = array_values(array_filter($posts, fn($p) => $p['id'] === $id));
    if (empty($found)) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Insight not found']);
        exit;
    }
    echo json_encode(['success' => true, 'data' => $found[0]]);
    exit;
}

// ---- Quick latest N for homepage ----
if (isset($_GET['limit'])) {
    $limit = max(1, (int) $_GET['limit']);
    echo json_encode(['success' => true, 'data' => array_slice($posts, 0, $limit)]);
    exit;
}

// ---- Filters ----
$out = $posts;

if (!empty($_GET['category'])) {
    $cat = trim($_GET['category']);
    $out = array_values(array_filter($out, fn($p) => ($p['category'] ?? '') === $cat));
}

if (!empty($_GET['search'])) {
    $q   = strtolower(trim($_GET['search']));
    $out = array_values(array_filter($out, fn($p) =>
        str_contains(strtolower($p['title']   ?? ''), $q) ||
        str_contains(strtolower($p['excerpt'] ?? ''), $q)
    ));
}

// ---- Paginate ----
$page     = max(1, (int)($_GET['page']     ?? 1));
$per_page = max(1, min(50, (int)($_GET['per_page'] ?? 9)));
$total    = count($out);

echo json_encode([
    'success' => true,
    'data'    => [
        'items'       => array_values(array_slice($out, ($page - 1) * $per_page, $per_page)),
        'total'       => $total,
        'page'        => $page,
        'per_page'    => $per_page,
        'total_pages' => (int) ceil($total / $per_page),
    ]
]);
```

---

To answer your question directly — yes, the pattern is always the same for every CSV he has:
```
His PHP page          →     Your /api/ file
──────────────────────      ──────────────────────────────
read_csv('x.csv')     →     read_csv('x.csv')        same
foreach loop HTML     →     json_encode() the array   different