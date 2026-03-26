<?php
$page_title = 'Publeesh AI – Research Intelligence';
include 'header.php';
?>

<style>
/* ── Hero ── */
.pb-hero{position:relative;overflow:hidden;min-height:600px;display:flex;align-items:center}
.pb-hero-bg{position:absolute;inset:0;background:url('asset/hero-scholars.jpg') center/cover no-repeat}
.pb-hero-overlay{position:absolute;inset:0;background:rgba(10,25,60,.85)}
.pb-hero-overlay2{position:absolute;inset:0;background:linear-gradient(to right,rgba(10,25,60,.95) 0%,rgba(10,25,60,.80) 55%,transparent 100%)}
.pb-hero-dots{position:absolute;inset:0;opacity:.10;background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:24px 24px}
.pb-hero-content{position:relative;z-index:1;padding:96px 24px;max-width:1200px;margin:0 auto;width:100%}
.pb-hero-eyebrow{font-size:11px;font-weight:700;letter-spacing:.15em;text-transform:uppercase;color:var(--orange);margin-bottom:16px}
.pb-hero h1{color:#fff;font-size:clamp(1.8rem,4vw,2.8rem);font-weight:800;line-height:1.2;margin:0 0 20px}
.pb-hero h1 span{color:var(--orange)}
.pb-hero .tagline{font-size:16px;font-weight:600;color:#fff;margin:0 0 10px;max-width:520px}
.pb-hero .desc{font-size:15px;color:rgba(255,255,255,.75);margin:0 0 36px;max-width:520px;line-height:1.65}
.pb-hero-btn{display:inline-flex;align-items:center;gap:8px;padding:14px 28px;background:var(--orange);color:#fff;border-radius:8px;font-size:15px;font-weight:700;text-decoration:none;transition:background .2s}
.pb-hero-btn:hover{background:rgba(232,101,26,.85)}

/* ── Hero two-col grid ── */
.pb-hero-grid{display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center;width:100%}
@media(max-width:900px){.pb-hero-grid{grid-template-columns:1fr}.pb-hero-animated{display:none!important}}

/* ── Animated visual (reused from homepage) ── */
.pb-hero-animated{display:flex;justify-content:center;align-items:center}
.animated-visual{width:340px;height:340px;position:relative}
.av-ring{position:absolute;border-radius:50%;border:1px solid rgba(255,255,255,.15);animation:pulse-ring 3s ease-in-out infinite}
.av-ring-1{width:100%;height:100%;top:0;left:0;animation-delay:0s}
.av-ring-2{width:75%;height:75%;top:12.5%;left:12.5%;animation-delay:.5s}
.av-ring-3{width:50%;height:50%;top:25%;left:25%;animation-delay:1s}
@keyframes pulse-ring{0%,100%{opacity:.3;transform:scale(1)}50%{opacity:.7;transform:scale(1.03)}}
.av-center{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:80px;height:80px;background:#e85d1a;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 0 40px rgba(232,93,26,.5)}
.av-center i{font-size:32px;color:#fff}
.av-node{position:absolute;width:52px;height:52px;border-radius:50%;background:rgba(255,255,255,.1);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,.9);font-size:18px;animation:float-node 4s ease-in-out infinite}
.av-node-1{top:10%;left:50%;transform:translateX(-50%);animation-delay:0s}
.av-node-2{top:50%;right:5%;transform:translateY(-50%);animation-delay:.7s}
.av-node-3{bottom:10%;left:50%;transform:translateX(-50%);animation-delay:1.4s}
.av-node-4{top:50%;left:5%;transform:translateY(-50%);animation-delay:2.1s}
@keyframes float-node{0%,100%{transform:translateX(-50%) translateY(0)}50%{transform:translateX(-50%) translateY(-8px)}}
.av-node-2,.av-node-4{animation-name:float-node-h}
@keyframes float-node-h{0%,100%{transform:translateY(-50%) translateX(0)}50%{transform:translateY(-50%) translateX(-6px)}}

/* ── Sections ── */
.pb-section{padding:72px 0}
.pb-section-muted{background:rgba(0,0,0,.03);border-top:1px solid var(--border)}
.pb-section-dark{background:var(--navy);position:relative;overflow:hidden}
.pb-section-dark-dots{position:absolute;inset:0;opacity:.10}
.container{max-width:1200px;margin:0 auto;padding:0 24px}
.pb-section-head{text-align:center;margin-bottom:56px}
.pb-section-head .eyebrow{font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--orange);margin-bottom:10px}
.pb-section-head h2{font-size:clamp(1.6rem,3vw,2.2rem);font-weight:800;color:var(--navy);margin:0 0 14px}
.pb-section-head p{color:var(--text-gray);font-size:16px;max-width:640px;margin:0 auto}
.pb-section-dark .pb-section-head h2,
.pb-section-dark .pb-section-head p{color:rgba(255,255,255,.85)}

/* ── Intro pillars (5 cols) ── */
.pb-pillars{display:grid;grid-template-columns:repeat(5,1fr);gap:14px;max-width:980px;margin:0 auto 64px}
@media(max-width:900px){.pb-pillars{grid-template-columns:repeat(3,1fr)}}
@media(max-width:540px){.pb-pillars{grid-template-columns:1fr 1fr}}
.pb-pillar{display:flex;flex-direction:column;align-items:center;gap:12px;padding:20px 14px;border-radius:14px;background:#fff;border:1px solid var(--border);text-align:center;transition:border-color .2s}
.pb-pillar:hover{border-color:rgba(232,101,26,.4)}
.pb-pillar-icon{width:44px;height:44px;border-radius:50%;background:rgba(232,101,26,.10);display:flex;align-items:center;justify-content:center;color:var(--orange);font-size:17px}
.pb-pillar p{font-size:13px;font-weight:600;color:var(--navy);margin:0;line-height:1.35}

/* ── Feature cards (3 cols) ── */
.pb-cards-3{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;max-width:1100px;margin:0 auto}
@media(max-width:900px){.pb-cards-3{grid-template-columns:1fr}}
.pb-feat-card{background:#fff;border:1px solid var(--border);border-radius:14px;padding:28px;display:flex;flex-direction:column;transition:border-color .2s}
.pb-feat-card:hover{border-color:rgba(232,101,26,.35)}
.pb-feat-card-tag{display:flex;align-items:center;gap:10px;margin-bottom:16px}
.pb-feat-card-tag .icon{width:36px;height:36px;border-radius:8px;background:rgba(232,101,26,.10);display:flex;align-items:center;justify-content:center;color:var(--orange);font-size:15px}
.pb-feat-card-tag .label{font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--text-gray)}
.pb-feat-card h3{font-size:17px;font-weight:700;color:var(--navy);margin:0 0 8px}
.pb-feat-card .sub{font-size:13px;color:var(--text-gray);margin:0 0 18px;line-height:1.5}
.pb-feat-list{list-style:none;padding:0;margin:0 0 16px;display:flex;flex-direction:column;gap:8px}
.pb-feat-list li{display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-gray)}
.pb-feat-list li::before{content:'';width:7px;height:7px;border-radius:50%;background:var(--orange);flex-shrink:0}
.pb-badge-row{display:flex;flex-wrap:wrap;gap:8px}
.pb-badge{font-size:11px;padding:4px 12px;border-radius:20px;background:rgba(232,101,26,.10);color:var(--orange);border:1px solid rgba(232,101,26,.2);font-weight:600}
.pb-badge-gray{font-size:11px;padding:4px 12px;border-radius:20px;background:#f1f5f9;color:var(--navy);border:1px solid var(--border);font-weight:500}

/* ── Start publishing btn ── */
.pb-center-btn{display:flex;justify-content:center;margin:28px 0}
.pb-btn-accent-outline{display:inline-flex;align-items:center;gap:8px;padding:13px 28px;border:1.5px solid var(--orange);color:var(--orange);border-radius:8px;font-size:15px;font-weight:700;text-decoration:none;background:transparent;transition:all .2s}
.pb-btn-accent-outline:hover{background:var(--orange);color:#fff}

/* ── Two-col cards ── */
.pb-cards-2{display:grid;grid-template-columns:1fr 1fr;gap:24px;max-width:980px;margin:0 auto}
@media(max-width:720px){.pb-cards-2{grid-template-columns:1fr}}
.pb-card-white{background:#fff;border:1px solid var(--border);border-radius:14px;padding:28px}
.pb-card-white h3{font-size:18px;font-weight:700;color:var(--navy);margin:0 0 6px}
.pb-card-white .sub{font-size:13px;color:var(--text-gray);margin:0 0 20px}
.pb-who-list{list-style:none;padding:0;margin:0 0 20px;display:flex;flex-direction:column;gap:12px}
.pb-who-list li{display:flex;align-items:flex-start;gap:10px;font-size:14px;color:var(--text-gray)}
.pb-who-list li::before{content:'';width:7px;height:7px;border-radius:50%;background:var(--orange);flex-shrink:0;margin-top:6px}
.pb-who-list li strong{color:var(--navy)}

/* ── Integrity banner ── */
.pb-integrity{max-width:980px;margin:0 auto;border-radius:16px;border:1px solid var(--border);background:rgba(0,0,0,.02);padding:40px}
.pb-integrity-grid{display:grid;grid-template-columns:auto 1fr;gap:32px;align-items:start}
@media(max-width:640px){.pb-integrity-grid{grid-template-columns:1fr}}
.pb-integrity-icon{width:56px;height:56px;border-radius:14px;background:rgba(232,101,26,.10);display:flex;align-items:center;justify-content:center;color:var(--orange);font-size:24px}
.pb-integrity h3{font-size:18px;font-weight:700;color:var(--navy);margin:0 0 10px}
.pb-integrity p{font-size:14px;color:var(--text-gray);margin:0 0 20px;line-height:1.65}
.pb-integrity-cols{display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:20px}
@media(max-width:560px){.pb-integrity-cols{grid-template-columns:1fr}}
.pb-integrity-label{font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--text-gray);margin-bottom:12px}
.pb-no-list{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px}
.pb-no-list li{display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-gray)}
.pb-no-icon{width:20px;height:20px;border-radius:50%;border:1px solid rgba(0,0,0,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:10px}
.pb-integrity-btn{display:inline-flex;align-items:center;gap:8px;padding:9px 18px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;font-weight:600;color:var(--navy);text-decoration:none;transition:border-color .2s}
.pb-integrity-btn:hover{border-color:var(--orange);color:var(--orange)}

/* ── How it works steps ── */
.pb-how-steps{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;max-width:900px;margin:0 auto 64px;position:relative}
@media(max-width:800px){.pb-how-steps{grid-template-columns:1fr}}
.pb-how-step{display:flex;flex-direction:column;align-items:center;text-align:center;gap:12px;position:relative}
.pb-how-num{width:48px;height:48px;border-radius:50%;background:var(--orange);color:#fff;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:700;flex-shrink:0;z-index:1}
.pb-how-step p{font-size:13px;color:rgba(255,255,255,.75);line-height:1.45;margin:0}
.pb-how-connector{position:absolute;top:24px;left:calc(50% + 24px);width:calc(100% - 48px);height:1px;background:rgba(255,255,255,.15)}
@media(max-width:800px){.pb-how-connector{display:none}}

/* ── Dark feature cards ── */
.pb-dark-card{background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);border-radius:14px;padding:28px;backdrop-filter:blur(4px)}
.pb-dark-card h3{font-size:18px;font-weight:700;color:#fff;margin:0 0 6px}
.pb-dark-card .sub{font-size:13px;color:rgba(255,255,255,.55);margin:0 0 20px}
.pb-dark-list{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:10px}
.pb-dark-list li{display:flex;align-items:center;gap:8px;font-size:13px;color:rgba(255,255,255,.75)}
.pb-dark-list li::before{content:'';width:7px;height:7px;border-radius:50%;background:var(--orange);flex-shrink:0}
.pb-dark-card-label{font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.45);margin-bottom:12px}
.pb-dark-icon-row{display:flex;align-items:center;gap:12px;margin-bottom:6px}
.pb-dark-icon{width:40px;height:40px;border-radius:10px;background:rgba(232,101,26,.2);display:flex;align-items:center;justify-content:center;color:var(--orange);font-size:16px}
.pb-dark-btn{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:12px;background:var(--orange);color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:700;text-decoration:none;transition:background .2s;margin-top:auto}
.pb-dark-btn:hover{background:rgba(232,101,26,.85)}
.pb-dark-btn-outline{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:12px;background:transparent;color:#fff;border:1.5px solid rgba(255,255,255,.3);border-radius:8px;font-size:14px;font-weight:600;text-decoration:none;transition:background .2s}
.pb-dark-btn-outline:hover{background:rgba(255,255,255,.08)}

/* ── Why matters ── */
.pb-why-grid{display:grid;grid-template-columns:1fr 1fr;gap:24px;max-width:980px;margin:0 auto}
@media(max-width:640px){.pb-why-grid{grid-template-columns:1fr}}
.pb-why-challenges{background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);border-radius:14px;padding:28px}
.pb-why-empower{background:rgba(232,101,26,.10);border:1px solid rgba(232,101,26,.25);border-radius:14px;padding:28px}
.pb-no-item{display:flex;align-items:flex-start;gap:10px;font-size:13px;color:rgba(255,255,255,.70);margin-bottom:12px}
.pb-no-circle{width:20px;height:20px;border-radius:50%;border:1px solid rgba(255,255,255,.3);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:9px;color:rgba(255,255,255,.5)}
.pb-empower-item{display:flex;align-items:flex-start;gap:10px;font-size:13px;color:rgba(255,255,255,.80);margin-bottom:12px}
.pb-empower-item::before{content:'';width:7px;height:7px;border-radius:50%;background:var(--orange);flex-shrink:0;margin-top:5px}
.pb-empower-item strong{color:#fff}
.pb-empower-item span{color:rgba(255,255,255,.55)}

/* ── Future roadmap (3 cols) ── */
.pb-future-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;max-width:980px;margin:0 auto 48px}
@media(max-width:900px){.pb-future-grid{grid-template-columns:1fr 1fr}}
@media(max-width:520px){.pb-future-grid{grid-template-columns:1fr}}
.pb-future-card{background:#fff;border:1px solid var(--border);border-radius:14px;padding:24px;transition:box-shadow .2s,transform .2s}
.pb-future-card:hover{box-shadow:0 6px 24px rgba(10,25,60,.08);transform:translateY(-2px)}
.pb-future-icon{width:40px;height:40px;border-radius:10px;background:rgba(232,101,26,.10);display:flex;align-items:center;justify-content:center;color:var(--orange);font-size:16px;margin-bottom:14px;transition:background .2s}
.pb-future-card:hover .pb-future-icon{background:var(--orange);color:#fff}
.pb-future-card h4{font-size:14px;font-weight:700;color:var(--navy);margin:0 0 8px}
.pb-future-card p{font-size:13px;color:var(--text-gray);margin:0;line-height:1.55}

/* ── Tag pills ── */
.pb-tags{display:flex;flex-wrap:wrap;justify-content:center;gap:12px;margin-bottom:56px}
.pb-tag{padding:8px 20px;border-radius:30px;border:1px solid rgba(232,101,26,.3);color:var(--orange);font-size:13px;font-weight:600;background:rgba(232,101,26,.05)}

/* ── CTA card ── */
.pb-cta-card{max-width:900px;margin:0 auto;background:linear-gradient(135deg,var(--navy) 0%,rgba(10,25,60,.92) 100%);border-radius:20px;overflow:hidden;position:relative}
.pb-cta-card-dots{position:absolute;inset:0;opacity:.10;background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:20px 20px}
.pb-cta-card-body{position:relative;z-index:1;padding:56px 48px;text-align:center}
@media(max-width:600px){.pb-cta-card-body{padding:40px 24px}}
.pb-cta-card h3{color:#fff;font-size:clamp(1.4rem,3vw,2rem);font-weight:800;margin:0 0 14px}
.pb-cta-card p{color:rgba(255,255,255,.70);font-size:15px;max-width:520px;margin:0 auto 32px}
.pb-cta-btns{display:flex;flex-wrap:wrap;gap:12px;justify-content:center}
.pb-cta-btn-orange{display:inline-flex;align-items:center;gap:8px;padding:13px 24px;background:var(--orange);color:#fff;border-radius:8px;font-size:14px;font-weight:700;text-decoration:none;transition:background .2s}
.pb-cta-btn-orange:hover{background:rgba(232,101,26,.85)}
.pb-cta-btn-outline{display:inline-flex;align-items:center;gap:8px;padding:13px 24px;border:1.5px solid rgba(255,255,255,.3);color:#fff;border-radius:8px;font-size:14px;font-weight:600;text-decoration:none;background:transparent;transition:background .2s}
.pb-cta-btn-outline:hover{background:rgba(255,255,255,.08)}
</style>

<!-- Hero -->
<section class="pb-hero">
  <div class="pb-hero-bg"></div>
  <div class="pb-hero-overlay"></div>
  <div class="pb-hero-overlay2"></div>
  <div class="pb-hero-dots"></div>
  <div class="pb-hero-content">
    <div class="pb-hero-grid">

      <!-- Left: text -->
      <div style="max-width:580px">
        <p class="pb-hero-eyebrow">Publeesh Ai</p>
        <h1>AI-Powered Research Intelligence <span>by Afrika Scholar</span></h1>
        <p class="tagline">Enhancing Research. Strengthening Scholarship. Preserving Integrity.</p>
        <p class="desc">Publeesh Ai is Afrika Scholar's AI-powered research intelligence platform, designed to support scholars, students, researchers, and institutions with structured research workflows, global data access, and responsible AI-assisted drafting tools.</p>
        <a href="dashboard_publeesh.php" class="pb-hero-btn">
          Start Publeeshing <i class="fas fa-arrow-right"></i>
        </a>
      </div>

      <!-- Right: animated visual -->
      <div class="pb-hero-animated">
        <div class="animated-visual">
          <div class="av-ring av-ring-1"></div>
          <div class="av-ring av-ring-2"></div>
          <div class="av-ring av-ring-3"></div>
          <div class="av-center"><i class="fas fa-book-open"></i></div>
          <div class="av-node av-node-1"><i class="fas fa-graduation-cap"></i></div>
          <div class="av-node av-node-2"><i class="fas fa-globe-africa"></i></div>
          <div class="av-node av-node-3"><i class="fas fa-file-alt"></i></div>
          <div class="av-node av-node-4"><i class="fas fa-users"></i></div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- What Is Publeesh Ai -->
<section class="pb-section pb-section-muted">
  <div class="container">
    <div class="pb-section-head">
      <h2>What is Publeesh Ai?</h2>
      <p>A research enablement tool integrated within Afrika Scholar's academic infrastructure — built for serious academic use, not automated academic substitution.</p>
    </div>

    <!-- 5 intro pillars -->
    <div class="pb-pillars">
      <?php foreach ([
        ['fas fa-pen-nib',   'Structured Drafting Assistance'],
        ['fas fa-book-open', 'Literature Review Organization'],
        ['fas fa-quote-right','Citation Guidance & Formatting'],
        ['fas fa-database',  'Global Institutional Dataset Access'],
        ['fas fa-chart-bar', 'Comparative Research Intelligence'],
      ] as $p): ?>
      <div class="pb-pillar">
        <div class="pb-pillar-icon"><i class="<?= $p[0] ?>"></i></div>
        <p><?= $p[1] ?></p>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- 3 feature blocks -->
    <div class="pb-cards-3">

      <!-- Block 1: Structured Drafting -->
      <div class="pb-feat-card">
        <div class="pb-feat-card-tag">
          <div class="icon"><i class="fas fa-pen-nib"></i></div>
          <span class="label">Research Drafting</span>
        </div>
        <h3>Structured Research Drafting Support</h3>
        <p class="sub">Help scholars think clearly, structure better, and refine faster.</p>
        <ul class="pb-feat-list">
          <?php foreach (['Research outlines','Thesis & dissertation frameworks','Literature review structures','Methodology templates','Research question refinements','Hypothesis framing assistance'] as $item): ?>
          <li><?= $item ?></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Block 2: Literature & Referencing -->
      <div class="pb-feat-card">
        <div class="pb-feat-card-tag">
          <div class="icon"><i class="fas fa-book-open"></i></div>
          <span class="label">Literature & Referencing</span>
        </div>
        <h3>Literature & Referencing Enhancement</h3>
        <p class="sub">Strengthen your academic credibility with properly structured referencing.</p>
        <ul class="pb-feat-list" style="margin-bottom:16px">
          <?php foreach (['Published research references','Citation-ready formatting support','Reference structuring tools','Bibliography compilation assistance','Source comparison insights'] as $item): ?>
          <li><?= $item ?></li>
          <?php endforeach; ?>
        </ul>
        <div class="pb-badge-row">
          <?php foreach (['APA','MLA','Chicago','Harvard'] as $s): ?>
          <span class="pb-badge"><?= $s ?></span>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Block 3: Global Data -->
      <div class="pb-feat-card">
        <div class="pb-feat-card-tag">
          <div class="icon"><i class="fas fa-globe"></i></div>
          <span class="label">Global Intelligence</span>
        </div>
        <h3>Global Research Data Access</h3>
        <p class="sub">Structured access to datasets from leading global institutions.</p>
        <div class="pb-badge-row" style="margin-bottom:16px">
          <?php foreach (['WHO','World Bank','IMF','UNESCO','OECD','FAO'] as $org): ?>
          <span class="pb-badge-gray"><?= $org ?></span>
          <?php endforeach; ?>
        </div>
        <p style="font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--text-gray);margin-bottom:10px">Comparative data across:</p>
        <ul class="pb-feat-list">
          <?php foreach (['Public health & climate change','Economics & education','Infrastructure & agriculture','Development indicators'] as $item): ?>
          <li><?= $item ?></li>
          <?php endforeach; ?>
        </ul>
      </div>

    </div>

    <div class="pb-center-btn">
      <a href="dashboard_publeesh.php" class="pb-btn-accent-outline">Start Publishing</a>
    </div>
  </div>
</section>

<!-- How Publeesh Fits Into Afrika Scholar -->
<section class="pb-section">
  <div class="container">
    <div class="pb-section-head">
      <p class="eyebrow">How Publeesh Ai Fits Into Afrika Scholar</p>
      <h2>Pan-African Academic Publishing, Research &amp;<br class="hidden-mobile"> University Enablement Infrastructure</h2>
      <p>with AI-Powered Research Intelligence. Publishing remains the primary pillar — Publeesh Ai strengthens it.</p>
    </div>

    <div class="pb-cards-2" style="margin-bottom:40px">

      <!-- Strengthens Publishing -->
      <div class="pb-card-white">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:6px">
          <div style="width:36px;height:36px;border-radius:8px;background:rgba(232,101,26,.10);display:flex;align-items:center;justify-content:center;color:var(--orange)"><i class="fas fa-layer-group"></i></div>
          <h3 style="margin:0;font-size:17px;font-weight:700;color:var(--navy)">Publeesh Ai Strengthens Publishing</h3>
        </div>
        <p class="sub" style="font-size:13px;color:var(--text-gray);margin:0 0 20px">AI enhances scholarship — it does not replace it.</p>
        <ul class="pb-feat-list">
          <?php foreach ([
            'Improving manuscript quality before submission',
            'Supporting clearer structuring and argumentation',
            'Enhancing data-backed research',
            'Supporting academic productivity',
            'Enabling comparative and cross-country scholarship',
          ] as $item): ?>
          <li><?= $item ?></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Who It's For -->
      <div class="pb-card-white">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:6px">
          <div style="width:36px;height:36px;border-radius:8px;background:rgba(232,101,26,.10);display:flex;align-items:center;justify-content:center;color:var(--orange)"><i class="fas fa-users"></i></div>
          <h3 style="margin:0;font-size:17px;font-weight:700;color:var(--navy)">Who Publeesh Ai Is For</h3>
        </div>
        <p class="sub" style="font-size:13px;color:var(--text-gray);margin:0 0 20px">Designed for every stage of academic research.</p>
        <ul class="pb-who-list">
          <?php foreach ([
            ['Students',      'preparing research papers, dissertations, and theses'],
            ['Academics',     'developing manuscripts for publication'],
            ['Researchers',   'conducting comparative policy and data studies'],
            ['Institutions',  'seeking research productivity tools'],
            ['Professionals', 'translating practice into credible research output'],
          ] as $row): ?>
          <li><strong><?= $row[0] ?></strong>&nbsp;<?= $row[1] ?></li>
          <?php endforeach; ?>
        </ul>
        <div class="pb-center-btn" style="margin:16px 0 0">
          <a href="dashboard_publeesh.php" class="pb-btn-accent-outline">Start Publishing</a>
        </div>
      </div>
    </div>

    <!-- Academic Integrity Banner -->
    <div class="pb-integrity">
      <div class="pb-integrity-grid">
        <div class="pb-integrity-icon"><i class="fas fa-shield-alt"></i></div>
        <div>
          <h3>Academic Integrity & Responsible Use</h3>
          <p>Publeesh Ai is designed as a research support system. Users are responsible for ensuring compliance with university academic integrity policies, journal submission standards, and ethical research guidelines.</p>
          <div class="pb-integrity-cols">
            <div>
              <p class="pb-integrity-label">Publeesh Ai does not:</p>
              <ul class="pb-no-list">
                <?php foreach (['Replace independent scholarship','Guarantee publication','Substitute peer review','Bypass institutional supervision'] as $item): ?>
                <li>
                  <span class="pb-no-icon"><i class="fas fa-times" style="font-size:9px"></i></span>
                  <?= $item ?>
                </li>
                <?php endforeach; ?>
              </ul>
            </div>
            <div>
              <p class="pb-integrity-label">Users must comply with:</p>
              <ul class="pb-feat-list">
                <?php foreach (['University academic integrity policies','Journal submission standards','Ethical research guidelines'] as $item): ?>
                <li><?= $item ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
          <p style="font-size:13px;color:var(--text-gray);margin-bottom:16px">Afrika Scholar promotes responsible AI usage aligned with global academic norms.</p>
          <a href="academic_integrity.php" class="pb-integrity-btn">
            Read Full Academic Integrity Policy <i class="fas fa-arrow-right" style="font-size:12px"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- How It Works + Dashboard + Subscription + Why Publeesh (dark section) -->
<section class="pb-section pb-section-dark">
  <div class="pb-section-dark-dots">
    <svg viewBox="0 0 100 100" preserveAspectRatio="none" style="width:100%;height:100%">
      <defs><pattern id="dark-diamond" width="20" height="20" patternUnits="userSpaceOnUse"><path d="M0 10 L10 0 L20 10 L10 20Z" fill="none" stroke="white" stroke-width="0.3"/></pattern></defs>
      <rect width="100" height="100" fill="url(#dark-diamond)"/>
    </svg>
  </div>
  <div class="container" style="position:relative;z-index:1">

    <!-- How It Works -->
    <div class="pb-section-head">
      <div style="font-size:36px;color:var(--orange);margin-bottom:16px">✦</div>
      <h2 style="color:#fff">How It Works</h2>
      <p>All within a unified academic dashboard.</p>
    </div>

    <div class="pb-how-steps">
      <?php foreach ([
        ['1','Create or log in to your Afrika Scholar account'],
        ['2','Subscribe to Research Intelligence Access'],
        ['3','Start a Research Project Workspace'],
        ['4','Generate structured drafts & retrieve global datasets'],
        ['5','Export structured documents for refinement and supervision'],
      ] as $i => $s): ?>
      <div class="pb-how-step">
        <div class="pb-how-num"><?= $s[0] ?></div>
        <?php if ($i < 4): ?><div class="pb-how-connector"></div><?php endif; ?>
        <p><?= $s[1] ?></p>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Dashboard + Subscription -->
    <div class="pb-cards-2" style="margin-bottom:56px">

      <!-- Dashboard -->
      <div class="pb-dark-card" style="display:flex;flex-direction:column">
        <div class="pb-dark-icon-row">
          <div class="pb-dark-icon"><i class="fas fa-th-large"></i></div>
          <h3 style="margin:0;font-size:17px">Publeesh Ai Dashboard Features</h3>
        </div>
        <p class="sub" style="font-size:13px;color:rgba(255,255,255,.55);margin:6px 0 20px">Designed for structured workflow, not chaos.</p>
        <ul class="pb-dark-list">
          <?php foreach ([
            'Project Workspace Management',
            'Structured Outline Generator',
            'Literature Review Assistant',
            'Citation Formatter',
            'Global Dataset Explorer',
            'Saved References Library',
            'Comparative Analysis Builder',
            'Export to Word / PDF',
            'Academic Integrity Reminder',
          ] as $f): ?>
          <li><?= $f ?></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Subscription -->
      <div class="pb-dark-card" style="display:flex;flex-direction:column">
        <div class="pb-dark-icon-row">
          <div class="pb-dark-icon"><i class="fas fa-credit-card"></i></div>
          <h3 style="margin:0;font-size:17px">Subscription Access</h3>
        </div>
        <p class="sub" style="font-size:13px;color:rgba(255,255,255,.55);margin:6px 0 20px">Research Intelligence access is available through subscription plans.</p>
        <p class="pb-dark-card-label">Plans may include:</p>
        <ul class="pb-dark-list" style="margin-bottom:28px">
          <?php foreach (['Writing & Structuring Access','Global Dataset Access','Advanced Comparative Intelligence','Institutional Licenses'] as $p): ?>
          <li><?= $p ?></li>
          <?php endforeach; ?>
        </ul>
        <div style="display:flex;flex-direction:column;gap:10px;margin-top:auto">
          <a href="subscribe.php" class="pb-dark-btn">
            View Subscription Plans <i class="fas fa-arrow-right"></i>
          </a>
          <a href="institutional_access.php" class="pb-dark-btn-outline">
            Request Institutional Access <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </div>

    <!-- Why Publeesh Matters -->
    <div style="text-align:center;margin-bottom:32px">
      <h3 style="font-size:clamp(1.4rem,3vw,1.8rem);font-weight:800;color:#fff;margin-bottom:10px">Why Publeesh Ai Matters</h3>
      <p style="color:rgba(255,255,255,.65);font-size:15px">Without compromising academic integrity.</p>
    </div>

    <div class="pb-why-grid">
      <div class="pb-why-challenges">
        <p style="font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.45);margin-bottom:16px">African scholars often face:</p>
        <?php foreach (['Limited access to structured research tools','Difficulty accessing consolidated global datasets','Fragmented research workflow systems','Time inefficiencies in drafting and structuring'] as $item): ?>
        <div class="pb-no-item">
          <span class="pb-no-circle"><i class="fas fa-times" style="font-size:8px"></i></span>
          <?= $item ?>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="pb-why-empower">
        <p style="font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--orange);margin-bottom:16px">Publeesh Ai empowers scholars to:</p>
        <?php foreach ([
          ['Work smarter',    'with structured AI-assisted workflows'],
          ['Structure better','through guided drafting and outlining'],
          ['Cite responsibly','with multi-format referencing tools'],
          ['Compare globally','using institutional datasets'],
          ['Publish confidently','with stronger, cleaner manuscripts'],
        ] as $row): ?>
        <div class="pb-empower-item">
          <strong><?= $row[0] ?></strong>&nbsp;<span><?= $row[1] ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</section>

<!-- Future Roadmap + CTA -->
<section class="pb-section pb-section-muted">
  <div class="container">
    <div class="pb-section-head">
      <p class="eyebrow">What's Coming</p>
      <h2>The Future of Research Intelligence in Africa</h2>
      <p>As Afrika Scholar expands its publishing infrastructure and institutional partnerships, Publeesh Ai will evolve.</p>
    </div>

    <div class="pb-future-grid">
      <?php foreach ([
        ['fas fa-th-large',       'Institutional Research Dashboards',      'University-level dashboards for tracking research output, productivity, and publication pipelines.'],
        ['fas fa-users',          'Cross-Institutional Collaboration',       'Tools enabling structured academic partnerships and co-authorship coordination across institutions.'],
        ['fas fa-chart-bar',      'Research Trend Analytics',                'Real-time analytics surfacing emerging research areas, citation trends, and knowledge gaps across Africa.'],
        ['fas fa-chart-bar',      'Impact Tracking Tools',                   'Monitor research visibility, citations, and scholarly reach across global academic platforms.'],
        ['fas fa-shield-alt',     'AI-Assisted Peer Review Support',         'Ethical and structured assistance to improve review quality — not to replace reviewers.'],
        ['fas fa-graduation-cap', 'Research Supervision Assist',             'Modules supporting academic supervisors and postgraduate students through structured research workflows.'],
      ] as $f): ?>
      <div class="pb-future-card">
        <div class="pb-future-icon"><i class="<?= $f[0] ?>"></i></div>
        <h4><?= $f[1] ?></h4>
        <p><?= $f[2] ?></p>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="pb-tags">
      <?php foreach (['Built responsibly.','Built institutionally.','Built for long-term scholarly infrastructure.'] as $tag): ?>
      <span class="pb-tag"><?= $tag ?></span>
      <?php endforeach; ?>
    </div>

    <!-- CTA card -->
    <div class="pb-cta-card">
      <div class="pb-cta-card-dots"></div>
      <div class="pb-cta-card-body">
        <div style="font-size:36px;color:var(--orange);margin-bottom:16px">✦</div>
        <h3>Ready to Enhance Your Research Workflow?</h3>
        <p>Join scholars and institutions already building smarter, more credible research with Publeesh Ai.</p>
        <div class="pb-cta-btns">
          <a href="publications.php" class="pb-cta-btn-orange">
            Access Research Intelligence <i class="fas fa-arrow-right"></i>
          </a>
          <a href="subscribe.php" class="pb-cta-btn-outline">Subscribe to Publeesh Ai</a>
          <a href="advisory.php" class="pb-cta-btn-outline">Request Institutional Demo</a>
        </div>
      </div>
    </div>

  </div>
</section>

<?php include 'footer.php'; ?>
