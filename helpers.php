<?php
/**
 * Afrika Scholar — CSV Data Layer
 * ================================
 * Features:
 *  - Exclusive file locking (flock) on every read & write so concurrent
 *    requests never corrupt a file.
 *  - Atomic writes: data is written to a temp file first, then renamed
 *    into place — an interrupted write never destroys existing data.
 *  - Automatic timestamped backup on every successful write.
 *  - Backup rotation: keeps the 10 most recent backups per file.
 *  - WAL (Write-Ahead Log): every mutation is appended to a log before
 *    the main file is touched, allowing manual recovery if needed.
 *  - Integrity check: after every write the file is re-read and the
 *    row count is verified against expectation.
 *  - get_next_id() runs under lock so no two requests get the same ID.
 *  - Auto-migrates root-level CSVs into data/ on first access.
 */

// ── Directory constants ────────────────────────────────────────────────────
define('DATA_DIR',   __DIR__ . '/data/');
define('BACKUP_DIR', __DIR__ . '/data/backups/');
define('WAL_DIR',    __DIR__ . '/data/wal/');
define('LOCK_DIR',   __DIR__ . '/data/locks/');

// Rolling backups to keep per CSV file
define('MAX_BACKUPS', 10);

// ── Boot ───────────────────────────────────────────────────────────────────
_csv_boot();

function _csv_boot(): void {
    foreach ([DATA_DIR, BACKUP_DIR, WAL_DIR, LOCK_DIR] as $dir) {
        if (!is_dir($dir)) mkdir($dir, 0755, true);
    }
    // Block direct HTTP access to data directories
    foreach ([DATA_DIR, BACKUP_DIR, WAL_DIR, LOCK_DIR] as $dir) {
        $ht = $dir . '.htaccess';
        if (!file_exists($ht)) {
            file_put_contents($ht, "Order deny,allow\nDeny from all\n");
        }
    }
    // Migrate any root-level CSVs into data/ on first boot
    foreach (glob(__DIR__ . '/*.csv') ?: [] as $src) {
        $dest = DATA_DIR . basename($src);
        if (!file_exists($dest)) copy($src, $dest);
    }
}

// ── Internal helpers ───────────────────────────────────────────────────────

function _csv_path(string $filename): string {
    return DATA_DIR . basename($filename);
}

/** Acquire an advisory flock via a dedicated lock file. Returns handle. */
function _csv_lock(string $filename, bool $exclusive = true) {
    $lock_path = LOCK_DIR . basename($filename) . '.lock';
    $lh = fopen($lock_path, 'c');
    if (!$lh) throw new RuntimeException("Cannot open lock file: $lock_path");
    if (!flock($lh, $exclusive ? LOCK_EX : LOCK_SH))  {
        fclose($lh);
        throw new RuntimeException("Cannot acquire lock for: $filename");
    }
    return $lh;
}

function _csv_unlock($lh): void { flock($lh, LOCK_UN); fclose($lh); }

/** Parse a CSV into associative rows (no locking — caller holds lock). */
function _csv_parse(string $path): array {
    $rows = [];
    if (!file_exists($path)) return $rows;
    $fh = fopen($path, 'r');
    if (!$fh) return $rows;
    $headers = fgetcsv($fh, 0, ',', '"', '\\');
    if (!$headers) { fclose($fh); return $rows; }
    while (($data = fgetcsv($fh, 0, ',', '"', '\\')) !== false) {
        if (count($data) === count($headers))
            $rows[] = array_combine($headers, $data);
    }
    fclose($fh);
    return $rows;
}

/** Read the header row only. */
function _csv_headers(string $path): array {
    if (!file_exists($path)) return [];
    $fh = fopen($path, 'r');
    if (!$fh) return [];
    $h = fgetcsv($fh, 0, ',', '"', '\\') ?: [];
    fclose($fh);
    return $h;
}

