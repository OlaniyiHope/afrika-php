<?php
// ============================================================
//  api/manuscripts.php
//
//  GET  /api/manuscripts.php              → all manuscripts
//  GET  /api/manuscripts.php?id=1         → single manuscript
//  GET  /api/manuscripts.php?status=under_review → filter by status
//
//  POST /api/manuscripts.php              → submit new manuscript
//       Body (JSON): {
//         journal_id, title, abstract, keywords,
//         article_type, discipline, authors,
//         affiliations, corresponding_email,
//         confirm_original, confirm_ethics, confirm_disclosure
//       }
// ============================================================

require_once __DIR__ . '/../helpers.php';

$allowed = [
    'http://localhost:5173',
    'http://localhost:3000',
    'https://afrikascholars.com',
];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
header('Access-Control-Allow-Origin: ' . (in_array($origin, $allowed) ? $origin : '*'));
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }


// ============================================================
//  POST — submit a new manuscript
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // React sends JSON body
    $body = json_decode(file_get_contents('php://input'), true);

    if (!$body) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid JSON body']);
        exit;
    }

    // ---- Validation ----
    $errors = [];

    if (empty($body['journal_id']))
        $errors[] = 'Please select a journal.';

    if (empty($body['title']))
        $errors[] = 'Title is required.';

    if (empty($body['abstract']))
        $errors[] = 'Abstract is required.';

    if (empty($body['authors']))
        $errors[] = 'Author name(s) are required.';

    if (empty($body['corresponding_email']) || !filter_var($body['corresponding_email'], FILTER_VALIDATE_EMAIL))
        $errors[] = 'A valid corresponding email is required.';

    if (empty($body['confirm_original']) || $body['confirm_original'] !== true)
        $errors[] = 'You must confirm the originality declaration.';

    if (empty($body['confirm_ethics']) || $body['confirm_ethics'] !== true)
        $errors[] = 'You must confirm the ethics declaration.';

    if (!empty($errors)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }

    // ---- Get next ID safely (his get_next_id() runs under lock) ----
    $id = get_next_id('manuscripts.csv');

    // ---- Build the row to match your CSV columns:
    //  id, title, authors, journal_id, category, abstract,
    //  keywords, submitted_at, status
    $row = [
        $id,
        trim($body['title']),
        trim($body['authors']),
        trim($body['journal_id']),
        trim($body['discipline']  ?? ''),
        trim($body['abstract']),
        trim($body['keywords']    ?? ''),
        date('Y-m-d'),
        'submitted',
    ];

    // ---- Write using his append_csv() — atomic, locked, backed up ----
    $saved = append_csv('manuscripts.csv', $row);

    if (!$saved) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to save manuscript. Please try again.']);
        exit;
    }

    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Manuscript submitted successfully.',
        'data'    => [
            'id'     => $id,
            'title'  => trim($body['title']),
            'status' => 'submitted',
        ]
    ]);
    exit;
}


// ============================================================
//  GET — read manuscripts
// ============================================================
$manuscripts = read_csv('manuscripts.csv');

if (empty($manuscripts)) {
    echo json_encode(['success' => true, 'data' => ['items' => [], 'total' => 0]]);
    exit;
}

// ---- Normalise types ----
foreach ($manuscripts as &$m) {
    $m['id']         = (int) ($m['id'] ?? 0);
    $m['journal_id'] = (int) ($m['journal_id'] ?? 0);
    $m['keywords']   = array_map('trim', explode(',', $m['keywords'] ?? ''));
}
unset($m);

// ---- Single manuscript ----
if (isset($_GET['id'])) {
    $id    = (int) $_GET['id'];
    $found = array_values(array_filter($manuscripts, fn($m) => $m['id'] === $id));
    if (empty($found)) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Not found']);
        exit;
    }
    echo json_encode(['success' => true, 'data' => $found[0]]);
    exit;
}

// ---- Filter by status ----
$out = $manuscripts;

if (!empty($_GET['status'])) {
    $status = trim($_GET['status']);
    $out    = array_values(array_filter($out, fn($m) => ($m['status'] ?? '') === $status));
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
