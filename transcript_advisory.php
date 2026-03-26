<?php
$page_title = 'Transcript Advisory';
include 'header.php';
?>

<style>
/* ══ TRANSCRIPT ADVISORY PAGE ════════════════════════════════════════ */

/* Breadcrumb */
.tr-breadcrumb {
    background: #f1f5f9; border-bottom: 1px solid #e5e7eb;
    padding: 12px 20px;
}
.tr-breadcrumb-inner {
    max-width: 1200px; margin: 0 auto;
    display: flex; align-items: center; gap: 8px;
    font-size: 13px; color: #64748b;
}
.tr-breadcrumb-inner a { color: #64748b; text-decoration: none; }
.tr-breadcrumb-inner a:hover { color: var(--orange,#e8651a); }
.tr-breadcrumb-inner i { font-size: 10px; color: #94a3b8; }
.tr-breadcrumb-inner span { color: #111827; font-weight: 600; }

/* Hero */
.tr-hero {
    position: relative; min-height: 420px;
    display: flex; align-items: center; justify-content: center; overflow: hidden;
}
.tr-hero-bg {
    position: absolute; inset: 0;
    background-image: url('asset/about-conference.jpg');
    background-size: cover; background-position: center; z-index: 0;
}
.tr-hero-overlay { position: absolute; inset: 0; background: rgba(10,22,55,.85); z-index: 1; }
.tr-hero-dots {
    position: absolute; inset: 0; z-index: 2; opacity: .10;
    background-image: radial-gradient(circle, #fff 1px, transparent 1px);
    background-size: 16px 16px;
}
.tr-hero-inner {
    position: relative; z-index: 3; text-align: center;
    color: #fff; padding: 80px 20px; max-width: 760px;
}
.tr-hero-eyebrow {
    font-size: 11px; font-weight: 700; letter-spacing: .14em;
    text-transform: uppercase; color: var(--orange,#e8651a); margin-bottom: 14px;
}
.tr-hero-title { font-size: clamp(28px,5vw,48px); font-weight: 800; margin: 0 0 18px; line-height: 1.15; color: #fff; }
.tr-hero-sub   { font-size: 17px; color: rgba(255,255,255,.80); margin: 0; line-height: 1.6; }

/* Steps grid */
.tr-steps-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(min(100%,240px), 1fr));
    gap: 20px;
}
.tr-step-card {
    border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px;
    position: relative; background: #fff;
    transition: box-shadow .18s, transform .18s;
}
.tr-step-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.09); transform: translateY(-2px); }
.tr-step-num {
    position: absolute; top: -14px; left: 16px;
    width: 28px; height: 28px; border-radius: 50%;
    background: var(--orange,#e8651a); color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 700;
}
.tr-step-icon {
    width: 44px; height: 44px; border-radius: 10px;
    background: rgba(45,43,143,.08); display: flex; align-items: center;
    justify-content: center; color: var(--navy,#2d2b8f); font-size: 18px;
    margin-bottom: 12px;
}
.tr-step-title { font-size: 14px; font-weight: 700; color: #111827; margin-bottom: 8px; }
.tr-step-desc  { font-size: 13px; color: #64748b; line-height: 1.6; }

/* University filter bar */
.tr-filter-bar {
    display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 24px; align-items: center;
}
.tr-search-wrap { position: relative; flex: 1; min-width: 220px; }
.tr-search-wrap i {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    color: #94a3b8; font-size: 13px; pointer-events: none;
}
.tr-search-wrap input {
    width: 100%; padding: 9px 12px 9px 34px;
    border: 1px solid #e5e7eb; border-radius: 8px;
    font-size: 13px; color: #111827; outline: none;
    background: #fff; box-sizing: border-box; transition: border-color .18s;
}
.tr-search-wrap input:focus { border-color: var(--orange,#e8651a); }
.tr-filter-select {
    padding: 9px 12px; border: 1px solid #e5e7eb; border-radius: 8px;
    font-size: 13px; color: #374151; background: #fff;
    outline: none; cursor: pointer; min-width: 150px;
}
.tr-results-count { font-size: 13px; color: #64748b; margin-bottom: 20px; }

/* University cards grid */
.tr-uni-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(min(100%,460px), 1fr));
    gap: 24px;
}
.tr-uni-card {
    background: #fff; border: 1px solid #e5e7eb; border-radius: 16px;
    padding: 24px; transition: box-shadow .18s, transform .18s;
}
.tr-uni-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.09); transform: translateY(-2px); }
.tr-uni-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; gap: 12px; }
.tr-uni-name  { font-size: 17px; font-weight: 700; color: #111827; margin-bottom: 4px; }
.tr-uni-loc   { font-size: 12px; color: #94a3b8; display: flex; align-items: center; gap: 4px; }
.tr-type-badge {
    font-size: 11px; font-weight: 600; padding: 3px 10px;
    border-radius: 999px; white-space: nowrap; flex-shrink: 0;
}
.tr-type-badge.federal  { background: rgba(59,130,246,.1);  color: #2563eb; }
.tr-type-badge.state    { background: rgba(34,197,94,.1);   color: #16a34a; }
.tr-type-badge.private  { background: rgba(168,85,247,.1);  color: #7c3aed; }
.tr-meta-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px;
}
.tr-meta-item { display: flex; align-items: flex-start; gap: 8px; }
.tr-meta-item i { color: #94a3b8; font-size: 14px; margin-top: 2px; flex-shrink: 0; }
.tr-meta-label { font-size: 11px; color: #94a3b8; margin-bottom: 2px; }
.tr-meta-value { font-size: 13px; font-weight: 600; color: #111827; }
.tr-info-row { display: flex; align-items: flex-start; gap: 8px; font-size: 13px; color: #374151; margin-bottom: 8px; }
.tr-info-row i { color: #94a3b8; font-size: 13px; flex-shrink: 0; margin-top: 2px; }
.tr-note { display: flex; align-items: flex-start; gap: 8px; font-size: 12px; color: var(--orange,#e8651a); margin-bottom: 12px; }
.tr-note i { flex-shrink: 0; margin-top: 2px; }
.tr-card-actions { display: flex; gap: 8px; padding-top: 16px; border-top: 1px solid #f1f5f9; }
.tr-action-btn {
    flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 5px;
    padding: 8px 10px; border-radius: 7px; font-size: 12px; font-weight: 600;
    border: 1px solid #e5e7eb; background: #fff; color: #374151;
    text-decoration: none; cursor: pointer; transition: all .15s;
}
.tr-action-btn:hover { border-color: var(--orange,#e8651a); color: var(--orange,#e8651a); }
.tr-action-btn.primary { background: var(--orange,#e8651a); color: #fff; border-color: var(--orange,#e8651a); }
.tr-action-btn.primary:hover { opacity: .88; color: #fff; }

/* Empty state */
.tr-empty { text-align: center; padding: 60px 20px; border: 1px solid #e5e7eb; border-radius: 16px; background: #fff; }
.tr-empty i { font-size: 48px; color: #cbd5e1; margin-bottom: 16px; display: block; }

/* Tabs */
.tr-tabs { display: flex; gap: 0; border: 1px solid #e5e7eb; border-radius: 9px; overflow: hidden; margin-bottom: 24px; width: fit-content; }
.tr-tab-btn {
    padding: 10px 22px; font-size: 13px; font-weight: 600; border: none;
    background: #fff; color: #64748b; cursor: pointer; transition: all .15s;
}
.tr-tab-btn.active { background: var(--orange,#e8651a); color: #fff; }
.tr-tab-content { display: none; }
.tr-tab-content.active { display: block; }
.tr-doc-list { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; }
.tr-doc-item { display: flex; align-items: flex-start; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #374151; }
.tr-doc-item:last-child { border-bottom: none; }
.tr-doc-item i.green  { color: #16a34a; font-size: 16px; flex-shrink: 0; margin-top: 1px; }
.tr-doc-item i.amber  { color: #d97706; font-size: 16px; flex-shrink: 0; margin-top: 1px; }
.tr-doc-item i.blue   { color: #2563eb; font-size: 16px; flex-shrink: 0; margin-top: 1px; }
.tr-doc-note { font-size: 13px; color: #64748b; margin-bottom: 16px; }

/* Challenges accordion */
.tr-accordion { max-width: 860px; margin: 0 auto; }
.tr-acc-item { border: 1px solid #e5e7eb; border-radius: 10px; margin-bottom: 10px; overflow: hidden; background: #fff; }
.tr-acc-trigger {
    display: flex; align-items: center; gap: 12px; padding: 18px 20px;
    cursor: pointer; width: 100%; background: none; border: none; text-align: left;
    font-size: 14px; font-weight: 600; color: #111827;
}
.tr-acc-trigger:hover { background: #fafafa; }
.tr-acc-trigger i.amber { color: #d97706; font-size: 16px; flex-shrink: 0; }
.tr-acc-chevron { margin-left: auto; color: #94a3b8; font-size: 12px; transition: transform .2s; flex-shrink: 0; }
.tr-acc-chevron.open { transform: rotate(180deg); }
.tr-acc-body { display: none; padding: 0 20px 20px 48px; }
.tr-acc-body.open { display: block; }
.tr-acc-desc { font-size: 13px; color: #64748b; line-height: 1.6; margin-bottom: 12px; }
.tr-acc-solution { display: flex; align-items: flex-start; gap: 8px; font-size: 13px; color: #16a34a; }
.tr-acc-solution i { flex-shrink: 0; margin-top: 2px; }

/* CTA */
.tr-cta { background: #0f1f3d; padding: 80px 20px; text-align: center; color: #fff; }
.tr-cta h2 { font-size: clamp(24px,3vw,34px); font-weight: 800; margin-bottom: 14px; color: #fff; }
.tr-cta p  { font-size: 16px; color: rgba(255,255,255,.8); margin-bottom: 28px; }
.tr-cta-btn {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--orange,#e8651a); color: #fff; border: none; border-radius: 9px;
    padding: 14px 32px; font-size: 15px; font-weight: 700; text-decoration: none;
    transition: opacity .15s;
}
.tr-cta-btn:hover { opacity: .88; color: #fff; }

@media (max-width: 700px) {
    .tr-steps-grid { grid-template-columns: 1fr 1fr; }
    .tr-uni-grid   { grid-template-columns: 1fr; }
    .tr-meta-grid  { grid-template-columns: 1fr; }
    .tr-tabs       { width: 100%; }
    .tr-tab-btn    { flex: 1; text-align: center; }
}
</style>

<?php
/* ── DATA ──────────────────────────────────────────────────────────── */
$transcript_steps = [
    ['Identify Your Institution',  'Locate your university in our database and review their specific requirements and processing times.'],
    ['Gather Required Documents',  'Collect all necessary documents including ID, original certificates, and payment receipts.'],
    ['Submit Application',         'Complete the application through the university\'s designated channel (online or physical).'],
    ['Make Payment',               'Pay the required fees through approved payment channels and retain proof of payment.'],
    ['Track Processing',           'Monitor your application status through the university\'s tracking system or follow up directly.'],
    ['Verification Process',       'The university verifies your academic records against their archives.'],
    ['Transcript Preparation',     'Your official transcript is prepared and sealed by the registrar\'s office.'],
    ['Delivery',                   'Receive your transcript via your chosen delivery method (courier, collection, or email).'],
];

$universities = [
    ['id'=>1,'name'=>'University of Lagos',           'short'=>'UNILAG','location'=>'Lagos','state'=>'Lagos',  'type'=>'federal',
     'time'=>'4–6 weeks','rate'=>'85%','fee_local'=>'₦5,000','fee_intl'=>'$50',
     'method'=>'Online + Physical','delivery'=>'Courier / Collection',
     'email'=>'transcripts@unilag.edu.ng','phone'=>'+234 1 280 2439','website'=>'https://unilag.edu.ng',
     'note'=>'Online portal available for alumni requests'],
    ['id'=>2,'name'=>'University of Ibadan',          'short'=>'UI',    'location'=>'Ibadan','state'=>'Oyo',   'type'=>'federal',
     'time'=>'6–8 weeks','rate'=>'80%','fee_local'=>'₦3,500','fee_intl'=>'$45',
     'method'=>'Physical only','delivery'=>'Collection only',
     'email'=>'registry@ui.edu.ng','phone'=>'+234 2 810 1100','website'=>'https://ui.edu.ng',
     'note'=>'Must appear in person or use authorized agent'],
    ['id'=>3,'name'=>'Ahmadu Bello University',       'short'=>'ABU',   'location'=>'Zaria','state'=>'Kaduna','type'=>'federal',
     'time'=>'6–10 weeks','rate'=>'75%','fee_local'=>'₦4,000','fee_intl'=>'$40',
     'method'=>'Physical only','delivery'=>'Collection / Courier',
     'email'=>'registrar@abu.edu.ng','phone'=>'+234 69 550 681','website'=>'https://abu.edu.ng',
     'note'=>'Allow extra time during ASUU strike periods'],
    ['id'=>4,'name'=>'Obafemi Awolowo University',    'short'=>'OAU',   'location'=>'Ile-Ife','state'=>'Osun','type'=>'federal',
     'time'=>'4–8 weeks','rate'=>'82%','fee_local'=>'₦4,500','fee_intl'=>'$48',
     'method'=>'Online + Physical','delivery'=>'Courier / Collection',
     'email'=>'transcripts@oauife.edu.ng','phone'=>'+234 36 230 290','website'=>'https://oauife.edu.ng',
     'note'=>''],
    ['id'=>5,'name'=>'University of Nigeria Nsukka',  'short'=>'UNN',   'location'=>'Nsukka','state'=>'Enugu','type'=>'federal',
     'time'=>'6–8 weeks','rate'=>'78%','fee_local'=>'₦3,000','fee_intl'=>'$38',
     'method'=>'Physical only','delivery'=>'Collection / Courier',
     'email'=>'registry@unn.edu.ng','phone'=>'+234 42 771 911','website'=>'https://unn.edu.ng',
     'note'=>''],
    ['id'=>6,'name'=>'University of Benin',           'short'=>'UNIBEN','location'=>'Benin City','state'=>'Edo','type'=>'federal',
     'time'=>'4–6 weeks','rate'=>'83%','fee_local'=>'₦5,000','fee_intl'=>'$50',
     'method'=>'Online + Physical','delivery'=>'Courier / Collection',
     'email'=>'registry@uniben.edu.ng','phone'=>'+234 52 600 439','website'=>'https://uniben.edu.ng',
     'note'=>''],
    ['id'=>7,'name'=>'Lagos State University',        'short'=>'LASU',  'location'=>'Ojo, Lagos','state'=>'Lagos','type'=>'state',
     'time'=>'3–5 weeks','rate'=>'88%','fee_local'=>'₦6,000','fee_intl'=>'$55',
     'method'=>'Online','delivery'=>'Email / Courier',
     'email'=>'transcripts@lasu.edu.ng','phone'=>'+234 1 420 8200','website'=>'https://lasu.edu.ng',
     'note'=>'Fastest processing among state universities'],
    ['id'=>8,'name'=>'Covenant University',           'short'=>'CU',    'location'=>'Ota','state'=>'Ogun',   'type'=>'private',
     'time'=>'2–4 weeks','rate'=>'95%','fee_local'=>'₦10,000','fee_intl'=>'$80',
     'method'=>'Online','delivery'=>'Email / Courier',
     'email'=>'registry@covenantuniversity.edu.ng','phone'=>'+234 1 454 7500','website'=>'https://covenantuniversity.edu.ng',
     'note'=>'Best success rate — fully digital process'],
    ['id'=>9,'name'=>'Bayero University Kano',        'short'=>'BUK',   'location'=>'Kano','state'=>'Kano',  'type'=>'federal',
     'time'=>'6–10 weeks','rate'=>'72%','fee_local'=>'₦3,500','fee_intl'=>'$40',
     'method'=>'Physical only','delivery'=>'Collection / Courier',
     'email'=>'registry@buk.edu.ng','phone'=>'+234 64 666 021','website'=>'https://buk.edu.ng',
     'note'=>''],
    ['id'=>10,'name'=>'Rivers State University',      'short'=>'RSU',   'location'=>'Port Harcourt','state'=>'Rivers','type'=>'state',
     'time'=>'4–6 weeks','rate'=>'80%','fee_local'=>'₦5,500','fee_intl'=>'$50',
     'method'=>'Online + Physical','delivery'=>'Courier / Collection',
     'email'=>'registry@rsu.edu.ng','phone'=>'+234 84 230 910','website'=>'https://rsu.edu.ng',
     'note'=>''],
];

$required_docs = [
    'mandatory' => [
        'Valid government-issued photo ID (National ID, Passport, or Driver\'s License)',
        'Original degree certificate or statement of result',
        'Completed transcript request form (institution-specific)',
        'Proof of payment of transcript fee',
        'Two recent passport photographs',
        'Application letter addressed to the Registrar',
        'Matriculation number or student ID',
    ],
    'conditional' => [
        'Marriage certificate (if name has changed since graduation)',
        'Affidavit of name change (if applicable)',
        'Letter of authorization (if using a representative)',
        'Representative\'s valid ID',
        'Employer or institution\'s official request letter',
        'Previous transcript copies (for reissuance requests)',
    ],
    'international' => [
        'Receiving institution\'s official address or email for direct dispatch',
        'International courier account number (DHL/FedEx) if applicable',
        'Apostille request form (for countries requiring Hague Convention authentication)',
        'Notarized copy of passport',
        'Additional handling fee payment proof',
    ],
];

$challenges = [
    ['challenge'=>'Lost or missing academic records',
     'description'=>'Some universities have incomplete digital archives, especially for graduates before 2000. Physical records may have been damaged or misplaced.',
     'solution'=>'Request a physical archive search. Bring your original certificates as supporting evidence. Some universities can reconstruct records from departmental copies.'],
    ['challenge'=>'Delayed processing beyond stated timelines',
     'description'=>'Administrative backlogs, staff shortages, or academic calendar disruptions can significantly delay processing.',
     'solution'=>'Submit a formal follow-up letter after 2 weeks. Escalate to the Deputy Registrar if there is no response within 30 days. Keep all correspondence records.'],
    ['challenge'=>'Name discrepancy between certificate and ID',
     'description'=>'Minor spelling differences or use of maiden vs married name can cause rejection or delays.',
     'solution'=>'Obtain an affidavit of name change from a magistrate court. Submit alongside a marriage certificate if applicable.'],
    ['challenge'=>'Payment disputes or lost payment receipts',
     'description'=>'Banks sometimes fail to remit to universities. Lost receipts create verification problems.',
     'solution'=>'Always keep digital copies of payment receipts. Contact your bank to obtain a re-print. Universities can verify payments through their accounts department.'],
    ['challenge'=>'Unresponsive registry office',
     'description'=>'Emails go unanswered and phone calls are not returned, making it impossible to track progress.',
     'solution'=>'Visit in person or send a representative with written authorization. File a formal complaint with the university\'s Vice-Chancellor\'s office if needed.'],
];

/* ── Active search/filter (PHP-side for no-JS fallback) ──────────────── */
$search      = trim($_GET['q']     ?? '');
$state_f     = trim($_GET['state'] ?? '');
$type_f      = trim($_GET['type']  ?? '');

$filtered = array_filter($universities, function($u) use ($search, $state_f, $type_f) {
    if ($search && stripos($u['name'].$u['short'].$u['location'], $search) === false) return false;
    if ($state_f && $u['state'] !== $state_f) return false;
    if ($type_f  && $u['type']  !== $type_f)  return false;
    return true;
});
$all_states = array_unique(array_column($universities, 'state'));
sort($all_states);
?>


<!-- ══ BREADCRUMB ════════════════════════════════════════════════════════ -->
<div class="tr-breadcrumb">
    <div class="tr-breadcrumb-inner">
        <a href="advisory.php">Advisory</a>
        <i class="fas fa-chevron-right"></i>
        <span>Transcript Advisory</span>
    </div>
</div>


<!-- ══ HERO ══════════════════════════════════════════════════════════════ -->
<section class="tr-hero">
    <div class="tr-hero-bg"></div>
    <div class="tr-hero-overlay"></div>
    <div class="tr-hero-dots"></div>
    <div class="tr-hero-inner">
        <div class="tr-hero-eyebrow">Transcript Advisory</div>
        <h1 class="tr-hero-title">Transcript Advisory</h1>
        <p class="tr-hero-sub">Navigate the transcript request process at Nigerian universities with our comprehensive guide and institution-specific information.</p>
    </div>
</section>


<!-- ══ 8-STEP PROCESS ════════════════════════════════════════════════════ -->
<section class="section">
    <div class="section-center" style="margin-bottom:48px">
        <h2 class="section-title">The 8-Step Process</h2>
        <p class="section-subtitle">A general guide to obtaining transcripts from Nigerian universities</p>
    </div>
    <div class="tr-steps-grid">
        <?php foreach ($transcript_steps as $i => $s): ?>
        <div class="tr-step-card">
            <div class="tr-step-num"><?= $i+1 ?></div>
            <div class="tr-step-icon"><i class="fas fa-file-alt"></i></div>
            <div class="tr-step-title"><?= htmlspecialchars($s[0]) ?></div>
            <p class="tr-step-desc"><?= htmlspecialchars($s[1]) ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</section>


<!-- ══ UNIVERSITY DATABASE ═══════════════════════════════════════════════ -->
<section class="section" style="background:#f8f9ff;border-top:1px solid #e5e7eb" id="universities">
    <div class="section-center" style="margin-bottom:32px">
        <h2 class="section-title">University Database</h2>
        <p class="section-subtitle">Find transcript processing information for Nigerian universities</p>
    </div>

    <!-- Filter bar -->
    <form method="GET" id="tr-filter-form">
        <div class="tr-filter-bar">
            <div class="tr-search-wrap">
                <i class="fas fa-search"></i>
                <input type="text" name="q" id="tr-search"
                       value="<?= htmlspecialchars($search) ?>"
                       placeholder="Search universities..."
                       oninput="trFilter()">
            </div>
            <select name="state" id="tr-state" class="tr-filter-select" onchange="trFilter()">
                <option value="">All States</option>
                <?php foreach ($all_states as $st): ?>
                <option value="<?= htmlspecialchars($st) ?>" <?= $state_f===$st?'selected':'' ?>><?= htmlspecialchars($st) ?></option>
                <?php endforeach; ?>
            </select>
            <select name="type" id="tr-type" class="tr-filter-select" onchange="trFilter()">
                <option value="">All Types</option>
                <option value="federal"  <?= $type_f==='federal' ?'selected':'' ?>>Federal</option>
                <option value="state"    <?= $type_f==='state'   ?'selected':'' ?>>State</option>
                <option value="private"  <?= $type_f==='private' ?'selected':'' ?>>Private</option>
            </select>
        </div>
    </form>

    <p class="tr-results-count" id="tr-count">Showing <strong><?= count($filtered) ?></strong> of <strong><?= count($universities) ?></strong> universities</p>

    <!-- University cards -->
    <div class="tr-uni-grid" id="tr-uni-grid">
        <?php foreach ($universities as $u):
            $hidden = !in_array($u, $filtered) ? 'style="display:none"' : '';
        ?>
        <div class="tr-uni-card"
             data-name="<?= strtolower($u['name'].' '.$u['short'].' '.$u['location']) ?>"
             data-state="<?= htmlspecialchars($u['state']) ?>"
             data-type="<?= htmlspecialchars($u['type']) ?>"
             <?= $hidden ?>>
            <div class="tr-uni-header">
                <div>
                    <div class="tr-uni-name"><?= htmlspecialchars($u['name']) ?></div>
                    <div class="tr-uni-loc"><i class="fas fa-map-marker-alt"></i><?= htmlspecialchars($u['location'].', '.$u['state']) ?></div>
                </div>
                <span class="tr-type-badge <?= $u['type'] ?>"><?= ucfirst($u['type']) ?></span>
            </div>

            <div class="tr-meta-grid">
                <div class="tr-meta-item">
                    <i class="fas fa-clock"></i>
                    <div><div class="tr-meta-label">Processing Time</div><div class="tr-meta-value"><?= htmlspecialchars($u['time']) ?></div></div>
                </div>
                <div class="tr-meta-item">
                    <i class="fas fa-check-circle"></i>
                    <div><div class="tr-meta-label">Success Rate</div><div class="tr-meta-value"><?= htmlspecialchars($u['rate']) ?></div></div>
                </div>
                <div class="tr-meta-item">
                    <i class="fas fa-coins"></i>
                    <div><div class="tr-meta-label">Local Fee</div><div class="tr-meta-value"><?= htmlspecialchars($u['fee_local']) ?></div></div>
                </div>
                <div class="tr-meta-item">
                    <i class="fas fa-globe"></i>
                    <div><div class="tr-meta-label">Int'l Fee</div><div class="tr-meta-value"><?= htmlspecialchars($u['fee_intl']) ?></div></div>
                </div>
            </div>

            <div class="tr-info-row"><i class="fas fa-file-alt"></i><span><strong>Method:</strong> <?= htmlspecialchars($u['method']) ?></span></div>
            <div class="tr-info-row"><i class="fas fa-envelope"></i><span><strong>Delivery:</strong> <?= htmlspecialchars($u['delivery']) ?></span></div>
            <?php if ($u['note']): ?>
            <div class="tr-note"><i class="fas fa-exclamation-circle"></i><span><?= htmlspecialchars($u['note']) ?></span></div>
            <?php endif; ?>

            <div class="tr-card-actions">
                <a href="mailto:<?= htmlspecialchars($u['email']) ?>" class="tr-action-btn">
                    <i class="fas fa-envelope"></i> Email
                </a>
                <a href="tel:<?= htmlspecialchars($u['phone']) ?>" class="tr-action-btn">
                    <i class="fas fa-phone"></i> Call
                </a>
                <a href="<?= htmlspecialchars($u['website']) ?>" target="_blank" rel="noopener noreferrer" class="tr-action-btn primary">
                    <i class="fas fa-globe"></i> Website
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="tr-empty" id="tr-empty" style="display:none">
        <i class="fas fa-building"></i>
        <h3 style="font-size:17px;font-weight:700;color:#111827;margin-bottom:8px">No universities found</h3>
        <p style="font-size:13px;color:#64748b">Try adjusting your search or filters</p>
    </div>
</section>


<!-- ══ REQUIRED DOCUMENTS ════════════════════════════════════════════════ -->
<section class="section">
    <div class="section-center" style="margin-bottom:40px">
        <h2 class="section-title">Required Documents</h2>
        <p class="section-subtitle">Prepare these documents before starting your transcript request</p>
    </div>
    <div style="max-width:860px;margin:0 auto">
        <div class="tr-tabs">
            <button class="tr-tab-btn active" onclick="trTab(this,'mandatory')">Mandatory</button>
            <button class="tr-tab-btn" onclick="trTab(this,'conditional')">Conditional</button>
            <button class="tr-tab-btn" onclick="trTab(this,'international')">International</button>
        </div>

        <div id="tab-mandatory" class="tr-tab-content active">
            <div class="tr-doc-list">
                <?php foreach ($required_docs['mandatory'] as $doc): ?>
                <div class="tr-doc-item"><i class="fas fa-check-circle green"></i><?= htmlspecialchars($doc) ?></div>
                <?php endforeach; ?>
            </div>
        </div>

        <div id="tab-conditional" class="tr-tab-content">
            <p class="tr-doc-note">These documents may be required depending on your specific situation:</p>
            <div class="tr-doc-list">
                <?php foreach ($required_docs['conditional'] as $doc): ?>
                <div class="tr-doc-item"><i class="fas fa-exclamation-circle amber"></i><?= htmlspecialchars($doc) ?></div>
                <?php endforeach; ?>
            </div>
        </div>

        <div id="tab-international" class="tr-tab-content">
            <p class="tr-doc-note">Additional documents for international transcript requests:</p>
            <div class="tr-doc-list">
                <?php foreach ($required_docs['international'] as $doc): ?>
                <div class="tr-doc-item"><i class="fas fa-globe blue"></i><?= htmlspecialchars($doc) ?></div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>


<!-- ══ COMMON CHALLENGES ═════════════════════════════════════════════════ -->
<section class="section" style="background:#f8f9ff;border-top:1px solid #e5e7eb">
    <div class="section-center" style="margin-bottom:40px">
        <h2 class="section-title">Common Challenges &amp; Solutions</h2>
        <p class="section-subtitle">Prepared responses to typical issues you might encounter</p>
    </div>
    <div class="tr-accordion">
        <?php foreach ($challenges as $i => $c): ?>
        <div class="tr-acc-item">
            <button class="tr-acc-trigger" onclick="trAcc(this)">
                <i class="fas fa-exclamation-circle amber"></i>
                <span><?= htmlspecialchars($c['challenge']) ?></span>
                <i class="fas fa-chevron-down tr-acc-chevron"></i>
            </button>
            <div class="tr-acc-body">
                <p class="tr-acc-desc"><?= htmlspecialchars($c['description']) ?></p>
                <div class="tr-acc-solution">
                    <i class="fas fa-check-circle"></i>
                    <span><?= htmlspecialchars($c['solution']) ?></span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>


<!-- ══ CTA ═══════════════════════════════════════════════════════════════ -->
<section class="tr-cta">
    <h2>Need Personalized Help?</h2>
    <p>If you're facing specific challenges or need guidance tailored to your situation, request our advisory support.</p>
    <a href="advisory.php#request-form" class="tr-cta-btn">Request Advisory Support <i class="fas fa-arrow-right"></i></a>
</section>


<script>
/* ── Live filter ─────────────────────────────────────────────────────── */
function trFilter() {
    var q     = document.getElementById('tr-search').value.toLowerCase();
    var state = document.getElementById('tr-state').value;
    var type  = document.getElementById('tr-type').value;
    var cards = document.querySelectorAll('.tr-uni-card');
    var shown = 0;
    cards.forEach(function(c) {
        var nameMatch  = !q     || c.dataset.name.includes(q);
        var stateMatch = !state || c.dataset.state === state;
        var typeMatch  = !type  || c.dataset.type  === type;
        var visible    = nameMatch && stateMatch && typeMatch;
        c.style.display = visible ? '' : 'none';
        if (visible) shown++;
    });
    document.getElementById('tr-count').innerHTML =
        'Showing <strong>' + shown + '</strong> of <strong><?= count($universities) ?></strong> universities';
    document.getElementById('tr-empty').style.display = shown === 0 ? 'block' : 'none';
}

/* ── Tabs ────────────────────────────────────────────────────────────── */
function trTab(btn, id) {
    document.querySelectorAll('.tr-tab-btn').forEach(function(b){ b.classList.remove('active'); });
    document.querySelectorAll('.tr-tab-content').forEach(function(t){ t.classList.remove('active'); });
    btn.classList.add('active');
    document.getElementById('tab-' + id).classList.add('active');
}

/* ── Accordion ───────────────────────────────────────────────────────── */
function trAcc(btn) {
    var body    = btn.parentElement.querySelector('.tr-acc-body');
    var chevron = btn.querySelector('.tr-acc-chevron');
    var isOpen  = body.classList.contains('open');
    document.querySelectorAll('.tr-acc-body').forEach(function(b){ b.classList.remove('open'); });
    document.querySelectorAll('.tr-acc-chevron').forEach(function(c){ c.classList.remove('open'); });
    if (!isOpen) { body.classList.add('open'); chevron.classList.add('open'); }
}
</script>

<?php include 'footer.php'; ?>
