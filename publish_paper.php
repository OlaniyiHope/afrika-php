<?php
$page_title = 'Publish Your Paper';
include 'header.php';
?>

<style>
/* ── Hero ── */
.pyp-hero{position:relative;overflow:hidden;min-height:400px;display:flex;align-items:center;justify-content:center}
.pyp-hero-bg{position:absolute;inset:0;background:url('asset/about-conference.jpg') center/cover no-repeat}
.pyp-hero-overlay{position:absolute;inset:0;background:rgba(10,25,60,.85)}
.pyp-hero-dots{position:absolute;inset:0;opacity:.10;background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:24px 24px}
.pyp-hero-content{position:relative;z-index:1;text-align:center;padding:80px 20px;max-width:700px;margin:0 auto}
.pyp-hero-eyebrow{font-size:12px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--orange);margin-bottom:16px}
.pyp-hero h1{color:#fff;font-size:clamp(2rem,5vw,3rem);font-weight:800;margin:0 0 20px}
.pyp-hero p{color:rgba(255,255,255,.8);font-size:17px;margin:0}

/* ── Section helpers ── */
.pyp-section{padding:72px 0}
.container{max-width:1200px;margin:0 auto;padding:0 24px}
.pyp-section-head{text-align:center;margin-bottom:48px}
.pyp-section-head h2{font-size:clamp(1.6rem,3vw,2.2rem);font-weight:800;color:var(--navy);margin:0 0 12px}
.pyp-section-head p{color:var(--text-gray);font-size:15px;max-width:560px;margin:0 auto}

/* ── Steps row ── */
.pyp-steps{display:flex;flex-wrap:wrap;justify-content:center;gap:12px;max-width:980px;margin:0 auto 48px}
.pyp-step-wrap{display:flex;align-items:center;gap:10px}
.pyp-step-card{width:168px;background:#fff;border:1px solid var(--border);border-radius:12px;padding:20px 16px;text-align:center;transition:box-shadow .2s,transform .2s}
.pyp-step-card:hover{box-shadow:0 6px 24px rgba(10,25,60,.08);transform:translateY(-2px)}
.pyp-step-icon{width:40px;height:40px;border-radius:50%;background:rgba(232,101,26,.10);display:flex;align-items:center;justify-content:center;margin:0 auto 10px;color:var(--orange);font-size:16px}
.pyp-step-card h3{font-size:13px;font-weight:700;color:var(--navy);margin:0 0 4px}
.pyp-step-card p{font-size:12px;color:var(--text-gray);margin:0}
.pyp-step-arrow{color:var(--text-gray);font-size:14px;opacity:.5}
@media(max-width:700px){.pyp-step-arrow{display:none}}

/* ── Start card ── */
.pyp-start-card{max-width:600px;margin:0 auto;border:1px solid var(--border);border-radius:16px;padding:40px;text-align:center;background:#fff}
.pyp-start-card h3{font-size:22px;font-weight:700;color:var(--navy);margin:0 0 8px}
.pyp-start-card .sub{color:var(--text-gray);font-size:14px;margin:0 0 28px}
.pyp-highlights{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:28px}
.pyp-highlight{padding:16px;background:#f8f9ff;border-radius:10px;text-align:center}
.pyp-highlight i{font-size:22px;color:var(--orange);margin-bottom:8px;display:block}
.pyp-highlight p{font-size:13px;font-weight:600;color:var(--navy);margin:0}
.pyp-begin-btn{display:inline-flex;align-items:center;gap:8px;padding:14px 32px;background:var(--orange);color:#fff;border-radius:8px;font-size:16px;font-weight:700;text-decoration:none;transition:background .2s}
.pyp-begin-btn:hover{background:rgba(232,101,26,.85)}
.pyp-guidelines-note{margin-top:16px;font-size:13px;color:var(--text-gray)}
.pyp-guidelines-note a{color:var(--orange);text-decoration:none}
.pyp-guidelines-note a:hover{text-decoration:underline}
</style>

<!-- Hero -->
<section class="pyp-hero">
  <div class="pyp-hero-bg"></div>
  <div class="pyp-hero-overlay"></div>
  <div class="pyp-hero-dots"></div>
  <div class="pyp-hero-content">
    <p class="pyp-hero-eyebrow">Submit Your Manuscript</p>
    <h1>Submit Your Manuscript</h1>
    <p>Share your research with the world through Afrika Scholar's peer-reviewed journals.</p>
  </div>
</section>

<!-- Submission Process -->
<section class="pyp-section">
  <div class="container">
    <div class="pyp-section-head">
      <h2>Submission Process</h2>
      <p>Our streamlined 5-step process from submission to publication</p>
    </div>

    <?php
    $process_steps = [
      ['fas fa-book-open',   'Select Journal',    'Choose the right journal for your research'],
      ['fas fa-file-alt',    'Prepare Manuscript','Format according to guidelines'],
      ['fas fa-upload',      'Submit',            'Upload your manuscript and files'],
      ['fas fa-user-friends','Peer Review',       'Expert evaluation of your work'],
      ['fas fa-check-circle','Publication',       'Get published and indexed'],
    ];
    ?>

    <div class="pyp-steps">
      <?php foreach ($process_steps as $i => $s): ?>
      <div class="pyp-step-wrap">
        <div class="pyp-step-card">
          <div class="pyp-step-icon"><i class="<?= $s[0] ?>"></i></div>
          <h3><?= $s[1] ?></h3>
          <p><?= $s[2] ?></p>
        </div>
        <?php if ($i < count($process_steps) - 1): ?>
        <span class="pyp-step-arrow"><i class="fas fa-arrow-right"></i></span>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Start card -->
    <div class="pyp-start-card">
      <h3>Start Your Submission</h3>
      <p class="sub">The full manuscript submission form will guide you through each step</p>

      <div class="pyp-highlights">
        <div class="pyp-highlight">
          <i class="fas fa-clock"></i>
          <p>4-6 Week Review</p>
        </div>
        <div class="pyp-highlight">
          <i class="fas fa-user-friends"></i>
          <p>Double-Blind Review</p>
        </div>
        <div class="pyp-highlight">
          <i class="fas fa-shield-alt"></i>
          <p>Open Access</p>
        </div>
      </div>

      <a href="submit_manuscript.php" class="pyp-begin-btn">
        Begin Submission <i class="fas fa-arrow-right"></i>
      </a>

      <p class="pyp-guidelines-note">
        Need help? <a href="peer_review_policy.php">View our submission guidelines</a>
      </p>
    </div>

  </div>
</section>

<?php include 'footer.php'; ?>
