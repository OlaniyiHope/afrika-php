<?php
// ============================================================
//  api/journals.php
//
//  GET /api/journals.php                      → all journals
//  GET /api/journals.php?id=2                 → single journal
//  GET /api/journals.php?category=Health      → filter by category
//  GET /api/journals.php?open_access=1        → open access only
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

$journals = read_csv('journals.csv');

if (empty($journals)) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'No journals found']);
    exit;
}

// ---- Normalise types ----
foreach ($journals as &$j) {
    $j['id']          = (int)  ($j['id']          ?? 0);
    $j['open_access'] = ($j['open_access'] ?? '') === '1';
}
unset($j);

// ---- Single journal ----
if (isset($_GET['id'])) {
    $id    = (int) $_GET['id'];
    $found = array_values(array_filter($journals, fn($j) => $j['id'] === $id));
    if (empty($found)) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Not found']);
        exit;
    }
    echo json_encode(['success' => true, 'data' => $found[0]]);
    exit;
}

// ---- Filters ----
$out = $journals;

if (!empty($_GET['category'])) {
    $cat = trim($_GET['category']);
    $out = array_values(array_filter($out, fn($j) =>
        ($j['category'] ?? $j['discipline'] ?? '') === $cat
    ));
}

if (isset($_GET['open_access']) && $_GET['open_access'] === '1') {
    $out = array_values(array_filter($out, fn($j) => $j['open_access']));
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
