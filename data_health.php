<?php
/**
 * Afrika Scholar — Data Health & Recovery Dashboard
 * Access: /data_health.php?key=YOUR_SECRET_KEY
 *
 * Set your secret key below. Keep this URL private.
 */
define('ADMIN_KEY', 'afrikascholar_admin_2026'); // ← change this in production

if (($_GET['key'] ?? '') !== ADMIN_KEY) {
    http_response_code(403);
    die('Access denied.');
}

require_once __DIR__ . '/helpers.php';

$action  = $_POST['action'] ?? '';
$file    = basename($_POST['file'] ?? '');
$message = '';
$msgType = '';

// ── Handle actions ─────────────────────────────────────────────────────────
if ($action === 'restore' && $file) {
    $result = restore_csv($file);
    if ($result) {
        $message = "✅ Restored <strong>$file</strong> from backup: <code>$result</code>";
        $msgType = 'success';
    } else {
        $message = "❌ No backup found for <strong>$file</strong>.";
        $msgType = 'error';
    }
}

if ($action === 'download_backup' && $file && isset($_POST['backup'])) {
    $backup = basename($_POST['backup']);
    $path   = BACKUP_DIR . $backup;
    if (file_exists($path) && strpos($backup, '..') === false) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $backup . '"');
        readfile($path);
        exit;
    }
}

if ($action === 'download_wal' && $file) {
    $wal = WAL_DIR . $file . '.wal';
    if (file_exists($wal)) {
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="' . $file . '.wal"');
        readfile($wal);
        exit;
    }
}

