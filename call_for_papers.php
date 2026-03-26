<?php
$page_title = 'Call for Papers';
require_once 'helpers.php';
$calls    = read_csv('call_for_papers.csv');
$journals = read_csv('journals.csv');
$open     = array_filter($calls, fn($c) => $c['status'] === 'open');
$upcoming = array_filter($calls, fn($c) => $c['status'] === 'upcoming');
include 'header.php';
?>

<style>
/* ── Hero ── */
.cfp-hero{position:relative;overflow:hidden;min-height:400px;display:flex;align-items:center;justify-content:center}
.cfp-hero-bg{position:absolute;inset:0;background:url('asset/about-conference.jpg') center/cover no-repeat}
.cfp-hero-overlay{position:absolute;inset:0;background:rgba(10,25,60,.85)}
.cfp-hero-dots{position:absolute;inset:0;opacity:.10;background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:24px 24px}
.cfp-hero-content{position:relative;z-index:1;text-align:center;padding:80px 20px;max-width:700px;margin:0 auto}
.cfp-hero-eyebrow{font-size:12px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--orange);margin-bottom:16px}
.cfp-hero h1{color:#fff;font-size:clamp(2rem,5vw,3rem);font-weight:800;margin:0 0 20px}
.cfp-hero p{color:rgba(255,255,255,.8);font-size:17px;margin:0}

/* ── Section helpers ── */
.cfp-section{padding:72px 0}
.cfp-section-muted{background:rgba(0,0,0,.03);border-top:1px solid var(--border)}
.cfp-section-dark{background:var(--navy);position:relative;overflow:hidden}
.container{max-width:1200px;margin:0 auto;padding:0 24px}
.cfp-section-head{text-align:center;margin-bottom:48px}
.cfp-section-head h2{font-size:clamp(1.6rem,3vw,2.2rem);font-weight:800;color:var(--navy);margin:0 0 12px}
.cfp-section-head p{color:var(--text-gray);font-size:15px;max-width:560px;margin:0 auto}

/* ── Open calls grid (3 cols) ── */
.cfp-calls-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;margin-bottom:48px}
@media(max-width:960px){.cfp-calls-grid{grid-template-columns:1fr 1fr}}
@media(max-width:600px){.cfp-calls-grid{grid-template-columns:1fr}}

