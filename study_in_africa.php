<?php
$page_title = 'Study in Africa';
include 'header.php';
?>

<style>
/* ── Breadcrumb ── */
.sia-breadcrumb{background:rgba(0,0,0,.04);border-bottom:1px solid var(--border)}
.sia-breadcrumb .inner{padding:10px 0;display:flex;align-items:center;gap:8px;font-size:14px;color:var(--text-gray)}
.sia-breadcrumb a{color:var(--text-gray);text-decoration:none}.sia-breadcrumb a:hover{color:var(--navy)}
.sia-breadcrumb .sep{font-size:12px;opacity:.5}
.sia-breadcrumb .current{color:var(--navy)}

/* ── Hero ── */
.sia-hero{position:relative;overflow:hidden;min-height:400px;display:flex;align-items:center;justify-content:center}
.sia-hero-bg{position:absolute;inset:0;background:url('asset/about-conference.jpg') center/cover no-repeat}
.sia-hero-overlay{position:absolute;inset:0;background:rgba(10,25,60,.85)}
.sia-hero-dots{position:absolute;inset:0;opacity:.10;background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:24px 24px}
.sia-hero-content{position:relative;z-index:1;text-align:center;padding:80px 20px;max-width:700px;margin:0 auto}
.sia-coming-badge{display:inline-flex;align-items:center;gap:8px;background:rgba(232,101,26,.20);color:var(--orange);padding:6px 18px;border-radius:30px;font-size:13px;font-weight:600;margin-bottom:24px}
.sia-hero h1{color:#fff;font-size:clamp(2rem,5vw,3rem);font-weight:800;margin:0 0 20px}
.sia-hero p{color:rgba(255,255,255,.8);font-size:17px;margin:0}

/* ── Section helpers ── */
.sia-section{padding:72px 0}
.sia-section-muted{background:#f8f9ff;border-top:1px solid var(--border)}
.sia-section-dark{background:var(--navy);position:relative;overflow:hidden}
.container{max-width:1200px;margin:0 auto;padding:0 24px}
.sia-section-head{text-align:center;margin-bottom:48px}
.sia-section-head h2{font-size:clamp(1.6rem,3vw,2.2rem);font-weight:800;color:var(--navy);margin:0 0 12px}
.sia-section-head p{color:var(--text-gray);font-size:15px;max-width:560px;margin:0 auto}

/* ── Feature cards grid ── */
.sia-features-wrap{max-width:860px;margin:0 auto 48px}
.sia-features{display:grid;grid-template-columns:1fr 1fr;gap:16px}
@media(max-width:640px){.sia-features{grid-template-columns:1fr}}
.sia-feature-card{background:#fff;border:1px solid var(--border);border-radius:12px;padding:20px 24px;display:flex;align-items:center;gap:16px;transition:box-shadow .2s,transform .2s}
.sia-feature-card:hover{box-shadow:0 6px 24px rgba(10,25,60,.08);transform:translateY(-2px)}
.sia-feature-num{width:40px;height:40px;border-radius:50%;background:rgba(232,101,26,.10);display:flex;align-items:center;justify-content:center;color:var(--orange);font-size:15px;font-weight:700;flex-shrink:0}
.sia-feature-card span{font-size:14px;font-weight:600;color:var(--navy)}

/* ── Map placeholder card ── */
.sia-map-wrap{max-width:860px;margin:0 auto}
.sia-map-card{background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden}
.sia-map-card-head{padding:24px;text-align:center;border-bottom:1px solid var(--border)}
.sia-map-card-title{display:flex;align-items:center;justify-content:center;gap:8px;font-size:16px;font-weight:700;color:var(--navy);margin:0 0 6px}
.sia-map-card-title i{color:var(--orange)}
.sia-map-card-sub{font-size:14px;color:var(--text-gray);margin:0}
.sia-map-placeholder{aspect-ratio:16/9;background:rgba(0,0,0,.04);display:flex;align-items:center;justify-content:center;margin:32px}
.sia-map-placeholder-inner{text-align:center}
.sia-map-placeholder-inner i{font-size:56px;color:var(--text-gray);opacity:.5;display:block;margin-bottom:16px}
.sia-map-placeholder-inner p{font-size:14px;color:var(--text-gray);margin:0}

/* ── CTA / Register Interest ── */
.sia-cta{text-align:center;padding:80px 24px;position:relative;z-index:1}
.sia-cta i.sia-cta-icon{font-size:40px;color:var(--orange);margin-bottom:24px;display:block}
.sia-cta h2{color:#fff;font-size:clamp(1.6rem,3vw,2.2rem);font-weight:800;margin:0 0 16px}
.sia-cta p{color:rgba(255,255,255,.75);font-size:16px;max-width:500px;margin:0 auto 32px}
.sia-cta-form{display:flex;gap:10px;max-width:440px;margin:0 auto}
@media(max-width:520px){.sia-cta-form{flex-direction:column}}
.sia-cta-form input{flex:1;padding:11px 16px;border-radius:8px;border:1px solid rgba(255,255,255,.25);background:rgba(255,255,255,.10);color:#fff;font-size:14px;outline:none}
.sia-cta-form input::placeholder{color:rgba(255,255,255,.5)}
.sia-cta-form input:focus{border-color:rgba(255,255,255,.5)}
.sia-cta-form .btn-notify{display:inline-flex;align-items:center;gap:8px;padding:11px 24px;background:var(--orange);color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;text-decoration:none;white-space:nowrap;transition:background .2s}
.sia-cta-form .btn-notify:hover{background:rgba(232,101,26,.85)}
</style>

<!-- Breadcrumb -->
<div class="sia-breadcrumb">
  <div class="container">
    <div class="inner">
      <a href="advisory.php">Advisory</a>
      <span class="sep"><i class="fas fa-chevron-right"></i></span>
      <span class="current">Study in Africa</span>
    </div>
  </div>
</div>

<!-- Hero -->
<section class="sia-hero">
  <div class="sia-hero-bg"></div>
  <div class="sia-hero-overlay"></div>
  <div class="sia-hero-dots"></div>
  <div class="sia-hero-content">
    <div class="sia-coming-badge">
      <i class="fas fa-bell"></i>
      Coming Soon
    </div>
    <h1>Study in Africa</h1>
    <p>Explore academic opportunities across the African continent. Study abroad without leaving Africa.</p>
  </div>
</section>

<!-- What We're Building -->
<section class="sia-section">
  <div class="container">
    <div class="sia-section-head">
      <h2>What We're Building</h2>
      <p>A comprehensive platform to support academic mobility across African universities, making it easier to study in different African countries.</p>
    </div>

    <?php
    $features = [
      'Cross-border student mobility programs',
      'University exchange partnerships',
      'Visa and documentation guidance',
      'Scholarship matching services',
      'Cultural orientation resources',
      'Alumni network connections',
    ];
    ?>

    <div class="sia-features-wrap">
      <div class="sia-features">
        <?php foreach ($features as $i => $f): ?>
        <div class="sia-feature-card">
          <div class="sia-feature-num"><?= $i + 1 ?></div>
          <span><?= $f ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Map placeholder card -->
    <div class="sia-map-wrap">
      <div class="sia-map-card">
        <div class="sia-map-card-head">
          <div class="sia-map-card-title">
            <i class="fas fa-map-marker-alt"></i>
            Destination Countries
          </div>
          <p class="sia-map-card-sub">Partnering with universities across the continent</p>
        </div>
        <div class="sia-map-placeholder">
          <div class="sia-map-placeholder-inner">
            <i class="fas fa-globe-africa"></i>
            <p>Interactive map coming soon</p>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- Register Interest -->
<section class="sia-section-dark">
  <div class="sia-cta">
    <i class="fas fa-globe sia-cta-icon"></i>
    <h2>Register Your Interest</h2>
    <p>Be the first to know when Study in Africa launches. Get early access and exclusive updates.</p>
    <form method="POST" action="footer.php" class="sia-cta-form">
      <input type="email" name="newsletter_email" placeholder="Enter your email" required>
      <button type="submit" class="btn-notify">
        Notify Me <i class="fas fa-arrow-right"></i>
      </button>
    </form>
  </div>
</section>

<?php include 'footer.php'; ?>
