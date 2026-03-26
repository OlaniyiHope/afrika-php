<?php
$page_title = 'Degree Programs';
include 'header.php';
?>

<style>
/* ── Breadcrumb ── */
.dp-breadcrumb{background:rgba(0,0,0,.04);border-bottom:1px solid var(--border)}
.dp-breadcrumb .inner{padding:10px 0;display:flex;align-items:center;gap:8px;font-size:14px;color:var(--text-gray)}
.dp-breadcrumb a{color:var(--text-gray);text-decoration:none}.dp-breadcrumb a:hover{color:var(--navy)}
.dp-breadcrumb .sep{font-size:12px;opacity:.5}
.dp-breadcrumb .current{color:var(--navy)}

/* ── Hero ── */
.dp-hero{position:relative;overflow:hidden;min-height:400px;display:flex;align-items:center;justify-content:center}
.dp-hero-bg{position:absolute;inset:0;background:url('asset/about-conference.jpg') center/cover no-repeat}
.dp-hero-overlay{position:absolute;inset:0;background:rgba(10,25,60,.85)}
.dp-hero-dots{position:absolute;inset:0;opacity:.10;background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:24px 24px}
.dp-hero-content{position:relative;z-index:1;text-align:center;padding:80px 20px;max-width:700px;margin:0 auto}
.dp-hero-eyebrow{font-size:12px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--orange);margin-bottom:16px}
.dp-hero h1{color:#fff;font-size:clamp(2rem,5vw,3rem);font-weight:800;margin:0 0 20px}
.dp-hero p{color:rgba(255,255,255,.8);font-size:17px;margin:0}

/* ── Section helpers ── */
.dp-section{padding:72px 0}
.dp-section-muted{background:#f8f9ff;border-top:1px solid var(--border)}
.dp-section-dark{background:var(--navy);position:relative;overflow:hidden}
.container{max-width:1200px;margin:0 auto;padding:0 24px}
.dp-section-head{text-align:center;margin-bottom:48px}
.dp-section-head h2{font-size:clamp(1.6rem,3vw,2.2rem);font-weight:800;color:var(--navy);margin:0 0 12px}
.dp-section-head p{color:var(--text-gray);font-size:15px;max-width:560px;margin:0 auto}

/* ── Pathway cards grid ── */
.dp-cards{display:grid;grid-template-columns:repeat(3,1fr);gap:28px}
@media(max-width:900px){.dp-cards{grid-template-columns:1fr}}

/* ── Individual card ── */
.dp-card{background:#fff;border:1px solid var(--border);border-radius:14px;padding:28px;display:flex;flex-direction:column;transition:box-shadow .2s,transform .2s}
.dp-card:hover{box-shadow:0 8px 32px rgba(10,25,60,.10);transform:translateY(-3px)}
.dp-card-header-row{display:flex;align-items:center;gap:12px;margin-bottom:20px}
.dp-card-icon{width:44px;height:44px;border-radius:10px;background:rgba(232,101,26,.10);display:flex;align-items:center;justify-content:center;color:var(--orange);font-size:18px;flex-shrink:0}
.dp-card-num{width:26px;height:26px;border-radius:50%;background:var(--navy);color:#fff;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0}
.dp-card h3{font-size:16px;font-weight:700;color:var(--navy);margin:0 0 8px}
.dp-card .dp-card-desc{color:var(--text-gray);font-size:14px;margin:0 0 20px;line-height:1.55}
.dp-card-meta{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px}
.dp-meta-item{display:flex;align-items:flex-start;gap:8px}
.dp-meta-item i{color:var(--text-gray);margin-top:2px;font-size:13px}
.dp-meta-label{font-size:11px;color:var(--text-gray);margin-bottom:2px}
.dp-meta-value{font-size:13px;font-weight:600;color:var(--navy)}
.dp-features-label{font-size:13px;font-weight:700;color:var(--navy);margin-bottom:10px}
.dp-features{list-style:none;padding:0;margin:0 0 20px;display:flex;flex-direction:column;gap:8px}
.dp-features li{display:flex;align-items:flex-start;gap:8px;font-size:13px;color:var(--text-dark,#2d3748);line-height:1.4}
.dp-features li i{color:var(--orange);margin-top:1px;font-size:13px;flex-shrink:0}
.dp-card .btn-apply{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:11px;background:var(--navy);color:#fff;border-radius:8px;font-size:14px;font-weight:600;text-decoration:none;transition:background .2s;margin-top:auto}
.dp-card .btn-apply:hover{background:rgba(10,25,60,.85)}

/* ── Support section ── */
.dp-support-grid{display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center}
@media(max-width:800px){.dp-support-grid{grid-template-columns:1fr}}
.dp-support-text h2{font-size:clamp(1.5rem,3vw,2rem);font-weight:800;color:var(--navy);margin:0 0 16px}
.dp-support-text p{color:var(--text-gray);font-size:15px;margin:0 0 28px;line-height:1.65}
.dp-support-list{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:12px}
.dp-support-list li{display:flex;align-items:center;gap:12px;font-size:15px;color:var(--navy)}
.dp-support-list li i{color:var(--orange);font-size:15px;flex-shrink:0}
.dp-stats{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.dp-stat-card{background:#fff;border:1px solid var(--border);border-radius:12px;padding:24px;text-align:center}
.dp-stat-card i{font-size:28px;color:var(--orange);margin-bottom:10px;display:block}
.dp-stat-num{font-size:28px;font-weight:800;color:var(--navy);margin-bottom:4px}
.dp-stat-label{font-size:13px;color:var(--text-gray)}

/* ── Alert / Notice ── */
.dp-notice-wrap{max-width:720px;margin:0 auto}
.dp-alert{border:1px solid var(--border);border-radius:12px;padding:24px 28px;background:#fff;display:flex;gap:16px}
.dp-alert-icon{color:var(--navy);font-size:18px;flex-shrink:0;margin-top:2px}
.dp-alert-title{font-size:15px;font-weight:700;color:var(--navy);margin:0 0 8px}
.dp-alert-body{font-size:14px;color:var(--text-gray);line-height:1.6}
.dp-alert-body ul{margin:8px 0 0 0;padding-left:20px;display:flex;flex-direction:column;gap:4px}
.dp-alert-note{margin-top:12px;font-size:14px;color:var(--text-gray)}

/* ── CTA dark ── */
.dp-cta{text-align:center;padding:80px 24px;position:relative;z-index:1}
.dp-cta i.dp-cta-icon{font-size:40px;color:var(--orange);margin-bottom:24px;display:block}
.dp-cta h2{color:#fff;font-size:clamp(1.6rem,3vw,2.2rem);font-weight:800;margin:0 0 16px}
.dp-cta p{color:rgba(255,255,255,.75);font-size:16px;max-width:560px;margin:0 auto 32px}
.dp-cta .btn-cta{display:inline-flex;align-items:center;gap:8px;padding:14px 32px;background:var(--orange);color:#fff;border-radius:8px;font-size:15px;font-weight:700;text-decoration:none;transition:background .2s}
.dp-cta .btn-cta:hover{background:rgba(232,101,26,.85)}
</style>

<!-- Breadcrumb -->
<div class="dp-breadcrumb">
  <div class="container">
    <div class="inner">
      <a href="advisory.php">Advisory</a>
      <span class="sep"><i class="fas fa-chevron-right"></i></span>
      <span class="current">Degree Programs</span>
    </div>
  </div>
</div>

<!-- Hero -->
<section class="dp-hero">
  <div class="dp-hero-bg"></div>
  <div class="dp-hero-overlay"></div>
  <div class="dp-hero-dots"></div>
  <div class="dp-hero-content">
    <p class="dp-hero-eyebrow">Degree Programs</p>
    <h1>Degree Programs</h1>
    <p>Navigate the transcript request process at Nigerian universities with our comprehensive guide and institution-specific information.</p>
  </div>
</section>

<!-- Academic Pathways -->
<section class="dp-section">
  <div class="container">
    <div class="dp-section-head">
      <h2>Academic Pathways</h2>
      <p>Explore degree options that fit your career stage and goals</p>
    </div>

    <?php
    $pathways = [
      [
        'title'    => 'Part-Time Undergraduate Programs',
        'desc'     => 'Earn your bachelor\'s degree while maintaining your career. Flexible schedules designed for working professionals.',
        'duration' => '4-6 years',
        'format'   => 'Evening &amp; Weekend Classes',
        'link'     => 'advisory-request.php',
        'features' => [
          'Flexible class schedules',
          'Same qualification as full-time programs',
          'Career-compatible timing',
          'Practical project-based learning',
        ],
      ],
      [
        'title'    => "Master's Programs",
        'desc'     => 'Advance your expertise with postgraduate education. Specialized programs across disciplines.',
        'duration' => '18-24 months',
        'format'   => 'Full-time, Part-time, or Hybrid',
        'link'     => 'advisory-request.php',
        'features' => [
          'Research and coursework options',
          'Industry-relevant specializations',
          'Thesis or project-based completion',
          'Networking with professionals',
        ],
      ],
      [
        'title'    => 'Doctoral Programs (PhD)',
        'desc'     => 'Contribute original research to your field. Join the academic community as a researcher and educator.',
        'duration' => '3-5 years',
        'format'   => 'Research-focused',
        'link'     => 'advisory-request.php',
        'features' => [
          'Original research contribution',
          'Publication opportunities',
          'Academic mentorship',
          'Conference participation',
        ],
      ],
    ];
    ?>

    <div class="dp-cards">
      <?php foreach ($pathways as $i => $p): ?>
      <div class="dp-card">
        <!-- Icon + number row -->
        <div class="dp-card-header-row">
          <div class="dp-card-icon"><i class="fas fa-graduation-cap"></i></div>
          <div class="dp-card-num"><?= $i + 1 ?></div>
        </div>

        <h3><?= $p['title'] ?></h3>
        <p class="dp-card-desc"><?= $p['desc'] ?></p>

        <!-- Duration / Format -->
        <div class="dp-card-meta">
          <div class="dp-meta-item">
            <i class="fas fa-clock"></i>
            <div>
              <div class="dp-meta-label">Duration</div>
              <div class="dp-meta-value"><?= $p['duration'] ?></div>
            </div>
          </div>
          <div class="dp-meta-item">
            <i class="fas fa-building"></i>
            <div>
              <div class="dp-meta-label">Format</div>
              <div class="dp-meta-value"><?= $p['format'] ?></div>
            </div>
          </div>
        </div>

        <!-- Features -->
        <div class="dp-features-label">Key Features:</div>
        <ul class="dp-features">
          <?php foreach ($p['features'] as $f): ?>
          <li><i class="fas fa-check-circle"></i> <?= $f ?></li>
          <?php endforeach; ?>
        </ul>

        <a href="<?= $p['link'] ?>" class="btn-apply">
          Apply Now <i class="fas fa-arrow-right"></i>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Comprehensive Pathway Support -->
<section class="dp-section dp-section-muted">
  <div class="container">
    <div class="dp-support-grid">

      <!-- Left: text + checklist -->
      <div class="dp-support-text">
        <h2>Comprehensive Pathway Support</h2>
        <p>Afrika Scholar provides guidance throughout your academic journey—from program selection to application completion. We help you make informed decisions about your educational future.</p>
        <ul class="dp-support-list">
          <?php
          $support = [
            'Program selection guidance',
            'Admission requirements clarification',
            'Application process support',
            'Institution comparison insights',
            'Scholarship and funding information',
            'Timeline and planning assistance',
          ];
          foreach ($support as $s): ?>
          <li><i class="fas fa-check-circle"></i> <?= $s ?></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Right: stat cards -->
      <div class="dp-stats">
        <div class="dp-stat-card">
          <i class="fas fa-book-open"></i>
          <div class="dp-stat-num">50+</div>
          <div class="dp-stat-label">Programs Covered</div>
        </div>
        <div class="dp-stat-card">
          <i class="fas fa-building"></i>
          <div class="dp-stat-num">20+</div>
          <div class="dp-stat-label">Partner Universities</div>
        </div>
        <div class="dp-stat-card">
          <i class="fas fa-users"></i>
          <div class="dp-stat-num">500+</div>
          <div class="dp-stat-label">Students Guided</div>
        </div>
        <div class="dp-stat-card">
          <i class="fas fa-briefcase"></i>
          <div class="dp-stat-num">85%</div>
          <div class="dp-stat-label">Success Rate</div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Our Position Notice -->
<section class="dp-section">
  <div class="container">
    <div class="dp-notice-wrap">
      <div class="dp-alert">
        <div class="dp-alert-icon"><i class="fas fa-building"></i></div>
        <div>
          <p class="dp-alert-title">Our Position</p>
          <div class="dp-alert-body">
            Afrika Scholar provides advisory services only. We do not:
            <ul>
              <li>Guarantee admission to any program</li>
              <li>Act as admission agents for universities</li>
              <li>Process applications on your behalf</li>
              <li>Charge for university-related fees</li>
            </ul>
          </div>
          <p class="dp-alert-note">All formal applications must be submitted directly to the institution.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="dp-section-dark">
  <div class="dp-cta">
    <i class="fas fa-graduation-cap dp-cta-icon"></i>
    <h2>Start Your Journey</h2>
    <p>Ready to explore degree options? Request a consultation to discuss your academic goals and find the right pathway.</p>
    <a href="advisory-request.php" class="btn-cta">
      Request Degree Advisory <i class="fas fa-arrow-right"></i>
    </a>
  </div>
</section>

<?php include 'footer.php'; ?>