/* ── Call card ── */
.cfp-card{background:#fff;border:1px solid var(--border);border-radius:14px;display:flex;flex-direction:column;transition:box-shadow .2s,transform .2s;overflow:hidden}
.cfp-card:hover{box-shadow:0 8px 32px rgba(10,25,60,.10);transform:translateY(-3px)}
.cfp-card-header{padding:24px 24px 0}
.cfp-card-journal{display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-gray);margin-bottom:12px}
.cfp-card-journal i{color:var(--orange)}
.cfp-card-title{font-size:17px;font-weight:700;color:var(--navy);margin:0 0 8px;line-height:1.35}
.cfp-card-desc{font-size:14px;color:var(--text-gray);margin:0;line-height:1.55}
.cfp-card-body{padding:16px 24px 24px;flex:1;display:flex;flex-direction:column}
.cfp-deadline{display:flex;align-items:center;gap:8px;font-size:13px;color:var(--navy);margin-bottom:16px}
.cfp-deadline i{color:var(--text-gray)}
.cfp-topics-label{font-size:12px;font-weight:700;color:var(--text-gray);text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px}
.cfp-topics{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:20px}
.cfp-badge{font-size:11px;padding:3px 10px;border-radius:20px;background:#f1f5f9;color:var(--navy);font-weight:500}
.cfp-submit-btn{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:11px;background:var(--navy);color:#fff;border-radius:8px;font-size:14px;font-weight:600;text-decoration:none;margin-top:auto;transition:background .2s}
.cfp-submit-btn:hover{background:rgba(10,25,60,.85)}

/* ── Journals grid (4 cols) ── */
.cfp-journals-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px}
@media(max-width:960px){.cfp-journals-grid{grid-template-columns:1fr 1fr}}
@media(max-width:500px){.cfp-journals-grid{grid-template-columns:1fr}}

/* ── Journal card ── */
.cfp-journal-card{background:#fff;border:1px solid var(--border);border-radius:14px;padding:24px;transition:box-shadow .2s,transform .2s}
.cfp-journal-card:hover{box-shadow:0 6px 24px rgba(10,25,60,.08);transform:translateY(-2px)}
.cfp-journal-icon{width:40px;height:40px;border-radius:10px;background:rgba(10,25,60,.08);display:flex;align-items:center;justify-content:center;color:var(--navy);font-size:16px;margin-bottom:14px}
.cfp-journal-name{font-size:15px;font-weight:700;color:var(--navy);margin:0 0 8px}
.cfp-journal-discipline{display:inline-block;font-size:12px;padding:3px 10px;border-radius:20px;border:1px solid var(--border);color:var(--text-gray);margin-bottom:16px}
.cfp-journal-meta{display:flex;flex-direction:column;gap:8px}
.cfp-journal-meta-row{display:flex;justify-content:space-between;font-size:13px}
.cfp-journal-meta-row .label{color:var(--text-gray)}
.cfp-journal-meta-row .value{color:var(--navy);font-weight:600}

/* ── CTA section ── */
.cfp-cta-grid{display:grid;grid-template-columns:1fr 1fr;gap:32px;align-items:center;max-width:900px;margin:0 auto}
@media(max-width:700px){.cfp-cta-grid{grid-template-columns:1fr}}
.cfp-cta-left h2{color:#fff;font-size:clamp(1.6rem,3vw,2rem);font-weight:800;margin:0 0 16px}
.cfp-cta-left p{color:rgba(255,255,255,.75);font-size:15px;margin:0 0 24px;line-height:1.65}
.cfp-cta-btns{display:flex;flex-wrap:wrap;gap:12px}
.cfp-btn-primary{display:inline-flex;align-items:center;gap:8px;padding:13px 24px;background:var(--orange);color:#fff;border-radius:8px;font-size:14px;font-weight:700;text-decoration:none;transition:background .2s}
.cfp-btn-primary:hover{background:rgba(232,101,26,.85)}
.cfp-btn-outline{display:inline-flex;align-items:center;gap:8px;padding:13px 24px;border:1.5px solid rgba(255,255,255,.3);color:#fff;border-radius:8px;font-size:14px;font-weight:600;text-decoration:none;transition:background .2s}
.cfp-btn-outline:hover{background:rgba(255,255,255,.10)}
.cfp-stats{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.cfp-stat{background:rgba(255,255,255,.10);border:1px solid rgba(255,255,255,.15);border-radius:12px;padding:20px;text-align:center}
.cfp-stat-num{font-size:28px;font-weight:800;color:var(--orange);margin-bottom:4px}
.cfp-stat-label{font-size:13px;color:rgba(255,255,255,.75)}
.cfp-stat i{font-size:22px;color:var(--orange);margin-bottom:8px;display:block}
</style>

<!-- Hero -->
<section class="cfp-hero">
  <div class="cfp-hero-bg"></div>
  <div class="cfp-hero-overlay"></div>
  <div class="cfp-hero-dots"></div>
  <div class="cfp-hero-content">
    <p class="cfp-hero-eyebrow">Call for Papers</p>
    <h1>Call for Papers</h1>
    <p>Contribute to the advancement of African scholarship. Submit your research to our open calls and special issues.</p>
  </div>
</section>

<!-- Open Calls -->
<section class="cfp-section">
  <div class="container">
    <div class="cfp-section-head">
      <h2>Open Calls</h2>
      <p>Current opportunities to publish your research</p>
    </div>

    <div class="cfp-calls-grid">
      <?php foreach ($open as $call):
        $topics = array_slice(explode(',', $call['topics']), 0, 4);
      ?>
      <div class="cfp-card">
        <div class="cfp-card-header">
          <div class="cfp-card-journal">
            <i class="fas fa-file-alt"></i>
            <?= htmlspecialchars($call['journal']) ?>
          </div>
          <h3 class="cfp-card-title"><?= htmlspecialchars($call['title']) ?></h3>
          <p class="cfp-card-desc"><?= htmlspecialchars($call['description']) ?></p>
        </div>
        <div class="cfp-card-body">
          <div class="cfp-deadline">
            <i class="fas fa-calendar"></i>
            Deadline: <strong><?= htmlspecialchars($call['deadline']) ?></strong>
          </div>
          <div class="cfp-topics-label">Topics of Interest:</div>
          <div class="cfp-topics">
            <?php foreach ($topics as $t): ?>
            <span class="cfp-badge"><?= htmlspecialchars(trim($t)) ?></span>
            <?php endforeach; ?>
          </div>
          <a href="submit_manuscript.php" class="cfp-submit-btn">
            Submit Paper <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Our Journals -->
<section class="cfp-section cfp-section-muted">
  <div class="container">
    <div class="cfp-section-head">
      <h2>Our Journals</h2>
      <p>Peer-reviewed journals across disciplines accepting submissions year-round</p>
    </div>

    <div class="cfp-journals-grid">
      <?php foreach ($journals as $j): ?>
      <div class="cfp-journal-card">
        <div class="cfp-journal-icon"><i class="fas fa-file-alt"></i></div>
        <div class="cfp-journal-name"><?= htmlspecialchars($j['name']) ?></div>
        <span class="cfp-journal-discipline"><?= htmlspecialchars($j['category'] ?? $j['discipline'] ?? '') ?></span>
        <div class="cfp-journal-meta">
          <div class="cfp-journal-meta-row">
            <span class="label">ISSN</span>
            <span class="value"><?= htmlspecialchars($j['issn'] ?? '—') ?></span>
          </div>
          <div class="cfp-journal-meta-row">
            <span class="label">Impact Factor</span>
            <span class="value"><?= htmlspecialchars($j['impact_factor'] ?? '—') ?></span>
          </div>
          <div class="cfp-journal-meta-row">
            <span class="label">Access</span>
            <span class="value"><?= ($j['open_access'] ?? '') === '1' ? 'Open Access' : 'Subscription' ?></span>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CTA: Ready to Publish -->
<section class="cfp-section cfp-section-dark">
  <div class="container" style="padding-top:0;padding-bottom:0">
    <div class="cfp-cta-grid">

      <!-- Left -->
      <div class="cfp-cta-left">
        <h2>Ready to Publish?</h2>
        <p>Submit your manuscript through our streamlined submission process. Our editorial team will guide you through peer review to publication.</p>
        <div class="cfp-cta-btns">
          <a href="submit_manuscript.php" class="cfp-btn-primary">
            Submit Manuscript <i class="fas fa-arrow-right"></i>
          </a>
          <a href="start_journal.php" class="cfp-btn-outline">
            Start a Journal
          </a>
        </div>
      </div>

      <!-- Right: stats -->
      <div class="cfp-stats">
        <div class="cfp-stat">
          <div class="cfp-stat-num">4-6</div>
          <div class="cfp-stat-label">Week Review</div>
        </div>
        <div class="cfp-stat">
          <div class="cfp-stat-num">85%</div>
          <div class="cfp-stat-label">Acceptance Rate</div>
        </div>
        <div class="cfp-stat">
          <i class="fas fa-users"></i>
          <div class="cfp-stat-label">Expert Reviewers</div>
        </div>
        <div class="cfp-stat">
          <i class="fas fa-building"></i>
          <div class="cfp-stat-label">DOI Assignment</div>
        </div>
      </div>

    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
