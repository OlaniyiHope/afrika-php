<?php
$page_title = 'Start a Journal';
require_once 'helpers.php';

$step = (int)($_POST['wstep'] ?? 1);
$success = false;
$errors = [];

$data = [
    'journal_title'   => trim($_POST['journal_title'] ?? ''),
    'discipline'      => trim($_POST['discipline'] ?? ''),
    'sub_discipline'  => trim($_POST['sub_discipline'] ?? ''),
    'scope'           => trim($_POST['scope'] ?? ''),
    'lead_institution'=> trim($_POST['lead_institution'] ?? ''),
    'country'         => trim($_POST['country'] ?? ''),
    'website'         => trim($_POST['website'] ?? ''),
    'inst_backed'     => isset($_POST['inst_backed']) ? '1' : '0',
    'editor_name'     => trim($_POST['editor_name'] ?? ''),
    'editor_title'    => trim($_POST['editor_title'] ?? ''),
    'editor_affil'    => trim($_POST['editor_affil'] ?? ''),
    'editor_email'    => trim($_POST['editor_email'] ?? ''),
    'frequency'       => trim($_POST['frequency'] ?? ''),
    'language'        => trim($_POST['language'] ?? 'English'),
    'confirm_review'  => isset($_POST['confirm_review']) ? '1' : '0',
    'confirm_ethics'  => isset($_POST['confirm_ethics']) ? '1' : '0',
    'confirm_no_predatory' => isset($_POST['confirm_no_predatory']) ? '1' : '0',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['go_next'])) {
        $next = (int)$_POST['go_next'];
        if ($next === 2 && !$data['journal_title']) { $errors[] = 'Journal title is required.'; }
        elseif ($next === 3 && !$data['editor_name']) { $errors[] = 'Editor-in-Chief name is required.'; }
        if (empty($errors)) $step = $next;
    } elseif (isset($_POST['go_prev'])) {
        $step = max(1, (int)$_POST['go_prev']); $errors = [];
    } elseif (isset($_POST['final_submit'])) {
        if (!$data['confirm_review'] || !$data['confirm_ethics'] || !$data['confirm_no_predatory']) {
            $errors[] = 'You must confirm all ethical commitments.'; $step = 5;
        } else {
            $id = get_next_id('manuscripts.csv');
            append_csv('manuscripts.csv', [$id, 'JOURNAL:'.$data['journal_title'], $data['editor_name'], '0', $data['discipline'], $data['scope'], '', date('Y-m-d'), 'journal_proposal']);
            $success = true; $step = 7;
        }
    }
}

include 'header.php';

$wsteps = ['Proposal','Governance','Standards','Technical','Review','Launch'];
?>

