<?php
// ============================================================
//  api/articles.php
//
//  GET /api/articles.php                → all articles (paginated)
//  GET /api/articles.php?id=3           → single article
//  GET /api/articles.php?featured=1     → featured only
//  GET /api/articles.php?category=STEM  → filter by category
//  GET /api/articles.php?type=Review Article → filter by type
//  GET /api/articles.php?search=climate → search title/abstract/authors/keywords
//  GET /api/articles.php?page=2&per_page=6
// ============================================================

require_once __DIR__ . '/../helpers.php';

$allowed = [
    'http://localhost:5173',
    'http://localhost:3000',
    'http://localhost:8001',
    'https://afrikascholars.com',
];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
header('Access-Control-Allow-Origin: ' . (in_array($origin, $allowed) ? $origin : '*'));
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

$articles = read_csv('articles.csv');

if (empty($articles)) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'No articles found']);
    exit;
}

// ---- Normalise ALL fields ----
foreach ($articles as &$a) {
    // Numbers
    $a['id']        = (int) ($a['id']        ?? 0);
    $a['year']      = (int) ($a['year']       ?? 0);
    $a['citations'] = (int) ($a['citations']  ?? 0);
    $a['downloads'] = (int) ($a['downloads']  ?? 0);
    $a['views']     = (int) ($a['views']      ?? 0);

    // Booleans
    $a['featured']    = ($a['featured']    ?? '') === '1';
    $a['open_access'] = ($a['open_access'] ?? '') === '1';

    // Arrays (comma-separated → array)
    $a['authors']  = array_map('trim', explode(',', $a['authors']  ?? ''));
    $a['keywords'] = array_map('trim', explode(',', $a['keywords'] ?? ''));

    // Strings
    $a['type']           = $a['type']           ?? '';
    $a['published_date'] = $a['published_date'] ?? '';
}
unset($a);

// ---- Single article ----
if (isset($_GET['id'])) {
    $id    = (int) $_GET['id'];
    $found = array_values(array_filter($articles, fn($a) => $a['id'] === $id));
    if (empty($found)) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Not found']);
        exit;
    }
    echo json_encode(['success' => true, 'data' => $found[0]]);
    exit;
}

// ---- Filters ----
$out = $articles;

if (!empty($_GET['featured']) && $_GET['featured'] === '1')
    $out = array_values(array_filter($out, fn($a) => $a['featured']));

if (!empty($_GET['category']) && $_GET['category'] !== 'All') {
    $cat = trim($_GET['category']);
    $out = array_values(array_filter($out, fn($a) => ($a['category'] ?? '') === $cat));
}

if (!empty($_GET['type'])) {
    $type = trim($_GET['type']);
    $out  = array_values(array_filter($out, fn($a) => ($a['type'] ?? '') === $type));
}

if (!empty($_GET['search'])) {
    $q   = strtolower(trim($_GET['search']));
    $out = array_values(array_filter($out, fn($a) =>
        str_contains(strtolower($a['title']    ?? ''), $q) ||
        str_contains(strtolower($a['abstract'] ?? ''), $q) ||
        str_contains(strtolower(implode(' ', $a['authors'])),  $q) ||
        str_contains(strtolower(implode(' ', $a['keywords'])), $q)
    ));
}

// ---- Paginate ----
$page     = max(1, (int)($_GET['page']     ?? 1));
$per_page = max(1, min(50, (int)($_GET['per_page'] ?? 12)));
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