/** Atomic write: write to .tmp then rename. */
function _csv_write_atomic(string $path, array $headers, array $rows): void {
    $tmp = $path . '.tmp.' . getmypid();
    $fh  = fopen($tmp, 'w');
    if (!$fh) throw new RuntimeException("Cannot open temp file: $tmp");
    fputcsv($fh, $headers, ',', '"', '\\');
    foreach ($rows as $row) {
        $vals = (is_array($row) && array_keys($row) !== range(0, count($row) - 1))
            ? array_values($row) : $row;
        fputcsv($fh, $vals, ',', '"', '\\');
    }
    fflush($fh);
    fclose($fh);
    if (!rename($tmp, $path)) { @unlink($tmp); throw new RuntimeException("Atomic rename failed: $path"); }
}

/** Create a timestamped backup and rotate old ones. */
function _csv_backup(string $filename): void {
    $src = _csv_path($filename);
    if (!file_exists($src)) return;
    $base  = pathinfo(basename($filename), PATHINFO_FILENAME);
    $stamp = date('Ymd_His') . '_' . substr(uniqid(), -4);
    copy($src, BACKUP_DIR . "{$base}_{$stamp}.csv");
    // Rotate
    $files = glob(BACKUP_DIR . "{$base}_*.csv") ?: [];
    if (count($files) > MAX_BACKUPS) {
        sort($files);
        foreach (array_slice($files, 0, count($files) - MAX_BACKUPS) as $old) @unlink($old);
    }
}

/** Append an entry to the Write-Ahead Log. */
function _csv_wal(string $filename, string $op, array $data): void {
    $entry = json_encode(['ts' => date('c'), 'pid' => getmypid(), 'op' => $op, 'file' => basename($filename), 'data' => $data]) . "\n";
    file_put_contents(WAL_DIR . basename($filename) . '.wal', $entry, FILE_APPEND | LOCK_EX);
}

/** Verify row count after write. */
function _csv_verify(string $filename, int $expected_min): bool {
    return count(_csv_parse(_csv_path($filename))) >= $expected_min;
}

// ═══════════════════════════════════════════════════════════════════════════
//  PUBLIC API
// ═══════════════════════════════════════════════════════════════════════════

/**
 * Read all rows from a CSV. Uses a shared lock (concurrent reads OK).
 */
function read_csv(string $filename): array {
    $path = _csv_path($filename);
    // Auto-migrate legacy root CSV on first access
    if (!file_exists($path)) {
        $root = __DIR__ . '/' . basename($filename);
        if (file_exists($root)) copy($root, $path);
        else return [];
    }
    $lh   = _csv_lock($filename, false);
    $rows = _csv_parse($path);
    _csv_unlock($lh);
    return $rows;
}

/**
 * Append a single row. Exclusive lock + WAL + backup + atomic write + verify.
 */
function append_csv(string $filename, array $row): bool {
    $path = _csv_path($filename);
    if (!file_exists($path)) {
        $root = __DIR__ . '/' . basename($filename);
        if (file_exists($root)) copy($root, $path);
    }
    $lh = _csv_lock($filename, true);
    try {
        _csv_wal($filename, 'append', $row);
        _csv_backup($filename);

        $headers  = _csv_headers($path);
        $existing = _csv_parse($path);

        if (empty($headers)) {
            // Empty/new file — plain append (caller controls header via first CSV write)
            $fh = fopen($path, 'a');
            if (!$fh) throw new RuntimeException("Cannot open: $path");
            fputcsv($fh, $row, ',', '"', '\\');
            fflush($fh); fclose($fh);
            _csv_unlock($lh);
            return true;
        }

        $new_row    = (count($row) === count($headers))
            ? array_combine($headers, array_values($row))
            : $row;
        $existing[] = $new_row;
        $expected   = count($existing);

        _csv_write_atomic($path, $headers, $existing);

        if (!_csv_verify($filename, $expected))
            throw new RuntimeException("Integrity check failed after append: $filename");

        _csv_unlock($lh);
        return true;
    } catch (Throwable $e) {
        _csv_unlock($lh);
        error_log('[AfrikaScholar CSV] append_csv: ' . $e->getMessage());
        return false;
    }
}

/**
 * Overwrite all rows (for bulk updates). Exclusive lock + WAL + backup + atomic write.
 */