$health  = csv_health();
$key     = htmlspecialchars(ADMIN_KEY);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Data Health — Afrika Scholar</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Inter',sans-serif;background:#f1f3f9;color:#1a1a2e;font-size:14px}
:root{--navy:#2D2B8F;--orange:#E8651A;--green:#059669;--red:#dc2626;--border:#e5e7eb}
.topbar{background:var(--navy);color:#fff;padding:16px 32px;display:flex;align-items:center;justify-content:space-between}
.topbar h1{font-size:18px;font-weight:800}.topbar span{font-size:13px;opacity:.7}
.container{max-width:1200px;margin:32px auto;padding:0 24px}
.card{background:#fff;border:1px solid var(--border);border-radius:12px;margin-bottom:24px;overflow:hidden}
.card-header{padding:18px 24px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
.card-header h2{font-size:16px;font-weight:700}
.card-body{padding:24px}
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700}
.badge-green{background:#d1fae5;color:var(--green)}
.badge-red{background:#fee2e2;color:var(--red)}
.badge-blue{background:#dbeafe;color:#1d4ed8}
.badge-orange{background:rgba(232,101,26,.1);color:var(--orange)}
table{width:100%;border-collapse:collapse}
th{padding:10px 14px;text-align:left;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;background:#f9fafb;border-bottom:1px solid var(--border)}
td{padding:12px 14px;border-bottom:1px solid #f3f4f6;vertical-align:top}
tr:last-child td{border:0}
.btn{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;border:1.5px solid transparent;font-family:inherit;transition:all .2s}
.btn-navy{background:var(--navy);color:#fff;border-color:var(--navy)}
.btn-navy:hover{background:#1e1c6e}
.btn-orange{background:var(--orange);color:#fff;border-color:var(--orange)}
.btn-red{background:var(--red);color:#fff;border-color:var(--red)}
.btn-outline{background:#fff;color:#374151;border-color:var(--border)}
.btn-outline:hover{border-color:var(--navy);color:var(--navy)}
.alert{padding:14px 20px;border-radius:8px;margin-bottom:24px;font-size:14px}
.alert-success{background:#d1fae5;border:1px solid #6ee7b7;color:#065f46}
.alert-error{background:#fee2e2;border:1px solid #fca5a5;color:var(--red)}
.stat-row{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px}
.stat{background:#fff;border:1px solid var(--border);border-radius:10px;padding:20px;text-align:center}
.stat-num{font-size:32px;font-weight:900;color:var(--navy)}
.stat-label{font-size:12px;color:#6b7280;margin-top:4px}
.mono{font-family:monospace;font-size:12px}
details summary{cursor:pointer;font-size:13px;font-weight:600;color:var(--navy);padding:8px 0}
details[open] summary{margin-bottom:8px}
</style>
</head>
<body>
<div class="topbar">
    <h1><i class="fas fa-database"></i> &nbsp;Afrika Scholar — Data Health Dashboard</h1>
    <span>All times in server timezone: <?= date('Y-m-d H:i:s T') ?></span>
</div>

<div class="container">

<?php if ($message): ?>
<div class="alert alert-<?= $msgType ?>"><?= $message ?></div>
<?php endif; ?>

<!-- SUMMARY STATS -->
<?php
$total_rows   = array_sum(array_column($health, 'rows'));
$total_files  = count($health);
$total_backups= array_sum(array_column($health, 'backups'));
$total_bytes  = array_sum(array_column($health, 'size_bytes'));
?>
<div class="stat-row">
    <div class="stat"><div class="stat-num"><?= $total_files ?></div><div class="stat-label">CSV Files</div></div>
    <div class="stat"><div class="stat-num"><?= number_format($total_rows) ?></div><div class="stat-label">Total Rows</div></div>
    <div class="stat"><div class="stat-num"><?= $total_backups ?></div><div class="stat-label">Total Backups</div></div>
    <div class="stat"><div class="stat-num"><?= round($total_bytes/1024, 1) ?> KB</div><div class="stat-label">Total Data Size</div></div>
</div>

<!-- FILE DETAILS -->
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-table" style="color:var(--orange);margin-right:8px"></i>CSV File Status</h2>
    </div>
    <div class="card-body" style="padding:0">
        <table>
            <thead>
                <tr>
                    <th>File</th>
                    <th>Rows</th>
                    <th>Size</th>
                    <th>Last Modified</th>
                    <th>Backups</th>
                    <th>WAL Entries</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($health as $fname => $info): ?>
            <tr>
                <td><strong class="mono"><?= htmlspecialchars($fname) ?></strong></td>
                <td><span class="badge badge-blue"><?= number_format($info['rows']) ?></span></td>
                <td><?= number_format($info['size_bytes']) ?> B</td>
                <td class="mono"><?= $info['last_modified'] ?></td>
                <td>
                    <span class="badge <?= $info['backups']>0?'badge-green':'badge-red' ?>"><?= $info['backups'] ?> backups</span>
                    <?php if ($info['latest_backup']): ?>
                    <div style="font-size:11px;color:#9ca3af;margin-top:4px">Latest: <?= htmlspecialchars($info['latest_backup']) ?></div>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($info['wal_entries'] > 0): ?>
                    <span class="badge badge-orange"><?= $info['wal_entries'] ?> entries</span>
                    <?php else: ?>
                    <span style="color:#9ca3af">—</span>
                    <?php endif; ?>
                </td>
                <td style="display:flex;gap:6px;flex-wrap:wrap">
                    <!-- RESTORE -->
                    <form method="POST">
                        <input type="hidden" name="action" value="restore">
                        <input type="hidden" name="file" value="<?= htmlspecialchars($fname) ?>">
                        <input type="hidden" name="key" value="<?= $key ?>">
                        <button type="submit" class="btn btn-outline" onclick="return confirm('Restore <?= addslashes($fname) ?> from latest backup?')" <?= $info['backups']===0?'disabled':'' ?>>
                            <i class="fas fa-undo"></i> Restore
                        </button>
                    </form>
                    <!-- DOWNLOAD WAL -->
                    <?php if ($info['wal_entries'] > 0): ?>
                    <form method="POST">
                        <input type="hidden" name="action" value="download_wal">
                        <input type="hidden" name="file" value="<?= htmlspecialchars($fname) ?>">
                        <input type="hidden" name="key" value="<?= $key ?>">
                        <button type="submit" class="btn btn-outline"><i class="fas fa-download"></i> WAL</button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- BACKUP BROWSER -->
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-history" style="color:var(--orange);margin-right:8px"></i>Backup Browser</h2>
        <span style="font-size:12px;color:#6b7280">Keeping <?= MAX_BACKUPS ?> most recent per file</span>
    </div>
    <div class="card-body">
        <?php foreach ($health as $fname => $info): ?>
        <?php $backups = list_backups($fname); ?>
        <?php if ($backups): ?>
        <details style="margin-bottom:16px;border:1px solid var(--border);border-radius:8px;padding:12px 16px">
            <summary><?= htmlspecialchars($fname) ?> <span class="badge badge-green" style="margin-left:8px"><?= count($backups) ?></span></summary>
            <table style="margin-top:8px">
                <thead><tr><th>Backup File</th><th>Action</th></tr></thead>
                <tbody>
                <?php foreach ($backups as $backup): ?>
                <tr>
                    <td class="mono"><?= htmlspecialchars($backup) ?></td>
                    <td>
                        <form method="POST" style="display:inline">
                            <input type="hidden" name="action" value="download_backup">
                            <input type="hidden" name="file" value="<?= htmlspecialchars($fname) ?>">
                            <input type="hidden" name="backup" value="<?= htmlspecialchars($backup) ?>">
                            <input type="hidden" name="key" value="<?= $key ?>">
                            <button type="submit" class="btn btn-outline"><i class="fas fa-download"></i> Download</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </details>
        <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>

<!-- DATA DIRECTORY PATHS -->
<div class="card">
    <div class="card-header"><h2><i class="fas fa-folder" style="color:var(--orange);margin-right:8px"></i>Directory Paths</h2></div>
    <div class="card-body">
        <table>
            <tr><th style="width:180px">Purpose</th><th>Path</th><th>Exists</th></tr>
            <?php foreach ([
                ['Live Data',    DATA_DIR],
                ['Backups',      BACKUP_DIR],
                ['Write-Ahead Log', WAL_DIR],
                ['Lock Files',   LOCK_DIR],
            ] as $d): ?>
            <tr>
                <td><?= $d[0] ?></td>
                <td class="mono"><?= htmlspecialchars($d[1]) ?></td>
                <td><?= is_dir($d[1]) ? '<span class="badge badge-green">✓ Yes</span>' : '<span class="badge badge-red">✗ No</span>' ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<p style="text-align:center;color:#9ca3af;font-size:12px;margin-top:24px">Afrika Scholar Data Health Dashboard &bull; <?= date('Y') ?></p>
</div>
</body>
</html>