<style>
/* ── Hero ── */
.sj-hero{position:relative;overflow:hidden;min-height:400px;display:flex;align-items:center;justify-content:center}
.sj-hero-bg{position:absolute;inset:0;background:url('asset/about-conference.jpg') center/cover no-repeat}
.sj-hero-overlay{position:absolute;inset:0;background:rgba(10,25,60,.85)}
.sj-hero-dots{position:absolute;inset:0;opacity:.10;background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:24px 24px}
.sj-hero-content{position:relative;z-index:1;text-align:center;padding:80px 20px;max-width:700px;margin:0 auto}
.sj-hero-eyebrow{font-size:12px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--orange);margin-bottom:16px}
.sj-hero h1{color:#fff;font-size:clamp(2rem,5vw,3rem);font-weight:800;margin:0 0 20px}
.sj-hero p{color:rgba(255,255,255,.8);font-size:17px;margin:0}

/* ── Section helpers ── */
.sj-section{padding:72px 0}
.sj-section-muted{background:#f8f9ff}
.container{max-width:1200px;margin:0 auto;padding:0 24px}

/* ── Landing two-col ── */
.sj-landing-grid{display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center;max-width:900px;margin:0 auto}
@media(max-width:800px){.sj-landing-grid{grid-template-columns:1fr}}
.sj-landing-left h2{font-size:clamp(1.6rem,3vw,2rem);font-weight:800;color:var(--navy);margin:0 0 24px}
.sj-check-list{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:14px}
.sj-check-list li{display:flex;align-items:center;gap:12px;font-size:15px;color:var(--navy)}
.sj-check-list li i{color:var(--orange);font-size:15px;flex-shrink:0}

/* ── Proposal card ── */
.sj-proposal-card{background:#fff;border:1px solid var(--border);border-radius:16px;padding:32px}
.sj-proposal-card h3{font-size:18px;font-weight:700;color:var(--navy);margin:0 0 4px}
.sj-proposal-card .sub{font-size:13px;color:var(--text-gray);margin:0 0 24px}
.sj-wizard-step-list{display:flex;flex-direction:column;gap:14px;margin-bottom:24px}
.sj-wizard-step-item{display:flex;align-items:center;gap:14px}
.sj-wizard-step-num{width:28px;height:28px;border-radius:50%;background:var(--orange);color:#fff;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0}
.sj-wizard-step-item span{font-size:14px;font-weight:600;color:var(--navy)}
.sj-start-btn{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:14px;background:var(--orange);color:#fff;border:none;border-radius:8px;font-size:15px;font-weight:700;cursor:pointer;transition:background .2s}
.sj-start-btn:hover{background:rgba(232,101,26,.85)}

/* ── Progress bar ── */
.sj-progress-bar{background:#fff;border-bottom:1px solid var(--border);padding:20px 24px}
.sj-progress-bar .container{max-width:760px}
.sj-wizard-steps{display:flex;align-items:center;justify-content:space-between;gap:4px}
.sj-wstep{display:flex;flex-direction:column;align-items:center;gap:6px;flex:1}
.sj-wstep-circle{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;border:2px solid #d1d5db;color:#9ca3af;background:#fff;transition:all .2s}
.sj-wstep.done .sj-wstep-circle{background:var(--orange);border-color:var(--orange);color:#fff}
.sj-wstep.active .sj-wstep-circle{background:var(--navy);border-color:var(--navy);color:#fff}
.sj-wstep-label{font-size:11px;color:var(--text-gray);font-weight:500}
.sj-wstep.active .sj-wstep-label{color:var(--navy);font-weight:700}
.sj-wstep.done .sj-wstep-label{color:var(--orange)}
</style>

<!-- Hero -->
<section class="sj-hero">
  <div class="sj-hero-bg"></div>
  <div class="sj-hero-overlay"></div>
  <div class="sj-hero-dots"></div>
  <div class="sj-hero-content">
    <p class="sj-hero-eyebrow">Start a Journal</p>
    <h1>Start a Journal</h1>
    <p>Launch a peer-reviewed academic journal with Afrika Scholar's publishing infrastructure.</p>
  </div>
</section>

<?php if ($step === 1 && !isset($_POST['go_next'])): ?>

<!-- Landing -->
<section class="sj-section">
  <div class="container">
    <div class="sj-landing-grid">

      <!-- Left: What You'll Get -->
      <div class="sj-landing-left">
        <h2>What You'll Get</h2>
        <ul class="sj-check-list">
          <?php foreach ([
            'Professional journal hosting',
            'Peer review management system',
            'DOI registration',
            'Indexing support',
            'Editorial dashboard',
            'Author submission portal',
          ] as $item): ?>
          <li><i class="fas fa-check-circle"></i> <?= $item ?></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Right: Proposal card -->
      <div class="sj-proposal-card">
        <h3>Journal Proposal Process</h3>
        <p class="sub">6-step guided wizard</p>

        <div class="sj-wizard-step-list">
          <?php foreach ($wsteps as $i => $wl): ?>
          <div class="sj-wizard-step-item">
            <div class="sj-wizard-step-num"><?= $i + 1 ?></div>
            <span><?= $wl ?></span>
          </div>
          <?php endforeach; ?>
        </div>

        <form method="POST">
          <input type="hidden" name="wstep" value="1">
          <?php foreach ($data as $k => $v): ?>
          <input type="hidden" name="<?= $k ?>" value="<?= htmlspecialchars($v) ?>">
          <?php endforeach; ?>
          <button type="submit" name="go_next" value="1" class="sj-start-btn">
            Start Proposal <i class="fas fa-arrow-right"></i>
          </button>
        </form>
      </div>

    </div>
  </div>
</section>

<?php else: ?>

<!-- Wizard progress bar -->
<div class="sj-progress-bar">
  <div class="container">
    <div class="sj-wizard-steps">
      <?php foreach ($wsteps as $i => $wl):
        $num = $i + 1;
        $done   = $num < $step;
        $active = $num === $step;
      ?>
      <div class="sj-wstep <?= $done ? 'done' : ($active ? 'active' : '') ?>">
        <div class="sj-wstep-circle">
          <?= $done ? '<i class="fas fa-check" style="font-size:12px"></i>' : $num ?>
        </div>
        <div class="sj-wstep-label"><?= $wl ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<section class="sj-section sj-section-muted">
  <div class="container" style="max-width:760px">

    <?php if (!empty($errors)): ?>
    <div class="alert alert-error"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div>
    <?php endif; ?>

    <?php if ($success || $step === 7): ?>
    <!-- SUCCESS -->
    <div style="background:#fff;border:1px solid var(--border);border-radius:16px;padding:60px;text-align:center">
      <div style="width:72px;height:72px;background:rgba(232,101,26,.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;font-size:32px;color:var(--orange)">
        <i class="fas fa-check-circle"></i>
      </div>
      <h2 style="font-size:24px;font-weight:800;margin-bottom:12px">Journal Proposal Submitted!</h2>
      <p style="color:var(--text-gray);margin-bottom:8px">Your proposal for <strong style="color:var(--navy)">"<?= htmlspecialchars($data['journal_title']) ?>"</strong> has been received.</p>
      <p style="color:var(--text-gray);font-size:14px;margin-bottom:32px">Our editorial infrastructure team will review your proposal and contact you within 5-7 business days to discuss next steps.</p>
      <div style="display:flex;gap:12px;justify-content:center">
        <a href="start_journal.php" class="btn btn-outline">Start Another</a>
        <a href="index.php" class="btn btn-navy">Go to Home</a>
      </div>
    </div>

    <?php else: ?>
    <form method="POST">
      <input type="hidden" name="wstep" value="<?= $step ?>">
      <?php foreach ($data as $k => $v): ?>
      <input type="hidden" name="<?= $k ?>" value="<?= htmlspecialchars($v) ?>">
      <?php endforeach; ?>

      <?php if ($step === 1): ?>
      <!-- STEP 1: Proposal -->
      <div style="text-align:center;margin-bottom:32px">
        <h2 style="font-size:24px;font-weight:800;color:var(--navy)">Journal Proposal</h2>
        <p style="color:var(--text-gray)">Capture your journal's intent and academic direction.</p>
      </div>
      <div style="background:#fff;border:1px solid var(--border);border-radius:14px;padding:32px;margin-bottom:20px">
        <h4 style="font-size:16px;font-weight:700;margin-bottom:20px">A. Journal Identity</h4>
        <div class="form-group">
          <label class="form-label">Proposed Journal Title *</label>
          <input type="text" name="journal_title" class="form-control" value="<?= htmlspecialchars($data['journal_title']) ?>" placeholder="e.g. African Journal of Applied Mathematics">
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
          <div class="form-group">
            <label class="form-label">Discipline / Field *</label>
            <input type="text" name="discipline" class="form-control" value="<?= htmlspecialchars($data['discipline']) ?>" placeholder="e.g. Mathematics">
          </div>
          <div class="form-group">
            <label class="form-label">Sub-discipline / Focus Area</label>
            <input type="text" name="sub_discipline" class="form-control" value="<?= htmlspecialchars($data['sub_discipline']) ?>" placeholder="e.g. Applied Mathematics">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Journal Scope (500–700 words)</label>
          <textarea name="scope" class="form-control" rows="6" placeholder="Describe the journal's scope, aims, and target audience..."><?= htmlspecialchars($data['scope']) ?></textarea>
        </div>
      </div>
      <div style="background:#fff;border:1px solid var(--border);border-radius:14px;padding:32px;margin-bottom:20px">
        <h4 style="font-size:16px;font-weight:700;margin-bottom:20px">B. Sponsoring Entity</h4>
        <div class="form-group">
          <label class="form-label">Lead Institution / Society</label>
          <input type="text" name="lead_institution" class="form-control" value="<?= htmlspecialchars($data['lead_institution']) ?>" placeholder="University or society name">
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
          <div class="form-group">
            <label class="form-label">Country</label>
            <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($data['country']) ?>">
          </div>
          <div class="form-group">
            <label class="form-label">Website</label>
            <input type="url" name="website" class="form-control" value="<?= htmlspecialchars($data['website']) ?>" placeholder="https://">
          </div>
        </div>
        <label style="display:flex;align-items:center;gap:10px;cursor:pointer">
          <div style="position:relative;width:44px;height:24px;background:<?= $data['inst_backed']==='1'?'var(--orange)':'#d1d5db' ?>;border-radius:12px;cursor:pointer;transition:background .2s">
            <div style="position:absolute;top:2px;left:<?= $data['inst_backed']==='1'?'22px':'2px' ?>;width:20px;height:20px;background:#fff;border-radius:50%;transition:left .2s"></div>
          </div>
          <input type="checkbox" name="inst_backed" value="1" <?= $data['inst_backed']==='1'?'checked':'' ?> style="display:none">
          <span style="font-size:14px">This journal is institution-backed</span>
        </label>
      </div>
      <div style="display:flex;justify-content:flex-end">
        <button type="submit" name="go_next" value="2" class="btn btn-navy">Continue Setup →</button>
      </div>

      <?php elseif ($step === 2): ?>
      <!-- STEP 2: Governance -->
      <div style="text-align:center;margin-bottom:32px">
        <h2 style="font-size:24px;font-weight:800;color:var(--navy)">Editorial Governance & Peer Review Structure</h2>
        <p style="color:var(--text-gray)">Ensure academic credibility with a robust editorial framework.</p>
      </div>
      <div style="background:#fff;border:1px solid var(--border);border-radius:14px;padding:32px;margin-bottom:20px">
        <h4 style="font-size:15px;font-weight:700;margin-bottom:20px">Required Editorial Components</h4>
        <?php foreach ([
          ['fas fa-user-tie',      'Editor-in-Chief',          'The lead editor responsible for editorial direction and final decisions.'],
          ['fas fa-users',         'Associate / Section Editors','Editors managing specific disciplines or article types within the journal.'],
          ['fas fa-clipboard-list','Reviewer Pool',             'A growing pool of qualified peer reviewers. Can be built over time with Afrika Scholar tools.'],
        ] as $comp): ?>
        <div style="display:flex;gap:14px;margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid var(--border)">
          <div style="width:40px;height:40px;background:rgba(45,43,143,.08);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--navy);font-size:16px;flex-shrink:0"><i class="<?= $comp[0] ?>"></i></div>
          <div>
            <div style="font-size:15px;font-weight:700"><?= $comp[1] ?></div>
            <div style="font-size:13px;color:var(--text-gray);margin-top:4px"><?= $comp[2] ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div style="background:#fff;border:1px solid var(--border);border-radius:14px;padding:32px;margin-bottom:20px">
        <h4 style="font-size:15px;font-weight:700;margin-bottom:20px">C. Editorial Leadership</h4>
        <p style="font-size:13px;color:var(--orange);margin-bottom:16px">Editor-in-Chief</p>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px">
          <div class="form-group" style="margin:0"><label class="form-label">Name *</label><input type="text" name="editor_name" class="form-control" value="<?= htmlspecialchars($data['editor_name']) ?>"></div>
          <div class="form-group" style="margin:0"><label class="form-label">Title</label><input type="text" name="editor_title" class="form-control" value="<?= htmlspecialchars($data['editor_title']) ?>" placeholder="Prof."></div>
          <div class="form-group" style="margin:0"><label class="form-label">Affiliation</label><input type="text" name="editor_affil" class="form-control" value="<?= htmlspecialchars($data['editor_affil']) ?>"></div>
        </div>
        <div class="form-group">
          <label class="form-label">Contact Email *</label>
          <input type="email" name="editor_email" class="form-control" value="<?= htmlspecialchars($data['editor_email']) ?>">
        </div>
      </div>
      <div style="display:flex;justify-content:space-between">
        <button type="submit" name="go_prev" value="1" class="btn btn-outline">← Back</button>
        <div style="display:flex;gap:12px">
          <button type="button" class="btn btn-outline" onclick="alert('Guidelines downloaded!')">Download Editorial Guidelines</button>
          <button type="submit" name="go_next" value="3" class="btn btn-orange">Continue Setup →</button>
        </div>
      </div>

      <?php elseif ($step === 3): ?>
      <!-- STEP 3: Standards -->
      <div style="text-align:center;margin-bottom:32px">
        <h2 style="font-size:24px;font-weight:800;color:var(--navy)">Publishing Standards</h2>
        <p style="color:var(--text-gray)">Define the publishing model and ethical commitments for your journal.</p>
      </div>
      <div style="background:#fff;border:1px solid var(--border);border-radius:14px;padding:32px;margin-bottom:20px">
        <h4 style="font-size:15px;font-weight:700;margin-bottom:20px">D. Publishing Model</h4>
        <div style="background:rgba(232,101,26,.08);border:1px solid rgba(232,101,26,.2);border-radius:8px;padding:14px;margin-bottom:20px;font-size:14px">
          <strong style="color:var(--orange)">Open Access</strong> is the default publishing model for all Afrika Scholar journals.
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
          <div class="form-group">
            <label class="form-label">Frequency</label>
            <select name="frequency" class="form-control">
              <?php foreach (['Continuous','Quarterly','Bi-annual','Annual','Monthly'] as $f): ?>
              <option <?= $data['frequency']===$f?'selected':'' ?>><?= $f ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Language(s) of Publication</label>
            <input type="text" name="language" class="form-control" value="<?= htmlspecialchars($data['language']) ?>" placeholder="English">
          </div>
        </div>
      </div>
      <div style="background:#fff;border:1px solid var(--border);border-radius:14px;padding:32px;margin-bottom:20px">
        <h4 style="font-size:15px;font-weight:700;margin-bottom:20px">E. Ethical Commitment</h4>
        <div style="display:flex;flex-direction:column;gap:12px">
          <?php foreach ([
            ['confirm_review',        'I commit to implementing rigorous peer review for all submissions'],
            ['confirm_ethics',        'I commit to following ethical publishing standards (COPE guidelines)'],
            ['confirm_no_predatory',  'I confirm this journal will not engage in predatory or pay-to-publish practices'],
          ] as $d): ?>
          <label style="display:flex;align-items:center;gap:12px;cursor:pointer">
            <input type="checkbox" name="<?= $d[0] ?>" value="1" <?= $data[$d[0]]==='1'?'checked':'' ?> style="accent-color:var(--orange);width:16px;height:16px">
            <span style="font-size:14px;color:var(--navy)"><?= $d[1] ?></span>
          </label>
          <?php endforeach; ?>
        </div>
      </div>
      <div style="display:flex;justify-content:space-between">
        <button type="submit" name="go_prev" value="2" class="btn btn-outline">← Back</button>
        <button type="submit" name="go_next" value="4" class="btn btn-orange">Continue Setup →</button>
      </div>

      <?php elseif ($step === 4): ?>
      <!-- STEP 4: Technical -->
      <div style="text-align:center;margin-bottom:32px">
        <h2 style="font-size:24px;font-weight:800;color:var(--navy)">Technical Setup</h2>
        <p style="color:var(--text-gray)">Configure technical preferences for your journal.</p>
      </div>
      <div style="background:#fff;border:1px solid var(--border);border-radius:14px;padding:32px;margin-bottom:20px">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
          <?php foreach ([
            ['fas fa-globe',    'Custom Domain',      'Your journal will be hosted at a custom subdomain on afrikascholar.org'],
            ['fas fa-robot',    'Submission Portal',  'Authors can submit directly through the Afrika Scholar submission system'],
            ['fas fa-chart-bar','Analytics Dashboard','Track views, downloads, citations, and reader engagement'],
            ['fas fa-search',   'DOI & Indexing',     'Automatic DOI assignment and indexing support included'],
          ] as $feat): ?>
          <div style="display:flex;gap:14px;padding:16px;border:1px solid var(--border);border-radius:10px">
            <div style="width:40px;height:40px;background:rgba(232,101,26,.1);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--orange);flex-shrink:0"><i class="<?= $feat[0] ?>"></i></div>
            <div>
              <div style="font-size:14px;font-weight:700"><?= $feat[1] ?></div>
              <div style="font-size:13px;color:var(--text-gray);margin-top:4px"><?= $feat[2] ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div style="display:flex;justify-content:space-between">
        <button type="submit" name="go_prev" value="3" class="btn btn-outline">← Back</button>
        <button type="submit" name="go_next" value="5" class="btn btn-orange">Continue Setup →</button>
      </div>

      <?php elseif ($step === 5): ?>
      <!-- STEP 5: Review -->
      <div style="text-align:center;margin-bottom:32px">
        <h2 style="font-size:24px;font-weight:800;color:var(--navy)">Review & Submit Proposal</h2>
        <p style="color:var(--text-gray)">Review your journal details before submitting.</p>
      </div>
      <div style="background:#fff;border:1px solid var(--border);border-radius:14px;padding:32px;margin-bottom:20px">
        <?php foreach ([
          'Journal Title' => $data['journal_title'],
          'Discipline'    => $data['discipline'],
          'Editor-in-Chief' => $data['editor_name'],
          'Institution'   => $data['lead_institution'],
          'Frequency'     => $data['frequency'],
          'Language'      => $data['language'],
        ] as $label => $val): ?>
        <?php if ($val): ?>
        <div style="display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border)">
          <span style="font-size:13px;color:var(--text-gray)"><?= $label ?></span>
          <span style="font-size:14px;font-weight:600;color:var(--navy)"><?= htmlspecialchars($val) ?></span>
        </div>
        <?php endif; endforeach; ?>
      </div>
      <div style="display:flex;justify-content:space-between">
        <button type="submit" name="go_prev" value="4" class="btn btn-outline">← Back</button>
        <button type="submit" name="final_submit" value="1" class="btn btn-orange" style="padding:12px 32px">Submit Proposal →</button>
      </div>
      <?php endif; ?>
    </form>
    <?php endif; ?>

  </div>
</section>
<?php endif; ?>

<?php include 'footer.php'; ?>