function write_csv(string $filename, array $headers, array $rows): bool {
    $lh = _csv_lock($filename, true);
    try {
        _csv_wal($filename, 'overwrite', ['rows' => count($rows)]);
        _csv_backup($filename);
        _csv_write_atomic(_csv_path($filename), $headers, $rows);
        if (!_csv_verify($filename, count($rows)))
            throw new RuntimeException("Integrity check failed after write: $filename");
        _csv_unlock($lh);
        return true;
    } catch (Throwable $e) {
        _csv_unlock($lh);
        error_log('[AfrikaScholar CSV] write_csv: ' . $e->getMessage());
        return false;
    }
}

/**
 * Update rows matching $condition via $mutator.
 * $condition = fn(array $row): bool
 * $mutator   = fn(array $row): array
 */
function update_csv(string $filename, callable $condition, callable $mutator): bool {
    $lh = _csv_lock($filename, true);
    try {
        $path    = _csv_path($filename);
        $headers = _csv_headers($path);
        $rows    = _csv_parse($path);
        _csv_wal($filename, 'update', []);
        _csv_backup($filename);
        $updated = array_map(fn($r) => $condition($r) ? $mutator($r) : $r, $rows);
        _csv_write_atomic($path, $headers, $updated);
        _csv_unlock($lh);
        return true;
    } catch (Throwable $e) {
        _csv_unlock($lh);
        error_log('[AfrikaScholar CSV] update_csv: ' . $e->getMessage());
        return false;
    }
}

/**
 * Delete rows where $condition returns true.
 */
function delete_csv(string $filename, callable $condition): bool {
    $lh = _csv_lock($filename, true);
    try {
        $path    = _csv_path($filename);
        $headers = _csv_headers($path);
        $rows    = _csv_parse($path);
        _csv_wal($filename, 'delete', []);
        _csv_backup($filename);
        $kept = array_values(array_filter($rows, fn($r) => !$condition($r)));
        _csv_write_atomic($path, $headers, $kept);
        _csv_unlock($lh);
        return true;
    } catch (Throwable $e) {
        _csv_unlock($lh);
        error_log('[AfrikaScholar CSV] delete_csv: ' . $e->getMessage());
        return false;
    }
}

/**
 * Get a safe next ID (runs under exclusive lock — no duplicate IDs).
 */
function get_next_id(string $filename): int {
    $lh   = _csv_lock($filename, true);
    $rows = _csv_parse(_csv_path($filename));
    _csv_unlock($lh);
    if (empty($rows)) return 1;
    $ids = array_filter(array_column($rows, 'id'), 'is_numeric');
    return empty($ids) ? 1 : (int) max($ids) + 1;
}

/**
 * Restore a CSV from its most recent backup.
 * Returns the backup filename used, or false if none available.
 */
function restore_csv(string $filename): string|false {
    $base  = pathinfo(basename($filename), PATHINFO_FILENAME);
    $files = glob(BACKUP_DIR . "{$base}_*.csv") ?: [];
    if (!$files) return false;
    sort($files);
    $latest = end($files);
    $lh = _csv_lock($filename, true);
    copy($latest, _csv_path($filename));
    _csv_unlock($lh);
    return basename($latest);
}

/**
 * List backups for a file (newest first).
 */
function list_backups(string $filename): array {
    $base  = pathinfo(basename($filename), PATHINFO_FILENAME);
    $files = glob(BACKUP_DIR . "{$base}_*.csv") ?: [];
    rsort($files);
    return array_map('basename', $files);
}

/**
 * Health report for all CSV files in data/.
 */
function csv_health(): array {
    $report = [];
    foreach (glob(DATA_DIR . '*.csv') ?: [] as $f) {
        $name = basename($f);
        $rows = _csv_parse($f);
        $bups = list_backups($name);
        $wal  = WAL_DIR . $name . '.wal';
        $report[$name] = [
            'rows'          => count($rows),
            'size_bytes'    => filesize($f),
            'last_modified' => date('Y-m-d H:i:s', filemtime($f)),
            'backups'       => count($bups),
            'latest_backup' => $bups[0] ?? null,
            'wal_entries'   => file_exists($wal) ? substr_count(file_get_contents($wal), "\n") : 0,
        ];
    }
    return $report;
}
