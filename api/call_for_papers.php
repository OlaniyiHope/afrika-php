<?php
// ============================================================
//  api/call_for_papers.php
//
//  GET /api/call_for_papers.php              → all calls (paginated)
//  GET /api/call_for_papers.php?status=open  → open only
//  GET /api/call_for_papers.php?status=upcoming → upcoming only
//  GET /api/call_for_papers.php?id=2         → single call
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

$calls = read_csv('call_for_papers.csv');

if (empty($calls)) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'No calls found']);
    exit;
}

// ---- Normalise types ----
foreach ($calls as &$c) {
    $c['id']     = (int) ($c['id'] ?? 0);
    // topics: comma-separated string → array (same as authors in articles)
    $c['topics'] = array_map('trim', explode(',', $c['topics'] ?? ''));
}
unset($c);

// ---- Single call ----
if (isset($_GET['id'])) {
    $id    = (int) $_GET['id'];
    $found = array_values(array_filter($calls, fn($c) => $c['id'] === $id));
    if (empty($found)) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Not found']);
        exit;
    }
    echo json_encode(['success' => true, 'data' => $found[0]]);
    exit;
}

// ---- Filter by status ----
$out = $calls;

if (!empty($_GET['status'])) {
    $status = trim($_GET['status']); // 'open' or 'upcoming'
    $out    = array_values(array_filter($out, fn($c) => ($c['status'] ?? '') === $status));
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
